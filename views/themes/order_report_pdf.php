<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * LIMS Order Report PDF
 *
 * @var TCPDF $pdf
 * @var object $order
 * @var array  $samples
 * @var array  $testsBySample          [sample_id => test objects...]
 * @var array  $resultsByTest          [test_id => result objects (most recent first)...]
 * @var array  $culturesBySample       [sample_id => culture objects...]
 * @var array  $cultureResultsByKey    ["sample_id:culture_id" => row from lims_culture_results]
 * @var array  $cultureSelectionsByKey ["sample_id:culture_id" => [set_id => value_id]]
 
 
	/lims/views/themes/order_report_pdf.php
 */

$CI = &get_instance();
// ==== SIGNATURE META – ασφάλεια για να μην έχουμε Undefined variable ====

// Hardening defaults
$samples = (isset($samples) && is_array($samples)) ? $samples : [];
$testsBySample = (isset($testsBySample) && is_array($testsBySample)) ? $testsBySample : [];
$resultsByTest = (isset($resultsByTest) && is_array($resultsByTest)) ? $resultsByTest : [];
$culturesBySample = (isset($culturesBySample) && is_array($culturesBySample)) ? $culturesBySample : [];
$cultureResultsByKey = (isset($cultureResultsByKey) && is_array($cultureResultsByKey)) ? $cultureResultsByKey : [];
$cultureSelectionsByKey = (isset($cultureSelectionsByKey) && is_array($cultureSelectionsByKey)) ? $cultureSelectionsByKey : [];
$canSign = isset($canSign) ? (bool)$canSign : false;

/// NOTE: Tests_model::sign_order γράφει signed_by / signed_at
$signed_by_staff_id = isset($signed_by_staff_id)
    ? (int)$signed_by_staff_id
    : (property_exists($order, 'signed_by') ? (int)$order->signed_by
        : (property_exists($order, 'signed_by_staff_id') ? (int)$order->signed_by_staff_id : 0));

$signed_at = isset($signed_at)
    ? $signed_at
    : (property_exists($order, 'signed_at') ? $order->signed_at : null);


/**
 * ==== ΡΥΘΜΙΣΕΙΣ ΑΠΟ OPTIONS ====
 */
$license_number   = get_option('lims_report_license_number') ?: get_option('lims_pdf_license_number');
$header_title     = get_option('lims_report_header_title')   ?: get_option('lims_pdf_header_title');
$header_subtitle  = get_option('lims_report_header_subtitle')?: get_option('lims_pdf_header_subtitle');
$font_family      = get_option('lims_report_font_family')    ?: get_option('lims_pdf_font_family');
$font_size_opt    = get_option('lims_report_font_size')      ?: get_option('lims_pdf_font_size');
$footer_text_opt  = get_option('lims_report_footer_text')    ?: get_option('lims_pdf_footer_text');
$footer_image_opt = get_option('lims_report_footer_image')   ?: get_option('lims_pdf_footer_image');

$font_family = $font_family ?: 'helvetica';
$font_size   = (float)($font_size_opt ?: 10);

$pdf->SetFont($font_family, '', $font_size);

$page_width   = $pdf->getPageWidth();
$page_height  = $pdf->getPageHeight();
$margins      = $pdf->getMargins();
$left_margin  = $margins['left'];
$right_margin = $margins['right'];
$top_margin   = $margins['top'];

/**
 * ============================================================
 * LOGO ΠΑΝΩ-ΚΕΝΤΡΑ
 * ============================================================
 */

$logo_file = get_option('company_logo_dark');
if (!$logo_file) {
    $logo_file = get_option('company_logo');
}

$logo_path = null;
if ($logo_file) {
    $logo_path = FCPATH . 'uploads/company/' . $logo_file;
    if (!is_file($logo_path)) {
        $logo_path = null;
    }
}

if (!$logo_path) {
    if (function_exists('pdf_logo_url')) {
        $logo_url = pdf_logo_url();
    } elseif (function_exists('logo_pdf_url')) {
        $logo_url = logo_pdf_url();
    } else {
        $logo_url = '';
    }

    if (!empty($logo_url)) {
        $base = rtrim(base_url(), '/');
        if (strpos($logo_url, $base) === 0) {
            $rel = substr($logo_url, strlen($base));
            $rel = ltrim($rel, '/');
            $candidate = FCPATH . $rel;
            if (is_file($candidate)) {
                $logo_path = $candidate;
            }
        }
    }
}

