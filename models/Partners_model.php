<?php defined('BASEPATH') or exit('No direct script access allowed');

class Partners_model extends App_Model
{
    public function all_with_customer()
    {
        return $this->db->select('p.*, c.company as customer_name, c.userid as customer_id')
                        ->from(db_prefix().'lims_partners AS p')
                        ->join(db_prefix().'clients AS c','c.userid = p.customer_id','left')
                        ->order_by('p.name','ASC')->get()->result();
    }

    public function get($id)
    {
        return $this->db->where('id',(int)$id)->get(db_prefix().'lims_partners')->row();
    }

    

    public function get_by_api_key($api_key)
    {
        $api_key = trim((string)$api_key);
        if ($api_key === '') {
            return null;
        }
        return $this->db->where('api_key', $api_key)->get(db_prefix().'lims_partners')->row();
    }

public function save($data, $id=null)
    {
        if ($id) {
            $this->db->where('id',(int)$id)->update(db_prefix().'lims_partners',$data);
            return (int)$id;
        }
        $this->db->insert(db_prefix().'lims_partners',$data);
        return (int)$this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where('id',(int)$id)->delete(db_prefix().'lims_partners');
        return $this->db->affected_rows() > 0;
    }

    public function set_active($id, $active)
    {
        $this->db->where('id',(int)$id)->update(db_prefix().'lims_partners',['active'=>$active?1:0]);
        return $this->db->affected_rows() > 0;
    }
	public function get_active_for_orders()
	{
		$this->db->where('active', 1);

		// Προαιρετικά: αν θες να φαίνονται μόνο “configured” partners
		$this->db->where('sync_enabled', 1);
		$this->db->where("(api_base_url IS NOT NULL AND api_base_url <> '')", null, false);

		return $this->db->get(db_prefix().'lims_partners')->result(); // objects
	}

}
