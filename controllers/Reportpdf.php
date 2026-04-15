<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reportpdf extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        // Μόνο όσοι έχουν LIMS admin rights
        if (!has_permission('lims', '', 'admin')) {
            access_denied('lims');
        }

        $this->load->model('lims/reportpdf_model', 'reportpdf_model');
    }

    /**
     * Handle POST από το settings form
     * Action: admin_url('lims/reportpdf/save')
     */
    public function save()
    {
        if (!$this->input->post()) {
            redirect(admin_url('settings?group=lims'));
        }

        $data = $this->input->post();

        $this->reportpdf_model->save($data);

        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'success' => true,
                'message' => _l('settings_updated'),
            ]);
            die;
        }

        set_alert('success', _l('settings_updated'));
        // Επιστροφή στο Settings → LIMS section
        redirect(admin_url('settings?group=lims'));
    }
}
