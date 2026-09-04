<?php
//lims/views/themes/orderpdf.php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * LIMS Order PDF Theme
 *
 * - Header: Logo (αριστερά) με στοιχεία εταιρείας από κάτω + Barcode (δεξιά: κείμενο & εικόνα)
 * - Title + Meta γραμμή
 * - 3 στήλες: Subject | Client | Order meta (+ Contract)
 * - Items σε 3 στήλες (Panels / Analyses / Cultures)
 * - Samples πίνακας
 * - Appointment μπλοκ (και χάρτης αν υπάρχει lat/lng)
 * - Notes full-width στο τέλος
 * - Footer από core option
 */

/** @var TCPDF $pdf */
/** @var object $order */

$CI = &get_instance();
$dimensions = $pdf->getPageDimensions();
$font_name  = isset($font_name) ? $font_name : 'dejavusans';
$font_size  = isset($font_size) ? (int)$font_size : 10;

// --- Guard: αν λείπει το $order, σταμάτα κομψά ---
if (!isset($order) || !is_object($order)) {
    $pdf->SetFont($font_name, '', 12);
    $pdf->Write(0, 'Order missing', '', 0, 'L', true, 0, false, false, 0);
    return;
}

/* -------------------------------------------------------------
 * Resolve Subject
 * ----------------------------------------------------------- */
$subject = isset($subject) ? $subject : null;
$subjectId = (int)($order->subject_id ?? 0);
if (!$subject && $subjectId > 0) {
    $subject = $CI->db
        ->where('id', $subjectId)
        ->get(db_prefix().'lims_subjects')
        ->row();
}

/* -------------------------------------------------------------
 * Resolve Client (μόνο αν δεν δόθηκε ήδη)
 * ----------------------------------------------------------- */
if (!isset($client) || !$client) {
    if (!class_exists('Clients_model', false)) {
        $CI->load->model('clients_model');
    }
    $cid    = (int)($order->client_id ?? 0);
    $client = $cid ? $CI->clients_model->get($cid) : null;
}

/* -------------------------------------------------------------
 * Resolve Appointment (τελευταίο/πιο πρόσφατο)
 * ----------------------------------------------------------- */
if (!isset($appointment)) {
    $appointment = $CI->db->where('order_id', (int)$order->id)
        ->order_by('appointment_at', 'DESC')
        ->limit(1)
        ->get(db_prefix().'lims_appointments')
        ->row();
}

/* =============================================================
 * HEADER: Logo (αριστερά) + στοιχεία εταιρείας από κάτω
 *         Barcode (κείμενο & εικόνα) δεξιά
 * =========================================================== */

// (1) Ανθεκτική ανεύρεση company logo
$logo_file = get_option('company_logo_dark');
$logo_path = $logo_file ? FCPATH . 'uploads/company/' . $logo_file : null;
if (!$logo_path || !is_file($logo_path)) {
    // Fallback μέσω pdf_logo_url() -> absolute URL -> μετατροπή σε filesystem path
    if (function_exists('pdf_logo_url')) {
        $url = pdf_logo_url();
        if ($url && strpos($url, base_url()) === 0) {
            $rel = FCPATH . ltrim(str_replace(base_url(), '', $url), '/');
            if (is_file($rel)) { $logo_path = $rel; }
        }
    }
}
$has_logo = $logo_path && is_file($logo_path);

// βασικές μετρήσεις
$pdf->SetFont($font_name, '', $font_size);
$headerTopY   = $pdf->getY();
$headerHeight = 40; // σταθερό ύψος header (προσαρμόσιμο)
$contentWidth = $dimensions['wk'] - $dimensions['lm'] - $dimensions['rm'];

// στήλες header
$leftColWidth  = $contentWidth * 0.52;          // αριστερά (logo + org info)
$rightColWidth = $contentWidth - $leftColWidth; // δεξιά (barcode)

$leftX  = $dimensions['lm'];
$rightX = $leftX + $leftColWidth;

$curYLeft  = $headerTopY;
$curYRight = $headerTopY;

// (2) Αριστερή στήλη: Logo on top
if ($has_logo) {
    try {
        // πλάτος λογότυπου ~70mm (ύψος auto)
        $pdf->Image($logo_path, $leftX, $curYLeft, 70, 0, '', '', 'T', true, 300);
    } catch (\Throwable $e) { /* ignore image errors */ }
    // ελαφρύ spacing κάτω από το logo
    $curYLeft += 35;
} else {
    // χωρίς logo: μικρό padding
    $curYLeft += 2;
}

