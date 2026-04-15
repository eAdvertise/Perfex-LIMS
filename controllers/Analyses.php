<?php defined('BASEPATH') or exit('No direct script access allowed');

class Analyses extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('lims/analyses_model',    'analyses_model');
        $this->load->model('lims/sampletypes_model', 'sampletypes_model');
        $this->load->model('lims/departments_model', 'departments_model');

        // Core items model (τιμολόγηση)
        $this->load->model('invoice_items_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $data['title'] = _l('lims_analyses');
        $data['rows']  = $this->analyses_model->all();
        $this->load->view('lims/admin/analyses/index', $data);
    }

    public function create($id = null)
	{
		if (!has_permission('lims','','admin')) { access_denied('Lims'); }

		if ($this->input->post()) {
			try {
				// Κράτα τυχόν υπάρχον item_id όταν κάνουμε EDIT
				$existingItemId = null;
				if ($id) {
					$existingRow    = $this->analyses_model->get($id);
					$existingItemId = ($existingRow && !empty($existingRow->item_id)) ? (int)$existingRow->item_id : null;
				}

				// --------- ΔΙΑΒΑΣΕ POST ----------
				$post = $this->input->post();

				// 2a) Select options (για result_type = select)
				$selectValues = $this->input->post('select_values');
				$selectOpts   = [];

				if (is_array($selectValues)
					&& isset($selectValues['value'])
					&& is_array($selectValues['value'])
				) {
					$vals  = $selectValues['value'];
					$labels= isset($selectValues['label']) ? $selectValues['label'] : [];

					foreach ($vals as $k => $v) {
						$v = trim((string)$v);
						if ($v === '') {
							continue; // αγνόησε κενές
						}
						$lbl = isset($labels[$k]) ? trim((string)$labels[$k]) : '';
						$selectOpts[] = [
							'value' => $v,
							'label' => $lbl,
						];
					}
				}

				// Αν δεν έχει τίποτα, κράτα null
				if (!empty($selectOpts)) {
					$post['select_options'] = json_encode($selectOpts);
				} else {
					$post['select_options'] = null;
				}

				// 2b) Reference ranges (θα τα περάσουμε στο model μετά το save)
				$specPost       = $this->input->post('spec') ?: [];
				$sampleTypeId   = isset($post['sample_type_id']) ? (int)$post['sample_type_id'] : null;
				$unitsUcum      = isset($post['units_ucum']) ? (string)$post['units_ucum'] : null;

				// ΜΗ στείλεις item_id προς το model
				unset($post['item_id'], $post['select_values'], $post['spec']);

				// 1) Save analysis
				$aid = $this->analyses_model->save($post, $id);

				// 2) Billing (όπως πριν)
				$analysis = $this->analyses_model->get($aid);
				$item_id  = $existingItemId ?: ($analysis->item_id ?? null);

				$long_description = trim($this->input->post('item_long_description') ?? '');
				$unit             = trim($this->input->post('item_unit') ?? '');
				$tax1_id          = $this->input->post('item_tax');
				$tax2_id          = $this->input->post('item_tax2');
				$rates_input      = $this->input->post('item_rates') ?? [];

				$currencies = $this->db->order_by('isdefault','DESC')->order_by('name','ASC')->get(db_prefix().'currencies')->result();
				$default_currency_id = null;
				foreach ($currencies as $c) {
					if (!empty($c->isdefault)) {
						$default_currency_id = (int)$c->id; break;
					}
				}
				$base_rate = ($default_currency_id && isset($rates_input[$default_currency_id]) && $rates_input[$default_currency_id] !== '')
							? (float)$rates_input[$default_currency_id] : 0;

				$grp = $this->db->where('name', 'Analysies')->get(db_prefix().'items_groups')->row();
				$group_id = $grp ? (int)$grp->id : (int)$this->invoice_items_model->add_group(['name' => 'Analysies']);

				$itemData = [
					'description'      => $analysis->name,
					'long_description' => $long_description !== '' ? $long_description : 'LIMS Analysis: '.$analysis->name,
					'rate'             => $base_rate,
					'unit'             => $unit !== '' ? $unit : null,
					'group_id'         => $group_id ?: 0,
					'tax'              => ($tax1_id !== '' && $tax1_id !== null) ? (int)$tax1_id : null,
					'tax2'             => ($tax2_id !== '' && $tax2_id !== null) ? (int)$tax2_id : null,
				];

				foreach ($rates_input as $curId => $val) {
					if ($val === '' || $val === null) continue;
					$curId = (int)$curId;
					if ($default_currency_id && $curId === $default_currency_id) continue;
					$itemData['rate_currency_'.$curId] = (float)$val;
				}

				if ($item_id) {
					$itemData['itemid'] = (int)$item_id;
					$this->invoice_items_model->edit($itemData);
				} else {
					$new_item_id = $this->invoice_items_model->add($itemData);
					if ($new_item_id) {
						$this->db->where('id', (int)$aid)->update(db_prefix().'lims_analyses', ['item_id' => (int)$new_item_id]);
					}
				}

				// 3) Reference ranges αποθήκευση
				$this->analyses_model->save_specs($aid, $specPost, $sampleTypeId, $unitsUcum);

				set_alert('success', _l('lims_saved'));
				return redirect(admin_url('lims/analyses'));
			} catch (Exception $e) {
				log_activity('LIMS Analyses save error: '.$e->getMessage());
				set_alert('danger', _l('lims_error_generic'));
			}
		}

		// GET: render form
		$data['title']        = _l('lims_analyses').($id ? ' #'.$id : '');
		$data['row']          = $id ? $this->analyses_model->get($id) : null;

		$data['sample_types'] = $this->sampletypes_model->all();
		$data['departments']  = $this->departments_model->all();

		$data['currencies']   = $this->db->order_by('isdefault','DESC')->order_by('name','ASC')->get(db_prefix().'currencies')->result();
		$data['taxes']        = $this->db->order_by('name','ASC')->get(db_prefix().'taxes')->result();
		$data['units']        = $this->db->distinct()->select('unit')->where('unit IS NOT NULL')->where("unit != ''")->get(db_prefix().'items')->result();

		$data['item']           = null;
		$data['item_rates_map'] = [];

		if (!empty($data['row']) && !empty($data['row']->item_id)) {
			$item = $this->invoice_items_model->get((int)$data['row']->item_id);
			if ($item) {
				$data['item'] = $item;
				foreach ($data['currencies'] as $c) {
					$cid = (int)$c->id;
					$col = 'rate_currency_'.$cid;
					if (!empty($c->isdefault)) {
						$data['item_rates_map'][$cid] = isset($item->rate) ? (float)$item->rate : null;
					} elseif (isset($item->$col)) {
						$data['item_rates_map'][$cid] = $item->$col !== null ? (float)$item->$col : null;
					} else {
						$data['item_rates_map'][$cid] = null;
					}
				}
			}
		}

		// ΝΕΟ: reference ranges για edit
		$data['specs'] = $id ? $this->analyses_model->get_specs($id) : [];

		$this->load->view('lims/admin/analyses/create', $data);
	}


    public function delete($id)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $id = (int)$id;

        // Πάρε linked item πριν τη διαγραφή
        $row = $this->analyses_model->get($id);
        $linkedItemId = $row && !empty($row->item_id) ? (int)$row->item_id : null;

        $ok = $this->analyses_model->delete($id);

        if ($ok && $linkedItemId) {
            // Σβήσε το Item ΜΟΝΟ αν ανήκει στο group "Analysies"
            $grp = $this->db->select('g.name')
                            ->from(db_prefix().'items as i')
                            ->join(db_prefix().'items_groups as g','g.id = i.group_id','left')
                            ->where('i.id',$linkedItemId)->get()->row();
            $groupName = $grp ? $grp->name : null;

            if ($groupName === 'Analysies') {
                $this->load->model('invoice_items_model');
                $this->invoice_items_model->delete($linkedItemId);
                log_activity('LIMS: Deleted Analysis and its Item [analysis_id:'.$id.', item_id:'.$linkedItemId.']');
            } else {
                // Μη σβήσεις “ξένο” item – απλώς αποσύνδεση
                $this->db->where('id',$id)->update(db_prefix().'lims_analyses',['item_id'=>null]);
                log_activity('LIMS: Deleted Analysis and detached external Item [analysis_id:'.$id.', item_id:'.$linkedItemId.']');
            }
        }

        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));
        redirect(admin_url('lims/analyses'));
    }

    public function toggle_status()
    {
        if (!has_permission('lims','','admin')) { ajax_access_denied(); }
        $id = (int)$this->input->post('id');
        $active = (int)$this->input->post('active') === 1;
        $ok = $this->analyses_model->set_active($id, $active);
        echo json_encode(['success'=>(bool)$ok]); die;
    }
}
