<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_300 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        $tblPartners = $p.'lims_partners';
        $tblOrders   = $p.'lims_orders';
        $tblSubjects = $p.'lims_subjects';

        // ---------- Partners: API fields ----------
        if ($CI->db->table_exists($tblPartners)) {
            if (!$CI->db->field_exists('api_base_url', $tblPartners)) {
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD COLUMN `api_base_url` VARCHAR(255) NULL AFTER `api_key`");
            }
            if (!$CI->db->field_exists('api_secret', $tblPartners)) {
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD COLUMN `api_secret` VARCHAR(255) NULL AFTER `api_base_url`");
            }
            if (!$CI->db->field_exists('sync_enabled', $tblPartners)) {
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD COLUMN `sync_enabled` TINYINT(1) NOT NULL DEFAULT 1 AFTER `api_secret`");
            }
            if (!$CI->db->field_exists('last_seen_at', $tblPartners)) {
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD COLUMN `last_seen_at` DATETIME NULL AFTER `sync_enabled`");
            }
            if (!$CI->db->field_exists('last_error', $tblPartners)) {
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD COLUMN `last_error` TEXT NULL AFTER `last_seen_at`");
            }
        }

        // ---------- Orders: partner + canonical uids ----------
        if ($CI->db->table_exists($tblOrders)) {
            if (!$CI->db->field_exists('order_uid', $tblOrders)) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD COLUMN `order_uid` CHAR(36) NULL AFTER `id`");
            }

            // Backfill order_uid for existing orders (safe; does not change business logic)
            $rows = $CI->db->select('id, order_uid')->from($tblOrders)->where('(order_uid IS NULL OR order_uid = \'\')', null, false)->get()->result();
            foreach ($rows as $r) {
                $uid = function_exists('lims_uuid_v4') ? lims_uuid_v4() : $this->uuid_v4_fallback();
                $CI->db->where('id', (int)$r->id)->update($tblOrders, ['order_uid' => $uid]);
            }

            // unique index uq_order_uid
            $idx = $CI->db->query("SHOW INDEX FROM `{$tblOrders}` WHERE Key_name='uq_order_uid'")->num_rows();
            if ($idx == 0) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD UNIQUE KEY `uq_order_uid` (`order_uid`)");
            }

            if (!$CI->db->field_exists('partner_id', $tblOrders)) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD COLUMN `partner_id` INT(10) UNSIGNED NULL AFTER `contract_id`");
            }
            if (!$CI->db->field_exists('partner_direction', $tblOrders)) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD COLUMN `partner_direction` ENUM('outbound','inbound') NULL AFTER `partner_id`");
            }
            if (!$CI->db->field_exists('partner_last_sync_at', $tblOrders)) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD COLUMN `partner_last_sync_at` DATETIME NULL AFTER `partner_direction`");
            }
            if (!$CI->db->field_exists('partner_sync_status', $tblOrders)) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD COLUMN `partner_sync_status` VARCHAR(32) NULL AFTER `partner_last_sync_at`");
            }
            if (!$CI->db->field_exists('partner_sync_error', $tblOrders)) {
                $CI->db->query("ALTER TABLE `{$tblOrders}` ADD COLUMN `partner_sync_error` TEXT NULL AFTER `partner_sync_status`");
            }
        }

        // ---------- Subjects: uids + origin mapping ----------
        if ($CI->db->table_exists($tblSubjects)) {
            if (!$CI->db->field_exists('subject_uid', $tblSubjects)) {
                $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD COLUMN `subject_uid` CHAR(36) NULL AFTER `id`");
            }

            $rows = $CI->db->select('id, subject_uid')->from($tblSubjects)->where('(subject_uid IS NULL OR subject_uid = \'\')', null, false)->get()->result();
            foreach ($rows as $r) {
                $uid = function_exists('lims_uuid_v4') ? lims_uuid_v4() : $this->uuid_v4_fallback();
                $CI->db->where('id', (int)$r->id)->update($tblSubjects, ['subject_uid' => $uid]);
            }

            $idx = $CI->db->query("SHOW INDEX FROM `{$tblSubjects}` WHERE Key_name='uq_subject_uid'")->num_rows();
            if ($idx == 0) {
                $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD UNIQUE KEY `uq_subject_uid` (`subject_uid`)");
            }

            if (!$CI->db->field_exists('origin_partner_id', $tblSubjects)) {
                $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD COLUMN `origin_partner_id` INT(10) UNSIGNED NULL AFTER `subject_uid`");
            }
            if (!$CI->db->field_exists('origin_subject_uid', $tblSubjects)) {
                $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD COLUMN `origin_subject_uid` CHAR(36) NULL AFTER `origin_partner_id`");
            }

            $idx2 = $CI->db->query("SHOW INDEX FROM `{$tblSubjects}` WHERE Key_name='uq_origin_subject'")->num_rows();
            if ($idx2 == 0) {
                $CI->db->query("ALTER TABLE `{$tblSubjects}` ADD UNIQUE KEY `uq_origin_subject` (`origin_partner_id`,`origin_subject_uid`)");
            }
        }

        // ---------- Sample cultures (per sample requests) ----------
        $tblSampleCultures = $p.'lims_sample_cultures';
        if (!$CI->db->table_exists($tblSampleCultures)) {
            $CI->db->query("
                CREATE TABLE `{$tblSampleCultures}` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `order_id` INT(10) UNSIGNED NOT NULL,
                  `sample_id` INT(10) UNSIGNED NOT NULL,
                  `culture_id` INT(10) UNSIGNED NOT NULL,
                  `created_at` DATETIME NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_sample_culture` (`sample_id`,`culture_id`),
                  KEY `idx_order_id` (`order_id`),
                  KEY `idx_sample_id` (`sample_id`),
                  KEY `idx_culture_id` (`culture_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

        // ---------- Sync outbox ----------
        $tblOutbox = $p.'lims_sync_outbox';
        if (!$CI->db->table_exists($tblOutbox)) {
            $CI->db->query("
                CREATE TABLE `{$tblOutbox}` (
                  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `partner_id` INT(10) UNSIGNED NOT NULL,
                  `order_id` INT(10) UNSIGNED NULL,
                  `order_uid` CHAR(36) NULL,
                  `event_type` VARCHAR(64) NOT NULL,
                  `idempotency_key` VARCHAR(128) NOT NULL,
                  `payload_json` LONGTEXT NOT NULL,
                  `attempts` INT(11) NOT NULL DEFAULT 0,
                  `next_retry_at` DATETIME NULL,
                  `last_error` TEXT NULL,
                  `created_at` DATETIME NOT NULL,
                  `sent_at` DATETIME NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_idem` (`partner_id`,`idempotency_key`),
                  KEY `idx_partner_next_retry` (`partner_id`,`next_retry_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

        // ---------- Sync inbox ----------
        $tblInbox = $p.'lims_sync_inbox';
        if (!$CI->db->table_exists($tblInbox)) {
            $CI->db->query("
                CREATE TABLE `{$tblInbox}` (
                  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `partner_id` INT(10) UNSIGNED NOT NULL,
                  `event_type` VARCHAR(64) NOT NULL,
                  `idempotency_key` VARCHAR(128) NOT NULL,
                  `payload_hash` CHAR(64) NULL,
                  `received_at` DATETIME NOT NULL,
                  `processed_at` DATETIME NULL,
                  `status` VARCHAR(32) NOT NULL DEFAULT 'received',
                  `last_error` TEXT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_inbox` (`partner_id`,`idempotency_key`),
                  KEY `idx_partner_received` (`partner_id`,`received_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }
    }

    private function uuid_v4_fallback()
    {
        // RFC 4122 compliant UUID v4
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
