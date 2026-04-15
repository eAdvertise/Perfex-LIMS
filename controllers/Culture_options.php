<?php defined('BASEPATH') or exit('No direct script access allowed');

class Culture_options extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_staff_logged_in()) {
            redirect(admin_url('authentication'));
        }

        $this->load->model('lims/culture_options_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims','english'); }
    }

    public function index()
    {
        if (!has_permission('lims','','admin')) {
            access_denied('LIMS');
        }

        $data['title'] = _l('lims_culture_options');
        $data['rows']  = $this->culture_options_model->all_sets(false);

        $this->load->view('lims/admin/culture_options/index', $data);
    }

    public function create($id = null)
    {
        if (!has_permission('lims','','admin')) {
            access_denied('LIMS');
        }

        if ($this->input->post()) {
            $post   = $this->input->post();
            $values = $post['values'] ?? [];
            unset($post['values']);

            $set_id = $this->culture_options_model->save_set($post, $values, $id);

            set_alert('success', _l('lims_saved'));
            redirect(admin_url('lims/culture_options'));
        }

        $data['row']    = $id ? $this->culture_options_model->get_set($id) : null;
        $data['values'] = $id ? $this->culture_options_model->get_values($id) : [];
        $data['title']  = _l('lims_culture_options') . ($id ? ' #'.$id : '');

        $this->load->view('lims/admin/culture_options/create', $data);
    }

    public function delete($id)
    {
        if (!has_permission('lims','','admin')) {
            access_denied('LIMS');
        }
        $ok = $this->culture_options_model->delete_set($id);
        set_alert($ok ? 'success':'danger', $ok?_l('lims_deleted'):_l('lims_error_generic'));
        redirect(admin_url('lims/culture_options'));
    }
}
