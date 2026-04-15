<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sampletypes extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('lims/Sampletypes_model', 'sampletypes_model');

        // Language
        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        if (!has_permission('lims', '', 'admin')) {
            access_denied('Lims');
        }

        $data['title'] = _l('lims_sample_types');
        $data['rows']  = $this->sampletypes_model->all();

        $this->load->view('lims/admin/sampletypes/index', $data);
    }

    public function create($id = null)
    {
        if (!has_permission('lims', '', 'admin')) {
            access_denied('Lims');
        }

        if ($this->input->post()) {
            try {
                $sid = $this->sampletypes_model->save($this->input->post(), $id);
                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/sampletypes'));
            } catch (Exception $e) {
                log_activity('LIMS Sampletypes save error: '.$e->getMessage());
                set_alert('danger', _l('lims_error_generic'));
                // fall through to re-render form
            }
        }

        $data['title'] = _l('lims_sample_types').($id ? ' #'.$id : '');
        $data['row']   = $id ? $this->sampletypes_model->get($id) : null;

        $this->load->view('lims/admin/sampletypes/create', $data);
    }

    public function delete($id)
    {
        if (!has_permission('lims', '', 'admin')) {
            access_denied('Lims');
        }

        $ok = $this->sampletypes_model->delete($id);
        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));
        redirect(admin_url('lims/sampletypes'));
    }

    // AJAX: toggle active status
    public function toggle_status()
    {
        if (!has_permission('lims', '', 'admin')) {
            ajax_access_denied();
        }

        $id     = (int)$this->input->post('id');
        $active = (int)$this->input->post('active') === 1;

        $ok = $this->sampletypes_model->set_active($id, $active);
        echo json_encode(['success' => (bool)$ok]);
        die;
    }
}
