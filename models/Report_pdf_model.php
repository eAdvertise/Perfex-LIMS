<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_pdf_model extends App_Model
{
    public function get_order_report_payload($order_id)
    {
        $order_id = (int)$order_id;
        if ($order_id <= 0) {
            return null;
        }

        $p = db_prefix();

        // ---------------------------
        // ORDER + SUBJECT + CLIENT
        // ---------------------------
        $subjectsTable = $p . 'lims_subjects';
        $subjectTypeExpr = $this->db->field_exists('type', $subjectsTable)
            ? 's.type'
            : ($this->db->field_exists('subject_type', $subjectsTable) ? 's.subject_type' : 'NULL');

        $subjectLangExpr = $this->db->field_exists('language', $subjectsTable)
            ? 's.language'
            : 'NULL';

        $this->db->select('o.*');
        $this->db->select('s.subject_name, s.first_name, s.last_name, s.internal_code, s.id_number, s.phone, s.email');
        $this->db->select($subjectTypeExpr . ' AS subject_type', false);
        $this->db->select($subjectLangExpr . ' AS subject_language', false);
        $this->db->select('c.company AS client_company');
        $clientsTable = $p . 'clients';
		if ($this->db->field_exists('default_language', $clientsTable)) {
			$this->db->select('c.default_language AS client_language');
		} else {
			$this->db->select('NULL AS client_language', false);
		}

		$this->db->from($p . 'lims_orders o');
        $this->db->join($subjectsTable . ' s', 's.id = o.subject_id', 'left');
        $this->db->join($p . 'clients c', 'c.userid = o.client_id', 'left');
        $this->db->where('o.id', $order_id);
        $order = $this->db->get()->row();
		
        if (!$order) {
            return null;
        }

        // ---------------------------
        // SETTINGS (resolved paths + lang)
        // ---------------------------
        $settings = $this->build_report_settings($order);

        // ---------------------------
        // SAMPLES (+ appointment)
        // ---------------------------
        $this->db->select('sm.*');
        $this->db->select('st.name AS sample_type_name, st.container AS sample_container');
        $this->db->select('ap.appointment_at, ap.assigned_staff AS sampler_staff');
        $this->db->from($p . 'lims_samples sm');
        $this->db->join($p . 'lims_sample_types st', 'st.id = sm.sample_type_id', 'left');
        $this->db->join($p . 'lims_appointments ap', 'ap.id = sm.appointment_id', 'left');
        $this->db->where('sm.order_id', $order_id);
        $this->db->order_by('sm.id', 'asc');
        $samples = $this->db->get()->result();

        $sampleIds = array_map(function ($s) { return (int)$s->id; }, $samples);

        // If no samples, still allow PDF (will show empty)
        if (empty($sampleIds)) {
            return [
                'order'              => $order,
                'settings'           => $settings,
                'samples'            => [],
                'resultRowsBySample' => [],
                'analysisAtBySample' => [],
                'reportNotesBySample'=> [],
                'culturesBySample'   => [],
                'cultureResultsByKey'=> [],
                'signature'          => $this->build_signature($order, $settings),
            ];
        }

        // ---------------------------
        // STAFF names map (sampler/analyst/signer)
        // ---------------------------
        $staffIds = [];
        foreach ($samples as $sm) {
            if (!empty($sm->sampler_staff)) {
                $staffIds[(int)$sm->sampler_staff] = true;
            }
        }
        if (!empty($order->signed_by)) {
            $staffIds[(int)$order->signed_by] = true;
        }

        // tests may add analyst ids later; build after fetching tests too

        // ---------------------------
        // TESTS + ANALYSES
        // ---------------------------
        $analysesTable = $p . 'lims_analyses';
        $unitsCol = $this->db->field_exists('unit', $analysesTable) ? 'a.unit' : 'a.units_ucum';

        $this->db->select('t.id AS test_id, t.sample_id, t.analysis_id, t.completed_at, t.assigned_staff');
        $this->db->select('a.name AS analysis_name, a.method AS analysis_method, a.decimal_places, ' . $unitsCol . ' AS analysis_units', false);
        $this->db->from($p . 'lims_tests t');
        $this->db->join($analysesTable . ' a', 'a.id = t.analysis_id', 'left');
        $this->db->where_in('t.sample_id', $sampleIds);
        $this->db->order_by('t.sample_id', 'asc');
        $this->db->order_by('t.id', 'asc');
        $tests = $this->db->get()->result();

        $testIds = [];
        foreach ($tests as $t) {
            $testIds[] = (int)$t->test_id;
            if (!empty($t->assigned_staff)) {
                $staffIds[(int)$t->assigned_staff] = true;
            }
        }

        $staffMap = $this->load_staff_map(array_keys($staffIds));

        // attach sampler name to samples
        foreach ($samples as $sm) {
            $sid = (int)$sm->sampler_staff;
            $sm->sampler_name = $sid && isset($staffMap[$sid]) ? $staffMap[$sid] : '';
        }

        // ---------------------------
        // RESULTS (latest per test)
        // ---------------------------
        $latestByTest = [];
        if (!empty($testIds)) {
            $this->db->from($p . 'lims_results r');
            $this->db->where_in('r.test_id', $testIds);
            $this->db->order_by('r.measured_at', 'desc');
            $this->db->order_by('r.id', 'desc');
            $resRows = $this->db->get()->result();

            foreach ($resRows as $r) {
                $tid = (int)$r->test_id;
                if (!isset($latestByTest[$tid])) {
                    $latestByTest[$tid] = $r;
                }
            }
        }

        // ---------------------------
        // BUILD resultRowsBySample + analysisAtBySample
        // ---------------------------
        $resultRowsBySample = [];
        $analysisAtBySample = [];

        foreach ($tests as $t) {
            $sid = (int)$t->sample_id;
            if (!isset($resultRowsBySample[$sid])) {
                $resultRowsBySample[$sid] = [];
            }

            $r = $latestByTest[(int)$t->test_id] ?? null;

            // Resolve result value safely across schema variants
            $value = '';
            if ($r) {
                if (property_exists($r, 'value') && $r->value !== null && $r->value !== '') {
                    $value = (string)$r->value;
                } elseif (property_exists($r, 'value_text') && $r->value_text !== null && $r->value_text !== '') {
                    $value = (string)$r->value_text;
                } elseif (property_exists($r, 'value_numeric') && $r->value_numeric !== null) {
                    $value = (string)$r->value_numeric;
                } elseif (property_exists($r, 'result') && $r->result !== null && $r->result !== '') {
                    $value = (string)$r->result;
                } elseif (property_exists($r, 'final_value') && $r->final_value !== null && $r->final_value !== '') {
                    $value = (string)$r->final_value;
                } elseif (property_exists($r, 'result_text') && $r->result_text !== null && $r->result_text !== '') {
                    $value = (string)$r->result_text;
                }
            }
            $flag  = $r ? (string)$r->flag  : '';
            $unit  = $r && !empty($r->unit) ? (string)$r->unit : (string)($t->analysis_units ?? '');

            // numeric formatting
            if ($value !== '' && is_numeric($value) && $t->decimal_places !== null && $t->decimal_places !== '') {
                $dp = (int)$t->decimal_places;
                $value = number_format((float)$value, $dp, '.', '');
            }

            $resultText = trim($value . ($flag !== '' ? (' ' . $flag) : ''));

            $resultRowsBySample[$sid][] = [
                'parameter' => (string)($t->analysis_name ?? ''),
                'method'    => (string)($t->analysis_method ?? ''),
                'units'     => (string)$unit,
                'result'    => $resultText,
            ];

            // analysis date: take measured_at if available else completed_at
            $dt = null;
            if ($r && !empty($r->measured_at)) {
                $dt = $r->measured_at;
            } elseif (!empty($t->completed_at)) {
                $dt = $t->completed_at;
            }
            if ($dt) {
                if (empty($analysisAtBySample[$sid]) || strtotime($dt) > strtotime($analysisAtBySample[$sid])) {
                    $analysisAtBySample[$sid] = $dt;
                }
            }
        }

        // ---------------------------
        // REPORT NOTES (schema-safe)
        // ---------------------------
        $reportNotesBySample = $this->fetch_report_notes_by_sample($order_id, $sampleIds, $testIds, $settings['lang']);

        // ---------------------------
        // CULTURES (order items + culture_results)
        // ---------------------------
        $culturesBySample = [];
        $cultureResultsByKey = [];

        // cultures ordered on this order
        $this->db->select('oi.source_id AS culture_id, cu.name AS culture_name, cu.code AS culture_code, cu.sample_type_id');
        $this->db->from($p . 'lims_order_items oi');
        $this->db->join($p . 'lims_cultures cu', 'cu.id = oi.source_id', 'left');
        $this->db->where('oi.order_id', $order_id);
        $this->db->where('oi.source_type', 'culture');
        $this->db->order_by('oi.id', 'asc');
        $cultureItems = $this->db->get()->result();

        $cultureIds = [];
        foreach ($cultureItems as $ci) {
            if (!empty($ci->culture_id)) {
                $cultureIds[(int)$ci->culture_id] = true;
            }
        }

        // match cultures per sample by sample_type_id (NULL => all)
        foreach ($samples as $sm) {
            $sid = (int)$sm->id;
            $culturesBySample[$sid] = [];
            foreach ($cultureItems as $ci) {
                $match = empty($ci->sample_type_id) || (int)$ci->sample_type_id === (int)$sm->sample_type_id;
                if ($match) {
                    $culturesBySample[$sid][] = [
                        'id'   => (int)$ci->culture_id,
                        'code' => (string)($ci->culture_code ?? ''),
                        'name' => (string)($ci->culture_name ?? ''),
                    ];
                }
            }
        }

        // culture results by (sample_id, culture_id)
        if (!empty($cultureIds)) {
            $this->db->from($p . 'lims_culture_results cr');
            $this->db->where_in('cr.sample_id', $sampleIds);
            $this->db->where_in('cr.culture_id', array_keys($cultureIds));
            $rows = $this->db->get()->result();

            foreach ($rows as $cr) {
                $key = ((int)$cr->sample_id) . ':' . ((int)$cr->culture_id);
                $cultureResultsByKey[$key] = [
                    'result_text'      => (string)($cr->result_text ?? ''),
                    'selected_options' => (string)($cr->selected_options_json ?? ''),
                    'notes'            => (string)($cr->notes ?? ''),
                ];
            }
        }

        // ---------------------------
        // SIGNATURE (signatures table + staff name)
        // ---------------------------
        $signature = $this->build_signature($order, $settings, $staffMap);

        return [
            'order'               => $order,
            'settings'            => $settings,
            'samples'             => $samples,
            'resultRowsBySample'  => $resultRowsBySample,
            'analysisAtBySample'  => $analysisAtBySample,
            'reportNotesBySample' => $reportNotesBySample,
            'culturesBySample'    => $culturesBySample,
            'cultureResultsByKey' => $cultureResultsByKey,
            'signature'           => $signature,
        ];
    }

    // ---------------------------------------------------------
    // SETTINGS
    // ---------------------------------------------------------
    private function build_report_settings($order)
    {
        $lang = $this->resolve_lang($order);

        $fontFamily = (string)(get_option('lims_report_font_family') ?: 'dejavuserif');
        $fontSize   = (float)(get_option('lims_report_font_size') ?: 10);

        // Files (stored as filenames in options)
        $logoFile   = (string)get_option('lims_report_logo');
        $bgFile     = (string)get_option('lims_report_background_image');
        $footerFile = (string)get_option('lims_report_footer_image');

        $logoPath   = $this->resolve_upload_path('uploads/lims/report/logo/', $logoFile);
        $bgPath     = $this->resolve_upload_path('uploads/lims/report/background/', $bgFile);
        $footerPath = $this->resolve_upload_path('uploads/lims/report/footer/', $footerFile);

        // Fallback logo: company dark logo
        if (!$logoPath) {
            $logoPath = $this->resolve_company_dark_logo_path();
        }
		// Signature toggle (support multiple option keys)
		$optShow = get_option('lims_report_show_signature');
		if ($optShow === null || $optShow === '') {
			$optShow = get_option('lims_report_pdf_show_signature'); // fallback key (αν το έχεις αλλιώς)
		}


        $settings = [
            'lang'              => $lang,

            'font_family'       => $fontFamily,
            'font_size'         => $fontSize,

            'background_path'   => $bgPath,
            'logo_path'         => $logoPath,
            'footer_image_path' => $footerPath,

            'logo_width'        => (float)(get_option('lims_report_logo_width') ?: 90),
            'logo_x'            => get_option('lims_report_logo_x'),
            'logo_y'            => (float)(get_option('lims_report_logo_y') ?: 8),

            'header_subtitle'   => $lang === 'en'
                ? (string)get_option('lims_report_header_subtitle_en')
                : (string)get_option('lims_report_header_subtitle_el'),

            'heading'           => $lang === 'en'
                ? (string)get_option('lims_report_heading_en')
                : (string)get_option('lims_report_heading_el'),

            'top_right_code'    => trim(
                (string)get_option('lims_report_topright_line1') . "\n" .
                (string)get_option('lims_report_topright_line2')
            ),

            // footer texts layering (your required order)
            'pre_footer_note'   => $lang === 'en'
                ? (string)get_option('lims_report_pre_footer_note_english')
                : (string)get_option('lims_report_pre_footer_note_greek'),

            'footer_text_lang'  => $lang === 'en'
                ? (string)get_option('lims_report_footer_text_english')
                : (string)get_option('lims_report_footer_text_greek'),

            'footer_text'       => (string)get_option('lims_report_footer_text'),

            // footer positioning (from settings)
            'footer_img_x'      => (float)(get_option('lims_report_footer_img_x') ?: 95),
            'footer_img_y'      => (float)(get_option('lims_report_footer_img_y') ?: 240),
            'footer_img_w'      => (float)(get_option('lims_report_footer_img_w') ?: 20),

            'footer_gap_mm'     => (float)(get_option('lims_report_footer_gap_mm') ?: 20),
            'footer_bottom_mm'  => (float)(get_option('lims_report_footer_bottom_margin_mm') ?: 10),

            'footer_line_thickness' => (float)(get_option('lims_report_footer_line_thickness') ?: 0.4),
            'footer_line_r'         => (int)(get_option('lims_report_footer_line_r') ?: 0),
            'footer_line_g'         => (int)(get_option('lims_report_footer_line_g') ?: 150),
            'footer_line_b'         => (int)(get_option('lims_report_footer_line_b') ?: 0),
            'footer_line_x1'        => (float)(get_option('lims_report_footer_line_x1') ?: 20),
            'footer_line_x2'        => (float)(get_option('lims_report_footer_line_x2') ?: 190),
            'footer_line_offset_mm' => (float)(get_option('lims_report_footer_line_offset_mm') ?: 2),
			'show_signature'        => ((int)$optShow === 1),
			'signature_width_mm'    => (float)(get_option('lims_report_signature_width_mm') ?: 42),
			'signature_default_staff_id' => (int)(get_option('lims_report_signature_staff_id') ?: 0),
        ];

        return $settings;
    }

    private function resolve_lang($order)
    {
        $useSubjectLang = (int)get_option('lims_report_language_from_subject') === 1;

        $default = (string)(get_option('lims_report_default_language') ?: 'greek');
        $default = strtolower($default);
        $defaultLang = ($default === 'english' || $default === 'en') ? 'en' : 'el';

        if (!$useSubjectLang) {
            return $defaultLang;
        }

        $raw = strtolower(trim((string)($order->subject_language ?? '')));
        if ($raw === 'en' || $raw === 'english') {
            return 'en';
        }
        if ($raw === 'el' || $raw === 'greek' || $raw === 'gr') {
            return 'el';
        }
		
        // If unknown/empty, fallback to default
        return $defaultLang;
    }

    private function resolve_upload_path($relativeDir, $filename)
    {
        $filename = trim((string)$filename);
        if ($filename === '') {
            return null;
        }

        $base = defined('FCPATH') ? rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR : rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . '../';
        $path = $base . trim($relativeDir, '/\\') . DIRECTORY_SEPARATOR . ltrim($filename, '/\\');

        return is_file($path) ? $path : null;
    }

    private function resolve_company_dark_logo_path()
    {
        // Perfex default company upload path
        if (function_exists('get_upload_path_by_type')) {
            $companyPath = rtrim(get_upload_path_by_type('company'), '/\\') . DIRECTORY_SEPARATOR;
            $dark = (string)get_option('company_logo_dark');
            if ($dark && is_file($companyPath . $dark)) {
                return $companyPath . $dark;
            }

            $light = (string)get_option('company_logo');
            if ($light && is_file($companyPath . $light)) {
                return $companyPath . $light;
            }
        }

        return null;
    }

    // ---------------------------------------------------------
    // STAFF helper
    // ---------------------------------------------------------
    private function load_staff_map(array $staffIds)
    {
        $staffIds = array_values(array_filter(array_map('intval', $staffIds)));
        $map = [];

        if (empty($staffIds)) {
            return $map;
        }

        $p = db_prefix();
        $this->db->select('staffid, firstname, lastname');
        $this->db->from($p . 'staff');
        $this->db->where_in('staffid', $staffIds);
        $rows = $this->db->get()->result();

        foreach ($rows as $r) {
            $map[(int)$r->staffid] = trim($r->firstname . ' ' . $r->lastname);
        }

        return $map;
    }

    // ---------------------------------------------------------
    // SIGNATURE
    // ---------------------------------------------------------
    private function build_signature($order, array $settings, array $staffMap = [])
	{
		$p = db_prefix();

		// Try multiple possible fields for signer staff id
		$staffId = 0;
		foreach (['signed_by', 'signer_staff_id', 'signed_staff_id', 'approved_by', 'verified_by'] as $k) {
			if (!empty($order->{$k})) {
				$staffId = (int)$order->{$k};
				break;
			}
		}

		// Fallback: default signer from settings (optional)
		if ($staffId <= 0) {
			$staffId = (int)($settings['signature_default_staff_id'] ?? 0);
		}

		// Signed at
		$signedAt = null;
		foreach (['signed_at', 'approved_at', 'verified_at'] as $k) {
			if (!empty($order->{$k})) {
				$signedAt = $order->{$k};
				break;
			}
		}

		$sig = [
			'staff_id'   => $staffId,
			'name'       => $staffId && isset($staffMap[$staffId]) ? $staffMap[$staffId] : '',
			'title'      => '',
			'license_no' => '',
			'extra_line' => '',
			'image_path' => null,
			'signed_at'  => $signedAt,
			'width_mm'   => (float)($settings['signature_width_mm'] ?? 42),
		];

		if ($staffId <= 0) {
			return $sig;
		}

		// Pull signature path from tblstaff.lims_signature (FULL PATH as per your system)
		$this->db->select('staffid, firstname, lastname');
		if ($this->db->field_exists('lims_signature', $p . 'staff')) {
			$this->db->select('lims_signature');
		} else {
			// If column does not exist, no signature
			return $sig;
		}

		$st = $this->db->where('staffid', $staffId)->get($p . 'staff')->row();
		if (!$st) {
			return $sig;
		}

		if ($sig['name'] === '') {
			$sig['name'] = trim((string)$st->firstname . ' ' . (string)$st->lastname);
		}

		$raw = trim((string)($st->lims_signature ?? ''));

		// Normalize/resolve to a local file path usable by TCPDF Image()
		$path = $this->resolve_staff_signature_path($raw);

		if ($path && is_file($path)) {
			$sig['image_path'] = $path;
		} else {
			// optional logging for debugging
			if ($raw !== '') {
				log_message('error', '[LIMS PDF] Staff signature not found. staffid=' . $staffId . ' lims_signature=' . $raw . ' resolved=' . ($path ?: 'NULL'));
			}
		}

		return $sig;
	}


    // ---------------------------------------------------------
    // REPORT NOTES (schema-safe)
    // ---------------------------------------------------------
    private function fetch_report_notes_by_sample($order_id, array $sampleIds, array $testIds, $lang)
    {
        $p = db_prefix();

        // default empty per sample
        $out = [];
        foreach ($sampleIds as $sid) {
            $out[(int)$sid] = ['free_text' => '', 'items' => []];
        }

        // Prebuilt notes table (id, code, text_greek/text_english OR el/en variants)
        $notesTable = $p . 'lims_report_notes';
        $hasNotesTable = $this->db->table_exists($notesTable);

        // Try read selections + free text from one of these:
        // 1) lims_tests columns (report_notes_free_text / report_note_ids_json / etc)
        // 2) tbllims_order_report_notes (order level) with optional sample_id
        // 3) tbllims_test_report_notes (per test) with optional sample_id
        //
        // We do not fail if they do not exist.

        // ---- (2) order-level table
        $orderNotesTable = $p . 'lims_order_report_notes';
        if ($this->db->table_exists($orderNotesTable)) {
            $q = $this->db->where('order_id', (int)$order_id)->get($orderNotesTable)->result();
            foreach ($q as $row) {
                $sid = $this->db->field_exists('sample_id', $orderNotesTable) ? (int)$row->sample_id : 0;
                $targets = $sid > 0 ? [$sid] : $sampleIds;

                $free = '';
                if ($this->db->field_exists('free_text', $orderNotesTable)) {
                    $free = (string)($row->free_text ?? '');
                } elseif ($this->db->field_exists('report_free_text', $orderNotesTable)) {
                    $free = (string)($row->report_free_text ?? '');
                }

                $idsJson = '';
                if ($this->db->field_exists('note_ids_json', $orderNotesTable)) {
                    $idsJson = (string)($row->note_ids_json ?? '');
                } elseif ($this->db->field_exists('note_ids', $orderNotesTable)) {
                    $idsJson = (string)($row->note_ids ?? '');
                }

                $items = $hasNotesTable ? $this->resolve_prebuilt_notes($idsJson, $lang) : [];

                foreach ($targets as $tSid) {
                    $out[(int)$tSid]['free_text'] = trim($out[(int)$tSid]['free_text'] . "\n" . $free);
                    $out[(int)$tSid]['items'] = array_values(array_unique(array_merge($out[(int)$tSid]['items'], $items)));
                }
            }
        }

        // ---- (3) test-level table
        $testNotesTable = $p . 'lims_test_report_notes';
        if ($this->db->table_exists($testNotesTable) && !empty($testIds)) {
            $this->db->where_in('test_id', array_map('intval', $testIds));
            $q = $this->db->get($testNotesTable)->result();
            foreach ($q as $row) {
                $sid = 0;
                if ($this->db->field_exists('sample_id', $testNotesTable)) {
                    $sid = (int)$row->sample_id;
                } elseif ($this->db->field_exists('test_id', $testNotesTable)) {
                    // fallback: map from tests table if needed (skip here for simplicity)
                    $sid = 0;
                }

                $free = '';
                if ($this->db->field_exists('free_text', $testNotesTable)) {
                    $free = (string)($row->free_text ?? '');
                }

                $idsJson = '';
                if ($this->db->field_exists('note_ids_json', $testNotesTable)) {
                    $idsJson = (string)($row->note_ids_json ?? '');
                }

                $items = $hasNotesTable ? $this->resolve_prebuilt_notes($idsJson, $lang) : [];

                if ($sid > 0 && isset($out[$sid])) {
                    $out[$sid]['free_text'] = trim($out[$sid]['free_text'] . "\n" . $free);
                    $out[$sid]['items'] = array_values(array_unique(array_merge($out[$sid]['items'], $items)));
                }
            }
        }

        // ---- (1) columns inside lims_tests (only if exist)
        $testsTable = $p . 'lims_tests';
        $colFree = null;
        $colJson = null;
        if ($this->db->table_exists($testsTable) && !empty($testIds)) {
            if ($this->db->field_exists('report_free_text', $testsTable)) {
                $colFree = 'report_free_text';
            } elseif ($this->db->field_exists('report_notes_free_text', $testsTable)) {
                $colFree = 'report_notes_free_text';
            }

            if ($this->db->field_exists('report_note_ids_json', $testsTable)) {
                $colJson = 'report_note_ids_json';
            } elseif ($this->db->field_exists('report_note_ids', $testsTable)) {
                $colJson = 'report_note_ids';
            }

            if ($colFree || $colJson) {
                $this->db->select('id, sample_id' . ($colFree ? (', ' . $colFree) : '') . ($colJson ? (', ' . $colJson) : ''), false);
                $this->db->from($testsTable);
                $this->db->where_in('id', array_map('intval', $testIds));
                $q = $this->db->get()->result();

                foreach ($q as $t) {
                    $sid = (int)$t->sample_id;
                    if (!isset($out[$sid])) {
                        continue;
                    }

                    $free = $colFree ? (string)$t->{$colFree} : '';
                    $idsJson = $colJson ? (string)$t->{$colJson} : '';

                    $items = $hasNotesTable ? $this->resolve_prebuilt_notes($idsJson, $lang) : [];

                    $out[$sid]['free_text'] = trim($out[$sid]['free_text'] . "\n" . $free);
                    $out[$sid]['items'] = array_values(array_unique(array_merge($out[$sid]['items'], $items)));
                }
            }
        }

        // normalize
        foreach ($out as $sid => $arr) {
            $out[$sid]['free_text'] = trim((string)$out[$sid]['free_text']);
            $out[$sid]['items'] = array_values(array_filter(array_map('trim', (array)$out[$sid]['items'])));
        }

        return $out;
    }

    private function resolve_prebuilt_notes($idsJson, $lang)
    {
        $p = db_prefix();
        $idsJson = trim((string)$idsJson);
        if ($idsJson === '') {
            return [];
        }

        $ids = json_decode($idsJson, true);
        if (!is_array($ids)) {
            return [];
        }
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (empty($ids)) {
            return [];
        }

        $notesTable = $p . 'lims_report_notes';
        if (!$this->db->table_exists($notesTable)) {
            return [];
        }

        // Try to locate language columns
        $col = null;
        if ($lang === 'en') {
            if ($this->db->field_exists('text_english', $notesTable)) $col = 'text_english';
            elseif ($this->db->field_exists('text_en', $notesTable)) $col = 'text_en';
            elseif ($this->db->field_exists('note_en', $notesTable)) $col = 'note_en';
        } else {
            if ($this->db->field_exists('text_greek', $notesTable)) $col = 'text_greek';
            elseif ($this->db->field_exists('text_el', $notesTable)) $col = 'text_el';
            elseif ($this->db->field_exists('note_el', $notesTable)) $col = 'note_el';
        }

        if (!$col) {
            // fallback: any generic 'text' column
            $col = $this->db->field_exists('text', $notesTable) ? 'text' : null;
        }

        if (!$col) {
            return [];
        }

        $this->db->select('id, code, ' . $col . ' AS txt', false);
        $this->db->from($notesTable);
        $this->db->where_in('id', $ids);
        $this->db->order_by('id', 'asc');
        $rows = $this->db->get()->result();

        $items = [];
        foreach ($rows as $r) {
            $txt = trim((string)$r->txt);
            if ($txt !== '') {
                $items[] = $txt;
            } else {
                // fallback to code if text empty
                $items[] = (string)($r->code ?? '');
            }
        }

        return array_values(array_filter($items));
    }
	private function resolve_staff_signature_path($raw)
	{
		$raw = trim((string)$raw);
		if ($raw === '') {
			return null;
		}

		// If it's already a valid local path
		if (is_file($raw)) {
			return $raw;
		}

		// If it looks like a URL, try to convert to local path by stripping base_url()
		if (preg_match('#^https?://#i', $raw)) {
			if (function_exists('base_url')) {
				$base = rtrim(base_url(), '/');
				if (stripos($raw, $base) === 0) {
					$rel = ltrim(substr($raw, strlen($base)), '/');
					$fcp = defined('FCPATH') ? rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR : null;
					if ($fcp) {
						$candidate = $fcp . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rel);
						return $candidate;
					}
				}
			}
			// If it's URL and we cannot map it to local, TCPDF may or may not load it depending on config.
			// Safer: return null to avoid broken PDF.
			return null;
		}

		// If it's a relative path like "uploads/..." or "/uploads/..."
		$fcp = defined('FCPATH') ? rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR : null;
		if ($fcp) {
			$rel = ltrim($raw, '/\\');
			$candidate = $fcp . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rel);
			return $candidate;
		}

		return null;
	}

}