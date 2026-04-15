<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sampletypes_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix().'lims_sample_types';
    }

    /** Get all sample types (ordered by name) */
    public function all()
    {
        return $this->db->order_by('name', 'ASC')->get($this->table)->result();
    }

    /** Get single sample type by id */
    public function get($id)
    {
        return $this->db->where('id', (int)$id)->get($this->table)->row();
    }

    /**
     * Create/Update sample type
     * @throws Exception when name is empty
     * @return int id
     */
    public function save($data, $id = null)
    {
        $rec = [
            'name'                     => trim($data['name'] ?? ''),
            'code'                     => trim($data['code'] ?? ''),
            'snomed_specimen_code'     => trim($data['snomed_specimen_code'] ?? ''),
            'min_volume'               => trim($data['min_volume'] ?? ''),
            'container'                => trim($data['container'] ?? ''),
            'stability_hours'          => ($data['stability_hours'] !== '' ? (int)$data['stability_hours'] : null),
            'storage_temp'             => trim($data['storage_temp'] ?? ''),
            'collection_instructions'  => $data['collection_instructions'] ?? null,
            'active'                   => isset($data['active']) ? 1 : 0,
        ];

        if ($rec['name'] === '') {
            throw new Exception('Sample Type name required');
        }

        if ($id) {
            $this->db->where('id', (int)$id)->update($this->table, $rec);
        } else {
            $this->db->insert($this->table, $rec);
            $id = (int)$this->db->insert_id();
        }

        return $id;
    }

    /** Delete sample type */
    public function delete($id)
    {
        $this->db->where('id', (int)$id)->delete($this->table);
        return $this->db->affected_rows() > 0;
    }

    /** Toggle active */
    public function set_active($id, $active)
    {
        $this->db->where('id', (int)$id)->update($this->table, ['active' => $active ? 1 : 0]);
        return $this->db->affected_rows() >= 0;
    }
	public function all_active()
	{
		return $this->db
			->where('active', 1)
			->order_by('name', 'ASC')
			->get($this->table)
			->result();
	}

}
