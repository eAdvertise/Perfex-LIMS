<?php defined('BASEPATH') or exit('No direct script access allowed');

class Teststatuses_model extends App_Model
{
    private $tbl;

    public function __construct()
    {
        parent::__construct();
        $this->tbl = db_prefix() . 'lims_test_statuses';
    }

    public function all()
    {
        return $this->db->order_by('position', 'ASC')->get($this->tbl)->result();
    }

    public function get($id)
    {
        return $this->db->where('id', (int) $id)->get($this->tbl)->row();
    }

    public function save($data, $id = null)
    {
        $rec = [
            'name'                  => trim($data['name'] ?? ''),
            'code'                  => trim($data['code'] ?? ''),
            'color'                 => trim($data['color'] ?? ''),
            'is_terminal'           => isset($data['is_terminal']) ? 1 : 0,
            'requires_result'       => isset($data['requires_result']) ? 1 : 0,
            'requires_verification' => isset($data['requires_verification']) ? 1 : 0,
            'requires_approval'     => isset($data['requires_approval']) ? 1 : 0,
            'active'                => isset($data['active']) ? 1 : 0,
        ];

        if ($rec['name'] === '' || $rec['code'] === '') {
            throw new Exception('Name & code required');
        }

        if ($id) {
            $this->db->where('id', (int) $id)->update($this->tbl, $rec);
            return (int) $id;
        }

        // position στο τέλος
        $max = $this->db->select_max('position')->get($this->tbl)->row();
        $rec['position'] = (int) ($max->position ?? 0) + 1;

        $this->db->insert($this->tbl, $rec);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $row = $this->get($id);
        if (!$row) {
            return false;
        }
        // μην σβήνεις default
        if ((int) $row->is_default === 1) {
            return false;
        }

        $this->db->where('id', (int) $id)->delete($this->tbl);
        return $this->db->affected_rows() > 0;
    }

    public function set_active($id, $active)
    {
        $this->db->where('id', (int) $id)->update($this->tbl, [
            'active' => $active ? 1 : 0,
        ]);

        return $this->db->affected_rows() > 0;
    }

    public function set_default($id)
    {
        // clear όλα
        $this->db->update($this->tbl, ['is_default' => 0]);

        // ορισμός νέου default + πάντα active
        $this->db->where('id', (int) $id)->update($this->tbl, [
            'is_default' => 1,
            'active'     => 1,
        ]);

        return $this->db->affected_rows() > 0;
    }

    public function move($id, $dir)
    {
        $row = $this->get($id);
        if (!$row) {
            return false;
        }

        if ($dir === 'up') {
            $swap = $this->db
                ->where('position <', (int) $row->position)
                ->order_by('position', 'DESC')
                ->limit(1)
                ->get($this->tbl)
                ->row();
        } else {
            $swap = $this->db
                ->where('position >', (int) $row->position)
                ->order_by('position', 'ASC')
                ->limit(1)
                ->get($this->tbl)
                ->row();
        }

        if (!$swap) {
            return false;
        }

        $this->db->where('id', (int) $row->id)->update($this->tbl, [
            'position' => (int) $swap->position,
        ]);
        $this->db->where('id', (int) $swap->id)->update($this->tbl, [
            'position' => (int) $row->position,
        ]);

        return true;
    }
}
