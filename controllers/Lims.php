<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lims extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // Models
        $this->load->model('lims/Lims_contracts_model', 'lims_contracts_model');
        $this->load->model('lims/Lims_orders_model',    'lims_orders_model');
        $this->load->model('lims/Lims_samples_model',   'lims_samples_model');
        $this->load->model('lims/Lims_tests_model',     'lims_tests_model');
        $this->load->model('lims/Lims_results_model',   'lims_results_model');
		$this->load->model('lims/tests_model');

        // Helpers
        $this->load->helper('lims/lims');
        $this->load->helper('lims/lims_pricing');

        // Language
        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        if (!has_permission('lims','','view')) access_denied('Lims');
        redirect(admin_url('lims/orders'));
    }

    /* ===================== ORDERS (stubs) ===================== */

    public function orders($action = null, $id = null)
    {
        if (!has_permission('lims','','view')) { access_denied('Lims'); }

        if ($action === 'create') {
            return $this->create_order();
        }

        $data['title']  = _l('lims_orders');
        $data['orders'] = $this->lims_orders_model->all();
        $this->load->view('lims/admin/orders/index', $data);
    }

    public function create_order()
    {
        if (!has_permission('lims','','manage_orders')) { access_denied('Lims'); }
        if ($this->input->post()) {
            $id = $this->lims_orders_model->add($this->input->post());
            if ($id) {
                set_alert('success', _l('lims_order_created'));
                return redirect(admin_url('lims/orders'));
            }
            set_alert('danger', _l('lims_error_generic'));
        }
        $data['title'] = _l('lims_new_order');
        $this->load->view('lims/admin/orders/create', $data);
    }

    public function create_invoice($order_id)
    {
        if (!has_permission('lims','','billing')) access_denied('Lims');
        $this->load->model('invoices_model');
        $order = $this->lims_orders_model->get($order_id);
        if (!$order) show_404();
        $tests = $this->lims_tests_model->get_by_order($order_id);

        $items = [];
        foreach ($tests as $t) {
            $rate = $t->unit_price;
            if ($rate === null || $rate === '') {
                $resolved = lims_resolve_price($order->client_id, (int)$t->item_id);
                $rate = $resolved['price'];
            }
            $items[] = [
                'description'       => $t->item_name ?? 'Lab Test',
                'long_description'  => 'Order #'.$order_id.' / Sample #'.$t->sample_id,
                'qty'               => 1,
                'rate'              => $rate,
                'itemid'            => $t->item_id,
            ];
        }

        $inv = [
            'clientid' => $order->client_id,
            'date'     => date('Y-m-d'),
            'duedate'  => date('Y-m-d', strtotime('+14 days')),
            'newitems' => $items,
        ];
        $invoice_id = $this->invoices_model->add($inv);
        if ($invoice_id) {
            $this->db->insert(db_prefix().'lims_billing_links',[
                'order_id'   => $order_id,
                'invoice_id' => $invoice_id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            set_alert('success', _l('lims_invoice_created'));
            return redirect(admin_url('invoices/list_invoices/'.$invoice_id));
        }
        set_alert('danger', _l('lims_invoice_failed'));
        redirect(admin_url('lims/orders'));
    }

    /* ===================== SETTINGS ===================== */

    public function settings()
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        if ($this->input->post()) {
            update_option('lims_barcode_prefix', $this->input->post('lims_barcode_prefix'));
            set_alert('success', _l('lims_saved'));
        }
        $data['title'] = _l('lims_settings').' (v'.(defined('LIMS_MODULE_VERSION')?LIMS_MODULE_VERSION:'0.0.1').')';
        $this->load->view('lims/admin/settings/index', $data);
    }

    /* ===================== CONTRACTS ===================== */

    // Dispatcher so that /admin/lims/contracts/create works even if routes.php isn't loaded
    public function contracts($action = null, $id = null)
	{
		if (!has_permission('lims','','billing')) { access_denied('Lims'); }

		if ($action === 'create') { return $this->create_contract($id); }
		if ($action === 'save')   { return $this->save_contract($id); } // <-- πρόσθεσε αυτή τη γραμμή

		$data['title']     = _l('lims_contracts');
		$data['contracts'] = $this->lims_contracts_model->all();
		$this->load->view('lims/admin/contracts/index', $data);
	}

    public function create_contract($id = null)
    {
        if (!has_permission('lims','','billing')) { access_denied('Lims'); }

        $data['title']    = _l('lims_contract_create').($id?' #'.$id:'');
        $data['contract'] = $id ? $this->lims_contracts_model->get($id) : null;

        // Clients (optional)
        $data['clients'] = $this->db
            ->select('userid, company')
            ->order_by('company','ASC')
            ->get(db_prefix().'clients')
            ->result();

        // Items
        $data['items'] = $this->db
            ->select('id, description, rate')
            ->order_by('description','ASC')
            ->get(db_prefix().'items')
            ->result();

        // Currencies + base
        $currQ = $this->db->order_by('name','ASC')->get(db_prefix().'currencies')->result();
        $data['currencies'] = $currQ;

        $base = function_exists('get_base_currency') ? get_base_currency() : null;
        $data['base_currency_id']   = (is_object($base) && isset($base->id)) ? (int)$base->id : null;
        $data['base_currency_name'] = (is_object($base) && isset($base->name)) ? $base->name : null;

        // JS maps
        $itemsMap = [];
        foreach ($data['items'] as $it) {
            $itemsMap[(int)$it->id] = ['rate' => (float)($it->rate ?? 0)];
        }
        $currMap = [];
        foreach ($currQ as $c) {
            $currMap[$c->name] = [
                'id'      => (int)$c->id,
                'rate'    => isset($c->rate) ? (float)$c->rate : 1.0, // fallback 1:1
                'is_base' => ($data['base_currency_id'] && (int)$c->id === (int)$data['base_currency_id']),
            ];
        }
        $data['items_map_json'] = json_encode($itemsMap);
        $data['curr_map_json']  = json_encode($currMap);

        // Existing prices grouped per item & currency for prefill
        $data['existing_prices'] = [];
        if (!empty($data['contract']->prices)) {
            foreach ($data['contract']->prices as $p) {
                $iid = (int)$p->item_id;
                $cur = (string)$p->currency;
                if (!isset($data['existing_prices'][$iid])) $data['existing_prices'][$iid] = [];
                $data['existing_prices'][$iid][$cur] = (float)$p->fixed_price;
            }
        }

        // Rows hard limit to items count (UI hint)
        $data['max_rows'] = count($data['items']);
		// … μέσα στη create_contract, ΠΡΙΝ το $this->load->view(…)
		$prefill_client = (int)$this->input->get('client_id');
		if ($prefill_client > 0 && empty($data['contract'])) {
			$data['contract'] = (object)['client_id' => $prefill_client];
		}

        $this->load->view('lims/admin/contracts/create', $data);
    }

    public function save_contract($id = null)
    {
        if (!has_permission('lims','','billing')) { access_denied('Lims'); }

        if (!$this->input->post()) { show_404(); }

        // Server-side limit: prevent more rows than available items
        $items_count = (int)$this->db->count_all_results(db_prefix().'items');
        if ($items_count > 0) {
            $rows_count = 0;
            $item_ids = (array)$this->input->post('item_id');
            if ($item_ids) {
                foreach ($item_ids as $iid) {
                    if ((int)$iid > 0) { $rows_count++; }
                }
            }
            if ($rows_count > $items_count) {
                set_alert('warning', _l('warning') . ': ' . 'Έχεις περισσότερες σειρές από τα διαθέσιμα items.');
                return redirect(admin_url('lims/contracts/create'.($id?'/'.$id:'')));
            }
        }

        try {
            $saved_id = $this->lims_contracts_model->save($this->input->post(), $id);
            set_alert('success', _l('lims_saved'));
             // NEW: επιστροφή στην καρτέλα πελάτη (αν ζητήθηκε)
			$return_to = $this->input->post('return_to');
			$client_id = (int)($this->input->post('client_id') ?? 0);
			if ($return_to === 'client_tab' && $client_id > 0) {
				return redirect(admin_url('clients/client/'.$client_id.'?group=lims-contracts'));
			}
			 return redirect(admin_url('lims/contracts'));
        } catch (Exception $e) {
            log_activity('LIMS Contracts save error: '.$e->getMessage());
            set_alert('danger', _l('lims_error_generic'));
            $return_to = $this->input->post('return_to');
			$client_id = (int)($this->input->post('client_id') ?? 0);
			if ($return_to === 'client_tab' && $client_id > 0) {
				return redirect(admin_url('clients/client/'.$client_id.'?group=lims-contracts'));
			}

			return redirect(admin_url('lims/contracts/create'.($id?'/'.$id:'')));
        }
    }

    public function delete_contract($id)
    {
        if (!has_permission('lims','','billing')) { access_denied('Lims'); }
        $ok = $this->lims_contracts_model->delete($id);
        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));
        $return_to = $this->input->get('return_to');
		$client_id = (int)$this->input->get('client_id');
		if ($return_to === 'client_tab' && $client_id > 0) {
			return redirect(admin_url('clients/client/'.$client_id.'?group=lims-contracts'));
		}

		redirect(admin_url('lims/contracts'));
    }

	public function toggle_contract_status()
	{
		if (!has_permission('lims','','billing')) { ajax_access_denied(); }

		$id     = (int)$this->input->post('id');
		$active = (int)$this->input->post('active') === 1;

		$ok = $this->lims_contracts_model->set_active($id, $active);

		echo json_encode(['success' => (bool)$ok]);
		die;
	}
	/**
     * Tests index (queue)
     * URL: /admin/lims/tests
     */
    public function tests()
    {
        if (!has_permission('lims', '', 'view')) {
            access_denied('lims');
        }

        $data['title']          = _l('lims_tests');
        $data['departments']    = $this->tests_model->get_departments();
        $data['sample_types']   = $this->tests_model->get_sample_types();
        $data['technicians']    = $this->tests_model->get_technicians();
        $data['statuses']       = $this->tests_model->get_statuses();

        $this->load->view('lims/admin/tests/manage', $data);
    }

    /**
     * Datatable source
     * URL: /admin/lims/tests_table
     */
    public function tests_table()
    {
        if (!has_permission('lims', '', 'view')) {
            ajax_access_denied();
        }

        $filters = [
            'status'       => $this->input->post('status'),
            'department'   => $this->input->post('department'),
            'sample_type'  => $this->input->post('sample_type'),
            'assigned_to'  => $this->input->post('assigned_to'),
            'date_from'    => $this->input->post('date_from'),
            'date_to'      => $this->input->post('date_to'),
        ];

        echo $this->tests_model->get_tests_table($filters);
    }

    /**
     * Test view
     * URL: /admin/lims/test/{id}
     */
    public function test($id)
    {
        if (!has_permission('lims', '', 'view')) {
            access_denied('lims');
        }

        $test = $this->tests_model->get($id);

        if (!$test) {
            blank_page(_l('lims_test_not_found'));
        }

        $data['test']        = $test;
        $data['order']       = $this->tests_model->get_order_for_test($id);
        $data['sample']      = $this->tests_model->get_sample_for_test($id);
        $data['audit']       = $this->tests_model->get_audit_trail($id);
        $data['attachments'] = $this->tests_model->get_attachments($id);

        $data['title']       = _l('lims_test') . ' #' . $id;

        $this->load->view('lims/admin/tests/test', $data);
    }
	public function ajax_quick_create_contruct()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		if (!has_permission('lims', '', 'admin') && !has_permission('lims', '', 'contracts')) {
			ajax_access_denied();
		}

		$data = $this->input->post(null, true);

		$id = $this->lims_contracts_model->add_quick($data);

		$resp = ['success' => false];
		if ($id) {
			$row = $this->lims_contracts_model->get($id);
			$label = $row->name ?: 'Contract #'.$id;
			$resp['success'] = true;
			$resp['id']      = (int)$id;
			$resp['name']    = $label . ' (#'.$id.')';
		}

		echo json_encode($resp);
		die;
	}

}
