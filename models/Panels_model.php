<?php defined('BASEPATH') or exit('No direct script access allowed');

class Panels_model extends App_Model
{
    public function all()
    {
        return $this->db->order_by('name','ASC')->get(db_prefix().'lims_panels')->result();
    }

    public function get($id)
    {
        return $this->db->where('id',(int)$id)->get(db_prefix().'lims_panels')->row();
    }

    public function get_panel_analysis_ids($panel_id)
    {
        $rows = $this->db->select('analysis_id')->where('panel_id',(int)$panel_id)->get(db_prefix().'lims_panel_items')->result();
        return array_map(function($r){ return (int)$r->analysis_id; }, $rows);
    }

    public function save($data, $id=null)
    {
        $rec = [
            'name'           => trim($data['name'] ?? ''),
            'code'           => trim($data['code'] ?? ''),
            'department_id'  => (int)($data['department_id'] ?? 0) ?: null,
            'active'         => isset($data['active']) ? 1 : 0,
        ];
        if ($rec['name']==='') { throw new Exception('Panel name required'); }

        if ($id) {
            $this->db->where('id',(int)$id)->update(db_prefix().'lims_panels', $rec);
            $panel_id = (int)$id;
        } else {
            $this->db->insert(db_prefix().'lims_panels', $rec);
            $panel_id = (int)$this->db->insert_id();
        }

        // Relations με Analyses
        $ids = $data['analysis_ids'] ?? [];
        if (!is_array($ids)) { $ids = []; }
        // καθάρισε & ξαναγράψε
        $this->db->where('panel_id',$panel_id)->delete(db_prefix().'lims_panel_items');
        $order = 0;
        foreach ($ids as $aid) {
            $aid = (int)$aid;
            if ($aid > 0) {
                $this->db->insert(db_prefix().'lims_panel_items', [
                    'panel_id'   => $panel_id,
                    'analysis_id'=> $aid,
                    'required'   => 1,
                    'sort_order' => $order++,
                ]);
            }
        }

        return $panel_id;
    }

    public function delete($id)
    {
        $id = (int)$id;
        $this->db->where('panel_id',$id)->delete(db_prefix().'lims_panel_items');
        $this->db->where('id',$id)->delete(db_prefix().'lims_panels');
        return $this->db->affected_rows() > 0;
    }

    public function set_active($id, $active)
    {
        $this->db->where('id',(int)$id)->update(db_prefix().'lims_panels',['active'=>$active?1:0]);
        return $this->db->affected_rows() > 0;
    }
}
