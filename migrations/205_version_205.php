<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_205 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        // ---------- tbllims_report_notes ----------
        $tblNotes = $p.'lims_report_notes';
        if (!$CI->db->table_exists($tblNotes)) {
            $CI->db->query("
                CREATE TABLE `{$tblNotes}` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `code` VARCHAR(191) DEFAULT NULL,
                  `title` VARCHAR(191) DEFAULT NULL,
                  `note_el` TEXT DEFAULT NULL,
                  `note_en` TEXT DEFAULT NULL,
                  `active` TINYINT(1) NOT NULL DEFAULT 1,
                  `position` INT(11) NOT NULL DEFAULT 0,
                  `created_at` DATETIME DEFAULT NULL,
                  `updated_at` DATETIME DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_code` (`code`),
                  KEY `idx_active_position` (`active`,`position`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } else {
            if (!$CI->db->field_exists('code', $tblNotes)) {
                $CI->db->query("ALTER TABLE `{$tblNotes}` ADD COLUMN `code` VARCHAR(191) NULL DEFAULT NULL AFTER `id`");
            }
            if (!$CI->db->field_exists('position', $tblNotes) && $CI->db->field_exists('sort_order', $tblNotes)) {
                // Αν έχεις sort_order, μπορούμε να το αφήσουμε. Προσθέτουμε position για standard ordering.
                $CI->db->query("ALTER TABLE `{$tblNotes}` ADD COLUMN `position` INT(11) NOT NULL DEFAULT 0 AFTER `active`");
            } elseif (!$CI->db->field_exists('position', $tblNotes)) {
                $CI->db->query("ALTER TABLE `{$tblNotes}` ADD COLUMN `position` INT(11) NOT NULL DEFAULT 0 AFTER `active`");
            }
            if (!$CI->db->field_exists('active', $tblNotes)) {
                $CI->db->query("ALTER TABLE `{$tblNotes}` ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `note_en`");
            }
        }

        // ---------- tbllims_order_report_notes ----------
        $tblOrderNotes = $p.'lims_order_report_notes';
        if (!$CI->db->table_exists($tblOrderNotes)) {
            $CI->db->query("
                CREATE TABLE `{$tblOrderNotes}` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `order_id` INT(10) UNSIGNED NOT NULL,
                  `free_text` LONGTEXT NULL,
                  `note_ids_json` TEXT NULL,
                  `updated_by` INT(10) UNSIGNED NULL,
                  `created_at` DATETIME NULL,
                  `updated_at` DATETIME NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uniq_order_id` (`order_id`),
                  KEY `idx_order_id` (`order_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } else {
            if (!$CI->db->field_exists('free_text', $tblOrderNotes)) {
                $CI->db->query("ALTER TABLE `{$tblOrderNotes}` ADD COLUMN `free_text` LONGTEXT NULL AFTER `order_id`");
            }
            if (!$CI->db->field_exists('note_ids_json', $tblOrderNotes)) {
                $CI->db->query("ALTER TABLE `{$tblOrderNotes}` ADD COLUMN `note_ids_json` TEXT NULL AFTER `free_text`");
            }
            if (!$CI->db->field_exists('updated_by', $tblOrderNotes)) {
                $CI->db->query("ALTER TABLE `{$tblOrderNotes}` ADD COLUMN `updated_by` INT(10) UNSIGNED NULL AFTER `note_ids_json`");
            }
            if (!$CI->db->field_exists('created_at', $tblOrderNotes)) {
                $CI->db->query("ALTER TABLE `{$tblOrderNotes}` ADD COLUMN `created_at` DATETIME NULL AFTER `updated_by`");
            }
            if (!$CI->db->field_exists('updated_at', $tblOrderNotes)) {
                $CI->db->query("ALTER TABLE `{$tblOrderNotes}` ADD COLUMN `updated_at` DATETIME NULL AFTER `created_at`");
            }
        }
    }
}
