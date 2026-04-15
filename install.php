<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lims_install
{
    public function install()
    {
        $CI =& get_instance();
		$CI->load->dbforge();
        $p   = db_prefix();
		$prefix = db_prefix();
		
        $orders          = $p.'lims_orders';
        $samples         = $p.'lims_samples';
        $tests           = $p.'lims_tests';
        $results         = $p.'lims_results';
        $barcodes        = $p.'lims_barcode_sequences';
        $contracts       = $p.'lims_contracts';
        $contract_prices = $p.'lims_contract_prices';
        $appointments    = $p.'lims_appointments';
        $billing_links   = $p.'lims_billing_links';
        $partners        = $p.'lims_partners';
        $item_rates      = $p.'lims_item_rates';
        $test_statuses   = $p.'lims_test_statuses';
        $activity        = $p.'lims_order_activity';

        /* -------- Core LIMS: Orders -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$orders}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `client_id` INT UNSIGNED NOT NULL,
            `contract_id` INT UNSIGNED NULL,
            `external_ref` VARCHAR(100) NULL,
            `status` ENUM('draft','submitted','accessioned','testing','verified','approved','reported') DEFAULT 'draft',
            `priority` TINYINT DEFAULT 0,
            `received_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `due_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `order_barcode` VARCHAR(32) NULL,
            `notes` TEXT NULL,
            `created_by` INT UNSIGNED NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (`client_id`),
            INDEX (`contract_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Samples -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$samples}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `order_id` INT UNSIGNED NOT NULL,
            `appointment_id` INT UNSIGNED NULL,
            `sample_uid` VARCHAR(50) NOT NULL,
            `barcode` VARCHAR(50) NOT NULL,
            `sample_type_id` INT UNSIGNED NULL,
            `collected_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `received_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `status` VARCHAR(30) NULL,
            `notes` TEXT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (`order_id`),
            INDEX (`appointment_id`),
            INDEX (`sample_type_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Tests (per sample) -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$tests}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `sample_id` INT UNSIGNED NOT NULL,
            `analysis_id` INT UNSIGNED NULL,
            `status` ENUM('pending','in_progress','completed','verified') DEFAULT 'pending',
            `status_code` VARCHAR(64) NULL,
            `assigned_staff` INT UNSIGNED NULL,
            `item_id` INT UNSIGNED NULL,
            `unit_price` DECIMAL(15,4) NULL,
            `currency` VARCHAR(10) NULL,
            `started_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `completed_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `verified_by` INT UNSIGNED NULL,
            `verified_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `approved_by` INT UNSIGNED NULL,
            `approved_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `reported_by` INT UNSIGNED NULL,
            `reported_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`sample_id`),
            INDEX (`analysis_id`),
            INDEX (`item_id`),
            INDEX (`assigned_staff`),
            INDEX (`status_code`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Results (per test) -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$results}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `test_id` INT UNSIGNED NOT NULL,
            `value_numeric` DECIMAL(15,6) NULL,
            `value_text` VARCHAR(255) NULL,
            `unit` VARCHAR(50) NULL,
            `flag` ENUM('L','H','LL','HH','A') NULL,
            `measured_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`test_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Barcode sequences -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$barcodes}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `prefix` VARCHAR(10) NOT NULL UNIQUE,
            `next_number` INT UNSIGNED NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Contracts (per client) -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$contracts}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `client_id` INT UNSIGNED NOT NULL,
            `name` VARCHAR(150) NOT NULL,
            `code` VARCHAR(100) NULL,
            `discount_percent` DECIMAL(10,2) NULL,
            `active` TINYINT(1) DEFAULT 1,
            `priority` INT DEFAULT 0,
            `valid_from` DATE NULL,
            `valid_to` DATE NULL,
            `notes` TEXT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`client_id`),
            INDEX (`active`),
            INDEX (`priority`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Contract Prices (per item+currency) -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$contract_prices}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `contract_id` INT UNSIGNED NOT NULL,
            `item_id` INT UNSIGNED NOT NULL,
            `fixed_price` DECIMAL(15,4) NOT NULL,
            `currency` VARCHAR(10) NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`contract_id`),
            INDEX(`item_id`),
            INDEX(`currency`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Appointments -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$appointments}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `client_id` INT UNSIGNED NOT NULL,
            `order_id` INT UNSIGNED NULL,
            `appointment_at` DATETIME NOT NULL,
            `visit_type` ENUM('lab','home') DEFAULT 'lab',
            `location_text` VARCHAR(255) NULL,
            `lat` DECIMAL(10,7) NULL,
            `lng` DECIMAL(10,7) NULL,
            `status` ENUM('pending','confirmed','completed','canceled','no_show') DEFAULT 'pending',
            `assigned_staff` INT UNSIGNED NULL,
            `task_id` INT UNSIGNED NULL,
            `notes` TEXT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`client_id`),
            INDEX(`appointment_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Billing links (Order->Invoice) -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$billing_links}` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `order_id` INT UNSIGNED NOT NULL,
            `invoice_id` INT UNSIGNED NOT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`order_id`),
            INDEX (`invoice_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Catalog: Sample Types -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_sample_types` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(191) NOT NULL,
            `code` VARCHAR(64) NULL,
            `snomed_specimen_code` VARCHAR(64) NULL,
            `min_volume` VARCHAR(64) NULL,
            `container` VARCHAR(128) NULL,
            `stability_hours` INT UNSIGNED NULL,
            `storage_temp` VARCHAR(64) NULL,
            `collection_instructions` TEXT NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY `uq_sampletype_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Catalog: Analyses -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_analyses` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(191) NOT NULL,
            `code` VARCHAR(64) NULL,
            `department` VARCHAR(128) NULL,
            `department_id` INT UNSIGNED NULL,
            `sample_type_id` INT UNSIGNED NULL,
            `method` VARCHAR(191) NULL,
            `tat_hours` INT UNSIGNED NULL,
            `result_type` ENUM('numeric','text','select') NOT NULL DEFAULT 'numeric',
            `decimal_places` TINYINT UNSIGNED NULL,
            `units_ucum` VARCHAR(64) NULL,
            `loinc_code` VARCHAR(64) NULL,
            `item_id` INT UNSIGNED NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_item` (`item_id`),
            KEY `idx_analysis_dept` (`department_id`),
            KEY `idx_analysis_sampletype` (`sample_type_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Catalog: Analysis Specifications -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_analysis_specs` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `analysis_id` INT UNSIGNED NOT NULL,
            `sample_type_id` INT UNSIGNED NOT NULL,
            `sex` ENUM('U','M','F') NOT NULL DEFAULT 'U',
            `age_min` DECIMAL(8,2) NULL,
            `age_max` DECIMAL(8,2) NULL,
            `ref_low` DECIMAL(18,6) NULL,
            `ref_high` DECIMAL(18,6) NULL,
            `critical_low` DECIMAL(18,6) NULL,
            `critical_high` DECIMAL(18,6) NULL,
            `unit_ucum` VARCHAR(64) NULL,
            `version` INT UNSIGNED NULL,
            `effective_from` DATE NULL,
            `effective_to` DATE NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            KEY `idx_analysis_sampletype` (`analysis_id`,`sample_type_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Catalog: Panels -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_panels` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(191) NOT NULL,
            `code` VARCHAR(64) NULL,
            `department_id` INT UNSIGNED NULL,
            `item_id` INT UNSIGNED NULL,
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_panel_dept` (`department_id`),
            KEY `idx_panel_item` (`item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_panel_items` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `panel_id` INT UNSIGNED NOT NULL,
            `analysis_id` INT UNSIGNED NOT NULL,
            `culture_id` INT UNSIGNED NULL,
            `required` TINYINT(1) NOT NULL DEFAULT 1,
            `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
            KEY `idx_panel` (`panel_id`),
            KEY `idx_analysis` (`analysis_id`),
            KEY `idx_panel_culture` (`panel_id`,`culture_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Departments -------- */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_departments` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `name` VARCHAR(191) NOT NULL,
          `code` VARCHAR(64) NULL,
          `active` TINYINT(1) NOT NULL DEFAULT 1,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY `uq_lims_dept_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Multi-currency item rates -------- */
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$item_rates}` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `item_id` INT UNSIGNED NOT NULL,
          `currency` VARCHAR(32) NOT NULL,
          `rate` DECIMAL(15,4) NOT NULL DEFAULT 0,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          KEY `idx_item_currency` (`item_id`,`currency`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Partners -------- */
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$partners}` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `customer_id` INT UNSIGNED NULL,
          `name` VARCHAR(191) NOT NULL,
          `email` VARCHAR(191) NULL,
          `phone` VARCHAR(64) NULL,
          `website` VARCHAR(191) NULL,
          `address` VARCHAR(255) NULL,
          `notes` TEXT NULL,
          `api_key` VARCHAR(128) NULL,
          `api_base_url` VARCHAR(255) NULL,
          `api_secret` VARCHAR(255) NULL,
          `sync_enabled` TINYINT(1) NOT NULL DEFAULT 1,
          `last_seen_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `last_error` TEXT NULL,
          `active` TINYINT(1) NOT NULL DEFAULT 1,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          KEY `idx_customer` (`customer_id`),
          KEY `idx_active` (`active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Test Statuses -------- */
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$test_statuses}` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `name` VARCHAR(100) NOT NULL,
          `code` VARCHAR(64) NOT NULL,
          `color` VARCHAR(20) NULL,
          `position` INT UNSIGNED NOT NULL DEFAULT 0,
          `is_default` TINYINT(1) NOT NULL DEFAULT 0,
          `is_terminal` TINYINT(1) NOT NULL DEFAULT 0,
          `requires_result` TINYINT(1) NOT NULL DEFAULT 0,
          `requires_verification` TINYINT(1) NOT NULL DEFAULT 0,
          `requires_approval` TINYINT(1) NOT NULL DEFAULT 0,
          `active` TINYINT(1) NOT NULL DEFAULT 1,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY `uq_code` (`code`),
          KEY `idx_active` (`active`),
          KEY `idx_position` (`position`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        if ((int)$CI->db->count_all($test_statuses) === 0) {
          $seed = [
            ['name'=>'Pending','code'=>'pending','color'=>'#999999','position'=>1,'is_default'=>1,'requires_result'=>0,'is_terminal'=>0],
            ['name'=>'In Progress','code'=>'in_progress','color'=>'#3a87ad','position'=>2,'is_default'=>0,'requires_result'=>0,'is_terminal'=>0],
            ['name'=>'Completed','code'=>'completed','color'=>'#5cb85c','position'=>3,'is_default'=>0,'requires_result'=>1,'is_terminal'=>0],
            ['name'=>'Verified','code'=>'verified','color'=>'#8e44ad','position'=>4,'is_default'=>0,'requires_result'=>1,'requires_verification'=>1,'is_terminal'=>0],
            ['name'=>'Approved','code'=>'approved','color'=>'#2ecc71','position'=>5,'is_default'=>0,'requires_result'=>1,'requires_verification'=>1,'requires_approval'=>1,'is_terminal'=>0],
            ['name'=>'Reported','code'=>'reported','color'=>'#34495e','position'=>6,'is_default'=>0,'requires_result'=>1,'is_terminal'=>1],
          ];
          foreach ($seed as $r) { $CI->db->insert($test_statuses, $r); }
        }

        /* -------- Order Items (pricing snapshot) -------- */
        $oi = $p.'lims_order_items';
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$oi}` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `order_id` INT UNSIGNED NOT NULL,
          `item_id` INT UNSIGNED NULL,
          `source_type` ENUM('panel','analysis','culture') NOT NULL,
          `source_id` INT UNSIGNED NOT NULL,
          `name` VARCHAR(191) NOT NULL,
          `qty` DECIMAL(15,4) NOT NULL DEFAULT 1,
          `unit` VARCHAR(50) NULL,
          `currency_id` INT UNSIGNED NOT NULL,
          `unit_price` DECIMAL(15,4) NOT NULL DEFAULT 0,
          `tax1_id` INT UNSIGNED NULL,
          `tax2_id` INT UNSIGNED NULL,
          `from_contract_id` INT UNSIGNED NULL,
          `discount_percent` DECIMAL(10,2) NULL,
          `fixed_price_applied` TINYINT(1) NOT NULL DEFAULT 0,
          `referred_partner_id` INT UNSIGNED NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          KEY `idx_order` (`order_id`),
          KEY `idx_source` (`source_type`,`source_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Order Activity -------- */
        $CI->db->query("CREATE TABLE IF NOT EXISTS `{$activity}` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `order_id` INT UNSIGNED NOT NULL,
          `action` VARCHAR(64) NOT NULL,
          `message` TEXT NULL,
          `meta` TEXT NULL,
          `staff_id` INT UNSIGNED NULL,
          `created_at` DATETIME NOT NULL,
          KEY `idx_order` (`order_id`),
          KEY `idx_action` (`action`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* -------- Ensure barcode sequence for ORD exists -------- */
        $exists = $CI->db->where('prefix','ORD')->get($barcodes)->row();
        if(!$exists){
            $CI->db->insert($barcodes, ['prefix'=>'ORD','next_number'=>1]);
        }

        /* ===== NEW TABLES: Culture Types & Cultures ===== */
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_culture_types` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `name` VARCHAR(191) NOT NULL,
          `code` VARCHAR(64) NULL,
          `description` TEXT NULL,
          `active` TINYINT(1) NOT NULL DEFAULT 1,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY `uq_culturetype_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$p}lims_cultures` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `name` VARCHAR(191) NOT NULL,
          `code` VARCHAR(64) NULL,
          `culture_type_id` INT UNSIGNED NULL,
          `sample_type_id` INT UNSIGNED NULL,
          `method` VARCHAR(191) NULL,
		  `tat_hours` INT(11) NULL DEFAULT NULL,
          `incubation_temp` VARCHAR(64) NULL,
          `incubation_time` VARCHAR(64) NULL,
          `loinc_code` VARCHAR(64) NULL,
          `item_id` INT UNSIGNED NULL,
          `active` TINYINT(1) NOT NULL DEFAULT 1,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          KEY `idx_culture_type` (`culture_type_id`),
          KEY `idx_sample_type` (`sample_type_id`),
          KEY `idx_item` (`item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // panel_items.culture_id ensure (safe on existing installs)
        if (!$CI->db->field_exists('culture_id', $p.'lims_panel_items')) {
            $CI->db->query("ALTER TABLE `{$p}lims_panel_items` ADD COLUMN `culture_id` INT UNSIGNED NULL AFTER `analysis_id`");
        }
        // ensure compound index
        $hasIdx = $CI->db->query("
            SELECT 1
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$p}lims_panel_items'
              AND INDEX_NAME = 'idx_panel_culture'
        ")->num_rows() > 0;
        if (!$hasIdx) {
            $CI->db->query("ALTER TABLE `{$p}lims_panel_items` ADD INDEX `idx_panel_culture` (`panel_id`,`culture_id`)");
        }

        /* ===== TEST AUDIT (history) ===== */
        $audit = $p.'lims_test_audit';
        $CI->db->query("
        CREATE TABLE IF NOT EXISTS `{$audit}` (
          `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `test_id` INT UNSIGNED NOT NULL,
          `action` VARCHAR(64) NOT NULL,    -- start/save/submit/verify/approve/report/edit_result/etc
          `old_status` VARCHAR(64) NULL,
          `new_status` VARCHAR(64) NULL,
          `old_value` TEXT NULL,
          `new_value` TEXT NULL,
          `reason` TEXT NULL,
          `staff_id` INT UNSIGNED NULL,
          `created_at` DATETIME NOT NULL,
          KEY `idx_test` (`test_id`),
          KEY `idx_action` (`action`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        /* ===== SAFETY / MIGRATIONS (idempotent) ===== */
        // tests.analysis_id
        if ($CI->db->query("SHOW COLUMNS FROM `{$tests}` LIKE 'analysis_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$tests}` ADD COLUMN `analysis_id` INT UNSIGNED NULL AFTER `sample_id`");
            $CI->db->query("CREATE INDEX `idx_analysis_id` ON `{$tests}` (`analysis_id`)");
        }
        // tests.item_id
        if ($CI->db->query("SHOW COLUMNS FROM `{$tests}` LIKE 'item_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$tests}` ADD COLUMN `item_id` INT UNSIGNED NULL AFTER `status_code`");
            $CI->db->query("CREATE INDEX `idx_item_id` ON `{$tests}` (`item_id`)");
        }
        // tests.status_code
        if ($CI->db->query("SHOW COLUMNS FROM `{$tests}` LIKE 'status_code'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$tests}` ADD COLUMN `status_code` VARCHAR(64) NULL AFTER `status`");
            $CI->db->query("CREATE INDEX `idx_status_code` ON `{$tests}` (`status_code`)");
        }
        // tests.assigned_staff
       $addCol = function($tbl,$col,$def) use ($CI) {
			if ($CI->db->query("SHOW COLUMNS FROM `{$tbl}` LIKE '{$col}'")->num_rows() == 0) {
				$CI->db->query("ALTER TABLE `{$tbl}` ADD COLUMN {$def}");
			}
		};


        // appointments: ensure task_id/lat/lng
        if ($CI->db->query("SHOW COLUMNS FROM `{$appointments}` LIKE 'task_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$appointments}` ADD COLUMN `task_id` INT UNSIGNED NULL AFTER `assigned_staff`");
        }
        if ($CI->db->query("SHOW COLUMNS FROM `{$appointments}` LIKE 'lat'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$appointments}` ADD COLUMN `lat` DECIMAL(10,7) NULL AFTER `location_text`");
        }
        if ($CI->db->query("SHOW COLUMNS FROM `{$appointments}` LIKE 'lng'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$appointments}` ADD COLUMN `lng` DECIMAL(10,7) NULL AFTER `lat`");
        }

        // samples: ensure notes, appointment_id
        if ($CI->db->query("SHOW COLUMNS FROM `{$samples}` LIKE 'notes'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$samples}` ADD COLUMN `notes` TEXT NULL AFTER `status`");
        }
        if ($CI->db->query("SHOW COLUMNS FROM `{$samples}` LIKE 'appointment_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$samples}` ADD COLUMN `appointment_id` INT UNSIGNED NULL AFTER `order_id`");
        }

        // orders: ensure order_barcode
        if ($CI->db->query("SHOW COLUMNS FROM `{$orders}` LIKE 'order_barcode'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$orders}` ADD COLUMN `order_barcode` VARCHAR(32) NULL AFTER `external_ref`");
        }
        // orders: ensure contract_id
        if ($CI->db->query("SHOW COLUMNS FROM `{$orders}` LIKE 'contract_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$orders}` ADD COLUMN `contract_id` INT UNSIGNED NULL AFTER `client_id`");
            $CI->db->query("CREATE INDEX `idx_contract_id` ON `{$orders}` (`contract_id`)");
        }

        // analyses: ensure sample_type_id & item_id
        if ($CI->db->query("SHOW COLUMNS FROM `{$p}lims_analyses` LIKE 'sample_type_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$p}lims_analyses` ADD COLUMN `sample_type_id` INT UNSIGNED NULL AFTER `department_id`");
            $CI->db->query("CREATE INDEX `idx_analysis_sampletype` ON `{$p}lims_analyses` (`sample_type_id`)");
        }
        if ($CI->db->query("SHOW COLUMNS FROM `{$p}lims_analyses` LIKE 'item_id'")->num_rows() == 0) {
            $CI->db->query("ALTER TABLE `{$p}lims_analyses` ADD COLUMN `item_id` INT UNSIGNED NULL AFTER `loinc_code`");
            $CI->db->query("CREATE INDEX `idx_item` ON `{$p}lims_analyses` (`item_id`)");
        }
		// Select options για αναλύσεις (JSON)
		if (!$CI->db->field_exists('select_options', db_prefix() . 'lims_analyses')) {
			$CI->db->query("
				ALTER TABLE `" . db_prefix() . "lims_analyses`
				ADD `select_options` TEXT NULL AFTER `result_type`
			");
		}
		// --- Culture Option Sets (τύποι επιλογών για cultures) ---
		if (!$CI->db->table_exists(db_prefix().'lims_culture_option_sets')) {
			$CI->db->query("
				CREATE TABLE `".db_prefix()."lims_culture_option_sets` (
				  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `name` varchar(191) NOT NULL,
				  `code` varchar(64) DEFAULT NULL,
				  `description` text,
				  `active` tinyint(1) NOT NULL DEFAULT '1',
				  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`),
				  KEY `idx_code` (`code`),
				  KEY `idx_active` (`active`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			");
		}

		// --- Culture Option Values (οι πραγματικές επιλογές ανά set) ---
		if (!$CI->db->table_exists(db_prefix().'lims_culture_option_values')) {
			$CI->db->query("
				CREATE TABLE `".db_prefix()."lims_culture_option_values` (
				  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `set_id` int(10) UNSIGNED NOT NULL,
				  `value` varchar(64) NOT NULL,
				  `label` varchar(191) NOT NULL,
				  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
				  `active` tinyint(1) NOT NULL DEFAULT '1',
				  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`),
				  KEY `idx_set` (`set_id`),
				  CONSTRAINT `fk_lims_copt_vals_set`
					FOREIGN KEY (`set_id`) REFERENCES `".db_prefix()."lims_culture_option_sets`(`id`)
					ON DELETE CASCADE ON UPDATE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			");
		}

		// --- Culture <-> Option Sets link (ποιά sets ισχύουν για κάθε culture) ---
		if (!$CI->db->table_exists(db_prefix().'lims_culture_option_links')) {
			$CI->db->query("
				CREATE TABLE `".db_prefix()."lims_culture_option_links` (
				  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `culture_id` int(10) UNSIGNED NOT NULL,
				  `set_id` int(10) UNSIGNED NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `idx_culture` (`culture_id`),
				  KEY `idx_set` (`set_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			");
		}
		// Seed default Culture Option Sets & Values (αν είναι άδεια)
		$sets_table  = db_prefix().'lims_culture_option_sets';
		$vals_table  = db_prefix().'lims_culture_option_values';

		$existing_sets = $CI->db->count_all($sets_table);
		if ((int)$existing_sets === 0) {
			// 1) Culture Result (No growth / Normal flora / Pathogen ... )
			$CI->db->insert($sets_table, [
				'name'        => 'Culture Result',
				'code'        => 'CULTURE_RESULT',
				'description' => 'High-level culture result such as No growth, Normal flora, Pathogen isolated.',
				'active'      => 1,
			]);
			$result_set_id = (int)$CI->db->insert_id();

			$result_values = [
				['value' => 'NO_GROWTH',           'label' => 'No growth',             'sort_order' => 10],
				['value' => 'NORMAL_FLORA',        'label' => 'Normal flora',          'sort_order' => 20],
				['value' => 'PATHOGEN_ISOLATED',   'label' => 'Pathogen isolated',     'sort_order' => 30],
				['value' => 'POSSIBLE_CONTAM',     'label' => 'Probable contamination','sort_order' => 40],
				['value' => 'RESULT_PENDING',      'label' => 'Result pending',        'sort_order' => 50],
			];
			foreach ($result_values as $v) {
				$v['set_id'] = $result_set_id;
				$CI->db->insert($vals_table, $v);
			}

			// 2) Semi-quantitative Growth (1+/2+/3+/4+ etc.)
			$CI->db->insert($sets_table, [
				'name'        => 'Semi-quantitative growth',
				'code'        => 'SEMI_QUANT',
				'description' => 'Semi-quantitative growth scale (e.g. 1+, 2+, 3+, 4+).',
				'active'      => 1,
			]);
			$semi_set_id = (int)$CI->db->insert_id();

			$semi_values = [
				['value' => 'SCANT',   'label' => 'Scant growth',   'sort_order' => 10],
				['value' => '1_PLUS',  'label' => '1+',             'sort_order' => 20],
				['value' => '2_PLUS',  'label' => '2+',             'sort_order' => 30],
				['value' => '3_PLUS',  'label' => '3+',             'sort_order' => 40],
				['value' => '4_PLUS',  'label' => '4+',             'sort_order' => 50],
				['value' => 'HEAVY',   'label' => 'Heavy growth',   'sort_order' => 60],
			];
			foreach ($semi_values as $v) {
				$v['set_id'] = $semi_set_id;
				$CI->db->insert($vals_table, $v);
			}

			// 3) Organism significance (Pathogen / Coloniser κ.λπ. – προαιρετικό)
			$CI->db->insert($sets_table, [
				'name'        => 'Organism significance',
				'code'        => 'ORG_SIGNIF',
				'description' => 'Significance of isolated organism (pathogen, coloniser, etc.).',
				'active'      => 1,
			]);
			$org_set_id = (int)$CI->db->insert_id();

			$org_values = [
				['value' => 'PATHOGEN',          'label' => 'Pathogen',           'sort_order' => 10],
				['value' => 'POSSIBLE_PATHOGEN', 'label' => 'Possible pathogen',  'sort_order' => 20],
				['value' => 'COLONISER',         'label' => 'Coloniser/normal flora','sort_order' => 30],
			];
			foreach ($org_values as $v) {
				$v['set_id'] = $org_set_id;
				$CI->db->insert($vals_table, $v);
			}
		}
		// Culture results (per sample + culture)
		if (!$CI->db->table_exists(db_prefix().'lims_culture_results')) {
			$CI->db->query("
				CREATE TABLE `".db_prefix()."lims_culture_results` (
				  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `sample_id` int(10) UNSIGNED NOT NULL,
				  `culture_id` int(10) UNSIGNED NOT NULL,
				  `result_text` text,
				  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
				  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`),
				  KEY `idx_sample_culture` (`sample_id`,`culture_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			");
		}
		// Ensure order_id exists on tbllims_culture_results
		if (!$CI->db->field_exists('order_id', 'tbllims_culture_results')) {
			$CI->db->query("
				ALTER TABLE `tbllims_culture_results`
				ADD `order_id` INT(10) UNSIGNED NOT NULL AFTER `id`;
			");
		}

		// Ensure composite index for (order_id, sample_id, culture_id)
		$idx = $CI->db->query("
			SHOW INDEX FROM `tbllims_culture_results`
			WHERE Key_name = 'idx_order_sample_culture'
		")->num_rows();

		if ($idx == 0) {
			$CI->db->query("
				ALTER TABLE `tbllims_culture_results`
				ADD KEY `idx_order_sample_culture` (`order_id`,`sample_id`,`culture_id`);
			");
		}
		// Ensure options_json exists on tbllims_culture_results
		if (!$CI->db->field_exists('options_json', db_prefix().'lims_culture_results')) {
			$CI->db->query("
				ALTER TABLE `".db_prefix()."lims_culture_results`
				ADD `options_json` TEXT NULL AFTER `result_text`;
			");
		}

		// ====== 1) Subjects table ======
		if (!$CI->db->table_exists($p . 'lims_subjects')) {
			// LIMS Subjects (patients / farms / labs / etc.)
			$CI->db->query("
			CREATE TABLE IF NOT EXISTS `{$p}lims_subjects` (
			  `id`                 INT(11) NOT NULL AUTO_INCREMENT,
			  `client_id`          INT(11) DEFAULT NULL,
			  `primary_contact_id` INT(11) DEFAULT NULL,

			  `subject_type`       VARCHAR(30) DEFAULT 'patient',

			  -- Ονομασία subject
			  `subject_name`       VARCHAR(191) DEFAULT NULL,
			  `first_name`         VARCHAR(100) DEFAULT NULL,
			  `last_name`          VARCHAR(100) DEFAULT NULL,

			  -- Patient/ID info
			  `internal_code`               VARCHAR(100) DEFAULT NULL,
			  `id_number`          VARCHAR(50)  DEFAULT NULL,
			  `nationality`        VARCHAR(100) DEFAULT NULL,
			  `gender`             VARCHAR(20)  DEFAULT NULL,
			  `social_insurance_no` VARCHAR(50) DEFAULT NULL,
			  `date_of_birth`      DATE DEFAULT NULL,
			  `language` VARCHAR(10) NULL DEFAULT NULL,
			  `phone`              VARCHAR(50)  DEFAULT NULL,
			  `email`              VARCHAR(100) DEFAULT NULL,
			  `address`            VARCHAR(255) DEFAULT NULL,
			  `city`               VARCHAR(100) DEFAULT NULL,
			  `state`              VARCHAR(100) DEFAULT NULL,
			  `zip`                VARCHAR(30)  DEFAULT NULL,
			  `country`            INT(11) DEFAULT NULL,

			  `notes`              TEXT DEFAULT NULL,
			  `active`             TINYINT(1) NOT NULL DEFAULT 1,

			  `created_at`         TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

			  PRIMARY KEY (`id`),
			  KEY `client_id` (`client_id`),
			  KEY `primary_contact_id` (`primary_contact_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
			");

		}
		
		
		
		// ====== 2) subject_id σε Orders / Appointments / Samples ======

		// tbllims_orders.subject_id
		if (!$CI->db->field_exists('subject_id', $p . 'lims_orders')) {
			$CI->db->query("
				ALTER TABLE `{$p}lims_orders`
				ADD COLUMN `subject_id` INT(11) NULL DEFAULT NULL
				AFTER `client_id`;
			");
		}

		// tbllims_appointments.subject_id
		if ($CI->db->table_exists($p . 'lims_appointments')
			&& !$CI->db->field_exists('subject_id', $p . 'lims_appointments')) {

			$CI->db->query("
				ALTER TABLE `{$p}lims_appointments`
				ADD COLUMN `subject_id` INT(11) NULL DEFAULT NULL
				AFTER `client_id`;
			");
		}

		// tbllims_samples.subject_id (προαιρετικό αλλά χρήσιμο)
		if ($CI->db->table_exists($p . 'lims_samples')
			&& !$CI->db->field_exists('subject_id', $p . 'lims_samples')) {

			$CI->db->query("
				ALTER TABLE `{$p}lims_samples`
				ADD COLUMN `subject_id` INT(11) NULL DEFAULT NULL
				AFTER `order_id`;
			");
		}
	




        // ====== 3) Partner Sync (v3.0.0) ======

        // Orders: canonical UID + partner fields
        if ($CI->db->table_exists($p.'lims_orders')) {
            if (!$CI->db->field_exists('order_uid', $p.'lims_orders')) {
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD COLUMN `order_uid` CHAR(36) NULL AFTER `id`");
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD UNIQUE KEY `uq_order_uid` (`order_uid`)");
            }
            if (!$CI->db->field_exists('partner_id', $p.'lims_orders')) {
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD COLUMN `partner_id` INT(10) UNSIGNED NULL AFTER `contract_id`");
            }
            if (!$CI->db->field_exists('partner_direction', $p.'lims_orders')) {
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD COLUMN `partner_direction` ENUM('outbound','inbound') NULL AFTER `partner_id`");
            }
            if (!$CI->db->field_exists('partner_last_sync_at', $p.'lims_orders')) {
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD COLUMN `partner_last_sync_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `partner_direction`");
            }
            if (!$CI->db->field_exists('partner_sync_status', $p.'lims_orders')) {
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD COLUMN `partner_sync_status` VARCHAR(32) NULL AFTER `partner_last_sync_at`");
            }
            if (!$CI->db->field_exists('partner_sync_error', $p.'lims_orders')) {
                $CI->db->query("ALTER TABLE `{$p}lims_orders` ADD COLUMN `partner_sync_error` TEXT NULL AFTER `partner_sync_status`");
            }

            // Backfill order_uid if empty (safe)
            $rows = $CI->db->select('id')->from($p.'lims_orders')->where('(order_uid IS NULL OR order_uid = \'\')', null, false)->get()->result();
            foreach ($rows as $r) {
                $uid = function_exists('lims_uuid_v4') ? lims_uuid_v4() : null;
                if (!$uid) { $uid = substr(md5(uniqid('', true)),0,8).'-'.substr(md5(uniqid('', true)),8,4).'-'.substr(md5(uniqid('', true)),12,4).'-'.substr(md5(uniqid('', true)),16,4).'-'.substr(md5(uniqid('', true)),20,12); }
                $CI->db->where('id', (int)$r->id)->update($p.'lims_orders', ['order_uid'=>$uid]);
            }
        }

        // Subjects: canonical UID + origin mapping
        if ($CI->db->table_exists($p.'lims_subjects')) {
            if (!$CI->db->field_exists('subject_uid', $p.'lims_subjects')) {
                $CI->db->query("ALTER TABLE `{$p}lims_subjects` ADD COLUMN `subject_uid` CHAR(36) NULL AFTER `id`");
                $CI->db->query("ALTER TABLE `{$p}lims_subjects` ADD UNIQUE KEY `uq_subject_uid` (`subject_uid`)");
            }
            if (!$CI->db->field_exists('origin_partner_id', $p.'lims_subjects')) {
                $CI->db->query("ALTER TABLE `{$p}lims_subjects` ADD COLUMN `origin_partner_id` INT(10) UNSIGNED NULL AFTER `subject_uid`");
            }
            if (!$CI->db->field_exists('origin_subject_uid', $p.'lims_subjects')) {
                $CI->db->query("ALTER TABLE `{$p}lims_subjects` ADD COLUMN `origin_subject_uid` CHAR(36) NULL AFTER `origin_partner_id`");
                $CI->db->query("ALTER TABLE `{$p}lims_subjects` ADD UNIQUE KEY `uq_origin_subject` (`origin_partner_id`,`origin_subject_uid`)");
            }

            $rows = $CI->db->select('id')->from($p.'lims_subjects')->where('(subject_uid IS NULL OR subject_uid = \'\')', null, false)->get()->result();
            foreach ($rows as $r) {
                $uid = function_exists('lims_uuid_v4') ? lims_uuid_v4() : null;
                if (!$uid) { $uid = substr(md5(uniqid('', true)),0,8).'-'.substr(md5(uniqid('', true)),8,4).'-'.substr(md5(uniqid('', true)),12,4).'-'.substr(md5(uniqid('', true)),16,4).'-'.substr(md5(uniqid('', true)),20,12); }
                $CI->db->where('id', (int)$r->id)->update($p.'lims_subjects', ['subject_uid'=>$uid]);
            }
        }

        // Partners: API config fields
        if ($CI->db->table_exists($p.'lims_partners')) {
            if (!$CI->db->field_exists('api_base_url', $p.'lims_partners')) {
                $CI->db->query("ALTER TABLE `{$p}lims_partners` ADD COLUMN `api_base_url` VARCHAR(255) NULL AFTER `api_key`");
            }
            if (!$CI->db->field_exists('api_secret', $p.'lims_partners')) {
                $CI->db->query("ALTER TABLE `{$p}lims_partners` ADD COLUMN `api_secret` VARCHAR(255) NULL AFTER `api_base_url`");
            }
            if (!$CI->db->field_exists('sync_enabled', $p.'lims_partners')) {
                $CI->db->query("ALTER TABLE `{$p}lims_partners` ADD COLUMN `sync_enabled` TINYINT(1) NOT NULL DEFAULT 1 AFTER `api_secret`");
            }
            if (!$CI->db->field_exists('last_seen_at', $p.'lims_partners')) {
                $CI->db->query("ALTER TABLE `{$p}lims_partners` ADD COLUMN `last_seen_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `sync_enabled`");
            }
            if (!$CI->db->field_exists('last_error', $p.'lims_partners')) {
                $CI->db->query("ALTER TABLE `{$p}lims_partners` ADD COLUMN `last_error` TEXT NULL AFTER `last_seen_at`");
            }
        }
		// ------------------------------------------------------------------------
		// Report Notes (fresh install)
		// ------------------------------------------------------------------------
		$tblReportNotes = db_prefix() . 'lims_report_notes';

		if (!$CI->db->table_exists($tblReportNotes)) {

			$CI->db->query("
				CREATE TABLE `{$tblReportNotes}` (
				  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
				  `note_el` text COLLATE utf8mb4_unicode_ci NOT NULL,
				  `note_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
				  `active` tinyint(1) NOT NULL DEFAULT '1',
				  `position` int(11) NOT NULL DEFAULT '0',
				  `sort_order` int(11) NOT NULL DEFAULT '0',
				  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`),
				  KEY `idx_active_sort` (`active`, `sort_order`),
				  KEY `idx_position` (`position`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
			");

			// Seed default notes (fresh install only)
			$CI->db->query("
				INSERT INTO `{$tblReportNotes}`
					(`id`, `code`, `note_el`, `note_en`, `active`, `position`, `sort_order`, `created_at`, `updated_at`)
				VALUES
					(1, 'Samples Note',
					 'Τα αποτελέσματα αφορούν μόνο τα δείγματα που έχουν εξεταστεί.',
					 'The results apply only to the samples that have been tested.',
					 1, 0, 1, '2025-12-17 19:27:36', '2025-12-17 19:27:36'),
					(2, 'Δήλωση Συμμόρφωσης',
					 'Δήλωση Συμμόρφωσης: Συνάδει με τους περί της Ποιότητας του Νερού Ανθρώπινης Κατανάλωσης Νόμος του 2023 (Ν.46(Ι)/2023).',
					 'Statement of Compliance: In accordance with the Quality of Water Intended for Human Consumption Law of 2023  (Ν.46(Ι)/2023).',
					 1, 0, 2, '2025-12-17 19:30:13', '2025-12-17 19:30:13');
			");
		}

        // Per-sample requested cultures (optional table)
        $tblSampleCultures = $p.'lims_sample_cultures';
        if (!$CI->db->table_exists($tblSampleCultures)) {
            $CI->db->query("
                CREATE TABLE `{$tblSampleCultures}` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `order_id` INT(10) UNSIGNED NOT NULL,
                  `sample_id` INT(10) UNSIGNED NOT NULL,
                  `culture_id` INT(10) UNSIGNED NOT NULL,
                  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_sample_culture` (`sample_id`,`culture_id`),
                  KEY `idx_order_id` (`order_id`),
                  KEY `idx_sample_id` (`sample_id`),
                  KEY `idx_culture_id` (`culture_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

        // Sync outbox / inbox
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
                  `next_retry_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `last_error` TEXT NULL,
                  `created_at` DATETIME NOT NULL,
                  `sent_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_idem` (`partner_id`,`idempotency_key`),
                  KEY `idx_partner_next_retry` (`partner_id`,`next_retry_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

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
                  `processed_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `status` VARCHAR(32) NOT NULL DEFAULT 'received',
                  `last_error` TEXT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uq_inbox` (`partner_id`,`idempotency_key`),
                  KEY `idx_partner_received` (`partner_id`,`received_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }


        /* -------- Default options -------- */
        add_option('lims_barcode_prefix', 'SMP');
        add_option('lims_default_department', '');
        add_option('lims_enable_contracts', '1');

        // Label settings (used by Labels PDF)
        add_option('lims_label_page_width_mm',  210);
        add_option('lims_label_page_height_mm', 297);
        add_option('lims_label_columns',        3);
        add_option('lims_label_rows',           8);
        add_option('lims_label_width_mm',       70);
        add_option('lims_label_height_mm',      35);
        add_option('lims_label_hgap_mm',        5);
        add_option('lims_label_vgap_mm',        5);
        add_option('lims_label_left_margin_mm', 10);
        add_option('lims_label_top_margin_mm',  10);
        add_option('lims_label_font_size',      8);
        add_option('lims_label_barcode_height', 12);
        add_option('lims_label_show_received',  1);
        add_option('lims_label_show_sampletype',1);
        add_option('lims_label_show_analysis',  1);

        // Report PDF settings (for later)
        add_option('lims_report_logo_max_w',    80);
        add_option('lims_report_show_ranges',   1);
        add_option('lims_report_show_flags',    1);
        add_option('lims_report_footer',        '');

        return true;
    }

    public function uninstall()
    {
        return true;
    }
}

// ------------------------------------------------------
// Backward-compatible installer entrypoints
// Perfex activation hooks expect a global function <module>_install()
// in many modules. This wrapper ensures fresh installs run correctly.
// ------------------------------------------------------

if (!function_exists('lims_install')) {
    function lims_install()
    {
        $installer = new Lims_install();
        return $installer->install();
    }
}

// Optional update helper (safe no-op for now)
if (!function_exists('lims_update')) {
    function lims_update()
    {
        // For now we reuse install() as it is idempotent (CREATE TABLE IF NOT EXISTS, etc.)
        $installer = new Lims_install();
        return $installer->install();
    }
}
