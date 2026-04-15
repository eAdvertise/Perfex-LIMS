<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cultures_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix().'lims_cultures';
    }

    /** Λίστα (με ονόματα Sample Type & Culture Type) */
    public function all()
    {
        $st = db_prefix().'lims_sample_types';
        $ct = db_prefix().'lims_culture_types';
        return $this->db
            ->select("c.*,
                      st.name AS sample_type_name,
                      ct.name AS culture_type_name")
            ->from($this->table.' AS c')
            ->join($st.' AS st','st.id = c.sample_type_id','left')
            ->join($ct.' AS ct','ct.id = c.culture_type_id','left')
            ->order_by('c.name','ASC')
            ->get()->result();
    }

    public function get($id)
    {
        return $this->db->where('id',(int)$id)->get($this->table)->row();
    }

    public function save($data, $id = null)
    {
        $rec = [
            'name'            => trim($data['name'] ?? ''),
            'code'            => trim($data['code'] ?? ''),
            'sample_type_id'  => (int)($data['sample_type_id'] ?? 0) ?: null,
            'culture_type_id' => (int)($data['culture_type_id'] ?? 0) ?: null,
            'method'          => trim($data['method'] ?? ''),
            'tat_hours'       => ($data['tat_hours'] !== '' ? (int)$data['tat_hours'] : null),
            'item_id'         => ($data['item_id'] ?? '') !== '' ? (int)$data['item_id'] : null,
            'active'          => isset($data['active']) ? 1 : 0,
        ];
        if ($rec['name'] === '') { throw new Exception('Name required'); }

        if ($id) {
            $this->db->where('id',(int)$id)->update($this->table,$rec);
        } else {
            $this->db->insert($this->table,$rec);
            $id = (int)$this->db->insert_id();
        }
        return $id;
    }

    public function delete($id)
    {
        $this->db->where('id',(int)$id)->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    public function set_active($id, $active)
    {
        $this->db->where('id',(int)$id)->update($this->table, ['active'=>$active?1:0]);
        return $this->db->affected_rows() >= 0;
    }
}
