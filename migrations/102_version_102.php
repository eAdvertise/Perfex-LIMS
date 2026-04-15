<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
    public function up()
    {
        $CI =& get_instance();
        $p  = db_prefix();

        /*
         * 1) tbllims_orders
         *    - subject_id (αν λείπει)
         *    - indexes: subject_id, client_id+subject_id
         */
        if ($CI->db->table_exists($p . 'lims_orders')) {

            if (!$CI->db->field_exists('subject_id', $p . 'lims_orders')) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_orders`
                    ADD `subject_id` INT(11) NULL AFTER `client_id`
                ");
            }

            // index για subject_id
            $idx = $CI->db->query("
                SHOW INDEX FROM `{$p}lims_orders`
                WHERE Key_name = 'idx_subject_id'
            ")->result_array();

            if (count($idx) === 0) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_orders`
                    ADD INDEX `idx_subject_id` (`subject_id`)
                ");
            }

            // σύνθετο index για client+subject (για γρήγορα filters στο subject profile)
            $idx = $CI->db->query("
                SHOW INDEX FROM `{$p}lims_orders`
                WHERE Key_name = 'idx_client_subject'
            ")->result_array();

            if (count($idx) === 0) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_orders`
                    ADD INDEX `idx_client_subject` (`client_id`, `subject_id`)
                ");
            }
        }

        /*
         * 2) tbllims_appointments
         *    - subject_id (αν λείπει)
         *    - indexes: subject_id, order_id
         */
        if ($CI->db->table_exists($p . 'lims_appointments')) {

            if (!$CI->db->field_exists('subject_id', $p . 'lims_appointments')) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_appointments`
                    ADD `subject_id` INT(11) NULL AFTER `client_id`
                ");
            }

            // index για subject_id
            $idx = $CI->db->query("
                SHOW INDEX FROM `{$p}lims_appointments`
                WHERE Key_name = 'idx_subject_id'
            ")->result_array();

            if (count($idx) === 0) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_appointments`
                    ADD INDEX `idx_subject_id` (`subject_id`)
                ");
            }

            // index για order_id (πιο γρήγορα joins με orders)
            if ($CI->db->field_exists('order_id', $p . 'lims_appointments')) {
                $idx = $CI->db->query("
                    SHOW INDEX FROM `{$p}lims_appointments`
                    WHERE Key_name = 'idx_order_id'
                ")->result_array();

                if (count($idx) === 0) {
                    $CI->db->query("
                        ALTER TABLE `{$p}lims_appointments`
                        ADD INDEX `idx_order_id` (`order_id`)
                    ");
                }
            }
        }

        /*
         * 3) tbllims_samples
         *    - subject_id (αν λείπει)
         *    - indexes: subject_id, appointment_id, sample_type_id
         */
        if ($CI->db->table_exists($p . 'lims_samples')) {

            if (!$CI->db->field_exists('subject_id', $p . 'lims_samples')) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_samples`
                    ADD `subject_id` INT(11) NULL AFTER `order_id`
                ");
            }

            // index για subject_id
            $idx = $CI->db->query("
                SHOW INDEX FROM `{$p}lims_samples`
                WHERE Key_name = 'idx_subject_id'
            ")->result_array();

            if (count($idx) === 0) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_samples`
                    ADD INDEX `idx_subject_id` (`subject_id`)
                ");
            }

            // index για appointment_id
            if ($CI->db->field_exists('appointment_id', $p . 'lims_samples')) {
                $idx = $CI->db->query("
                    SHOW INDEX FROM `{$p}lims_samples`
                    WHERE Key_name = 'idx_appointment_id'
                ")->result_array();

                if (count($idx) === 0) {
                    $CI->db->query("
                        ALTER TABLE `{$p}lims_samples`
                        ADD INDEX `idx_appointment_id` (`appointment_id`)
                    ");
                }
            }

            // index για sample_type_id
            if ($CI->db->field_exists('sample_type_id', $p . 'lims_samples')) {
                $idx = $CI->db->query("
                    SHOW INDEX FROM `{$p}lims_samples`
                    WHERE Key_name = 'idx_sample_type_id'
                ")->result_array();

                if (count($idx) === 0) {
                    $CI->db->query("
                        ALTER TABLE `{$p}lims_samples`
                        ADD INDEX `idx_sample_type_id` (`sample_type_id`)
                    ");
                }
            }
        }

        /*
         * 4) tbllims_contracts
         *    - subject_id (νέο πεδίο για per-subject συμβόλαια)
         *    - index: subject_id
         */
        if ($CI->db->table_exists($p . 'lims_contracts')) {

            if (!$CI->db->field_exists('subject_id', $p . 'lims_contracts')) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_contracts`
                    ADD `subject_id` INT(11) NULL AFTER `client_id`
                ");
            }

            $idx = $CI->db->query("
                SHOW INDEX FROM `{$p}lims_contracts`
                WHERE Key_name = 'idx_subject_id'
            ")->result_array();

            if (count($idx) === 0) {
                $CI->db->query("
                    ALTER TABLE `{$p}lims_contracts`
                    ADD INDEX `idx_subject_id` (`subject_id`)
                ");
            }
        }

        // Αν αργότερα χρειαστούμε κι άλλα subject-related indexes, τα βάζουμε εδώ.
    }

    public function down()
    {
        // Συνήθως στα Perfex migrations δεν κάνουμε rollback (μένει κενό).
        // Αν θέλεις, μπορούμε να προσθέσουμε εδώ DROP INDEX / DROP COLUMN.
    }
}
