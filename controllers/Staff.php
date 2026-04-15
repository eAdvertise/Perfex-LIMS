<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save_signature($staff_id)
    {
        $staff_id = (int)$staff_id;

        if (!is_admin() && $staff_id !== (int)get_staff_user_id()) {
            access_denied('LIMS Staff Signature');
        }

        if ($staff_id <= 0) {
            show_404();
        }

        if (!isset($_FILES['signature']) || empty($_FILES['signature']['name'])) {
            set_alert('warning', _l('lims_signature_no_file'));
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('staff/member/' . $staff_id));
        }

        $path = FCPATH . 'uploads/lims_signatures/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        // Παλιά υπογραφή
        $staff = $this->db->select('lims_signature')
            ->from(db_prefix() . 'staff')
            ->where('staffid', $staff_id)
            ->get()
            ->row();

        $ext      = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
        $filename = 'staff_' . $staff_id . '_signature_' . uniqid() . '.' . $ext;

        if (!move_uploaded_file($_FILES['signature']['tmp_name'], $path . $filename)) {
            set_alert('danger', _l('lims_signature_upload_error'));
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('staff/member/' . $staff_id));
        }

        // Σβήσε παλιά
        if ($staff && !empty($staff->lims_signature) && is_file($path . $staff->lims_signature)) {
            @unlink($path . $staff->lims_signature);
        }

        $this->db->where('staffid', $staff_id)
                 ->update(db_prefix() . 'staff', ['lims_signature' => $filename]);

        set_alert('success', _l('lims_signature_saved'));
        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('staff/member/' . $staff_id));
    }
}
