<?php defined('BASEPATH') or exit('No direct script access allowed');

class Departments_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix().'lims_departments';
    }

    public function all()
    {
        return $this->db->order_by('name','ASC')->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->db->where('id',(int)$id)->get($this->table)->row();
    }

    public function save($data, $id=null)
    {
        $rec = [
            'name'   => trim($data['name'] ?? ''),
            'code'   => trim($data['code'] ?? ''),
            'active' => isset($data['active']) ? 1 : 0,
        ];
        if ($rec['name']==='') { throw new Exception('Department name required'); }

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

    public function set_active($id,$active)
    {
        $this->db->where('id',(int)$id)->update($this->table, ['active'=>$active?1:0]);
        return $this->db->affected_rows() >= 0;
    }
}
