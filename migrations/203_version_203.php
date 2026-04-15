<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_203 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$p}lims_report_notes` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `code` VARCHAR(191) NULL DEFAULT NULL,
              `note_el` TEXT NOT NULL,
              `note_en` TEXT NOT NULL,
              `active` TINYINT(1) NOT NULL DEFAULT 1,
              `sort_order` INT(11) NOT NULL DEFAULT 0,
              `created_at` DATETIME NULL DEFAULT NULL,
              `updated_at` DATETIME NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uq_code` (`code`),
              KEY `idx_active_sort` (`active`, `sort_order`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$p}lims_test_report_notes` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `test_id` INT(10) UNSIGNED NOT NULL,
              `note_id` INT(10) UNSIGNED NOT NULL,
              `created_at` DATETIME NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uq_test_note` (`test_id`,`note_id`),
              KEY `idx_test_id` (`test_id`),
              KEY `idx_note_id` (`note_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }
}
