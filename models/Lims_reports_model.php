<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lims_reports_model extends App_Model
{
    /**
     * Build the final data array for the Order Report PDF.
     * It expects that your Tests_model (or other model) can provide the base structure via get_order_tests_data($order_id).
     *
     * @param int $order_id
     * @param int|null $override_signed_by_staff_id
     * @return array
     */
    public function get_order_report_pdf_data($order_id, $override_signed_by_staff_id = null)
    {
        $order_id = (int)$order_id;

        // 1) Get base data (order/samples/tests/results/cultures...)
        $base = $this->get_base_order_tests_data($order_id);

        if (empty($base['order'])) {
            return $base;
        }

        $order = $base['order'];

        // 2) Subject
        $subject = null;
        if (!empty($order->subject_id)) {
            $subject = $this->db
                ->where('id', (int)$order->subject_id)
                ->get(db_prefix() . 'lims_subjects')
                ->row();
        }

        // 3) Who signs
        $signed_by_staff_id = $this->resolve_signer_staff_id($order, $override_signed_by_staff_id);

        // 4) Signature meta + signer staff row
        $signature = null;
        $signerStaff = null;
        $signature_image_path = null;

        if ($signed_by_staff_id > 0) {

            $signature = $this->db
                ->where('staff_id', $signed_by_staff_id)
                ->get(db_prefix() . 'lims_signatures')
                ->row();

            $signerStaff = $this->db
                ->where('staffid', $signed_by_staff_id)
                ->get(db_prefix() . 'staff')
                ->row();

            if ($signature && !empty($signature->image_file)) {
                $candidate = FCPATH . 'uploads/lims_signatures/' . $signature->image_file;
                if (is_file($candidate)) {
                    $signature_image_path = $candidate;
                }
            }
        }

        $base['subject'] = $subject;
        $base['signature'] = $signature;
        $base['signerStaff'] = $signerStaff;
        $base['signature_image_path'] = $signature_image_path;

        $base['signed_by_staff_id'] = $signed_by_staff_id;
        $base['signed_at'] = property_exists($order, 'signed_at') ? $order->signed_at : null;

        return $base;
    }

    /**
     * Try to load base dataset from your existing logic.
     * Adjust this if your project stores the function elsewhere.
     */
    protected function get_base_order_tests_data($order_id)
    {
        // Attempt to use existing module model
        if (!property_exists($this, 'tests_model')) {
            // Common Perfex module loading patterns
            $this->load->model('lims/Tests_model', 'tests_model');
        }

        if ($this->tests_model && method_exists($this->tests_model, 'get_order_tests_data')) {
            return (array)$this->tests_model->get_order_tests_data($order_id);
        }

        // Fallback: try direct order only so PDF doesn't fatally error
        $order = $this->db
            ->where('id', (int)$order_id)
            ->get(db_prefix() . 'lims_orders')
            ->row();

        return [
            'order' => $order,
            'samples' => [],
            'testsBySample' => [],
            'resultsByTest' => [],
            'culturesBySample' => [],
            'cultureResultsByKey' => [],
            'cultureSelectionsByKey' => [],
        ];
    }

    /**
     * Apply the signer selection rules you already used in the view.
     */
    protected function resolve_signer_staff_id($order, $override_signed_by_staff_id = null)
    {
        if (!empty($override_signed_by_staff_id)) {
            return (int)$override_signed_by_staff_id;
        }

        if (is_object($order) && property_exists($order, 'signed_by_staff_id') && !empty($order->signed_by_staff_id)) {
            return (int)$order->signed_by_staff_id;
        }

        if (is_object($order) && property_exists($order, 'created_by') && !empty($order->created_by)) {
            return (int)$order->created_by;
        }

        if (function_exists('get_staff_user_id')) {
            return (int)get_staff_user_id();
        }

        return 0;
    }
}
