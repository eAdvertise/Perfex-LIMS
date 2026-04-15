<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/pdf/App_pdf.php');

class Lims_order_report_pdf extends App_pdf
{
    /** @var array */
    protected $payload = [];

    /** @var array */
    protected $settings = [];

    /** @var object */
    protected $order;

    public function __construct($payload)
    {
        // Validate early (parent may create a page and call Header())
        if (!is_array($payload) || empty($payload['order']) || empty($payload['settings'])) {
            throw new InvalidArgumentException('Lims_order_report_pdf requires payload with [order] and [settings].');
        }

        $this->payload  = $payload;
        $this->settings = is_array($payload['settings']) ? $payload['settings'] : [];
        $this->order    = $payload['order'];

        parent::__construct();

        $orderId = (int)($this->order->id ?? 0);
        $this->SetTitle('LIMS Report - Order #' . $orderId);

        // Typography defaults
        $font = (string)($this->settings['font_family'] ?? 'dejavuserif');
        $size = (float)($this->settings['font_size'] ?? 10);
        $this->SetFont($font, '', $size);

        // Use TCPDF Header/Footer
        $this->setPrintHeader(true);
        $this->setPrintFooter(true);

        // Layout: content starts below header; reserve footer space
        $this->SetMargins(10, 58, 10);
        $this->SetAutoPageBreak(true, 28);

        $this->SetHeaderMargin(0);
        $this->SetFooterMargin(0);
    }

    protected function type()
    {
        return 'lims_order_report';
    }

    protected function file_path()
    {
        return APP_MODULES_PATH . 'lims/views/themes/order_report_pdf_v2.php';
    }

    protected function file_name()
    {
        $id = (int)($this->order->id ?? 0);
        return 'lims-report-order-' . $id . '.pdf';
    }

    public function prepare()
    {
        $this->set_view_vars($this->payload);

        // Do NOT AddPage() here; App_pdf/TCPDF already created first page.
        // Ensure cursor is at body start (below header)
        $this->SetY(58);

        return $this->build();
    }

    // =========================================================
    // HEADER
    // =========================================================
    public function Header()
    {
        // ---------- Background (full page) ----------
        $bg = $this->settings['background_path'] ?? '';
        if (is_string($bg) && $bg !== '' && is_file($bg)) {
            $w = $this->getPageWidth();
            $h = $this->getPageHeight();

            $this->SetAutoPageBreak(false, 0);
            $this->Image($bg, 0, 0, $w, $h, '', '', '', false, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 28);
            $this->setPageMark();
        }

        // ---------- Logo ----------
        $logo = $this->settings['logo_path'] ?? '';
        if (is_string($logo) && $logo !== '' && is_file($logo)) {
            $w = (float)($this->settings['logo_width'] ?? 90);
            $y = (float)($this->settings['logo_y'] ?? 8);

            $xOpt = $this->settings['logo_x'] ?? '';
            if ($xOpt === '' || $xOpt === null) {
                // center on A4 portrait (210mm)
                $x = (210 - $w) / 2;
                if ($x < 0) { $x = 0; }
            } else {
                $x = (float)$xOpt;
            }

            $this->Image($logo, $x, $y, $w, 0, '', '', '', false, 300, '', false, false, 0);
        }

        // ---------- Green line (header) ----------
        $this->SetLineWidth(0.4);
        $this->SetDrawColor(0, 150, 0);
        $this->Line(20, 33, 190, 33);
        $this->SetDrawColor(0, 0, 0);

        // ---------- Subtitle + Heading ----------
        $font = (string)($this->settings['font_family'] ?? 'dejavuserif');

        $y = 35;

        $sub = (string)($this->settings['header_subtitle'] ?? '');
        if ($sub !== '') {
            $this->SetFont($font, '', 8);
            $this->SetXY(10, $y);
            $this->Cell(190, 5, $sub, 0, 1, 'C');
            $y += 7;
        }

        $heading = (string)($this->settings['heading'] ?? '');
        if ($heading !== '') {
            $this->SetFont($font, '', 14);
            $this->SetXY(10, $y);
            $this->Cell(190, 8, $heading, 0, 1, 'C');
        }

       // ---------- Top-right code (2 lines) ----------
		$code = (string)($this->settings['top_right_code'] ?? '');
		if ($code !== '') {
			$this->SetFont($font, '', 9);

			// Το table ξεκινά στο body γύρω στο Y=58, άρα κρατάμε το top-right text πιο ψηλά.
			// Αν υπάρχει heading, το τοποθετούμε ακριβώς κάτω από αυτό.
			$codeX = (float)($this->settings['top_right_x'] ?? 170);

			// $y εδώ είναι το σημείο που σχεδιάστηκε το heading (ή το τελευταίο header line).
			// Αν δεν υπάρχει heading/subtitle, κρατάμε ένα ασφαλές default.
			$refY  = isset($y) ? (float)$y : 42;
			$codeY = (float)($this->settings['top_right_y'] ?? ($refY + 8));

			// Hard safety: μην κατέβει ποτέ μέσα στο body table
			if ($codeY > 54) { $codeY = 54; }

			$this->SetXY($codeX, $codeY);
			$this->MultiCell(30, 8, $code, 0, 'R', false, 1);
		}

    }

