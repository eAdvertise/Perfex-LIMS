<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$p  = db_prefix();

// ---------------------------------------------------------------------
// Φόρτωση Test Statuses, για να πάρουμε name + color ανά code
// ---------------------------------------------------------------------
$CI->load->model('lims/teststatuses_model', 'ts_model');
$ts_rows = $CI->ts_model->all();
$TEST_STATUS_MAP = [];
if (!empty($ts_rows)) {
    foreach ($ts_rows as $s) {
        if (!empty($s->code)) {
            $TEST_STATUS_MAP[$s->code] = $s; // $s->name, $s->color, ...
        }
    }
}

/**
 * Datatable: LIMS Tests Queue (grouped by Order)
 * Κάθε γραμμή = ένα order
 */
// lims/views/admin/tables/tests.php
// Βασικές στήλες για DataTables (search/sort)
$aColumns = [
    'o.id',
    'o.order_barcode',
    'subj.subject_name',
    'o.status',
];

$sIndexColumn = 'o.id';
$sTable       = $p . 'lims_orders o';

// JOIN με subjects
$join = [
    'LEFT JOIN ' . $p . 'lims_subjects subj ON subj.id = o.subject_id',
];

$where = [];

/* ------------------------------------------------------------------
 * ΦΙΛΤΡΟ STATUS (order.status) – κωδικός από lims_test_statuses.code
 * ---------------------------------------------------------------- */
$status = $CI->input->post('status');
if ($status !== null && $status !== '') {
    $status  = $CI->db->escape_str($status);
    $where[] = 'AND o.status = "' . $status . '"';
}

/* ------------------------------------------------------------------
 * ΦΙΛΤΡΟ DEPARTMENT (orders που έχουν tests σε συγκεκριμένο department)
 * ---------------------------------------------------------------- */
$departmentId = $CI->input->post('department_id');
if ($departmentId !== null && $departmentId !== '') {
    $depId = (int)$departmentId;

    $where[] = 'AND EXISTS (
        SELECT 1
        FROM ' . $p . 'lims_samples s
        JOIN ' . $p . 'lims_tests t ON t.sample_id = s.id
        JOIN ' . $p . 'lims_analyses a ON a.id = t.analysis_id
        WHERE s.order_id = o.id
          AND a.department_id = ' . $depId . '
    )';
}

/* ------------------------------------------------------------------
 * ΦΙΛΤΡΟ ASSIGNED STAFF (orders που έχουν tests assigned σε συγκεκριμένο staff)
 * ---------------------------------------------------------------- */
$assignedStaff = $CI->input->post('assigned_staff');
if ($assignedStaff !== null && $assignedStaff !== '') {
    $staffId = (int)$assignedStaff;

    $where[] = 'AND EXISTS (
        SELECT 1
        FROM ' . $p . 'lims_samples s
        JOIN ' . $p . 'lims_tests t ON t.sample_id = s.id
        WHERE s.order_id = o.id
          AND t.assigned_staff = ' . $staffId . '
    )';
}

/* ------------------------------------------------------------------
 * ΦΙΛΤΡΟ ΗΜΕΡΟΜΗΝΙΑΣ (date_from / date_to πάνω σε samples.received_at)
 * ---------------------------------------------------------------- */
$dateFrom = $CI->input->post('date_from');
if (!empty($dateFrom)) {
    $sqlFrom = to_sql_date($dateFrom);
    $where[] = 'AND EXISTS (
        SELECT 1
        FROM ' . $p . 'lims_samples s
        WHERE s.order_id = o.id
          AND DATE(s.received_at) >= "' . $sqlFrom . '"
    )';
}

$dateTo = $CI->input->post('date_to');
if (!empty($dateTo)) {
    $sqlTo = to_sql_date($dateTo);
    $where[] = 'AND EXISTS (
        SELECT 1
        FROM ' . $p . 'lims_samples s
        WHERE s.order_id = o.id
          AND DATE(s.received_at) <= "' . $sqlTo . '"
    )';
}

/* ------------------------------------------------------------------
 * Extra πεδία (subqueries) για counts & dates
 * ---------------------------------------------------------------- */
$additionalSelect = [
    // Πλήθος samples για το order
    '(SELECT COUNT(*) 
        FROM ' . $p . 'lims_samples s 
      WHERE s.order_id = o.id
    ) AS samples_count',

    // Πλήθος tests για το order
    '(SELECT COUNT(*) 
        FROM ' . $p . 'lims_tests t 
        JOIN ' . $p . 'lims_samples s2 ON s2.id = t.sample_id 
      WHERE s2.order_id = o.id
    ) AS tests_count',

    // Πλήθος open tests (pending + in_progress) – για την ώρα κρατάμε το ίδιο business rule
    '(SELECT COUNT(*) 
        FROM ' . $p . 'lims_tests t2 
        JOIN ' . $p . 'lims_samples s3 ON s3.id = t2.sample_id 
      WHERE s3.order_id = o.id 
        AND t2.status IN (\'pending\',\'in_progress\')
    ) AS open_tests',

    // Πρώτη ημερομηνία received
    '(SELECT MIN(s4.received_at) 
        FROM ' . $p . 'lims_samples s4 
      WHERE s4.order_id = o.id
    ) AS first_received',

    // Τελευταία ημερομηνία received
    '(SELECT MAX(s5.received_at) 
        FROM ' . $p . 'lims_samples s5 
      WHERE s5.order_id = o.id
    ) AS last_received',
];

