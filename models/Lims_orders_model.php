<?php defined('BASEPATH') or exit('No direct script access allowed');
class Lims_orders_model extends App_Model{
    protected $table;
    public function __construct(){ parent::__construct(); $this->table = db_prefix().'lims_orders'; }
    public function add($data){ $ins=['client_id'=>(int)($data['client_id']??0),'status'=>'draft','notes'=>$data['notes']??null,'received_at'=>$data['received_at']??null]; $this->db->insert($this->table,$ins); return $this->db->insert_id(); }
    public function get($id){ return $this->db->where('id',$id)->get($this->table)->row(); }
    public function all(){ return $this->db->order_by('id','DESC')->get($this->table)->result(); }
}