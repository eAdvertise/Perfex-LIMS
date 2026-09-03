<?php defined('BASEPATH') or exit('No direct script access allowed');

/** @var TCPDF $pdf */
/** @var object $order */
/** @var array<object> $samples */

$CI = &get_instance();

// ensure TCPDFBarcode is available
if (!class_exists('TCPDFBarcode')) {
    // path για Perfex 3.x
    @require_once(APPPATH . 'vendor/tecnickcom/tcpdf/tcpdf_barcodes_1d.php');
}

$font_name = isset($font_name) ? $font_name : 'dejavusans';
$font_size = (float) (get_option('lims_label_font_size') ?: 8);

// === Settings ===
$pageW = (float) get_option('lims_label_page_width_mm');
$pageH = (float) get_option('lims_label_page_height_mm');

$cols      = max(1, (int) get_option('lims_label_columns'));
$rows      = max(1, (int) get_option('lims_label_rows'));
$labelW    = (float) get_option('lims_label_width_mm');
$labelH    = (float) get_option('lims_label_height_mm');
$hGap      = (float) get_option('lims_label_hgap_mm');
$vGap      = (float) get_option('lims_label_vgap_mm');
$leftMar   = (float) get_option('lims_label_left_margin_mm');
$topMar    = (float) get_option('lims_label_top_margin_mm');

$barcodeH  = (float) get_option('lims_label_barcode_height');
if ($labelW <= 0 || $labelH <= 0) { $labelW = 70; $labelH = 35; }
if ($barcodeH < 6) { $barcodeH = 10; }

// === Grid helpers ===
$totalPerPage = $cols * $rows;
$labelsCount  = count($samples);
$pages        = (int) ceil($labelsCount / $totalPerPage);

// Header/Footer off (safety)
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Font baseline
$pdf->SetFont($font_name, '', $font_size);

// *** FIX: Μην προσθέτεις σελίδα στην αρχή, γιατί App_pdf έχει ήδη προσθέσει 1η σελίδα.
// Θα κάνουμε AddPage ΜΟΝΟ από τη 2η σελίδα και μετά.
$index = 0;
for ($p = 0; $p < $pages; $p++) {
    if ($p > 0) {
        $pdf->AddPage('P', [$pageW, $pageH]);
    }
    // προαιρετικά: καθάρισε τρέχουσα σελίδα αν κάτι έμεινε
    $pdf->SetXY(0, 0);

    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            if ($index >= $labelsCount) break;

            $s = $samples[$index++];
            // Label origin (top-left) in mm
            $x0 = $leftMar + $c * ($labelW + $hGap);
            $y0 = $topMar  + $r * ($labelH + $vGap);

            // Inner padding
            $padX = 2.0; $padY = 1.5;
            $innerX = $x0 + $padX;
            $innerY = $y0 + $padY;
            $innerW = max(1, $labelW - 2*$padX);

            // --- 1) Απόλυτα κεντραρισμένο Code128 barcode ---
			$barcodeText = (string) ($s->barcode ?: $order->order_barcode ?: ($s->sample_uid ?: ('S'.$s->id)));

			// style: δεν βασιζόμαστε στο align του TCPDF, κεντράρουμε χειροκίνητα
			$style = [
				'position' => '',
				'align'    => 'N',
				'stretch'  => false,
				'fitwidth' => false,
				'border'   => 0,
				'hpadding' => 0,
				'vpadding' => 0,
				'fgcolor'  => [0,0,0],
				'bgcolor'  => false,
				'text'     => false,
			];

			// πάρε το “module map” μέσω TCPDFBarcode
			$modules = 100.0; // fallback
			if (class_exists('TCPDFBarcode')) {
				try {
					$bgen   = new TCPDFBarcode($barcodeText, 'C128');
					$barArr = $bgen->getBarcodeArray();
					if (isset($barArr['maxw']) && $barArr['maxw'] > 0) {
						$modules = (float)$barArr['maxw'];
					}
				} catch (\Throwable $e) {
					// keep fallback
				}
			}

			// διαθέσιμο πλάτος μέσα στο label
			$targetW = $innerW * 0.95;                 // 95% για quiet zones
			$moduleW = $targetW / $modules;            // πλάτος ανά module
			$moduleW = max(0.28, min(0.6, $moduleW));  // clamp για αναγνωσιμότητα
			$actualW = $modules * $moduleW;

			// κεντράρισμα
			$barcodeX = $innerX + ($innerW - $actualW) / 2.0;
			$barcodeY = $innerY;

			// σχεδίαση
			$pdf->write1DBarcode(
				$barcodeText,
				'C128',
				$barcodeX,
				$barcodeY,
				$actualW,
				$barcodeH,
				$moduleW, // x-dim
				$style,
				'N'
			);

			// ενημέρωση cursor
			$cursorY = $barcodeY + $barcodeH + 0.6;

            $pdf->SetFont($font_name, 'B', $font_size);
            $pdf->SetXY($innerX, $cursorY);
            $pdf->MultiCell($innerW, 0, $barcodeText, 0, 'C', 0, 1, '', '', true);
            $cursorY = $pdf->GetY() + 0.2;

            // --- 3) 2η γραμμή (μικρά, κεντραρισμένα): Order#ID - UID - Sample Type ---
            // UID: προτιμάμε $s->sample_uid, fallback σε #id
            $uid = !empty($s->sample_uid) ? $s->sample_uid : ('#'.$s->id);
            $stype = !empty($s->sample_type_name) ? $s->sample_type_name : '';
            $line2 = 'Order#'.(int)$order->id.' - '.$uid.($stype !== '' ? (' - '.$stype) : '');

            $pdf->SetFont($font_name, '', max(6, $font_size - 1.5));
            $pdf->SetXY($innerX, $cursorY);
            $pdf->MultiCell($innerW, 0, $line2, 0, 'C', 0, 1, '', '', true);
            // $cursorY = $pdf->GetY();  // (δεν το χρειαζόμαστε πιο κάτω)

            // (προαιρετικό) βοηθητικό border για δοκιμές:
            //$pdf->Rect($x0, $y0, $labelW, $labelH, 'D', [], [230,230,230]);
        }
    }
}
