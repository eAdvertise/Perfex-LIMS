<?php
//lims/helpers/lims_pdf_helper.php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('order_pdf')) {
    /**
     * Δημιουργεί TCPDF για Order μέσω App_pdf.
     * @param object $order  // περιέχει και ->lines
     * @param string $tag
     * @return TCPDF
     */
    function order_pdf($order, $tag = '')
    {
        // μονοπάτι στη library μας
        $path = module_dir_path('lims', 'libraries/pdf/Order_pdf.php');
        // ίδια υπογραφή με αυτά που ήδη χρησιμοποιείς: app_pdf(type, library_path, data, tag)
        return app_pdf('order', $path, $order, $tag);
    }
}

if (!function_exists('sample_labels_pdf')) {
    /**
     * @param object $order  Order row (χρησιμοποιείται για ημερομηνίες και πελάτη)
     * @param array  $samples  Array of sample rows (κάθε row από tbllims_samples)
     */
    function sample_labels_pdf($order, array $samples)
    {
        return app_pdf('sample_labels', module_dir_path('lims','libraries/pdf/Sample_labels_pdf.php'), ['order'=>$order, 'samples'=>$samples]);
    }
}

if (!function_exists('lims_sample_labels_pdf')) {
    /**
     * $payload = [
     *   'order'   => (object) order row,
     *   'samples' => (array<object>) samples of the order WITH minimal meta (barcode, sample_type_id, etc.)
     * ]
     */
    function lims_sample_labels_pdf(array $payload)
    {
        return app_pdf(
            'lims_labels',
            module_dir_path(LIMS_MODULE_NAME, 'libraries/pdf/Labels_pdf.php'),
            (object) $payload
        );
    }
}
if (!function_exists('lims_order_report_pdf')) {
    /**
     * Δημιουργεί Lab Report PDF για ένα order
     *
     * @param int    $order_id
     * @param string $output_type I=inline, D=download, S=string
     * @return void|string
     */
    function lims_order_report_pdf($order_id, $output_type = 'I')
    {
        $CI = &get_instance();
        $order_id = (int)$order_id;

        $CI->load->model('lims/tests_model');

        $data = $CI->tests_model->get_order_tests_data($order_id);

        if (!$data || empty($data['order'])) {
            show_404();
        }

        $order = $data['order'];

        // Μπορείς εδώ να φορτώσεις client/patient info αν χρειαστεί extra
        if (!empty($order->client_id)) {
            $CI->db->select('company, vat, phonenumber, city, country');
            $CI->db->from(db_prefix() . 'clients');
            $CI->db->where('userid', (int)$order->client_id);
            $data['client'] = $CI->db->get()->row();
        } else {
            $data['client'] = null;
        }

        // Settings για report header/footer
        $data['report_license']       = get_option('lims_report_license_number');
        $data['report_header_title']  = get_option('lims_report_header_title');
        $data['report_header_sub']    = get_option('lims_report_header_subtitle');
        $data['report_header_sub2']   = get_option('lims_report_header_subtitle_2');
        $data['report_footer_text']   = get_option('lims_report_footer_text');
        $data['report_font_family']   = get_option('lims_report_font_family');
        $data['report_font_size']     = get_option('lims_report_font_size');

        // Company info
        $data['company_name']    = get_option('companyname');
        $data['company_address'] = get_option('invoice_company_address');
        $data['company_city']    = get_option('invoice_company_city');
        $data['company_country'] = get_option('invoice_company_country_code');
        $data['company_vat']     = get_option('company_vat');

        // PDF
        $CI->load->library('pdf');
        $pdf = $CI->pdf->load();

        // Βασικές ρυθμίσεις
        $pdf->SetTitle(_l('lims_report_pdf_title') . ' #' . $order->id);
        $pdf->SetAuthor(get_option('companyname'));
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Font από settings (αν υπάρχει)
        $fontFamily = $data['report_font_family'] ?: 'dejavusans';
        $fontSize   = (int)($data['report_font_size'] ?: 9);
        $pdf->SetFont($fontFamily, '', $fontSize);

        $pdf->AddPage();

        $html = $CI->load->view('lims/admin/reports/order_report_pdf', $data, true);

        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = 'lab-report-' . $order->id . '.pdf';

        if ($output_type === 'D') {
            $pdf->Output($filename, 'D');
        } elseif ($output_type === 'S') {
            return $pdf->Output($filename, 'S');
        } else {
            $pdf->Output($filename, 'I');
        }
    }
}