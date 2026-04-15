<?php defined('BASEPATH') or exit('No direct script access allowed');

class Partners extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/partners_model','partners_model');
        $this->load->helper('lims/lims');
        $this->load->model('clients_model'); // core
        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $data['title'] = _l('lims_partners');
        $data['rows']  = $this->partners_model->all_with_customer();
        $this->load->view('lims/admin/partners/index', $data);
    }

    public function create($id = null)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }

        if ($this->input->post()) {
            $post = $this->input->post();
            try {
                // Αν δεν έχει επιλεγεί existing customer, μπορεί να δημιουργηθεί νέος core client
                $customer_id = $post['customer_id'] ?? '';
                if ($customer_id === '' || $customer_id === null) {
                    $company = trim($post['new_customer_company'] ?? '');
                    if ($company !== '') {
                        // Δημιουργία βασικού πελάτη
                        $clientData = [
                            'company'     => $company,
                            'website'     => trim($post['website'] ?? ''),
                            'phonenumber' => trim($post['phone'] ?? ''),
                            'address'     => trim($post['address'] ?? ''),
                            'active'      => 1,
                        ];
                        $newId = $this->clients_model->add($clientData);
                        if ($newId) {
                            $customer_id = $newId;
                        }
                    }
                }

                // Καθαρό record για partners
                $rec = [
                    'name'        => trim($post['name'] ?? ''),
                    'customer_id' => $customer_id !== '' ? (int)$customer_id : null,
                    'email'       => trim($post['email'] ?? ''),
                    'phone'       => trim($post['phone'] ?? ''),
                    'website'     => trim($post['website'] ?? ''),
                    'address'     => trim($post['address'] ?? ''),
                    'notes'       => trim($post['notes'] ?? ''),
                    'api_key'     => trim($post['api_key'] ?? ''),
                    'api_base_url'=> trim($post['api_base_url'] ?? ''),
                    'api_secret'  => trim($post['api_secret'] ?? ''),
                    'sync_enabled'=> isset($post['sync_enabled']) ? 1 : 0,
                    'active'      => isset($post['active']) ? 1 : 0,
                ];
                if ($rec['name'] === '') { throw new Exception(_l('lims_error_name_required')); }

                
                // Auto-generate keys for new partner if not provided
                if (!$id) {
                    if ($rec['api_key'] === '') {
                        $rec['api_key'] = lims_random_token(40);
                    }
                    if ($rec['api_secret'] === '') {
                        $rec['api_secret'] = lims_random_token(64);
                    }
                }
$pid = $this->partners_model->save($rec, $id);
                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/partners'));

            } catch (Throwable $e) {
                // Log full error for troubleshooting
                log_activity('LIMS Partners save error: '.$e->getMessage());
                // Show message to admins; fallback to generic if empty
                $msg = trim((string)$e->getMessage());
                if ($msg === '') { $msg = _l('lims_error_generic'); }
                set_alert('danger', $msg);
            }
        }

        // GET
        $data['title']     = _l('lims_partner_form_title') . ($id ? ' #'.$id : '');
        $data['row']       = $id ? $this->partners_model->get($id) : null;
        $data['customers'] = $this->db->select('userid,company')->order_by('company','ASC')->get(db_prefix().'clients')->result();

        $this->load->view('lims/admin/partners/create', $data);
    }

    public function delete($id)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $ok = $this->partners_model->delete((int)$id);
        set_alert($ok?'success':'danger', $ok?_l('lims_deleted'):_l('lims_error_generic'));
        redirect(admin_url('lims/partners'));
    }

    public function toggle_status()
    {
        if (!has_permission('lims','','admin')) { ajax_access_denied(); }
        $id = (int)$this->input->post('id');
        $active = (int)$this->input->post('active') === 1;
        $ok = $this->partners_model->set_active($id, $active);
        echo json_encode(['success'=>(bool)$ok]); die;
    }
}
