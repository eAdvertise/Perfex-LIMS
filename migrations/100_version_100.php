<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_100 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $CI->load->dbforge();

        /**
         * Helper για να προσθέτουμε subject_id + index σε διάφορα tables
         * με υποψήφιες στήλες για AFTER (για να μην σκάει αν δεν υπάρχει).
         *
         * @param string $table              core table name WITHOUT prefix (π.χ. 'invoices')
         * @param array  $afterCandidates    πιθανοί τίτλοι στηλών για AFTER (π.χ. ['clientid','client_id'])
         * @param string $indexName          όνομα index για subject_id
         */
        $addSubjectColumn = function ($table, array $afterCandidates = [], $indexName = null) use ($CI) {

            // UN-prefixed table name για CI helpers
            $coreTable = $table;
            // FULL table name με prefix για raw SQL
            $dbTable   = db_prefix() . $table;

            // Αν δεν υπάρχει ο πίνακας, μην κάνεις τίποτα
            if (!$CI->db->table_exists($coreTable)) {
                return;
            }

            // Βρες ποια από τις υποψήφιες στήλες υπάρχει πραγματικά
            $afterColumn = null;
            foreach ($afterCandidates as $candidate) {
                if ($CI->db->field_exists($candidate, $coreTable)) {
                    $afterColumn = $candidate;
                    break;
                }
            }

            // Αν δεν υπάρχει ακόμα το πεδίο subject_id -> το προσθέτουμε
            if (!$CI->db->field_exists('subject_id', $coreTable)) {
                $fields = [
                    'subject_id' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'null'       => true,
                        'default'    => null,
                    ],
                ];

                // Βάζουμε AFTER μόνο αν όντως υπάρχει η στήλη
                if (!empty($afterColumn)) {
                    $fields['subject_id']['after'] = $afterColumn;
                }

                // ΣΗΜΑΝΤΙΚΟ: εδώ περνάμε UNprefixed table name
                $CI->dbforge->add_column($coreTable, $fields);
            }

            // Index για subject_id (αν δεν υπάρχει ήδη) - εδώ χρησιμοποιούμε FULL όνομα
            if ($indexName) {
                $indexes = $CI->db->query("
                    SHOW INDEX FROM `{$dbTable}`
                    WHERE Key_name = " . $CI->db->escape($indexName)
                )->result_array();

                if (count($indexes) === 0) {
                    $CI->db->query("ALTER TABLE `{$dbTable}` ADD INDEX `{$indexName}` (`subject_id`)");
                }
            }
        };

        // 1) Invoices: tblinvoices (συνήθως clientid, αλλά παίζουμε safe)
        $addSubjectColumn('invoices', ['clientid', 'client_id'], 'idx_invoice_subject');

        // 2) Credit Notes: tblcreditnotes (συνήθως clientid, αλλά παίζουμε safe)
        $addSubjectColumn('creditnotes', ['clientid', 'client_id'], 'idx_creditnote_subject');

        // 3) Delivery Notes: tbldelivery_notes (συνήθως clientid)
        $addSubjectColumn('delivery_notes', ['clientid', 'client_id'], 'idx_dn_subject');

        // 4) Receipts: tblreceipts (στο module σου μπορεί να είναι client_id ή clientid)
        $addSubjectColumn('receipts', ['client_id', 'clientid'], 'idx_receipt_subject');


        /**
         * LIMS Billing Columns στο tblinvoices
         */
        $coreInvoicesTable = 'invoices';                  // UNprefixed
        $fullInvoicesTable = db_prefix() . 'invoices';    // FULL για raw SQL

        if ($CI->db->table_exists($coreInvoicesTable)) {

            // lims_billing_amount
            if (!$CI->db->field_exists('lims_billing_amount', $coreInvoicesTable)) {
                $CI->db->query(
                    'ALTER TABLE `' . $fullInvoicesTable . '`
                     ADD `lims_billing_amount` DECIMAL(15,2) NOT NULL DEFAULT 0
                     AFTER `total`'
                );
            }

            // lims_billing_status
            if (!$CI->db->field_exists('lims_billing_status', $coreInvoicesTable)) {
                $CI->db->query(
                    'ALTER TABLE `' . $fullInvoicesTable . '`
                     ADD `lims_billing_status` VARCHAR(20) NULL DEFAULT NULL
                     AFTER `lims_billing_amount`'
                );
            }
        }

        // Option για module version
        if (!get_option('lims_module_version')) {
            add_option('lims_module_version', '1.0.0');
        } else {
            update_option('lims_module_version', '1.0.0');
        }
    }

    public function down()
    {
        // Συνήθως στα Perfex modules δεν κάνουμε down()
        // Αν θες, μπορούμε να προσθέσουμε drop_column εδώ.
    }
}