// (3) Στοιχεία εταιρείας κάτω από το logo
$pdf->SetXY($leftX, $curYLeft);
$pdf->SetFont($font_name, '', 12);
$orgHtml = '<div style="color:#424242;">' . format_organization_info() . '</div>';
$pdf->writeHTMLCell($leftColWidth, 0, $leftX, $curYLeft, $orgHtml, 0, 1, false, true, 'L', true);
$curYLeft = max($curYLeft, $pdf->GetY());

// (4) Δεξιά στήλη: Barcode text + εικόνα
$barcodeText = (string)($order->order_barcode ?? '');
if ($barcodeText !== '') {
    // μεγάλο κείμενο barcode επάνω δεξιά
    $pdf->SetXY($rightX, $curYRight);
    $pdf->SetFont($font_name, 'B', 13);
    $pdf->MultiCell($rightColWidth, 0, $barcodeText, 0, 'R', 0, 1, '', '', true);

    $curYRight = $pdf->GetY() + 2;

    // εικόνα barcode
    $pdf->SetFont($font_name, '', $font_size);
    $style = [
        'position' => '',
        'align'    => 'R',
        'stretch'  => false,
        'fitwidth' => true,
        'border'   => 0,
        'hpadding' => 0,
        'vpadding' => 0,
        'fgcolor'  => [0,0,0],
        'bgcolor'  => false,
        'text'     => false,
    ];
    $barcodeW = min(64, $rightColWidth);    // πλάτος barcode block
    $barcodeH = 14;                         // ύψος barcode block
    $barcodeX = $rightX + ($rightColWidth - ($barcodeW - 15)); // καθαρή δεξιά στοίχιση
    $barcodeY = $curYRight;

    try {
        $pdf->write1DBarcode($barcodeText, 'C128', $barcodeX, $barcodeY, $barcodeW, $barcodeH, 0.4, $style, 'R');
    } catch (\Throwable $e) { /* ignore barcode errors */ }

    $curYRight = $barcodeY + $barcodeH + 2;
}

// κλειδώνουμε ύψος header και κατεβάζουμε τον κέρσορα
$afterHeaderY = max($curYLeft, $curYRight, $headerTopY + $headerHeight);
$pdf->SetY($afterHeaderY);
$pdf->Ln(2);

/* =============================================================
 * Τίτλος & Meta γραμμή
 * =========================================================== */
$pdf->SetFont($font_name, 'B', 15);
$pdf->Cell(0, 0, ((_l('lims_order') ?: 'Order') . ' #' . (int)$order->id), 0, 1, 'L');
$pdf->Ln(1);
$pdf->SetFont($font_name, '', $font_size);

$status   = (string)($order->status ?? 'draft');
$priority = lims_priority_label($order->priority);
$created  = !empty($order->created_at) ? _dt($order->created_at) : '—';
$due      = !empty($order->due_at)     ? _dt($order->due_at)     : '—';

$metaRow = sprintf(
    '%s: %s   |   %s: %s   |   %s: %d   |   %s: %s',
    (_l('date_created') ?: 'Created'), $created,
    (_l('due_date') ?: 'Due'), $due,
    (_l('priority') ?: 'Priority'), $priority,
    (_l('status') ?: 'Status'), ucfirst($status)
);
$pdf->SetTextColor(80,80,80);
$pdf->MultiCell(0, 0, $metaRow, 0, 'L', 0, 1);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(2);

/* =============================================================
 * 3-στήλες: Subject | Client | Order Meta (+ Contract)
 * =========================================================== */
$colSubject   = [];
$colClient    = [];
$colOrderMeta = [];

/* -------- Subject -------- */
$colSubject[] = '<b>'.(_l('lims_subject') ?: 'Subject').'</b>';

