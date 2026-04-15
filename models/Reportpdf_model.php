<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reportpdf_model extends App_Model
{
    /**
     * Option keys stored in tbloptions
     */
    protected $option_keys = [
        'lims_report_license_number',
        'lims_report_header_title',
        'lims_report_header_subtitle',
        'lims_report_font_family',
        'lims_report_font_size',
        'lims_report_footer_text',
        'lims_report_footer_image',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Επιστρέφει όλα τα settings σαν array
     * [option_key => value]
     */
    public function get_settings()
    {
        $settings = [];

        foreach ($this->option_keys as $key) {
            $settings[$key] = get_option($key);
        }

        // Defaults αν είναι άδεια
        if (empty($settings['lims_report_font_family'])) {
            $settings['lims_report_font_family'] = 'dejavusans'; // κλασική για TCPDF/MPDF
        }
        if (empty($settings['lims_report_font_size'])) {
            $settings['lims_report_font_size'] = 10;
        }

        return $settings;
    }

    /**
     * Αποθήκευση settings από $_POST
     */
    public function save($data)
    {
        foreach ($this->option_keys as $key) {
            $val = isset($data[$key]) ? trim((string)$data[$key]) : '';

            if ($key === 'lims_report_font_size') {
                $val = (int)$val;
                if ($val <= 0) {
                    $val = 10;
                }
            }

            // Αν υπάρχει ήδη, κάνε update, αλλιώς add
            if (get_option($key) !== false) {
                update_option($key, $val);
            } else {
                add_option($key, $val, 0);
            }
        }

        return true;
    }
}
