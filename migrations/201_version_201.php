<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_201 extends App_module_migration
{
    public function up()
    {
        $CI =& get_instance();
        $prefix = db_prefix();

        /**
         * Στόχοι:
         *  - tbllims_orders.status
         *  - tbllims_tests.status
         *
         * Τα κάνουμε VARCHAR(50) για να μπορούμε να χρησιμοποιούμε
         * τα codes από τον πίνακα tbllims_test_statuses χωρίς να πειράζουμε ENUMs.
         */
        $targets = [
            'lims_orders' => 'draft',
            'lims_tests'  => 'draft',
            // Αν θέλεις κι άλλα tables π.χ. 'lims_something' => 'default_status',
            // τα προσθέτεις εδώ.
        ];

        foreach ($targets as $shortTable => $defaultStatus) {
            $table = $prefix . $shortTable;

            if (!$CI->db->table_exists($table)) {
                continue;
            }

            // Έλεγξε αν υπάρχει πεδίο 'status'
            $fields = $CI->db->list_fields($table);
            if (!in_array('status', $fields, true)) {
                continue;
            }

            $defaultStatus = $defaultStatus ?: 'draft';
            $defaultEsc    = $CI->db->escape($defaultStatus); // π.χ. 'draft'

            // Αλλαγή τύπου στήλης σε VARCHAR(50)
            // Διατηρούμε NOT NULL + DEFAULT
            $sql = "ALTER TABLE `{$table}` 
                    MODIFY `status` VARCHAR(50) NOT NULL DEFAULT {$defaultEsc}";
            $CI->db->query($sql);
        }
    }
}
