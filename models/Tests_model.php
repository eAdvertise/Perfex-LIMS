<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tests_model extends App_Model
{
    protected $table = 'tbllims_tests';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Single test with joins (sample, order, analysis, status, result)
     */
    public function get($id)
    {
        $this->db->select('
            t.*,
            s.sample_uid,
            s.barcode         AS sample_barcode,
            s.collected_at    AS sample_collected_at,
            s.received_at     AS sample_received_at,
            s.order_id,
            stype.id          AS sample_type_id,
            stype.name        AS sample_type_name,

            o.order_barcode,
            o.status          AS order_status,

            a.name            AS analysis_name,
            a.code            AS analysis_code,
            a.result_type,
            a.decimal_places,
            a.units_ucum,

            dep.name          AS department_name,

            ts.name           AS status_name,
            ts.code           AS status_code,
            ts.color          AS status_color,

            r.id              AS result_id,
            r.value_numeric,
            r.value_text,
            r.unit            AS result_unit,
            r.flag            AS result_flag,
            r.measured_at     AS result_measured_at
        ');
        $this->db->from($this->table . ' t');
        $this->db->join('tbllims_samples s', 's.id = t.sample_id', 'left');
        $this->db->join('tbllims_sample_types stype', 'stype.id = s.sample_type_id', 'left');
        $this->db->join('tbllims_orders o', 'o.id = s.order_id', 'left');
        $this->db->join('tbllims_analyses a', 'a.id = t.analysis_id', 'left');
        $this->db->join('tbllims_departments dep', 'dep.id = a.department_id', 'left');
        $this->db->join('tbllims_test_statuses ts', 'ts.code = IFNULL(t.status_code, t.status)', 'left');
        $this->db->join('tbllims_results r', 'r.test_id = t.id', 'left');

        $this->db->where('t.id', $id);

        return $this->db->get()->row();
    }

    /**
     * Audit trail για test
     */
    public function get_audit_trail($test_id)
    {
        return $this->db->order_by('created_at', 'DESC')
            ->get_where('tbllims_test_audit', ['test_id' => $test_id])
            ->result();
    }

    /**
     * Attachments από core files table (rel_type = lims_test)
     */
    public function get_attachments($test_id)
    {
        return $this->db->order_by('dateadded', 'DESC')
            ->get_where(db_prefix() . 'files', [
                'rel_type' => 'lims_test',
                'rel_id'   => $test_id,
            ])->result();
    }

    /**
     * Dropdown helpers
     */
    public function get_departments()
	{
		return $this->db
			->order_by('name', 'ASC')
			->get('tbllims_departments')
			->result_array();
	}

	public function get_sample_types()
	{
		return $this->db
			->order_by('name', 'ASC')
			->get('tbllims_sample_types')
			->result_array();
	}

	public function get_technicians()
	{
		$this->db->select('staffid, CONCAT(firstname," ",lastname) as full_name');
		$this->db->from(db_prefix() . 'staff');

		return $this->db->get()->result_array();
	}

	public function get_statuses()
	{
		return $this->db
			->order_by('position', 'ASC')
			->get_where('tbllims_test_statuses', ['active' => 1])
			->result_array();
	}


    /**
     * Datatable για /admin/lims/tests/table
     */
    public function get_tests_table($filters = [])
    {
        $aColumns = [
            't.id',
            'o.order_barcode',
            's.sample_uid',
            'stype.name',
            'a.name',
            'dep.name',
            'ts.name',
            'CONCAT(st.firstname," ",st.lastname)',
            's.collected_at',
        ];

        $sIndexColumn = 't.id';
        $sTable       = $this->table . ' t';

        $join = [
            'LEFT JOIN tbllims_samples s ON s.id = t.sample_id',
            'LEFT JOIN tbllims_sample_types stype ON stype.id = s.sample_type_id',
            'LEFT JOIN tbllims_orders o ON o.id = s.order_id',
            'LEFT JOIN tbllims_analyses a ON a.id = t.analysis_id',
            'LEFT JOIN tbllims_departments dep ON dep.id = a.department_id',
            'LEFT JOIN tbllims_test_statuses ts ON ts.code = IFNULL(t.status_code, t.status)',
            'LEFT JOIN ' . db_prefix() . 'staff st ON st.staffid = t.assigned_staff',
        ];

        $where = [];

        // Φίλτρο status (χρησιμοποιούμε το code από tbllims_test_statuses)
        if (!empty($filters['status'])) {
            $status = $this->db->escape_str($filters['status']);
            $where[] = 'AND IFNULL(t.status_code, t.status) = "' . $status . '"';
        }

        // Φίλτρο department (από analysis.department_id)
        if (!empty($filters['department'])) {
            $depId = (int) $filters['department'];
            $where[] = 'AND a.department_id = ' . $depId;
        }

        // Φίλτρο sample_type (s.sample_type_id)
        if (!empty($filters['sample_type'])) {
            $stypeId = (int) $filters['sample_type'];
            $where[] = 'AND s.sample_type_id = ' . $stypeId;
        }

        // Φίλτρο assigned_to
        if (!empty($filters['assigned_to'])) {
            $staffId = (int) $filters['assigned_to'];
            $where[] = 'AND t.assigned_staff = ' . $staffId;
        }

        // Φίλτρα ημερομηνίας (collected_at)
        if (!empty($filters['date_from'])) {
            $where[] = 'AND DATE(s.collected_at) >= "' . to_sql_date($filters['date_from']) . '"';
        }
        if (!empty($filters['date_to'])) {
            $where[] = 'AND DATE(s.collected_at) <= "' . to_sql_date($filters['date_to']) . '"';
        }

        $extraSelect = [
			't.id as test_id',
			'stype.name as sample_type_name',
			'a.name as analysis_name',
			'dep.name as department_name',
			'ts.name as status_label',
			'ts.code as status_code',
			'ts.color as status_color',
			'CONCAT(st.firstname," ",st.lastname) as assigned_to_name',
			'o.id as order_id', // <-- ΠΡΟΣΘΗΚΗ
		];


        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $extraSelect);

        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $row = [];

            // ID + link
            $orderUrl = admin_url('lims/tests/order/' . $aRow['order_id'] . '?test=' . $aRow['test_id']);

			$row[] = '<a href="' . $orderUrl . '">#' . $aRow['test_id'] . '</a>';

            // Order barcode
            $row[] = !empty($aRow['order_barcode']) ? html_escape($aRow['order_barcode']) : '-';

            // Sample (UID + type)
            $sampleLabel = $aRow['sample_uid'];
            if (!empty($aRow['sample_type_name'])) {
                $sampleLabel .= ' (' . html_escape($aRow['sample_type_name']) . ')';
            }
            $row[] = $sampleLabel;

            // Analysis name
            $row[] = html_escape($aRow['analysis_name']);

            // Department
            $row[] = html_escape($aRow['department_name']);

            // Status (με χρώμα από test_statuses)
            $statusColor = !empty($aRow['status_color']) ? $aRow['status_color'] : '#777';
            $row[] = '<span class="label" style="background:' . html_escape($statusColor) . ';">'
                   . html_escape($aRow['status_label']) . '</span>';

            // Assigned to
            $row[] = $aRow['assigned_to_name'];

            // Collected at
            $row[] = $aRow['collected_at'] ? _dt($aRow['collected_at']) : '-';

            // Actions
            //$options = icon_btn('lims/tests/view/' . $aRow['test_id'], 'fa fa-eye', 'btn-default');
			$options = icon_btn($orderUrl, 'fa fa-eye', 'btn-default'); 
            $row[] = $options;

            $output['aaData'][] = $row;
        }

        return json_encode($output);
    }
		
	public function get_order_tests_data($order_id)
	{
		$order_id = (int)$order_id;

		// ===== 1) Order + SUBJECT info (όχι client) =====
		$this->db->select('
			o.*,
			subj.id           AS subject_id,
			subj.subject_name,
			subj.first_name,
			subj.last_name,
			subj.internal_code,
			subj.id_number,
			subj.phone,
			subj.email
		');
		$this->db->from('tbllims_orders o');
		$this->db->join('tbllims_subjects subj', 'subj.id = o.subject_id', 'left');
		$this->db->where('o.id', $order_id);
		$order = $this->db->get()->row();

		if (!$order) {
			return null;
		}

		// ===== 2) Samples =====
		$this->db->select('s.*, st.name as sample_type_name, st.code as sample_type_code');
		$this->db->from('tbllims_samples s');
		$this->db->join('tbllims_sample_types st', 'st.id = s.sample_type_id', 'left');
		$this->db->where('s.order_id', $order_id);
		$this->db->order_by('s.id', 'ASC');
		$samples = $this->db->get()->result();


		$sampleIds = array_column($samples, 'id');

		$tests                  = [];
		$testsBySample          = [];
		$resultsByTest          = [];
		$culturesBySample       = [];
		$cultureResultsByKey    = [];
		$cultureSelectionsByKey = [];

		if (!empty($sampleIds)) {

			// ===== 3) LAB TESTS =====
			$this->db->select('
				t.*,
				s.sample_uid,
				s.barcode as sample_barcode,
				stype.name as sample_type_name,
				a.name as analysis_name,
				a.code as analysis_code,
				a.result_type,
				a.decimal_places,
				a.units_ucum,
				dep.name as department_name
			');
			$this->db->from('tbllims_tests t');
			$this->db->join('tbllims_samples s', 's.id = t.sample_id', 'left');
			$this->db->join('tbllims_sample_types stype', 'stype.id = s.sample_type_id', 'left');
			$this->db->join('tbllims_analyses a', 'a.id = t.analysis_id', 'left');
			$this->db->join('tbllims_departments dep', 'dep.id = a.department_id', 'left');
			$this->db->where_in('t.sample_id', $sampleIds);
			$this->db->order_by('s.id', 'ASC');
			$this->db->order_by('t.id', 'ASC');
			$tests = $this->db->get()->result();

			$testIds = [];
			foreach ($tests as $t) {
				$testIds[] = $t->id;
				$testsBySample[$t->sample_id][] = $t;
			}

			// 3.1 Results ανά test
			if (!empty($testIds)) {
				$this->db->from('tbllims_results');
				$this->db->where_in('test_id', $testIds);
				$this->db->order_by('measured_at', 'DESC');
				$this->db->order_by('id', 'DESC');
				$results = $this->db->get()->result();

				foreach ($results as $r) {
					$resultsByTest[$r->test_id][] = $r;
				}
			}

			// ===== 4) CULTURES =====


			// 4.0 NEW (v3): If per-sample culture mapping exists, prefer it for workflow accuracy
			$useSampleCultures = false;
			$tblSampleCultures = db_prefix().'lims_sample_cultures';
			if ($this->db->table_exists($tblSampleCultures)) {
				$cntRow = $this->db->select('COUNT(*) AS c')->where('order_id', $order_id)->get($tblSampleCultures)->row();
				$useSampleCultures = $cntRow && (int)$cntRow->c > 0;
			}

			if ($useSampleCultures) {

				$this->db->select(
					'sc.sample_id, ' .
					'c.id AS culture_id, ' .
					'c.name AS culture_name, ' .
					'c.code AS culture_code, ' .
					'c.sample_type_id AS culture_sample_type_id, ' .
					'ct.name AS culture_type_name',
					false
				);
				$this->db->from($tblSampleCultures.' sc');
				$this->db->join(db_prefix().'lims_cultures c', 'c.id = sc.culture_id', 'inner');
				$this->db->join(db_prefix().'lims_culture_types ct', 'ct.id = c.culture_type_id', 'left');
				$this->db->where('sc.order_id', $order_id);
				$this->db->where_in('sc.sample_id', $sampleIds);
				$this->db->order_by('sc.sample_id','ASC');
				$this->db->order_by('c.id','ASC');
				$scRows = $this->db->get()->result();

				$sampleCultureRows = []; // [sample_id => [cultureObj, ...]]
				$cultureIds = [];

				foreach ($scRows as $r) {
					$sid = (int)$r->sample_id;
					$cid = (int)$r->culture_id;
					$cultureIds[$cid] = true;
					if (!isset($sampleCultureRows[$sid])) {
						$sampleCultureRows[$sid] = [];
					}
					$sampleCultureRows[$sid][$cid] = $r; // de-dupe by id
				}

				$cultureIds = array_keys($cultureIds);

				// Culture results (per order + sample + culture)
				if (!empty($cultureIds)) {
					$this->db->from(db_prefix().'lims_culture_results');
					$this->db->where('order_id', $order_id);
					$this->db->where_in('sample_id', $sampleIds);
					$this->db->where_in('culture_id', $cultureIds);
					$rows = $this->db->get()->result();

					foreach ($rows as $r) {
						$key = (int)$r->sample_id . ':' . (int)$r->culture_id;
						$r->comment = $r->result_text;
						$cultureResultsByKey[$key] = $r;

						if (!empty($r->options_json)) {
							$decoded = json_decode($r->options_json, true);
							if (is_array($decoded)) {
								foreach ($decoded as $setId => $valId) {
									$setId = (int)$setId;
									$valId = (int)$valId;
									if ($setId > 0 && $valId > 0) {
										if (!isset($cultureSelectionsByKey[$key])) {
											$cultureSelectionsByKey[$key] = [];
										}
										$cultureSelectionsByKey[$key][$setId] = $valId;
									}
								}
							}
						}
					}

					// Culture option sets & values ανά culture
					$cultureOptionsByCulture = [];

					$this->db->select('
						l.culture_id,
						s.id   AS set_id,
						s.name AS set_name,
						s.code AS set_code,
						v.id   AS value_id,
						v.value,
						v.label,
						v.sort_order
					');
					$this->db->from(db_prefix().'lims_culture_option_links l');
					$this->db->join(db_prefix().'lims_culture_option_sets s','s.id = l.set_id AND s.active = 1','left');
					$this->db->join(db_prefix().'lims_culture_option_values v','v.set_id = s.id AND v.active = 1','left');
					$this->db->where_in('l.culture_id', $cultureIds);
					$this->db->order_by('s.name','ASC');
					$this->db->order_by('v.sort_order','ASC');
					$optRows = $this->db->get()->result();

					foreach ($optRows as $row) {
						if (!$row->set_id) {
							continue;
						}
						$cid = (int)$row->culture_id;
						$sid = (int)$row->set_id;

						if (!isset($cultureOptionsByCulture[$cid])) {
							$cultureOptionsByCulture[$cid] = [];
						}
						if (!isset($cultureOptionsByCulture[$cid][$sid])) {
							$cultureOptionsByCulture[$cid][$sid] = [
								'set_id'   => $sid,
								'set_code' => $row->set_code,
								'set_name' => $row->set_name,
								'values'   => [],
							];
						}
						if ($row->value_id) {
							$cultureOptionsByCulture[$cid][$sid]['values'][] = [
								'id'    => (int)$row->value_id,
								'value' => $row->value,
								'label' => $row->label,
							];
						}
					}

					// Build per-sample cultures strictly from mapping
					foreach ($samples as $s) {
						$sid = (int)$s->id;
						if (empty($sampleCultureRows[$sid])) {
							continue;
						}
						foreach ($sampleCultureRows[$sid] as $cid => $c) {
							$obj = (object)[
								'culture_id'        => (int)$cid,
								'culture_name'      => $c->culture_name,
								'culture_code'      => $c->culture_code,
								'culture_type_name' => $c->culture_type_name,
								'option_sets'       => [],
							];

							if (isset($cultureOptionsByCulture[(int)$cid])) {
								$obj->option_sets = array_values($cultureOptionsByCulture[(int)$cid]);
							}

							$culturesBySample[$sid][] = $obj;
						}
					}
				}

			} else {


			// 4.1 Direct cultures σε order_items (source_type = 'culture')
			$this->db->select(
				'c.id AS culture_id, ' .
				'c.name AS culture_name, ' .
				'c.code AS culture_code, ' .
				'c.sample_type_id AS culture_sample_type_id, ' .
				'ct.name AS culture_type_name',
				false
			);
			$this->db->from(db_prefix().'lims_order_items oi');
			$this->db->join(
				db_prefix().'lims_cultures c',
				'c.id = oi.source_id',
				'inner'
			);
			$this->db->join(
				db_prefix().'lims_culture_types ct',
				'ct.id = c.culture_type_id',
				'left'
			);
			$this->db->where('oi.order_id', $order_id);
			$this->db->where('oi.source_type', 'culture');
			$this->db->order_by('c.id', 'ASC');
			$directCultures = $this->db->get()->result();

			// 4.2 Cultures μέσα από panels
			$this->db->distinct();
			$this->db->select(
				'c.id AS culture_id, ' .
				'c.name AS culture_name, ' .
				'c.code AS culture_code, ' .
				'c.sample_type_id AS culture_sample_type_id, ' .
				'ct.name AS culture_type_name',
				false
			);
			$this->db->from(db_prefix().'lims_order_items oi');
			$this->db->join(
				db_prefix().'lims_panel_items pi',
				'pi.panel_id = oi.source_id',
				'inner'
			);
			$this->db->join(
				db_prefix().'lims_cultures c',
				'c.id = pi.culture_id',
				'inner'
			);
			$this->db->join(
				db_prefix().'lims_culture_types ct',
				'ct.id = c.culture_type_id',
				'left'
			);
			$this->db->where('oi.order_id', $order_id);
			$this->db->where('oi.source_type', 'panel');
			$this->db->order_by('c.id', 'ASC');
			$panelCultures = $this->db->get()->result();

			// 4.3 Merge direct + panel cultures (χωρίς διπλά)
			$byId = [];

			if (!empty($directCultures)) {
				foreach ($directCultures as $c) {
					$cid        = (int)$c->culture_id;
					$byId[$cid] = $c;
				}
			}

			if (!empty($panelCultures)) {
				foreach ($panelCultures as $c) {
					$cid = (int)$c->culture_id;
					if (!isset($byId[$cid])) {
						$byId[$cid] = $c;
					}
				}
			}

			// 4.4 Αν ακόμα δεν έχουμε τίποτα → fallback σε όλα τα active cultures
			if (empty($byId)) {
				$this->db->select(
					'c.id AS culture_id, ' .
					'c.name AS culture_name, ' .
					'c.code AS culture_code, ' .
					'c.sample_type_id AS culture_sample_type_id, ' .
					'ct.name AS culture_type_name',
					false
				);
				$this->db->from(db_prefix().'lims_cultures c');
				$this->db->join(
					db_prefix().'lims_culture_types ct',
					'ct.id = c.culture_type_id',
					'left'
				);
				$this->db->where('c.active', 1);
				$this->db->order_by('c.name', 'ASC');
				$fallback = $this->db->get()->result();
				foreach ($fallback as $c) {
					$cid        = (int)$c->culture_id;
					$byId[$cid] = $c;
				}
			}

			$orderCultures = array_values($byId);
			$cultureIds    = array_keys($byId);

			if (!empty($cultureIds)) {
				// 4.5 Culture results (per order + sample + culture)
				$this->db->from(db_prefix().'lims_culture_results');
				$this->db->where('order_id', $order_id);
				$this->db->where_in('sample_id', $sampleIds);
				$this->db->where_in('culture_id', $cultureIds);
				$rows = $this->db->get()->result();

				foreach ($rows as $r) {
					$key = (int)$r->sample_id . ':' . (int)$r->culture_id;

					// alias για το view, που περιμένει ->comment
					$r->comment = $r->result_text;
					$cultureResultsByKey[$key] = $r;

					// decode options_json -> selections per set
					if (!empty($r->options_json)) {
						$decoded = json_decode($r->options_json, true);
						if (is_array($decoded)) {
							foreach ($decoded as $setId => $valId) {
								$setId = (int)$setId;
								$valId = (int)$valId;
								if ($setId > 0 && $valId > 0) {
									if (!isset($cultureSelectionsByKey[$key])) {
										$cultureSelectionsByKey[$key] = [];
									}
									$cultureSelectionsByKey[$key][$setId] = $valId;
								}
							}
						}
					}
				}

				// 4.6 Culture option sets & values ανά culture
				$cultureOptionsByCulture = [];

				$this->db->select('
					l.culture_id,
					s.id   AS set_id,
					s.name AS set_name,
					s.code AS set_code,
					v.id   AS value_id,
					v.value,
					v.label,
					v.sort_order
				');
				$this->db->from(db_prefix().'lims_culture_option_links l');
				$this->db->join(
					db_prefix().'lims_culture_option_sets s',
					's.id = l.set_id AND s.active = 1',
					'left'
				);
				$this->db->join(
					db_prefix().'lims_culture_option_values v',
					'v.set_id = s.id AND v.active = 1',
					'left'
				);
				$this->db->where_in('l.culture_id', $cultureIds);
				$this->db->order_by('s.name','ASC');
				$this->db->order_by('v.sort_order','ASC');
				$optRows = $this->db->get()->result();

				foreach ($optRows as $row) {
					if (!$row->set_id) {
						continue;
					}
					$cid = (int)$row->culture_id;
					$sid = (int)$row->set_id;

					if (!isset($cultureOptionsByCulture[$cid])) {
						$cultureOptionsByCulture[$cid] = [];
					}
					if (!isset($cultureOptionsByCulture[$cid][$sid])) {
						$cultureOptionsByCulture[$cid][$sid] = [
							'set_id'   => $sid,
							'set_code' => $row->set_code,
							'set_name' => $row->set_name,
							'values'   => [],
						];
					}
					if ($row->value_id) {
						$cultureOptionsByCulture[$cid][$sid]['values'][] = [
							'id'    => (int)$row->value_id,
							'value' => $row->value,
							'label' => $row->label,
						];
					}
				}

				// 4.7 Κατανομή cultures ανά sample, με option_sets για dropdowns
				foreach ($samples as $s) {
					$sid     = (int)$s->id;
					$stypeId = (int)$s->sample_type_id;

					foreach ($orderCultures as $c) {
						$cid         = (int)$c->culture_id;
						$cStypeId    = isset($c->culture_sample_type_id) ? (int)$c->culture_sample_type_id : 0;

						// Αν το culture έχει sample_type_id, πρέπει να ταιριάζει με του sample.
						// Αν είναι NULL, ισχύει για όλα τα sample types.
						if ($cStypeId && $cStypeId !== $stypeId) {
							continue;
						}

						$obj = (object)[
							'culture_id'         => $cid,
							'culture_name'       => $c->culture_name,
							'culture_code'       => $c->culture_code,
							'culture_type_name'  => $c->culture_type_name,
							'option_sets'        => [],
						];

						if (isset($cultureOptionsByCulture[$cid])) {
							// κρατάμε τα sets ως array για να κάνουμε loops στο view
							$obj->option_sets = array_values($cultureOptionsByCulture[$cid]);
						}

						$culturesBySample[$sid][] = $obj;
					}
				}
			}
			}
		}
		// ----------------------------------------------------
        // Υπολογισμός readiness υπογραφής (canSign)
        // Κριτήριο:
        //  - Υπάρχει τουλάχιστον 1 test
        //  - ΚΑΘΕ test (που δεν είναι canceled) έχει τουλάχιστον 1 αποτέλεσμα
        //  - ΚΑΘΕ culture (αν υπάρχουν) έχει αποτέλεσμα
        // ----------------------------------------------------
        $canSign             = false;
        $hasAnyTest          = false;
        $allTestsCompleted   = true;
        $allCulturesCompleted = true;

        // Tests: βασιζόμαστε στα αποτελέσματα, όχι στο t.status
        foreach ($testsBySample as $sampleId => $testsList) {
            foreach ($testsList as $t) {

                // αγνοούμε tests σε κατάσταση canceled
                if ((string)$t->status === 'canceled') {
                    continue;
                }

                $hasAnyTest = true;

                $tid       = (int)$t->id;
                $hasResult = !empty($resultsByTest[$tid]);

                // αν δεν υπάρχει αποτέλεσμα για το test -> δεν είμαστε έτοιμοι
                if (!$hasResult) {
                    $allTestsCompleted = false;
                    break 2; // βγες και από τα 2 foreach
                }
            }
        }

        // Cultures: αν έχεις cultures, απαιτούμε result για καθεμία
        foreach ($culturesBySample as $sampleId => $cultureList) {
            foreach ($cultureList as $cu) {
                $key = (int)$sampleId . ':' . (int)$cu->culture_id;
                if (!isset($cultureResultsByKey[$key])) {
                    $allCulturesCompleted = false;
                    break 2;
                }
            }
        }

        $canSign = $hasAnyTest && $allTestsCompleted && $allCulturesCompleted;

        // ===== 6) REPORT NOTES (free text + predefined selections) =====
        $reportNotes      = $this->get_report_notes(true);
        $orderReportNotes = $this->get_order_report_notes($order_id);

        return [
            'order'               => $order,
            'samples'             => $samples,
            'testsBySample'       => $testsBySample,
            'resultsByTest'       => $resultsByTest,
            'culturesBySample'    => $culturesBySample,
            'cultureResultsByKey' => $cultureResultsByKey,
            'cultureSelectionsByKey' => $cultureSelectionsByKey,
            'reportNotes'          => $reportNotes,
            'orderReportNotes'     => $orderReportNotes,
            'canSign'             => $canSign,
        ];

	}

	// ======================================================
	// Report Notes (Order-level) – free text + predefined notes
	// ======================================================

	public function get_report_notes($activeOnly = true)
	{
		$table = db_prefix().'lims_report_notes';

		// Soft-guard (χωρίς CREATE TABLE)
		if (method_exists($this->db, 'table_exists') && !$this->db->table_exists($table)) {
			return [];
		}

		$this->db->from($table);

		if ($activeOnly) {
			$this->db->where('active', 1);
		}

		$this->db->order_by('sort_order', 'ASC');
		$this->db->order_by('id', 'ASC');

		return $this->db->get()->result();
	}


	public function get_order_report_notes($order_id)
	{
		$order_id = (int)$order_id;

		$out = ['free_text' => '', 'note_ids' => []];
		if ($order_id <= 0) return $out;

		$table = db_prefix().'lims_order_report_notes';
		if (method_exists($this->db, 'table_exists') && !$this->db->table_exists($table)) {
			return $out;
		}

		$row = $this->db->where('order_id', $order_id)->get($table)->row();
		if (!$row) return $out;

		$out['free_text'] = (string)($row->free_text ?? '');

		$raw = trim((string)($row->note_ids_json ?? ''));
		if ($raw !== '') {
			$decoded = json_decode($raw, true);
			if (is_array($decoded)) {
				$ids = array_values(array_unique(array_filter(array_map('intval', $decoded), fn($v) => $v > 0)));
				sort($ids);
				$out['note_ids'] = $ids;
			}
		}

		return $out;
	}


	public function save_order_report_notes($order_id, $free_text, $note_ids, $staff_id = 0)
	{
		$order_id = (int)$order_id;
		$staff_id = (int)$staff_id;

		if ($order_id <= 0) return false;

		$table = db_prefix().'lims_order_report_notes';
		if (method_exists($this->db, 'table_exists') && !$this->db->table_exists($table)) {
			// no DDL here — απλά fail gracefully
			return false;
		}

		$free_text = trim((string)$free_text);

		$ids = is_array($note_ids) ? $note_ids : [];
		$ids = array_values(array_unique(array_filter(array_map('intval', $ids), fn($v) => $v > 0)));
		sort($ids);

		$json = json_encode($ids, JSON_UNESCAPED_UNICODE);
		$now  = date('Y-m-d H:i:s');

		$existing = $this->db->where('order_id', $order_id)->get($table)->row();

		$data = [
			'free_text'    => ($free_text !== '' ? $free_text : null),
			'note_ids_json'=> $json,
			'updated_by'   => ($staff_id > 0 ? $staff_id : null),
			'updated_at'   => $now,
		];

		if ($existing) {
			$this->db->where('order_id', $order_id)->update($table, $data);
			return $this->db->affected_rows() >= 0;
		}

		$data['order_id']   = $order_id;
		$data['created_at'] = $now;

		$this->db->insert($table, $data);
		return $this->db->insert_id() ? true : false;
	}

	
	public function save_order_results($order_id, $values, $units, $flags, $measured_at)
	{
		$order_id = (int)$order_id;
		if ($order_id <= 0) {
			return;
		}

		$staff_id = get_staff_user_id();
		$now      = date('Y-m-d H:i:s');

		if (!is_array($values)) {
			$values = [];
		}

		foreach ($values as $test_id => $value) {
			$test_id = (int)$test_id;
			$value   = trim((string)$value);

			// Αν δεν έχει τιμή, απλά skip (δεν πειράζουμε status)
			if ($test_id <= 0 || $value === '') {
				continue;
			}

			// Φέρνουμε το test + analysis + order, για ασφάλεια
			$this->db->select('t.id, t.status, t.status_code, a.result_type');
			$this->db->from('tbllims_tests t');
			$this->db->join('tbllims_samples s', 's.id = t.sample_id', 'left');
			$this->db->join('tbllims_orders o', 'o.id = s.order_id', 'left');
			$this->db->join('tbllims_analyses a', 'a.id = t.analysis_id', 'left');
			$this->db->where('t.id', $test_id);
			$this->db->where('o.id', $order_id);
			$test = $this->db->get()->row();

			if (!$test) {
				// άκυρο test για αυτό το order -> skip
				continue;
			}

			$unit = isset($units[$test_id]) ? trim((string)$units[$test_id]) : null;
			$flag = isset($flags[$test_id]) ? trim((string)$flags[$test_id]) : null;
			$mea  = isset($measured_at[$test_id]) && $measured_at[$test_id]
				? to_sql_date($measured_at[$test_id], true)
				: $now;

			// INSERT στο tbllims_results
			$insert = [
				'test_id'     => $test_id,
				'unit'        => $unit ?: null,
				'flag'        => $flag ?: null,
				'measured_at' => $mea,
				'created_at'  => $now,
			];

			if ($test->result_type === 'numeric') {
				$insert['value_numeric'] = ($value !== '') ? (float)$value : null;
			} else {
				$insert['value_text'] = $value;
			}

			$this->db->insert('tbllims_results', $insert);

			// === ΕΝΗΜΕΡΩΣΗ TEST STATUS ===
			// ΔΕΝ το αδειάζουμε ποτέ.
			// Βάζουμε και status ΚΑΙ status_code = 'complete' για να παίζει με το join στο tbllims_test_statuses.
			$this->db->where('id', $test_id);
			$this->db->update('tbllims_tests', [
				'status'      => 'complete',
				'status_code' => 'complete',
				'completed_at'=> $mea,
			]);

			// Audit trail
			$this->db->insert('tbllims_test_audit', [
				'test_id'    => $test_id,
				'action'     => 'result_entered',
				'old_status' => $test->status,
				'new_status' => 'complete',
				'old_value'  => null,
				'new_value'  => json_encode($insert),
				'staff_id'   => $staff_id,
				'created_at' => $now,
			]);
		}

		// Μετά από όλα τα tests, κάνουμε re-check αν το order μπορεί να θεωρηθεί "completed"
		$this->update_order_status_if_all_tests_completed($order_id);
	}


	public function save_order_culture_results($order_id, $culture_comments = [], $culture_options = [])
	{
		$order_id = (int)$order_id;
		if ($order_id <= 0) {
			return;
		}

		if (!is_array($culture_comments)) {
			$culture_comments = [];
		}
		if (!is_array($culture_options)) {
			$culture_options = [];
		}

		$now   = date('Y-m-d H:i:s');
		$table = db_prefix().'lims_culture_results';

		// Build combined map: sample_id -> culture_id -> ['comment'=>..,'options'=>[..]]
		$map = [];

		foreach ($culture_comments as $sample_id => $perCulture) {
			$sample_id = (int)$sample_id;
			if ($sample_id <= 0 || !is_array($perCulture)) {
				continue;
			}
			foreach ($perCulture as $culture_id => $text) {
				$culture_id = (int)$culture_id;
				if ($culture_id <= 0) {
					continue;
				}
				if (!isset($map[$sample_id])) {
					$map[$sample_id] = [];
				}
				if (!isset($map[$sample_id][$culture_id])) {
					$map[$sample_id][$culture_id] = [
						'comment' => '',
						'options' => [],
					];
				}
				$map[$sample_id][$culture_id]['comment'] = trim((string)$text);
			}
		}

		foreach ($culture_options as $sample_id => $perCulture) {
			$sample_id = (int)$sample_id;
			if ($sample_id <= 0 || !is_array($perCulture)) {
				continue;
			}
			foreach ($perCulture as $culture_id => $sets) {
				$culture_id = (int)$culture_id;
				if ($culture_id <= 0 || !is_array($sets)) {
					continue;
				}
				if (!isset($map[$sample_id])) {
					$map[$sample_id] = [];
				}
				if (!isset($map[$sample_id][$culture_id])) {
					$map[$sample_id][$culture_id] = [
						'comment' => '',
						'options' => [],
					];
				}
				$map[$sample_id][$culture_id]['options'] = $sets;
			}
		}

		if (empty($map)) {
			return;
		}

		foreach ($map as $sample_id => $perCulture) {
			foreach ($perCulture as $culture_id => $data) {
				$comment = isset($data['comment']) ? trim((string)$data['comment']) : '';
				$sets    = isset($data['options']) && is_array($data['options']) ? $data['options'] : [];

				// Normalise selections: set_id => value_id (ints, >0)
				$normalized = [];
				foreach ($sets as $set_id => $value_id) {
					$set_id   = (int)$set_id;
					$value_id = (int)$value_id;
					if ($set_id > 0 && $value_id > 0) {
						$normalized[$set_id] = $value_id;
					}
				}

				$optionsJson = !empty($normalized) ? json_encode($normalized) : null;

				// Find existing row
				$this->db->where('order_id', $order_id);
				$this->db->where('sample_id', $sample_id);
				$this->db->where('culture_id', $culture_id);
				$existing = $this->db->get($table)->row();

				$hasData = ($comment !== '' || !empty($normalized));

				if ($existing) {
					if (!$hasData) {
						// Καθόλου δεδομένα -> delete
						$this->db->where('id', $existing->id)->delete($table);
					} else {
						$update = [
							'result_text' => $comment !== '' ? $comment : null,
							'options_json'=> $optionsJson,
							'updated_at'  => $now,
						];

						$this->db->where('id', $existing->id)->update($table, $update);
					}
				} else {
					if (!$hasData) {
						continue;
					}

					$this->db->insert($table, [
						'order_id'    => $order_id,
						'sample_id'   => $sample_id,
						'culture_id'  => $culture_id,
						'result_text' => $comment !== '' ? $comment : null,
						'options_json'=> $optionsJson,
						'created_at'  => $now,
					]);
				}
			}
		}
		$this->refresh_order_completion_status($order_id);
	}


	private function refresh_order_completion_status($order_id)
	{
		$order_id = (int)$order_id;
		if ($order_id <= 0) {
			return;
		}

		$data = $this->get_order_tests_data($order_id);
		if (!$data || empty($data['order'])) {
			return;
		}

		$order               = $data['order'];
		$samples             = $data['samples']             ?? [];
		$testsBySample       = $data['testsBySample']       ?? [];
		$resultsByTest       = $data['resultsByTest']       ?? [];
		$culturesBySample    = $data['culturesBySample']    ?? [];
		$cultureResultsByKey = $data['cultureResultsByKey'] ?? [];

		// --------- ΕΛΕΓΧΟΣ TESTS ----------
		$totalTests = 0;
		$openTests  = 0;

		foreach ($testsBySample as $sid => $tests) {
			foreach ($tests as $t) {
				$totalTests++;
				$hasResults = !empty($resultsByTest[$t->id]);
				if (!$hasResults) {
					$openTests++;
				}
			}
		}

		// --------- ΕΛΕΓΧΟΣ CULTURES ----------
		$totalCultures = 0;
		$openCultures  = 0;

		foreach ($samples as $s) {
			$sid = (int)$s->id;
			if (empty($culturesBySample[$sid])) {
				continue;
			}

			foreach ($culturesBySample[$sid] as $cu) {
				$totalCultures++;
				$key  = $sid . ':' . (int)$cu->culture_id;
				$cres = $cultureResultsByKey[$key] ?? null;

				if (!$cres) {
					$openCultures++;
					continue;
				}

				$comment     = isset($cres->result_text) ? trim((string)$cres->result_text) : '';
				$optionsJson = isset($cres->options_json) ? trim((string)$cres->options_json) : '';

				$hasComment = $comment !== '';
				$hasOptions = $optionsJson !== '' && $optionsJson !== 'null' && $optionsJson !== '[]' && $optionsJson !== '{}';

				if (!($hasComment || $hasOptions)) {
					$openCultures++;
				}
			}
		}

		$allTestsDone    = ($totalTests > 0 && $openTests === 0);
		$allCulturesDone = ($totalCultures === 0 || $openCultures === 0);

		if (!$allTestsDone || !$allCulturesDone) {
			// Δεν είναι ακόμα όλα συμπληρωμένα
			return;
		}

		$now = date('Y-m-d H:i:s');

		// Μην κάνεις downgrade αν το order είναι ήδη σε πιο "προχωρημένο" state
		$protected = ['complete', 'signed', 'reported', 'approved'];

		if (!in_array($order->status, $protected, true)) {
			$this->db->where('id', $order_id);
			$this->db->update('tbllims_orders', [
				'status'     => 'complete',
				'updated_at' => $now,
			]);
		}
	}
	/**
	 * Αν ΟΛΑ τα tests του order είναι κλειστά (όχι pending/in_progress),
	 * τότε ανεβάζουμε το status του order σε "complete"
	 * (εκτός αν είναι ήδη reported/signed/canceled).
	 */
	private function update_order_status_if_all_tests_completed($order_id)
	{
		$order_id = (int)$order_id;
		if ($order_id <= 0) {
			return;
		}

		// Μετράμε πόσα tests είναι ακόμα ανοιχτά για το συγκεκριμένο order
		$sql = "
			SELECT
				COUNT(*) AS total_tests,
				SUM(
					CASE
						WHEN (t.status IN ('pending','in_progress')
							  OR IFNULL(t.status_code,'') IN ('pending','in_progress'))
						THEN 1
						ELSE 0
					END
				) AS open_tests
			FROM tbllims_tests t
			JOIN tbllims_samples s ON s.id = t.sample_id
			WHERE s.order_id = ?
		";

		$row = $this->db->query($sql, [$order_id])->row();
		if (!$row || (int)$row->total_tests === 0) {
			// Δεν υπάρχουν tests -> δεν αλλάζουμε τίποτα
			return;
		}

		if ((int)$row->open_tests > 0) {
			// Υπάρχουν ακόμα ανοιχτά tests -> δεν πειράζουμε το order status
			return;
		}

		// Φέρνουμε το τρέχον status του order
		$order = $this->db->select('status')
			->from('tbllims_orders')
			->where('id', $order_id)
			->get()
			->row();

		if (!$order) {
			return;
		}

		$current = (string)$order->status;

		// Αν είναι ήδη σε πιο "τελικό" status, δεν το κατεβάζουμε
		if (in_array($current, ['reported', 'signed', 'canceled'], true)) {
			return;
		}

		// Αλλιώς, ανεβάζουμε σε "complete"
		$this->db->where('id', $order_id);
		$this->db->update('tbllims_orders', [
			'status'     => 'complete',
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function can_sign_order($order_id)
    {
        $order_id = (int)$order_id;
        if ($order_id <= 0) {
            return false;
        }

        $data = $this->get_order_tests_data($order_id);
        if (!$data || empty($data['order'])) {
            return false;
        }

        return !empty($data['canSign']);
    }

    public function sign_order($order_id, $staff_id)
    {
        $order_id = (int)$order_id;
        $staff_id = (int)$staff_id;

        if ($order_id <= 0 || $staff_id <= 0) {
            return false;
        }

        if (!$this->can_sign_order($order_id)) {
            return false;
        }

        $now = date('Y-m-d H:i:s');

        // 1) Tests -> signed (όσα δεν είναι canceled και έχουν αποτελέσματα)
		$this->db->query("
			UPDATE tbllims_tests t
			JOIN tbllims_samples s ON s.id = t.sample_id
			SET t.status = 'signed'
			WHERE s.order_id = ?
			  AND t.status <> 'canceled'
			  AND EXISTS (
				  SELECT 1 FROM tbllims_results r
				  WHERE r.test_id = t.id
			  )
		", [$order_id]);


        // 2) Order -> signed
        $this->db->where('id', $order_id)
                 ->update('tbllims_orders', [
                     'status'     => 'signed',
                     'signed_by'  => $staff_id,
                     'signed_at'  => $now,
                 ]);

        // 3) Order activity log
        $this->db->insert('tbllims_order_activity', [
            'order_id'   => $order_id,
            'action'     => 'signed',
            'message'    => 'Order signed',
            'meta'       => null,
            'staff_id'   => $staff_id,
            'created_at' => $now,
        ]);

        // 4) Audit ανά test
        $tests = $this->db->query("
            SELECT t.id
            FROM tbllims_tests t
            JOIN tbllims_samples s ON s.id = t.sample_id
            WHERE s.order_id = ?
        ", [$order_id])->result();

        foreach ($tests as $t) {
            $this->db->insert('tbllims_test_audit', [
                'test_id'    => $t->id,
                'action'     => 'signed',
                'old_status' => null,
                'new_status' => 'signed',
                'old_value'  => null,
                'new_value'  => null,
                'reason'     => null,
                'staff_id'   => $staff_id,
                'created_at' => $now,
            ]);
        }

        return true;
    }


}
