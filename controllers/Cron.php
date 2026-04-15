<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/sync_model', 'sync_model');
    }

    /**
     * Run partner sync outbox.
     * URL: /lims/cron/sync_outbox?key=YOUR_CRON_KEY
     */
    public function sync_outbox()
    {
        $cronKey = '';
        if (function_exists('get_option')) {
            $cronKey = (string)get_option('cron_key');
        }

        $key = (string)$this->input->get('key');
        if ($cronKey !== '' && $key !== $cronKey) {
            $this->output->set_status_header(403);
            echo 'Forbidden';
            return;
        }

        $limit = (int)$this->input->get('limit');
        if ($limit <= 0) $limit = 10;

        $res = $this->sync_model->process_outbox($limit);

        $this->output->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
