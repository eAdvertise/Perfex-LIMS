<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('lims_get_staff_signature_path')) {
    function lims_get_staff_signature_path($staff_id)
    {
        $staff_id = (int)$staff_id;
        if ($staff_id <= 0) {
            return null;
        }

        $base = FCPATH . 'uploads/staff_signatures/';
        if (!is_dir($base)) {
            return null;
        }

        $files = glob($base . 'staff_' . $staff_id . '.*');
        if (!$files) {
            return null;
        }

        return $files[0];
    }
}

if (!function_exists('lims_get_staff_signature_url')) {
    function lims_get_staff_signature_url($staff_id)
    {
        $path = lims_get_staff_signature_path($staff_id);
        if (!$path) {
            return null;
        }

        $rel = str_replace(FCPATH, '', $path);
        return base_url($rel);
    }
}
