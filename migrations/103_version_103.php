<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
    public function up()
    {
        // Πάρε το CI instance – εδώ έχουμε το $db
        $CI = &get_instance();
        $db = $CI->db;

        // 1) Ασφαλής έλεγχος ύπαρξης πίνακα tests
        if (!$db->table_exists(db_prefix() . 'lims_tests')) {
            return;
        }

        // 2) ALTER ENUM στο tbllims_tests.status για να προσθέσουμε "signed"
        $db->query("
            ALTER TABLE `" . db_prefix() . "lims_tests`
            MODIFY `status` ENUM('pending','in_progress','completed','verified','signed')
            DEFAULT 'pending';
        ");

        // 3) Εισαγωγή row στο tbllims_test_statuses για το "signed" (αν δεν υπάρχει ήδη)
        if ($db->table_exists('tbllims_test_statuses')) {
            $exists = $db
                ->where('code', 'signed')
                ->get('tbllims_test_statuses')
                ->row();

            if (!$exists) {
                $db->insert('tbllims_test_statuses', [
                    'code'     => 'signed',
                    'name'     => 'Signed',
                    'color'    => '#3f51b5',
                    'position' => 90,
                    'is_default'           => 0,
                    'is_terminal'          => 1,
                    'requires_result'      => 1,
                    'requires_verification'=> 1,
                    'requires_approval'    => 1,
                    'active'   => 1,
                ]);
            }
        }
    }
}