$logo_bottom_y = $top_margin;

if ($logo_path && is_file($logo_path)) {
    $usable_w = $page_width - $left_margin - $right_margin;
    $img_w    = min(60, $usable_w * 0.6); // πλάτος λογότυπου σε mm
    $img_h_layout = 18; // fallback

    if (function_exists('getimagesize')) {
        $size = @getimagesize($logo_path);
        if ($size && !empty($size[0]) && !empty($size[1])) {
            $origW = (float)$size[0];
            $origH = (float)$size[1];
            if ($origW > 0) {
                $ratio = $origH / $origW;
                $img_h_layout = $img_w * $ratio;
            }
        }
    }

    $img_x = $left_margin + ($usable_w - $img_w) / 2;
    $img_y = $top_margin;

    try {
        $pdf->Image(
            $logo_path,
            $img_x,
            $img_y,
            $img_w,
            0, // auto-height
            '',
            '',
            '',
            false,
            300,
            '',
            false,
            false,
            0,
            false,
            false
        );

        $logo_bottom_y = $img_y + $img_h_layout;
    } catch (\Throwable $e) {
        $logo_bottom_y = $top_margin;
    }
}

/**
 * ============================================================
 * LICENSE ΠΑΝΩ-ΔΕΞΙΑ
 * ============================================================
 */
if (!empty($license_number)) {
    $pdf->SetFont($font_family, '', $font_size);
    $pdf->SetXY($page_width - $right_margin - 60, $top_margin + 2);
    $pdf->Cell(60, 5, $license_number, 0, 0, 'R');
}

/**
 * ============================================================
 * HEADER TITLE / SUBTITLE
 * ============================================================
 */
$header_y = max($logo_bottom_y + 2, $top_margin + 10);
$pdf->SetY($header_y);

if (!empty($header_title)) {
    $pdf->SetFont($font_family, 'B', $font_size + 2);
    $pdf->Cell(0, 7, $header_title, 0, 1, 'C');
} else {
    $pdf->SetFont($font_family, 'B', $font_size + 2);
    $pdf->Cell(0, 7, 'LIMS Report', 0, 1, 'C');
}

if (!empty($header_subtitle)) {
    $pdf->SetFont($font_family, '', $font_size);
    $pdf->Cell(0, 6, $header_subtitle, 0, 1, 'C');
}

$pdf->Ln(2);
$pdf->Cell(0, 0, '', 'T', 1);
$pdf->Ln(4);

/**
 * ============================================================
 * ORDER + SUBJECT INFO BLOCK
 * ============================================================
 */

// Ημ/νίες Order
$createdAt = !empty($order->created_at) ? _dt($order->created_at) : '-';
$dueAt     = !empty($order->due_at)    ? _dt($order->due_at)    : '-';

// ORDER INFO (απλό block, χωρίς client)
$pdf->SetFont($font_family, 'B', $font_size + 1);
$pdf->Cell(0, 6, _l('lims_order') . ' #' . (int)$order->id, 0, 1, 'L');

$pdf->SetFont($font_family, '', $font_size);
$pdf->Cell(0, 5, _l('date_created') . ': ' . $createdAt, 0, 1, 'L');
$pdf->Cell(0, 5, _l('due_date') . ': ' . $dueAt, 0, 1, 'L');

if (!empty($order->order_barcode)) {
    $pdf->Cell(0, 5, 'Barcode: ' . $order->order_barcode, 0, 1, 'L');
}

$pdf->Ln(3);

// SUBJECT FETCH
$subject = null;
if (!empty($order->subject_id)) {
    $subject = $CI->db
        ->where('id', (int)$order->subject_id)
        ->get(db_prefix().'lims_subjects')
        ->row();
}

