<?php
//lims/libraries/Samples_labels_pdf.php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'libraries/pdf/App_pdf.php');

class Sample_labels_pdf extends App_pdf
{
    /** @var object $order */
    protected $order;

    /** @var array $samples */
    protected $samples = [];

    public function __construct($data, $tag = '')
    {
        parent::__construct();

        if (!is_array($data) || empty($data['order']) || empty($data['samples'])) {
            throw new \InvalidArgumentException('Sample_labels_pdf requires [order, samples].');
        }
        $this->order   = $data['order'];
        $this->samples = $data['samples'];
        $this->tag     = $tag;

        // τίτλος παραμένει τυπικός
        $this->SetTitle('Sample Labels - Order #'.(int)$this->order->id);
    }

    protected function type()
    {
        return 'sample_labels';
    }

    protected function file_path()
    {
        // Template view
        return APP_MODULES_PATH.'lims/views/themes/sample_labels.php';
    }

    protected function file_name()
    {
        return 'Sample-Labels-Order-'.$this->order->id.'.pdf';
    }

    public function prepare()
    {
        // View variables
        $this->set_view_vars([
            'order'   => $this->order,
            'samples' => $this->samples,
        ]);

        return $this->build();
    }
}
