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

    public function turnaround_metrics($days = 30)
    {
        $orders = $this->table('orders');
        $empty = ['average_hours' => 0, 'on_time_percent' => 0, 'completed_orders' => 0, 'change_percent' => 0];
        if (!$this->db->table_exists($orders)) {
            return $empty;
        }

        $days = max(1, (int)$days);
        $currentStart = date('Y-m-d 00:00:00', strtotime('-' . ($days - 1) . ' days'));
        $previousStart = date('Y-m-d 00:00:00', strtotime('-' . (($days * 2) - 1) . ' days'));
        $completionField = $this->db->field_exists('signed_at', $orders)
            ? 'COALESCE(signed_at, updated_at)'
            : 'updated_at';

        $getPeriod = function ($from, $to = null) use ($orders, $completionField) {
            $this->db
                ->select("COUNT(*) AS completed_orders, AVG(TIMESTAMPDIFF(MINUTE, created_at, {$completionField})) / 60 AS average_hours, SUM(CASE WHEN due_at IS NOT NULL AND {$completionField} <= due_at THEN 1 ELSE 0 END) AS on_time_orders, SUM(CASE WHEN due_at IS NOT NULL THEN 1 ELSE 0 END) AS orders_with_due_date", false)
                ->from($orders)
                ->where_in('status', ['complete', 'signed', 'reported'])
                ->where('created_at >=', $from);
            if ($to !== null) {
                $this->db->where('created_at <', $to);
            }

            return $this->db->get()->row();
        };

        $current = $getPeriod($currentStart);
        $previous = $getPeriod($previousStart, $currentStart);
        $average = $current ? round((float)$current->average_hours, 1) : 0;
        $previousAverage = $previous ? (float)$previous->average_hours : 0;
        $change = $previousAverage > 0 ? round((($average - $previousAverage) / $previousAverage) * 100, 1) : 0;

        return [
            'average_hours'    => $average,
            'on_time_percent'  => $current && (int)$current->orders_with_due_date > 0
                ? round(((int)$current->on_time_orders / (int)$current->orders_with_due_date) * 100, 1) : 0,
            'completed_orders' => $current ? (int)$current->completed_orders : 0,
            'change_percent'   => $change,
        ];
    }

    public function activity_trend($days = 14)
    {
        $days = max(1, min(60, (int)$days));
        $start = date('Y-m-d 00:00:00', strtotime('-' . ($days - 1) . ' days'));
        $series = [];
        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $date = date('Y-m-d', strtotime('-' . $offset . ' days'));
            $series[$date] = ['date' => $date, 'orders' => 0, 'samples' => 0, 'reports' => 0];
        }

        $sources = [
            'orders'  => [$this->table('orders'), 'created_at', []],
            'samples' => [$this->table('samples'), 'received_at', []],
            'reports' => [$this->table('orders'), 'updated_at', ['status' => ['signed', 'reported']]],
        ];
        foreach ($sources as $key => [$table, $dateField, $filters]) {
            if (!$this->db->table_exists($table)) {
                continue;
            }
            $this->db->select("DATE({$dateField}) AS activity_date, COUNT(*) AS total", false)
                ->from($table)->where($dateField . ' >=', $start);
            foreach ($filters as $field => $values) {
                $this->db->where_in($field, $values);
            }
            $rows = $this->db->group_by("DATE({$dateField})", false)->get()->result();
            foreach ($rows as $row) {
                if (isset($series[$row->activity_date])) {
                    $series[$row->activity_date][$key] = (int)$row->total;
                }
            }
        }

        return array_values($series);
    }

    public function critical_results($limit = 10)
    {
        $results = $this->table('results');
        $tests = $this->table('tests');
        $samples = $this->table('samples');
        if (!$this->db->table_exists($results) || !$this->db->table_exists($tests) || !$this->db->table_exists($samples)) {
            return [];
        }

        return $this->db
            ->select('r.id, r.value_numeric, r.value_text, r.unit, r.flag, r.measured_at, t.id AS test_id, a.name AS analysis_name, s.order_id, o.order_barcode', false)
            ->from($results . ' AS r')
            ->join($tests . ' AS t', 't.id = r.test_id', 'inner')
            ->join($this->table('analyses') . ' AS a', 'a.id = t.analysis_id', 'left')
            ->join($samples . ' AS s', 's.id = t.sample_id', 'inner')
            ->join($this->table('orders') . ' AS o', 'o.id = s.order_id', 'left')
            ->where("r.id = (SELECT MAX(r2.id) FROM {$results} r2 WHERE r2.test_id = r.test_id)", null, false)
            ->where_in('r.flag', ['L', 'H', 'LL', 'HH', 'A'])
            ->order_by("FIELD(r.flag, 'LL', 'HH', 'A', 'L', 'H')", '', false)
            ->order_by('r.measured_at', 'DESC')
            ->limit((int)$limit)
            ->get()->result();
    }

    public function billing_summary()
    {
        $orders = $this->table('orders');
        $links = $this->table('billing_links');
        $invoices = db_prefix() . 'invoices';
        $summary = ['uninvoiced' => 0, 'draft' => 0, 'unpaid' => 0, 'overdue' => 0];
        if (!$this->db->table_exists($orders) || !$this->db->table_exists($links) || !$this->db->table_exists($invoices)) {
            return $summary;
        }

        $summary['uninvoiced'] = (int)$this->db
            ->from($orders . ' AS o')
            ->where_in('o.status', ['complete', 'signed', 'reported'])
            ->where("NOT EXISTS (SELECT 1 FROM {$links} bl WHERE bl.order_id = o.id)", null, false)
            ->count_all_results();

        $rows = $this->db
            ->select('i.status, COUNT(DISTINCT i.id) AS total', false)
            ->from($links . ' AS bl')
            ->join($invoices . ' AS i', 'i.id = bl.invoice_id', 'inner')
            ->group_by('i.status')
            ->get()->result();
        foreach ($rows as $row) {
            $status = (int)$row->status;
            if ($status === 6) {
                $summary['draft'] += (int)$row->total;
            } elseif ($status === 4) {
                $summary['overdue'] += (int)$row->total;
            } elseif (in_array($status, [1, 3], true)) {
                $summary['unpaid'] += (int)$row->total;
            }
        }

        return $summary;
    }

    public function assigned_tests($staffId, $limit = 10)
    {
        $staffId = (int)$staffId;
        $tests = $this->table('tests');
        if ($staffId <= 0 || !$this->db->table_exists($tests)) {
            return [];
        }

        $statusExpression = "COALESCE(NULLIF(t.status_code, ''), NULLIF(t.status, ''), 'pending')";
        $terminalStatuses = ['complete', 'completed', 'verified', 'approved', 'reported', 'signed', 'canceled'];
        $escapedTerminalStatuses = implode(', ', array_map([$this->db, 'escape'], $terminalStatuses));
        return $this->db
            ->select("t.id, {$statusExpression} AS test_status, t.started_at, a.name AS analysis_name, d.name AS department_name, s.order_id, s.sample_uid, o.order_barcode, o.due_at, o.priority", false)
            ->from($tests . ' AS t')
            ->join($this->table('analyses') . ' AS a', 'a.id = t.analysis_id', 'left')
            ->join($this->table('departments') . ' AS d', 'd.id = a.department_id', 'left')
            ->join($this->table('samples') . ' AS s', 's.id = t.sample_id', 'inner')
            ->join($this->table('orders') . ' AS o', 'o.id = s.order_id', 'inner')
            ->where('t.assigned_staff', $staffId)
            ->where("{$statusExpression} NOT IN ({$escapedTerminalStatuses})", null, false)
            ->order_by('(o.due_at IS NOT NULL AND o.due_at < NOW())', 'DESC', false)
            ->order_by('o.priority', 'DESC')
            ->order_by('o.due_at', 'ASC')
            ->limit((int)$limit)
            ->get()->result();
    }

    public function samples_requiring_action($limit = 10)
    {
        $samples = $this->table('samples');
        if (!$this->db->table_exists($samples)) {
            return [];
        }

        return $this->db
            ->select("s.id, s.sample_uid, s.barcode, s.status, s.collected_at, s.received_at, st.name AS sample_type_name, o.id AS order_id, o.order_barcode, o.due_at, o.priority, COALESCE(NULLIF(sub.subject_name, ''), NULLIF(TRIM(CONCAT(COALESCE(sub.first_name, ''), ' ', COALESCE(sub.last_name, ''))), ''), '-') AS subject_name", false)
            ->from($samples . ' AS s')
            ->join($this->table('sample_types') . ' AS st', 'st.id = s.sample_type_id', 'left')
            ->join($this->table('orders') . ' AS o', 'o.id = s.order_id', 'inner')
            ->join($this->table('subjects') . ' AS sub', 'sub.id = COALESCE(s.subject_id, o.subject_id)', 'left', false)
            ->where_in('s.status', ['draft', 'pending', 'collected'])
            ->order_by('(o.due_at IS NOT NULL AND o.due_at < NOW())', 'DESC', false)
            ->order_by('o.priority', 'DESC')
            ->order_by("FIELD(s.status, 'collected', 'pending', 'draft')", '', false)
            ->order_by('o.due_at', 'ASC')
            ->limit((int)$limit)
            ->get()->result();
    }
}
