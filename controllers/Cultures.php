<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cultures extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/cultures_model',      'cultures_model');
        $this->load->model('lims/culturetypes_model',  'culturetypes_model');
        $this->load->model('lims/sampletypes_model',   'sampletypes_model');
        $this->load->model('invoice_items_model');
		$this->load->model('lims/culture_options_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }
    }

    public function index()
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $data['title']   = _l('lims_cultures');
        $data['rows']    = $this->cultures_model->all();
        $this->load->view('lims/admin/cultures/index', $data);
    }

    public function create($id = null)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }

        if ($this->input->post()) {
            try {
                // κρατάμε τυχόν υπάρχον item_id σε edit
                $existingItemId = null;
                if ($id) {
                    $existing = $this->cultures_model->get($id);
                    $existingItemId = $existing && !empty($existing->item_id) ? (int)$existing->item_id : null;
                }

                // 1) save culture
                $post = $this->input->post();
                unset($post['item_id']);
                $cid = $this->cultures_model->save($post, $id);
				// Μετά το save της culture
				$culture_option_sets = $this->input->post('culture_option_sets') ?: [];
				$this->culture_options_model->save_culture_links($cid, $culture_option_sets);

                // 2) prepare Item payload (auto link)
                $culture = $this->cultures_model->get($cid);
                $item_id = $existingItemId ?: ($culture->item_id ?? null);

                $long_description = trim($this->input->post('item_long_description') ?? '');
                $unit             = trim($this->input->post('item_unit') ?? '');
                $tax1_id          = $this->input->post('item_tax');
                $tax2_id          = $this->input->post('item_tax2');
                $rates_input      = $this->input->post('item_rates') ?? [];

                $currencies = $this->db->order_by('isdefault','DESC')->order_by('name','ASC')->get(db_prefix().'currencies')->result();
                $default_currency_id = null;
                foreach ($currencies as $c) { if (!empty($c->isdefault)) { $default_currency_id = (int)$c->id; break; } }
                $base_rate = ($default_currency_id && isset($rates_input[$default_currency_id]) && $rates_input[$default_currency_id] !== '')
                            ? (float)$rates_input[$default_currency_id] : 0;

                // ensure group "Cultures"
                $grp = $this->db->where('name', 'Cultures')->get(db_prefix().'items_groups')->row();
                $group_id = $grp ? (int)$grp->id : (int)$this->invoice_items_model->add_group(['name' => 'Cultures']);

                $itemData = [
                    'description'      => $culture->name,
                    'long_description' => $long_description !== '' ? $long_description : 'LIMS Culture: '.$culture->name,
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
                        $this->db->where('id', (int)$cid)->update(db_prefix().'lims_cultures', ['item_id' => (int)$new_item_id]);
                    }
                }

                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/cultures'));
            } catch (Exception $e) {
                log_activity('LIMS Cultures save error: '.$e->getMessage());
                set_alert('danger', _l('lims_error_generic'));
            }
        }

        // GET
        $data['title']          = _l('lims_culture').($id ? ' #'.$id : '');
        $data['row']            = $id ? $this->cultures_model->get($id) : null;
        $data['culture_types']  = $this->culturetypes_model->all_active();
        $data['sample_types']   = $this->sampletypes_model->all_active();
        $data['currencies']     = $this->db->order_by('isdefault','DESC')->order_by('name','ASC')->get(db_prefix().'currencies')->result();
        $data['taxes']          = $this->db->order_by('name','ASC')->get(db_prefix().'taxes')->result();
        $data['units']          = $this->db->distinct()->select('unit')->where('unit IS NOT NULL')->where("unit != ''")->get(db_prefix().'items')->result();

        // Prefill item for edit
        $data['item']            = null;
        $data['item_rates_map']  = [];
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
		// Όλα τα active option sets
		$data['culture_option_sets'] = $this->culture_options_model->all_sets(true);

		// Επιλεγμένα για την τρέχουσα culture (σε edit)
		$data['culture_option_selected'] = [];
		if (!empty($data['row']) && !empty($data['row']->id)) {
			$data['culture_option_selected'] =
				$this->culture_options_model->get_culture_set_ids($data['row']->id);
		}

        $this->load->view('lims/admin/cultures/create', $data);
    }

    public function delete($id)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $id = (int)$id;

        // φέρε το culture & linked item
        $row = $this->cultures_model->get($id);
        if (!$row) {
            set_alert('danger', _l('lims_error_generic'));
            return redirect(admin_url('lims/cultures'));
        }

        $linkedItemId = !empty($row->item_id) ? (int)$row->item_id : null;

        // Αν υπάρχει συνδεδεμένο Item και ανήκει στο group "Cultures", σβήσ’ το με το core model
        if ($linkedItemId) {
            $grp = $this->db->select('g.name')
                            ->from(db_prefix().'items as i')
                            ->join(db_prefix().'items_groups as g','g.id = i.group_id','left')
                            ->where('i.id',$linkedItemId)->get()->row();
            $groupName = $grp ? $grp->name : null;

            if ($groupName === 'Cultures') {
                $this->invoice_items_model->delete($linkedItemId);
                log_activity('LIMS: Deleted Item of Culture [culture_id:'.$id.', item_id:'.$linkedItemId.']');
            } else {
                // αλλιώς, απλώς αποσύνδεση για να μη σβήσουμε ξένο item
                $this->db->where('id',$id)->update(db_prefix().'lims_cultures',['item_id'=>null]);
                log_activity('LIMS: Detached external Item from Culture [culture_id:'.$id.', item_id:'.$linkedItemId.']');
            }
        }

        $ok = $this->cultures_model->delete($id);
        set_alert($ok ? 'success' : 'danger', $ok ? _l('lims_deleted') : _l('lims_error_generic'));
        return redirect(admin_url('lims/cultures'));
    }
}