    // =========================================================
    // FOOTER
    // Required order:
    // 1) Footer Image
    // 2) pre_footer_note_[lang]
    // 3) footer_text_[lang]
    // 4) footer_text (bottom) + green line above
    // =========================================================
    public function Footer()
    {
        $font = (string)($this->settings['font_family'] ?? 'dejavuserif');
        $this->SetFont($font, '', 8);

        $pageH = $this->getPageHeight();

        $bottom = (float)($this->settings['footer_bottom_mm'] ?? 10);
        $gap    = (float)($this->settings['footer_gap_mm'] ?? 20);

        // ---- Bottom footer_text (ONLY at bottom) ----
        $bottomText = (string)($this->settings['footer_text'] ?? '');
        $bottomTextY = $pageH - $bottom - 4;

        // Green line above bottom text
        $lineW = (float)($this->settings['footer_line_thickness'] ?? 0.4);
        $r = (int)($this->settings['footer_line_r'] ?? 0);
        $g = (int)($this->settings['footer_line_g'] ?? 150);
        $b = (int)($this->settings['footer_line_b'] ?? 0);
        $x1 = (float)($this->settings['footer_line_x1'] ?? 20);
        $x2 = (float)($this->settings['footer_line_x2'] ?? 190);
        $off = (float)($this->settings['footer_line_offset_mm'] ?? 2);

        $this->SetLineWidth($lineW);
        $this->SetDrawColor($r, $g, $b);
        $this->Line($x1, $bottomTextY - $off, $x2, $bottomTextY - $off);
        $this->SetDrawColor(0, 0, 0);

        if ($bottomText !== '') {
            $this->SetXY(10, $bottomTextY);
            $this->MultiCell(190, 4, $bottomText, 0, 'C', false, 1);
        }

        // ---- footer_text_[lang] (gap above) ----
        $footerLang = (string)($this->settings['footer_text_lang'] ?? '');
        if ($footerLang !== '') {
            $y = $bottomTextY - $gap;
            $this->SetXY(10, $y);
            $this->MultiCell(190, 4, $footerLang, 0, 'C', false, 1);
        }

        // ---- pre_footer_note_[lang] (another gap above) ----
        $pre = (string)($this->settings['pre_footer_note'] ?? '');
        if ($pre !== '') {
            $y = $bottomTextY - ($gap * 2);
            $this->SetXY(10, $y);
            $this->MultiCell(190, 4, $pre, 0, 'C', false, 1);
        }

        // ---- Footer image (as per settings, should not overlap texts) ----
        $footerImg = $this->settings['footer_image_path'] ?? '';
        if (is_string($footerImg) && $footerImg !== '' && is_file($footerImg)) {
            $x = (float)($this->settings['footer_img_x'] ?? 95);
            $y = (float)($this->settings['footer_img_y'] ?? 240);
            $w = (float)($this->settings['footer_img_w'] ?? 20);
            $this->Image($footerImg, $x, $y, $w, 0, '', '', '', false, 300, '', false, false, 0);
        }
    }
}
