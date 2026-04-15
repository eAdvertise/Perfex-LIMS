<?php defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends AdminController
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('lims/orders_model','orders_model');
		$this->load->model('lims/lims_contracts_model', 'contracts_model');
		$this->load->model('lims/analyses_model','analyses_model');
		$this->load->model('lims/panels_model','panels_model');
		$this->load->model('lims/subjects_model','subjects_model');

		// ➕ ΝΕΟ
		$this->load->model('lims/cultures_model','cultures_model');
		$this->load->model('lims/partners_model','partners_model');
		$this->load->model('lims/sync_model','sync_model');

		$this->load->model('invoice_items_model');
		$this->load->model('partners_model');
		$this->load->helper('lims/lims'); // => modules/lims/helpers/lims_helper.php
		$locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
		$this->lang->load('lims', $locale);
		if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
	}

    public function index()
    {
        $data['title'] = _l('lims_orders');
        $data['rows']  = $this->orders_model->all();
        $this->load->view('lims/admin/orders/index', $data);
    }

    public function create()
	{
		// STEP 1: αποθήκευση header/draft
		if ($this->input->post('action') === 'save_step1') {

			$subject_id  = (int)$this->input->post('subject_id');
			$contract_id = (int)$this->input->post('contract_id') ?: null;

			// ΝΕΟ: mode καταχώρησης
			$entry_mode = $this->input->post('entry_mode');
			if (!in_array($entry_mode, ['tests','samples'], true)) {
				$entry_mode = 'tests'; // default: παλιό mode
			}
			// Partner (optional): if selected, enforce samples mode
			$partner_id = (int)$this->input->post('partner_id');
			if ($partner_id > 0) {
				$entry_mode = 'samples';
			}
			log_activity('LIMS save_step1 draft partner_id=' . (int)($draft['partner_id'] ?? 0));


			// Προσπάθησε να βρεις client από το subject (αν υπάρχει)
			$client_id = null;
			if ($subject_id > 0) {
				$sub = $this->db->where('id', $subject_id)
								->get(db_prefix().'lims_subjects')
								->row();
				if ($sub && !empty($sub->client_id)) {
					$client_id = (int)$sub->client_id;
				}
			}

			$draft = [
				'subject_id'  => $subject_id ?: null,
				'client_id'   => $client_id, // μπορεί να είναι και null
				'contract_id' => $contract_id,
				'priority'    => (int)$this->input->post('priority') ?: 0,
				'due_at'      => trim($this->input->post('due_at')) ?: null,
				'notes'       => trim($this->input->post('notes')) ?: null,
				// ΝΕΟ
				'partner_id'  => $partner_id > 0 ? $partner_id : null,
				'entry_mode'  => $entry_mode,
			];
			
			$this->session->set_userdata('lims_order_draft', $draft);
			return redirect(admin_url('lims/orders/create?step=2'));
		}

		// STEP 2: submit (tests-mode ή samples-mode)
		if ($this->input->post('action') === 'save_submit') {
			$draft = $this->session->userdata('lims_order_draft') ?: [];

			// Απαιτούμε subject (όχι απαραίτητα client)
			if (empty($draft['subject_id'])) {
				set_alert('danger', _l('lims_subject').' '._l('is_required'));
				return redirect(admin_url('lims/orders/create'));
			}

			$entry_mode = isset($draft['entry_mode']) ? $draft['entry_mode'] : 'tests';

			// Safety: Partner orders must run in samples mode
			if (!empty($draft['partner_id'])) {
				$entry_mode = 'samples';
			}


			// ------------------------------------------------------------------
			// MODE A: tests / panels (παλιό mode – όπως το είχαμε)
			// ------------------------------------------------------------------
			if ($entry_mode === 'tests') {

				$order_id = $this->orders_model->create_order($draft);

			// Partner orders: build stable sample_uid prefix based on order_uid
			$orderUidShort = null;
			if (!empty($draft['partner_id'])) {
				$oRow = $this->orders_model->get($order_id);
				if ($oRow && !empty($oRow->order_uid)) {
					$orderUidShort = lims_uid_short($oRow->order_uid);
				}
			}


				// Νόμισμα (προς το παρόν έτσι κι αλλιώς default είναι)
				$order_currency = $this->orders_model->get_order_currency_id($draft['client_id'] ?? null);

				// ----------------- ΔΙΑΒΑΣΜΑ ΕΠΙΛΟΓΩΝ -----------------
				$selected = [];

				// (A) Νέα ονοματολογία
				$selPanels   = $this->input->post('pick_panels')   ?: [];
				$selAnalyses = $this->input->post('pick_analyses') ?: [];
				$selCultures = $this->input->post('pick_cultures') ?: [];

				foreach ((array)$selPanels as $pid) {
					$id = (int)$pid;
					if ($id > 0) {
						$selected[] = ['type' => 'panel', 'id' => $id];
					}
				}
				foreach ((array)$selAnalyses as $aid) {
					$id = (int)$aid;
					if ($id > 0) {
						$selected[] = ['type' => 'analysis', 'id' => $id];
					}
				}
				foreach ((array)$selCultures as $cid) {
					$id = (int)$cid;
					if ($id > 0) {
						$selected[] = ['type' => 'culture', 'id' => $id];
					}
				}

				// (B) Υφιστάμενο payload: lines[*][checked], lines[*][type], lines[*][id]
				$linesPost = $this->input->post('lines');
				if (is_array($linesPost)) {
					foreach ($linesPost as $k => $r) {
						$checked = isset($r['checked']) ? (string)$r['checked'] : '';
						if ($checked !== '1' && $checked !== 1) {
							continue;
						}

						$type = isset($r['type']) ? trim((string)$r['type']) : '';
						$id   = isset($r['id'])   ? (int)$r['id'] : 0;

						if ($id > 0 && in_array($type, ['panel','analysis','culture'], true)) {
							$selected[] = ['type' => $type, 'id' => $id];
						}
					}
				}
				// -----------------------------------------------------

				// Νόμισμα (πελάτη ή default – ξανά για σιγουριά)
				$order_currency = $this->orders_model->get_order_currency_id($draft['client_id'] ?? null);

				foreach ($selected as $row) {
					$type = $row['type'];
					$sid  = (int)$row['id'];

					if ($type === 'panel') {
						$panel = $this->panels_model->get($sid);
						if (!$panel) {
							continue;
						}
						$item = $panel->item_id ? $this->invoice_items_model->get($panel->item_id) : null;
						$snap = $this->orders_model->build_snapshot_from_item(
							$item,
							$panel->name,
							$order_currency,
							$draft['contract_id'] ?? null,
							$panel->id,
							'panel'
						);
						$this->orders_model->add_order_item($order_id, $snap);

					} elseif ($type === 'analysis') {
						$ana = $this->analyses_model->get($sid);
						if (!$ana) {
							continue;
						}
						$item = $ana->item_id ? $this->invoice_items_model->get($ana->item_id) : null;
						$snap = $this->orders_model->build_snapshot_from_item(
							$item,
							$ana->name,
							$order_currency,
							$draft['contract_id'] ?? null,
							$ana->id,
							'analysis'
						);
						$this->orders_model->add_order_item($order_id, $snap);

					} elseif ($type === 'culture') {
						$cul = $this->cultures_model->get($sid);
						if (!$cul) {
							continue;
						}
						$item = $cul->item_id ? $this->invoice_items_model->get($cul->item_id) : null;
						$snap = $this->orders_model->build_snapshot_from_item(
							$item,
							$cul->name,
							$order_currency,
							$draft['contract_id'] ?? null,
							$cul->id,
							'culture'
						);
						$this->orders_model->add_order_item($order_id, $snap);
					}
				}

				// καθάρισε draft, redirect στο view
				$this->session->unset_userdata('lims_order_draft');
				set_alert('success', _l('lims_saved'));
				return redirect(admin_url('lims/orders/view/' . $order_id));
			}

			// ------------------------------------------------------------------
			// MODE B: samples-first
			// ------------------------------------------------------------------

			$samplesPost = $this->input->post('samples');
			if (!is_array($samplesPost) || empty($samplesPost)) {
				set_alert('danger', _l('lims_samples_required') ?: 'At least one sample is required.');
				return redirect(admin_url('lims/orders/create?step=2'));
			}

			// Δημιουργία order (χωρίς items ακόμη)
			$order_id = $this->orders_model->create_order($draft);

			// Βεβαιώσου ότι έχει barcode το order (θα το κληρονομήσουν τα samples)
			$order_barcode = $this->orders_model->ensure_barcode($order_id);

			$p = db_prefix();

			// Counters για billing
			$panelCounts    = []; // [panel_id => qty]
			$analysisCounts = []; // [analysis_id => qty]
			$cultureCounts  = []; // [culture_id => qty]

			// Counter για sample_uid
			$sampleIndex    = 0;
			$createdSamples = 0;

			foreach ($samplesPost as $idx => $row) {

				$sample_type_id = isset($row['sample_type_id']) ? (int)$row['sample_type_id'] : 0;
				if ($sample_type_id <= 0) {
					// skip άδεια/μη έγκυρα rows
					continue;
				}

				$sample_uid   = isset($row['sample_uid']) ? trim($row['sample_uid']) : '';
				$sample_notes = isset($row['notes']) ? trim($row['notes']) : '';

				if ($sample_uid === '') {
					$sampleIndex++;
					if (!empty($draft['partner_id']) && $orderUidShort) {
						$sample_uid = 'S-' . $orderUidShort . '-' . str_pad((string)$sampleIndex, 3, '0', STR_PAD_LEFT);
					} else {
						$sample_uid = 'S' . $order_id . '-' . str_pad((string)$sampleIndex, 3, '0', STR_PAD_LEFT);
					}
				}

				// Δημιουργία sample
				$this->db->insert($p . 'lims_samples', [
					'order_id'       => $order_id,
					'subject_id'     => !empty($draft['subject_id']) ? (int)$draft['subject_id'] : null,
					'appointment_id' => null,
					'sample_uid'     => $sample_uid,
					'barcode'        => $order_barcode ?: $sample_uid,
					'sample_type_id' => $sample_type_id,
					'status'         => 'pending',
					'notes'          => $sample_notes !== '' ? $sample_notes : null,
					'created_at'     => date('Y-m-d H:i:s'),
				]);

				$sample_id = (int)$this->db->insert_id();


				// Persist per-sample requested cultures in tbllims_sample_cultures (if available)
				if (!empty($requestedCultureIds) && $this->db->table_exists($p.'lims_sample_cultures')) {
					foreach ($requestedCultureIds as $cid) {
						$this->db->query(
							"INSERT IGNORE INTO `".$p."lims_sample_cultures` (order_id, sample_id, culture_id, created_at) VALUES (?,?,?,?)",
							[$order_id, $sample_id, (int)$cid, date('Y-m-d H:i:s')]
						);
					}
				}
				if (!$sample_id) {
					continue;
				}
				$createdSamples++;

				// --- Tests selection per sample ---
				$panelIds    = isset($row['panels'])   ? array_map('intval', (array)$row['panels'])   : [];
				$analysisIds = isset($row['analyses']) ? array_map('intval', (array)$row['analyses']) : [];
				$cultureIds  = isset($row['cultures']) ? array_map('intval', (array)$row['cultures']) : [];

				$panelIds    = array_values(array_filter($panelIds));
				$analysisIds = array_values(array_filter($analysisIds));
				$cultureIds  = array_values(array_filter($cultureIds));


				// Χρειαζόμαστε αναλύσεις/καλλιέργειες που ανήκουν στα panels που διάλεξε
				$panelAnalyses = []; // [panel_id => [analysis_id,...]]
				$panelCultures = []; // [panel_id => [culture_id,...]]

				if (!empty($panelIds)) {
					$rows = $this->db->select('panel_id, analysis_id, culture_id')
						->from($p . 'lims_panel_items')
						->where_in('panel_id', $panelIds)
						->order_by('sort_order', 'ASC')
						->get()->result();

					foreach ($rows as $r) {
						$pid = (int)$r->panel_id;
						if (!isset($panelAnalyses[$pid])) {
							$panelAnalyses[$pid] = [];
						}
						if (!isset($panelCultures[$pid])) {
							$panelCultures[$pid] = [];
						}

						if (!empty($r->analysis_id)) {
							$panelAnalyses[$pid][] = (int)$r->analysis_id;
						}
						if (!empty($r->culture_id)) {
							$panelCultures[$pid][] = (int)$r->culture_id;
						}
					}
				}

				// === Δημιουργία Tests (tbllims_tests) ===
				// Όλες οι analyses (από panels + standalone)
				$analysisIdsForTests = [];

				// από panels
				foreach ($panelAnalyses as $pid => $alist) {
					foreach ($alist as $aid) {
						$analysisIdsForTests[$aid] = true;
					}
				}

				// standalone analyses
				foreach ($analysisIds as $aid) {
					$analysisIdsForTests[$aid] = true;
				}

				if (!empty($analysisIdsForTests)) {
					foreach (array_keys($analysisIdsForTests) as $aid) {
						$this->db->insert($p . 'lims_tests', [
							'sample_id'   => $sample_id,
							'analysis_id' => (int)$aid,
							'status'      => 'pending',
							'created_at'  => date('Y-m-d H:i:s'),
						]);
					}
				}

				// === Billing Counters ===
				// Panels: πάντα 1 per sample → αυξάνουμε counter
				foreach ($panelIds as $pid) {
					if (!isset($panelCounts[$pid])) {
						$panelCounts[$pid] = 0;
					}
					$panelCounts[$pid]++;
				}

				// Analyses: μετράμε μόνο αυτές ΠΟΥ ΔΕΝ περιλαμβάνονται σε επιλεγμένα panels
				$analysesInPanels = [];
				foreach ($panelAnalyses as $pid => $alist) {
					foreach ($alist as $aid) {
						$analysesInPanels[$aid] = true;
					}
				}

				foreach ($analysisIds as $aid) {
					if (isset($analysesInPanels[$aid])) {
						// αν το analysis είναι ήδη μέσα σε panel,
						// θεωρούμε ότι χρεώνεται μέσω του panel και δεν το ξαναμετράμε
						continue;
					}
					if (!isset($analysisCounts[$aid])) {
						$analysisCounts[$aid] = 0;
					}
					$analysisCounts[$aid]++;
				}

				// Cultures: αντίστοιχη λογική
				$culturesInPanels = [];
				foreach ($panelCultures as $pid => $clist) {
					foreach ($clist as $cid) {
						$culturesInPanels[$cid] = true;
					}
				}

				foreach ($cultureIds as $cid) {
					if (isset($culturesInPanels[$cid])) {
						continue;
					}
					if (!isset($cultureCounts[$cid])) {
						$cultureCounts[$cid] = 0;
					}
					$cultureCounts[$cid]++;
				}

				// Προαιρετικά εδώ μπορούν να μπουν culture_results placeholders στο μέλλον
			}

			// Αν τελικά δεν δημιουργήθηκε ούτε ένα sample, ακύρωσε το order
			if ($createdSamples === 0) {
				$this->db->where('id', $order_id)->delete($p . 'lims_orders');
				set_alert('danger', _l('lims_samples_required') ?: 'At least one valid sample is required.');
				return redirect(admin_url('lims/orders/create?step=2'));
			}

			// === Δημιουργία Billing lines (tbllims_order_items) ===
			$order_currency = $this->orders_model->get_order_currency_id($draft['client_id'] ?? null);

			// Panels
			foreach ($panelCounts as $panel_id => $qty) {
				$panel = $this->panels_model->get($panel_id);
				if (!$panel) {
					continue;
				}
				$item = $panel->item_id ? $this->invoice_items_model->get($panel->item_id) : null;
				$snap = $this->orders_model->build_snapshot_from_item(
					$item,
					$panel->name,
					$order_currency,
					$draft['contract_id'] ?? null,
					$panel->id,
					'panel'
				);
				$snap['qty'] = (float)$qty;

				$this->orders_model->add_order_item($order_id, $snap);
			}

			// Analyses
			foreach ($analysisCounts as $analysis_id => $qty) {
				$ana = $this->analyses_model->get($analysis_id);
				if (!$ana) {
					continue;
				}
				$item = $ana->item_id ? $this->invoice_items_model->get($ana->item_id) : null;
				$snap = $this->orders_model->build_snapshot_from_item(
					$item,
					$ana->name,
					$order_currency,
					$draft['contract_id'] ?? null,
					$ana->id,
					'analysis'
				);
				$snap['qty'] = (float)$qty;

				$this->orders_model->add_order_item($order_id, $snap);
			}

			// Cultures
			foreach ($cultureCounts as $culture_id => $qty) {
				$cul = $this->cultures_model->get($culture_id);
				if (!$cul) {
					continue;
				}
				$item = $cul->item_id ? $this->invoice_items_model->get($cul->item_id) : null;
				$snap = $this->orders_model->build_snapshot_from_item(
					$item,
					$cul->name,
					$order_currency,
					$draft['contract_id'] ?? null,
					$cul->id,
					'culture'
				);
				$snap['qty'] = (float)$qty;

				$this->orders_model->add_order_item($order_id, $snap);
			}

			

			// Partner orders: enqueue sync event (order.created) for background delivery
			if (!empty($draft['partner_id'])) {
				try {
					$this->sync_model->enqueue_order_created((int)$draft['partner_id'], (int)$order_id);
					$this->orders_model->update_order((int)$order_id, [
						'partner_sync_status' => 'queued',
						'partner_sync_error'  => null,
					]);
				} catch (Throwable $e) {
					// do not block UI; store error for admin visibility
					$this->orders_model->update_order((int)$order_id, [
						'partner_sync_status' => 'failed',
						'partner_sync_error'  => $e->getMessage(),
					]);
				}
			}
// Καθάρισε draft, redirect
			$this->session->unset_userdata('lims_order_draft');
			set_alert('success', _l('lims_saved'));
			return redirect(admin_url('lims/orders/view/' . $order_id));
		}

		// GET
		$step        = (int)($this->input->get('step') ?: 1);
		$data['title'] = _l('lims_new_order');

		if ($step === 2) {
			$draft      = $this->session->userdata('lims_order_draft') ?: [];
			$entry_mode = isset($draft['entry_mode']) ? $draft['entry_mode'] : 'tests';

			// Safety: Partner orders must run in samples mode
			if (!empty($draft['partner_id'])) {
				$entry_mode = 'samples';
			}


			$data['step']       = 2;
			$data['draft']      = $draft;
			$data['entry_mode'] = $entry_mode;

			if ($entry_mode === 'samples') {
				// SAMPLE-FIRST VIEW
				$data['sample_types'] = $this->db
					->order_by('name', 'ASC')
					->get(db_prefix() . 'lims_sample_types')
					->result();

				$data['panels']   = $this->panels_model->all();
				$data['analyses'] = $this->analyses_model->all();
				$data['cultures'] = $this->cultures_model->all();

				$this->load->view('lims/admin/orders/create_step2_samples', $data);
			} else {
				// ΠΑΛΙΟ TEST-FIRST VIEW
				$data['panels']    = $this->panels_model->all();
				$data['analyses']  = $this->analyses_model->all();
				$data['cultures']  = $this->cultures_model->all();
				$data['contracts'] = [];
				$this->load->view('lims/admin/orders/create_step2', $data);
			}
			return;
		}

		// STEP 1 view
		$data['step']      = 1;
		$data['subjects']  = $this->db->order_by('id', 'DESC')->get(db_prefix() . 'lims_subjects')->result();
		$data['clients']   = $this->db
			->select('userid, company')
			->order_by('company', 'ASC')
			->get(db_prefix() . 'clients')
			->result();

		$data['contracts'] = $this->contracts_model->all();
		
		$data['partners'] = $this->partners_model->get_active_for_orders();

		$this->load->view('lims/admin/orders/create_step1', $data);
	}



	public function edit($id)
	{
		$id = (int)$id;
		$row = $this->orders_model->get($id);
		if (!$row) { set_alert('danger', _l('lims_error_generic')); return redirect(admin_url('lims/orders')); }

		if ($this->input->post('action') === 'save_header') {
			$data = [
				'client_id'   => (int)$this->input->post('client_id'),
				'contract_id' => (int)$this->input->post('contract_id') ?: null,
				'priority'    => (int)$this->input->post('priority') ?: 0,
				'due_at'      => trim($this->input->post('due_at')) ?: null,
				'notes'       => trim($this->input->post('notes')) ?: null,
			];
			$ok = $this->orders_model->update_order($id, $data);
			set_alert($ok ? 'success':'danger', $ok ? _l('lims_saved') : _l('lims_error_generic'));
			return redirect(admin_url('lims/orders/view/'.$id));
		}

		$data['title']     = _l('lims_order').' #'.$id;
		$data['row']       = $row;
		$data['clients']   = $this->db->select('userid,company')->order_by('company','ASC')->get(db_prefix().'clients')->result();
		$data['contracts'] = $this->db->order_by('priority','DESC')->get(db_prefix().'lims_contracts')->result();

		// ένα απλό view για την κεφαλίδα (αν δεν υπάρχει, μπορείς προσωρινά να το βάλεις μέσα στο view του order)
		$this->load->view('lims/admin/orders/edit_header', $data);
	}


    public function view($id)
    {
        $data['title'] = _l('lims_order').' #'.$id;
        $data['row']   = $this->orders_model->get($id);
        $data['lines'] = $this->orders_model->get_items($id);
		$data['samples'] = $this->orders_model->get_samples_with_tests($id);

        $this->load->view('lims/admin/orders/view', $data);
    }
	
	public function change_status()
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}
		$id     = (int)$this->input->post('id');
		$status = trim($this->input->post('status'));

		$allowed = [
			// ΝΕΑ business statuses
			'draft',
			'in_progress',
			'appointment',
			'samples',
			'reported',
			'complete',
			'canceled',

			// legacy – για να μη σπάσει τίποτα παλιό (testing workflow κτλ)
			'submitted',
			'accessioned',
			'testing',
			'verified',
			'approved',
		];

		if (!in_array($status, $allowed, true)) {
			echo json_encode(['success'=>false,'message'=>'Invalid status']); die;
		}

		$ok = $this->orders_model->update_status($id, $status);
		if ($ok) {
			$this->orders_model->add_activity($id, 'status_changed', _l('lims_action_status_changed', ucfirst($status)), [
				'new_status' => $status,
			]);
		}

		echo json_encode(['success'=>(bool)$ok]); die;
	}
	private function bump_order_status($order_id, $new_status)
	{
		$order_id    = (int)$order_id;
		$new_status  = (string)$new_status;

		$order = $this->orders_model->get($order_id);
		if (!$order) { return; }

		// ranking για να μην "πισωγυρίζουμε"
		$rank = [
			'draft'       => 0,
			'in_progress' => 1,
			'appointment' => 2,
			'samples'     => 3,
			'reported'    => 4,
			'complete'    => 5,
			'canceled'    => 99,
		];

		$cur = (string)($order->status ?: 'draft');

		$curRank = isset($rank[$cur])      ? $rank[$cur]      : 0;
		$newRank = isset($rank[$new_status]) ? $rank[$new_status] : 0;

		// μόνο αν είναι "μπροστά" το νέο status
		if ($newRank > $curRank) {
			$this->orders_model->update_status($order_id, $new_status);
			if (method_exists($this->orders_model, 'add_activity')) {
				$this->orders_model->add_activity(
					$order_id,
					'status_changed',
					_l('lims_action_status_changed', ucfirst($new_status)),
					['new_status' => $new_status]
				);
			}
		}
	}

	// === LOAD APPOINTMENTS PICKER (modal body) ===
	public function appointments_picker($order_id)
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}
		$order_id = (int)$order_id;

		$order = $this->orders_model->get($order_id);
		if (!$order) {
			echo '<div class="text-danger">Order not found.</div>';
			die;
		}

		$this->load->model('lims/appointments_model','appointments_model');

		// ΜΟΝΟ του συγκεκριμένου client + ONLY upcoming + status όχι canceled/no_show
		$now = date('Y-m-d H:i:s');
		$rows = $this->db
			->where('client_id', (int)$order->client_id)
			->where('appointment_at >=', $now)
			->where_not_in('status', ['canceled','no_show'])
			->order_by('appointment_at', 'ASC')
			->limit(50)
			->get(db_prefix().'lims_appointments')
			->result();

		$data = [
			'order'        => $order,
			'appointments' => $rows,
		];
		$this->load->view('lims/admin/orders/_modal_appointments_picker', $data);
	}


	// === LINK EXISTING APPOINTMENT -> ORDER ===
	public function link_appointment()
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}
		$order_id       = (int)$this->input->post('order_id');
		$appointment_id = (int)$this->input->post('appointment_id');

		$order = $this->orders_model->get($order_id);
		if (!$order) { echo json_encode(['success'=>false,'message'=>'Order not found']); die; }

		$this->load->model('lims/appointments_model','appointments_model');
		$ap = $this->appointments_model->get($appointment_id);
		if (!$ap) { echo json_encode(['success'=>false,'message'=>'Appointment not found']); die; }

		// Ασφάλεια: πρέπει να ταιριάζει ο client
		if ((int)$ap->client_id !== (int)$order->client_id) {
			echo json_encode(['success'=>false,'message'=>'Client mismatch']); die;
		}

		// Σύνδεση (μονοσήμαντη)
		$this->db->where('id',$appointment_id)->update(db_prefix().'lims_appointments', [
			'order_id' => $order_id,
		]);

		// Activity logs
		if (method_exists($this->orders_model,'add_activity')) {
			$this->orders_model->add_activity($order_id, 'appointment_linked_to_order',
				'Appointment linked', ['appointment_id' => $appointment_id]);
		}
		if ($this->load->model('lims/appointments_model','appointments_model', true)) {
			if (method_exists($this->appointments_model,'add_activity')) {
				$this->appointments_model->add_activity($appointment_id, 'appointment_linked_to_order',
					'Linked to order', ['order_id'=>$order_id]);
			}
		}
		// Auto status bump -> Appointment
		$this->bump_order_status($order_id, 'appointment');

		echo json_encode(['success'=>true]);
		die;
	}

	// === UNLINK APPOINTMENT FROM ORDER ===
	public function unlink_appointment()
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}
		$order_id       = (int)$this->input->post('order_id');
		$appointment_id = (int)$this->input->post('appointment_id');

		$ap = $this->db->where('id',$appointment_id)->get(db_prefix().'lims_appointments')->row();
		if (!$ap || (int)$ap->order_id !== $order_id) {
			echo json_encode(['success'=>false]); die;
		}

		$this->db->where('id',$appointment_id)->update(db_prefix().'lims_appointments', ['order_id'=>NULL]);

		// Activity logs
		if (method_exists($this->orders_model,'add_activity')) {
			$this->orders_model->add_activity($order_id, 'appointment_unlinked_from_order',
				'Appointment unlinked', ['appointment_id' => $appointment_id]);
		}
		// appointments log αν υπάρχει
		if ($this->load->model('lims/appointments_model','appointments_model', true)) {
			if (method_exists($this->appointments_model,'add_activity')) {
				$this->appointments_model->add_activity($appointment_id, 'appointment_unlinked_from_order',
					'Unlinked from order', ['order_id'=>$order_id]);
			}
		}

		echo json_encode(['success'=>true]);
		die;
	}

	// === CREATE NEW APPOINTMENT (INLINE από Order) ===
	public function create_appointment_from_order()
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}

		$order_id = (int)$this->input->post('order_id');
		$order = $this->orders_model->get($order_id);
		if (!$order) { echo json_encode(['success'=>false,'message'=>'Order not found']); die; }

		$appointment_at = trim($this->input->post('appointment_at'));
		if ($appointment_at === '') { echo json_encode(['success'=>false,'message'=>'Date/time required']); die; }

		$visit_type     = in_array($this->input->post('visit_type'), ['lab','home']) ? $this->input->post('visit_type') : 'lab';
		$location_text  = trim($this->input->post('location_text')) ?: null;
		$lat            = $this->input->post('lat') !== '' ? (float)$this->input->post('lat') : null;
		$lng            = $this->input->post('lng') !== '' ? (float)$this->input->post('lng') : null;
		$assigned_staff = $this->input->post('assigned_staff') !== '' ? (int)$this->input->post('assigned_staff') : null;
		$notes          = trim($this->input->post('notes')) ?: null;

		$payload = [
			'client_id'      => (int)$order->client_id,
			'order_id'       => (int)$order_id,
			'appointment_at' => $appointment_at,
			'visit_type'     => $visit_type,
			'location_text'  => $location_text,
			'lat'            => $lat,
			'lng'            => $lng,
			'assigned_staff' => $assigned_staff,
			'status'         => 'pending',
			'notes'          => $notes,
		];

		$this->load->model('lims/appointments_model','appointments_model');
		// χρησιμοποίησε τη δική σου add/save του Appointments model
		if (method_exists($this->appointments_model,'add')) {
			$aid = $this->appointments_model->add($payload);
		} else if (method_exists($this->appointments_model,'save')) {
			$aid = $this->appointments_model->save($payload);
		} else {
			// fallback: direct insert
			$this->db->insert(db_prefix().'lims_appointments', $payload);
			$aid = (int)$this->db->insert_id();
		}

		if ($aid) {
			if (method_exists($this->orders_model,'add_activity')) {
				$this->orders_model->add_activity($order_id, 'appointment_created_from_order',
					'Appointment created from Order', ['appointment_id'=>$aid]);
			}
			if (method_exists($this->appointments_model,'add_activity')) {
				$this->appointments_model->add_activity($aid, 'appointment_created', 'Created via Order', ['order_id'=>$order_id]);
			}
			echo json_encode(['success'=>true,'appointment_id'=>$aid]); die;
		}
		// Auto status bump -> Appointment
		$this->bump_order_status($order_id, 'appointment');

		echo json_encode(['success'=>false,'message'=>'Create failed']); die;
	}
	public function new_appointment_modal($order_id)
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}
		$order_id = (int)$order_id;

		$order = $this->orders_model->get($order_id);
		if (!$order) { echo '<div class="alert alert-danger">Order not found.</div>'; die; }

		// staff list for assign
		$staff = $this->db->where('active',1)
						  ->order_by('firstname','ASC')
						  ->get(db_prefix().'staff')->result();

		$data = [
			'order' => $order,
			'staff' => $staff,
			// προεπιλογές
			'prefill' => [
				'client_id' => (int)$order->client_id,
				'visit_type' => 'lab',
				'appointment_at' => date('Y-m-d H:i'), // τώρα + user can adjust
				'location_text' => '',
				'lat' => '',
				'lng' => '',
				'assigned_staff' => get_staff_user_id() ?: null,
			],
			'csrf' => [
				'name'  => $this->security->get_csrf_token_name(),
				'hash'  => $this->security->get_csrf_hash(),
			],
		];
		$this->load->view('lims/admin/orders/_modal_appointment_create', $data);
	}
	public function create_appointment_ajax($order_id)
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		$order_id = (int)$order_id;
		$order = $this->orders_model->get($order_id);
		if (!$order) { echo json_encode(['success'=>false,'message'=>'Order not found']); die; }

		// basic sanitize
		$client_id      = (int)$order->client_id;
		$appointment_at = trim($this->input->post('appointment_at', true));
		$visit_type     = in_array($this->input->post('visit_type', true), ['lab','home'], true) ? $this->input->post('visit_type', true) : 'lab';
		$location_text  = trim($this->input->post('location_text', true));
		$lat            = $this->input->post('lat', true);
		$lng            = $this->input->post('lng', true);
		$assigned_staff = (int)($this->input->post('assigned_staff') ?: 0) ?: null;
		$notes          = trim($this->input->post('notes', true));
		$create_task    = (int)($this->input->post('create_task') ?: 0) === 1;

		if ($appointment_at === '') {
			echo json_encode(['success'=>false,'message'=>_l('problem_creating')]); die;
		}

		// create appointment
		$appt = [
			'client_id'      => $client_id,
			'order_id'       => $order_id,
			'appointment_at' => to_sql_date($appointment_at, true), // datetime
			'visit_type'     => $visit_type,
			'location_text'  => $location_text,
			'lat'            => ($lat !== '' ? (float)$lat : null),
			'lng'            => ($lng !== '' ? (float)$lng : null),
			'status'         => 'confirmed',
			'assigned_staff' => $assigned_staff,
			'notes'          => $notes,
			'created_at'     => date('Y-m-d H:i:s'),
		];

		// insert
		$this->db->insert(db_prefix().'lims_appointments', $appt);
		$appointment_id = (int)$this->db->insert_id();

		if (!$appointment_id) {
			echo json_encode(['success'=>false,'message'=>_l('problem_creating')]); die;
		}

		// optional Task
		if ($create_task) {
			$this->load->model('tasks_model');
			$subject = 'Appointment for Order #'.$order_id.' ('.$visit_type.')';

			$task_data = [
				'name'        => $subject,
				'rel_type'    => 'customer',
				'rel_id'      => $client_id,
				'dateadded'   => date('Y-m-d H:i:s'),
				'startdate'   => date('Y-m-d', strtotime($appointment_at)),
				'status'      => 1, // not started
				'priority'    => 2,
				'repeat_every'=> 0,
				'description' => $location_text ? ('Location: '.$location_text."\n\n".$notes) : $notes,
			];
			$task_id = $this->tasks_model->add($task_data, []);
			if ($task_id) {
				// assign staff if provided
				if ($assigned_staff) {
					$this->tasks_model->add_task_assignees([
						'taskid' => $task_id,
						'assignees' => [$assigned_staff],
					]);
				}
				// link back to appointment
				$this->db->where('id', $appointment_id)->update(db_prefix().'lims_appointments', [
					'task_id' => $task_id,
				]);
			}
		}

		// activity log on order
		if (method_exists($this->orders_model,'add_activity')) {
			$this->orders_model->add_activity($order_id, 'appointment_created',
				'Appointment created and linked',
				['appointment_id' => $appointment_id]
			);
		}

		echo json_encode(['success'=>true, 'appointment_id'=>$appointment_id]);
	}

	public function create_invoice($id)
	{
		if (!has_permission('invoices', '', 'create')) {
			access_denied('Invoices');
		}

		$id   = (int)$id;
		$mode = $this->input->get('mode') ?: 'normal'; // draft | normal | pay

		$order = $this->orders_model->get($id);
		if (!$order) {
			set_alert('danger', _l('lims_error_generic'));
			return redirect(admin_url('lims/orders'));
		}

		// Θωράκιση: καθάρισε πιθανά auto-pay flags από sessions άλλων modules
		$this->load->library('session');
		foreach ([
			'auto_pay_invoice','auto_payment_invoice_id','auto_payment_amount',
			'guest_auto_pay','guest_invoice_autopay','auto_pay'
		] as $k) { $this->session->unset_userdata($k); }

		$lines = $this->orders_model->get_items($id);
		if (!$lines) {
			set_alert('warning', _l('no_items_found') ?: 'No items found.');
			return redirect(admin_url('lims/orders/view/'.$id));
		}

		// Νόμισμα
		$currency_id = 0;
		foreach ($lines as $ln) { if (!empty($ln->currency_id)) { $currency_id = (int)$ln->currency_id; break; } }
		if (!$currency_id) {
			$def = $this->db->where('isdefault',1)->get(db_prefix().'currencies')->row();
			if ($def) { $currency_id = (int)$def->id; }
		}

		// Billing από πελάτη
		$client = $this->db->where('userid', (int)$order->client_id)->get(db_prefix().'clients')->row();

		$inv = [
			'clientid'        => (int)$order->client_id,
			'date'            => date('Y-m-d'),
			'duedate'         => !empty($order->due_at) ? date('Y-m-d', strtotime($order->due_at)) : null,
			'currency'        => $currency_id,
			'newitems'        => [],
			'allowed_payment_modes' => [],
			'show_quantity_as' => 1,
			'billing_street'   => (string)($client->address ?? ''),
			'billing_city'     => (string)($client->city ?? ''),
			'billing_state'    => (string)($client->state ?? ''),
			'billing_zip'      => (string)($client->zip ?? ''),
			'billing_country'  => (int)($client->country ?? 0),
			'shipping_street'  => (string)($client->address ?? ''),
			'shipping_city'    => (string)($client->city ?? ''),
			'shipping_state'   => (string)($client->state ?? ''),
			'shipping_zip'     => (string)($client->zip ?? ''),
			'shipping_country' => (int)($client->country ?? 0),
			'clientnote'       => '',
			'terms'            => '',
		];
		if ($mode === 'draft') {
			$inv['save_as_draft'] = 1; // αφήνει number=STATUS_DRAFT_NUMBER, θα το καθαρίσουμε αμέσως μετά
		}

		$taxMap = function($id){
			if (!$id) return null;
			$t = get_instance()->db->where('id',(int)$id)->get(db_prefix().'taxes')->row();
			return $t ? ($t->name.'|'.(0+$t->taxrate)) : null;
		};

		foreach ($lines as $ln) {
			$taxes = [];
			$t1 = $taxMap($ln->tax1_id ?? null);
			$t2 = $taxMap($ln->tax2_id ?? null);
			if ($t1) $taxes[] = $t1;
			if ($t2) $taxes[] = $t2;

			$inv['newitems'][] = [
				'description'      => $ln->name,
				'long_description' => '',
				'qty'              => (float)($ln->qty ?? 1),
				'unit'             => $ln->unit ?? '',
				'rate'             => (float)($ln->unit_price ?? 0),
				'order'            => 1,
				'taxname'          => $taxes,
			];
		}

		// Δημιουργία
		$this->load->model('invoices_model');
		$invoice_id = $this->invoices_model->add($inv);
		if (!$invoice_id) {
			set_alert('danger', _l('problem_creating').' '._l('invoice'));
			return redirect(admin_url('lims/orders/view/'.$id));
		}

		// Link order<->invoice
		$this->db->insert(db_prefix().'lims_billing_links', [
			'order_id'   => (int)$id,
			'invoice_id' => (int)$invoice_id,
			'created_at' => date('Y-m-d H:i:s'),
		]);

		// --- Number & status handling ---
		$invRow = $this->invoices_model->get($invoice_id);
		$draftNum = defined('Invoices_model::STATUS_DRAFT_NUMBER') ? Invoices_model::STATUS_DRAFT_NUMBER : 1000000000;

		if ($mode === 'draft') {
			// κρατάμε draft status αλλά number=0 για να μη φαίνεται INV-1000000000
			$this->db->where('id', $invoice_id)->update(db_prefix().'invoices', ['number' => 0]);
		} else {
			// NORMAL / PAY: αν για οποιονδήποτε λόγο έμεινε στο draft number, διόρθωσέ το
			if ((int)$invRow->number === (int)$draftNum) {
				$next = (int) get_option('next_invoice_number'); // έχει ήδη αυξηθεί από το core
				$assign = max(1, $next - 1);
				$this->db->where('id', $invoice_id)->update(db_prefix().'invoices', ['number' => $assign]);
			}
		}

		// Ασφάλεια κατά auto-pay τρίτων + status
		$this->db->where('invoiceid',$invoice_id)->delete(db_prefix().'invoicepaymentrecords');
		if ($mode === 'normal') {
			$this->db->where('id',$invoice_id)->update(db_prefix().'invoices', ['status'=>1]); // unpaid
		} elseif ($mode === 'pay') {
			$this->load->model('payments_model');
			$invoice = $this->invoices_model->get($invoice_id);
			$amount_to_pay = $invoice ? (float)$invoice->total : 0.00;
			$pmode = $this->db->where('active',1)->order_by('id','ASC')->get(db_prefix().'payment_modes')->row();
			if ($pmode && $amount_to_pay > 0) {
				$pay = [
					'amount'      => $amount_to_pay,
					'invoiceid'   => $invoice_id,
					'paymentmode' => $pmode->id,
					'date'        => date('Y-m-d'),
					'note'        => 'Auto payment from LIMS (Convert & Pay)',
				];
				$this->payments_model->add($pay);
			}
		}

		// Recalc & activity
		if (method_exists($this->invoices_model, 'update_invoice_status')) {
			$this->invoices_model->update_invoice_status($invoice_id, true);
		}
		$this->orders_model->add_activity($id, 'invoice_created',
			_l('lims_action_invoice_created'),
			['invoice_id' => (int)$invoice_id, 'mode' => $mode]
		);

		set_alert('success', _l('invoice').' '._l('created_successfully'));
		return redirect(admin_url('lims/orders/view/'.$id));
	}


	public function generate_barcode($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) {
			access_denied('Lims');
		}
		$id = (int)$id;

		$order = $this->orders_model->get($id);
		if (!$order) {
			set_alert('danger', _l('lims_error_generic'));
			return redirect(admin_url('lims/orders'));
		}

		$force = (int)($this->input->get('force') ?: 0) === 1;
		if (!empty($order->order_barcode) && !$force) {
			// ήδη έχει barcode
			return redirect(admin_url('lims/orders/view/'.$id));
		}

		$seqTbl = db_prefix().'lims_barcode_sequences';
		$prefix = 'ORD';

		// βεβαιώσου ότι υπάρχει σειρά για ORD
		if ($this->db->where('prefix',$prefix)->get($seqTbl)->num_rows() == 0) {
			$this->db->insert($seqTbl, ['prefix'=>$prefix,'next_number'=>1]);
		}

		// Atomic αύξηση χωρίς FOR UPDATE (χρησιμοποιούμε LAST_INSERT_ID)
		$this->db->trans_begin();

		$this->db->query("UPDATE `{$seqTbl}` 
						  SET next_number = LAST_INSERT_ID(next_number + 1) 
						  WHERE prefix = ?", [$prefix]);

		$row = $this->db->query("SELECT LAST_INSERT_ID() AS seq")->row();
		$newVal = isset($row->seq) ? (int)$row->seq : 0;

		// Χρησιμοποιούμε το προηγούμενο νούμερο ως τρέχον barcode
		$num  = max(1, $newVal - 1);
		$code = $prefix . str_pad((string)$num, 8, '0', STR_PAD_LEFT);

		$this->db->where('id',$id)->update(db_prefix().'lims_orders', [
			'order_barcode' => $code,
			'updated_at'    => date('Y-m-d H:i:s'),
		]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			set_alert('danger', _l('lims_error_generic'));
		} else {
			$this->db->trans_commit();
			set_alert('success', _l('lims_saved'));
		}
		if ($this->db->trans_status() !== FALSE) {
			$this->orders_model->add_activity($id, $force ? 'barcode_regenerated' : 'barcode_generated',
				_l($force ? 'lims_action_barcode_regenerated' : 'lims_action_barcode_generated', $code),
				['barcode' => $code]
			);
		}
		// Auto status bump -> In progress
		if ($this->db->trans_status() !== FALSE) {
			$this->bump_order_status($id, 'in_progress');
		}


		
		return redirect(admin_url('lims/orders/view/'.$id));
	}

	public function inline_update_notes($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) { ajax_access_denied(); }
		$id = (int)$id;
		$notes = trim((string)$this->input->post('notes'));
		$ok = $this->db->where('id',$id)->update(db_prefix().'lims_orders', [
			'notes'      => $notes,
			'updated_at' => date('Y-m-d H:i:s'),
		]);
		if ($ok) {
			$this->orders_model->add_activity($id, 'notes_updated', _l('lims_saved'), ['field'=>'notes']);
		}
		echo json_encode(['success'=>(bool)$ok]); die;
	}
	public function inline_update_contract($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) { ajax_access_denied(); }
		$id = (int)$id;

		$cid = $this->input->post('contract_id');
		$contract_id = ($cid === '' || $cid === null) ? null : (int)$cid;

		// update order.contract_id (τώρα που υπάρχει η στήλη)
		$this->db->where('id', $id)->update(db_prefix().'lims_orders', ['contract_id' => $contract_id]);

		// reprice items: εφαρμόζουμε το contract (αν δόθηκε), αλλιώς καθαρίζουμε from_contract_id
		$this->load->model('lims/orders_model','orders_model');
		$lines = $this->orders_model->get_items($id);

		if ($contract_id) {
			// Φέρε τις τιμές του συμβολαίου σε map: [item_id=>fixed_price, currency]
			$prices = $this->db->select('item_id,fixed_price,currency')
							   ->where('contract_id', $contract_id)
							   ->get(db_prefix().'lims_contract_prices')->result();
			$priceMap = [];
			foreach ($prices as $p) {
				$priceMap[(int)$p->item_id] = ['rate'=>(float)$p->fixed_price,'currency'=>(string)$p->currency];
			}

			foreach ($lines as $ln) {
				$iid = (int)($ln->item_id ?? 0);
				if ($iid && isset($priceMap[$iid])) {
					$this->db->where('id', (int)$ln->id)->update(db_prefix().'lims_order_items', [
						'from_contract_id'    => $contract_id,
						'unit_price'          => $priceMap[$iid]['rate'],
						'fixed_price_applied' => 1,
					]);
				} else {
					// δεν έχει fixed price → καθάρισε flags, κράτα base snapshot
					$this->db->where('id', (int)$ln->id)->update(db_prefix().'lims_order_items', [
						'from_contract_id'    => $contract_id,
						'fixed_price_applied' => 0,
					]);
				}
			}
		} else {
			// Καθαρισμός συμβολαίου από τις γραμμές
			$this->db->where('order_id', $id)->update(db_prefix().'lims_order_items', [
				'from_contract_id'    => null,
				'fixed_price_applied' => 0,
			]);
		}

		// activity log
		if (isset($this->orders_model)) {
			$this->orders_model->add_activity($id, 'contract_updated', 'Contract set to '.($contract_id ?: 'none'), [
				'contract_id' => $contract_id,
			]);
		}

		echo json_encode(['success' => true]); die;
	}

	
	public function inline_update_items($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) { ajax_access_denied(); }
		$id = (int)$id;

		// 1) Μάζεψε επιλογές
		$selected = [];

		// (A) pick_*[] υποστήριξη
		foreach ((array)$this->input->post('pick_panels') as $pid) {
			$pid = (int)$pid; if ($pid>0) $selected[] = ['type'=>'panel','id'=>$pid];
		}
		foreach ((array)$this->input->post('pick_analyses') as $aid) {
			$aid = (int)$aid; if ($aid>0) $selected[] = ['type'=>'analysis','id'=>$aid];
		}
		foreach ((array)$this->input->post('pick_cultures') as $cid) {
			$cid = (int)$cid; if ($cid>0) $selected[] = ['type'=>'culture','id'=>$cid];
		}

		// (B) παλιό schema: lines[*][checked], lines[*][type], lines[*][id]
		$linesPost = $this->input->post('lines');
		if (is_array($linesPost)) {
			foreach ($linesPost as $r) {
				$checked = isset($r['checked']) ? (string)$r['checked'] : '';
				if ($checked !== '1' && $checked !== 1) { continue; }
				$type = isset($r['type']) ? trim((string)$r['type']) : '';
				$sid  = isset($r['id'])   ? (int)$r['id'] : 0;
				if ($sid>0 && in_array($type,['panel','analysis','culture'],true)) {
					$selected[] = ['type'=>$type,'id'=>$sid];
				}
			}
		}

		// 2) Βρες currency για snapshots
		$order = $this->orders_model->get($id);
		if (!$order) { echo json_encode(['success'=>false]); die; }
		$currency_id = $this->orders_model->get_order_currency_id((int)$order->client_id);

		// 3) Αντικατάσταση items (safe: μέσα σε transaction)
		$this->db->trans_begin();

		$this->db->where('order_id',$id)->delete(db_prefix().'lims_order_items');

		foreach ($selected as $row) {
			$type = $row['type'];
			$sid  = (int)$row['id'];

			if ($type === 'panel') {
				$panel = $this->panels_model->get($sid); if (!$panel) continue;
				$item  = $panel->item_id ? $this->invoice_items_model->get($panel->item_id) : null;
				$snap  = $this->orders_model->build_snapshot_from_item($item, $panel->name, $currency_id, $order->contract_id ?? null, $panel->id, 'panel');
				$this->orders_model->add_order_item($id, $snap);

			} elseif ($type === 'analysis') {
				$an = $this->analyses_model->get($sid); if (!$an) continue;
				$item = $an->item_id ? $this->invoice_items_model->get($an->item_id) : null;
				$snap = $this->orders_model->build_snapshot_from_item($item, $an->name, $currency_id, $order->contract_id ?? null, $an->id, 'analysis');
				$this->orders_model->add_order_item($id, $snap);

			} elseif ($type === 'culture') {
				$cu = $this->cultures_model->get($sid); if (!$cu) continue;
				$item = $cu->item_id ? $this->invoice_items_model->get($cu->item_id) : null;
				$snap = $this->orders_model->build_snapshot_from_item($item, $cu->name, $currency_id, $order->contract_id ?? null, $cu->id, 'culture');
				$this->orders_model->add_order_item($id, $snap);
			}
		}

		$ok = $this->db->trans_status();
		if ($ok) {
			$this->db->trans_commit();
			$this->orders_model->add_activity($id, 'items_updated', _l('lims_saved'));
		} else {
			$this->db->trans_rollback();
		}

		echo json_encode(['success'=>(bool)$ok]); die;
	}
	public function inline_items_picker($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) { ajax_access_denied(); }
		$id = (int)$id;

		$this->load->model('lims/panels_model','panels_model');
		$this->load->model('lims/analyses_model','analyses_model');
		$this->load->model('lims/cultures_model','cultures_model');
		$this->load->model('lims/partners_model','partners_model');
		$this->load->model('lims/sync_model','sync_model');
		$this->load->model('lims/orders_model','orders_model');

		$data = [];
		$data['order_id'] = $id;
		$data['panels']   = $this->panels_model->all();
		$data['analyses'] = $this->analyses_model->all();
		$data['cultures'] = $this->cultures_model->all();

		$lines = $this->orders_model->get_items($id);
		$pre = ['panel'=>[], 'analysis'=>[], 'culture'=>[]];
		foreach ($lines as $ln) {
			$st = (string)$ln->source_type;
			if (isset($pre[$st])) { $pre[$st][] = (int)$ln->source_id; }
		}
		$data['preselected'] = $pre;

		$this->load->view('lims/admin/orders/_items_picker_modal_body', $data);
	}
	public function ajax_create_appointment_inline($order_id)
	{
		if (!has_permission('lims', '', 'appointments') && !has_permission('lims', '', 'admin')) {
			ajax_access_denied();
		}

		// --- JSON hardening ---
		while (ob_get_level() > 0) { @ob_end_clean(); }
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_content_type('application/json; charset=utf-8');

		$order_id = (int)$order_id;
		$order    = $this->orders_model->get($order_id);
		if (!$order) {
			echo json_encode(['success'=>false,'message'=>'Order not found']); exit;
		}

		$this->load->model('lims/appointments_model', 'appointments_model');

		$ts_str        = trim((string)$this->input->post('appointment_at'));
		$visit_type    = in_array($this->input->post('visit_type'), ['lab','home'], true) ? $this->input->post('visit_type') : 'lab';
		$assigned      = (int)$this->input->post('assigned_staff');
		$location_text = trim((string)$this->input->post('location_text'));
		$lat           = $this->input->post('lat');
		$lng           = $this->input->post('lng');
		$notes         = trim((string)$this->input->post('notes'));
		$create_task   = (int)$this->input->post('create_task') === 1;

		if ($ts_str === '') { echo json_encode(['success'=>false,'message'=>'Date/time required']); exit; }

		$appt_dt  = strtotime($ts_str) ?: time();
		$appt_iso = date('Y-m-d H:i:s', $appt_dt);
		$due_date = date('Y-m-d', $appt_dt);

		$appt_id = $this->appointments_model->add([
			'client_id'      => (int)$order->client_id,
			'order_id'       => $order_id,
			'appointment_at' => $appt_iso,
			'visit_type'     => $visit_type,
			'location_text'  => $location_text !== '' ? $location_text : null,
			'lat'            => is_numeric($lat) ? (float)$lat : null,
			'lng'            => is_numeric($lng) ? (float)$lng : null,
			'assigned_staff' => $assigned ?: null,
			'notes'          => $notes !== '' ? $notes : null,
			'status'         => 'confirmed',
		]);

		if (!$appt_id) { echo json_encode(['success'=>false,'message'=>'Could not create appointment']); exit; }

		$this->orders_model->add_activity(
			$order_id,'appointment_created','Appointment created',['appointment_id'=>(int)$appt_id]
		);

		// ----- Optional: create linked Task (use data['assignees']) -----
		$task_id = null;
		if ($create_task) {
			$this->load->model('tasks_model');

			$assignees = [];
			if ($assigned > 0) { $assignees[] = (int)$assigned; }

			$taskData = [
				'name'         => 'Appointment for Order #'.$order_id.' ('.($visit_type === 'home' ? 'Home' : 'Lab').')',
				'description'  => $notes ?: ($location_text ? 'Location: '.$location_text : ''),
				'rel_type'     => 'customer',
				'rel_id'       => (int)$order->client_id,

				// Προσοχή: Perfex περιμένει Y-m-d
				'startdate'    => date('Y-m-d'),
				'duedate'      => $due_date,   // ίδιο με το appointment

				'priority'     => 2,
				'status'       => 1,           // not started
				// ΜΗΝ βάζεις repeat_every αν δεν υπάρχει επανάληψη
				'addedfrom'    => get_staff_user_id(),
				'is_public'    => 0,
				'billable'     => 0,

				// <-- ΕΔΩ οι assignees σωστά:
				'assignees'    => $assignees,
			];

			// 2ο arg κενό/προεπιλογές (μην περνάς assignees εδώ)
			$task_id = $this->tasks_model->add($taskData, []);
			if ($task_id) {
				$this->db->where('id', $appt_id)
						 ->update(db_prefix().'lims_appointments', ['task_id' => (int)$task_id]);
			}
		}
		// Auto status bump -> Appointment
		$this->bump_order_status($order_id, 'appointment');

		echo json_encode([
			'success'        => true,
			'appointment_id' => (int)$appt_id,
			'task_created'   => (bool)$task_id,
			'task_id'        => $task_id ? (int)$task_id : null,
		]);
		exit; // <-- ΜΗΝ το ξεχάσεις
	}
	public function materialize_samples($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) {
			access_denied('Lims');
		}

		$id = (int)$id;
		$order = $this->orders_model->get($id);
		if (!$order) {
			set_alert('danger', _l('lims_error_generic'));
			return redirect(admin_url('lims/orders'));
		}

		// (προαιρετικό) φρόντισε να υπάρχει barcode στο order για να το κληρονομήσουν τα samples
		$this->orders_model->ensure_barcode($id);

		$ok = $this->orders_model->materialize_samples_and_tests($id);
		if ($ok) {
			// Auto status bump -> Samples
			$this->bump_order_status($id, 'samples');

			// activity log
			$this->orders_model->add_activity($id, 'samples_materialized', 'Samples & Tests materialized from order items');
			set_alert('success', _l('lims_saved') . ' — Samples & Tests generated.');
		} else {
			set_alert('danger', _l('lims_error_generic'));
		}

		return redirect(admin_url('lims/orders/view/'.$id));
	}

	public function ajax_sample_collected()
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) { ajax_access_denied(); }

		$sample_id = (int)$this->input->post('sample_id');
		if (!$sample_id) { echo json_encode(['success'=>false]); die; }

		$p = db_prefix();

		$now = date('Y-m-d H:i:s');

		$ok = $this->db->where('id',$sample_id)->update($p.'lims_samples', [
			'status'       => 'collected',
			'collected_at' => $now,
			'updated_at'   => $now,
		]);

		if ($ok) {
			$s = $this->db->where('id',$sample_id)->get($p.'lims_samples')->row();
			if ($s && method_exists($this->orders_model,'add_activity')) {
				$this->orders_model->add_activity((int)$s->order_id, 'sample_collected', 'Sample marked as collected', [
					'sample_id' => (int)$sample_id,
				]);
			}

			// Partner sync: enqueue samples.status so cron will update LAB B
			if ($s && !empty($s->order_id)) {
				try {
					$this->load->model('lims/sync_model','sync_model');
					$this->sync_model->enqueue_samples_status((int)$s->order_id);
				} catch (Throwable $e) {
					log_activity('LIMS Sync enqueue samples.status error: '.$e->getMessage());
				}
			}
		}

		echo json_encode(['success'=>(bool)$ok]); die;
	}

	public function toggle_sample_collected()
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) {
			ajax_access_denied();
		}

		$sample_id = (int)$this->input->post('sample_id');
		$to_collected = ((int)$this->input->post('collected') === 1);

		$tbl = db_prefix().'lims_samples';
		$now = date('Y-m-d H:i:s');

		$data = [
			'status'     => $to_collected ? 'collected' : 'pending',
			'updated_at' => $now,
		];
		$data['collected_at'] = $to_collected ? $now : null;

		$ok = $this->db->where('id',$sample_id)->update($tbl, $data);

		if ($ok) {
			$p = db_prefix();
			$s = $this->db->where('id',$sample_id)->get($p.'lims_samples')->row();
			if ($s && method_exists($this->orders_model,'add_activity')) {
				$this->orders_model->add_activity((int)$s->order_id,
					$to_collected ? 'sample_collected' : 'sample_uncollected',
					$to_collected ? 'Sample marked as collected' : 'Sample marked as pending',
					['sample_id'=>(int)$sample_id]
				);
			}

			// Partner sync: enqueue samples.status
			if ($s && !empty($s->order_id)) {
				try {
					$this->load->model('lims/sync_model','sync_model');
					$this->sync_model->enqueue_samples_status((int)$s->order_id);
				} catch (Throwable $e) {
					log_activity('LIMS Sync enqueue samples.status error: '.$e->getMessage());
				}
			}
		}

		echo json_encode([
			'success' => (bool)$ok,
			'collected_at' => $to_collected ? $now : null,
			'status' => $data['status'],
		]);
		die;
	}


	public function print_pdf($id)
	{
		$id = (int)$id;

		$order = $this->orders_model->get($id);
		if (!$order) { show_404(); }

		// φέρε και τις γραμμές (snapshots)
		$order->lines = $this->orders_model->get_items($id);

		// χρειάζονται για το app_pdf()
		$this->load->helper('pdf');        // core perfex helper
		$this->load->helper('lims_pdf');   // ο helper που βάλαμε πιο πάνω

		// --- EXTRA DATA FOR PDF ---

		// 1) Subject + Client + Primary contact (subject-aware)
		$this->load->model('clients_model');

		$subject = null;
		if (!empty($order->subject_id)) {
			$subject = $this->db
				->where('id', (int)$order->subject_id)
				->get(db_prefix().'lims_subjects')
				->row();
		}

		// Βρες ποιο client_id θα χρησιμοποιηθεί:
		// - πρώτα κοιτάμε το order->client_id (αν έχει οριστεί ρητά)
		// - αλλιώς, αν το subject έχει client_id, χρησιμοποιούμε αυτό
		$resolved_client_id = null;
		if (!empty($order->client_id)) {
			$resolved_client_id = (int)$order->client_id;
		} elseif ($subject && !empty($subject->client_id)) {
			$resolved_client_id = (int)$subject->client_id;
		}

		$client  = null;
		$primary = null;

		if ($resolved_client_id) {
			$client = $this->clients_model->get($resolved_client_id);
			if ($client) {
				$primary = $this->db->where('userid', (int)$client->userid)
									->where('is_primary', 1)
									->get(db_prefix().'contacts')->row();
			}
		}

		// Περά τα στο $order για χρήση μέσα στο PDF template
		$order->subject          = $subject;
		$order->client           = $client;
		$order->primary          = $primary;
		$order->resolved_client_id = $resolved_client_id; // αν το χρειαστείς στο template

		// 2) Contract (from order or first line with from_contract_id)
		$contractId = null;
		if (!empty($order->contract_id)) $contractId = (int)$order->contract_id;
		if (!$contractId && !empty($order->lines)) {
			foreach ($order->lines as $ln) {
				if (!empty($ln->from_contract_id)) { $contractId = (int)$ln->from_contract_id; break; }
			}
		}
		$contract = null;
		if ($contractId) {
			$contract = $this->db->where('id',$contractId)->get(db_prefix().'lims_contracts')->row();
		}
		$order->contract = $contract;

		// 3) Split lines + panel children (analyses & cultures)
		$panels   = [];
		$analyses = [];
		$cultures = [];
		foreach ($order->lines as $ln) {
			if ($ln->source_type === 'panel')   $panels[]   = (int)$ln->source_id;
			if ($ln->source_type === 'analysis')$analyses[] = (int)$ln->source_id;
			if ($ln->source_type === 'culture') $cultures[] = (int)$ln->source_id;
		}
		$panels   = array_values(array_unique($panels));
		$analyses = array_values(array_unique($analyses));
		$cultures = array_values(array_unique($cultures));

		// Panel children από tbllims_panel_items (έχουμε συμφωνήσει να περιέχει και cultures)
		$panelChildren = []; // [panel_id => ['analyses'=>[], 'cultures'=>[]]]
		if (!empty($panels)) {
			$p = db_prefix();

			// Παίρνουμε ΟΛΑ τα rows του panel_items και διακρίνουμε από το ποια στήλη είναι μη-NULL
			$rows = $this->db->select('pi.panel_id, pi.analysis_id, pi.culture_id')
				->from($p.'lims_panel_items AS pi')
				->where_in('pi.panel_id', $panels)
				->order_by('pi.sort_order','ASC')
				->get()->result();

			foreach ($rows as $r) {
				$pid = (int)$r->panel_id;
				if (!isset($panelChildren[$pid])) {
					$panelChildren[$pid] = ['analyses'=>[], 'cultures'=>[]];
				}
				if (!empty($r->culture_id)) {
					$panelChildren[$pid]['cultures'][] = (int)$r->culture_id;
				} elseif (!empty($r->analysis_id)) {
					$panelChildren[$pid]['analyses'][] = (int)$r->analysis_id;
				}
			}
		}
		$order->panel_children = $panelChildren;

		// 4) Analyses & Cultures details για χρήση στο PDF
		$analysisDetails = [];
		if (!empty($panelChildren)) {
			$allAnalysisIds = [];
			foreach ($panelChildren as $g) { $allAnalysisIds = array_merge($allAnalysisIds, $g['analyses']); }
			$allAnalysisIds = array_values(array_unique(array_filter($allAnalysisIds)));
			if ($allAnalysisIds) {
				$rows = $this->db->select('id, name, code, sample_type_id')
					->where_in('id', $allAnalysisIds)
					->get(db_prefix().'lims_analyses')->result();
				foreach ($rows as $r) { $analysisDetails[(int)$r->id] = $r; }
			}
		}

		$cultureDetails = [];
		if (!empty($panelChildren)) {
			$allCultureIds = [];
			foreach ($panelChildren as $g) { $allCultureIds = array_merge($allCultureIds, $g['cultures']); }
			$allCultureIds = array_values(array_unique(array_filter($allCultureIds)));
			if ($allCultureIds) {
				$rows = $this->db->select('id, name, code, method, sample_type_id')
					->where_in('id', $allCultureIds)
					->get(db_prefix().'lims_cultures')->result();
				foreach ($rows as $r) { $cultureDetails[(int)$r->id] = $r; }
			}
		}

		$order->analysis_details = $analysisDetails;
		$order->culture_details  = $cultureDetails;

		// 6) Samples for order
		$samples = $this->db->select('s.*, st.name AS st_name, st.code AS st_code, st.min_volume')
			->from(db_prefix().'lims_samples s')
			->join(db_prefix().'lims_sample_types st','st.id=s.sample_type_id','left')
			->where('s.order_id', $id)
			->order_by('s.id','ASC')
			->get()->result();
		$order->samples = $samples;

		// 7) Latest appointment for this order (αν υπάρχει)
		$appointment = $this->db->where('order_id',$id)
			->order_by('appointment_at','DESC')->limit(1)
			->get(db_prefix().'lims_appointments')->row();
		$order->appointment = $appointment;

		try {
			$pdf = order_pdf($order);
		} catch (Exception $e) {
			if (strpos($e->getMessage(), 'Unable to get the size of the image') !== false) {
				show_pdf_unable_to_get_image_size_error();
			}
			set_alert('danger', $e->getMessage());
			redirect(admin_url('lims/orders/view/'.$id));
			return;
		}

		// Επιλογές εξόδου (ίδιο pattern με τα υπόλοιπα PDF)
		$type = 'D';
		if ($this->input->get('output_type')) $type = $this->input->get('output_type');
		if ($this->input->get('print'))       $type = 'I';

		// ΑΠΟΛΥΤΑ κρίσιμο για "λευκή σελίδα": καθάρισε όλα τα output buffers + κλείσε gzip
		while (ob_get_level() > 0) { @ob_end_clean(); }
		if (function_exists('apache_setenv')) { @apache_setenv('no-gzip', '1'); }
		@ini_set('zlib.output_compression', 'Off');

		$pdf->Output('Order-'.$id.'.pdf', $type);
	}



	public function pdf_sample_labels($order_id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) { access_denied('Lims'); }
		$order_id = (int)$order_id;

		$p = db_prefix();
		// φέρε samples με type/min volume
		$samples = $this->db->select("s.*, st.name AS st_name, st.code AS st_code, st.min_volume AS st_min_volume")
			->from("{$p}lims_samples s")
			->join("{$p}lims_sample_types st","st.id=s.sample_type_id","left")
			->where('s.order_id',$order_id)
			->order_by('s.id','ASC')
			->get()->result();

		if (!$samples) { show_404(); }

		// Layout: 3x10 ετικέτες (A4) – προσαρμόσιμο
		$cols = 3; $rows = 10;
		$marginL = 6; $marginT = 10;   // mm
		$cellW = 63; $cellH = 27;      // περίπου Avery L4737 (adjust κατά βούληση)
		$pad = 2;

		// Load TCPDF directly from third_party
		$tcpdfPath = APPPATH.'third_party/tcpdf/tcpdf.php';
		if (!file_exists($tcpdfPath)) {
			show_error('TCPDF not found at: '.$tcpdfPath);
		}
		require_once($tcpdfPath);

		// τώρα μπορείς κανονικά:
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->SetCreator('LIMS');
		$pdf->SetTitle('Sample Labels (Order '.$order_id.')');
		$pdf->SetMargins(0, 0, 0);
		$pdf->SetAutoPageBreak(false, 0);
		$pdf->AddPage();

		$style = ['position'=>'', 'align'=>'L', 'stretch'=>false, 'fitwidth'=>true, 'border'=>false, 'padding'=>0, 'fgcolor'=>[0,0,0], 'bgcolor'=>false, 'text'=>false];

		$i = 0;
		foreach ($samples as $sp) {
			// 1 label per sample — αν θες 2 labels ανά sample, κάνε loop 2 φορές
			$labelsForThis = 1;

			for ($rep=0; $rep<$labelsForThis; $rep++) {
				$col = $i % $cols;
				$row = floor($i / $cols) % $rows;

				if ($i > 0 && $row == 0 && $col == 0) { $pdf->AddPage(); } // νέα σελίδα κάθε 30 labels

				$x = $marginL + $col * $cellW;
				$y = $marginT + $row * $cellH;

				// πλαίσιο (debug): $pdf->Rect($x, $y, $cellW, $cellH);

				// τίτλος
				$pdf->SetFont('helvetica', 'B', 11);
				$pdf->SetXY($x + $pad, $y + $pad);
				$title = !empty($sp->sample_uid) ? $sp->sample_uid : ('S'.$order_id.'-'.$sp->id);
				$pdf->Cell($cellW - 2*$pad, 5, $title, 0, 1, 'L', false, '', 0, false, 'T', 'M');

				// type + min volume
				$pdf->SetFont('helvetica', '', 9);
				$pdf->SetXY($x + $pad, $y + $pad + 6);
				$line2 = trim(($sp->st_name ?: '-') . (!empty($sp->st_code) ? ' ('.$sp->st_code.')' : ''));
				$pdf->Cell($cellW - 2*$pad, 5, $line2, 0, 1, 'L');

				$pdf->SetXY($x + $pad, $y + $pad + 12);
				$mv = isset($sp->st_min_volume) && $sp->st_min_volume !== null ? (float)$sp->st_min_volume.' ml' : '-';
				$pdf->Cell($cellW - 2*$pad, 5, 'Min Volume: '.$mv, 0, 1, 'L');

				// barcode (CODE128): sample barcode αν υπάρχει, αλλιώς του order
				$code = $sp->barcode ?: '';
				if (!$code) {
					// πάρε order barcode fallback
					$order = $this->orders_model->get($order_id);
					$code = $order && $order->order_barcode ? $order->order_barcode : $title;
				}
				$pdf->write1DBarcode($code, 'C128', $x + $pad, $y + $cellH - 12, $cellW - 2*$pad, 12, 0.4, $style, 'N');

				$i++;
			}
		}

		$pdf->Output('sample-labels-order-'.$order_id.'.pdf', 'I');
	}
	

	public function print_sample_labels($order_id)
	{
		if (!has_permission('lims', '', 'manage_samples') && !has_permission('lims','','admin')) {
			access_denied('LIMS');
		}

		$order_id = (int)$order_id;
		$order = $this->orders_model->get($order_id);
		if (!$order) { show_error('Order not found'); }

		// Φέρε samples + ελαφρύ meta
		$p = db_prefix();
		$samples = $this->db->select('s.*, st.name AS sample_type_name')
			->from($p.'lims_samples AS s')
			->join($p.'lims_sample_types AS st','st.id=s.sample_type_id','left')
			->where('s.order_id', $order_id)
			->order_by('s.id','ASC')
			->get()->result();

		if (!$samples) {
			set_alert('warning', _l('no_items_found') ?: 'No samples found.');
			return redirect(admin_url('lims/orders/view/'.$order_id));
		}

		// Φέρε ανά sample τις analysis names (από lims_tests -> lims_analyses)
		$testsBySample = [];
		$tests = $this->db->select('t.sample_id, a.name AS analysis_name')
			->from($p.'lims_tests AS t')
			->join($p.'lims_analyses AS a','a.id=t.analysis_id','left')
			->where_in('t.sample_id', array_map(fn($s)=> (int)$s->id, $samples))
			->order_by('t.id','ASC')
			->get()->result();

		foreach ($tests as $t) {
			$sid = (int)$t->sample_id;
			if (!isset($testsBySample[$sid])) $testsBySample[$sid] = [];
			if (!empty($t->analysis_name))    $testsBySample[$sid][] = (string)$t->analysis_name;
		}

		// embed analysis list στο sample object (πρώτο όνομα για label)
		foreach ($samples as $s) {
			$sid = (int)$s->id;
			$s->first_analysis_name = !empty($testsBySample[$sid]) ? $testsBySample[$sid][0] : '';
		}

		$payload = [
			'order'   => $order,
			'samples' => $samples,
		];

		$this->load->helper('lims/lims_pdf');
		try {
			$pdf = lims_sample_labels_pdf($payload);
		} catch (Exception $e) {
			if (strpos($e->getMessage(), 'Unable to get the size of the image') !== false) {
				show_pdf_unable_to_get_image_size_error();
			}
			show_error($e->getMessage());
		}

		$filename = 'Order-'.$order_id.'-Sample-Labels.pdf';
		ob_end_clean(); // καθάρισε buffer για TCPDF
		$type = $this->input->get('print') ? 'I' : 'D';
		$pdf->Output($filename, $type);
	}
	public function report_pdf($order_id)
	{
		if (!has_permission('lims', '', 'view')) {
			access_denied('Lims');
		}

		$order_id = (int)$order_id;
		if ($order_id <= 0) {
			show_404();
		}

		// NEW: use Report_pdf_model (not tests_model)
		$this->load->model('lims/report_pdf_model');

		$payload = $this->report_pdf_model->get_order_report_payload($order_id);
		if (!$payload || empty($payload['order'])) {
			show_404();
		}

		// IMPORTANT: σωστό case/path για Linux
		$this->load->library('lims/Lims_order_report_pdf', $payload);

		/** @var Lims_order_report_pdf $pdf */
		$pdf = $this->lims_order_report_pdf->prepare();

		$file_name = slug_it('lims-report-order-' . (int)$payload['order']->id) . '.pdf';

		// Ασφάλεια για “λευκή σελίδα” από buffers/gzip
		while (ob_get_level() > 0) { @ob_end_clean(); }
		if (function_exists('apache_setenv')) { @apache_setenv('no-gzip', '1'); }
		@ini_set('zlib.output_compression', 'Off');

		// Default inline. Download only if ?download=1
		$type = $this->input->get('download') ? 'D' : 'I';
		$pdf->Output($file_name, $type);
	}

	public function print_report($order_id)
	{
		// Keep backward endpoint
		return $this->report_pdf($order_id);
	}


	public function delete($id)
	{
		if (!has_permission('lims','','manage_orders') && !has_permission('lims','','admin')) {
			access_denied('Lims');
		}

		$id = (int)$id;
		if (!$id) {
			return redirect(admin_url('lims/orders'));
		}

		$p = db_prefix();

		// ---- Έλεγχος για συνδεδεμένα αντικείμενα ----
		$hasAppointments = (int)$this->db
			->where('order_id', $id)
			->count_all_results($p.'lims_appointments');

		$hasSamples = (int)$this->db
			->where('order_id', $id)
			->count_all_results($p.'lims_samples');

		// Tests: ιδανικά έχουν στήλη order_id, αλλιώς ελέγχουμε μέσω sample_id
		$hasTests = 0;

		// Αν υπάρχει στήλη order_id στο lims_tests, χρησιμοποίησέ την
		if ($this->db->field_exists('order_id', $p.'lims_tests')) {
			$hasTests = (int)$this->db
				->where('order_id', $id)
				->count_all_results($p.'lims_tests');
		} else {
			// fallback: tests μέσω samples
			if ($hasSamples) {
				$sampleIds = $this->db->select('id')
					->where('order_id', $id)
					->get($p.'lims_samples')
					->result();

				if ($sampleIds) {
					$ids = array_map(function($r){ return (int)$r->id; }, $sampleIds);
					if (!empty($ids)) {
						$this->db->where_in('sample_id', $ids);
						$hasTests = (int)$this->db->count_all_results($p.'lims_tests');
					}
				}
			}
		}

		if ($hasAppointments || $hasSamples || $hasTests) {
			// Μήνυμα: δεν επιτρέπεται διαγραφή
			$msg = _l('lims_order_delete_has_children')
				?: 'This order cannot be deleted because it has linked appointments, samples or tests.';
			set_alert('warning', $msg);

			return redirect(admin_url('lims/orders/view/'.$id));
		}

		// ---- Ασφαλής διαγραφή (transaction) ----
		$this->db->trans_begin();

		// Σβήσε γραμμές order
		$this->db->where('order_id', $id)->delete($p.'lims_order_items');

		// Σβήσε activity log για το order (αν χρησιμοποιείς τέτοιο table)
		if ($this->db->table_exists($p.'lims_order_activity')) {
			$this->db->where('order_id', $id)->delete($p.'lims_order_activity');
		}

		// Σβήσε billing links (αν υπάρχουν)
		if ($this->db->table_exists($p.'lims_billing_links')) {
			$this->db->where('order_id', $id)->delete($p.'lims_billing_links');
		}

		// Τέλος, σβήσε το ίδιο το order
		$this->db->where('id', $id)->delete($p.'lims_orders');

		$ok = $this->db->trans_status();

		if ($ok) {
			$this->db->trans_commit();
			set_alert('success', _l('deleted', _l('lims_order')) ?: 'Order deleted successfully.');
		} else {
			$this->db->trans_rollback();
			set_alert('danger', _l('problem_deleting', _l('lims_order')) ?: 'Problem deleting order.');
		}

		return redirect(admin_url('lims/orders'));
	}

	public function create_estimate($id)
	{
		if (!has_permission('estimates', '', 'create')) {
			access_denied('Estimates');
		}

		$id    = (int)$id;
		$order = $this->orders_model->get($id);
		if (!$order) {
			set_alert('danger', _l('lims_error_generic'));
			return redirect(admin_url('lims/orders'));
		}

		$lines = $this->orders_model->get_items($id);
		if (!$lines) {
			set_alert('warning', _l('no_items_found') ?: 'No items found.');
			return redirect(admin_url('lims/orders/view/'.$id));
		}

		// Currency όπως στο create_invoice
		$currency_id = 0;
		foreach ($lines as $ln) {
			if (!empty($ln->currency_id)) {
				$currency_id = (int)$ln->currency_id;
				break;
			}
		}
		if (!$currency_id) {
			$def = $this->db->where('isdefault',1)->get(db_prefix().'currencies')->row();
			if ($def) { $currency_id = (int)$def->id; }
		}

		// Client
		$client = $this->db->where('userid', (int)$order->client_id)->get(db_prefix().'clients')->row();

		$est = [
			'clientid'        => (int)$order->client_id,
			'date'            => date('Y-m-d'),
			'expirydate'      => !empty($order->due_at) ? date('Y-m-d', strtotime($order->due_at)) : null,
			'currency'        => $currency_id,
			'newitems'        => [],
			'show_quantity_as'=> 1,
			'billing_street'  => (string)($client->address ?? ''),
			'billing_city'    => (string)($client->city ?? ''),
			'billing_state'   => (string)($client->state ?? ''),
			'billing_zip'     => (string)($client->zip ?? ''),
			'billing_country' => (int)($client->country ?? 0),
			'clientnote'      => '',
			'terms'           => '',
		];

		// μετατροπή order items -> estimate items
		$taxMap = function($id){
			if (!$id) return null;
			$t = get_instance()->db->where('id',(int)$id)->get(db_prefix().'taxes')->row();
			return $t ? ($t->name.'|'.(0+$t->taxrate)) : null;
		};

		foreach ($lines as $ln) {
			$taxes = [];
			$t1 = $taxMap($ln->tax1_id ?? null);
			$t2 = $taxMap($ln->tax2_id ?? null);
			if ($t1) $taxes[] = $t1;
			if ($t2) $taxes[] = $t2;

			$est['newitems'][] = [
				'description'      => $ln->name,
				'long_description' => '',
				'qty'              => (float)($ln->qty ?? 1),
				'unit'             => $ln->unit ?? '',
				'rate'             => (float)($ln->unit_price ?? 0),
				'order'            => 1,
				'taxname'          => $taxes,
			];
		}

		// Δημιουργία estimate
		$this->load->model('estimates_model');
		$estimate_id = $this->estimates_model->add($est);

		if (!$estimate_id) {
			set_alert('danger', _l('problem_creating').' '._l('estimate'));
			return redirect(admin_url('lims/orders/view/'.$id));
		}

		// Activity log
		if (method_exists($this->orders_model,'add_activity')) {
			$this->orders_model->add_activity(
				$id,
				'estimate_created',
				_l('lims_action_estimate_created'),
				['estimate_id' => (int)$estimate_id]
			);
		}

		set_alert('success', _l('estimate').' '._l('created_successfully'));
		// redirect στο estimate
		return redirect(admin_url('estimates/estimate/'.$estimate_id));
	}

}
