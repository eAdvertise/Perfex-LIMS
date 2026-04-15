<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends AdminController
{
    public function save_report_pdf()
    {
        if (!has_permission('lims', '', 'edit')) {
            access_denied('Lims');
        }

        $settings = $this->input->post('settings', true);
        if (is_array($settings)) {
            foreach ($settings as $k => $v) {
                update_option($k, is_null($v) ? '' : $v);
            }
        }

        // yes/no options come as separate posts via render_yes_no_option
        // Ensure we store them if present
        foreach ([
            'lims_report_show_signature',
            'lims_report_language_from_subject',
        ] as $yn) {
            $val = $this->input->post($yn, true);
            if ($val !== null) {
                update_option($yn, $val);
            }
        }

        $bg = $this->handle_upload('report_background', FCPATH.'uploads/lims/report/background/');
        if ($bg) {
            update_option('lims_report_background_image', $bg);
        }

        $fi = $this->handle_upload('report_footer_image', FCPATH.'uploads/lims/report/footer/');
        if ($fi) {
            update_option('lims_report_footer_image', $fi);
        }

        set_alert('success', _l('settings_updated'));

        // Return to the settings tab group
        redirect(admin_url('settings?group=lims-report-pdf'));
    }

    private function handle_upload($inputName, $targetDir)
    {
        if (empty($_FILES[$inputName]['name'])) {
            return null;
        }

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $tmp  = $_FILES[$inputName]['tmp_name'];
        $name = $_FILES[$inputName]['name'];

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            set_alert('warning', 'Invalid file type. Use PNG/JPG.');
            return null;
        }

        $safe = uniqid('lims_', true) . '.' . $ext;
        $dest = rtrim($targetDir, '/').'/'.$safe;

        if (@move_uploaded_file($tmp, $dest)) {
            return $safe;
        }

        set_alert('warning', 'Upload failed.');
        return null;
    }
}
