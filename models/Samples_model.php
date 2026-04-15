<?php defined('BASEPATH') or exit('No direct script access allowed');

class Samples_model extends App_Model
{
    protected $tbl_samples;
    protected $tbl_orders;
    protected $tbl_barcode_sequences;
    protected $tbl_sample_types; // <-- νέο: σωστός πίνακας για sample types

    public function __construct()
    {
        parent::__construct();
        $p = db_prefix();
        $this->tbl_samples           = $p.'lims_samples';
        $this->tbl_orders            = $p.'lims_orders';
        $this->tbl_barcode_sequences = $p.'lims_barcode_sequences';
        $this->tbl_sample_types      = $p.'lims_sample_types';
    }

    /* -------------------------------------------------
     * SAMPLES (κύρια οντότητα)
     * ------------------------------------------------- */

    /** Λίστα Samples με joined sample type (για index/list) */
    public function all()
    {
        $p = db_prefix();
        return $this->db->select("s.*, st.name AS sample_type_name, st.code AS sample_type_code")
                        ->from("{$p}lims_samples AS s")
                        ->join("{$p}lims_sample_types AS st", "st.id = s.sample_type_id", "left")
                        ->order_by('s.id','DESC')
                        ->get()->result();
    }

    /** Ένα sample */
    public function get($id)
    {
        return $this->db->where('id', (int)$id)->get($this->tbl_samples)->row();
    }

