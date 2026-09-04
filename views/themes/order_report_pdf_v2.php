<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Variables provided:
 * - $pdf (TCPDF)
 * - $order (object)
 * - $settings (array)
 * - $samples (array of objects)
 * - $resultRowsBySample (array)
 * - $analysisAtBySample (array sample_id => datetime)
 * - $reportNotesBySample (array sample_id => ['free_text','items'])
 * - $culturesBySample (array sample_id => cultures[])
 * - $cultureResultsByKey (array "sample:culture" => data)
 * - $signature (array)
 */

$lang = $settings['lang'] ?? 'el';
$tr = function($el, $en) use ($lang) { return $lang === 'en' ? $en : $el; };

$font = $settings['font_family'] ?: 'dejavuserif';
$base = (float)($settings['font_size'] ?: 10);

$pdf->SetFont($font, '', $base);

// Build owner label/value (subject_type rule)
$subjectType = strtolower(trim((string)($order->subject_type ?? '')));
$isPerson = in_array($subjectType, ['patient','doctor'], true);

$ownerLabel = $isPerson ? $tr('Όνομα', 'Name') : $tr('Επωνυμία Ιδιοκτήτριας Εταιρείας', 'Owner Company Name');

$nameParts = [];
if (!empty($order->subject_name)) { $nameParts[] = $order->subject_name; }
if (!empty($order->first_name))   { $nameParts[] = $order->first_name; }
if (!empty($order->last_name))    { $nameParts[] = $order->last_name; }
$subjectFull = trim(implode(' ', $nameParts));

$ownerValue = $isPerson
    ? $subjectFull
    : ((string)($order->client_company ?? '') ?: $subjectFull);

// Dates (issue date)
$issuedAt = !empty($order->signed_at) ? _dt($order->signed_at) : _dt(date('Y-m-d H:i:s'));

