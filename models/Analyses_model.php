<?php defined('BASEPATH') or exit('No direct script access allowed');

class Analyses_model extends App_Model
{
    protected $table;
    protected $specs_table;

    public function __construct()
    {
        parent::__construct();
        $p = db_prefix();
        $this->table       = $p.'lims_analyses';
        $this->specs_table = $p.'lims_analysis_specs';
    }

    /** Get all analyses (ordered by name) */
    public function all()
    {
        return $this->db->order_by('name', 'ASC')->get($this->table)->result();
    }

    /** Get one analysis + specs (if any) */
    public function get($id)
    {
        $row = $this->db->where('id', (int)$id)->get($this->table)->row();
        if ($row) {
            // load specs list (optional UI later)
            $row->specs = $this->db->where('analysis_id', (int)$id)
                                   ->order_by('effective_from', 'DESC')
                                   ->get($this->specs_table)->result();
        }
        return $row;
    }

    /**
     * Create/Update analysis
     * Accepts optional arrays for specs (spec_* fields) – ignored if not provided.
     * @throws Exception when name is empty
     * @return int id
     */
    public function save($data, $id = null)
    {
        // Προσοχή: ΔΕΝ γράφουμε/δέχουμε item_id εδώ – αυτό το χειρίζεται ο controller με το invoice_items_model
        $rec = [
            'name'           => trim($data['name'] ?? ''),
            'code'           => trim($data['code'] ?? ''),
            'department_id'  => (int)($data['department_id'] ?? 0) ?: null,
            'sample_type_id' => (int)($data['sample_type_id'] ?? 0) ?: null, // NEW: primary specimen type
            'method'         => trim($data['method'] ?? ''),
            'tat_hours'      => ($data['tat_hours'] !== '' ? (int)$data['tat_hours'] : null),
            'result_type'    => in_array(($data['result_type'] ?? 'numeric'), ['numeric','text','select']) ? $data['result_type'] : 'numeric',
            'decimal_places' => ($data['decimal_places'] !== '' ? (int)$data['decimal_places'] : null),
            'units_ucum'     => trim($data['units_ucum'] ?? ''),
            'loinc_code'     => trim($data['loinc_code'] ?? ''),
            'active'         => isset($data['active']) ? 1 : 0,
        ];

        if ($rec['name'] === '') {
            throw new Exception('Analysis name required');
        }

        if ($id) {
            $this->db->where('id', (int)$id)->update($this->table, $rec);
        } else {
            $this->db->insert($this->table, $rec);
            $id = (int)$this->db->insert_id();
        }

        // --- Optional: replace specs if passed from form (UI μπορεί να προστεθεί αργότερα)
        if (!empty($data['spec_sample_type_id']) && is_array($data['spec_sample_type_id'])) {
            $this->db->where('analysis_id', $id)->delete($this->specs_table);

            $n = count($data['spec_sample_type_id']);
            for ($i = 0; $i < $n; $i++) {
                $st = (int)($data['spec_sample_type_id'][$i] ?? 0);
                if (!$st) { continue; }

                $this->db->insert($this->specs_table, [
                    'analysis_id'    => $id,
                    'sample_type_id' => $st,
                    'sex'            => ($data['spec_sex'][$i] ?? 'U'),
                    'age_min'        => ($data['spec_age_min'][$i] ?? '') !== '' ? (float)$data['spec_age_min'][$i] : null,
                    'age_max'        => ($data['spec_age_max'][$i] ?? '') !== '' ? (float)$data['spec_age_max'][$i] : null,
                    'ref_low'        => ($data['spec_ref_low'][$i] ?? '') !== '' ? (float)$data['spec_ref_low'][$i] : null,
                    'ref_high'       => ($data['spec_ref_high'][$i] ?? '') !== '' ? (float)$data['spec_ref_high'][$i] : null,
                    'critical_low'   => ($data['spec_crit_low'][$i] ?? '') !== '' ? (float)$data['spec_crit_low'][$i] : null,
                    'critical_high'  => ($data['spec_crit_high'][$i] ?? '') !== '' ? (float)$data['spec_crit_high'][$i] : null,
                    'unit_ucum'      => trim($data['spec_unit_ucum'][$i] ?? ''),
                    'version'        => ($data['spec_version'][$i] ?? '') !== '' ? (int)$data['spec_version'][$i] : null,
                    'effective_from' => ($data['spec_eff_from'][$i] ?? '') ?: null,
                    'effective_to'   => ($data['spec_eff_to'][$i] ?? '') ?: null,
                    'active'         => 1,
                ]);
            }
        }

        return $id;
    }

