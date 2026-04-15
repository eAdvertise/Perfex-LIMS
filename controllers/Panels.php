<?php defined('BASEPATH') or exit('No direct script access allowed');

class Panels extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lims/panels_model',     'panels_model');
        $this->load->model('lims/analyses_model',   'analyses_model');
        $this->load->model('lims/departments_model','departments_model');
        $this->load->model('invoice_items_model');

        $locale = get_staff_default_language() ?: get_option('active_language') ?: 'english';
        $this->lang->load('lims', $locale);
        if ($locale !== 'english') { $this->lang->load('lims', 'english'); }

        // Αν διαγραφεί item από το core → διαγράφουμε και το Panel
        hooks()->add_action('item_deleted', [$this, 'on_core_item_deleted']);
    }

    public function index()
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $data['title'] = _l('lims_panels');
        $data['rows']  = $this->panels_model->all();
        $this->load->view('lims/admin/panels/index', $data);
    }

    public function create($id = null)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $this->ensure_panel_itemid_column();

        if ($this->input->post()) {
            try {
                $post = $this->input->post();

                // Save panel (και αναλύσεις)
                $panel_id = $this->panels_model->save($post, $id);
                $panel    = $this->panels_model->get($panel_id);

                // Φτιάξε AUTO Long Description σε bullet list
                $analysis_ids = $post['analysis_ids'] ?? [];
                $names = [];
                if (!empty($analysis_ids)) {
                    $analyses = $this->analyses_model->get_many_by_ids($analysis_ids);
                    foreach ($analyses as $a) { $names[] = trim($a->name); }
                }
                $auto_long = "Analyses:\n";
                foreach ($names as $n) {
                    $auto_long .= " -".$n."\n";
                }

                // Billing inputs
                $rates_input = $post['item_rates'] ?? []; // [currencyId => rate]
                $tax1_id     = $post['item_tax'] ?? null;
                $tax2_id     = $post['item_tax2'] ?? null;
                $unit        = trim($post['item_unit'] ?? ''); // ΜΟΝΟ στο create

                // Νόμισμα βάσης
                $currencies = $this->db->order_by('isdefault','DESC')->get(db_prefix().'currencies')->result();
                $defCurId   = null;
                foreach ($currencies as $c) { if (!empty($c->isdefault)) { $defCurId = (int)$c->id; break; } }
                $base_rate  = (isset($rates_input[$defCurId]) && $rates_input[$defCurId] !== '') ? (float)$rates_input[$defCurId] : 0;

                // Group "Panels" (create if missing)
                $grp = $this->db->where('name','Panels')->get(db_prefix().'items_groups')->row();
                $group_id = $grp ? (int)$grp->id : (int)$this->invoice_items_model->add_group(['name'=>'Panels']);

                $existing_item = $panel->item_id ?? null;

                if ($existing_item) {
                    // EDIT MODE: ΜΟΝΟ rates & taxes (ΔΕΝ αλλάζουμε description / long_description / unit)
                    $itemData = [
                        'itemid' => (int)$existing_item,
                        'rate'   => $base_rate,
                        'tax'    => $tax1_id ?: null,
                        'tax2'   => $tax2_id ?: null,
                    ];
                    // multi-currency columns
                    foreach ($rates_input as $cid=>$val){
                        if ($val === '' || $val === null) continue;
                        if ((int)$cid === (int)$defCurId) continue;
                        $itemData['rate_currency_'.(int)$cid] = (float)$val;
                    }
                    $this->invoice_items_model->edit($itemData);

                } else {
                    // CREATE MODE: description = panel name, long_description = bullet list από analyses, unit set, group=Panels
                    $itemData = [
                        'description'      => $panel->name,
                        'long_description' => $auto_long,
                        'rate'             => $base_rate,
                        'unit'             => $unit !== '' ? $unit : null,
                        'group_id'         => $group_id,
                        'tax'              => $tax1_id ?: null,
                        'tax2'             => $tax2_id ?: null,
                    ];
                    foreach ($rates_input as $cid=>$val){
                        if ($val === '' || $val === null) continue;
                        if ((int)$cid === (int)$defCurId) continue;
                        $itemData['rate_currency_'.(int)$cid] = (float)$val;
                    }
                    $new_item_id = $this->invoice_items_model->add($itemData);
                    if ($new_item_id) {
                        $this->db->where('id',$panel_id)->update(db_prefix().'lims_panels',['item_id'=>$new_item_id]);
                    }
                }

                set_alert('success', _l('lims_saved'));
                return redirect(admin_url('lims/panels'));

            } catch (Exception $e) {
                log_activity('LIMS Panels save error: '.$e->getMessage());
                set_alert('danger', _l('lims_error_generic'));
            }
        }

        // GET
        $data['title']        = _l('lims_panels').($id?' #'.$id:'');
        $data['row']          = $id ? $this->panels_model->get($id) : null;
        $data['departments']  = $this->departments_model->all();
        $data['analyses']     = $this->analyses_model->all();
        $data['selected_ids'] = $id ? $this->panels_model->get_panel_analysis_ids($id) : [];

        $data['currencies']   = $this->db->order_by('isdefault','DESC')->get(db_prefix().'currencies')->result();
        $data['taxes']        = $this->db->order_by('name','ASC')->get(db_prefix().'taxes')->result();
        $data['units']        = $this->db->distinct()->select('unit')->where('unit IS NOT NULL')->where("unit!=''")->get(db_prefix().'items')->result();

        // Prefill από item (για EDIT)
        $data['item']          = null;
        $data['item_rates_map']= [];
        if (!empty($data['row']->item_id)) {
            $item = $this->invoice_items_model->get((int)$data['row']->item_id);
            if ($item) {
                $data['item'] = $item;
                $defId=null; foreach($data['currencies'] as $c){ if(!empty($c->isdefault)){ $defId=(int)$c->id; break; } }
                foreach($data['currencies'] as $c){
                    $cid=(int)$c->id;
                    if ($defId && $cid===$defId) {
                        $data['item_rates_map'][$cid] = isset($item->rate) ? (float)$item->rate : null;
                    } else {
                        $col='rate_currency_'.$cid;
                        $data['item_rates_map'][$cid] = isset($item->$col) ? (float)$item->$col : null;
                    }
                }
            }
        }

        $this->load->view('lims/admin/panels/create',$data);
    }

    public function delete($id)
    {
        if (!has_permission('lims','','admin')) { access_denied('Lims'); }
        $row=$this->panels_model->get($id);
        $iid=$row->item_id??null;
        $ok=$this->panels_model->delete($id);
        if($ok && $iid){ $this->invoice_items_model->delete($iid); }
        set_alert($ok?'success':'danger',$ok?_l('lims_deleted'):_l('lims_error_generic'));
        redirect(admin_url('lims/panels'));
    }

    public function on_core_item_deleted($item_id)
    {
        $row=$this->db->where('item_id',(int)$item_id)->get(db_prefix().'lims_panels')->row();
        if($row){
            $this->panels_model->delete((int)$row->id);
            log_activity('LIMS: Panel auto-deleted with Item ID '.$item_id);
        }
    }

    private function ensure_panel_itemid_column()
    {
        $tbl=db_prefix().'lims_panels';
        $col=$this->db->query("SHOW COLUMNS FROM `{$tbl}` LIKE 'item_id'")->num_rows();
        if(!$col){
            $this->db->query("ALTER TABLE `{$tbl}` ADD COLUMN `item_id` INT UNSIGNED NULL AFTER `code`");
            $this->db->query("CREATE INDEX `idx_panel_item` ON `{$tbl}` (`item_id`)");
        }
    }
}
