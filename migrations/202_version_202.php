<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_202 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $table = db_prefix() . 'lims_subjects';

        if (!$CI->db->field_exists('language', $table)) {
            // language = Perfex language folder name (e.g. english, greek)
            $CI->db->query("ALTER TABLE `{$table}` ADD `language` VARCHAR(50) NULL AFTER `subject_type`");
        }
    }
}
