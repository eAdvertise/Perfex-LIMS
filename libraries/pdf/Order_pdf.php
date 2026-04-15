<?php
// lims/libraries/pdf/Order_pdf.php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'libraries/pdf/App_pdf.php');

class Order_pdf extends App_pdf
{
    /** @var object */
    protected $order;

    /** @var object|null */
    protected $client = null;

    /** @var object|null */
    protected $currency = null;

    /**
     * Προσοχή: ΔΕΝ το λέμε "subject" για να μην συγκρούεται
     * με το internal $subject (document subject) του TCPDF.
     * 
     * @var object|null
     */
    protected $subject_row = null;

    /** @var string */
    protected $ref = '';

    public function __construct($order, $tag = '')
    {
        parent::__construct();

        if (!is_object($order)) {
            throw new \InvalidArgumentException('Order_pdf requires order object.');
        }

        $this->order = $order;
        $this->tag   = $tag;

        // Lazy-load models
        if (!class_exists('Clients_model', false)) {
            $this->ci->load->model('clients_model');
        }
        if (!class_exists('Currencies_model', false)) {
            $this->ci->load->model('currencies_model');
        }

        // Προσπάθησε να φορτώσεις Subjects model (αν υπάρχει)
        if (!class_exists('Lims_subjects_model', false)
            && file_exists(module_dir_path('lims', 'models/Lims_subjects_model.php'))) {
            $this->ci->load->model('lims/lims_subjects_model', 'lims_subjects_model');
        }

		$this->ci->load->helper('lims/lims'); 
        // Client
        $cid = (int)($this->order->client_id ?? 0);
        $this->client = $cid ? $this->ci->clients_model->get($cid) : null;

        // Subject (από order->subject_id αν υπάρχει)
        $sid = (int)($this->order->subject_id ?? 0);
        if ($sid > 0) {
            if (isset($this->ci->lims_subjects_model)) {
                $this->subject_row = $this->ci->lims_subjects_model->get($sid);
            } else {
                // fallback: direct query
                $this->subject_row = $this->ci->db
                    ->where('id', $sid)
                    ->get(db_prefix().'lims_subjects')
                    ->row();
            }
        }

        // Currency (OBJECT)
        if ($this->client && !empty($this->client->default_currency)) {
            $this->currency = $this->ci->currencies_model->get($this->client->default_currency);
        } else {
            $this->currency = $this->ci->currencies_model->get_base_currency();
        }

        // Human ref (π.χ. 000012)
        $num = (int)($this->order->id ?? 0);
        $this->ref = $num ? str_pad($num, 6, '0', STR_PAD_LEFT) : '';

        $this->SetTitle('Order #'.$this->ref);
        // Αν θέλεις μπορείς προαιρετικά:
        // $this->SetSubject('Order #'.$this->ref);
    }

    protected function type()
    {
        // χρησιμοποιείται από το app_pdf/theme system
        return 'order';
    }

    protected function file_path()
    {
        // view του module για το PDF
        // modules/lims/views/themes/orderpdf.php
        return APP_MODULES_PATH.'lims/views/themes/orderpdf.php';
    }

    protected function file_name()
    {
        // όνομα αρχείου για download/attach
        return 'Order-'.$this->ref.'.pdf';
    }

    // ΠΡΕΠΕΙ να είναι public (ίδιο access level με App_pdf::prepare)
    public function prepare()
    {
        // Δώσε στο view ό,τι χρειάζεται για να μη λείπει τίποτα
        $this->set_view_vars([
            'order'            => $this->order,
            'lines'            => isset($this->order->lines) ? $this->order->lines : [],
            'client'           => $this->client,
            'currency'         => $this->currency,
            'ref'              => $this->ref,
            // subject αντικείμενο περνάει ως "subject" στο view
            'subject'          => $this->subject_row,
            'primary'          => isset($this->order->primary) ? $this->order->primary : null,
            'contract'         => isset($this->order->contract) ? $this->order->contract : null,
            'panel_children'   => isset($this->order->panel_children) ? $this->order->panel_children : [],
            'analysis_details' => isset($this->order->analysis_details) ? $this->order->analysis_details : [],
            'culture_details'  => isset($this->order->culture_details) ? $this->order->culture_details : [],
            'samples'          => isset($this->order->samples) ? $this->order->samples : [],
            'appointment'      => isset($this->order->appointment) ? $this->order->appointment : null,
        ]);

        return $this->build(); // renderάρει το view και σου επιστρέφει TCPDF instance
    }
}
