<?php defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

$aColumns = [
    's.id',
    's.internal_code',
    's.subject_name',
    's.subject_type',
    's.email',
    's.phone',
    's.active',
];
$sIndexColumn = 's.id';
$sTable = db_prefix() . 'lims_subjects AS s';
$join = ['LEFT JOIN ' . db_prefix() . 'clients AS c ON c.userid = s.client_id'];
$where = [];
if ($CI->db->field_exists('is_deleted', db_prefix() . 'lims_subjects')) {
    $where[] = 'AND s.is_deleted = 0';
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    's.first_name',
    's.last_name',
    's.client_id',
    's.id AS subject_id',
    'c.company AS client_company',
]);
$output = $result['output'];
$rows = $result['rResult'];
$canManage = has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin');

foreach ($rows as $row) {
    $data = [];
    $id = (int)$row['subject_id'];
    $display = trim((string)$row['subject_name']);
    if ($display === '') {
        $display = trim((string)$row['first_name'] . ' ' . (string)$row['last_name']);
    }
    if ($display === '') {
        $display = '#' . $id;
    }

    $data[] = $id;
    $data[] = html_escape((string)$row['internal_code']);
    $data[] = '<a href="' . admin_url('lims/subjects/view/' . $id) . '">' . html_escape($display) . '</a>'
        . (!empty($row['client_company']) ? '<div class="row-options">' . html_escape($row['client_company']) . '</div>' : '');
    $data[] = html_escape((string)$row['subject_type']);
    $data[] = html_escape((string)$row['email']);
    $data[] = html_escape((string)$row['phone']);
    $data[] = (int)$row['active'] === 1
        ? '<span class="label label-success">' . _l('active') . '</span>'
        : '<span class="label label-default">' . _l('inactive') . '</span>';

    $options = '<a href="' . admin_url('lims/subjects/view/' . $id) . '" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a> ';
    if ($canManage) {
        $options .= '<a href="' . admin_url('lims/subjects/create/' . $id) . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a> ';
        $options .= '<a href="' . admin_url('lims/subjects/delete/' . $id) . '" class="btn btn-danger btn-sm js-lims-subject-delete" data-subject-id="' . $id . '"><i class="fa fa-trash"></i></a>';
    }
    $data[] = $options;
    $output['aaData'][] = $data;
}

echo json_encode($output);
