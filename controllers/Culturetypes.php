<?php defined('BASEPATH') or exit('No direct script access allowed');

class Culturetypes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!has_permission('lims','','admin')) { access_denied('LIMS'); }

        $this->load->model('lims/culturetypes_model', 'culturetypes_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        $data['title'] = _l('lims_culture_types') ?: 'Culture Types';
        $data['rows']  = $this->culturetypes_model->all();
        $this->load->view('lims/admin/culturetypes/index', $data);
    }

    public function create($id = null)
    {
        if ($this->input->post()) {
            $post = $this->input->post();
            try {
                $savedId = $this->culturetypes_model->save($post, $id);
                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/culturetypes'));
            } catch (Exception $e) {
                log_activity('LIMS Culturetypes save error: ' . $e->getMessage());
                set_alert('danger', _l('lims_error_generic'));
            }
        }

        $data['title'] = _l('lims_culture_types') . ($id ? ' #'.$id : '');
        $data['row']   = $id ? $this->culturetypes_model->get($id) : null;
        $this->load->view('lims/admin/culturetypes/create', $data);
    }

    public function delete($id)
    {
        $ok = $this->culturetypes_model->delete((int)$id);
        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));
        redirect(admin_url('lims/culturetypes'));
    }

    public function toggle_status()
    {
        if (!has_permission('lims','','admin')) { ajax_access_denied(); }
        $id     = (int)$this->input->post('id');
        $active = (int)$this->input->post('active') === 1;
        $ok     = $this->culturetypes_model->set_active($id, $active);
        echo json_encode(['success'=>(bool)$ok]); die;
    }
}