    /** Delete analysis (and its specs) */
    public function delete($id)
    {
        $id = (int)$id;

        // σβήσε τυχόν specs
        $this->db->where('analysis_id',$id)->delete(db_prefix().'lims_analysis_specs');

        $this->db->where('id',$id)->delete(db_prefix().'lims_analyses');
        return $this->db->affected_rows() > 0;
    }

    /** Toggle active */
    public function set_active($id, $active)
    {
        $this->db->where('id', (int)$id)->update($this->table, ['active' => $active ? 1 : 0]);
        return $this->db->affected_rows() >= 0;
    }

    public function get_many_by_ids($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        // Καθάρισε input: κράτα μόνο θετικά integers & unique
        $ids = array_values(array_unique(array_filter(array_map(function($v){
            return (int)$v;
        }, $ids), function($v){ return $v > 0; })));

        if (empty($ids)) {
            return [];
        }

        return $this->db
            ->where_in('id', $ids)
            ->order_by('name', 'ASC')
            ->get(db_prefix() . 'lims_analyses')
            ->result();
    }
	
    /**
     * Επιστρέφει όλα τα specs για ένα analysis
     */
    public function get_specs($analysis_id)
    {
        return $this->db->where('analysis_id', (int)$analysis_id)
            ->order_by('id','ASC')
            ->get(db_prefix().'lims_analysis_specs')
            ->result();
    }

    /**
     * Αποθηκεύει reference ranges.
     * Διαγράφει τα παλιά και ξαναβάζει όσα ήρθαν από τη φόρμα.
     */
    public function save_specs($analysis_id, $specPost, $sampleTypeId = null, $unitsUcum = null)
    {
        $analysis_id  = (int)$analysis_id;
        $sampleTypeId = $sampleTypeId ? (int)$sampleTypeId : 0;

        // καθάρισε παλιά
        $this->db->where('analysis_id', $analysis_id)
                 ->delete(db_prefix().'lims_analysis_specs');

        if (!is_array($specPost) || empty($specPost['sex'])) {
            return;
        }

        $rows = [];

        $sex        = $specPost['sex'];
        $age_min    = isset($specPost['age_min'])        ? $specPost['age_min']        : [];
        $age_max    = isset($specPost['age_max'])        ? $specPost['age_max']        : [];
        $crit_low   = isset($specPost['critical_low'])   ? $specPost['critical_low']   : [];
        $norm_low   = isset($specPost['ref_low'])        ? $specPost['ref_low']        : [];
        $norm_high  = isset($specPost['ref_high'])       ? $specPost['ref_high']       : [];
        $crit_high  = isset($specPost['critical_high'])  ? $specPost['critical_high']  : [];

        $count = count($sex);

        for ($i=0; $i<$count; $i++) {
            $s = isset($sex[$i]) ? trim($sex[$i]) : 'U';

            $aMin = isset($age_min[$i])   && $age_min[$i]   !== '' ? (float)$age_min[$i]   : null;
            $aMax = isset($age_max[$i])   && $age_max[$i]   !== '' ? (float)$age_max[$i]   : null;
            $cLo  = isset($crit_low[$i])  && $crit_low[$i]  !== '' ? (float)$crit_low[$i]  : null;
            $nLo  = isset($norm_low[$i])  && $norm_low[$i]  !== '' ? (float)$norm_low[$i]  : null;
            $nHi  = isset($norm_high[$i]) && $norm_high[$i] !== '' ? (float)$norm_high[$i] : null;
            $cHi  = isset($crit_high[$i]) && $crit_high[$i] !== '' ? (float)$crit_high[$i] : null;

            // Αν είναι εντελώς κενή γραμμή, αγνόησέ την
            if ($aMin === null && $aMax === null && $cLo === null && $nLo === null && $nHi === null && $cHi === null) {
                continue;
            }

            $rows[] = [
                'analysis_id'   => $analysis_id,
                'sample_type_id'=> $sampleTypeId ?: 0,
                'sex'           => in_array($s, ['U','M','F']) ? $s : 'U',
                'age_min'       => $aMin,
                'age_max'       => $aMax,
                'ref_low'       => $nLo,
                'ref_high'      => $nHi,
                'critical_low'  => $cLo,
                'critical_high' => $cHi,
                'unit_ucum'     => $unitsUcum ?: null,
                'active'        => 1,
            ];
        }

        if (!empty($rows)) {
            $this->db->insert_batch(db_prefix().'lims_analysis_specs', $rows);
        }
    }
}
