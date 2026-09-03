<?php
defined('BASEPATH') or exit('No direct script access allowed');
//views/themes.sample_lables.php
/** @var TCPDF $pdf */
/** @var object $order */
/** @var array  $samples */

$CI = &get_instance();

$w_mm   = (float) (get_option('lims_label_width_mm') ?: 60);
$h_mm   = (float) (get_option('lims_label_height_mm') ?: 30);
$mg_mm  = (float) (get_option('lims_label_margin_mm') ?: 2);
$fsize  = (float) (get_option('lims_label_font_size') ?: 8);
$bh_mm  = (float) (get_option('lims_label_barcode_height') ?: 12);
$useOrd = get_option('lims_label_include_order_received_at') == '1';

$font_name = isset($font_name) ? $font_name : 'dejavusans';

// Για κάθε sample → νέα σελίδα custom size
foreach ($samples as $s) {
    // ορίσε custom σελίδα σε mm
    $pdf->AddPage('P', [$w_mm, $h_mm]);

    $pdf->SetMargins($mg_mm, $mg_mm, $mg_mm);
    $pdf->SetAutoPageBreak(true, $mg_mm);
    $pdf->SetFont($font_name, '', $fsize);

    $usableW = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];

    // 1) BARCODE (Code128)
    $codeText = (string)($s->barcode ?: $order->order_barcode ?: $s->sample_uid ?: ('S'.$s->id));
    $style = [
        'position' => '',
        'align'    => 'C',
        'stretch'  => false,
        'fitwidth' => true,
        'border'   => 0,
        'hpadding' => 0,
        'vpadding' => 0,
        'fgcolor'  => [0,0,0],
        'bgcolor'  => false,
        'text'     => false, // κείμενο θα το βάλουμε από κάτω εμείς
    ];

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->write1DBarcode($codeText, 'C128', $x, $y, $usableW, $bh_mm, 0.35, $style, 'C');

    // Μετά το barcode, λίγο κενό
    $pdf->SetY($y + $bh_mm + 1);

    // 2) Barcode text (μεσαία γραμμή)
    $pdf->SetFont($font_name, 'B', $fsize + 1);
    $pdf->Cell(0, 0, $codeText, 0, 1, 'C');
    $pdf->Ln(0.5);

    // 3) Analysis names (όλες που ανήκουν στο sample)
    //   - βρίσκουμε tests για το sample και φέρνουμε ονόματα αναλύσεων
    $tests = $CI->db->select('t.analysis_id, a.name')
        ->from(db_prefix().'lims_tests t')
        ->join(db_prefix().'lims_analyses a', 'a.id=t.analysis_id', 'left')
        ->where('t.sample_id', (int)$s->id)->get()->result();

    $anaNames = [];
    foreach ($tests as $t) {
        if (!empty($t->name)) { $anaNames[] = $t->name; }
    }
    $anaText = $anaNames ? implode(', ', array_slice($anaNames, 0, 4)) : ''; // bounded, για να χωράει

    if ($anaText !== '') {
        $pdf->SetFont($font_name, '', $fsize);
        $pdf->MultiCell(0, 0, $anaText, 0, 'C', 0, 1);
    }

    // 4) Sample type name
    $stName = '';
    if (!empty($s->sample_type_id)) {
        $st = $CI->db->select('name')->where('id',(int)$s->sample_type_id)->get(db_prefix().'lims_sample_types')->row();
        if ($st && !empty($st->name)) $stName = $st->name;
    }

    // 5) Received date (order->received_at αν υπάρχει & flag, αλλιώς sample->created_at)
    $dateStr = '';
    if ($useOrd && !empty($order->received_at)) {
        $dateStr = _dt($order->received_at);
    } else {
        $dateStr = !empty($s->created_at) ? _dt($s->created_at) : '';
    }

    // Τελευταίες 2 σειρές, μικρό font
    $pdf->SetFont($font_name, '', max(6, $fsize - 1));
    if ($stName !== '') {
        $pdf->Cell(0, 0, $stName, 0, 1, 'C');
    }
    if ($dateStr !== '') {
        $pdf->SetTextColor(80,80,80);
        $pdf->Cell(0, 0, $dateStr, 0, 1, 'C');
        $pdf->SetTextColor(0,0,0);
    }
}
