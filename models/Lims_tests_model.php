<?php defined('BASEPATH') or exit('No direct script access allowed');
class Lims_tests_model extends App_Model{
    protected $table;
    public function __construct(){ parent::__construct(); $this->table = db_prefix().'lims_tests'; }
    public function add($data){ $this->db->insert($this->table,$data); return $this->db->insert_id(); }
    public function get_by_order($order_id){
        $sql = "SELECT t.*, s.id as sample_id, i.description as item_name FROM ".db_prefix()."lims_tests t
                JOIN ".db_prefix()."lims_samples s ON s.id=t.sample_id
                LEFT JOIN ".db_prefix()."items i ON i.id=t.item_id
                WHERE s.order_id=?";
        return $this->db->query($sql, [$order_id])->result();
    }
}