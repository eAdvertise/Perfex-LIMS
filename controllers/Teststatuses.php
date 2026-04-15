<?php defined('BASEPATH') or exit('No direct script access allowed');

class Teststatuses extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        // Μόνο LIMS admins
        if (!has_permission('lims', '', 'admin')) {
            access_denied('Lims');
        }

        $this->load->model('lims/teststatuses_model', 'teststatuses_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') {
            $this->lang->load('lims', 'english');
        }
    }

    public function index()
    {
        $data['title'] = _l('lims_test_statuses');
        $data['rows']  = $this->teststatuses_model->all();

        $this->load->view('lims/admin/test_statuses/index', $data);
    }

    public function create($id = null)
    {
        if ($this->input->post()) {
            $post = $this->input->post();

            try {
                $this->teststatuses_model->save($post, $id);
                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/teststatuses'));
            } catch (Exception $e) {
                log_activity('LIMS Test Status save error: ' . $e->getMessage());
                set_alert('danger', _l('lims_error_generic'));
            }
        }

        $data['title'] = _l('lims_test_statuses') . ($id ? ' #' . (int) $id : '');
        $data['row']   = $id ? $this->teststatuses_model->get($id) : null;

        $this->load->view('lims/admin/test_statuses/create', $data);
    }

    public function delete($id)
    {
        $ok = $this->teststatuses_model->delete((int) $id);
        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));

        redirect(admin_url('lims/teststatuses'));
    }

    public function toggle_active()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id     = (int) $this->input->post('id');
        $active = (int) $this->input->post('active') === 1;

        $ok = $this->teststatuses_model->set_active($id, $active);

        echo json_encode(['success' => (bool) $ok]);
        die;
    }

    public function set_default()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = (int) $this->input->post('id');
        $ok = $this->teststatuses_model->set_default($id);

        echo json_encode(['success' => (bool) $ok]);
        die;
    }

    public function move()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id  = (int) $this->input->post('id');
        $dir = $this->input->post('dir'); // 'up' | 'down'

        $ok = $this->teststatuses_model->move($id, $dir);

        echo json_encode(['success' => (bool) $ok]);
        die;
    }
}
