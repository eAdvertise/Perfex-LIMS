<?php defined('BASEPATH') or exit('No direct script access allowed');

class Culture_options_model extends App_Model
{
    protected $sets_table;
    protected $vals_table;
    protected $links_table;

    public function __construct()
    {
        parent::__construct();
        $this->sets_table  = db_prefix().'lims_culture_option_sets';
        $this->vals_table  = db_prefix().'lims_culture_option_values';
        $this->links_table = db_prefix().'lims_culture_option_links';
    }

    /** Όλα τα sets (προαιρετικά μόνο active) */
    public function all_sets($only_active = false)
    {
        if ($only_active) {
            $this->db->where('active', 1);
        }
        return $this->db->order_by('name','ASC')->get($this->sets_table)->result();
    }

    /** Ένα set */
    public function get_set($id)
    {
        return $this->db->where('id',(int)$id)->get($this->sets_table)->row();
    }

    /** Values για set */
    public function get_values($set_id)
    {
        return $this->db->where('set_id',(int)$set_id)
                        ->order_by('sort_order','ASC')
                        ->order_by('id','ASC')
                        ->get($this->vals_table)->result();
    }

    /** Create / Update set + values */
    public function save_set($data, $values, $id = null)
    {
        $setData = [
            'name'        => trim($data['name'] ?? ''),
            'code'        => trim($data['code'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'active'      => !empty($data['active']) ? 1 : 0,
        ];

        if ($id) {
            $this->db->where('id',(int)$id)->update($this->sets_table, $setData);
            $set_id = (int)$id;
        } else {
            $this->db->insert($this->sets_table, $setData);
            $set_id = (int)$this->db->insert_id();
        }

        // Values: περιμένουμε arrays value[], label[], sort_order[]
        $vals = $values ?? [];
        $value_arr = $vals['value'] ?? [];
        $label_arr = $vals['label'] ?? [];
        $sort_arr  = $vals['sort_order'] ?? [];

        // Καθάρισε παλιά
        $this->db->where('set_id', $set_id)->delete($this->vals_table);

        if (!empty($value_arr) && is_array($value_arr)) {
            foreach ($value_arr as $idx => $val) {
                $val   = trim($val);
                $label = isset($label_arr[$idx]) ? trim($label_arr[$idx]) : '';
                if ($val === '' && $label === '') {
                    continue;
                }
                $sort  = isset($sort_arr[$idx]) && $sort_arr[$idx] !== ''
                       ? (int)$sort_arr[$idx] : ($idx+1)*10;

                $this->db->insert($this->vals_table, [
                    'set_id'     => $set_id,
                    'value'      => $val !== '' ? $val : strtoupper(preg_replace('/\s+/', '_', $label)),
                    'label'      => $label !== '' ? $label : $val,
                    'sort_order' => $sort,
                    'active'     => 1,
                ]);
            }
        }

        return $set_id;
    }

    public function delete_set($id)
    {
        $id = (int)$id;
        // values θα φύγουν με cascade
        $this->db->where('id',$id)->delete($this->sets_table);
        return ($this->db->affected_rows() > 0);
    }

    /** Για cultures: φέρε active sets + values grouped */
    public function get_sets_with_values()
    {
        $sets = $this->all_sets(true);
        $out  = [];
        foreach ($sets as $s) {
            $out[$s->id] = [
                'set'    => $s,
                'values' => $this->get_values($s->id),
            ];
        }
        return $out;
    }

    /** Links: ποιά sets ισχύουν για συγκεκριμένο culture */
    public function get_culture_set_ids($culture_id)
    {
        $rows = $this->db->select('set_id')
                         ->where('culture_id',(int)$culture_id)
                         ->get($this->links_table)->result();
        return array_map(function($r){return (int)$r->set_id;}, $rows);
    }

    public function save_culture_links($culture_id, $set_ids)
    {
        $culture_id = (int)$culture_id;
        $set_ids    = array_unique(array_map('intval', $set_ids ?: []));

        $this->db->where('culture_id',$culture_id)->delete($this->links_table);

        foreach ($set_ids as $sid) {
            if ($sid <= 0) continue;
            $this->db->insert($this->links_table, [
                'culture_id' => $culture_id,
                'set_id'     => $sid,
            ]);
        }
    }
}