if ($subject) {
    // Όνομα subject – προσπάθησε διάφορα πεδία
    $name = '';
    if (!empty($subject->subject_name))        $name = $subject->subject_name;
    elseif (!empty($subject->first_name) || !empty($subject->last_name)) {
        $name = trim(($subject->first_name ?? '').' '.($subject->last_name ?? ''));
    }

    if ($name === '') {
        $name = 'Subject #'.(int)$subject->id;
    }

    $colSubject[] = html_escape($name).' (#'.(int)$subject->id.')';

    // ID / Passport / Internal code / Email (ό,τι υπάρχει)
    $idParts = [];

    if (!empty($subject->id_number)) {
        $idParts[] = 'ID: '.html_escape($subject->id_number);
    }
    if (!empty($subject->passport_number)) {
        $idParts[] = 'Passport: '.html_escape($subject->passport_number);
    } elseif (!empty($subject->passport)) {
        $idParts[] = 'Passport: '.html_escape($subject->passport);
    }
    if (!empty($subject->social_insurance_no)) {
        $colSubject[] = 'Social Insurance: ' . _d($subject->social_insurance_no);
    }

    if (!empty($subject->internal_code)) {
        $idParts[] = 'Internal: '.html_escape($subject->internal_code);
    } elseif (!empty($subject->code)) {
        $idParts[] = 'Internal: '.html_escape($subject->code);
    }

    if (!empty($subject->email)) {
        $idParts[] = 'Email: '.html_escape($subject->email);
    }

    if ($idParts) {
        $colSubject[] = implode('<br>', $idParts);
    }

    // optional: phone / dob
    if (!empty($subject->phone)) {
        $colSubject[] = 'Phone: '.html_escape($subject->phone);
    }
    if (!empty($subject->date_of_birth)) {
        $colSubject[] = 'DOB: ' . _d($subject->date_of_birth);
    }
} else {
    $colSubject[] = '—';
}

/* -------- Client (Billing) -------- */
$colClient[] = '<b>'.(_l('client') ?: 'Client').'</b>';

if ($client) {
    $colClient[] = html_escape(get_company_name($client->userid)) . ' (#' . (int)$client->userid . ')';

    if (!empty($client->address)) {
        $colClient[] = html_escape($client->address);
    }

    $cityLine = [];
    if (!empty($client->city))    $cityLine[] = html_escape($client->city);
    if (!empty($client->state))   $cityLine[] = html_escape($client->state);
    if (!empty($client->country)) $cityLine[] = html_escape($client->country);
    if ($cityLine) {
        $colClient[] = implode(', ', $cityLine);
    }

    if (!empty($client->phonenumber)) {
        $colClient[] = (_l('clients_phone') ?: 'Phone').': '.html_escape($client->phonenumber);
    }
    if (!empty($client->email)) {
        $colClient[] = (_l('clients_email') ?: 'Email').': '.html_escape($client->email);
    }
} else {
    $colClient[] = '—';
}

/* -------- Contract & Order Meta (δεξιά) -------- */

// Contract detection
$contractId = null;
if (!empty($order->contract_id)) {
    $contractId = (int)$order->contract_id;
} else {
    $probe = $CI->db->select('from_contract_id')
        ->where('order_id', (int)$order->id)
        ->where('from_contract_id IS NOT NULL', null, false)
        ->limit(1)->get(db_prefix().'lims_order_items')->row();
    if ($probe) { $contractId = (int)$probe->from_contract_id; }
}

$contractLabel = '—';
if ($contractId) {
    $cRow = $CI->db->where('id', $contractId)->get(db_prefix().'lims_contracts')->row();
    if ($cRow) {
        $contractLabel = html_escape($cRow->name).' (#'.(int)$cRow->id.')';
    }
}

$colOrderMeta[] = '<b>'.(_l('lims_order') ?: 'Order').' #'.(int)$order->id.'</b>';
$colOrderMeta[] = (_l('status') ?: 'Status').': '.ucfirst($status ?: 'draft');
$colOrderMeta[] = (_l('date_created') ?: 'Created').': '.$created;
$colOrderMeta[] = (_l('due_date') ?: 'Due').': '.$due;
$colOrderMeta[] = (_l('priority') ?: 'Priority').': '.$priority;
$colOrderMeta[] = (_l('lims_contract') ?: 'Contract').': '.$contractLabel;

// TCPDF-safe table (χωρίς THEAD)
$tbl  = '<table width="100%" border="0" cellpadding="4" cellspacing="0"><tbody><tr>';
$tbl .= '<td width="33%" valign="top" style="color:#424242;">'.implode('<br>', $colSubject).'</td>';
$tbl .= '<td width="33%" valign="top" style="color:#424242;">'.implode('<br>', $colClient).'</td>';
$tbl .= '<td width="34%" valign="top" style="color:#424242;">'.implode('<br>', $colOrderMeta).'</td>';
$tbl .= '</tr></tbody></table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Ln(2);

/* =============================================================
 * Items (Panels / Analyses / Cultures) σε 3 στήλες
 * =========================================================== */
$lines = $CI->db->where('order_id',(int)$order->id)
    ->order_by('id','ASC')->get(db_prefix().'lims_order_items')->result();

