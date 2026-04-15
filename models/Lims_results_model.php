<?php defined('BASEPATH') or exit('No direct script access allowed');
class Lims_results_model extends App_Model{
    protected $table;
    public function __construct(){ parent::__construct(); $this->table = db_prefix().'lims_results'; }
    public function add($data){ $this->db->insert($this->table,$data); return $this->db->insert_id(); }
    public function get_by_test($test_id){ return $this->db->where('test_id',$test_id)->get($this->table)->result(); }
}