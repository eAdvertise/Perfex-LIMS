<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_302 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        // Cleanup triggers created by older failed migration attempts (if any),
        // so re-running the migration is safe.
        $trgRows = $CI->db->query(
            "SELECT TRIGGER_NAME FROM INFORMATION_SCHEMA.TRIGGERS
             WHERE TRIGGER_SCHEMA = DATABASE()
               AND (TRIGGER_NAME LIKE 'lims\\_%\\_bi_updated_at'
                    OR TRIGGER_NAME LIKE 'lims\\_%\\_bu_updated_at'
                    OR TRIGGER_NAME LIKE 'lims\\_%\\_ai_updated_at'
                    OR TRIGGER_NAME LIKE 'lims\\_%\\_au_updated_at')"
        )->result_array();

        foreach ($trgRows as $r) {
            $name = (string)($r['TRIGGER_NAME'] ?? '');
            if ($name !== '') {
                try { $CI->db->query("DROP TRIGGER `{$name}`"); } catch (Throwable $e) { /* ignore */ }
            }
        }

        // All LIMS tables in the current database
        $tables = $CI->db->query(
            "SELECT TABLE_NAME
               FROM INFORMATION_SCHEMA.TABLES
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME LIKE ?",
            [$p . "lims\\_%"]
        )->result_array();

        foreach ($tables as $t) {
            $tbl = (string)$t['TABLE_NAME'];
            if ($tbl === '') continue;

            // 1) Ensure updated_at exists
            if (!$CI->db->field_exists('updated_at', $tbl)) {
                $after = $CI->db->field_exists('created_at', $tbl) ? " AFTER `created_at`" : "";
                $CI->db->query("ALTER TABLE `{$tbl}` ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL{$after}");
            }

            // 2) Backfill updated_at for existing rows (best-effort)
            try {
                if ($CI->db->field_exists('created_at', $tbl)) {
                    $CI->db->query("UPDATE `{$tbl}` SET `updated_at` = COALESCE(`updated_at`, `created_at`) WHERE `updated_at` IS NULL");
                } else {
                    $CI->db->query("UPDATE `{$tbl}` SET `updated_at` = COALESCE(`updated_at`, NOW()) WHERE `updated_at` IS NULL");
                }
            } catch (Throwable $e) {
                // ignore
            }

            // 3) Make updated_at automatic at DB level.
            // Use DATETIME (not TIMESTAMP) to avoid MariaDB CURRENT_TIMESTAMP limitations.
            try {
                $CI->db->query(
                    "ALTER TABLE `{$tbl}` MODIFY `updated_at`
                     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
                );
            } catch (Throwable $e) {
                // Fallback: keep the column but without DEFAULT/ON UPDATE so we don't break migrations.
                // (Only relevant on very old MariaDB versions.)
                try {
                    $CI->db->query("ALTER TABLE `{$tbl}` MODIFY `updated_at` DATETIME NULL DEFAULT NULL");
                } catch (Throwable $e2) {
                    // ignore
                }
            }
        }
    }
}