    /** Δημιουργία sample (public) */
    public function add(array $data)
    {
        // Normalization
        $row = [
            'order_id'       => isset($data['order_id']) ? (int)$data['order_id'] : null,
            'appointment_id' => !empty($data['appointment_id']) ? (int)$data['appointment_id'] : null,
            'sample_type_id' => !empty($data['sample_type_id']) ? (int)$data['sample_type_id'] : null,
            'status'         => !empty($data['status']) ? (string)$data['status'] : 'pending',
            'notes'          => isset($data['notes']) ? (string)$data['notes'] : null,
        ];

        // Datetimes normalize
        foreach (['received_at','collected_at'] as $dt) {
            if (!empty($data[$dt])) {
                $ts = strtotime($data[$dt]);
                $row[$dt] = $ts ? date('Y-m-d H:i:s', $ts) : null;
            } else {
                $row[$dt] = null;
            }
        }

        // UID
        $row['sample_uid'] = !empty($data['sample_uid'])
            ? (string)$data['sample_uid']
            : $this->generate_sample_uid((int)$row['order_id']);

        // Barcode: αν δεν δόθηκε, πάρε/εξασφάλισε του Order
        if (!empty($data['barcode'])) {
            $row['barcode'] = (string)$data['barcode'];
        } else {
            if (!empty($row['order_id'])) {
                $order = $this->db->where('id',(int)$row['order_id'])->get($this->tbl_orders)->row();
                if ($order) {
                    $row['barcode'] = $this->ensure_order_barcode($order);
                }
            }
        }

        $row['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->tbl_samples, $row);
        return (int)$this->db->insert_id();
    }

    /** Alias για συμβατότητα με controller */
    public function create(array $data) { return $this->add($data); }

    /** Ενημέρωση sample */
    public function update($id, array $data)
    {
        $upd = [];

        // επιτρεπτά πεδία
        $fields = [
            'appointment_id','sample_type_id','status','notes',
            'received_at','collected_at','barcode','sample_uid'
        ];
        foreach ($fields as $f) {
            if (array_key_exists($f, $data)) {
                if (in_array($f, ['received_at','collected_at'], true)) {
                    $val = $data[$f];
                    if ($val === '' || $val === null) {
                        $upd[$f] = null;
                    } else {
                        $ts = strtotime($val);
                        $upd[$f] = $ts ? date('Y-m-d H:i:s', $ts) : null;
                    }
                } else {
                    $upd[$f] = $data[$f];
                }
            }
        }

        $upd['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int)$id)->update($this->tbl_samples, $upd);
        return $this->db->affected_rows() >= 0;
    }

    /** Θέσε status */
    public function update_status($id, $status)
    {
        $this->db->where('id',(int)$id)->update($this->tbl_samples, [
            'status'     => (string)$status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows() >= 0;
    }

    /** Θέσε Collected + timestamp */
    public function set_collected($id, $ts = null)
    {
        $dt = $ts ? (strtotime($ts) ? date('Y-m-d H:i:s', strtotime($ts)) : null) : date('Y-m-d H:i:s');
        $this->db->where('id',(int)$id)->update($this->tbl_samples, [
            'status'       => 'collected',
            'collected_at' => $dt,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows() >= 0;
    }

    /**
     * Δημιουργεί samples για το order, με ΚΟΙΝΟ barcode με το order.
     * - Ensure order_barcode
     * - Βρίσκει sample_type_ids από order items (panels -> analyses)
     * - Δημιουργεί 1 sample για κάθε sample_type_id (ή 1 generic αν δεν βρεθεί)
     */
    public function generate_for_order($order_id)
    {
        $order_id = (int)$order_id;
        if ($order_id <= 0) { return false; }

        $order = $this->db->where('id', $order_id)->get($this->tbl_orders)->row();
        if (!$order) { return false; }

        // 1) Barcode στο order
        $barcode = $this->ensure_order_barcode($order);

        // 2) Items
        $oi = $this->db->where('order_id', $order_id)->get(db_prefix().'lims_order_items')->result();
        if (!$oi) { $oi = []; }

        // 3) Sample types από items
        $sampleTypeIds = [];

        $panelAnalyses = function($panel_id) {
            return $this->db->select('a.id, a.sample_type_id')
                            ->from(db_prefix().'lims_panel_items pi')
                            ->join(db_prefix().'lims_analyses a', 'a.id = pi.analysis_id', 'left')
                            ->where('pi.panel_id', (int)$panel_id)->get()->result();
        };

        foreach ($oi as $row) {
            if ($row->source_type === 'analysis') {
                $a = $this->db->where('id', (int)$row->source_id)->get(db_prefix().'lims_analyses')->row();
                if ($a && !empty($a->sample_type_id)) {
                    $sampleTypeIds[] = (int)$a->sample_type_id;
                }
            } elseif ($row->source_type === 'panel') {
                $list = $panelAnalyses((int)$row->source_id);
                foreach ($list as $a) {
                    if (!empty($a->sample_type_id)) {
                        $sampleTypeIds[] = (int)$a->sample_type_id;
                    }
                }
            } elseif ($row->source_type === 'culture') {
                // Optional: αν τα cultures έχουν sample_type_id, πρόσθεσέ το
                $c = $this->db->select('sample_type_id')->where('id',(int)$row->source_id)->get(db_prefix().'lims_cultures')->row();
                if ($c && !empty($c->sample_type_id)) {
                    $sampleTypeIds[] = (int)$c->sample_type_id;
                }
            }
        }

        $sampleTypeIds = array_values(array_unique(array_filter($sampleTypeIds, function($v){ return $v > 0; })));

        // 4) Δημιουργία
        if (empty($sampleTypeIds)) {
            $this->create_sample([
                'order_id'       => $order_id,
                'barcode'        => $barcode,
                'sample_type_id' => null,
                'appointment_id' => property_exists($order,'appointment_id') ? $order->appointment_id : null,
            ]);
            return true;
        }

        foreach ($sampleTypeIds as $stId) {
            $this->create_sample([
                'order_id'       => $order_id,
                'barcode'        => $barcode,
                'sample_type_id' => $stId,
                'appointment_id' => property_exists($order,'appointment_id') ? $order->appointment_id : null,
            ]);
        }
        return true;
    }

    /** Δημιουργεί ένα sample με auto-UID (εσωτερική) */
    protected function create_sample(array $data)
    {
        $uid = $this->generate_sample_uid((int)$data['order_id']);
        $rec = [
            'order_id'       => (int)$data['order_id'],
            'sample_uid'     => $uid,
            'barcode'        => isset($data['barcode']) ? (string)$data['barcode'] : null,
            'sample_type_id' => isset($data['sample_type_id']) ? (int)$data['sample_type_id'] : null,
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
        ];
        if (!empty($data['appointment_id'])) {
            $rec['appointment_id'] = (int)$data['appointment_id'];
        }
        $this->db->insert($this->tbl_samples, $rec);
        return (int)$this->db->insert_id();
    }

    /** UID μορφής S{order_id}-NN (μοναδικό ανά order) */
    public function generate_sample_uid(int $order_id): string
    {
        $order_id = max(1, $order_id);
        $count = (int)$this->db->where('order_id', $order_id)->count_all_results($this->tbl_samples);
        $n = $count + 1;
        $uid = 'S'.$order_id.'-'.str_pad((string)$n, 2, '0', STR_PAD_LEFT);

        // εξασφάλισε μοναδικότητα (σε περίπτωση race)
        while ($this->db->where('order_id',$order_id)->where('sample_uid',$uid)->count_all_results($this->tbl_samples) > 0) {
            $n++;
            $uid = 'S'.$order_id.'-'.str_pad((string)$n, 2, '0', STR_PAD_LEFT);
        }
        return $uid;
    }

    /** Επιστρέφει/εξασφαλίζει barcode στο Order (ORD00000001 κλπ.) */
    protected function ensure_order_barcode($orderRow)
    {
        if (!empty($orderRow->order_barcode)) {
            return $orderRow->order_barcode;
        }

        $prefix = 'ORD';
        // ensure sequence row
        if ($this->db->where('prefix',$prefix)->get($this->tbl_barcode_sequences)->num_rows() == 0) {
            $this->db->insert($this->tbl_barcode_sequences, ['prefix'=>$prefix,'next_number'=>1]);
        }

        // atomic increment
        $this->db->query("UPDATE `{$this->tbl_barcode_sequences}`
                          SET next_number = LAST_INSERT_ID(next_number + 1)
                          WHERE prefix = ?", [$prefix]);
        $row = $this->db->query("SELECT LAST_INSERT_ID() AS seq")->row();
        $seq = isset($row->seq) ? max(1, ((int)$row->seq) - 1) : 1;
        $code = $prefix . str_pad((string)$seq, 8, '0', STR_PAD_LEFT);

        $this->db->where('id', (int)$orderRow->id)->update($this->tbl_orders, [
            'order_barcode' => $code,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        return $code;
    }

    /* -------------------------------------------------
     * SAMPLE TYPES (διατήρηση παλαιών μεθόδων)
     * ------------------------------------------------- */

    /** Δημιουργία/Ενημέρωση Sample Type (διατήρηση API) */
    public function save($data, $id = null)
    {
        $rec = [
            'name'                    => trim($data['name'] ?? ''),
            'code'                    => trim($data['code'] ?? ''),
            'snomed_specimen_code'    => trim($data['snomed_specimen_code'] ?? ''),
            'min_volume'              => trim($data['min_volume'] ?? ''),
            'container'               => trim($data['container'] ?? ''),
            'stability_hours'         => ($data['stability_hours'] !== '' ? (int)$data['stability_hours'] : null),
            'storage_temp'            => trim($data['storage_temp'] ?? ''),
            'collection_instructions' => $data['collection_instructions'] ?? null,
            'active'                  => isset($data['active']) ? 1 : 0,
        ];

        if ($rec['name'] === '') {
            throw new Exception('Sample Type name required');
        }

        if ($id) {
            $this->db->where('id', (int)$id)->update($this->tbl_sample_types, $rec);
        } else {
            $this->db->insert($this->tbl_sample_types, $rec);
            $id = (int)$this->db->insert_id();
        }

        return $id;
    }

    /** Διαγραφή Sample Type (παλαιό API) */
    public function delete($id)
    {
        $this->db->where('id', (int)$id)->delete($this->tbl_sample_types);
        return $this->db->affected_rows() > 0;
    }

    /** Εναλλαγή active σε Sample Type (παλαιό API) */
    public function set_active($id, $active)
    {
        $this->db->where('id', (int)$id)->update($this->tbl_sample_types, ['active' => $active ? 1 : 0]);
        return $this->db->affected_rows() >= 0;
    }
}
