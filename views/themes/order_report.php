<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @var TCPDF $pdf
 * @var object $order
 * @var array  $samples
 * @var array  $testsBySample
 * @var array  $resultsByTest
 * @var array  $orderCultures
 * @var array  $cultureResultsBySample
 * @var array  $cultureOptionsByCulture
 */

$CI = &get_instance();

$font = 'dejavusans';
$pdf->SetFont($font, '', 10);

// ===== HEADER =====
$pdf->SetFont($font, 'B', 14);
$pdf->Cell(0, 8, 'LIMS Report - Order #' . (int)$order->id, 0, 1, 'C');

$pdf->SetFont($font, '', 9);
$pdf->Ln(2);

$createdAt = !empty($order->created_at) ? _dt($order->created_at) : '-';
$dueAt     = !empty($order->due_at)    ? _dt($order->due_at)    : '-';

$pdf->Cell(0, 5, 'Created: ' . $createdAt, 0, 1, 'L');
$pdf->Cell(0, 5, 'Due: ' . $dueAt, 0, 1, 'L');

if (!empty($order->order_barcode)) {
    $pdf->Cell(0, 5, 'Order Barcode: ' . $order->order_barcode, 0, 1, 'L');
}

$pdf->Ln(2);
$pdf->Cell(0, 0, '', 'T', 1);
$pdf->Ln(4);

// ===== TESTS TABLE =====
$pdf->SetFont($font, 'B', 10);
$pdf->Cell(35, 7, 'Sample', 1, 0, 'L');
$pdf->Cell(60, 7, 'Test', 1, 0, 'L');
$pdf->Cell(25, 7, 'Result', 1, 0, 'L');
$pdf->Cell(20, 7, 'Unit', 1, 0, 'L');
$pdf->Cell(20, 7, 'Flag', 1, 1, 'L');

$pdf->SetFont($font, '', 9);

if (!empty($samples)) {
    foreach ($samples as $s) {
        $sampleTests = isset($testsBySample[$s->id]) ? $testsBySample[$s->id] : [];
        if (empty($sampleTests)) {
            continue;
        }

        $sampleLabel = !empty($s->sample_uid) ? $s->sample_uid : ('#' . $s->id);

        foreach ($sampleTests as $t) {
            $last = null;
            if (!empty($resultsByTest[$t->id])) {
                $last = $resultsByTest[$t->id][0];
            }

            $testLabel = $t->analysis_name ?? '';
            $resultVal = '';
            $unit      = '';
            $flag      = '';

            if ($last) {
                if ($t->result_type === 'numeric' && $last->value_numeric !== null) {
                    $resultVal = (string)$last->value_numeric;
                } else {
                    $resultVal = (string)$last->value_text;
                }
                $unit = $last->unit ?: ($t->units_ucum ?? '');
                $flag = $last->flag ?: '';
            }

            $pdf->Cell(35, 6, $sampleLabel, 1, 0, 'L');
            $pdf->Cell(60, 6, $testLabel,   1, 0, 'L');
            $pdf->Cell(25, 6, $resultVal,   1, 0, 'L');
            $pdf->Cell(20, 6, $unit,        1, 0, 'L');
            $pdf->Cell(20, 6, $flag,        1, 1, 'L');
        }
    }
} else {
    $pdf->Cell(160, 6, 'No samples / tests', 1, 1, 'C');
}

$pdf->Ln(5);
$pdf->SetFont($font, 'B', 11);
$pdf->Cell(0, 6, 'Cultures (placeholder)', 0, 1, 'L');
$pdf->SetFont($font, '', 9);

if (empty($orderCultures)) {
    $pdf->Cell(0, 5, 'No cultures for this order.', 0, 1, 'L');
} else {
    $pdf->Cell(0, 5, 'Cultures section will be designed later.', 0, 1, 'L');
}
