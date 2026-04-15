<?php defined('BASEPATH') or exit('No direct script access allowed');

class Culturetypes_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix().'lims_culture_types';
    }

    /** List all (ordered) */
    public function all()
    {
        return $this->db->order_by('name','ASC')->get($this->table)->result();
    }

    /** Only active */
    public function all_active()
    {
        return $this->db->where('active',1)->order_by('name','ASC')->get($this->table)->result();
    }

    /** Get one */
    public function get($id)
    {
        return $this->db->where('id',(int)$id)->get($this->table)->row();
    }

    /**
     * Create/Update
     * @return int id
     * @throws Exception
     */
    public function save($data, $id = null)
    {
        $rec = [
            'name'        => trim($data['name'] ?? ''),
            'code'        => trim($data['code'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'active'      => isset($data['active']) ? 1 : 0,
        ];
        if ($rec['name'] === '') {
            throw new Exception('Name required');
        }

        if ($id) {
            $this->db->where('id',(int)$id)->update($this->table, $rec);
            return (int)$id;
        } else {
            $this->db->insert($this->table, $rec);
            return (int)$this->db->insert_id();
        }
    }

    /** Delete */
    public function delete($id)
    {
        $this->db->where('id',(int)$id)->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    /** Toggle active */
    public function set_active($id, $active)
    {
        $this->db->where('id',(int)$id)->update($this->table, ['active' => $active ? 1 : 0]);
        return $this->db->affected_rows() >= 0;
    }
}