// Loop samples => 1 page each
$first = true;
foreach ((array)$samples as $sm) {

    if (!$first) {
        $pdf->AddPage();
    }
    $first = false;

    $sampleId = (int)$sm->id;

    // Sampling datetime/time + sampler from appointment (fallback collected_at)
    $samplingDT = !empty($sm->appointment_at) ? $sm->appointment_at : (!empty($sm->collected_at) ? $sm->collected_at : '');
    $samplingDate = $samplingDT ? _d($samplingDT) : '';
    $samplingTime = $samplingDT ? date('H:i', strtotime($samplingDT)) : '';

    $sampler = (string)($sm->sampler_name ?? '');

    // Received at lab
    $received = !empty($sm->received_at) ? _d($sm->received_at) : '';

    // Analysis date (best effort)
    $analysisDT = $analysisAtBySample[$sampleId] ?? '';
    $analysisDate = $analysisDT ? _d($analysisDT) : '';

    // Analyst (best effort - not always known)
    $analyst = '';

    // Lab registration no (use sample_uid)
    $labReg = (string)($sm->sample_uid ?? '');

    // Report ref (use order id)
    $reportRef = (string)((int)($order->id ?? 0));

    // Sample type + packaging
    $sampleType = (string)($sm->sample_type_name ?? '');
    $packaging  = (string)($sm->sample_container ?? '');
    $statusPack = trim(($sm->status ?? '') . ($packaging !== '' ? (' / ' . $packaging) : ''));

    // Info table like template (keeps layout even if some values empty)
    $html = '
    <table cellpadding="3" cellspacing="0" style="width:100%;border-collapse:collapse;font-size:10px;">
      <tr>
        <td style="border:2px solid #e5e5e5;width:50%;"><strong>'.$tr('Ημερομηνία Δειγματοληψίας:', 'Sampling date:').'</strong> '.$samplingDate.'</td>
        <td style="border:2px solid #e5e5e5;width:50%;"><strong>'.$tr('Ημερομηνία Παραλαβής στο Εργαστήριο:', 'Received at lab:').'</strong> '.$received.'</td>
      </tr>
      <tr>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Ώρα:', 'Time:').'</strong> '.$samplingTime.'</td>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Ημερομηνία Ανάλυσης:', 'Analysis date:').'</strong> '.$analysisDate.'</td>
      </tr>
      <tr>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Είδος Δείγματος:', 'Sample type:').'</strong> '.htmlspecialchars($sampleType, ENT_QUOTES, 'UTF-8').'</td>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Αναλυτής:', 'Analyst:').'</strong> '.htmlspecialchars($analyst, ENT_QUOTES, 'UTF-8').'</td>
      </tr>
      <tr>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Κατάσταση Δείγματος/Συσκευασία:', 'Status/Packaging:').'</strong> '.htmlspecialchars($statusPack, ENT_QUOTES, 'UTF-8').'</td>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Αρ. Καταχώρησης Εργαστηρίου:', 'Lab reg. no:').'</strong> '.htmlspecialchars($labReg, ENT_QUOTES, 'UTF-8').'</td>
      </tr>
      <tr>
        <td style="border:2px solid #e5e5e5;"><strong>*'.$tr('Δειγματολήπτης:', 'Sampler:').'</strong> '.htmlspecialchars($sampler, ENT_QUOTES, 'UTF-8').'</td>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Αρ. Αναφοράς:', 'Report ref:').'</strong> '.htmlspecialchars($reportRef, ENT_QUOTES, 'UTF-8').'</td>
      </tr>
      <tr>
        <td style="border:2px solid #e5e5e5;"><strong>'.$tr('Αρ. Φακέλου Κερματοδέκτη:', 'File no:').'</strong> </td>
        <td style="border:2px solid #e5e5e5;"><strong>'.htmlspecialchars($ownerLabel, ENT_QUOTES, 'UTF-8').':</strong> '.htmlspecialchars($ownerValue, ENT_QUOTES, 'UTF-8').'</td>
      </tr>
      <tr>
        <td colspan="2" style="border:2px solid #e5e5e5;text-align:center;"><strong>'.$tr('Ημερομηνία Έκδοσης Αποτελεσμάτων:', 'Results issue date:').'</strong> '.$issuedAt.'</td>
      </tr>
    </table>';

    $pdf->Ln(2);
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(4);

    // -------- Results table --------
    $col1 = $tr('Παράμετρος', 'Parameter');
    $col2 = $tr('Μέθοδος Εξέτασης', 'Method');
    $col3 = $tr('Μονάδες', 'Units');
    $col4 = $tr('Αποτέλεσμα', 'Result');

    $rows = $resultRowsBySample[$sampleId] ?? [];

    $html = '
    <table cellpadding="3" cellspacing="0" style="width:100%;font-size:10px;border-collapse:collapse;">
      <thead>
        <tr>
          <th style="border:2px solid #e5e5e5;width:38%;text-align:center;"><strong>'.$col1.'</strong></th>
          <th style="border:2px solid #e5e5e5;width:28%;text-align:center;"><strong>'.$col2.'</strong></th>
          <th style="border:2px solid #e5e5e5;width:14%;text-align:center;"><strong>'.$col3.'</strong></th>
          <th style="border:2px solid #e5e5e5;width:20%;text-align:center;"><strong>'.$col4.'</strong></th>
        </tr>
      </thead>
      <tbody>
    ';

    if (!empty($rows)) {
        foreach ($rows as $r) {
            // allow object or array
            $param  = is_array($r) ? ($r['parameter'] ?? '') : ($r->parameter ?? '');
            $method = is_array($r) ? ($r['method'] ?? '') : ($r->method ?? '');
            $units  = is_array($r) ? ($r['units'] ?? '') : ($r->units ?? '');
            $res    = is_array($r) ? ($r['result'] ?? '') : ($r->result ?? '');

            $html .= '
              <tr>
                <td style="border:2px solid #e5e5e5;width:38%;text-align:center;">'.htmlspecialchars((string)$param, ENT_QUOTES, 'UTF-8').'</td>
                <td style="border:2px solid #e5e5e5;width:28%;text-align:center;">'.htmlspecialchars((string)$method, ENT_QUOTES, 'UTF-8').'</td>
                <td style="border:2px solid #e5e5e5;width:14%;text-align:center;">'.htmlspecialchars((string)$units, ENT_QUOTES, 'UTF-8').'</td>
                <td style="border:2px solid #e5e5e5;width:20%;text-align:center;">'.htmlspecialchars((string)$res, ENT_QUOTES, 'UTF-8').'</td>
              </tr>
            ';
        }
    } else {
        $html .= '
          <tr>
            <td colspan="4" style="border:1px solid #e5e5e5;text-align:center;color:#666;">—</td>
          </tr>
        ';
    }

    $html .= '</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(4);

    // -------- Notes (Σημ.:*) from Laboratory Test --------
    $notes = $reportNotesBySample[$sampleId] ?? ['free_text' => '', 'items' => []];
    $items = (array)($notes['items'] ?? []);
    $free  = (string)($notes['free_text'] ?? '');

    if (!empty($items) || trim($free) !== '') {

        $pdf->SetFont($font, '', $base);
        $pdf->Cell(0, 5, $tr('Σημ.:*', 'Notes:*'), 0, 1, 'L');

        $pdf->SetFont($font, '', $base - 1);

        $i = 1;
        foreach ($items as $t) {
            $t = trim((string)$t);
            if ($t === '') continue;
            $pdf->MultiCell(0, 4, $i . '. ' . $t, 0, 'L', false, 1);
            $i++;
        }

        if (trim($free) !== '') {
            // free text as final paragraph
            $pdf->Ln(1);
            $pdf->MultiCell(0, 4, $free, 0, 'L', false, 1);
        }

        $pdf->Ln(6);
    }

    // -------- Signature block --------
	$showSig = !empty($settings['show_signature']);
	$hasSigContent = !empty($signature) && (!empty($signature['name']) || (!empty($signature['image_path']) && is_file($signature['image_path'])));

	if ($showSig && $hasSigContent) {


        $pdf->SetFont($font, '', $base);
        $companyName = trim((string)($settings['invoice_company_name'] ?? ''));
		if ($companyName === '') {
			$companyName = 'D.A.K NutriLab Ltd'; // fallback
		}
		$pdf->Ln(2);
		$pdf->Cell(0, 5, $tr('Για την ', 'For ') . $companyName, 0, 1, 'L');

        $pdf->Ln(2);

        // Signature image
        if (!empty($signature['image_path']) && is_file($signature['image_path'])) {
            $w = (float)($signature['width_mm'] ?? 42);
            $x = 18;
            $y = $pdf->GetY();
            $pdf->Image($signature['image_path'], $x, $y, $w, 0, '', '', '', false, 300, '', false, false, 0);
            $pdf->Ln(18);
        } else {
            $pdf->Ln(12);
        }

        $pdf->SetFont($font, '', $base);
        $pdf->Cell(0, 5, (string)$signature['name'], 0, 1, 'L');
		// Signed at (date + time) under staff name
		$signedAt = (string)($signature['signed_at'] ?? '');
		if ($signedAt !== '') {
			// Use Perfex helpers if available
			if (function_exists('_dt')) {
				$signedAtTxt = _dt($signedAt);
			} else {
				$ts = strtotime($signedAt);
				$signedAtTxt = $ts ? date('Y-m-d H:i', $ts) : $signedAt;
			}

			$pdf->SetFont($font, '', $base - 1);
			$pdf->Cell(0, 5, $tr('Υπογράφηκε: ', 'Signed at: ') . $signedAtTxt, 0, 1, 'L');
			$pdf->SetFont($font, '', $base);
		}

        if (!empty($signature['title'])) {
            $pdf->Cell(0, 5, (string)$signature['title'], 0, 1, 'L');
        }

        if (!empty($signature['extra_line'])) {
            $pdf->Cell(0, 5, (string)$signature['extra_line'], 0, 1, 'L');
        }

        $pdf->Ln(2);
        $pdf->Cell(0, 5, $tr('Τέλος Έκθεσης', 'End of Report'), 0, 1, 'L');
    }

    // -------- Cultures on separate page --------
    $cultures = $culturesBySample[$sampleId] ?? [];
    if (!empty($cultures)) {
        $pdf->AddPage();

        $pdf->SetFont($font, '', $base + 1);
        $pdf->Cell(0, 8, $tr('Καλλιέργειες', 'Cultures'), 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->SetFont($font, '', $base);

        $html = '
        <table cellpadding="3" cellspacing="0" style="width:100%;font-size:10px;border-collapse:collapse;">
          <thead>
            <tr style="background: #e5e5e5;">
              <th style="border:2px solid #e5e5e5;width:30%;text-align:center;"><strong>'.$tr('Κωδικός', 'Code').'</strong></th>
              <th style="border:2px solid #e5e5e5;width:35%;text-align:center;"><strong>'.$tr('Καλλιέργεια', 'Culture').'</strong></th>
              <th style="border:2px solid #e5e5e5;width:35%;text-align:center;"><strong>'.$tr('Αποτέλεσμα', 'Result').'</strong></th>
            </tr>
          </thead>
          <tbody>
        ';

        foreach ($cultures as $c) {
            $cid = (int)($c['id'] ?? 0);
            $key = $sampleId . ':' . $cid;
            $cr  = $cultureResultsByKey[$key] ?? [];

            $resText = (string)($cr['result_text'] ?? '');
            if ($resText === '' && !empty($cr['selected_options'])) {
                $resText = (string)$cr['selected_options'];
            }

            $html .= '
            <tr>
              <td style="border:2px solid #e5e5e5;width:30%;text-align:center;">'.htmlspecialchars((string)($c['code'] ?? ''), ENT_QUOTES, 'UTF-8').'</td>
              <td style="border:2px solid #e5e5e5;width:35%;text-align:center;">'.htmlspecialchars((string)($c['name'] ?? ''), ENT_QUOTES, 'UTF-8').'</td>
              <td style="border:2px solid #e5e5e5;width:35%;text-align:center;">'.htmlspecialchars($resText, ENT_QUOTES, 'UTF-8').'</td>
            </tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
    }
}
