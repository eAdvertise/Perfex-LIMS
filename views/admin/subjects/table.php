<?php defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    's.id as subject_id',
    's.first_name as first_name',
    's.last_name as last_name',
    's.subject_name as subject_name',
    's.subject_type as subject_type',
    's.internal_code as internal_code',
    's.client_id as subject_client_id',
    's.created_at as created_at',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'lims_subjects AS s';

$join = [
    'LEFT JOIN ' . db_prefix() . 'clients AS c ON c.userid = s.client_id',
];

$where = [];

// επιπλέον πεδία που θέλουμε στο result
$additionalSelect = [
    'c.company AS client_company',
];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // ID
    $id = isset($aRow['subject_id']) ? (int) $aRow['subject_id'] : 0;
    $row[] = $id;

    // -------- SUBJECT NAME ----------
    $first   = isset($aRow['first_name'])     ? trim($aRow['first_name'])     : '';
    $last    = isset($aRow['last_name'])      ? trim($aRow['last_name'])      : '';
    $sname   = isset($aRow['subject_name'])   ? trim($aRow['subject_name'])   : '';
    $stype   = isset($aRow['subject_type'])   ? trim($aRow['subject_type'])   : '';
    $company = isset($aRow['client_company']) ? trim($aRow['client_company']) : '';

    $displayName = '';

    if ($stype === 'patient') {
        // patient: 1) first+last
        if ($first !== '' || $last !== '') {
            $displayName = trim($first . ' ' . $last);
        }
        // 2) αλλιώς → client company (existing customer)
        elseif ($company !== '') {
            $displayName = $company;
        }
        // 3) fallback → subject_name
        elseif ($sname !== '') {
            $displayName = $sname;
        }
    } else {
        // non-patient: 1) subject_name
        if ($sname !== '') {
            $displayName = $sname;
        }
        // 2) company
        elseif ($company !== '') {
            $displayName = $company;
        }
        // 3) first+last
        elseif ($first !== '' || $last !== '') {
            $displayName = trim($first . ' ' . $last);
        }
    }

    if ($displayName === '') {
        $displayName = '#' . $id;
    }

    $row[] = '<a href="' . admin_url('lims/subjects/view/' . $id) . '">'
           . html_escape($displayName)
           . '</a>';

    // -------- CUSTOMER ----------
    $clientId = isset($aRow['subject_client_id']) ? (int) $aRow['subject_client_id'] : 0;

    if ($clientId > 0) {
        $label = $company !== ''
            ? $company . ' (#' . $clientId . ')'
            : '#' . $clientId;

        $row[] = '<a href="' . admin_url('clients/client/' . $clientId) . '">'
               . html_escape($label)
               . '</a>';
    } else {
        $row[] = '<span class="text-muted">—</span>';
    }

    // -------- TYPE ----------
    $typeLabel = $stype !== '' ? ucfirst($stype) : '—';
    $row[] = html_escape($typeLabel);

    // -------- INTERNAL CODE ----------
    $code = isset($aRow['internal_code']) ? trim($aRow['internal_code']) : '';
    $row[] = $code !== ''
        ? html_escape($code)
        : '<span class="text-muted">—</span>';

    // -------- CREATED AT ----------
    if (!empty($aRow['created_at']) && $aRow['created_at'] != '0000-00-00 00:00:00') {
        $row[] = _dt($aRow['created_at']);
    } else {
        $row[] = '<span class="text-muted">—</span>';
    }

    // -------- OPTIONS ----------
    $options  = '<a href="' . admin_url('lims/subjects/view/' . $id) . '" class="btn btn-default btn-sm">';
    $options .= '<i class="fa fa-eye"></i> ' . _l('view') . '</a> ';

    $options .= '<a href="' . admin_url('lims/subjects/create/' . $id) . '" class="btn btn-default btn-sm">';
    $options .= '<i class="fa fa-pencil-square-o"></i></a>';

    $row[] = $options;

    $output['aaData'][] = $row;
}

echo json_encode($output);
