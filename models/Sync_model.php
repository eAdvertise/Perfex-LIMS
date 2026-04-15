<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sync_model extends App_Model
{
    protected $tblOutbox;
    protected $tblInbox;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('lims/lims');
        $p = db_prefix();
        $this->tblOutbox = $p.'lims_sync_outbox';
        $this->tblInbox  = $p.'lims_sync_inbox';

        $this->load->model('lims/orders_model', 'orders_model');
        $this->load->model('lims/partners_model', 'partners_model');
    }

    /* ============================================================
     * ENQUEUE (sender-side)
     * ============================================================ */

    public function enqueue_order_created($partner_id, $order_id)
    {
        $partner_id = (int)$partner_id;
        $order_id   = (int)$order_id;

        $partner = $this->partners_model->get($partner_id);
        if (!$partner || (int)$partner->active !== 1) {
            throw new RuntimeException('Partner not found or inactive.');
        }
        if (isset($partner->sync_enabled) && (int)$partner->sync_enabled !== 1) {
            return false;
        }

        $payload = $this->build_order_created_payload($order_id);
        if (!$payload) {
            throw new RuntimeException('Unable to build order payload.');
        }

        $order_uid = $payload['order']['order_uid'] ?? null;
        $idem = 'order.created:'.($order_uid ?: $order_id);

        $row = [
            'partner_id'      => $partner_id,
            'order_id'        => $order_id,
            'order_uid'       => $order_uid,
            'event_type'      => 'order.created',
            'idempotency_key' => $idem,
            'payload_json'    => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'attempts'        => 0,
            'next_retry_at'   => null,
            'last_error'      => null,
            'created_at'      => date('Y-m-d H:i:s'),
            'sent_at'         => null,
        ];

        if (!$this->db->table_exists($this->tblOutbox)) {
            throw new RuntimeException('Sync outbox table does not exist (run migrations).');
        }

        $this->db->query(
            "INSERT IGNORE INTO `{$this->tblOutbox}` (partner_id, idempotency_key, created_at, event_type, payload_json, order_id, order_uid)
             VALUES (?,?,?,?,?,?,?)",
            [$partner_id, $idem, $row['created_at'], $row['event_type'], $row['payload_json'], $order_id, $order_uid]
        );

        $this->db->where('partner_id', $partner_id)
                 ->where('idempotency_key', $idem)
                 ->update($this->tblOutbox, [
                    'payload_json' => $row['payload_json'],
                    'order_id'     => $order_id,
                    'order_uid'    => $order_uid,
                    'sent_at'      => null,
                    'attempts'     => 0,
                    'next_retry_at'=> null,
                    'last_error'   => null,
                 ]);

        return true;
    }

    /**
     * Enqueue samples.status for the order partner (if any).
     * Called when samples become collected/received after initial sync.
     */
    public function enqueue_samples_status($order_id)
    {
        $order_id = (int)$order_id;
        $p = db_prefix();
        $order = $this->db->where('id', $order_id)->get($p.'lims_orders')->row();
        if (!$order) return false;

        $partner_id = (int)($order->partner_id ?? 0);
        if ($partner_id <= 0) return false;

        $partner = $this->partners_model->get($partner_id);
        if (!$partner || (int)$partner->active !== 1) return false;
        if (isset($partner->sync_enabled) && (int)$partner->sync_enabled !== 1) return false;

        $payload = $this->build_samples_status_payload($order_id);
        if (!$payload) return false;

        $order_uid = $payload['order']['order_uid'] ?? ($order->order_uid ?? null);
        $idem = 'samples.status:'.($order_uid ?: $order_id);

        $this->db->query(
            "INSERT IGNORE INTO `{$this->tblOutbox}` (partner_id, idempotency_key, created_at, event_type, payload_json, order_id, order_uid)
             VALUES (?,?,?,?,?,?,?)",
            [$partner_id, $idem, date('Y-m-d H:i:s'), 'samples.status', json_encode($payload, JSON_UNESCAPED_UNICODE), $order_id, $order_uid]
        );

        $this->db->where('partner_id', $partner_id)
                 ->where('idempotency_key', $idem)
                 ->update($this->tblOutbox, [
                    'event_type'    => 'samples.status',
                    'payload_json'  => json_encode($payload, JSON_UNESCAPED_UNICODE),
                    'order_id'      => $order_id,
                    'order_uid'     => $order_uid,
                    'sent_at'       => null,
                    'attempts'      => 0,
                    'next_retry_at' => null,
                    'last_error'    => null,
                 ]);

        return true;
    }

    /**
     * Enqueue results.saved for the order partner (if any).
     * Called when results are entered/updated (save_results()).
     */
    public function enqueue_results_saved($order_id)
    {
        $order_id = (int)$order_id;
        $p = db_prefix();
        $order = $this->db->where('id', $order_id)->get($p.'lims_orders')->row();
        if (!$order) return false;

        $partner_id = (int)($order->partner_id ?? 0);
        if ($partner_id <= 0) return false;

        $partner = $this->partners_model->get($partner_id);
        if (!$partner || (int)$partner->active !== 1) return false;
        if (isset($partner->sync_enabled) && (int)$partner->sync_enabled !== 1) return false;

        $payload = $this->build_results_saved_payload($order_id);
        if (!$payload) return false;

        $order_uid = $payload['order']['order_uid'] ?? ($order->order_uid ?? null);
        $idem = 'results.saved:'.($order_uid ?: $order_id);

        $this->db->query(
            "INSERT IGNORE INTO `{$this->tblOutbox}` (partner_id, idempotency_key, created_at, event_type, payload_json, order_id, order_uid)
             VALUES (?,?,?,?,?,?,?)",
            [$partner_id, $idem, date('Y-m-d H:i:s'), 'results.saved', json_encode($payload, JSON_UNESCAPED_UNICODE), $order_id, $order_uid]
        );

        $this->db->where('partner_id', $partner_id)
                 ->where('idempotency_key', $idem)
                 ->update($this->tblOutbox, [
                    'event_type'    => 'results.saved',
                    'payload_json'  => json_encode($payload, JSON_UNESCAPED_UNICODE),
                    'order_id'      => $order_id,
                    'order_uid'     => $order_uid,
                    'sent_at'       => null,
                    'attempts'      => 0,
                    'next_retry_at' => null,
                    'last_error'    => null,
                 ]);

        return true;
    }

    /* ============================================================
     * PAYLOAD BUILDERS (sender-side)
     * ============================================================ */

    /**
     * v4 (based on your v3):
     * - Include order_barcode
     * - Include order_items (panel/analysis/culture) with code+name+qty
     * - Include per-sample tests/cultures (codes) for backward compatibility
     * - Include sample status fields (status/collected_at/received_at)
     */
    public function build_order_created_payload($order_id)
    {
        $p = db_prefix();
        $order = $this->db->where('id', (int)$order_id)->get($p.'lims_orders')->row();
        if (!$order) return null;

        $subject = null;
        if (!empty($order->subject_id)) {
            $subject = $this->db->where('id', (int)$order->subject_id)->get($p.'lims_subjects')->row();
        }

        // Order items (panels/analyses/cultures)
        $orderItems = [];
        if ($this->db->table_exists($p.'lims_order_items')) {
            $rows = $this->db->select('oi.id, oi.source_type, oi.source_id, oi.name, oi.qty')
                ->from($p.'lims_order_items oi')
                ->where('oi.order_id', (int)$order_id)
                ->order_by('oi.id','ASC')
                ->get()->result();

            $panelIds = []; $analysisIds = []; $cultureIds = [];
            foreach ($rows as $r) {
                if ($r->source_type === 'panel')    $panelIds[]   = (int)$r->source_id;
                if ($r->source_type === 'analysis') $analysisIds[] = (int)$r->source_id;
                if ($r->source_type === 'culture')  $cultureIds[]  = (int)$r->source_id;
            }
            $panelIds    = array_values(array_unique($panelIds));
            $analysisIds = array_values(array_unique($analysisIds));
            $cultureIds  = array_values(array_unique($cultureIds));

            $panelMeta = []; $analysisMeta = []; $cultureMeta = [];
            if (!empty($panelIds) && $this->db->table_exists($p.'lims_panels')) {
                $ps = $this->db->select('id, code, name')->where_in('id',$panelIds)->get($p.'lims_panels')->result();
                foreach ($ps as $x) $panelMeta[(int)$x->id] = ['code'=>$x->code, 'name'=>$x->name];
            }
            if (!empty($analysisIds) && $this->db->table_exists($p.'lims_analyses')) {
                $as = $this->db->select('id, code, name')->where_in('id',$analysisIds)->get($p.'lims_analyses')->result();
                foreach ($as as $x) $analysisMeta[(int)$x->id] = ['code'=>$x->code, 'name'=>$x->name];
            }
            if (!empty($cultureIds) && $this->db->table_exists($p.'lims_cultures')) {
                $cs = $this->db->select('id, code, name')->where_in('id',$cultureIds)->get($p.'lims_cultures')->result();
                foreach ($cs as $x) $cultureMeta[(int)$x->id] = ['code'=>$x->code, 'name'=>$x->name];
            }

            foreach ($rows as $r) {
                $meta = null;
                if ($r->source_type === 'panel')    $meta = $panelMeta[(int)$r->source_id]    ?? null;
                if ($r->source_type === 'analysis') $meta = $analysisMeta[(int)$r->source_id] ?? null;
                if ($r->source_type === 'culture')  $meta = $cultureMeta[(int)$r->source_id]  ?? null;

                $orderItems[] = [
                    'source_type' => (string)$r->source_type,
                    'code'        => $meta ? ($meta['code'] ?? null) : null,
                    'name'        => (string)($r->name ?: ($meta['name'] ?? '')),
                    'qty'         => (float)$r->qty,
                ];
            }
        }

        // Samples
        $samples = $this->db->select('s.*, st.code AS sample_type_code, st.name AS sample_type_name')
            ->from($p.'lims_samples s')
            ->join($p.'lims_sample_types st','st.id = s.sample_type_id','left')
            ->where('s.order_id', (int)$order_id)
            ->order_by('s.id','ASC')
            ->get()->result();

        $sampleIds = array_map(function($r){ return (int)$r->id; }, $samples);

        // Per-sample analysis/culture codes.
        // We derive codes from tbllims_tests / tbllims_sample_cultures (if already materialized)
        // AND from tbllims_order_items / panels so that "panel-only" orders still sync correctly.
        $testsBySample = [];
        if (!empty($sampleIds)) {
            $tests = $this->db->select('t.sample_id, a.code AS analysis_code')
                ->from($p.'lims_tests t')
                ->join($p.'lims_analyses a', 'a.id = t.analysis_id', 'left')
                ->where_in('t.sample_id', $sampleIds)
                ->order_by('t.id','ASC')
                ->get()->result();
            foreach ($tests as $t) {
                $sid = (int)$t->sample_id;
                if (!isset($testsBySample[$sid])) $testsBySample[$sid] = [];
                if (!empty($t->analysis_code)) {
                    $testsBySample[$sid][] = (string)$t->analysis_code;
                }
            }
        }

        $culturesBySample = [];
        if (!empty($sampleIds) && $this->db->table_exists($p.'lims_sample_cultures')) {
            $rows = $this->db->select('sc.sample_id, c.code AS culture_code')
                ->from($p.'lims_sample_cultures sc')
                ->join($p.'lims_cultures c','c.id = sc.culture_id','left')
                ->where('sc.order_id', (int)$order_id)
                ->where_in('sc.sample_id', $sampleIds)
                ->order_by('sc.id','ASC')
                ->get()->result();
            foreach ($rows as $r) {
                $sid = (int)$r->sample_id;
                if (!isset($culturesBySample[$sid])) $culturesBySample[$sid] = [];
                if (!empty($r->culture_code)) {
                    $culturesBySample[$sid][] = (string)$r->culture_code;
                }
            }
        }

        // Derive tests/cultures from order_items + panels (in case tests tables are not materialized yet)
        if ($this->db->table_exists($p.'lims_order_items')) {
            // sample type map
            $sampleTypeBySampleId = [];
            foreach ($samples as $s) {
                $sampleTypeBySampleId[(int)$s->id] = (int)($s->sample_type_id ?? 0);
            }

            $analysisIds = [];
            $cultureIds  = [];
            $panelIds    = [];
            $oi = $this->db->select('source_type, source_id')
                ->from($p.'lims_order_items')
                ->where('order_id', (int)$order_id)
                ->get()->result();

            foreach ($oi as $x) {
                $sid = (int)($x->source_id ?? 0);
                if ($sid <= 0) continue;
                if ($x->source_type === 'analysis') $analysisIds[] = $sid;
                if ($x->source_type === 'culture')  $cultureIds[]  = $sid;
                if ($x->source_type === 'panel')    $panelIds[]    = $sid;
            }
            $analysisIds = array_values(array_unique($analysisIds));
            $cultureIds  = array_values(array_unique($cultureIds));
            $panelIds    = array_values(array_unique($panelIds));

            // direct analyses
            if (!empty($analysisIds) && $this->db->table_exists($p.'lims_analyses')) {
                $rows = $this->db->select('id, code, sample_type_id')
                    ->where_in('id', $analysisIds)
                    ->get($p.'lims_analyses')->result();
                foreach ($rows as $r) {
                    $code = trim((string)$r->code);
                    if ($code === '') continue;
                    $stReq = (int)($r->sample_type_id ?? 0);
                    foreach ($sampleTypeBySampleId as $sampleId => $stId) {
                        if ($stReq > 0 && (int)$stId !== $stReq) continue;
                        if (!isset($testsBySample[$sampleId])) $testsBySample[$sampleId] = [];
                        $testsBySample[$sampleId][] = $code;
                    }
                }
            }

            // direct cultures
            if (!empty($cultureIds) && $this->db->table_exists($p.'lims_cultures')) {
                $rows = $this->db->select('id, code, sample_type_id')
                    ->where_in('id', $cultureIds)
                    ->get($p.'lims_cultures')->result();
                foreach ($rows as $r) {
                    $code = trim((string)$r->code);
                    if ($code === '') continue;
                    $stReq = (int)($r->sample_type_id ?? 0);
                    foreach ($sampleTypeBySampleId as $sampleId => $stId) {
                        if ($stReq > 0 && (int)$stId !== $stReq) continue;
                        if (!isset($culturesBySample[$sampleId])) $culturesBySample[$sampleId] = [];
                        $culturesBySample[$sampleId][] = $code;
                    }
                }
            }

            // panel expansion (analyses + optional cultures)
            if (!empty($panelIds) && $this->db->table_exists($p.'lims_panel_items')) {
                $pi = $this->db->select('pi.analysis_id, pi.culture_id, a.code AS analysis_code, a.sample_type_id AS analysis_sample_type_id, c.code AS culture_code, c.sample_type_id AS culture_sample_type_id')
                    ->from($p.'lims_panel_items pi')
                    ->join($p.'lims_analyses a', 'a.id = pi.analysis_id', 'left')
                    ->join($p.'lims_cultures c', 'c.id = pi.culture_id', 'left')
                    ->where_in('pi.panel_id', $panelIds)
                    ->get()->result();

                foreach ($pi as $r) {
                    $aCode = trim((string)($r->analysis_code ?? ''));
                    if ($aCode !== '') {
                        $stReq = (int)($r->analysis_sample_type_id ?? 0);
                        foreach ($sampleTypeBySampleId as $sampleId => $stId) {
                            if ($stReq > 0 && (int)$stId !== $stReq) continue;
                            if (!isset($testsBySample[$sampleId])) $testsBySample[$sampleId] = [];
                            $testsBySample[$sampleId][] = $aCode;
                        }
                    }

                    $cCode = trim((string)($r->culture_code ?? ''));
                    if ($cCode !== '') {
                        $stReq = (int)($r->culture_sample_type_id ?? 0);
                        foreach ($sampleTypeBySampleId as $sampleId => $stId) {
                            if ($stReq > 0 && (int)$stId !== $stReq) continue;
                            if (!isset($culturesBySample[$sampleId])) $culturesBySample[$sampleId] = [];
                            $culturesBySample[$sampleId][] = $cCode;
                        }
                    }
                }
            }
        }

        $outSamples = [];
        foreach ($samples as $s) {
            $sid = (int)$s->id;
            $outSamples[] = [
                'sample_uid'       => $s->sample_uid,
                'barcode'          => $s->barcode,
                'status'           => $s->status ?? null,
                'collected_at'     => $s->collected_at ?? null,
                'received_at'      => $s->received_at ?? null,
                'sample_type_code' => $s->sample_type_code,
                'sample_type_name' => $s->sample_type_name,
                'tests'            => array_values(array_unique($testsBySample[$sid] ?? [])),
                'cultures'         => array_values(array_unique($culturesBySample[$sid] ?? [])),
            ];
        }

        return [
            'event_type' => 'order.created',
            'sent_at'    => date('c'),
            'order' => [
                'order_uid'     => $order->order_uid ?? null,
                'order_barcode' => $order->order_barcode ?? null,
                'external_ref'  => $order->external_ref ?? null,
                'priority'      => (int)($order->priority ?? 0),
                'due_at'        => $order->due_at ?? null,
                'notes'         => $order->notes ?? null,
                'subject'       => $subject ? [
                    'subject_uid' 		  => ($subject->subject_uid ?? null) ?: ($subject->origin_subject_uid ?? null),
                    'internal_code'       => $subject->internal_code ?? null,
                    'subject_name'        => $subject->subject_name ?? null,
                    'first_name'          => $subject->first_name ?? null,
                    'last_name'           => $subject->last_name ?? null,
                    'id_number'           => $subject->id_number ?? null,
                    'social_insurance_no' => $subject->social_insurance_no ?? null,
                    'date_of_birth'       => $subject->date_of_birth ?? null,
                    'gender'              => $subject->gender ?? null,
                    'nationality'         => $subject->nationality ?? null,
                    'phone'               => $subject->phone ?? null,
                    'email'               => $subject->email ?? null,
                    'address'             => $subject->address ?? null,
                    'city'                => $subject->city ?? null,
                    'zip'                 => $subject->zip ?? null,
                    'country'             => $subject->country ?? null,
                    'notes'               => $subject->notes ?? null,
                    'language'            => $subject->language ?? null,
                ] : null,
                'order_items' => $orderItems,
                'samples'     => $outSamples,
            ],
        ];
    }

    public function build_samples_status_payload($order_id)
    {
        $p = db_prefix();
        $order = $this->db->where('id', (int)$order_id)->get($p.'lims_orders')->row();
        if (!$order) return null;

        $samples = $this->db->select('id, sample_uid, status, collected_at, received_at')
            ->from($p.'lims_samples')
            ->where('order_id', (int)$order_id)
            ->order_by('id','ASC')
            ->get()->result();

        $out = [];
        foreach ($samples as $s) {
            $out[] = [
                'sample_uid'   => $s->sample_uid,
                'status'       => $s->status ?? null,
                'collected_at' => $s->collected_at ?? null,
                'received_at'  => $s->received_at ?? null,
            ];
        }

        return [
            'event_type' => 'samples.status',
            'sent_at'    => date('c'),
            'order' => [
                'order_uid' => $order->order_uid ?? null,
                'samples'   => $out,
            ],
        ];
    }

    /**
     * Results payload: sends “current state” for tests/results/cultures/notes.
     * Receiver will upsert accordingly and log activity.
     */
    public function build_results_saved_payload($order_id)
    {
        $p = db_prefix();
        $order = $this->db->where('id', (int)$order_id)->get($p.'lims_orders')->row();
        if (!$order) return null;

        // Tests + latest results rows
        $tests = $this->db->select('t.id AS test_id, t.status, t.status_code, t.completed_at, s.sample_uid, a.code AS analysis_code, a.result_type')
            ->from($p.'lims_tests t')
            ->join($p.'lims_samples s', 's.id = t.sample_id', 'left')
            ->join($p.'lims_analyses a', 'a.id = t.analysis_id', 'left')
            ->where('s.order_id', (int)$order_id)
            ->order_by('t.id','ASC')
            ->get()->result();

        $testIds = array_map(function($r){ return (int)$r->test_id; }, $tests);

        $latestResults = [];
        if (!empty($testIds) && $this->db->table_exists($p.'lims_results')) {
            // Latest per test_id (by id)
            $rows = $this->db->query(
                "SELECT r.*
                   FROM `{$p}lims_results` r
                   JOIN (
                        SELECT test_id, MAX(id) AS max_id
                          FROM `{$p}lims_results`
                         WHERE test_id IN (".implode(',', array_map('intval',$testIds)).")
                         GROUP BY test_id
                   ) x ON x.test_id = r.test_id AND x.max_id = r.id"
            )->result();

            foreach ($rows as $r) {
                $latestResults[(int)$r->test_id] = [
                    'value_numeric' => $r->value_numeric ?? null,
                    'value_text'    => $r->value_text ?? null,
                    'unit'          => $r->unit ?? null,
                    'flag'          => $r->flag ?? null,
                    'measured_at'   => $r->measured_at ?? null,
                ];
            }
        }

        $outTests = [];
        foreach ($tests as $t) {
            $res = $latestResults[(int)$t->test_id] ?? null;
            $outTests[] = [
                'sample_uid'    => $t->sample_uid,
                'analysis_code' => $t->analysis_code,
                'status'        => $t->status ?? null,
                'status_code'   => $t->status_code ?? null,
                'completed_at'  => $t->completed_at ?? null,
                'result'        => $res,
            ];
        }

        // Culture results
        $outCultures = [];
        if ($this->db->table_exists($p.'lims_culture_results')) {
            $crows = $this->db->select('cr.*, s.sample_uid, c.code AS culture_code')
                ->from($p.'lims_culture_results cr')
                ->join($p.'lims_samples s', 's.id = cr.sample_id', 'left')
                ->join($p.'lims_cultures c', 'c.id = cr.culture_id', 'left')
                ->where('cr.order_id', (int)$order_id)
                ->order_by('cr.id','ASC')
                ->get()->result();

            foreach ($crows as $r) {
                $outCultures[] = [
                    'sample_uid'     => $r->sample_uid,
                    'culture_code'   => $r->culture_code,
                    'comment'        => $r->comment ?? null,
                    'option_value_id'=> $r->option_value_id ?? null,
                ];
            }
        }

        // Order report notes
        $reportNotes = null;
        if ($this->db->table_exists($p.'lims_order_report_notes')) {
            $rn = $this->db->where('order_id', (int)$order_id)->get($p.'lims_order_report_notes')->row();
            if ($rn) {
                $reportNotes = [
                    'free_text'     => $rn->free_text ?? null,
                    'note_ids_json' => $rn->note_ids_json ?? null,
                ];
            }
        }

        return [
            'event_type' => 'results.saved',
            'sent_at'    => date('c'),
            'order' => [
                'order_uid'     => $order->order_uid ?? null,
                'tests'         => $outTests,
                'culture_results'=> $outCultures,
                'report_notes'  => $reportNotes,
            ],
        ];
    }

    /* ============================================================
     * CRON SENDER
     * ============================================================ */

    public function process_outbox($limit = 10)
    {
        $limit = (int)$limit;
        if ($limit <= 0) $limit = 10;

        if (!$this->db->table_exists($this->tblOutbox)) {
            return ['processed' => 0, 'sent' => 0, 'failed' => 0, 'skipped' => 0];
        }

        $now = date('Y-m-d H:i:s');

        $items = $this->db->where('sent_at', null)
            ->group_start()
                ->where('next_retry_at', null)
                ->or_where('next_retry_at <=', $now)
            ->group_end()
            ->order_by('id','ASC')
            ->limit($limit)
            ->get($this->tblOutbox)->result();

        $processed = 0; $sent = 0; $failed = 0; $skipped = 0;

        foreach ($items as $it) {
            $processed++;

            $partner = $this->partners_model->get((int)$it->partner_id);
            if (!$partner || (int)$partner->active !== 1 || (isset($partner->sync_enabled) && (int)$partner->sync_enabled !== 1)) {
                $skipped++;
                $this->mark_outbox_failed($it->id, 'Partner inactive or sync disabled.', 86400);
                continue;
            }

            $baseUrl = trim((string)($partner->api_base_url ?? ''));
            if ($baseUrl === '') {
                $failed++;
                $this->mark_outbox_failed($it->id, 'Partner API Base URL is empty.', 3600);
                continue;
            }

            // Primary + fallback endpoints
            $endpoints = [];
            if (in_array($it->event_type, ['order.created','samples.status'], true)) {
                $endpoints[] = rtrim($baseUrl, '/').'/admin/lims/orders';
                $endpoints[] = rtrim($baseUrl, '/').'/lims/lims_api/orders';
            } elseif (in_array($it->event_type, ['results.saved'], true)) {
                // IMPORTANT: /admin/lims/tests may accept the request but not apply results.
                // Always prefer the dedicated API receiver for full processing.
                $endpoints[] = rtrim($baseUrl, '/').'/lims/lims_api/tests';
                $endpoints[] = rtrim($baseUrl, '/').'/admin/lims/tests';
            } else {
                // Unknown event type -> do not hammer partner
                $failed++;
                $this->mark_outbox_failed($it->id, 'Unsupported event type: '.$it->event_type, 3600);
                continue;
            }

            $payload = (string)$it->payload_json;

            $headers = [
                'Content-Type: application/json',
                'X-LIMS-API-KEY: '.(string)$partner->api_key,
                'X-LIMS-EVENT: '.(string)$it->event_type,
                'X-LIMS-IDEMPOTENCY-KEY: '.(string)$it->idempotency_key,
            ];

            $secret = trim((string)($partner->api_secret ?? ''));
            if ($secret !== '') {
                $sig = hash_hmac('sha256', (string)$it->idempotency_key."\n".$payload, $secret);
                $headers[] = 'X-LIMS-SIGNATURE: '.$sig;
            }

            $ok = false;
            $err = null;

            foreach ($endpoints as $endpoint) {
                try {
                    $resp = $this->http_post_json($endpoint, $payload, $headers);
                    $ok = ($resp['http_code'] >= 200 && $resp['http_code'] < 300);
                    if ($ok) { $err = null; break; }
                    $err = 'HTTP '.$resp['http_code'].' - '.substr((string)$resp['body'], 0, 600);
                } catch (Throwable $e) {
                    $ok = false;
                    $err = $e->getMessage();
                }
            }

            if ($ok) {
                $sent++;
                $this->db->where('id', (int)$it->id)->update($this->tblOutbox, [
                    'sent_at'      => date('Y-m-d H:i:s'),
                    'attempts'     => ((int)$it->attempts) + 1,
                    'next_retry_at'=> null,
                    'last_error'   => null,
                ]);

                if (!empty($it->order_id)) {
                    $this->db->where('id', (int)$it->order_id)->update(db_prefix().'lims_orders', [
                        'partner_last_sync_at' => date('Y-m-d H:i:s'),
                        'partner_sync_status'  => 'sent',
                        'partner_sync_error'   => null,
                    ]);
                }

                $this->db->where('id', (int)$partner->id)->update(db_prefix().'lims_partners', [
                    'last_seen_at' => date('Y-m-d H:i:s'),
                    'last_error'   => null,
                ]);

            } else {
                $failed++;
                $this->mark_outbox_failed($it->id, $err ?: 'Unknown error', $this->backoff_seconds((int)$it->attempts + 1));

                if (!empty($it->order_id)) {
                    $this->db->where('id', (int)$it->order_id)->update(db_prefix().'lims_orders', [
                        'partner_sync_status' => 'failed',
                        'partner_sync_error'  => $err ?: 'Unknown error',
                    ]);
                }

                $this->db->where('id', (int)$partner->id)->update(db_prefix().'lims_partners', [
                    'last_error' => $err ?: 'Unknown error',
                ]);
            }
        }

        return compact('processed','sent','failed','skipped');
    }

    private function mark_outbox_failed($outbox_id, $error, $retrySeconds)
    {
        $outbox_id = (int)$outbox_id;
        $retrySeconds = (int)$retrySeconds;
        if ($retrySeconds < 60) $retrySeconds = 60;

        $next = date('Y-m-d H:i:s', time() + $retrySeconds);

        $this->db->where('id', $outbox_id)->set('attempts', 'attempts+1', false)->update($this->tblOutbox, [
            'next_retry_at' => $next,
            'last_error'    => (string)$error,
        ]);
    }

    private function backoff_seconds($attempt)
    {
        $attempt = max(1, (int)$attempt);
        $sec = 60 * pow(2, min(6, $attempt - 1)); // cap at 64 minutes
        return min($sec, 3600);
    }

    private function http_post_json($url, $body, array $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $respBody = curl_exec($ch);
        $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($respBody === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException($err ?: 'cURL error');
        }
        curl_close($ch);

        return ['http_code' => $http, 'body' => $respBody];
    }
}
