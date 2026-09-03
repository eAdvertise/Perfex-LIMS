<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subjects extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/subjects_model');
        $this->load->model('clients_model');
        $this->load->model('staff_model');
        $this->load->model('misc_model');

        // ΠΟΛΥ ΣΗΜΑΝΤΙΚΟ: helper για handle_file_upload()
        $this->load->helper('upload');
    }

    public function index()
    {
        if (!has_permission('lims', '', 'view')) {
            access_denied('lims');
        }

        $data['title'] = _l('lims_subjects');

        $this->load->view('lims/admin/subjects/manage', $data);
    }

    /**
     * Create / Edit form
     * URL:
     *  - /admin/lims/subjects/create
     *  - /admin/lims/subjects/create/{id}
     */
    public function create($id = null)
	{
		
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$id      = $id ? (int)$id : null;
		$subject = $id ? $this->subjects_model->get($id) : null;
		$subject_code = $this->subjects_model->generate_internal_code();
		
		if ($id && !$subject) {
			show_404();
		}
		if ($subject) {
			// edit → δείξε αυτό που ήδη έχει η εγγραφή
			$subject_code = $subject->internal_code ?? $subject->code ?? '';
		} else {
			// create → δείξε το επόμενο διαθέσιμο
			$subject_code = $this->subjects_model->generate_internal_code();
		}
			if ($this->input->post()) {

				$post = $this->input->post();

				// ----- Customer mode -----
				$mode = isset($post['customer_mode']) ? $post['customer_mode'] : 'existing';

				if ($mode === 'new') {
					// Δημιούργησε νέο customer + primary contact (χωρίς να καλέσουμε clients_model->add για contact)
					$new = [
						'company'             => $post['new_customer_company'] ?? '',
					'vat'                 => $post['new_customer_vat'] ?? '',
					'phonenumber'         => $post['new_customer_phone'] ?? '',
					'website'             => $post['new_customer_website'] ?? '',
					'country'             => $post['new_customer_country'] ?? '',
					'city'                => $post['new_customer_city'] ?? '',
					'address'             => $post['new_customer_address'] ?? '',
					'zip'                 => $post['new_customer_zip'] ?? '',
					'state'               => $post['new_customer_state'] ?? '',
					'billing_street'      => $post['new_customer_address'] ?? '',
					'billing_city'        => $post['new_customer_city'] ?? '',
					'billing_state'       => $post['new_customer_state'] ?? '',
					'billing_zip'         => $post['new_customer_zip'] ?? '',
					'billing_country'     => $post['new_customer_country'] ?? '',
					'shipping_street'     => $post['new_customer_address'] ?? '',
					'shipping_city'       => $post['new_customer_city'] ?? '',
					'shipping_state'      => $post['new_customer_state'] ?? '',
					'shipping_zip'        => $post['new_customer_zip'] ?? '',
					'shipping_country'    => $post['new_customer_country'] ?? '',
				];

					$this->db->trans_begin();
					$customer_id = $this->clients_model->add($new);

					if ($customer_id) {
						$contactFirstname = trim((string)($post['new_customer_firstname'] ?? ''));
						$contactLastname  = trim((string)($post['new_customer_lastname'] ?? ''));
						$contactEmail     = trim((string)($post['new_customer_email'] ?? ''));
						$contactPhone     = trim((string)($post['new_customer_phone'] ?? ''));

						if ($contactFirstname === '') {
							$contactFirstname = trim((string)($new['company'] ?? '')) ?: 'Primary';
						}

						$contactInsert = [
							'userid'      => (int)$customer_id,
							'is_primary'  => 1,
							'firstname'   => $contactFirstname,
							'lastname'    => $contactLastname,
							'email'       => $contactEmail !== '' ? $contactEmail : null,
							'phonenumber' => $contactPhone !== '' ? $contactPhone : null,
							'title'       => '',
							'datecreated' => date('Y-m-d H:i:s'),
							'direction'   => 0,
							'active'      => 1,
						];

						$this->db->insert(db_prefix() . 'contacts', $contactInsert);
						$contact_id = (int)$this->db->insert_id();

						if ($this->db->trans_status() === false || !$contact_id) {
							$this->db->trans_rollback();
							set_alert('danger', _l('problem_adding') ?: 'Could not create customer contact.');
							redirect(admin_url('lims/subjects/create'));
						}

						$this->db->trans_commit();
						$post['client_id']          = (int)$customer_id;
						$post['primary_contact_id'] = (int)$contact_id;
					} else {
						$this->db->trans_rollback();
						set_alert('danger', _l('problem_adding') ?: 'Could not create customer.');
						redirect(admin_url('lims/subjects/create'));
					}
			} else {
				// existing customer
				$post['primary_contact_id'] = null;
				$post['client_id'] = !empty($post['client_id']) ? (int)$post['client_id'] : null;
				if (!$post['client_id']) {
					$post['client_id'] = null;
				}
			}

			// active checkbox (αν δεν σταλεί)
			if (!isset($post['active'])) {
				$post['active'] = 0;
			}

			// καθάρισε τα new_customer_* να μην σε νοιάζουν στο model
			foreach ($post as $k => $v) {
				if (strpos($k, 'new_customer_') === 0 || $k === 'customer_mode') {
					unset($post[$k]);
				}
			}

			if ($id) {
				$success = $this->subjects_model->update($id, $post);
				if ($success) {
					set_alert('success', _l('updated_successfully'));
				}
				redirect(admin_url('lims/subjects/view/' . $id));
			} else {
				$newId = $this->subjects_model->create($post);
				if ($newId) {
					set_alert('success', _l('added_successfully'));
					redirect(admin_url('lims/subjects/view/' . $newId));
				} else {
					set_alert('danger', _l('problem_adding') ?: 'Could not create subject.');
					redirect(admin_url('lims/subjects/create'));
				}
			}
		}

		// clients list για dropdown
		$clients = $this->db
			->select('userid, company')
			->from(db_prefix().'clients')
			->order_by('company','ASC')
			->get()->result_array();

		$data['subject'] = $subject;
		if (!$subject && (int)$this->input->get('client_id') > 0) {
			$data['subject'] = (object)['client_id' => (int)$this->input->get('client_id'), 'active' => 1];
		}
		$data['internal_code'] = $subject_code;
		$data['clients'] = $clients;
		$data['countries'] = get_all_countries();

		// Active languages from Setup -> Settings -> Localization (Enabled Languages / Disable Languages)
		$data['languages'] = $this->subjects_model->get_active_languages_dropdown();

		$data['title']   = $id
			? ((_l('lims_subject_edit') ?: 'Edit Subject').' #'.$id)
			: (_l('lims_subject_new') ?: 'New Subject');

		$this->load->view('lims/admin/subjects/form', $data);
	}


	public function view($id)
	{
		if (!has_permission('lims', '', 'view')) {
			access_denied('lims');
		}

		$id      = (int)$id;
		$subject = $this->subjects_model->get($id);

		if (!$subject) {
			show_404();
		}

		$client  = $this->subjects_model->get_client($subject);
		$orders  = $this->subjects_model->get_orders($id);
		$samples = $this->subjects_model->get_samples($id);

		// group = ποιά επιλογή είναι ενεργή στο αριστερό menu
		$group = $this->input->get('group');
		if ($group === null || $group === '') {
			$group = 'profile';
		}

		$allowed_groups = [
			'profile',
			'orders',
			'samples',
			'invoices',
			'creditnotes',
			'receipts_payments',
			'waybills',
			'notes',
			'files',
			'reminders',
		];
		if (!in_array($group, $allowed_groups, true)) {
			$group = 'profile';
		}

		// Display name για τίτλο (όπως στο customer)
		$displayName = '';
		if ($subject->subject_type === 'patient') {
			$displayName = trim(($subject->first_name ?? '') . ' ' . ($subject->last_name ?? ''));
		}
		if ($displayName === '' && !empty($subject->subject_name)) {
			$displayName = $subject->subject_name;
		}
		if ($displayName === '') {
			$displayName = '#' . $id;
		}

		// Subject details (left card): support all subject types
		$subjectType = strtolower((string)($subject->subject_type ?? ''));
		if ($subjectType === '') {
			$subjectType = 'other';
		}

		$detailsTitleByType = [
			'patient'    => _l('lims_subject_type_patient') ?: 'Patient',
			'farm'       => _l('lims_subject_type_farm') ?: 'Farm',
			'restaurant' => _l('lims_subject_type_restaurant') ?: 'Restaurant',
			'lab'        => _l('lims_subject_type_lab') ?: 'Laboratory',
			'doctor'     => _l('lims_subject_type_doctor') ?: 'Doctor',
			'other'      => _l('lims_subject_type_other') ?: 'Other',
		];

		$detailsName = trim((string)($subject->subject_name ?? ''));
		if ($detailsName === '') {
			$detailsName = trim((string)($subject->first_name ?? '') . ' ' . (string)($subject->last_name ?? ''));
		}
		if ($detailsName === '') {
			$detailsName = '#' . $id;
		}

		$detailsRows = [
			['label' => _l('lims_subject_name') ?: _l('name') ?: 'Name', 'value' => $detailsName],
		];

		if (!empty($subject->internal_code)) {
			$detailsRows[] = ['label' => _l('lims_subject_internal_code') ?: 'Internal Code', 'value' => (string)$subject->internal_code];
		}
		if (!empty($subject->id_number)) {
			$detailsRows[] = ['label' => _l('lims_subject_id_number') ?: 'ID / Passport', 'value' => (string)$subject->id_number];
		}
		if (!empty($subject->phone)) {
			$detailsRows[] = ['label' => _l('client_phone') ?: _l('phone') ?: 'Phone', 'value' => (string)$subject->phone];
		}
		if (!empty($subject->email)) {
			$detailsRows[] = ['label' => _l('client_email') ?: _l('email') ?: 'Email', 'value' => (string)$subject->email];
		}
		if (!empty($subject->address)) {
			$detailsRows[] = ['label' => _l('client_address') ?: _l('address') ?: 'Address', 'value' => (string)$subject->address];
		}
		if (!empty($subject->city)) {
			$detailsRows[] = ['label' => _l('lims_subject_city') ?: _l('city') ?: 'City', 'value' => (string)$subject->city];
		}
		if (!empty($subject->zip)) {
			$detailsRows[] = ['label' => _l('lims_subject_zip') ?: _l('zip') ?: 'ZIP', 'value' => (string)$subject->zip];
		}

		$data['subject_details_card_title'] = ($detailsTitleByType[$subjectType] ?? ucfirst($subjectType)) . ' Details';
		$data['subject_details_rows']       = $detailsRows;
		$data['subject_type_normalized']    = $subjectType;

		// Keep existing UI behavior intact:
		// Many installations render Subject Details only for "patient" type in the profile view.
		// To keep the same UI and still show details for all subject types, provide a display copy
		// normalized as patient while preserving the real type in separate data key.
		$subjectForView = clone $subject;
		$realSubjectType = strtolower((string)($subjectForView->subject_type ?? ''));
		if ($realSubjectType !== 'patient') {
			$subjectForView->subject_type = 'patient';
			if (empty($subjectForView->first_name) && !empty($subjectForView->subject_name)) {
				$subjectForView->first_name = $subjectForView->subject_name;
			}
		}

		$data['subject']               = $subjectForView;
		$data['subject_real_type']     = $realSubjectType;
		$data['client']  = $client;
		$data['orders']  = $orders;
		$data['samples'] = $samples;
		$data['group']   = $group;
		$data['title']   = $displayName;

		$subjectId = $id;

		// =========================
		// Billing data για subject
		// =========================

		// NOTE:
		// Τα core Perfex tables (invoices/creditnotes/invoicepaymentrecords) ΔΕΝ έχουν by default column subject_id.
		// Σε παλιές εγκαταστάσεις μπορεί να υπάρχει custom column. Σε fresh installs κάνουμε safe-guard.

		// Invoices
		$data['billing_invoices'] = [];
		$tblInvoices = db_prefix() . 'invoices';
		if ($this->db->table_exists($tblInvoices) && $this->db->field_exists('subject_id', $tblInvoices)) {
			$data['billing_invoices'] = $this->db
				->where('subject_id', $id)
				->order_by('date', 'DESC')
				->get($tblInvoices)
				->result();
		}

		// Credit Notes
		$data['billing_creditnotes'] = [];
		$tblCredit = db_prefix() . 'creditnotes';
		if ($this->db->table_exists($tblCredit) && $this->db->field_exists('subject_id', $tblCredit)) {
			$data['billing_creditnotes'] = $this->db
				->where('subject_id', $id)
				->order_by('date', 'DESC')
				->get($tblCredit)
				->result();
		}

		// Delivery Notes
		$data['billing_delivery_notes'] = [];
		$tblDelivery = db_prefix() . 'delivery_notes';
		if ($this->db->table_exists($tblDelivery) && $this->db->field_exists('subject_id', $tblDelivery)) {
			$data['billing_delivery_notes'] = $this->db
				->where('subject_id', $id)
				->order_by('date', 'DESC')
				->get($tblDelivery)
				->result();
		}

		// Receipts
		$data['billing_receipts'] = [];
		$tblReceipts = db_prefix() . 'receipts';
		if ($this->db->table_exists($tblReceipts) && $this->db->field_exists('subject_id', $tblReceipts)) {
			$data['billing_receipts'] = $this->db
				->where('subject_id', $id)
				->order_by('payment_date', 'DESC')
				->get($tblReceipts)
				->result();
		}

		// Payments (invoicepaymentrecords) – μόνο αν δεν έχουμε receipts
		$data['billing_payments'] = [];
		$tblPayments = db_prefix() . 'invoicepaymentrecords';
		if (empty($data['billing_receipts']) && $this->db->table_exists($tblPayments) && $this->db->field_exists('subject_id', $tblPayments)) {
			$data['billing_payments'] = $this->db
				->where('subject_id', $id)
				->order_by('date', 'DESC')
				->get($tblPayments)
				->result();
		}
		// ==================================
		// Notes / Files / Reminders για Subject
		// ==================================
		$this->load->model('misc_model');

		// NOTES (τύπος lims_subject)
		if ($group === 'notes') {
			$data['user_notes'] = $this->db
				->select('n.*, s.firstname, s.lastname')
				->from(db_prefix() . 'notes AS n')
				->join(db_prefix() . 'staff AS s', 's.staffid = n.addedfrom', 'left')
				->where('n.rel_id', $id)
				->where('n.rel_type', 'lims_subject')
				->order_by('n.dateadded', 'DESC')
				->get()
				->result_array();
		}
		// REMINDERS (τύπος lims_subject)
		if ($group === 'reminders') {

			// Reminders για αυτό το Subject
			$reminders = $this->db
				->select(db_prefix() . 'reminders.*, ' .
						 db_prefix() . 'staff.firstname, ' .
						 db_prefix() . 'staff.lastname')
				->from(db_prefix() . 'reminders')
				->join(
					db_prefix() . 'staff',
					db_prefix() . 'staff.staffid = ' . db_prefix() . 'reminders.staff',
					'left'
				)
				->where(db_prefix() . 'reminders.rel_type', 'lims_subject')
				->where(db_prefix() . 'reminders.rel_id', $id)
				->order_by(db_prefix() . 'reminders.date', 'DESC')
				->get()
				->result_array();

			$data['reminders'] = $reminders;

		}
		// Attachments (Files panel)
		
		if ($group === 'files') {
			$data['attachments'] = $this->db
				->select('f.*, s.firstname, s.lastname')
				->from(db_prefix().'files AS f')
				->join(db_prefix().'staff AS s', 's.staffid = f.staffid', 'left')
				->where('f.rel_type', 'lims_subject')
				->where('f.rel_id', $subjectId)
				->order_by('f.dateadded', 'DESC')
				->get()->result();
		}



		$this->load->view('lims/admin/subjects/profile', $data);
	}




	public function ajax_customer_details($client_id)
	{
		if (!is_staff_logged_in()) {
			show_404();
		}

		$client_id = (int)$client_id;
		if ($client_id <= 0) {
			echo json_encode(['success' => false]);
			die;
		}

		$p = db_prefix();

		// Βασικά στοιχεία πελάτη
		$client = $this->db
			->where('userid', $client_id)
			->get($p . 'clients')
			->row();

		if (!$client) {
			echo json_encode(['success' => false]);
			die;
		}

		// Primary contact (αν υπάρχει)
		$primary = $this->db
			->where([
				'userid'    => $client_id,
				'is_primary'=> 1,
			])
			->get($p . 'contacts')
			->row();

		echo json_encode([
			'success'   => true,
			'company'   => $client->company,
			'phone'     => $client->phonenumber,
			'address'   => $client->address,
			'city'      => $client->city,
			'zip'       => $client->zip,
			'country'   => (int)$client->country,
			'email'     => $primary ? $primary->email      : '',
			'firstname' => $primary ? $primary->firstname  : '',
			'lastname'  => $primary ? $primary->lastname   : '',
		]);
		die;
	}
	public function table()
	{
		if (!has_permission('lims', '', 'view')) {
			ajax_access_denied();
		}

		$this->app->get_table_data(module_views_path('lims', 'admin/subjects/table'));
	}

	public function add_note($id)
	{
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$id = (int)$id;
		$description = trim((string)$this->input->post('description', true));
		if ($id > 0 && $description !== '' && $this->subjects_model->get($id)) {
			$this->db->insert(db_prefix() . 'notes', [
				'rel_id' => $id,
				'rel_type' => 'lims_subject',
				'description' => $description,
				'addedfrom' => get_staff_user_id(),
				'dateadded' => date('Y-m-d H:i:s'),
			]);
			set_alert($this->db->affected_rows() > 0 ? 'success' : 'danger', $this->db->affected_rows() > 0 ? _l('added_successfully') : _l('problem_adding'));
		}

		redirect(admin_url('lims/subjects/view/' . $id . '?group=notes'));
	}

	public function delete_note($subject_id, $note_id)
	{
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$this->db->where('id', (int)$note_id)
			->where('rel_id', (int)$subject_id)
			->where('rel_type', 'lims_subject')
			->delete(db_prefix() . 'notes');

		redirect(admin_url('lims/subjects/view/' . (int)$subject_id . '?group=notes'));
	}

	public function upload_attachment($id)
	{
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$id = (int)$id;
		if ($id <= 0 || !$this->subjects_model->get($id)) {
			show_404();
		}

		if (isset($_FILES['file']['name']) && $_FILES['file']['name'] !== '') {
			if (!is_array($_FILES['file']['name'])) {
				foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $key) {
					$_FILES['file'][$key] = [$_FILES['file'][$key]];
				}
			}

			_file_attachments_index_fix('file');
			$path = FCPATH . 'uploads/lims_subjects/' . $id . '/';
			_maybe_create_upload_path($path);

			foreach ($_FILES['file']['name'] as $index => $originalName) {
				if (_perfex_upload_error($_FILES['file']['error'][$index]) || !_upload_extension_allowed($originalName)) {
					continue;
				}

				$filename = unique_filename($path, $originalName);
				if (!move_uploaded_file($_FILES['file']['tmp_name'][$index], $path . $filename)) {
					continue;
				}
				if (is_image($path . $filename)) {
					create_img_thumb($path, $filename);
				}
				$this->misc_model->add_attachment_to_database($id, 'lims_subject', [[
					'file_name' => $filename,
					'filetype' => $_FILES['file']['type'][$index],
				]]);
			}
		}

		redirect(admin_url('lims/subjects/view/' . $id . '?group=files'));
	}

	public function add_external_attachment()
	{
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$subject_id = (int)$this->input->post('subject_id');
		$files = $this->input->post('files');
		$external = (int)$this->input->post('external');
		if ($subject_id > 0 && $files && $this->subjects_model->get($subject_id)) {
			$this->misc_model->add_attachment_to_database($subject_id, 'lims_subject', $files, $external);
		}

		redirect(admin_url('lims/subjects/view/' . $subject_id . '?group=files'));
	}

	public function delete_attachment($subject_id, $attachment_id)
	{
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$subject_id = (int)$subject_id;
		$attachment_id = (int)$attachment_id;
		$attachment = $this->db
			->where('id', $attachment_id)
			->where('rel_type', 'lims_subject')
			->where('rel_id', $subject_id)
			->get(db_prefix() . 'files')
			->row();

		if ($attachment) {
			if (empty($attachment->external)) {
				$path = FCPATH . 'uploads/lims_subjects/' . $subject_id . '/' . basename($attachment->file_name);
				if (is_file($path)) {
					unlink($path);
				}
				$thumb = dirname($path) . '/thumb_' . basename($attachment->file_name);
				if (is_file($thumb)) {
					unlink($thumb);
				}
			}
			$this->db->where('id', $attachment_id)->delete(db_prefix() . 'files');
		}

		redirect(admin_url('lims/subjects/view/' . $subject_id . '?group=files'));
	}

	public function ajax_quick_create()
	{
		if (!is_staff_logged_in()) {
			show_404();
		}

		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		
		$data = $this->input->post(null, true);

		// εδώ ήταν το add_quick()
		$id = $this->subjects_model->add_quick($data);

		if ($id) {
			// Φτιάχνουμε name για το dropdown
			$display = '';

			if (!empty($data['last_name']) || !empty($data['first_name'])) {
				$display = trim(($data['last_name'] ?? '') . ' ' . ($data['first_name'] ?? ''));
			}

			if ($display === '' && !empty($data['subject_name'])) {
				$display = $data['subject_name'];
			}

			if ($display === '') {
				$display = 'Subject #' . $id;
			}

			echo json_encode([
				'success' => true,
				'id'      => $id,
				'name'    => $display,
			]);
		} else {
			echo json_encode(['success' => false]);
		}
		die;
	}

	public function delete($id)
	{
		if (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin')) {
			access_denied('lims');
		}

		$id = (int)$id;
		if ($id <= 0) {
			return $this->redirect_after_subject_delete();
		}

		$subject = $this->subjects_model->get($id);
		if (!$subject) {
			set_alert('warning', _l('not_found'));
			return $this->redirect_after_subject_delete();
		}
		if ($this->subjects_model->is_marked_deleted($id)) {
			set_alert('warning', 'Subject is already archived.');
			return $this->redirect_after_subject_delete();
		}

		$mode = (string)$this->input->get('mode');
		if ($mode === '') {
			$mode = (string)$this->input->post('mode');
		}

		$targetSubjectId = (int)$this->input->post('target_subject_id');
		if ($targetSubjectId <= 0) {
			$targetSubjectId = (int)$this->input->get('target_subject_id');
		}

		$counts = $this->subjects_model->get_linked_counts($id);
		$hasLinks = array_sum(array_map('intval', $counts)) > 0;

		if (!$hasLinks) {
			$ok = $this->subjects_model->delete_subject_only($id);
			set_alert($ok ? 'success' : 'danger', $ok ? (_l('deleted', _l('lims_subject')) ?: 'Subject deleted.') : (_l('problem_deleting', _l('lims_subject')) ?: 'Could not delete subject.'));
			return $this->redirect_after_subject_delete();
		}

		if ($mode === 'delete_all') {
			$ok = $this->subjects_model->delete_with_links($id);
			set_alert($ok ? 'success' : 'danger', $ok ? (_l('deleted', _l('lims_subject')) ?: 'Subject and linked records deleted.') : (_l('problem_deleting', _l('lims_subject')) ?: 'Could not delete subject.'));
			return $this->redirect_after_subject_delete();
		}

		if ($mode === 'transfer') {
			if ($targetSubjectId <= 0 || $targetSubjectId === $id || !$this->subjects_model->get($targetSubjectId)) {
				set_alert('warning', _l('lims_error_generic') ?: 'Please select a valid target subject.');
				return $this->redirect_after_subject_delete();
			}
			if ($this->subjects_model->is_marked_deleted($targetSubjectId)) {
				set_alert('warning', 'Target subject is archived. Please select an active subject.');
				return $this->redirect_after_subject_delete();
			}

			$moved = $this->subjects_model->transfer_links($id, $targetSubjectId);
			if (!$moved) {
				set_alert('danger', _l('lims_error_generic') ?: 'Could not transfer linked records.');
				return $this->redirect_after_subject_delete();
			}

			$ok = $this->subjects_model->delete_subject_only($id);
			set_alert($ok ? 'success' : 'danger', $ok ? (_l('deleted', _l('lims_subject')) ?: 'Subject deleted after transfer.') : (_l('problem_deleting', _l('lims_subject')) ?: 'Subject transfer succeeded but delete failed.'));
			return $this->redirect_after_subject_delete();
		}

		if ($mode === 'archive') {
			$ok = $this->subjects_model->mark_as_deleted($id, get_staff_user_id());
			set_alert($ok ? 'success' : 'danger', $ok ? 'Subject archived (marked as deleted).' : 'Could not archive subject.');
			return $this->redirect_after_subject_delete();
		}

		$details = 'Orders: ' . (int)$counts['orders']
			. ', Contracts: ' . (int)$counts['contracts']
			. ', Appointments: ' . (int)$counts['appointments']
			. ', Tests: ' . (int)$counts['tests']
			. ', Samples: ' . (int)$counts['samples'];
		set_alert('warning', 'This subject has linked records. Choose "Delete all", "Transfer", or "Archive". ' . $details);

		return $this->redirect_after_subject_delete();
	}

	public function delete_dependencies($id)
	{
		if (!is_staff_logged_in() || (!has_permission('lims', '', 'manage_orders') && !has_permission('lims', '', 'admin'))) {
			echo json_encode(['success' => false, 'message' => 'Access denied.']);
			die;
		}

		$id = (int)$id;
		$subject = $this->subjects_model->get($id);

		if (!$subject) {
			echo json_encode(['success' => false, 'message' => 'Subject not found.']);
			die;
		}

		$counts = $this->subjects_model->get_linked_counts($id);

		echo json_encode([
			'success' => true,
			'counts'  => $counts,
			'has_any' => array_sum(array_map('intval', $counts)) > 0,
		]);
		die;
	}

	private function redirect_after_subject_delete()
	{
		$returnTo = (string)$this->input->get('return_to');
		$clientId = (int)$this->input->get('client_id');

		if ($returnTo === 'client_tab' && $clientId > 0) {
			return redirect(admin_url('clients/client/' . $clientId . '?group=lims-subjects'));
		}

		return redirect(admin_url('lims/subjects'));
	}


}
