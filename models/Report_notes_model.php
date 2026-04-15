<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report_notes_model extends App_Model
{
    protected $table;
    protected $pivot;

    public function __construct()
    {
        parent::__construct();
        $p = db_prefix();
        $this->table = $p.'lims_report_notes';
        $this->pivot = $p.'lims_test_report_notes';
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => (int)$id])->row();
    }

    public function add($data)
    {
        $now = date('Y-m-d H:i:s');

        $row = [
            'code'       => !empty($data['code']) ? trim((string)$data['code']) : null,
            'note_el'    => trim((string)$data['note_el']),
            'note_en'    => trim((string)$data['note_en']),
            'active'     => isset($data['active']) ? (int)$data['active'] : 1,
            'sort_order' => isset($data['sort_order']) ? (int)$data['sort_order'] : 0,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $this->guard_unique_code($row['code'], null);

        $this->db->insert($this->table, $row);
        return (int)$this->db->insert_id();
    }

    public function update($id, $data)
    {
        $id  = (int)$id;
        $now = date('Y-m-d H:i:s');

        $row = [
            'code'       => !empty($data['code']) ? trim((string)$data['code']) : null,
            'note_el'    => trim((string)$data['note_el']),
            'note_en'    => trim((string)$data['note_en']),
            'active'     => isset($data['active']) ? (int)$data['active'] : 1,
            'sort_order' => isset($data['sort_order']) ? (int)$data['sort_order'] : 0,
            'updated_at' => $now,
        ];

        $this->guard_unique_code($row['code'], $id);

        $this->db->where('id', $id)->update($this->table, $row);
        return $this->db->affected_rows() >= 0;
    }

    public function delete($id)
    {
        $id = (int)$id;

        $this->db->where('note_id', $id)->delete($this->pivot);
        $this->db->where('id', $id)->delete($this->table);

        return $this->db->affected_rows() > 0;
    }

    private function guard_unique_code($code, $ignoreId = null)
    {
        $code = $code !== null ? trim((string)$code) : null;
        if ($code === null || $code === '') {
            return;
        }

        $this->db->from($this->table)->where('code', $code);
        if ($ignoreId) {
            $this->db->where('id !=', (int)$ignoreId);
        }

        if ($this->db->count_all_results() > 0) {
            throw new Exception('Code already exists.');
        }
    }

    // Pivot helpers (θα τα χρησιμοποιήσουμε στο Laboratory Test UI αργότερα)
    public function get_note_ids_for_test($test_id)
    {
        $rows = $this->db->select('note_id')
            ->from($this->pivot)
            ->where('test_id', (int)$test_id)
            ->get()->result_array();

        return array_map(fn($r) => (int)$r['note_id'], $rows);
    }

    public function set_notes_for_test($test_id, $note_ids)
    {
        $test_id = (int)$test_id;
        $note_ids = is_array($note_ids) ? $note_ids : [];
        $note_ids = array_values(array_unique(array_filter(array_map('intval', $note_ids), fn($v) => $v > 0)));

        $this->db->trans_begin();

        $this->db->where('test_id', $test_id)->delete($this->pivot);

        $now = date('Y-m-d H:i:s');
        foreach ($note_ids as $nid) {
            $this->db->insert($this->pivot, [
                'test_id'    => $test_id,
                'note_id'    => (int)$nid,
                'created_at' => $now,
            ]);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            throw new Exception('Failed to save test notes.');
        }

        $this->db->trans_commit();
    }
}
