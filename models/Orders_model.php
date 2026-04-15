<?php defined('BASEPATH') or exit('No direct script access allowed');

class Orders_model extends App_Model
{
    public function all()
    {
        return $this->db->order_by('id','DESC')->get(db_prefix().'lims_orders')->result();
    }

    public function get($id)
    {
        return $this->db->where('id',(int)$id)->get(db_prefix().'lims_orders')->row();
    }

    public function get_items($order_id)
    {
        return $this->db->where('order_id',(int)$order_id)->get(db_prefix().'lims_order_items')->result();
    }

    protected function get_default_status_code()
    {
        $row = $this->db
            ->where('is_default', 1)
            ->where('active', 1)
            ->order_by('position', 'ASC')
            ->get(db_prefix().'lims_test_statuses')
            ->row();

        if ($row && !empty($row->code)) {
            return $row->code;
        }
        return 'draft';
    }

    public function create_order($data)
    {
        $rec = [
            'client_id'  => isset($data['client_id']) && $data['client_id'] ? (int)$data['client_id'] : null,
            'subject_id' => isset($data['subject_id']) && $data['subject_id'] ? (int)$data['subject_id'] : null,
            'order_uid'  => function_exists('lims_uuid_v4') ? lims_uuid_v4() : null,
            'status'     => $this->get_default_status_code(),
            'priority'   => (int)($data['priority'] ?? 0),
            'due_at'     => $data['due_at'] ?? null,
            'notes'      => $data['notes'] ?? null,
            'partner_id' => isset($data['partner_id']) && $data['partner_id'] ? (int)$data['partner_id'] : null,
            'partner_direction' => !empty($data['partner_id']) ? 'outbound' : null,
            'partner_sync_status' => !empty($data['partner_id']) ? 'draft' : null,
            'created_by' => get_staff_user_id(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (empty($rec['order_uid'])) {
            $rec['order_uid'] = bin2hex(random_bytes(16));
            $rec['order_uid'] = substr($rec['order_uid'],0,8).'-'.substr($rec['order_uid'],8,4).'-'.substr($rec['order_uid'],12,4).'-'.substr($rec['order_uid'],16,4).'-'.substr($rec['order_uid'],20,12);
        }

        $this->db->insert(db_prefix().'lims_orders', $rec);
        return (int)$this->db->insert_id();
    }

    public function add(array $data)
    {
        $tbl = db_prefix().'lims_orders';

        $allowed = ['client_id','external_ref','status','priority','received_at','due_at','notes','created_by'];
        $row = [];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) { $row[$k] = $data[$k]; }
        }

        if (!isset($row['status']) || $row['status'] === '') {
            $row['status'] = $this->get_default_status_code();
        }
        if (!isset($row['priority'])) { $row['priority'] = 0; }
        if (!isset($row['created_by']) && is_staff_logged_in()) { $row['created_by'] = get_staff_user_id(); }

        foreach (['received_at','due_at'] as $dt) {
            if (isset($row[$dt]) && $row[$dt] !== '') {
                $ts = strtotime($row[$dt]);
                $row[$dt] = $ts ? date('Y-m-d H:i:s', $ts) : null;
            } else {
                $row[$dt] = null;
            }
        }

        $row['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert($tbl, $row);
        return $this->db->insert_id();
    }

    public function create_from_appointment($client_id, $appointment_at, $notes = '')
    {
        return $this->add([
            'client_id'   => (int)$client_id,
            'status'      => $this->get_default_status_code(),
            'priority'    => 0,
            'received_at' => $appointment_at,
            'notes'       => '[Auto] Order created from appointment' . ($notes ? (' - '.$notes) : ''),
        ]);
    }

    public function add_order_item($order_id, $snap)
    {
        $snap = (array)$snap;

        // IMPORTANT: remove numeric keys (e.g. 0,1,2) that break CI insert() -> SQL syntax error
        foreach ($snap as $k => $v) {
            if (is_int($k) || ctype_digit((string)$k)) {
                unset($snap[$k]);
            }
        }

        $snap = array_merge([
            'item_id'             => null,
            'source_type'         => null,
            'source_id'           => null,
            'name'                => '',
            'qty'                 => 1,
            'unit'                => null,
            'currency_id'         => 0,
            'unit_price'          => 0,
            'tax1_id'             => null,
            'tax2_id'             => null,
            'from_contract_id'    => null,
            'discount_percent'    => null,
            'fixed_price_applied' => 0,
            'referred_partner_id' => null,
        ], $snap);

        $snap['order_id'] = (int)$order_id;

        $this->db->insert(db_prefix().'lims_order_items', $snap);
        return (int)$this->db->insert_id();
    }

    public function update_order($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id',(int)$id)->update(db_prefix().'lims_orders',$data);
        return $this->db->affected_rows() >= 0;
    }

    public function get_order_currency_id($client_id)
    {
        $cur = $this->db->where('isdefault',1)->get(db_prefix().'currencies')->row();
        return $cur ? (int)$cur->id : 1;
    }

    public function build_snapshot_from_item($itemRow, $fallbackName, $currency_id, $contract_id, $source_id, $source_type)
    {
        $name  = $fallbackName ?: ($itemRow->description ?? 'Service');
        $unit  = $itemRow->unit ?? '';
        $tax1  = isset($itemRow->taxid)   ? (int)$itemRow->taxid   : null;
        $tax2  = isset($itemRow->taxid_2) ? (int)$itemRow->taxid_2 : null;

        $baseRate = isset($itemRow->rate) ? (float)$itemRow->rate : 0.0;

        $rateCol = 'rate_currency_'.$currency_id;
        if ($itemRow && isset($itemRow->$rateCol) && $itemRow->$rateCol !== null) {
            $baseRate = (float)$itemRow->$rateCol;
        }

        $fixed = null;
        if ($contract_id && $itemRow && isset($itemRow->itemid)) {
            $fix = $this->db->where('contract_id',(int)$contract_id)
                            ->where('item_id',(int)$itemRow->itemid)
                            ->where('currency',(string)$currency_id)
                            ->get(db_prefix().'lims_contract_prices')->row();
            if ($fix) $fixed = (float)$fix->fixed_price;
        }

        $rateToUse = $fixed !== null ? $fixed : $baseRate;

        return [
            'order_id'        => null,
            'item_id'         => $itemRow && isset($itemRow->itemid) ? (int)$itemRow->itemid : null,
            'source_type'     => in_array($source_type, ['panel','analysis','culture'], true) ? $source_type : 'analysis',
            'source_id'       => (int)$source_id,
            'name'            => $name,
            'qty'             => 1,
            'unit'            => $unit ?: '',
            'currency_id'     => (int)$currency_id,
            'unit_price'      => $rateToUse,
            'tax1_id'         => $tax1,
            'tax2_id'         => $tax2,
            'from_contract_id'=> $fixed !== null ? (int)$contract_id : null,
            'discount_percent'=> null,
            'fixed_price_applied' => $fixed !== null ? 1 : 0,
            'referred_partner_id' => null,
            'created_at'      => date('Y-m-d H:i:s'),
        ];
    }

    public function update_status($id, $status)
    {
        $this->db->where('id',(int)$id)->update(db_prefix().'lims_orders', [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return $this->db->affected_rows() > 0;
    }

    public function add_activity($order_id, $action, $message = '', $meta = [])
    {
        $data = [
            'order_id'  => (int)$order_id,
            'action'    => (string)$action,
            'message'   => $message ?: '',
            'meta'      => !empty($meta) ? json_encode($meta) : null,
            'staff_id'  => is_staff_logged_in() ? get_staff_user_id() : null,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
        $this->db->insert(db_prefix().'lims_order_activity', $data);
        return (int)$this->db->insert_id();
    }

    public function get_activity($order_id, $limit = 100)
    {
        $this->db->where('order_id', (int)$order_id);
        $this->db->order_by('id','DESC');
        if ($limit) $this->db->limit((int)$limit);
        return $this->db->get(db_prefix().'lims_order_activity')->result();
    }

    public function ensure_barcode($order_id)
    {
        $order = $this->get($order_id);
        if (!$order) return false;
        if (!empty($order->order_barcode)) return $order->order_barcode;

        $seqTbl = db_prefix().'lims_barcode_sequences';
        $prefix = 'ORD';

        if ($this->db->where('prefix',$prefix)->get($seqTbl)->num_rows() == 0) {
            $this->db->insert($seqTbl, ['prefix'=>$prefix,'next_number'=>1]);
        }

        $this->db->trans_begin();
        $this->db->query("UPDATE `{$seqTbl}` SET next_number = LAST_INSERT_ID(next_number + 1) WHERE prefix = ?", [$prefix]);
        $r = $this->db->query("SELECT LAST_INSERT_ID() AS seq")->row();
        $num  = max(1, ((int)$r->seq) - 1);
        $code = $prefix . str_pad((string)$num, 8, '0', STR_PAD_LEFT);

        $this->db->where('id', $order_id)->update(db_prefix().'lims_orders', [
            'order_barcode' => $code,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        if ($this->db->trans_status() === FALSE) { $this->db->trans_rollback(); return false; }
        $this->db->trans_commit();
        return $code;
    }



    /**
     * Υλοποίηση αυτόματης υλοποίησης Samples & Tests για ένα Order
     * - Ομαδοποιεί τις γραμμές (panels/analyses) ανά sample_type_id
     * - Δημιουργεί 1 Sample ανά sample_type_id
     * - Για κάθε analysis δημιουργεί Test που δείχνει στο lims_analyses.id
     * - Χρησιμοποιεί κοινό barcode με το Order (order_barcode)
     * - Idempotent: αν ξανακληθεί, δεν διπλο-δημιουργεί
     */
    public function materialize_samples_and_tests($order_id)
    {
        $order_id = (int)$order_id;
        if ($order_id <= 0) return false;

        $p = db_prefix();

        $order = $this->db->where('id',$order_id)->get($p.'lims_orders')->row();
        if (!$order) return false;

        $barcode = $this->ensure_barcode($order_id);

        $lines = $this->db->where('order_id',$order_id)
                          ->order_by('id','ASC')
                          ->get($p.'lims_order_items')->result();
        if (!$lines) return true;

        // idempotent
        if ($this->db->where('order_id',$order_id)->count_all_results($p.'lims_samples') > 0) {
            return true;
        }

        // helpers: expand από τον ΙΔΙΟ πίνακα
        $expandPanelAnalyses = function($panel_id){
            return $this->db->select('a.id AS analysis_id, a.sample_type_id')
                ->from(db_prefix().'lims_panel_items pi')
                ->join(db_prefix().'lims_analyses a','a.id = pi.analysis_id','left')
                ->where('pi.panel_id',(int)$panel_id)
                ->where('pi.analysis_id IS NOT NULL', null, false)
                ->order_by('pi.sort_order','ASC')
                ->get()->result();
        };
        $expandPanelCultures = function($panel_id){
            return $this->db->select('c.id AS culture_id, c.sample_type_id')
                ->from(db_prefix().'lims_panel_items pi')
                ->join(db_prefix().'lims_cultures c','c.id = pi.culture_id','left')
                ->where('pi.panel_id',(int)$panel_id)
                ->where('pi.culture_id IS NOT NULL', null, false)
                ->order_by('pi.sort_order','ASC')
                ->get()->result();
        };

        // min volume από sample type
        $minVolOfType = function($sample_type_id){
            static $cache = [];
            $sid = (int)$sample_type_id;
            if (!isset($cache[$sid])) {
                $r = $this->db->select('min_volume_ml')
                    ->where('id',$sid)->get(db_prefix().'lims_sample_types')->row();
                $cache[$sid] = $r ? (float)$r->min_volume_ml : null;
            }
            return $cache[$sid];
        };

        $uidCounter = 1;
        $createSample = function($args) use ($order_id, $barcode, &$uidCounter){
            $p = db_prefix();
            $sample_uid = 'S'.$order_id.'-'.str_pad((string)$uidCounter, 3, '0', STR_PAD_LEFT);
            $uidCounter++;

            $this->db->insert($p.'lims_samples', [
                'order_id'       => $order_id,
                'appointment_id' => null,
                'sample_uid'     => $sample_uid,
                'barcode'        => $barcode ?: null,
                'sample_type_id' => $args['sample_type_id'] ?: null,
                'status'         => 'pending',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $sample_id = (int)$this->db->insert_id();

            // Tests μόνο για analyses
            if ($sample_id > 0 && $args['kind'] === 'analysis') {
                $this->db->insert($p.'lims_tests', [
                    'sample_id'   => $sample_id,
                    'analysis_id' => (int)$args['ref_id'],
                    'status'      => 'pending',
                    'item_id'     => null,
                    'unit_price'  => null,
                    'currency'    => null,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
            }

            return $sample_id;
        };

        foreach ($lines as $ln) {
            if ($ln->source_type === 'panel') {
                // Analyses του panel
                foreach ($expandPanelAnalyses((int)$ln->source_id) as $row) {
                    if (!(int)$row->analysis_id) continue;
                    $stype = (int)$row->sample_type_id;
                    $createSample([
                        'kind'          => 'analysis',
                        'ref_id'        => (int)$row->analysis_id,
                        'sample_type_id'=> $stype,
                    ]);
                }
                // Cultures του panel
                foreach ($expandPanelCultures((int)$ln->source_id) as $row) {
                    if (!(int)$row->culture_id) continue;
                    $stype = (int)$row->sample_type_id;
                    $createSample([
                        'kind'          => 'culture',
                        'ref_id'        => (int)$row->culture_id,
                        'sample_type_id'=> $stype,
                    ]);
                }

            } elseif ($ln->source_type === 'analysis') {
                $a = $this->db->select('id,sample_type_id')
                              ->where('id',(int)$ln->source_id)->get($p.'lims_analyses')->row();
                if ($a) {
                    $createSample([
                        'kind'          => 'analysis',
                        'ref_id'        => (int)$a->id,
                        'sample_type_id'=> (int)$a->sample_type_id,
                    ]);
                }

            } elseif ($ln->source_type === 'culture') {
                $c = $this->db->select('id,sample_type_id')
                              ->where('id',(int)$ln->source_id)->get($p.'lims_cultures')->row();
                if ($c) {
                    $createSample([
                        'kind'          => 'culture',
                        'ref_id'        => (int)$c->id,
                        'sample_type_id'=> (int)$c->sample_type_id,
                    ]);
                }
            }
        }

        return true;
    }

    public function update_notes($id,$notes){
      $this->db->where('id',(int)$id)->update(db_prefix().'lims_orders',[
        'notes'=>$notes,'updated_at'=>date('Y-m-d H:i:s')
      ]);
      return $this->db->affected_rows()>=0;
    }
    public function update_contract($id,$contract_id){
      $this->db->where('id',(int)$id)->update(db_prefix().'lims_orders',[
        'contract_id'=>$contract_id?:null,'updated_at'=>date('Y-m-d H:i:s')
      ]);
      return $this->db->affected_rows()>=0;
    }

	public function get_samples_with_tests($order_id)
	{
		$p = db_prefix();

		// Samples + sample type
		$samples = $this->db->select("s.*, st.name AS sample_type_name, st.code AS sample_type_code")
			->from("{$p}lims_samples AS s")
			->join("{$p}lims_sample_types AS st","st.id = s.sample_type_id","left")
			->where('s.order_id', (int)$order_id)
			->order_by('s.id','ASC')
			->get()->result();

		if (!$samples) return [];

		$sampleIds = array_map(function($r){ return (int)$r->id; }, $samples);

		// Tests (per sample)
		$tests = $this->db->select("t.*, a.name AS analysis_name, a.code AS analysis_code")
			->from("{$p}lims_tests AS t")
			->join("{$p}lims_analyses AS a","a.id = t.analysis_id","left")
			->where_in('t.sample_id', $sampleIds)
			->order_by('t.id','ASC')
			->get()->result();

		// Latest result per test_id
		$resultsByTest = [];
		$testIds = array_values(array_unique(array_map(function($t){ return (int)$t->id; }, $tests)));

		if (!empty($testIds) && $this->db->table_exists($p.'lims_results')) {
			$idsCsv = implode(',', array_map('intval', $testIds));

			$rows = $this->db->query(
				"SELECT r.*
				   FROM `{$p}lims_results` r
				   JOIN (
						SELECT test_id, MAX(id) AS max_id
						  FROM `{$p}lims_results`
						 WHERE test_id IN ({$idsCsv})
						 GROUP BY test_id
				   ) x ON x.test_id = r.test_id AND x.max_id = r.id"
			)->result();

			foreach ($rows as $r) {
				$resultsByTest[(int)$r->test_id] = $r;
			}
		}

		// Attach result to each test
		foreach ($tests as $t) {
			$t->latest_result = $resultsByTest[(int)$t->id] ?? null;
		}

		// Group tests by sample
		$testsBySample = [];
		foreach ($tests as $t) {
			$testsBySample[(int)$t->sample_id][] = $t;
		}

		// Cultures per sample + their result_text/options_json (if any)
		$culturesBySample = [];
		if ($this->db->table_exists($p.'lims_sample_cultures') && $this->db->table_exists($p.'lims_cultures')) {

			$cRows = $this->db->select("sc.sample_id,
										c.id AS culture_id,
										c.code AS culture_code,
										c.name AS culture_name,
										cr.result_text,
										cr.options_json,
										cr.updated_at AS result_updated_at", false)
				->from("{$p}lims_sample_cultures sc")
				->join("{$p}lims_cultures c", "c.id = sc.culture_id", "inner")
				->join("{$p}lims_culture_results cr", "cr.order_id = sc.order_id AND cr.sample_id = sc.sample_id AND cr.culture_id = sc.culture_id", "left")
				->where('sc.order_id', (int)$order_id)
				->where_in('sc.sample_id', $sampleIds)
				->order_by('sc.id','ASC')
				->get()->result();

			foreach ($cRows as $r) {
				$culturesBySample[(int)$r->sample_id][] = $r;
			}
		}

		// Attach everything to samples
		foreach ($samples as &$s) {
			$sid = (int)$s->id;
			$s->tests    = $testsBySample[$sid] ?? [];
			$s->cultures = $culturesBySample[$sid] ?? [];
		}

		return $samples;
	}



}
