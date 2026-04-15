<?php defined('BASEPATH') or exit('No direct script access allowed');

class Appointments extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
            access_denied('Lims');
        }
        $this->load->model('lims/Appointments_model','appointments_model');
        $this->load->model('lims/Orders_model','orders_model');
        $this->load->model('clients_model');
        $this->load->model('staff_model');
        $this->load->model('tasks_model');
        $this->load->language('lims/lims');
    }

    public function index()
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			access_denied('Lims');
		}

		$range = $this->input->get('range') ?: 'upcoming';
		$from  = $this->input->get('from') ?: null;
		$to    = $this->input->get('to')   ?: null;

		// Αν δόθηκε custom range, το σεβόμαστε όπως είναι.
		if ($range !== 'custom') {
			[$from, $to] = $this->map_range_to_bounds($range);
		}

		$opts = [
			'from'          => $from ?: null,
			'to'            => $to ?: null,
			'upcoming_only' => ($range === 'upcoming'), // default
		];

		$data['title'] = _l('lims_appointments');
		$data['rows']  = $this->appointments_model->all($opts);
		$data['range'] = $range;
		$data['from']  = $from;
		$data['to']    = $to;

		$this->load->view('lims/admin/appointments/index', $data);
	}

	/** Helper: map προκαθορισμένα ranges σε from/to */
	private function map_range_to_bounds(string $range): array
	{
		$today = new DateTime('today');
		$y    = (int)$today->format('Y');
		$m    = (int)$today->format('m');

		switch ($range) {
			case 'last_month':
				$first = (new DateTime("first day of last month"))->format('Y-m-d');
				$last  = (new DateTime("last day of last month"))->format('Y-m-d');
				return [$first, $last];

			case 'last_2_months':
				$first = (new DateTime("first day of -2 month"))->format('Y-m-d');
				$last  = (new DateTime("last day of last month"))->format('Y-m-d');
				return [$first, $last];

			case 'this_year':
				return [sprintf('%d-01-01',$y), sprintf('%d-12-31',$y)];

			case 'last_year':
				return [sprintf('%d-01-01',$y-1), sprintf('%d-12-31',$y-1)];

			case 'next_month':
				$first = (new DateTime("first day of next month"))->format('Y-m-d');
				$last  = (new DateTime("last day of next month"))->format('Y-m-d');
				return [$first, $last];

			case 'custom':
				return [null, null];

			case 'upcoming':
			default:
				// upcoming: χωρίς fixed to, μόνο από σήμερα και μετά
				return [date('Y-m-d'), null];
		}
	}


    // Create / Edit form
    public function create($id = null)
    {
        $row = null;
		if ($id) {
			$row = $this->appointments_model->get($id);
			if(!$row){
				set_alert('danger', _l('lims_error_generic'));
				return redirect(admin_url('lims/appointments'));
			}
		}

		$order_id   = (int)($this->input->get('order_id')   ?: ($row->order_id   ?? 0));
		$subject_id = (int)($this->input->get('subject_id') ?: ($row->subject_id ?? 0));

		// φέρε όλα τα subjects (ή βάλε where client_id=... αν θες)
		$subjects = $this->db
			->order_by('id', 'asc') // άλλαξέ το σε 'name' αν χρειάζεται
			->get(db_prefix().'lims_subjects')
			->result();

		// orders του συγκεκριμένου subject
		$orders = [];
		if ($subject_id) {
			$orders = $this->db
				->where('subject_id', $subject_id)
				->order_by('id', 'DESC')
				->get(db_prefix().'lims_orders')
				->result();
		}

		// αν θέλουμε prefill address/lat/lng από subject
		$subject_addr = '';
		$subject_lat  = null;
		$subject_lng  = null;
		if ($subject_id) {
			$sub = $this->db->where('id', $subject_id)
							->get(db_prefix().'lims_subjects')
							->row();
			if ($sub) {
				// προσαρμόζεις τα ονόματα πεδίων εδώ
				$subject_addr = $sub->address ?? '';
				$subject_lat  = $sub->lat     ?? null;
				$subject_lng  = $sub->lng     ?? null;
			}
		}

		$data['title']        = $id ? _l('edit').' #' . (int)$id : _l('add_new');
		$data['row']          = $row;
		$data['subjects']     = $subjects;
		$data['orders']       = $orders;
		$data['subject_id']   = $subject_id;
		$data['order_id']     = $order_id;
		$data['subject_addr'] = $subject_addr;
		$data['subject_lat']  = $subject_lat;
		$data['subject_lng']  = $subject_lng;
		$data['staff']        = $this->staff_model->get('', ['active'=>1]);

		$this->load->view('lims/admin/appointments/create', $data);


    }

    // Save handler (add/update)
    public function save($id = null)
    {
        $post = $this->input->post(null, true);

		$subject_id   = (int)($post['subject_id'] ?? 0);
		$link_mode    = $post['link_mode'] ?? 'none'; // none|existing|new
		$link_orderid = (int)($post['order_id'] ?? 0);
		$visit_type   = in_array(($post['visit_type'] ?? 'lab'), ['lab','home'], true) ? $post['visit_type'] : 'lab';
		$appt_at      = $post['appointment_at'] ?? '';
		$location     = trim($post['location_text'] ?? '');
		$assigned     = (int)($post['assigned_staff'] ?? 0);
		$notes        = trim($post['notes'] ?? '');
		$make_task    = isset($post['make_task']);
		$task_id      = null;
		$lat    	  = isset($post['lat']) && $post['lat'] !== '' ? (float)$post['lat'] : null;
		$lng    	  = isset($post['lng']) && $post['lng'] !== '' ? (float)$post['lng'] : null;

		// βρες client_id από το subject (αν υπάρχει σύνδεση)
		$client_id = 0;
		$subject   = null;
		if ($subject_id) {
			$subject = $this->db->where('id', $subject_id)
								->get(db_prefix().'lims_subjects')
								->row();
			if ($subject && !empty($subject->client_id)) {
				$client_id = (int)$subject->client_id;
			}
		}

		if(!$subject_id || empty($appt_at)){
			set_alert('danger', _l('lims_error_generic'));
			return redirect(admin_url('lims/appointments'));
		}


        // --- LINKING LOGIC ---
		$order_id = null;
		if ($link_mode === 'existing') {
			$order_id = (int)$this->input->post('order_id') ?: null;
		} elseif ($link_mode === 'new') {
			// Δημιουργία Order κατευθείαν από appointment
			if (method_exists($this->orders_model, 'create_from_appointment')) {
				// προσαρμόζεις τη signature στο Orders_model:
				// create_from_appointment($subject_id, $appointment_at, $notes)
				$order_id = $this->orders_model->create_from_appointment($subject_id, $appt_at, $notes);
			} else {
				// fallback: απευθείας insert
				$order_data = [
					'subject_id'  => $subject_id,
					'client_id'   => $client_id ?: null,
					'status'      => 'submitted',
					'priority'    => 0,
					'received_at' => $appt_at,
					'notes'       => '[Auto] Order created from appointment'
									 . ($notes ? (' - '.$notes) : ''),
				];
				$order_id = $this->orders_model->add($order_data);
			}
		}

		
        $payload = [
			'subject_id'     => $subject_id,
			'client_id'      => $client_id ?: null, // optional, για reports/tasks
			'order_id'       => $order_id,
			'appointment_at' => $appt_at,
			'visit_type'     => $visit_type,
			'location_text'  => $location ?: null,
			'status'         => $post['status'] ?? 'pending',
			'assigned_staff' => $assigned ?: null,
			'notes'          => $notes ?: null,
			'lat'            => $lat,
			'lng'            => $lng,
		];


        if (!$id) {
            $appt_id = $this->appointments_model->add($payload);
            if($appt_id){
                // create Task (optional)
				if ($make_task) {
					// ΥΠΟΧΡΕΩΤΙΚΑ/ΑΣΦΑΛΗ πεδία για Tasks_model->add()
					$taskTitleSubject = $subject && !empty($subject->full_name)
						? $subject->full_name
						: ('Subject #'.$subject_id);

					$taskData = [
						'name'        => ($visit_type==='home' ? '[Home Visit] ' : '[Lab Visit] ')
										 . $taskTitleSubject.' — ' . _dt($appt_at),
						'rel_id'      => $client_id ?: null,   // πάντα client για core tasks
						'rel_type'    => 'customer',
						'startdate'   => date('Y-m-d', strtotime($appt_at)), // μορφή που θέλει το core
						'duedate'     => date('Y-m-d', strtotime($appt_at)),
						'priority'    => 2,
						'repeat_every'=> 0,
						'is_public'   => 0,
						'description' => "Appointment ID: {$appt_id}\n"
										. ($location ? "Location: {$location}\n" : '')
										. ($notes ? "Notes: {$notes}\n" : ''),
					];

					// 1) Περνάμε assignees ΜΕΣΑ στο add() (πολλές εκδόσεις του Perfex το υποστηρίζουν)
					if ($assigned) {
						$taskData['assignees'] = [$assigned]; // απλός πίνακας IDs
					}

					$task_id = $this->tasks_model->add($taskData);

					// 2) Fallback: αν το add() ΔΕΝ έκανε assign, δοκίμασε add_task_assignees με array-of-objects schema
					if ($task_id && $assigned) {
						if (method_exists($this->tasks_model, 'add_task_assignees')) {
							// Κάποιες εκδόσεις θέλουν [["assigneeid"=>ID], ...]
							$assigneesPayload = [ ['assigneeid' => (int)$assigned] ];
							try {
								$this->tasks_model->add_task_assignees($task_id, $assigneesPayload);
							} catch (\Throwable $e) {
								// Αν αποτύχει, αγνόησέ το (έχει ήδη ανατεθεί από το add ή δεν υποστηρίζεται αυτή η υπογραφή)
							}
						}
					}

					// Αποθήκευσε το task_id στο ραντεβού ΜΟΝΟ αν δημιουργήθηκε επιτυχώς
					if ($task_id) {
						$this->appointments_model->update($appt_id, ['task_id' => $task_id]);
					} else {
						// Προαιρετικό: ενημέρωσε τον χρήστη ότι δεν δημιουργήθηκε Task
						// set_alert('warning', _l('problem_creating').' (Task)');
					}
				}

                // activity log on order (if any)
                if ($order_id) {
					// activity log
					$this->orders_model->add_activity($order_id, 'appointment_created', 'Appointment scheduled', [
						'appointment_id'=>$appt_id,
						'visit_type'    =>$visit_type,
						'assigned_staff'=>$assigned
					]);

					// optional: αν το order είναι ακόμα σε αρχικό στάδιο, γίνε "appointment"
					$o = $this->orders_model->get($order_id);
					if ($o) {
						$current = (string)($o->status ?? 'draft');
						// μόνο αν είναι σε "early" status
						$early = ['draft','submitted','in_progress'];
						if (in_array($current, $early, true)) {
							$this->db->where('id', $order_id)
									 ->update(db_prefix().'lims_orders', ['status' => 'appointment']);

							$this->orders_model->add_activity(
								$order_id,
								'status_changed',
								'Order status updated to appointment from appointment create',
								['from' => $current, 'to' => 'appointment', 'appointment_id' => $appt_id]
							);
						}
					}
				}

                set_alert('success', _l('added_successfully', _l('lims_appointment')));
                // redirect back smartly
                if ((int)($post['return_to_order'] ?? 0) === 1 && $order_id) {
                    return redirect(admin_url('lims/orders/view/'.$order_id));
                }
                return redirect(admin_url('lims/appointments'));
            }
            set_alert('danger', _l('problem_creating'));
            return redirect(admin_url('lims/appointments/show/'.$appt_id));
        } else {
            $ok = $this->appointments_model->update($id, $payload);
            if($ok){
                // keep task in sync (only assignee & dates/desc)
                $row = $this->appointments_model->get($id);
                if ($row && $row->task_id) {
					// update βασικά πεδία
					$this->db->where('id', $row->task_id)->update(db_prefix().'tasks', [
						'startdate'  => date('Y-m-d', strtotime($appt_at)),
						'duedate'    => date('Y-m-d', strtotime($appt_at)),
						'description'=> "Appointment ID: {$id}\n"
										. ($location ? "Location: {$location}\n" : '')
										. ($notes ? "Notes: {$notes}\n" : ''),
					]);

					// ανανέωση assignee, με ασφάλεια
					if ($assigned && method_exists($this->tasks_model, 'add_task_assignees')) {
						try {
							// αρκετές εκδόσεις υποστηρίζουν 3ο arg "replace"
							$this->tasks_model->add_task_assignees($row->task_id, [ ['assigneeid' => (int)$assigned] ], true);
						} catch (\Throwable $e) {
							// ignore
						}
					}
				}

                if ($row && $row->order_id) {
                    $this->orders_model->add_activity($row->order_id, 'appointment_updated', 'Appointment updated', [
                        'appointment_id'=>$id
                    ]);
                }
                set_alert('success', _l('updated_successfully', _l('lims_appointment')));
            }
            return redirect(admin_url('lims/appointments/show/'.$id));

        }
    }

    public function delete($id)
    {
        $row = $this->appointments_model->get($id);
        if(!$row){ return redirect(admin_url('lims/appointments')); }
        $ok = $this->appointments_model->delete($id);
        if($ok){
            if ($row->order_id) {
                $this->orders_model->add_activity($row->order_id, 'appointment_deleted', 'Appointment deleted', [
                    'appointment_id'=>$id
                ]);
            }
            set_alert('success', _l('deleted', _l('lims_appointment')));
        } else {
            set_alert('danger', _l('problem_deleting'));
        }
        return redirect(admin_url('lims/appointments'));
    }

    // AJAX: fetch orders by client for the form
    
	public function show($id)
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			access_denied('Lims');
		}

		$id  = (int)$id;
		$row = $this->appointments_model->get($id);
		if (!$row) {
			set_alert('warning', _l('no_results_found'));
			return redirect(admin_url('lims/appointments'));
		}

		// Εμπλουτισμός δεδομένων για το view-only
		if (!empty($row->client_id)) {
			$c = $this->db->select('company')->where('userid', (int)$row->client_id)->get(db_prefix().'clients')->row();
			$row->client_name = $c->company ?? null;
		}
		if (!empty($row->assigned_staff)) {
			$s = $this->db->select('firstname,lastname')->where('staffid', (int)$row->assigned_staff)->get(db_prefix().'staff')->row();
			$row->staff_firstname = $s->firstname ?? null;
			$row->staff_lastname  = $s->lastname ?? null;
		}

		$data['title'] = _l('lims_appointment') . ' #' . $id;
		$data['row']   = $row;

		$this->load->view('lims/admin/appointments/show', $data);
	}
	public function update_notes($id)
	{
		if (!has_permission('lims','','appointments') && !has_permission('lims','','admin')) {
			header('Content-Type: application/json'); http_response_code(403);
			echo json_encode(['success'=>false,'message'=>_l('access_denied')]); return;
		}
		$id   = (int)$id;
		$row  = $this->appointments_model->get($id);
		if (!$row) {
			header('Content-Type: application/json'); http_response_code(404);
			echo json_encode(['success'=>false,'message'=>_l('no_results_found')]); return;
		}
		$notes = $this->input->post('notes', true);
		$ok    = $this->appointments_model->update($id, ['notes' => $notes]);

		// (προαιρετικά) γράψε activity στο order αν υπάρχει
		if ($ok && !empty($row->order_id)) {
			$this->orders_model->add_activity($row->order_id, 'appointment_notes_updated', 'Appointment notes updated', [
				'appointment_id'=>$id
			]);
		}

		header('Content-Type: application/json');
		echo json_encode([
			'success' => (bool)$ok,
			'message' => $ok ? (_l('updated_successfully', _l('notes')) ?: 'Notes updated')
							 : (_l('problem_updating') ?: 'Problem updating'),
		]);
	}
	public function orders_by_subject($subject_id)
	{
		$subject_id = (int)$subject_id;
		$rows = [];
		if ($subject_id) {
			$rows = $this->db
				->where('subject_id', $subject_id)
				->order_by('id', 'DESC')
				->get(db_prefix().'lims_orders')
				->result();
		}
		echo json_encode($rows); die;
	}
	public function orders_by_client($client_id)
	{
		// Προσωρινό alias – ερμηνεύουμε το $client_id σαν subject_id
		return $this->orders_by_subject($client_id);
	}




}
