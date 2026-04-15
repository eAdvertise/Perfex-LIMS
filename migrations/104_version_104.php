<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_104 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        // 1) Υπογραφή στο staff
        if (!$CI->db->field_exists('lims_signature', $p . 'staff')) {
            $CI->db->query("
                ALTER TABLE `{$p}staff`
                ADD `lims_signature` VARCHAR(191) NULL DEFAULT NULL AFTER `profile_image`
            ");
        }

        // 2) signed_by / signed_at στο order
        if (!$CI->db->field_exists('signed_by', $p . 'lims_orders')) {
            $CI->db->query("
                ALTER TABLE `{$p}lims_orders`
                ADD `signed_by` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `created_by`,
                ADD `signed_at` DATETIME NULL DEFAULT NULL AFTER `signed_by`
            ");
        }

        // 3) Προσθήκη 'signed' στο enum του order.status (και καθάρισμα διπλού 'reported')
        $row = $CI->db->query("SHOW COLUMNS FROM `{$p}lims_orders` LIKE 'status'")->row();
        if ($row && strpos($row->Type, 'signed') === false) {
            $CI->db->query("
                ALTER TABLE `{$p}lims_orders`
                MODIFY `status` ENUM(
                    'draft',
                    'submitted',
                    'accessioned',
                    'testing',
                    'verified',
                    'approved',
                    'reported',
                    'in_progress',
                    'appointment',
                    'samples',
                    'complete',
                    'signed',
                    'canceled'
                ) DEFAULT 'draft'
            ");
        }

        // Τα tbllims_tests στο schema σου έχουν ήδη 'signed' στο enum, οπότε δεν το πειράζω εδώ.
    }

    public function down()
    {
        // Προαιρετικό: μπορείς να μην κάνεις rollback για αυτά.
    }
}
