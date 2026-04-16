<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_304 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        $tblSubjects = $p . 'lims_subjects';

        if (!$CI->db->table_exists($tblSubjects)) {
            return;
        }

        if (!$CI->db->field_exists('is_deleted', $tblSubjects)) {
            $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `active`");
            $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD KEY `idx_is_deleted` (`is_deleted`)");
        }

        if (!$CI->db->field_exists('deleted_at', $tblSubjects)) {
            $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD COLUMN `deleted_at` DATETIME NULL AFTER `is_deleted`");
        }

        if (!$CI->db->field_exists('deleted_by', $tblSubjects)) {
            $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD COLUMN `deleted_by` INT(11) NULL AFTER `deleted_at`");
            $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD KEY `idx_deleted_by` (`deleted_by`)");
        }
    }
}
