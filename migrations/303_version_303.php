<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_303 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        $tblStaff = $p . 'staff';

        if ($CI->db->table_exists($tblStaff) && !$CI->db->field_exists('lims_signature', $tblStaff)) {
            // Store signature as TEXT/LONGTEXT (base64 or JSON or file path – whatever your UI uses)
            $CI->db->query("ALTER TABLE `{$tblStaff}` ADD COLUMN `lims_signature` LONGTEXT NULL");
        }
    }
}