// Συλλογή id ανά είδος
$panelIds = $analysisIds = $cultureIds = [];
foreach ((array)$lines as $ln) {
    if ($ln->source_type === 'panel')    $panelIds[]    = (int)$ln->source_id;
    if ($ln->source_type === 'analysis') $analysisIds[] = (int)$ln->source_id;
    if ($ln->source_type === 'culture')  $cultureIds[]  = (int)$ln->source_id;
}
$panelIds    = array_values(array_unique($panelIds));
$analysisIds = array_values(array_unique($analysisIds));
$cultureIds  = array_values(array_unique($cultureIds));

// Panels -> μέλη (analyses & cultures κάτω από κάθε panel)
$panelAnalyses = [];
if (!empty($panelIds)) {
    $p = db_prefix();

    // analyses
    $rows = $CI->db->select("pi.panel_id, a.name AS an_name, a.code AS an_code")
        ->from("{$p}lims_panel_items pi")
        ->join("{$p}lims_analyses a", "a.id = pi.analysis_id", "left")
        ->where_in('pi.panel_id', $panelIds)
        ->order_by('pi.sort_order', 'ASC')->get()->result_array();

    foreach ($rows as $r) {
        $pid = (int)$r['panel_id'];
        if (!isset($panelAnalyses[$pid])) $panelAnalyses[$pid] = [];
        if (!empty($r['an_name'])) {
            $panelAnalyses[$pid][] = [
                'name' => (string)$r['an_name'],
                'code' => (string)($r['an_code'] ?? '')
            ];
        }
    }

    // cultures (προαιρετικά)
    $crows = $CI->db->select("pi.panel_id, c.name AS cu_name, c.code AS cu_code")
        ->from("{$p}lims_panel_items pi")
        ->join("{$p}lims_cultures c", "c.id = pi.culture_id", "left")
        ->where_in('pi.panel_id', $panelIds)
        ->where('pi.culture_id IS NOT NULL', null, false)
        ->order_by('pi.sort_order', 'ASC')->get()->result_array();

    foreach ($crows as $r) {
        $pid = (int)$r['panel_id'];
        if (!isset($panelAnalyses[$pid])) $panelAnalyses[$pid] = [];
        if (!empty($r['cu_name'])) {
            $panelAnalyses[$pid][] = [
                'name' => (string)$r['cu_name'],
                'code' => (string)($r['cu_code'] ?? '')
            ];
        }
    }
}

// Cultures -> λεπτομέρειες (sample type, min_volume)
$cultureDetails = [];
if (!empty($cultureIds)) {
    $p = db_prefix();
    $cRows = $CI->db->select("c.id,c.name,c.code,c.sample_type_id, st.name AS st_name, st.code AS st_code, st.min_volume AS st_min_volume")
        ->from("{$p}lims_cultures c")
        ->join("{$p}lims_sample_types st", "st.id = c.sample_type_id", "left")
        ->where_in('c.id', $cultureIds)->get()->result();
    foreach ($cRows as $r) { $cultureDetails[(int)$r->id] = $r; }
}

// Συνθέσεις για 3 στήλες
$displayPanels   = [];
$displayAnalyses = [];
$displayCultures = [];

foreach ($lines as $ln) {
    if ($ln->source_type === 'panel') {
        $displayPanels[] = ['id' => (int)$ln->source_id, 'name' => $ln->name];
    } elseif ($ln->source_type === 'analysis') {
        $displayAnalyses[] = ['id' => (int)$ln->source_id, 'name' => $ln->name];
    } elseif ($ln->source_type === 'culture') {
        $cid   = (int)$ln->source_id;
        $st    = $cultureDetails[$cid] ?? null;
        $stName = $st->st_name ?? '';
        $minVol = $st->st_min_volume ?? '';
        $displayCultures[] = [
            'id'            => $cid,
            'name'          => $ln->name,
            'st_name'       => $stName,
            'min_volume_ml' => $minVol,
        ];
    }
}

$pdf->SetFont($font_name, 'B', 12);
$pdf->Cell(0, 0, (_l('lims_select_services') ?: 'Selected services'), 0, 1);
$pdf->Ln(1);
$pdf->SetFont($font_name, '', $font_size);

