<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lims_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('lims/lims');
        $this->load->model('lims/orders_model', 'orders_model');
        $this->load->model('lims/subjects_model', 'subjects_model');
        $this->load->model('lims/partners_model', 'partners_model');
    }

    public function ping()
    {
        $partner = $this->authenticate_request();
        return $this->json_response([
            'success' => true,
            'partner_id' => (int)$partner->id,
            'server_time' => date('c'),
        ]);
    }

    public function orders()
    {
        if (strtoupper($this->input->method(true)) !== 'POST') {
            return $this->json_response(['success'=>false,'error'=>'Method not allowed'], 405);
        }

        $partner = $this->authenticate_request();

        $eventType = trim((string)$this->input->get_request_header('X-LIMS-EVENT', true));
        $idemKey   = trim((string)$this->input->get_request_header('X-LIMS-IDEMPOTENCY-KEY', true));
        $rawBody   = file_get_contents('php://input');

        if ($idemKey === '') return $this->json_response(['success'=>false,'error'=>'Missing X-LIMS-IDEMPOTENCY-KEY'], 400);

        $p = db_prefix();
        $tblInbox = $p.'lims_sync_inbox';
        if (!$this->db->table_exists($tblInbox)) {
            return $this->json_response(['success'=>false,'error'=>'Sync inbox table missing (run migrations).'], 500);
        }

        // idempotency
        $existing = $this->db->where('partner_id', (int)$partner->id)
            ->where('idempotency_key', $idemKey)
            ->get($tblInbox)->row();
        if ($existing) {
            return $this->json_response(['success'=>true,'status'=>$existing->status,'message'=>'Already received']);
        }

        $data = json_decode($rawBody, true);
        if (!is_array($data)) return $this->json_response(['success'=>false,'error'=>'Invalid JSON'], 400);

        if ($eventType === '') $eventType = (string)($data['event_type'] ?? '');
        $eventType = trim($eventType);

        $this->db->insert($tblInbox, [
            'partner_id'      => (int)$partner->id,
            'event_type'      => $eventType,
            'idempotency_key' => $idemKey,
            'payload_hash'    => hash('sha256', (string)$rawBody),
            'received_at'     => date('Y-m-d H:i:s'),
            'status'          => 'received',
        ]);
        $inboxId = (int)$this->db->insert_id();

        try {
            if ($eventType === 'order.created') {
                $orderId = $this->handle_order_created($partner, $data);
                $this->mark_inbox_ok($tblInbox, $inboxId, $partner);
                return $this->json_response(['success'=>true,'order_id'=>(int)$orderId]);
            }
            if ($eventType === 'samples.status') {
                $updated = $this->handle_samples_status($partner, $data);
                $this->mark_inbox_ok($tblInbox, $inboxId, $partner);
                return $this->json_response(['success'=>true,'updated'=>(int)$updated]);
            }

            $this->db->where('id', $inboxId)->update($tblInbox, [
                'processed_at' => date('Y-m-d H:i:s'),
                'status'       => 'skipped',
                'last_error'   => 'Unsupported event type: '.$eventType,
            ]);
            return $this->json_response(['success'=>false,'error'=>'Unsupported event type'], 400);

        } catch (Throwable $e) {
            $this->db->where('id', $inboxId)->update($tblInbox, [
                'processed_at' => date('Y-m-d H:i:s'),
                'status'       => 'failed',
                'last_error'   => $e->getMessage(),
            ]);
            $this->db->where('id', (int)$partner->id)->update($p.'lims_partners', [
                'last_error' => $e->getMessage(),
            ]);
            return $this->json_response(['success'=>false,'error'=>$e->getMessage()], 500);
        }
    }

    public function tests()
    {
        if (strtoupper($this->input->method(true)) !== 'POST') {
            return $this->json_response(['success'=>false,'error'=>'Method not allowed'], 405);
        }

        $partner = $this->authenticate_request();

        $eventType = trim((string)$this->input->get_request_header('X-LIMS-EVENT', true));
        $idemKey   = trim((string)$this->input->get_request_header('X-LIMS-IDEMPOTENCY-KEY', true));
        $rawBody   = file_get_contents('php://input');

        if ($idemKey === '') return $this->json_response(['success'=>false,'error'=>'Missing X-LIMS-IDEMPOTENCY-KEY'], 400);

        $p = db_prefix();
        $tblInbox = $p.'lims_sync_inbox';
        if (!$this->db->table_exists($tblInbox)) {
            return $this->json_response(['success'=>false,'error'=>'Sync inbox table missing (run migrations).'], 500);
        }

        $existing = $this->db->where('partner_id', (int)$partner->id)
            ->where('idempotency_key', $idemKey)
            ->get($tblInbox)->row();
        if ($existing) {
            return $this->json_response(['success'=>true,'status'=>$existing->status,'message'=>'Already received']);
        }

        $data = json_decode($rawBody, true);
        if (!is_array($data)) return $this->json_response(['success'=>false,'error'=>'Invalid JSON'], 400);

        if ($eventType === '') $eventType = (string)($data['event_type'] ?? '');
        $eventType = trim($eventType);

        $this->db->insert($tblInbox, [
            'partner_id'      => (int)$partner->id,
            'event_type'      => $eventType,
            'idempotency_key' => $idemKey,
            'payload_hash'    => hash('sha256', (string)$rawBody),
            'received_at'     => date('Y-m-d H:i:s'),
            'status'          => 'received',
        ]);
        $inboxId = (int)$this->db->insert_id();

        try {
            if ($eventType === 'results.saved') {
                $updated = $this->handle_results_saved($partner, $data);
                $this->mark_inbox_ok($tblInbox, $inboxId, $partner);
                return $this->json_response(['success'=>true,'updated'=>(int)$updated]);
            }

            $this->db->where('id', $inboxId)->update($tblInbox, [
                'processed_at' => date('Y-m-d H:i:s'),
                'status'       => 'skipped',
                'last_error'   => 'Unsupported event type: '.$eventType,
            ]);
            return $this->json_response(['success'=>false,'error'=>'Unsupported event type'], 400);

        } catch (Throwable $e) {
            $this->db->where('id', $inboxId)->update($tblInbox, [
                'processed_at' => date('Y-m-d H:i:s'),
                'status'       => 'failed',
                'last_error'   => $e->getMessage(),
            ]);
            $this->db->where('id', (int)$partner->id)->update($p.'lims_partners', [
                'last_error' => $e->getMessage(),
            ]);
            return $this->json_response(['success'=>false,'error'=>$e->getMessage()], 500);
        }
    }

    /* ============================================================
     * HANDLERS
     * ============================================================ */

    private function handle_order_created($partner, array $data)
    {
        $p = db_prefix();
        $payloadOrder = $data['order'] ?? null;
        if (!is_array($payloadOrder)) throw new InvalidArgumentException('Missing order payload.');

        $orderUid = trim((string)($payloadOrder['order_uid'] ?? ''));
        if ($orderUid === '') throw new InvalidArgumentException('Missing order_uid.');

        // If exists already
        $existingOrder = $this->db->where('order_uid', $orderUid)->get($p.'lims_orders')->row();
        if ($existingOrder) return (int)$existingOrder->id;

        $partnerCustomerId = (int)($partner->customer_id ?? 0);
        if ($partnerCustomerId <= 0) throw new RuntimeException('Partner is not linked to a customer_id (client).');

        $subjectPayload = $payloadOrder['subject'] ?? null;
        if (!is_array($subjectPayload)) throw new InvalidArgumentException('Missing subject payload.');

        $originSubjectUid = trim((string)($subjectPayload['subject_uid'] ?? ''));
        if ($originSubjectUid === '') throw new InvalidArgumentException('Missing subject_uid.');

        // Upsert subject mapping
        $subject = $this->db->where('origin_partner_id', (int)$partner->id)
            ->where('origin_subject_uid', $originSubjectUid)
            ->get($p.'lims_subjects')->row();

        $subData = [
            'client_id'           => $partnerCustomerId,
            'subject_type'        => 'human',
            'internal_code'       => $subjectPayload['internal_code'] ?? null,
            'subject_name'        => $subjectPayload['subject_name'] ?? null,
            'first_name'          => $subjectPayload['first_name'] ?? null,
            'last_name'           => $subjectPayload['last_name'] ?? null,
            'id_number'           => $subjectPayload['id_number'] ?? null,
            'social_insurance_no' => $subjectPayload['social_insurance_no'] ?? null,
            'date_of_birth'       => $subjectPayload['date_of_birth'] ?? null,
            'gender'              => $subjectPayload['gender'] ?? null,
            'nationality'         => $subjectPayload['nationality'] ?? null,
            'phone'               => $subjectPayload['phone'] ?? null,
            'email'               => $subjectPayload['email'] ?? null,
            'address'             => $subjectPayload['address'] ?? null,
            'city'                => $subjectPayload['city'] ?? null,
            'zip'                 => $subjectPayload['zip'] ?? null,
            'country'             => $subjectPayload['country'] ?? null,
            'notes'               => $subjectPayload['notes'] ?? null,
            'language'            => $subjectPayload['language'] ?? null,
            'origin_partner_id'   => (int)$partner->id,
            'origin_subject_uid'  => $originSubjectUid,
        ];

        if (!empty($subData['internal_code'])) {
            $dup = $this->db->where('internal_code', (string)$subData['internal_code'])->get($p.'lims_subjects')->row();
            if ($dup && (!$subject || (int)$dup->id !== (int)$subject->id)) {
                $subData['internal_code'] = (string)$subData['internal_code'].'-P'.(int)$partner->id;
            }
        } else {
            if (method_exists($this->subjects_model, 'generate_internal_code')) {
                $subData['subject_uid'] = function_exists('lims_uuid_v4') ? lims_uuid_v4() : $this->uuid_v4_fallback();
            }
        }

        if ($subject) {
            $subjectId = (int)$subject->id;
            $this->db->where('id', $subjectId)->update($p.'lims_subjects', $subData);
        } else {
            $subData['subject_uid'] = function_exists('lims_uuid_v4') ? lims_uuid_v4() : null;
            $subData['created_at']  = date('Y-m-d H:i:s');
            $this->db->insert($p.'lims_subjects', $subData);
            $subjectId = (int)$this->db->insert_id();
        }

        // Create inbound order
        $orderRec = [
            'client_id'            => $partnerCustomerId,
            'contract_id'          => null,
            'external_ref'         => $payloadOrder['external_ref'] ?? null,
            'status'               => 'submitted',
            'priority'             => (int)($payloadOrder['priority'] ?? 0),
            'received_at'          => date('Y-m-d H:i:s'),
            'due_at'               => $payloadOrder['due_at'] ?? null,
            'order_barcode' => $this->apply_partner_barcode_prefix($partner, $payloadOrder['order_barcode'] ?? null),
            'notes'                => $payloadOrder['notes'] ?? null,
            'created_by'           => null,
            'created_at'           => date('Y-m-d H:i:s'),
            'subject_id'           => $subjectId,
            'order_uid'            => $orderUid,
            'partner_id'           => (int)$partner->id,
            'partner_direction'    => 'inbound',
            'partner_sync_status'  => 'received',
            'partner_sync_error'   => null,
        ];

        $this->db->insert($p.'lims_orders', $orderRec);
        $orderId = (int)$this->db->insert_id();

        // Ensure barcode exists
        $this->orders_model->ensure_barcode($orderId);
        $orderRow = $this->orders_model->get($orderId);
        $orderBarcode = $orderRow ? $orderRow->order_barcode : ($payloadOrder['order_barcode'] ?? null);

        // Insert order_items (if provided)
        $items = $payloadOrder['order_items'] ?? [];
        if (is_array($items) && $this->db->table_exists($p.'lims_order_items')) {
            foreach ($items as $it) {
                if (!is_array($it)) continue;
                $source_type = (string)($it['source_type'] ?? '');
                $code = trim((string)($it['code'] ?? ''));
                $name = (string)($it['name'] ?? '');
                $qty  = (float)($it['qty'] ?? 1);

                $source_id = null;
                if ($code !== '') {
                    if ($source_type === 'panel') {
                        $r = $this->db->where('code', $code)->get($p.'lims_panels')->row();
                        $source_id = $r ? (int)$r->id : null;
                    } elseif ($source_type === 'analysis') {
                        $r = $this->db->where('code', $code)->get($p.'lims_analyses')->row();
                        $source_id = $r ? (int)$r->id : null;
                    } elseif ($source_type === 'culture') {
                        $r = $this->db->where('code', $code)->get($p.'lims_cultures')->row();
                        $source_id = $r ? (int)$r->id : null;
                    }
                }

                $this->db->insert($p.'lims_order_items', [
                    'order_id'    => $orderId,
                    'source_type' => $source_type,
                    'source_id'   => $source_id,
                    'name'        => $name,
                    'qty'         => $qty,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Samples
        $samples = $payloadOrder['samples'] ?? [];
        if (!is_array($samples) || empty($samples)) {
            // No samples => still OK (order exists)
            return $orderId;
        }

        // Ensure sample types exist (auto-provision by code) based on payload
        $sampleTypePayloadByCode = [];
        foreach ($samples as $s) {
            if (!is_array($s)) continue;
            $c = trim((string)($s['sample_type_code'] ?? ''));
            if ($c === '') continue;
            $sampleTypePayloadByCode[$c] = [
                'code' => $c,
                'name' => (string)($s['sample_type_name'] ?? ''),
            ];
        }
        if (!empty($sampleTypePayloadByCode)) {
            foreach ($sampleTypePayloadByCode as $c => $meta) {
                $row = $this->db->where('code', $c)->get($p.'lims_sample_types')->row();
                if ($row) continue;
                // Minimal create: code + name (fallback to code)
                $this->db->insert($p.'lims_sample_types', [
                    'code'       => $c,
                    'name'       => trim((string)$meta['name']) !== '' ? (string)$meta['name'] : $c,
                    'active'     => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Map sample type by code
        $sampleTypeByCode = [];
        $stRows = $this->db->select('id, code')->get($p.'lims_sample_types')->result();
        foreach ($stRows as $st) {
            $sampleTypeByCode[(string)$st->code] = (int)$st->id;
        }

        $sampleIdByUid = [];

        foreach ($samples as $s) {
            if (!is_array($s)) continue;

            $sample_uid = (string)($s['sample_uid'] ?? '');
            if ($sample_uid === '') continue;

            $barcode = (string)($s['barcode'] ?? '');
            if ($barcode === '' && $orderBarcode) $barcode = (string)$orderBarcode;

            $stCode = (string)($s['sample_type_code'] ?? '');
            $stId = $sampleTypeByCode[$stCode] ?? null;

            $status = (string)($s['status'] ?? 'pending');
            $collected_at = $s['collected_at'] ?? null;
            $received_at  = $s['received_at'] ?? null;

            // idempotent per order+sample_uid
            $existingSample = $this->db->where('order_id', $orderId)->where('sample_uid', $sample_uid)->get($p.'lims_samples')->row();
            if ($existingSample) {
                $sampleIdByUid[$sample_uid] = (int)$existingSample->id;
                continue;
            }

            $this->db->insert($p.'lims_samples', [
                'order_id'       => $orderId,
                'sample_uid'     => $sample_uid,
                'barcode' => $this->apply_partner_barcode_prefix($partner, (string)($s['barcode'] ?? '')),
                'sample_type_id' => $stId,
                'status'         => $status,
                'collected_at'   => $collected_at ?: null,
                'received_at'    => $received_at ?: null,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $sampleIdByUid[$sample_uid] = (int)$this->db->insert_id();
        }

        // Materialize tests/cultures.
        // IMPORTANT: tests/order/{id} UI reads from tbllims_tests and tbllims_sample_cultures.
        // Orders coming from partners might include panels that don't exist locally yet; therefore we MUST
        // materialize from per-sample codes in payload first, then fall back to order_items panel expansion.

        // Build name hints from payload order_items (analysis/culture only; panels don't include their items)
        $analysisNameByCode = [];
        $cultureNameByCode  = [];
        $items = $payloadOrder['order_items'] ?? [];
        if (is_array($items)) {
            foreach ($items as $it) {
                if (!is_array($it)) continue;
                $t = (string)($it['source_type'] ?? '');
                $c = trim((string)($it['code'] ?? ''));
                if ($c === '') continue;
                if ($t === 'analysis') $analysisNameByCode[$c] = (string)($it['name'] ?? $c);
                if ($t === 'culture')  $cultureNameByCode[$c]  = (string)($it['name'] ?? $c);
            }
        }

        // Load existing analysis/culture id maps by code
        $analysisIdByCode = [];
        if ($this->db->table_exists($p.'lims_analyses')) {
            $rows = $this->db->select('id, code')->where('code IS NOT NULL', null, false)->get($p.'lims_analyses')->result();
            foreach ($rows as $r) {
                $code = trim((string)$r->code);
                if ($code !== '') $analysisIdByCode[$code] = (int)$r->id;
            }
        }
        $cultureIdByCode = [];
        if ($this->db->table_exists($p.'lims_cultures')) {
            $rows = $this->db->select('id, code')->where('code IS NOT NULL', null, false)->get($p.'lims_cultures')->result();
            foreach ($rows as $r) {
                $code = trim((string)$r->code);
                if ($code !== '') $cultureIdByCode[$code] = (int)$r->id;
            }
        }

        // 1) Prefer per-sample codes from payload
        $anyPayloadCodes = false;
        foreach ($samples as $s) {
            if (!is_array($s)) continue;
            $suid = (string)($s['sample_uid'] ?? '');
            if ($suid === '' || !isset($sampleIdByUid[$suid])) continue;
            $sid = (int)$sampleIdByUid[$suid];

            $sampleRow = $this->db->where('id', $sid)->get($p.'lims_samples')->row();
            $stId = $sampleRow ? (int)$sampleRow->sample_type_id : 0;

            $testCodes = $s['tests'] ?? [];
            $culCodes  = $s['cultures'] ?? [];
            if (is_array($testCodes) && count($testCodes) > 0) $anyPayloadCodes = true;
            if (is_array($culCodes)  && count($culCodes)  > 0) $anyPayloadCodes = true;

            // Analyses -> tbllims_tests
            if (is_array($testCodes)) {
                foreach ($testCodes as $codeRaw) {
                    $code = trim((string)$codeRaw);
                    if ($code === '') continue;

                    // Ensure analysis exists locally by code
                    $aid = $analysisIdByCode[$code] ?? 0;
                    if ($aid <= 0) {
                        $this->db->insert($p.'lims_analyses', [
                            'name'          => $analysisNameByCode[$code] ?? $code,
                            'code'          => $code,
                            'sample_type_id'=> $stId ?: null,
                            'active'        => 1,
                        ]);
                        $aid = (int)$this->db->insert_id();
                        if ($aid > 0) $analysisIdByCode[$code] = $aid;
                    }

                    if ($aid <= 0) continue;

                    $exists = $this->db->where('sample_id', $sid)
                        ->where('analysis_id', $aid)
                        ->get($p.'lims_tests')->row();
                    if ($exists) continue;

                    // NOTE: status enum in schema supports 'pending' (not 'draft')
                    $this->db->insert($p.'lims_tests', [
                        'sample_id'   => $sid,
                        'analysis_id' => $aid,
                        'status'      => 'pending',
                        'status_code' => 'pending',
                    ]);
                }
            }

            // Cultures -> tbllims_sample_cultures
            if ($this->db->table_exists($p.'lims_sample_cultures') && is_array($culCodes)) {
                foreach ($culCodes as $codeRaw) {
                    $code = trim((string)$codeRaw);
                    if ($code === '') continue;

                    $cid = $cultureIdByCode[$code] ?? 0;
                    if ($cid <= 0) {
                        $this->db->insert($p.'lims_cultures', [
                            'name'          => $cultureNameByCode[$code] ?? $code,
                            'code'          => $code,
                            'sample_type_id'=> $stId ?: null,
                            'active'        => 1,
                        ]);
                        $cid = (int)$this->db->insert_id();
                        if ($cid > 0) $cultureIdByCode[$code] = $cid;
                    }
                    if ($cid <= 0) continue;

                    $exists = $this->db->where('order_id', $orderId)
                        ->where('sample_id', $sid)
                        ->where('culture_id', $cid)
                        ->get($p.'lims_sample_cultures')->row();
                    if ($exists) continue;

                    $this->db->insert($p.'lims_sample_cultures', [
                        'order_id'   => $orderId,
                        'sample_id'  => $sid,
                        'culture_id' => $cid,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        // 2) Fallback: if payload had no per-sample codes, use local order_items expansion
        if (!$anyPayloadCodes) {
            $analysisIdsFromItems = [];
            $cultureIdsFromItems  = [];
            $panelIdsFromItems    = [];

            $oi = $this->db->where('order_id', $orderId)->get($p.'lims_order_items')->result();
            foreach ($oi as $x) {
                if ($x->source_type === 'analysis' && $x->source_id) $analysisIdsFromItems[] = (int)$x->source_id;
                if ($x->source_type === 'culture'  && $x->source_id) $cultureIdsFromItems[]  = (int)$x->source_id;
                if ($x->source_type === 'panel'    && $x->source_id) $panelIdsFromItems[]    = (int)$x->source_id;
            }
            $analysisIdsFromItems = array_values(array_unique($analysisIdsFromItems));
            $cultureIdsFromItems  = array_values(array_unique($cultureIdsFromItems));
            $panelIdsFromItems    = array_values(array_unique($panelIdsFromItems));

            $panelAnalyses = [];
            $panelCultures = [];
            if (!empty($panelIdsFromItems) && $this->db->table_exists($p.'lims_panel_items')) {
                $pa = $this->db->select('pi.panel_id, a.id AS analysis_id, a.sample_type_id')
                    ->from($p.'lims_panel_items pi')
                    ->join($p.'lims_analyses a', 'a.id = pi.analysis_id', 'left')
                    ->where_in('pi.panel_id', $panelIdsFromItems)
                    ->where('pi.analysis_id IS NOT NULL', null, false)
                    ->get()->result();
                foreach ($pa as $r) {
                    if (!$r->analysis_id) continue;
                    $panelAnalyses[] = ['analysis_id'=>(int)$r->analysis_id, 'sample_type_id'=>(int)$r->sample_type_id];
                }

                $pc = $this->db->select('pi.panel_id, c.id AS culture_id, c.sample_type_id')
                    ->from($p.'lims_panel_items pi')
                    ->join($p.'lims_cultures c', 'c.id = pi.culture_id', 'left')
                    ->where_in('pi.panel_id', $panelIdsFromItems)
                    ->where('pi.culture_id IS NOT NULL', null, false)
                    ->get()->result();
                foreach ($pc as $r) {
                    if (!$r->culture_id) continue;
                    $panelCultures[] = ['culture_id'=>(int)$r->culture_id, 'sample_type_id'=>(int)$r->sample_type_id];
                }
            }

            foreach ($sampleIdByUid as $sid) {
                $sid = (int)$sid;
                $sampleRow = $this->db->where('id', $sid)->get($p.'lims_samples')->row();
                $stId = $sampleRow ? (int)$sampleRow->sample_type_id : 0;

                $analysesToAdd = $analysisIdsFromItems;
                foreach ($panelAnalyses as $pa) {
                    if ((int)$pa['sample_type_id'] === $stId) $analysesToAdd[] = (int)$pa['analysis_id'];
                }
                $analysesToAdd = array_values(array_unique(array_filter($analysesToAdd)));
                foreach ($analysesToAdd as $aid) {
                    $exists = $this->db->where('sample_id', $sid)
                        ->where('analysis_id', (int)$aid)
                        ->get($p.'lims_tests')->row();
                    if ($exists) continue;

                    $this->db->insert($p.'lims_tests', [
                        'sample_id'   => $sid,
                        'analysis_id' => (int)$aid,
                        'status'      => 'pending',
                        'status_code' => 'pending',
                    ]);
                }

                if ($this->db->table_exists($p.'lims_sample_cultures')) {
                    $culturesToAdd = $cultureIdsFromItems;
                    foreach ($panelCultures as $pc) {
                        if ((int)$pc['sample_type_id'] === $stId) $culturesToAdd[] = (int)$pc['culture_id'];
                    }
                    $culturesToAdd = array_values(array_unique(array_filter($culturesToAdd)));
                    foreach ($culturesToAdd as $cid) {
                        $exists = $this->db->where('order_id', $orderId)
                            ->where('sample_id', $sid)
                            ->where('culture_id', (int)$cid)
                            ->get($p.'lims_sample_cultures')->row();
                        if ($exists) continue;
                        $this->db->insert($p.'lims_sample_cultures', [
                            'order_id'   => $orderId,
                            'sample_id'  => $sid,
                            'culture_id' => (int)$cid,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        }

        // Activity log
        if ($this->db->table_exists($p.'lims_order_activity')) {
            $this->db->insert($p.'lims_order_activity', [
                'order_id'   => $orderId,
                'action'     => 'partner_order_received',
                'message'    => 'Order received from partner sync (order.created)',
                'meta'       => json_encode(['partner_id'=>(int)$partner->id,'order_uid'=>$orderUid]),
                'staff_id'   => null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return $orderId;
    }

    private function handle_samples_status($partner, array $data)
    {
        $p = db_prefix();
        $payloadOrder = $data['order'] ?? null;
        if (!is_array($payloadOrder)) throw new InvalidArgumentException('Missing order payload.');

        $orderUid = trim((string)($payloadOrder['order_uid'] ?? ''));
        if ($orderUid === '') throw new InvalidArgumentException('Missing order_uid.');

        $order = $this->db->where('order_uid', $orderUid)->get($p.'lims_orders')->row();
        if (!$order) return 0;

        $samples = $payloadOrder['samples'] ?? [];
        if (!is_array($samples)) return 0;

        $updated = 0;
        foreach ($samples as $s) {
            if (!is_array($s)) continue;
            $sample_uid = (string)($s['sample_uid'] ?? '');
            if ($sample_uid === '') continue;

            $row = $this->db->where('order_id', (int)$order->id)->where('sample_uid', $sample_uid)->get($p.'lims_samples')->row();
            if (!$row) continue;

            $newStatus = (string)($s['status'] ?? $row->status);
            $newCollected = $s['collected_at'] ?? $row->collected_at;
            $newReceived  = $s['received_at']  ?? $row->received_at;

            $this->db->where('id', (int)$row->id)->update($p.'lims_samples', [
                'status'       => $newStatus,
                'collected_at' => $newCollected ?: null,
                'received_at'  => $newReceived ?: null,
            ]);
            $updated++;

            if ($this->db->table_exists($p.'lims_order_activity')) {
                $this->db->insert($p.'lims_order_activity', [
                    'order_id'   => (int)$order->id,
                    'action'     => 'partner_sample_status_updated',
                    'message'    => 'Sample status updated from partner sync (samples.status)',
                    'meta'       => json_encode(['sample_uid'=>$sample_uid,'status'=>$newStatus]),
                    'staff_id'   => null,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        return $updated;
    }

    private function handle_results_saved($partner, array $data)
    {
        $p = db_prefix();
        $payloadOrder = $data['order'] ?? null;
        if (!is_array($payloadOrder)) throw new InvalidArgumentException('Missing order payload.');

        $orderUid = trim((string)($payloadOrder['order_uid'] ?? ''));
        if ($orderUid === '') throw new InvalidArgumentException('Missing order_uid.');

        $order = $this->db->where('order_uid', $orderUid)->get($p.'lims_orders')->row();
        if (!$order) return 0;

        $updated = 0;

        // Upsert tests + insert lims_results
        $tests = $payloadOrder['tests'] ?? [];
        if (is_array($tests)) {
            foreach ($tests as $t) {
                if (!is_array($t)) continue;

                $sample_uid = (string)($t['sample_uid'] ?? '');
                $analysis_code = (string)($t['analysis_code'] ?? '');
                if ($sample_uid === '' || $analysis_code === '') continue;

                $srow = $this->db->where('order_id', (int)$order->id)->where('sample_uid', $sample_uid)->get($p.'lims_samples')->row();
                if (!$srow) continue;

                $arow = $this->db->where('code', $analysis_code)->get($p.'lims_analyses')->row();
                if (!$arow) continue;

                $testRow = $this->db->where('sample_id', (int)$srow->id)->where('analysis_id', (int)$arow->id)->get($p.'lims_tests')->row();
                if (!$testRow) {
                    $this->db->insert($p.'lims_tests', [
                        'sample_id'   => (int)$srow->id,
                        'analysis_id' => (int)$arow->id,
                        'status'      => 'draft',
                        'status_code' => 'draft',
                        'created_at'  => date('Y-m-d H:i:s'),
                    ]);
                    $testId = (int)$this->db->insert_id();
                    $testRow = $this->db->where('id', $testId)->get($p.'lims_tests')->row();
                }

                $newStatus = (string)($t['status'] ?? $testRow->status);
                $newCode   = (string)($t['status_code'] ?? $testRow->status_code);
                $completed = $t['completed_at'] ?? $testRow->completed_at;

                $this->db->where('id', (int)$testRow->id)->update($p.'lims_tests', [
                    'status'       => $newStatus,
                    'status_code'  => $newCode ?: $newStatus,
                    'completed_at' => $completed ?: null,
                ]);

                // Insert result row (latest snapshot)
                $res = $t['result'] ?? null;
                if (is_array($res) && $this->db->table_exists($p.'lims_results')) {
                    $ins = [
                        'test_id'     => (int)$testRow->id,
                        'unit'        => $res['unit'] ?? null,
                        'flag'        => $res['flag'] ?? null,
                        'measured_at' => $res['measured_at'] ?? date('Y-m-d H:i:s'),
                        'created_at'  => date('Y-m-d H:i:s'),
                    ];
                    if (isset($res['value_numeric']) && $res['value_numeric'] !== null) {
                        $ins['value_numeric'] = (float)$res['value_numeric'];
                    } else {
                        $ins['value_text'] = $res['value_text'] ?? null;
                    }
                    $this->db->insert($p.'lims_results', $ins);
                }

                // Audit trail
                if ($this->db->table_exists($p.'lims_test_audit')) {
                    $this->db->insert($p.'lims_test_audit', [
                        'test_id'    => (int)$testRow->id,
                        'action'     => 'result_synced',
                        'old_status' => $testRow->status ?? null,
                        'new_status' => $newStatus,
                        'old_value'  => null,
                        'new_value'  => json_encode($t, JSON_UNESCAPED_UNICODE),
                        'reason'     => 'results.saved sync',
                        'staff_id'   => null,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                $updated++;
            }
        }

        // Culture results upsert
        $crows = $payloadOrder['culture_results'] ?? [];
        if (is_array($crows) && $this->db->table_exists($p.'lims_culture_results')) {
            foreach ($crows as $cr) {
                if (!is_array($cr)) continue;
                $sample_uid = (string)($cr['sample_uid'] ?? '');
                $culture_code = (string)($cr['culture_code'] ?? '');
                if ($sample_uid === '' || $culture_code === '') continue;

                $srow = $this->db->where('order_id', (int)$order->id)->where('sample_uid', $sample_uid)->get($p.'lims_samples')->row();
                if (!$srow) continue;

                $crow = $this->db->where('code', $culture_code)->get($p.'lims_cultures')->row();
                if (!$crow) continue;

                $existing = $this->db->where('order_id', (int)$order->id)
                    ->where('sample_id', (int)$srow->id)
                    ->where('culture_id', (int)$crow->id)
                    ->get($p.'lims_culture_results')->row();

                $dataUp = [
                    'comment'         => $cr['comment'] ?? null,
                    'option_value_id' => $cr['option_value_id'] ?? null,
                ];

                if ($existing) {
                    $this->db->where('id', (int)$existing->id)->update($p.'lims_culture_results', $dataUp);
                } else {
                    $dataUp['order_id']   = (int)$order->id;
                    $dataUp['sample_id']  = (int)$srow->id;
                    $dataUp['culture_id'] = (int)$crow->id;
                    $dataUp['created_at'] = date('Y-m-d H:i:s');
                    $this->db->insert($p.'lims_culture_results', $dataUp);
                }
                $updated++;
            }
        }

        // Report notes upsert
        $rn = $payloadOrder['report_notes'] ?? null;
        if (is_array($rn) && $this->db->table_exists($p.'lims_order_report_notes')) {
            $existing = $this->db->where('order_id', (int)$order->id)->get($p.'lims_order_report_notes')->row();
            $up = [
                'free_text'     => $rn['free_text'] ?? null,
                'note_ids_json' => $rn['note_ids_json'] ?? '[]',
                'updated_by'    => null,
            ];
            if ($existing) {
                $this->db->where('order_id', (int)$order->id)->update($p.'lims_order_report_notes', $up);
            } else {
                $up['order_id'] = (int)$order->id;
                $up['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert($p.'lims_order_report_notes', $up);
            }
            $updated++;
        }

        if ($this->db->table_exists($p.'lims_order_activity')) {
            $this->db->insert($p.'lims_order_activity', [
                'order_id'   => (int)$order->id,
                'action'     => 'partner_results_received',
                'message'    => 'Results updated from partner sync (results.saved)',
                'meta'       => json_encode(['partner_id'=>(int)$partner->id,'order_uid'=>$orderUid]),
                'staff_id'   => null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return $updated;
    }

    private function mark_inbox_ok($tblInbox, $inboxId, $partner)
    {
        $p = db_prefix();
        $this->db->where('id', (int)$inboxId)->update($tblInbox, [
            'processed_at' => date('Y-m-d H:i:s'),
            'status'       => 'processed',
            'last_error'   => null,
        ]);
        $this->db->where('id', (int)$partner->id)->update($p.'lims_partners', [
            'last_seen_at' => date('Y-m-d H:i:s'),
            'last_error'   => null,
        ]);
    }

    /* ============================================================
     * AUTH / JSON HELPERS
     * ============================================================ */

    private function authenticate_request()
    {
        $apiKey = trim((string)$this->input->get_request_header('X-LIMS-API-KEY', true));
        if ($apiKey === '') {
            $this->json_response(['success'=>false,'error'=>'Missing X-LIMS-API-KEY'], 401);
            exit;
        }

        $p = db_prefix();
        $partner = $this->db->where('api_key', $apiKey)->get($p.'lims_partners')->row();
        if (!$partner || (int)$partner->active !== 1) {
            $this->json_response(['success'=>false,'error'=>'Invalid partner API key'], 403);
            exit;
        }
        if (isset($partner->sync_enabled) && (int)$partner->sync_enabled !== 1) {
            $this->json_response(['success'=>false,'error'=>'Partner sync disabled'], 403);
            exit;
        }

        // Optional signature
        $sig = trim((string)$this->input->get_request_header('X-LIMS-SIGNATURE', true));
        $idemKey = trim((string)$this->input->get_request_header('X-LIMS-IDEMPOTENCY-KEY', true));
        $secret = trim((string)($partner->api_secret ?? ''));
        if ($secret !== '' && $sig !== '' && $idemKey !== '') {
            $rawBody = file_get_contents('php://input');
            $expected = hash_hmac('sha256', $idemKey."\n".$rawBody, $secret);
            if (!hash_equals($expected, $sig)) {
                $this->json_response(['success'=>false,'error'=>'Invalid signature'], 403);
                exit;
            }
        }

        return $partner;
    }

    private function json_response($data, $status = 200)
    {
        $this->output
            ->set_status_header((int)$status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
	private function uuid_v4_fallback()
	{
		$data = random_bytes(16);
		$data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
		$data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
	private function apply_partner_barcode_prefix($partner, $barcode)
	{
		$barcode = trim((string)$barcode);
		if ($barcode === '') return $barcode;

		$prefix = trim((string)($partner->barcode_prefix ?? ''));
		if ($prefix === '') return $barcode;

		// Normalize prefix format (uppercase + ensure trailing dash)
		$prefix = strtoupper($prefix);
		if (substr($prefix, -1) !== '-') $prefix .= '-';

		// If already prefixed with THIS partner prefix, keep it
		if (stripos($barcode, $prefix) === 0) return $barcode;

		// If barcode already looks prefixed (e.g. ABC-xxxx), do not double-prefix
		// This protects against "NTL-NTL-..." and also if sender already stores prefixed barcodes.
		if (preg_match('/^[A-Z0-9]{2,10}-/i', $barcode)) return $barcode;

		return $prefix . $barcode;
	}


}
