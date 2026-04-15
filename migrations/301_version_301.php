<?php defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_301 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $p  = db_prefix();

        $tblPartners = $p.'lims_partners';

        if ($CI->db->table_exists($tblPartners)) {
            // Add barcode prefix for partner barcode namespaces (e.g. LPT- / NTL-).
            if (!$CI->db->field_exists('barcode_prefix', $tblPartners)) {
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD COLUMN `barcode_prefix` VARCHAR(16) NULL AFTER `name`");
            }

            // Unique index for barcode_prefix (ignore NULLs)
            $idx = $CI->db->query("SHOW INDEX FROM `{$tblPartners}` WHERE Key_name='uq_partner_barcode_prefix'")->num_rows();
            if ($idx == 0) {
                // MariaDB/MySQL allows multiple NULLs in UNIQUE index.
                $CI->db->query("ALTER TABLE `{$tblPartners}` ADD UNIQUE KEY `uq_partner_barcode_prefix` (`barcode_prefix`)");
            }
        }
    }
}
