<?php defined('BASEPATH') or exit('No direct script access allowed');

class Appointments_model extends App_Model
{
    public function __construct(){ parent::__construct(); }

    public function all(array $opts = [])
	{
		// $opts: from (Y-m-d), to (Y-m-d), upcoming_only (bool)
		$from = $opts['from'] ?? null;
		$to   = $opts['to']   ?? null;
		$upcoming_only = !empty($opts['upcoming_only']);

		$this->db->select('a.*, c.company as client_name, s.firstname, s.lastname')
				 ->from(db_prefix().'lims_appointments as a')
				 ->join(db_prefix().'clients as c','c.userid = a.client_id','left')
				 ->join(db_prefix().'staff as s','s.staffid = a.assigned_staff','left');

		if ($from) {
			$this->db->where('a.appointment_at >=', $from.' 00:00:00');
		}
		if ($to) {
			$this->db->where('a.appointment_at <=', $to.' 23:59:59');
		}
		if ($upcoming_only && !$from && !$to) {
			$this->db->where('a.appointment_at >=', date('Y-m-d').' 00:00:00');
		}

		$this->db->order_by('a.appointment_at','DESC');
		return $this->db->get()->result();
	}


    public function get($id)
    {
        $this->db->where('id',(int)$id);
        return $this->db->get(db_prefix().'lims_appointments')->row();
    }

    public function add($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert(db_prefix().'lims_appointments', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id',(int)$id)->update(db_prefix().'lims_appointments', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
	{
		$row = $this->get($id);
		if(!$row) return false;
		
		$this->db->where('id',(int)$id)->delete(db_prefix().'lims_appointments');
		return $this->db->affected_rows() > 0;
	}

}