// Init datatable
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    // keys: id, order_barcode, subject_name, status, + extra selects
    $order_id = (int)$aRow['id'];
    $barcode  = $aRow['order_barcode'];
    $subject  = $aRow['subject_name'];

    $samples_count = isset($aRow['samples_count']) ? (int)$aRow['samples_count'] : 0;
    $tests_count   = isset($aRow['tests_count'])   ? (int)$aRow['tests_count']   : 0;
    $open_tests    = isset($aRow['open_tests'])    ? (int)$aRow['open_tests']    : 0;

    $first_received_raw = $aRow['first_received'] ?? null;
    $last_received_raw  = $aRow['last_received']  ?? null;

    $first_received = !empty($first_received_raw) ? _dt($first_received_raw) : '—';
    $last_received  = !empty($last_received_raw)  ? _dt($last_received_raw)  : '—';

    $status = $aRow['status'];

    // ----- Build row: 10 στήλες όπως στο manage.php -----
    $row = [];

    // 1. Order #
    $row[] = '#' . $order_id;

    // 2. Barcode
    $row[] = !empty($barcode)
        ? '<code>' . html_escape($barcode) . '</code>'
        : '—';

    // 3. Subject (όνομα ή παύλα)
    if (!empty($subject)) {
        $row[] = html_escape($subject);
    } else {
        $row[] = '<span class="text-muted">—</span>';
    }

    // 4. Samples count
    $row[] = (string)$samples_count;

    // 5. Tests count
    $row[] = (string)$tests_count;

    // 6. Open tests
    if ($open_tests > 0) {
        $row[] = '<span class="label label-warning">' . $open_tests . '</span>';
    } else {
        $row[] = '<span class="label label-success">0</span>';
    }

    // 7. First received
    $row[] = $first_received;

    // 8. Last received
    $row[] = $last_received;

    // 9. Status (order) – ΠΛΕΟΝ ΑΠΟ lims_test_statuses ΑΝ ΥΠΑΡΧΕΙ
    $statusCode = (string)$status;
    $statusHtml = '';

    if (isset($TEST_STATUS_MAP[$statusCode])) {
        $ts   = $TEST_STATUS_MAP[$statusCode];
        $name = $ts->name ?: ucfirst($statusCode);
        $colorStyle = '';
        if (!empty($ts->color)) {
            $hex = html_escape($ts->color);
            $colorStyle = ' style="background:' . $hex . ';border-color:' . $hex . ';"';
        }
        $statusHtml = '<span class="label"' . $colorStyle . '>' . html_escape($name) . '</span>';
    } else {
        // Fallback σε παλιό mapping για compatibility
        $labelClass = 'default';
        switch ($statusCode) {
            case 'draft':       $labelClass = 'default'; break;
            case 'submitted':   $labelClass = 'info';    break;
            case 'accessioned': $labelClass = 'primary'; break;
            case 'testing':     $labelClass = 'warning'; break;
            case 'verified':    $labelClass = 'purple';  break;
            case 'approved':    $labelClass = 'success'; break;
            case 'reported':    $labelClass = 'inverse'; break;
            case 'complete':    $labelClass = 'success'; break;
            case 'signed':      $labelClass = 'success'; break;
            case 'canceled':    $labelClass = 'danger';  break;
        }
        $statusHtml = '<span class="label label-' . $labelClass . '">' . ucfirst(html_escape($statusCode)) . '</span>';
    }

    $row[] = $statusHtml;

    // 10. Options
    $viewUrl = admin_url('lims/tests/order/' . $order_id);
    $signUrl = admin_url('lims/tests/sign_order/' . $order_id);

    $options = '<a href="' . $viewUrl . '" class="btn btn-default btn-sm">
                    <i class="fa fa-eye"></i>
                </a>';

    // Επιτρέπουμε sign μόνο αν δεν υπάρχουν open tests και το status δεν είναι draft/canceled/signed
    $can_sign = ($open_tests === 0 && !in_array($statusCode, ['draft','canceled','signed'], true));

    if (has_permission('lims', '', 'enter_results') && $can_sign) {
        $options .= ' <a href="' . $signUrl . '" class="btn btn-success btn-sm"
                        title="' . _l('lims_sign_report') . '"
                        onclick="return confirm(\'' . _l('lims_sign_confirm') . '\');">
                        <i class="fa fa-signature"></i>
                     </a>';
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($output);
exit;
