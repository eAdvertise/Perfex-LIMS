<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Signatures extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        // Βάλε όποιο permission θες
        if (!has_permission('lims', '', 'admin') && !has_permission('lims', '', 'enter_results')) {
            access_denied('Lims Signatures');
        }
    }

    // Παλιό URL που χρησιμοποιείς: /admin/lims/signatures/upload/ID
    public function upload($staff_id = 0)
    {
        return $this->save($staff_id);
    }

    public function save($staff_id = 0)
    {
        $staff_id = (int) $staff_id;
        if ($staff_id <= 0) {
            $staff_id = (int) get_staff_user_id();
        }

        // DEBUG (αν θες να δεις τι έρχεται):
        // echo '<pre>'; print_r($_FILES); exit;

        if (!isset($_FILES['lims_signature_file']) || empty($_FILES['lims_signature_file']['name'])) {
            set_alert('warning', _l('no_file_selected'));
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('staff/member/'.$staff_id));
        }

        $path = FCPATH . 'uploads/lims_signatures/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $ext = pathinfo($_FILES['lims_signature_file']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext ?: 'png');

        $filename = 'staff_'.$staff_id.'_signature_'.time().'.'.$ext;
        $fullpath = $path . $filename;

        if (!move_uploaded_file($_FILES['lims_signature_file']['tmp_name'], $fullpath)) {
            set_alert('danger', 'Error uploading signature file.');
            redirect($_SERVER['HTTP_REFERER'] ?? admin_url('staff/member/'.$staff_id));
        }

        // Σχετική διαδρομή για να την βάζουμε στο src=""
        $relpath = 'uploads/lims_signatures/'.$filename;

        $this->db->where('staffid', $staff_id);
        $this->db->update(db_prefix().'staff', [
            'lims_signature' => $relpath,
        ]);

        set_alert('success', _l('settings_updated'));

        redirect($_SERVER['HTTP_REFERER'] ?? admin_url('staff/member/'.$staff_id));
    }
}
