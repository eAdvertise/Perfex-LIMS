<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $CI->load->dbforge();

        // Χρησιμοποιούμε "γυμνό" όνομα πίνακα για dbforge/table_exists/field_exists
        $table   = 'invoicepaymentrecords';
        $dbTable = db_prefix() . $table; // full όνομα πίνακα για raw SQL

        // Αν ο πίνακας δεν υπάρχει, κάνουμε απλά skip (να μην σκάει σε άλλα setups)
        if (!$CI->db->table_exists($table)) {
            return;
        }

        // Προσθέτουμε subject_id αν δεν υπάρχει ήδη
        if (!$CI->db->field_exists('subject_id', $table)) {
            $fields = [
                'subject_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'invoiceid', // να κάθεται δίπλα στο invoiceid
                ],
            ];

            // ΕΔΩ περνάμε το *όνομα πίνακα χωρίς prefix*, dbforge θα βάλει μόνο του το prefix
            $CI->dbforge->add_column($table, $fields);
        }

        // Index για πιο γρήγορο filter ανά subject
        $indexName = 'idx_payment_subject';

        // ΕΔΩ χρησιμοποιούμε το πλήρες όνομα πίνακα με prefix (γιατί είναι raw SQL)
        $indexes = $CI->db->query("
            SHOW INDEX FROM `{$dbTable}`
            WHERE Key_name = " . $CI->db->escape($indexName)
        )->result_array();

        if (count($indexes) === 0) {
            $CI->db->query("ALTER TABLE `{$dbTable}` ADD INDEX `{$indexName}` (`subject_id`)");
        }
    }

    public function down()
    {
        // δεν κάνουμε drop τις στήλες στα Perfex modules συνήθως
        // $CI =& get_instance();
        // $CI->load->dbforge();
        // $CI->dbforge->drop_column('invoicepaymentrecords', 'subject_id');
    }
}
