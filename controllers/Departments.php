<?php defined('BASEPATH') or exit('No direct script access allowed');

class Departments extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/departments_model','departments_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        if (!has_permission('lims','','admin')) access_denied('Lims');
        $data['title'] = _l('lims_departments');
        $data['rows']  = $this->departments_model->all();
        $this->load->view('lims/admin/departments/index', $data);
    }

    public function create($id=null)
    {
        if (!has_permission('lims','','admin')) access_denied('Lims');

        if ($this->input->post()) {
            try {
                $did = $this->departments_model->save($this->input->post(), $id);
                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/departments'));
            } catch (Exception $e) {
                log_activity('LIMS Departments save error: '.$e->getMessage());
                set_alert('danger', _l('lims_error_generic'));
            }
        }

        $data['title'] = _l('lims_departments').($id ? ' #'.$id : '');
        $data['row']   = $id ? $this->departments_model->get($id) : null;
        $this->load->view('lims/admin/departments/create', $data);
    }

    public function delete($id)
    {
        if (!has_permission('lims','','admin')) access_denied('Lims');
        $ok = $this->departments_model->delete($id);
        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));
        redirect(admin_url('lims/departments'));
    }

    public function toggle_status()
    {
        if (!has_permission('lims','','admin')) ajax_access_denied();
        $id = (int)$this->input->post('id');
        $active = (int)$this->input->post('active') === 1;
        $ok = $this->departments_model->set_active($id,$active);
        echo json_encode(['success'=>(bool)$ok]); die;
    }
}
