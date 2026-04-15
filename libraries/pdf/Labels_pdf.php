<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'libraries/pdf/App_pdf.php');

class Labels_pdf extends App_pdf
{
    /** @var object { order:object, samples:array<object> } */
    protected $data;

    public function __construct($payload, $tag = '')
    {
        parent::__construct();

        if (!is_object($payload) || !isset($payload->order) || !isset($payload->samples)) {
            throw new InvalidArgumentException('Labels_pdf requires payload with order & samples.');
        }

        $this->data = $payload;
        $this->tag  = $tag;

        $orderId = (int)($this->data->order->id ?? 0);
        $this->SetTitle('Sample Labels - Order #'.$orderId);

        // Γενικές ρυθμίσεις header/footer off για labels
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);

        // Ορισμός διάστασης σελίδας από settings (mm)
        $w = (float) get_option('lims_label_page_width_mm');
        $h = (float) get_option('lims_label_page_height_mm');
        $this->setPageFormat([max(50,$w), max(50,$h)], 'P'); // Portrait

        // Μικρά margins – πραγματικά margins ελέγχονται από το view με setXY offsets
        $this->SetMargins(0, 0, 0);
    }

    protected function type()
    {
        return 'lims_labels';
    }

    protected function file_path()
    {
        // View μέσα στο module
        return APP_MODULES_PATH.'lims/views/themes/labels.php';
    }

    protected function file_name()
    {
        $orderId = (int)($this->data->order->id ?? 0);
        return 'Order-'.$orderId.'-Sample-Labels.pdf';
    }

    public function prepare()
    {
        // Πέρνα ό,τι χρειάζεται το view
        $this->set_view_vars([
            'order'   => $this->data->order,
            'samples' => $this->data->samples,
        ]);

        return $this->build();
    }
}
