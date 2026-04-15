<?php defined('BASEPATH') or exit('No direct script access allowed');

class Samples extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/samples_model','samples_model');
        $this->load->model('lims/orders_model','orders_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

	public function index()
	{
        if (!has_permission('lims','','manage_samples') && !has_permission('lims','','admin')) {
            access_denied('Lims');
        }

        $order_id = (int)($this->input->get('order_id') ?: 0);
		
		$this->load->model('lims/samples_model','samples_model');
		$data['title'] = _l('lims_samples');
		$data['order_id']  = $order_id;
		$data['rows']  = $this->samples_model->all();
		$this->load->view('lims/admin/samples/index', $data);
	}


    public function create($id = null)
	{
		if (!has_permission('lims','','manage_samples') && !has_permission('lims','','admin')) {
			access_denied('Lims');
		}

		$id = $id ? (int)$id : null;

		/* =========================
		   ========== POST ==========
		   ========================= */
		if ($this->input->post()) {

			$post           = $this->input->post();
			$link_mode      = $post['link_mode'] ?? 'order'; // 'order' | 'appointment'
			$order_id       = (int)($post['order_id'] ?? 0);
			$appointment_id = (int)($post['appointment_id'] ?? 0);
			$make_order     = ((int)($post['create_order'] ?? 0) === 1);

			// -- Δημιουργία Order αν ζητήθηκε --
			if ($link_mode === 'appointment') {
				// Από appointment
				if ($appointment_id && $make_order) {
					$appt = $this->db->where('id', $appointment_id)->get(db_prefix().'lims_appointments')->row();
					if ($appt) {
						// Φτιάξε order χρησιμοποιώντας τον client από το appointment
						$draft = [
							'client_id' => (int)$appt->client_id,
							'status'    => 'submitted',
							'priority'  => (int)($post['order_priority'] ?? 0),
							'due_at'    => !empty($post['order_due_at']) ? $post['order_due_at'] : null,
							'notes'     => trim($post['order_notes'] ?? ''),
						];
						$order_id = $this->orders_model->create_order($draft);
						if ($order_id) {
							// Σύνδεσε το appointment με το order
							$this->db->where('id', $appointment_id)->update(db_prefix().'lims_appointments', ['order_id' => $order_id]);
						}
					}
				} elseif ($appointment_id && !$order_id) {
					// Δεν θα δημιουργήσουμε νέο – προσπάθησε να πάρεις order από το appointment
					$appt = $this->db->where('id', $appointment_id)->get(db_prefix().'lims_appointments')->row();
					if ($appt && !empty($appt->order_id)) {
						$order_id = (int)$appt->order_id;
					}
				}
			} else {
				// link_mode === 'order'
				if ($make_order && !$order_id) {
					// Φτιάξε νέο order από στοιχεία φόρμας
					$client_id = (int)($post['order_client_id'] ?? 0);
					if (!$client_id) {
						set_alert('warning', _l('client').' '._l('is_required'));
						return redirect(admin_url('lims/samples/create'));
					}
					$draft = [
						'client_id' => $client_id,
						'status'    => 'submitted',
						'priority'  => (int)($post['order_priority'] ?? 0),
						'due_at'    => !empty($post['order_due_at']) ? $post['order_due_at'] : null,
						'notes'     => trim($post['order_notes'] ?? ''),
					];
					$order_id = $this->orders_model->create_order($draft);
				}
			}

			// Πρέπει πλέον να έχουμε order_id
			if (!$order_id) {
				set_alert('danger', _l('problem_creating').' '._l('order'));
				return redirect(admin_url('lims/samples/create'));
			}

			// Εξασφάλισε κοινό barcode στο Order (και θα το κληρονομήσει το Sample)
			$this->orders_model->ensure_barcode($order_id);

			// Build payload για sample
			$payload = [
				'order_id'       => $order_id,
				'appointment_id' => ($link_mode === 'appointment' && $appointment_id) ? $appointment_id : null,
				'sample_uid'     => trim($post['sample_uid'] ?? ''),
				'sample_type_id' => !empty($post['sample_type_id']) ? (int)$post['sample_type_id'] : null,
				'collected_at'   => $post['collected_at'] ?? null,
				'received_at'    => $post['received_at'] ?? null,
				'status'         => $post['status'] ?? 'draft',
				'notes'          => trim($post['notes'] ?? ''),
			];

			if ($id) {
				$ok = $this->samples_model->update($id, $payload);
				if ($ok) set_alert('success', _l('updated_successfully', _l('lims_sample')));
			} else {
				$id = $this->samples_model->create($payload);
				if ($id) set_alert('success', _l('added_successfully', _l('lims_sample')));
			}

			// Redirect: προτιμάμε να επιστρέφουμε στην καρτέλα του Order
			return redirect(admin_url('lims/orders/view/'.$order_id.'#samples'));
		}

		/* =========================
		   ========== GET ===========
		   ========================= */
		$row         = $id ? $this->samples_model->get($id) : null;
		$preOrderId  = (int)($this->input->get('order_id') ?? 0);
		$preMode     = $this->input->get('link_mode'); // 'order' | 'appointment' | null

		// Ασφαλής προεπιλογή mode
		if ($preMode === 'order' || $preMode === 'appointment') {
			$mode = $preMode;
		} else {
			$mode = ($row && !empty($row->appointment_id)) ? 'appointment' : 'order';
		}

		// Λίστες για selects
		$data['orders'] = $this->db->select('id,order_barcode')
			->order_by('id','DESC')->limit(100)->get(db_prefix().'lims_orders')->result();

		$data['appointments'] = $this->db->select('a.id,a.appointment_at,c.company as client_name')
			->from(db_prefix().'lims_appointments as a')
			->join(db_prefix().'clients as c','c.userid=a.client_id','left')
			->order_by('a.appointment_at','DESC')->limit(200)->get()->result();

		$data['clients'] = $this->db->select('userid,company')
			->order_by('company','ASC')->get(db_prefix().'clients')->result();

		$data['types']    = $this->db->order_by('name','ASC')->get(db_prefix().'lims_sample_types')->result();
		$data['title']    = $id ? (_l('edit').' '._l('lims_sample').' #'.$id) : _l('lims_sample_add');
		$data['row']      = $row;
		$data['order_id'] = $preOrderId ?: ($row->order_id ?? 0);
		$data['mode']     = $mode;

		$this->load->view('lims/admin/samples/create', $data);
	}


    public function delete($id)
    {
        if (!has_permission('lims','','manage_samples') && !has_permission('lims','','admin')) {
            access_denied('Lims');
        }
        $id  = (int)$id;
        $row = $this->samples_model->get($id);
        if (!$row) {
            set_alert('warning', _l('no_results_found'));
            return redirect(admin_url('lims/samples'));
        }
        $this->samples_model->delete($id);
        set_alert('success', _l('deleted', _l('lims_sample')));
        $return_to = $this->input->get('return') ?: '';
        if ($return_to === 'order' && !empty($row->order_id)) {
            return redirect(admin_url('lims/orders/view/'.$row->order_id.'#samples'));
        }
        return redirect(admin_url('lims/samples'.($row->order_id?'?order_id='.(int)$row->order_id:'')));
    }

    public function generate_barcode($id)
    {
        if (!has_permission('lims','','manage_samples') && !has_permission('lims','','admin')) {
            access_denied('Lims');
        }
        $id  = (int)$id;
        $row = $this->samples_model->get($id);
        if (!$row) {
            set_alert('warning', _l('no_results_found'));
            return redirect(admin_url('lims/samples'));
        }
        $code = $this->samples_model->generate_barcode($id, $this->input->get('force') == '1');
        if ($code) {
            set_alert('success', _l('lims_barcode_generated'));
        } else {
            set_alert('danger', _l('lims_error_generic'));
        }
        $return_to = $this->input->get('return') ?: '';
        if ($return_to === 'order' && !empty($row->order_id)) {
            return redirect(admin_url('lims/orders/view/'.$row->order_id.'#samples'));
        }
        return redirect(admin_url('lims/samples'.($row->order_id?'?order_id='.(int)$row->order_id:'')));
    }

    public function change_status()
    {
        if (!has_permission('lims','','manage_samples') && !has_permission('lims','','admin')) {
            ajax_access_denied();
        }
        $id     = (int)$this->input->post('id');
        $status = trim($this->input->post('status'));
        $ok = $this->samples_model->change_status($id, $status);
        echo json_encode(['success'=>(bool)$ok]);
        die;
    }
}
