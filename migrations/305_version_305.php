<?php defined('BASEPATH') or exit('No direct script access allowed');

if (class_exists('Migration_Version_305', false)) {
    return;
}

class Migration_Version_305 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $ordersTable = db_prefix() . 'lims_orders';

        if (!$CI->db->table_exists($ordersTable)) {
            return;
        }

        if (!$CI->db->field_exists('signed_by', $ordersTable)) {
            $CI->db->query("ALTER TABLE `{$ordersTable}` ADD COLUMN `signed_by` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `created_by`");
        }

        if (!$CI->db->field_exists('signed_at', $ordersTable)) {
            $CI->db->query("ALTER TABLE `{$ordersTable}` ADD COLUMN `signed_at` DATETIME NULL DEFAULT NULL AFTER `signed_by`");
        }

        $statusColumn = $CI->db->query("SHOW COLUMNS FROM `{$ordersTable}` LIKE 'status'")->row();
        if ($statusColumn && stripos((string)$statusColumn->Type, "'signed'") === false) {
            $CI->db->query("
                ALTER TABLE `{$ordersTable}`
                MODIFY COLUMN `status` ENUM(
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
    }
}