// SUBJECT BLOCK (ΠΛΗΡΗ ΣΤΟΙΧΕΙΑ SUBJECT – ΟΧΙ CLIENT)
$pdf->SetFont($font_family, 'B', $font_size + 2);
$pdf->Cell(0, 6, _l('lims_subject') ?: 'Subject', 0, 1, 'L');
$pdf->SetFont($font_family, '', $font_size - 2);

if (!$subject) {
    $pdf->Cell(0, 5, '—', 0, 1, 'L');
} else {
    // Προετοιμασία πεδίων subject
    $nameParts = [];
    if (!empty($subject->subject_name)) {
        $nameParts[] = $subject->subject_name;
    }
    if (!empty($subject->first_name)) {
        $nameParts[] = $subject->first_name;
    }
    if (!empty($subject->last_name)) {
        $nameParts[] = $subject->last_name;
    }
    $fullName = trim(implode(' ', $nameParts));

    $internalCode = $subject->internal_code ?: '';
    $idNumber     = $subject->id_number ?: '';
    $nationality  = $subject->nationality ?: '';
    $gender       = $subject->gender ?: '';
    $socialIns    = $subject->social_insurance_no ?: '';
    $dob          = !empty($subject->date_of_birth) ? _d($subject->date_of_birth) : '';

    $phone        = $subject->phone ?: '';
    $email        = $subject->email ?: '';
    $address      = $subject->address ?: '';
    $city         = $subject->city ?: '';
    $state        = $subject->state ?: '';
    $zip          = $subject->zip ?: '';

    $countryName  = '';
    if (!empty($subject->country) && function_exists('get_country')) {
        $country = get_country($subject->country);
        if ($country) {
            $countryName = $country->short_name;
        }
    }

    $notes        = $subject->notes ?: '';

    // Helper για να βάζουμε "-" όταν είναι κενό
    $fmt = function($val) {
        return $val !== '' ? html_escape($val) : '-';
    };

    // HTML table 2-στηλο: Αριστερά personal, δεξιά contact/address
    $html  = '<table cellspacing="0" cellpadding="3" border="0" width="100%">';
    $html .= '<tr>';
    $html .= '  <td width="50%"><strong>' . html_escape(_l('lims_subject_name') ?: 'Name') . ':</strong> ' . $fmt($fullName) . '</td>';
    $html .= '  <td width="50%"><strong>' . html_escape(_l('contact_phone') ?: 'Phone') . ':</strong> ' . $fmt($phone) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '  <td><strong>' . html_escape(_l('lims_subject_internal_code') ?: 'Internal code') . ':</strong> ' . $fmt($internalCode) . '</td>';
    $html .= '  <td><strong>Email:</strong> ' . $fmt($email) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '  <td><strong>' . html_escape(_l('lims_subject_id_number') ?: 'ID/Passport') . ':</strong> ' . $fmt($idNumber) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '  <td><strong>' . html_escape(_l('lims_subject_nationality') ?: 'Nationality') . ':</strong> ' . $fmt($nationality) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '  <td><strong>' . html_escape(_l('lims_subject_gender') ?: 'Gender') . ':</strong> ' . $fmt($gender) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '  <td><strong>' . html_escape(_l('lims_subject_dob') ?: 'DOB') . ':</strong> ' . $fmt($dob) . '</td>';
    $html .= '</tr>';

    $html .= '<tr>';
    $html .= '  <td><strong>' . html_escape(_l('lims_subject_social_insurance_no') ?: 'S/I No.') . ':</strong> ' . $fmt($socialIns) . '</td>';
    $html .= '</tr>';

    if ($notes !== '') {
        $html .= '<tr>';
        $html .= '  <td colspan="2"><strong>' . html_escape(_l('notes') ?: 'Notes') . ':</strong> ' . nl2br($fmt($notes)) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Γράφουμε το subject block με writeHTML, για να υπολογιστεί σωστά το ύψος
    $pdf->writeHTML($html, true, false, false, false, '');
}

$pdf->Ln(4);



/**
 * ============================================================
 * ΠΙΝΑΚΑΣ ΔΟΚΙΜΩΝ
 * ============================================================
 */
$pdf->SetFont($font_family, 'B', $font_size);
$pdf->Cell(35, 7, _l('lims_test_field_sample') ?: 'Sample', 1, 0, 'L');
$pdf->Cell(60, 7, _l('lims_test_table_col_test') ?: 'Test', 1, 0, 'L');
$pdf->Cell(25, 7, _l('lims_test_result_value') ?: 'Result', 1, 0, 'L');
$pdf->Cell(20, 7, _l('lims_test_result_unit') ?: 'Unit', 1, 0, 'L');
$pdf->Cell(20, 7, _l('lims_test_flag') ?: 'Flag', 1, 1, 'L');

$pdf->SetFont($font_family, '', $font_size - 0.5);

if (!empty($samples)) {
    foreach ($samples as $s) {
        $sampleTests = isset($testsBySample[$s->id]) ? $testsBySample[$s->id] : [];
        if (empty($sampleTests)) {
            continue;
        }

        $sampleLabel = !empty($s->sample_uid) ? $s->sample_uid : ('#' . $s->id);

        foreach ($sampleTests as $t) {
            $results = $resultsByTest[$t->id] ?? [];
            $last    = !empty($results) ? $results[0] : null;

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

$pdf->Ln(6);

/**
 * ============================================================
 * CULTURES SECTION (με πραγματικά δεδομένα από culturesBySample)
 * ============================================================
 */
$hasCultures = false;

foreach ((array)$samples as $s) {
    $sampleCultures = $culturesBySample[$s->id] ?? [];
    if (!empty($sampleCultures)) {
        $hasCultures = true;
        break;
    }
}

if ($hasCultures) {
    $pdf->SetFont($font_family, 'B', $font_size + 1);
    $pdf->Cell(0, 6, _l('lims_cultures') ?: 'Cultures', 0, 1, 'L');
    $pdf->Ln(1);

    $pdf->SetFont($font_family, 'B', $font_size);
    $pdf->Cell(35, 7, _l('lims_test_field_sample') ?: 'Sample', 1, 0, 'L');
    $pdf->Cell(50, 7, _l('lims_culture') ?: 'Culture', 1, 0, 'L');
    $pdf->Cell(35, 7, _l('lims_culture_type') ?: 'Type', 1, 0, 'L');
    $pdf->Cell(40, 7, _l('lims_culture_options') ?: 'Options', 1, 0, 'L');
    $pdf->Cell(0,  7, _l('lims_culture_comment') ?: 'Comment', 1, 1, 'L');

    $pdf->SetFont($font_family, '', $font_size - 0.5);

    foreach ((array)$samples as $s) {
        $sampleCultures = $culturesBySample[$s->id] ?? [];
        if (empty($sampleCultures)) {
            continue;
        }

        $sampleLabel = !empty($s->sample_uid) ? $s->sample_uid : ('#' . $s->id);

        foreach ($sampleCultures as $cu) {
            $key  = (int)$s->id . ':' . (int)$cu->culture_id;
            $cres = $cultureResultsByKey[$key] ?? null;

            $comment = $cres && !empty($cres->comment) ? $cres->comment : '';

            $selectedSets = $cultureSelectionsByKey[$key] ?? [];
            $optionLabels = [];

            if (!empty($cu->option_sets) && is_array($cu->option_sets)) {
                foreach ($cu->option_sets as $set) {
                    $setId   = (int)($set['set_id'] ?? 0);
                    $setName = $set['set_name'] ?? '';
                    $values  = $set['values'] ?? [];

                    $selValId = $selectedSets[$setId] ?? null;
                    if ($selValId && !empty($values)) {
                        foreach ($values as $val) {
                            if ((int)$val['id'] === (int)$selValId) {
                                $label = $val['label'] ?? $val['value'] ?? '';
                                if ($label !== '') {
                                    $optionLabels[] = ($setName ? $setName . ': ' : '') . $label;
                                }
                                break;
                            }
                        }
                    }
                }
            }

            $optText = !empty($optionLabels) ? implode('; ', $optionLabels) : '';

            // Στήλες: Sample | Culture | Type | Options | Comment
            $pdf->Cell(35, 6, $sampleLabel, 1, 0, 'L');
            $pdf->Cell(50, 6, $cu->culture_name ?? '', 1, 0, 'L');
            $pdf->Cell(35, 6, $cu->culture_type_name ?? '', 1, 0, 'L');
            $pdf->Cell(40, 6, $optText, 1, 0, 'L');

            // Comment: MultiCell μέχρι το τέλος γραμμής
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $w = $page_width - $pdf->getMargins()['right'] - $x;

            $pdf->MultiCell($w, 6, $comment, 1, 'L', 0, 1, '', '', true);
        }
    }

    $pdf->Ln(4);
}

// ======================================================================
// SIGNATURE (Image + Staff Name + Meta)
// Uses:
//  - order.signed_by (primary)
//  - order.signed_at
//  - tbllims_signatures.staff_id + image_file (uploads/lims_signatures/)
// ======================================================================

// Resolve signer staff id (priority: explicit var -> order.signed_by -> fallbacks)
$staff_id_for_signature = 0;

if (isset($signed_by_staff_id) && (int)$signed_by_staff_id > 0) {
    $staff_id_for_signature = (int)$signed_by_staff_id;
} elseif (property_exists($order, 'signed_by') && (int)$order->signed_by > 0) {
    $staff_id_for_signature = (int)$order->signed_by;
} elseif (property_exists($order, 'signed_by_staff_id') && (int)$order->signed_by_staff_id > 0) {
    $staff_id_for_signature = (int)$order->signed_by_staff_id;
} elseif (property_exists($order, 'created_by') && (int)$order->created_by > 0) {
    $staff_id_for_signature = (int)$order->created_by;
} elseif (function_exists('get_staff_user_id') && (int)get_staff_user_id() > 0) {
    $staff_id_for_signature = (int)get_staff_user_id();
}

// Load signature + staff row
$signature = null;
$staffRow  = null;

if ($staff_id_for_signature > 0) {
    $signature = $CI->db
        ->where('staff_id', $staff_id_for_signature)
        ->get(db_prefix().'lims_signatures')
        ->row();

    $staffRow = $CI->db
        ->where('staffid', $staff_id_for_signature)
        ->get(db_prefix().'staff')
        ->row();
}

// Resolve image absolute path
$img_path = null;

if ($signature && !empty($signature->image_file)) {
    $imgFile = trim((string)$signature->image_file);

    // 1) absolute filesystem path
    if (is_file($imgFile)) {
        $img_path = $imgFile;
    }

    // 2) URL that belongs to this site -> map to FCPATH
    if (!$img_path && preg_match('#^https?://#i', $imgFile)) {
        $base = rtrim(site_url(), '/');
        if (strpos($imgFile, $base) === 0) {
            $rel  = ltrim(substr($imgFile, strlen($base)), '/');
            $cand = FCPATH . $rel;
            if (is_file($cand)) {
                $img_path = $cand;
            }
        }
    }

    // 3) filename/relative -> uploads/lims_signatures/<file>
    if (!$img_path) {
        $cand = FCPATH . 'uploads/lims_signatures/' . ltrim($imgFile, '/');
        if (is_file($cand)) {
            $img_path = $cand;
        }
    }
}

// If we have either signature meta or staff row, print the block.
// Image is optional (if file missing), but name/meta will still show.
if ($staff_id_for_signature > 0 && ($signature || $staffRow)) {

    // Avoid footer overlap: if close to bottom, add new page
    $needH  = 45; // image + 2-4 lines
    $limitY = $pdf->getPageHeight() - $pdf->getBreakMargin();
    if ($pdf->GetY() + $needH > $limitY) {
        $pdf->AddPage();
    }

    $pdf->Ln(6);

    // Print image if available
    if ($img_path && is_file($img_path)) {
        $sigX = $left_margin;
        $sigY = $pdf->GetY();
        $sigW = 42;

        try {
            $pdf->Image($img_path, $sigX, $sigY, $sigW, 0);
        } catch (\Throwable $e) {
            // ignore image errors
        }

        // Move cursor under the signature image
        if (method_exists($pdf, 'getImageRBY')) {
            $pdf->SetY((float)$pdf->getImageRBY() + 2);
        } else {
            $pdf->SetY($sigY + 22);
        }
    }

    // Print staff name
    $staffName = '';
    if ($staffRow) {
        $staffName = trim(($staffRow->firstname ?? '') . ' ' . ($staffRow->lastname ?? ''));
    }
    if ($staffName === '') {
        $staffName = 'Authorised Signatory';
    }

    $pdf->SetFont($font_family, 'B', $font_size);
    $pdf->Cell(0, 6, $staffName, 0, 1, 'L');

    // Print signature meta lines
    $pdf->SetFont($font_family, '', max(7, $font_size - 1));

    if ($signature && !empty($signature->title)) {
        $pdf->Cell(0, 5, (string)$signature->title, 0, 1, 'L');
    }
    if ($signature && !empty($signature->license_no)) {
        $pdf->Cell(0, 5, (string)$signature->license_no, 0, 1, 'L');
    }
    if ($signature && !empty($signature->extra_line)) {
        $pdf->Cell(0, 5, (string)$signature->extra_line, 0, 1, 'L');
    }

    // Signed at line (from order)
    if (!empty($signed_at)) {
        $pdf->Cell(0, 5, 'Signed at: ' . _dt($signed_at), 0, 1, 'L');
    }

    $pdf->Ln(2);
}



/**
 * ============================================================
 * FOOTER (εικόνα + κείμενο στην ίδια σελίδα)
 * ============================================================
 */
$footer_text = trim((string)$footer_text_opt);
$footer_img  = trim((string)$footer_image_opt);

if ($footer_text !== '' || $footer_img !== '') {

    $page_width   = $pdf->getPageWidth();
    $page_height  = $pdf->getPageHeight();
    $margins      = $pdf->getMargins();
    $left_margin  = $margins['left'];
    $right_margin = $margins['right'];
    $bottom_marg  = $pdf->getBreakMargin();

    $old_apb   = $pdf->getAutoPageBreak();
    $old_bmarg = $bottom_marg;
    $pdf->SetAutoPageBreak(false, 0);

    $min_block_h = 40;
    $footer_y    = max($pdf->GetY() + 5, $page_height - $bottom_marg - $min_block_h);

    $usable_w  = $page_width - $left_margin - $right_margin;
    $current_y = $footer_y;

    // Εικόνα footer
    if ($footer_img !== '') {
        $img_path = $footer_img;

        if (preg_match('#^https?://#i', $img_path)) {
            $base = rtrim(site_url(), '/');
            if (strpos($img_path, $base) === 0) {
                $rel      = substr($img_path, strlen($base));
                $rel      = ltrim($rel, '/');
                $img_path = FCPATH . $rel;
            }
        }

        if (is_file($img_path)) {

            $origW = 0;
            $origH = 0;
            if (function_exists('getimagesize')) {
                $size = @getimagesize($img_path);
                if ($size && !empty($size[0]) && !empty($size[1])) {
                    $origW = (float)$size[0];
                    $origH = (float)$size[1];
                }
            }

            $img_w = $usable_w * 0.6;
            $img_h = 0;

            if ($origW > 0 && $origH > 0) {
                $mmW = $origW * 0.2645833333;
                $mmH = $origH * 0.2645833333;

                if ($mmW > $usable_w) {
                    $scale = $usable_w / $mmW;
                    $mmW   = $mmW * $scale;
                    $mmH   = $mmH * $scale;
                }

                $img_w = $mmW;
                $img_h = $mmH;
            }

            $img_x = $left_margin + ($usable_w - $img_w) / 2;

            try {
                $pdf->Image(
                    $img_path,
                    $img_x,
                    $current_y,
                    $img_w,
                    $img_h ?: 0,
                    '',
                    '',
                    '',
                    false,
                    300,
                    '',
                    false,
                    false,
                    0,
                    false,
                    false
                );

                $current_y += ($img_h ?: 14) + 1;
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    if ($footer_text !== '') {
        $pdf->SetFont($font_family, '', max(6, $font_size - 2));
        $pdf->SetXY($left_margin, $current_y);
        $pdf->MultiCell($usable_w, 0, $footer_text, 0, 'C', 0, 1, '', '', true);
    }

    $pdf->SetAutoPageBreak($old_apb, $old_bmarg);
    $pdf->setPageMark();
}