// Στήλη Panels
$col1 = '<ul style="margin:0; padding-left:12px;">';
if (!empty($displayPanels)) {
    foreach ($displayPanels as $pnl) {
        $col1 .= '<li><b>'.html_escape($pnl['name']).'</b>';
        if (!empty($panelAnalyses[$pnl['id']])) {
            $col1 .= '<div style="color:#777; font-size:90%; margin-top:2px;"><em>'
                  .((_l('lims_analyses') ?: 'Analyses'))
                  .':</em><ul style="margin:2px 0 0 12px;">';
            foreach ($panelAnalyses[$pnl['id']] as $a) {
                $col1 .= '<li>'.html_escape($a['name']).'</li>';
            }
            $col1 .= '</ul></div>';
        }
        $col1 .= '</li>';
    }
} else {
    $col1 .= '<li style="color:#999;">'.((_l('no_items_found') ?: 'None')).'</li>';
}
$col1 .= '</ul>';

// Στήλη Analyses
$col2 = '<ul style="margin:0; padding-left:12px;">';
if (!empty($displayAnalyses)) {
    foreach ($displayAnalyses as $an) {
        $col2 .= '<li><b>'.html_escape($an['name']).'</b></li>';
    }
} else {
    $col2 .= '<li style="color:#999;">'.((_l('no_items_found') ?: 'None')).'</li>';
}
$col2 .= '</ul>';

// Στήλη Cultures
$col3 = '<ul style="margin:0; padding-left:12px;">';
if (!empty($displayCultures)) {
    foreach ($displayCultures as $cu) {
        $extra = [];
        if (!empty($cu['st_name'])) $extra[] = html_escape($cu['st_name']);
        if ($cu['min_volume_ml'] !== '' && $cu['min_volume_ml'] !== null) {
            $vol = is_numeric($cu['min_volume_ml']) ? (0 + $cu['min_volume_ml']) : $cu['min_volume_ml'];
            $extra[] = ((_l('lims_min_volume') ?: 'Min vol').': '.$vol.' ml');
        }
        $meta = $extra ? (' <span style="color:#777;">('.implode(' · ', $extra).')</span>') : '';
        $col3 .= '<li><b>'.html_escape($cu['name']).'</b>'.$meta.'</li>';
    }
} else {
    $col3 .= '<li style="color:#999;">'.((_l('no_items_found') ?: 'None')).'</li>';
}
$col3 .= '</ul>';

// TCPDF-safe table (χωρίς THEAD)
$tbl  = '<table width="100%" border="0" cellpadding="6" cellspacing="0"><tbody>';
$tbl .= '<tr style="background-color:#f3f3f3;font-weight:bold;">'
      . '<td width="33%">' . ((_l('lims_panels') ?: 'Panels'))    . '</td>'
      . '<td width="33%">' . ((_l('lims_analyses') ?: 'Analyses')) . '</td>'
      . '<td width="34%">' . ((_l('lims_cultures') ?: 'Cultures')) . '</td>'
      . '</tr>';
$tbl .= '<tr>';
$tbl .= '<td width="33%" valign="top">'.$col1.'</td>';
$tbl .= '<td width="33%" valign="top">'.$col2.'</td>';
$tbl .= '<td width="34%" valign="top">'.$col3.'</td>';
$tbl .= '</tr></tbody></table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Ln(2);

/* =============================================================
 * Samples
 * =========================================================== */
$pdf->SetFont($font_name, 'B', 12);
$pdf->Cell(0, 0, (_l('lims_samples') ?: 'Samples'), 0, 1);
$pdf->Ln(1);
$pdf->SetFont($font_name, '', $font_size);

$samples = $CI->db->where('order_id',(int)$order->id)
    ->order_by('id','ASC')->get(db_prefix().'lims_samples')->result();

