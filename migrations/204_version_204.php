<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_204 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        // Master notes (αν δεν υπάρχει ήδη από δική σου υλοποίηση, το δημιουργεί)
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$p}lims_report_notes` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `title` VARCHAR(191) NULL DEFAULT NULL,
              `note_el` TEXT NULL DEFAULT NULL,
              `note_en` TEXT NULL DEFAULT NULL,
              `active` TINYINT(1) NOT NULL DEFAULT 1,
              `position` INT(11) NOT NULL DEFAULT 0,
              `created_at` DATETIME NULL DEFAULT NULL,
              `updated_at` DATETIME NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_active_position` (`active`,`position`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Order-level selection + free text (1 row per order)
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `{$p}lims_order_report_notes` (
              `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `order_id` INT(10) UNSIGNED NOT NULL,
              `free_text` LONGTEXT NULL,
              `note_ids_json` TEXT NULL,
              `updated_by` INT(10) UNSIGNED NULL,
              `created_at` DATETIME NULL DEFAULT NULL,
              `updated_at` DATETIME NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uniq_order_id` (`order_id`),
              KEY `idx_order_id` (`order_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }
}
