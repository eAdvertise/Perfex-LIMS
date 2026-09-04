<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lims_dashboard_model extends App_Model
{
    private function table($name)
    {
        return db_prefix() . 'lims_' . $name;
    }

    private function count_where($table, array $where = [], array $whereIn = [])
    {
        if (!$this->db->table_exists($table)) {
            return 0;
        }

        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        foreach ($whereIn as $key => $values) {
            $this->db->where_in($key, $values);
        }

        return (int)$this->db->count_all_results($table);
    }

    public function overview()
    {
        $orders = $this->table('orders');
        $samples = $this->table('samples');
        $tests = $this->table('tests');
        $today = date('Y-m-d');

        $ordersToday = 0;
        $overdue = 0;
        $completedToday = 0;
        $testsInProgress = 0;
        if ($this->db->table_exists($orders)) {
            $ordersToday = (int)$this->db->where('created_at >=', $today . ' 00:00:00')
                ->where('created_at <=', $today . ' 23:59:59')->count_all_results($orders);
            $overdue = (int)$this->db->where('due_at <', date('Y-m-d H:i:s'))
                ->where_not_in('status', ['complete', 'signed', 'reported', 'canceled'])
                ->count_all_results($orders);
            $completedToday = (int)$this->db->where('updated_at >=', $today . ' 00:00:00')
                ->where('updated_at <=', $today . ' 23:59:59')
                ->where_in('status', ['complete', 'signed', 'reported'])
                ->count_all_results($orders);
        }
        if ($this->db->table_exists($tests)) {
            $testsInProgress = (int)$this->db
                ->group_start()
                    ->where_in('status', ['pending', 'in_progress'])
                    ->or_where_in('status_code', ['pending', 'in_progress'])
                ->group_end()
                ->count_all_results($tests);
        }

        return [
            'orders_today'    => $ordersToday,
            'pending_samples' => $this->count_where($samples, [], ['status' => ['draft', 'pending']]),
            'tests_progress'  => $testsInProgress,
            'ready_to_sign'   => $this->count_where($orders, ['status' => 'complete']),
            'overdue'         => $overdue,
            'completed_today' => $completedToday,
        ];
    }

    public function attention_orders($limit = 10)
    {
        $orders = $this->table('orders');
        if (!$this->db->table_exists($orders)) {
            return [];
        }

        return $this->db
            ->select("o.id, o.order_barcode, o.status, o.priority, o.due_at, c.company AS customer_name, COALESCE(NULLIF(sub.subject_name, ''), NULLIF(TRIM(CONCAT(COALESCE(sub.first_name, ''), ' ', COALESCE(sub.last_name, ''))), ''), '-') AS subject_name", false)
            ->from($orders . ' AS o')
            ->join(db_prefix() . 'clients AS c', 'c.userid = o.client_id', 'left')
            ->join($this->table('subjects') . ' AS sub', 'sub.id = o.subject_id', 'left')
            ->where_not_in('o.status', ['complete', 'signed', 'reported', 'canceled'])
            ->order_by('(o.due_at IS NOT NULL AND o.due_at < NOW())', 'DESC', false)
            ->order_by('o.priority', 'DESC')
            ->order_by('o.due_at', 'ASC')
            ->limit((int)$limit)
            ->get()->result();
    }

    public function ready_to_sign($limit = 8)
    {
        $orders = $this->table('orders');
        if (!$this->db->table_exists($orders)) {
            return [];
        }

        return $this->db
            ->select("o.id, o.order_barcode, o.updated_at, c.company AS customer_name, COALESCE(NULLIF(sub.subject_name, ''), NULLIF(TRIM(CONCAT(COALESCE(sub.first_name, ''), ' ', COALESCE(sub.last_name, ''))), ''), '-') AS subject_name", false)
            ->from($orders . ' AS o')
            ->join(db_prefix() . 'clients AS c', 'c.userid = o.client_id', 'left')
            ->join($this->table('subjects') . ' AS sub', 'sub.id = o.subject_id', 'left')
            ->where('o.status', 'complete')
            ->order_by('o.updated_at', 'ASC')
            ->limit((int)$limit)
            ->get()->result();
    }

    public function todays_appointments($limit = 10)
    {
        $appointments = $this->table('appointments');
        if (!$this->db->table_exists($appointments)) {
            return [];
        }

        $today = date('Y-m-d');
        return $this->db
            ->select("a.id, a.appointment_at, a.status, a.visit_type, c.company AS customer_name, COALESCE(NULLIF(sub.subject_name, ''), NULLIF(TRIM(CONCAT(COALESCE(sub.first_name, ''), ' ', COALESCE(sub.last_name, ''))), ''), '-') AS subject_name", false)
            ->from($appointments . ' AS a')
            ->join(db_prefix() . 'clients AS c', 'c.userid = a.client_id', 'left')
            ->join($this->table('subjects') . ' AS sub', 'sub.id = a.subject_id', 'left')
            ->where('a.appointment_at >=', $today . ' 00:00:00')
            ->where('a.appointment_at <=', $today . ' 23:59:59')
            ->order_by('a.appointment_at', 'ASC')
            ->limit((int)$limit)
            ->get()->result();
    }

    public function orders_by_status()
    {
        $orders = $this->table('orders');
        if (!$this->db->table_exists($orders)) {
            return [];
        }

        $rows = $this->db
            ->select('status, COUNT(*) AS total', false)
            ->from($orders)
            ->group_by('status')
            ->order_by('total', 'DESC')
            ->get()->result();

        $counts = [];
        foreach ($rows as $row) {
            $status = trim((string)$row->status);
            if ($status !== '') {
                $counts[$status] = (int)$row->total;
            }
        }

        return $counts;
    }

    public function recent_activity($limit = 10)
    {
        $activity = $this->table('order_activity');
        if (!$this->db->table_exists($activity)) {
            return [];
        }

        return $this->db
            ->select("a.id, a.order_id, a.action, a.message, a.created_at, o.order_barcode, CONCAT(COALESCE(st.firstname, ''), ' ', COALESCE(st.lastname, '')) AS staff_name", false)
            ->from($activity . ' AS a')
            ->join($this->table('orders') . ' AS o', 'o.id = a.order_id', 'left')
            ->join(db_prefix() . 'staff AS st', 'st.staffid = a.staff_id', 'left')
            ->order_by('a.created_at', 'DESC')
            ->limit((int)$limit)
            ->get()->result();
    }

    public function tests_by_department()
    {
        $tests = $this->table('tests');
        $analyses = $this->table('analyses');
        $departments = $this->table('departments');
        if (!$this->db->table_exists($tests)
            || !$this->db->table_exists($analyses)
            || !$this->db->table_exists($departments)) {
            return [];
        }

        $statusExpression = "COALESCE(NULLIF(t.status_code, ''), NULLIF(t.status, ''), 'pending')";

        return $this->db
            ->select("d.id, COALESCE(NULLIF(d.name, ''), '') AS department_name, COUNT(t.id) AS total_tests, SUM(CASE WHEN {$statusExpression} = 'pending' THEN 1 ELSE 0 END) AS pending_tests, SUM(CASE WHEN {$statusExpression} = 'in_progress' THEN 1 ELSE 0 END) AS progress_tests, SUM(CASE WHEN {$statusExpression} IN ('complete', 'completed', 'verified', 'approved', 'reported', 'signed') THEN 1 ELSE 0 END) AS completed_tests", false)
            ->from($tests . ' AS t')
            ->join($analyses . ' AS a', 'a.id = t.analysis_id', 'left')
            ->join($departments . ' AS d', 'd.id = a.department_id', 'left')
            ->group_by(['d.id', 'd.name'])
            ->order_by('total_tests', 'DESC')
            ->get()->result();
    }
}