if (!empty($samples)) {
    $tbl  = '<table width="100%" cellpadding="6" cellspacing="0" border="0"><tbody>';
    $tbl .= '<tr style="background-color:#f3f3f3;font-weight:bold;">'
          . '<td width="18%">' . ((_l('lims_sample') ?: 'Sample'))          . '</td>'
          . '<td width="20%">' . ((_l('barcode') ?: 'Barcode'))              . '</td>'
          . '<td width="22%">' . ((_l('lims_sample_type') ?: 'Sample Type')) . '</td>'
          . '<td width="22%">' . ((_l('status') ?: 'Status'))                . '</td>'
          . '<td width="18%">' . ((_l('lims_collected_at') ?: 'Collected At')). '</td>'
          . '</tr>';
    foreach ($samples as $s) {
        $stName = '—';
        if (!empty($s->sample_type_id)) {
            $st = $CI->db->select('name')->where('id', (int)$s->sample_type_id)
                ->get(db_prefix().'lims_sample_types')->row();
            if ($st) $stName = $st->name;
        }
        $statusS = (string)($s->status ?? '');
        $colTxt = '#757575'; $label = ucfirst($statusS ?: 'pending');
        if ($statusS === 'collected') { $colTxt = '#2E7D32'; $label = 'Collected'; }

        $tbl .= '<tr>'
              . '<td>'.html_escape($s->sample_uid ?: ('#'.$s->id)).'</td>'
              . '<td>'.html_escape($s->barcode ?: '—').'</td>'
              . '<td>'.html_escape($stName).'</td>'
              . '<td><span style="color:'.$colTxt.';font-weight:600;">'.$label.'</span></td>'
              . '<td>'.(!empty($s->collected_at) ? _dt($s->collected_at) : '—').'</td>'
              . '</tr>';
    }
    $tbl .= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
} else {
    $pdf->SetTextColor(120,120,120);
    $pdf->Write(0, (_l('no_items_found') ?: 'None'), '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetTextColor(0,0,0);
}
$pdf->Ln(2);

/* =============================================================
 * Appointment
 * =========================================================== */
$pdf->SetFont($font_name, 'B', 12);
$pdf->Cell(0, 0, (_l('lims_appointments') ?: 'Appointments'), 0, 1);
$pdf->Ln(1);
$pdf->SetFont($font_name, '', $font_size);

if ($appointment) {
    $meta = [];
    if (!empty($appointment->appointment_at)) {
        $meta[] = ((_l('lims_appointment_at') ?: 'Appointment At') . ': ' . _dt($appointment->appointment_at));
    }
    if (!empty($appointment->visit_type)) {
        $meta[] = ((_l('lims_visit_type') ?: 'Visit Type') . ': ' . ucfirst($appointment->visit_type));
    }
    if (!empty($appointment->assigned_staff)) {
        $meta[] = ((_l('lims_assigned_staff') ?: 'Assigned Staff') . ': ' . get_staff_full_name($appointment->assigned_staff));
    }
    if (!empty($appointment->status)) {
        $meta[] = ((_l('status') ?: 'Status') . ': ' . ucfirst($appointment->status));
    }
    if (!empty($appointment->location_text)) {
        $meta[] = ((_l('location') ?: 'Location') . ': ' . $appointment->location_text);
    }

    if (!empty($meta)) {
        $safeMeta = array_map('html_escape', $meta);
        $pdf->writeHTML(
            '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td style="color:#424242;">'
            .implode('<br>', $safeMeta)
            .'</td></tr></tbody></table>',
            true, false, false, false, ''
        );
    } else {
        $pdf->SetTextColor(120,120,120);
        $pdf->Write(0, (_l('no_items_found') ?: 'None'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetTextColor(0,0,0);
    }

    // Static map (αν επιτρέπεται remote image από server)
    if (!empty($appointment->lat) && !empty($appointment->lng)) {
        $lat = (float)$appointment->lat;
        $lng = (float)$appointment->lng;
        $mapUrl = 'https://staticmap.openstreetmap.de/staticmap.php?center='
            .rawurlencode($lat.','.$lng)
            .'&zoom=15&size=700x320&markers='
            .rawurlencode($lat.','.$lng).',lightblue1';

        $pdf->Ln(3);
        try {
            $pdf->Image($mapUrl, '', '', 140, 64, '', '', 'T', true, 300, '', false, false, 1, false, false, false);
        } catch (\Throwable $e) { /* ignore if remote blocked */ }
    }
} else {
    $pdf->SetTextColor(120,120,120);
    $pdf->Write(0, (_l('no_items_found') ?: 'None'), '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetTextColor(0,0,0);
}

/* =============================================================
 * Notes (FULL WIDTH)
 * =========================================================== */
if (!empty($order->notes)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', 12);
    $pdf->Cell(0, 0, (_l('notes') ?: 'Notes'), 0, 1, 'L');
    $pdf->Ln(1);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->writeHTML('<div>'.nl2br(html_escape($order->notes)).'</div>', true, false, false, false, '');
}

/* =============================================================
 * Footer
 * =========================================================== */
$pdf->Ln(6);
$pdf->SetFont($font_name, '', 9);
$footer = (string)get_option('pdf_footer');
if ($footer !== '') {
    $pdf->writeHTML(
        '<div style="text-align:center;color:#999">'.html_escape($footer).'</div>',
        true, false, false, false, ''
    );
}
