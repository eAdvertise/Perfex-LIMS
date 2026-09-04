<!-- lims/views/admin/orders/view.php -->
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<?php
// Φόρτωσε όλα τα Test Statuses για χρήση στο header + dropdown
$CI =& get_instance();
$CI->db->order_by('position','ASC');
$lims_status_rows = $CI->db->get(db_prefix().'lims_test_statuses')->result();

// Map by code για γρήγορο lookup
$lims_status_map = [];
foreach ($lims_status_rows as $st) {
    if (!empty($st->code)) {
        $lims_status_map[$st->code] = $st;
    }
}
?>

<style>
/* απλό timeline */
.timeline{list-style:none;margin:0;padding:0;position:relative}
.timeline:before{content:'';position:absolute;left:15px;top:0;bottom:0;width:2px;background:#e9ecef}
.timeline-item{position:relative;margin:0 0 15px 0;padding-left:40px}
.timeline-time{font-size:12px;color:#999;margin-bottom:3px}
.timeline-icon{position:absolute;left:9px;top:4px;width:14px;height:14px;border-radius:50%;background:#fff;border:2px solid #bbb}
.timeline-content{background:#fafafa;border:1px solid #eee;border-radius:4px;padding:10px}

  /* δώσε ξεκάθαρο ύψος στο div του χάρτη μέσα στο modal */
  #lims-appt-map { height: 360px; width: 100%; }
  /* (προαιρετικό) σε μικρές οθόνες λίγο πιο μικρό */
  @media (max-width: 768px){ #lims-appt-map{ height: 280px; } }
</style>

<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <!-- Header -->
        <div class="row">
          <div class="col-md-6">
            <h4 class="mtop5">
              <?php echo _l('lims_order'); ?> <?php echo '#'.(int)$row->id; ?>
              <?php
                $status_code = $row->status ?? 'draft';
                $st = isset($lims_status_map[$status_code]) ? $lims_status_map[$status_code] : null;

                if ($st) {
                    $text  = $st->name ?: ucfirst((string)$status_code);
                    $color = trim($st->color ?? '');
                    // bootstrap label class, απλό default και χρώμα από το status
                    if ($color !== '') {
                        echo '<span class="label mleft5" style="background:'
                           . html_escape($color)
                           . ';">'
                           . html_escape($text)
                           . '</span>';
                    } else {
                        echo '<span class="label label-default mleft5">'
                           . html_escape($text)
                           . '</span>';
                    }
                } else {
                    // Fallback: άγνωστο status, δείξε το code
                    echo '<span class="label label-default mleft5">'
                       . html_escape(ucfirst((string)$status_code))
                       . '</span>';
                }
              ?>
            </h4>
            <div class="text-muted">
              <small>
                <?php echo _l('date_created'); ?>:
                <?php echo !empty($row->created_at) ? _dt($row->created_at) : '—'; ?>
                &nbsp;|&nbsp;
                <?php echo _l('due_date'); ?>:
                <?php echo !empty($row->due_at) ? _dt($row->due_at) : '—'; ?>
                &nbsp;|&nbsp;
                <?php echo _l('priority'); ?>:
                <?php echo lims_priority_label($row->priority); ?>
              </small>
            </div>
          </div>
          <div class="col-md-6 text-right">
            <?php
              // Βρες το πιο πρόσφατο linked invoice (αν υπάρχει)
              $link = $this->db->where('order_id',(int)$row->id)
                               ->order_by('id','DESC')
                               ->get(db_prefix().'lims_billing_links')->row();
              $invBtnHtml = '';
              if ($link) {
                  $inv   = $this->db->where('id',(int)$link->invoice_id)->get(db_prefix().'invoices')->row();
                  if ($inv) {
                      if (!function_exists('format_invoice_number')) { $this->load->helper('invoices'); }
                      $invNo = function_exists('format_invoice_number') ? format_invoice_number($inv->id) : ('#'.$inv->id);
                      $invBtnHtml = '<a href="'.admin_url('invoices#'.(int)$inv->id).'" class="btn btn-success" target="_blank">'
                                  . '<i class="fa fa-file-text"></i> '.$invNo.'</a>';
                  }
              }
            ?>
            <div class="btn-group">
				<a href="<?php echo admin_url('lims/orders'); ?>" class="btn btn-default">
					<i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?>
				</a>

				<!-- PDF placeholder (inactive) -->
				<a class="btn btn-default" href="<?php echo admin_url('lims/orders/print_pdf/'.(int)$row->id); ?>" target="_blank">
					<i class="fa fa-file-pdf"></i> PDF
				</a>

				<a href="<?php echo admin_url('lims/orders/print_sample_labels/'.(int)$row->id.'?print=1'); ?>" target="_blank" class="btn btn-default">
					<i class="fa fa-tags"></i> <?php echo _l('lims_pdf_sample_labels') ?: 'Sample Labels'; ?>
				</a>
				<a href="<?php echo admin_url('lims/tests/order/'.(int)$row->id); ?>" target="_blank" class="btn btn-default">
					<i class="fa fa-pen-to-square"></i> <?php echo _l('lims_order') ?: 'Order'; ?>
				</a>
				
				<?php if (has_permission('invoices','','create')): ?>
					<?php if (!$invBtnHtml): ?>
						<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-file-text"></i> <?php echo _l('invoice_create'); ?> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="<?php echo admin_url('lims/orders/create_invoice/'.(int)$row->id.'?mode=draft'); ?>">
										<?php echo _l('lims_convert_as_draft') ?: 'Convert as Draft'; ?>
									</a>
								</li>
								<li>
									<a href="<?php echo admin_url('lims/orders/create_invoice/'.(int)$row->id.'?mode=normal'); ?>">
										<?php echo _l('lims_convert') ?: 'Convert'; ?>
									</a>
								</li>
								<li>
									<a href="<?php echo admin_url('lims/orders/create_invoice/'.(int)$row->id.'?mode=pay'); ?>">
										<?php echo _l('lims_convert_and_pay') ?: 'Convert & Pay'; ?>
									</a>
								</li>
							</ul>
						</div>
					<?php else: ?>
						<?php echo $invBtnHtml; ?>
					<?php endif; ?>
				<?php endif; ?>
			  
				<?php if (has_permission('estimates','','create')): ?>
				  <a href="<?php echo admin_url('lims/orders/create_estimate/'.(int)$row->id); ?>"
					 class="btn btn-default">
					<i class="fa fa-file"></i>
					<?php
					  echo _l('estimate_create')
						?: (_l('create_estimate') ?: 'Create Estimate');
					?>
				  </a>
				<?php endif; ?>

            </div>

            <!-- ΔΥΝΑΜΙΚΟ status dropdown από lims_test_statuses -->
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-exchange"></i> <?php echo _l('status'); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" id="dd-status">
              <?php foreach ($lims_status_rows as $st): ?>
                <?php if ((int)$st->active !== 1) continue; ?>
                <li>
                  <a href="#" class="lnk-change-status" data-status="<?php echo html_escape($st->code); ?>">
                    <?php echo html_escape($st->name ?: $st->code); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>

          </div>
        </div>

        <hr class="mbot10" />

        <!-- Subject / Contract / Notes -->
        <div class="row">
          <div class="col-md-4">
            <h5 class="mbot10">
              <?php echo _l('lims_subject') ?: _l('client'); ?>
            </h5>
            <?php
              // Φέρε subject (αν υπάρχει)
              $subject = null;
              if (!empty($row->subject_id)) {
                $subject = $this->db->where('id', (int)$row->subject_id)
                                    ->get(db_prefix().'lims_subjects')
                                    ->row();
              }

              // Προσπάθησε να βρεις συνδεδεμένο client είτε από το order, είτε από το subject
              $linked_client     = null;
              $linked_client_id  = null;

              if (!empty($row->client_id)) {
                $linked_client_id = (int)$row->client_id;
                $linked_client    = $this->db->select('company')
                  ->where('userid', $linked_client_id)
                  ->get(db_prefix().'clients')
                  ->row();
              } elseif ($subject && !empty($subject->client_id)) {
                $linked_client_id = (int)$subject->client_id;
                $linked_client    = $this->db->select('company')
                  ->where('userid', $linked_client_id)
                  ->get(db_prefix().'clients')
                  ->row();
              }
            ?>

            <?php if ($subject): ?>
              <?php
                // Προσπάθεια για όνομα subject
                $subject_name = '';
                if (!empty($subject->subject_name)) {
                  $subject_name = $subject->subject_name;
                } elseif (!empty($subject->name)) {
                  $subject_name = $subject->name;
                } else {
                  $fn = $subject->firstname ?? $subject->first_name ?? '';
                  $ln = $subject->lastname  ?? $subject->last_name  ?? '';
                  $subject_name = trim($fn.' '.$ln);
                }
                if ($subject_name === '') {
                  $subject_name = 'Subject #'.(int)$row->subject_id;
                }
              ?>
              <p class="no-mbot">
                <strong><?php echo html_escape($subject_name); ?></strong>
                <br>
                <small class="text-muted">
                  <?php if (!empty($subject->id_number)): ?>
                    ID / Passport: <?php echo html_escape($subject->id_number); ?><br>
                  <?php endif; ?>
                  <?php if (!empty($subject->internal_code)): ?>
                    Internal code: <?php echo html_escape($subject->internal_code); ?><br>
                  <?php endif; ?>
                  <?php if (!empty($subject->email)): ?>
                    <i class="fa fa-envelope"></i>
                    <a href="mailto:<?php echo html_escape($subject->email); ?>">
                      <?php echo html_escape($subject->email); ?>
                    </a><br>
                  <?php endif; ?>
                </small>

                <?php if ($linked_client): ?>
                  <br>
                  <small>
                    <?php echo _l('client') ?: 'Client'; ?>:
                    <a href="<?php echo admin_url('clients/client/'.$linked_client_id); ?>">
                      <?php echo html_escape($linked_client->company).' (#'.$linked_client_id.')'; ?>
                    </a>
                  </small>
                <?php endif; ?>
              </p>

            <?php elseif ($linked_client): ?>
              <!-- Fallback: δεν υπάρχει subject, μόνο client -->
              <p class="no-mbot">
                <a href="<?php echo admin_url('clients/client/'.$linked_client_id); ?>">
                  <?php echo html_escape($linked_client->company).' (#'.$linked_client_id.')'; ?>
                </a>
              </p>
            <?php else: ?>
              <p class="text-muted">—</p>
            <?php endif; ?>
          </div>


          <div class="col-md-4">
            <h5 class="mbot10">
			  <?php echo _l('lims_contract'); ?>
			  <?php if (has_permission('lims','','manage_orders') || has_permission('lims','','admin')): ?>
				<button class="btn btn-default mleft5" id="btn-edit-contract">
				  <i class="fa fa-pencil"></i> <?php echo _l('lims_edit') ?: 'Edit'; ?>
				</button>
			  <?php endif; ?>
			</h5>

            <?php
              $contractId = null;
              if (!empty($lines)) {
                foreach ($lines as $ln) {
                  if (!empty($ln->from_contract_id)) { $contractId = (int)$ln->from_contract_id; break; }
                }
              }
              if (!$contractId && !empty($row->contract_id)) { $contractId = (int)$row->contract_id; }
              if ($contractId) {
                $contract = $this->db->where('id',$contractId)->get(db_prefix().'lims_contracts')->row();
              }
            ?>
            <p class="no-mbot">
              <?php if (!empty($contract)): ?>
                <?php echo html_escape($contract->name).' (#'.(int)$contract->id.')'; ?>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </p>
          </div>

          <div class="col-md-4">
            <h5 class="mbot10">
			  <?php echo _l('notes'); ?>
			  <?php if (has_permission('lims','','manage_orders') || has_permission('lims','','admin')): ?>
				<button class="btn btn-default mleft5" id="btn-edit-notes">
				  <i class="fa fa-pencil"></i> <?php echo _l('lims_edit') ?: 'Edit'; ?>
				</button>
			  <?php endif; ?>
			</h5>
			<p class="no-mbot" id="order-notes-view"><?php echo !empty($row->notes) ? nl2br(html_escape($row->notes)) : '<span class="text-muted">—</span>'; ?></p>

          </div>
        </div>

		<!-- Client Primary Contact -->
        <div class="row mtop20">
          <div class="col-md-6">
            <h5 class="mbot10"><?php echo _l('contact_primary') ?: 'Primary Contact'; ?></h5>
            <?php
              $pc = null;
              $clientIdForContact = null;

              if (!empty($row->client_id)) {
                $clientIdForContact = (int)$row->client_id;
              } elseif (!empty($row->subject_id)) {
                // αν το subject είναι συνδεδεμένο με client, χρησιμοποίησέ τον
                $sub_client = $this->db->select('client_id')
                  ->where('id', (int)$row->subject_id)
                  ->get(db_prefix().'lims_subjects')
                  ->row();
                if ($sub_client && !empty($sub_client->client_id)) {
                  $clientIdForContact = (int)$sub_client->client_id;
                }
              }

              if ($clientIdForContact) {
                $pc = $this->db->where('userid', $clientIdForContact)
                               ->where('is_primary',1)
                               ->get(db_prefix().'contacts')->row();
              }
            ?>

            <?php if ($pc): ?>
              <p class="no-mbot">
                <strong><?php echo html_escape(trim(($pc->firstname ?? '').' '.($pc->lastname ?? ''))); ?></strong><br>
                <?php if (!empty($pc->email)): ?>
                  <i class="fa fa-envelope"></i>
                  <a href="mailto:<?php echo html_escape($pc->email); ?>"><?php echo html_escape($pc->email); ?></a><br>
                <?php endif; ?>
                <?php if (!empty($pc->phonenumber)): ?>
                  <i class="fa fa-phone"></i>
                  <a href="tel:<?php echo html_escape($pc->phonenumber); ?>"><?php echo html_escape($pc->phonenumber); ?></a>
                <?php endif; ?>
              </p>
            <?php else: ?>
              <p class="text-muted">—</p>
            <?php endif; ?>
          </div>

          <!-- Barcode block -->
          <div class="col-md-6">
            <h5 class="mbot10"><?php echo _l('barcode') ?: 'Barcode'; ?></h5>
            <?php if (empty($row->order_barcode)): ?>
              <a href="<?php echo admin_url('lims/orders/generate_barcode/'.(int)$row->id); ?>" class="btn btn-default">
                <i class="fa fa-barcode"></i> <?php echo _l('Generate Barcode') ?: 'Generate Barcode'; ?>
              </a>
            <?php else: ?>
              <a href="<?php echo admin_url('lims/orders/generate_barcode/'.(int)$row->id.'?force=1'); ?>" class="btn btn-warning">
                <i class="fa fa-refresh"></i> <?php echo _l('Regenerate') ?: 'Regenerate'; ?>
              </a>
            <?php endif; ?>
            <?php if (!empty($row->order_barcode)): ?>
              <div class="row mtop15">
                <div class="col-md-6">
                  <h5 class="mbot10"><i class="fa fa-barcode"></i> <?php echo _l('Order Barcode') ?: 'Order Barcode'; ?></h5>
                  <svg id="order-barcode-svg"></svg>
                  <div class="mtop10">
                    <code id="order-barcode-text"><?php echo html_escape($row->order_barcode); ?></code>
                    <button type="button" class="btn btn-default" id="btn-copy-barcode"><i class="fa fa-clipboard"></i> <?php echo _l('copy') ?: 'Copy'; ?></button>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <hr/>

        <!-- Υπηρεσίες (χωρίς τιμές) -->
        <h5 class="mbot10">
		  <?php echo _l('items') ?: 'Services'; ?>
		  <?php if (has_permission('lims','','manage_orders') || has_permission('lims','','admin')): ?>
			<button class="btn btn-default mleft5" id="btn-edit-items">
			  <i class="fa fa-pencil"></i> <?php echo _l('lims_edit') ?: 'Edit'; ?>
			</button>
		  <?php endif; ?>
		</h5>

        <?php if (empty($lines)): ?>
          <p class="text-muted">—</p>
        <?php else: ?>
          <div class="list-group">
            <?php
              // ------- Split order lines by type -------
              $panelIds   = [];
              $analysisIds= [];
              $cultureIds = [];
              foreach ($lines as $ln) {
                if ($ln->source_type === 'panel')   { $panelIds[]   = (int)$ln->source_id; }
                if ($ln->source_type === 'analysis'){ $analysisIds[]= (int)$ln->source_id; }
                if ($ln->source_type === 'culture') { $cultureIds[] = (int)$ln->source_id; }
              }
              $panelIds    = array_values(array_unique($panelIds));
              $analysisIds = array_values(array_unique($analysisIds));
              $cultureIds  = array_values(array_unique($cultureIds));

              // ------- Fetch panel->analyses for nested listing (ONLY for selected panel IDs) -------
              $panelAnalyses = [];
              if (!empty($panelIds)) {
                $p = db_prefix();
                $rows = $this->db->select("pi.panel_id,a.name,a.code")
                  ->from("{$p}lims_panel_items pi")
                  ->join("{$p}lims_analyses a","a.id = pi.analysis_id","left")
                  ->where_in('pi.panel_id', $panelIds)
                  ->order_by('pi.sort_order','ASC')
                  ->get()->result_array();
                foreach ($rows as $r) {
                  $pid = (int)$r['panel_id'];
                  if (!isset($panelAnalyses[$pid])) $panelAnalyses[$pid] = [];
                  $panelAnalyses[$pid][] = [
                    'name' => (string)$r['name'],
                    'code' => (string)($r['code'] ?? ''),
                  ];
                }
              }

              // ------- Fetch culture details (ONLY for selected culture IDs) -------
              $cultureDetails = [];
              if (!empty($cultureIds)) {
                $p = db_prefix();
                $cRows = $this->db->select("c.id,c.name,c.code,c.method,c.tat_hours,c.sample_type_id,st.name AS st_name,st.code AS st_code")
                  ->from("{$p}lims_cultures c")
                  ->join("{$p}lims_sample_types st","st.id = c.sample_type_id","left")
                  ->where_in('c.id', $cultureIds)
                  ->get()->result();
                foreach ($cRows as $r) {
                  $cultureDetails[(int)$r->id] = $r;
                }
              }

              // ------- Build display arrays from $lines (preserve order) -------
              $displayPanels   = [];
              $displayAnalyses = [];
              $displayCultures = [];
              foreach ($lines as $ln) {
                if ($ln->source_type === 'panel') {
                  $displayPanels[] = [
                    'id'   => (int)$ln->source_id,
                    'name' => $ln->name,
                    'code' => '',
                  ];
                } elseif ($ln->source_type === 'analysis') {
                  $displayAnalyses[] = [
                    'id'   => (int)$ln->source_id,
                    'name' => $ln->name,
                    'code' => '',
                  ];
                } elseif ($ln->source_type === 'culture') {
                  $cid = (int)$ln->source_id;
                  $displayCultures[] = [
                    'id'   => $cid,
                    'name' => $ln->name,
                    'code' => isset($cultureDetails[$cid]) ? ($cultureDetails[$cid]->code ?? '') : '',
                  ];
                }
              }
            ?>

            <div class="panel_s">
              <div class="panel-body">
                <h4 class="mbot20"><?php echo _l('lims_select_services') ?: 'Selected services'; ?></h4>

                <div class="row">
                  <!-- PANELS -->
                  <div class="col-md-4">
                    <h5 class="mbot10"><?php echo _l('lims_panels') ?: 'Panels'; ?></h5>
                    <?php if (empty($displayPanels)): ?>
                      <p class="text-muted"><?php echo _l('no_items_found') ?: 'None'; ?></p>
                    <?php else: ?>
                      <ul class="list-unstyled">
                        <?php foreach ($displayPanels as $pnl): ?>
                          <li class="mbot10">
                            <strong><?php echo html_escape($pnl['name']); ?></strong>
                            <?php if (!empty($pnl['code'])): ?>
                              <span class="label label-default mleft5"><?php echo html_escape($pnl['code']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($panelAnalyses[$pnl['id']])): ?>
                              <div class="mtop5 text-muted">
                                <em><?php echo _l('lims_analyses') ?: 'Analyses'; ?>:</em>
                                <ul class="list-unstyled mtop5 mbot0">
                                  <?php foreach ($panelAnalyses[$pnl['id']] as $a): ?>
                                    <li>– <?php echo html_escape($a['name']); ?>
                                      <?php if (!empty($a['code'])): ?>
                                        <small class="text-muted mleft5"><?php echo html_escape($a['code']); ?></small>
                                      <?php endif; ?>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                              </div>
                            <?php endif; ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </div>

                  <!-- ANALYSES (standalone) -->
                  <div class="col-md-4">
                    <h5 class="mbot10"><?php echo _l('lims_analyses') ?: 'Analyses'; ?></h5>
                    <?php if (empty($displayAnalyses)): ?>
                      <p class="text-muted"><?php echo _l('no_items_found') ?: 'None'; ?></p>
                    <?php else: ?>
                      <ul class="list-unstyled">
                        <?php foreach ($displayAnalyses as $an): ?>
                          <li class="mbot5">
                            <strong><?php echo html_escape($an['name']); ?></strong>
                            <?php if (!empty($an['code'])): ?>
                              <span class="label label-default mleft5"><?php echo html_escape($an['code']); ?></span>
                            <?php endif; ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </div>

                  <!-- CULTURES -->
                  <div class="col-md-4">
                    <h5 class="mbot10"><?php echo _l('lims_cultures') ?: 'Cultures'; ?></h5>
                    <?php if (empty($displayCultures)): ?>
                      <p class="text-muted"><?php echo _l('no_items_found') ?: 'None'; ?></p>
                    <?php else: ?>
                      <ul class="list-unstyled">
                        <?php foreach ($displayCultures as $cu):
                          $det = $cultureDetails[$cu['id']] ?? null; ?>
                          <li class="mbot10">
                            <strong><?php echo html_escape($cu['name']); ?></strong>
                            <?php if (!empty($cu['code'])): ?>
                              <span class="label label-default mleft5"><?php echo html_escape($cu['code']); ?></span>
                            <?php endif; ?>
                            <?php if ($det): ?>
                              <div class="mtop5 text-muted">
                                <div>
                                  <small>
                                    <?php echo _l('lims_sample_type') ?: 'Sample Type'; ?>:
                                    <?php echo html_escape($det->st_name ?? '-'); ?>
                                    <?php if (!empty($det->st_code)): ?>
                                      <span class="mleft5">(<?php echo html_escape($det->st_code); ?>)</span>
                                    <?php endif; ?>
                                  </small>
                                </div>
                                <?php if (!empty($det->method)): ?>
                                  <div><small><?php echo _l('lims_method') ?: 'Method'; ?>: <?php echo html_escape($det->method); ?></small></div>
                                <?php endif; ?>
                                <?php if (!empty($det->tat_hours)): ?>
                                  <div><small><?php echo _l('lims_tat_hours') ?: 'TAT (hours)'; ?>: <?php echo (int)$det->tat_hours; ?></small></div>
                                <?php endif; ?>
                              </div>
                            <?php endif; ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

          </div>
        <?php endif; ?>

        <hr/>
		<?php
		  $p = db_prefix();

		  // Samples (ήδη τα φέρνεις, αλλά ας τα χρησιμοποιήσουμε και εδώ για ids)
		  $samples = $this->db->select("s.*, st.name AS st_name, st.code AS st_code, st.min_volume AS st_min_volume")
			->from("{$p}lims_samples s")
			->join("{$p}lims_sample_types st","st.id = s.sample_type_id","left")
			->where('s.order_id', (int)$row->id)
			->order_by('s.id','ASC')
			->get()->result();

		  $samples_count = count($samples);

		  // Tests summary via sample_id
		  $tests_total     = 0;
		  $tests_completed = 0;
		  $tests_pending   = 0;

		  if ($this->db->table_exists("{$p}lims_tests") && $samples_count > 0) {

			$sampleIds = array_map(function($s){
			  return (int)$s->id;
			}, $samples);

			$tests = $this->db
			  ->where_in('sample_id', $sampleIds)
			  ->get("{$p}lims_tests")
			  ->result();

			$tests_total = count($tests);

			if ($tests_total > 0) {
			  foreach ($tests as $t) {
				$st = (string)$t->status;
				// ό,τι θεωρείς "completed/final"
				if (in_array($st, ['approved','verified','reported','complete'])) {
				  $tests_completed++;
				} else {
				  $tests_pending++;
				}
			  }
			}
		  }
		?>

        <!-- Samples & Appointments placeholders -->
        <div class="row">
			<div class="col-md-6">
				<h5 class="mbot10">
				  <?php echo _l('lims_samples'); ?>
				  <?php if ($samples_count || $tests_total): ?>
					<br/><small class="text-muted">
					  <?php
						echo $samples_count.' '._l('lims_samples');
						if ($tests_total) {
						  echo ' · '.$tests_total.' '._l('lims_tests') ?: 'tests';
						  echo ' ('.$tests_pending.' '._l('pending_tasks') ?: 'pending';
						  echo ', '.$tests_completed.' '._l('completed_tasks') ?: 'completed';
						  echo ')';
						}
					  ?>
					</small>
				  <?php endif; ?>
				</h5>
				  <?php if (has_permission('lims','','manage_orders') || has_permission('lims','','admin')): ?>
					<a href="<?php echo admin_url('lims/samples/create?order_id='.(int)$row->id.'&return=order'); ?>" class="btn btn-default btn-sm">
					  <i class="fa fa-plus"></i> <?php echo _l('lims_sample_add'); ?>
					</a>
					<a href="<?php echo admin_url('lims/samples?order_id='.(int)$row->id); ?>" class="btn btn-default btn-sm">
					  <i class="fa fa-list"></i> <?php echo _l('lims_samples'); ?>
					</a>
					<a href="<?php echo admin_url('lims/orders/materialize_samples/'.(int)$row->id); ?>" class="btn btn-default btn-sm mleft5" onclick="return confirm('Generate Samples & Tests from selected items?');">
					  <i class="fa fa-magic"></i> Generate Samples & Tests
					</a>
				  <?php endif; ?>

				<?php
					$p = db_prefix();
					$samples = $this->db->select("s.*, st.name AS st_name, st.code AS st_code, st.min_volume AS st_min_volume")
						->from("{$p}lims_samples s")
						->join("{$p}lims_sample_types st","st.id = s.sample_type_id","left")
						->where('s.order_id', (int)$row->id)
						->order_by('s.id','ASC')
						->get()->result();
					?>

					<h5 class="mbot10"><?php echo _l('lims_samples'); ?></h5>
					<?php if (empty($samples)): ?>
					  <p class="text-muted">—</p>
					<?php else: ?>
					  <div class="list-group mtop10">
						<?php foreach ($samples as $sp): 
						  $isCollected = (string)$sp->status === 'collected';
						  $badgeCls = $isCollected ? 'success' : 'default';
						?>
						  <div class="list-group-item">
							<div class="row">
							  <div class="col-sm-8">
								<strong>Sample #<?php echo (int)$sp->id; ?></strong>
								<?php if (!empty($sp->sample_uid)): ?>
								  <code class="mleft5"><?php echo html_escape($sp->sample_uid); ?></code>
								<?php endif; ?>
								<?php if (!empty($sp->barcode)): ?>
								  <span class="label label-info mleft5"><i class="fa fa-barcode"></i> <?php echo html_escape($sp->barcode); ?></span>
								<?php endif; ?>

								<!-- Tags -->
								<div class="mtop5">
								  <?php if (!empty($sp->st_name)): ?>
									<span class="label label-primary"><?php echo html_escape($sp->st_name); ?><?php echo !empty($sp->st_code) ? ' ('.html_escape($sp->st_code).')' : ''; ?></span>
								  <?php endif; ?>
								  <?php if (!empty($sp->st_min_volume)): ?>
									<span class="label label-default"><?php echo _l('lims_min_volume') ?: 'Min Volume'; ?>: <?php echo (float)$sp->st_min_volume; ?> ml</span>
								  <?php endif; ?>
								  <span class="label label-<?php echo $badgeCls; ?> js-sp-status-<?php echo (int)$sp->id; ?>">
									<?php echo ucfirst($sp->status ?: 'pending'); ?>
								  </span>
								  <?php if (!empty($sp->collected_at)): ?>
									<span class="label label-default js-sp-collected-at-<?php echo (int)$sp->id; ?>">
									  <?php echo _l('lims_collected_at') ?: 'Collected At'; ?>: <?php echo _dt($sp->collected_at); ?>
									</span>
								  <?php else: ?>
									<span class="label label-default js-sp-collected-at-<?php echo (int)$sp->id; ?>" style="display:none;"></span>
								  <?php endif; ?>
								</div>
							  </div>
							  <div class="col-sm-4 text-right">
								<button
								  class="btn btn-<?php echo $isCollected ? 'warning' : 'success'; ?> btn-sm js-toggle-sample"
								  data-id="<?php echo (int)$sp->id; ?>"
								  data-collected="<?php echo $isCollected ? '0' : '1'; ?>">
								  <i class="fa <?php echo $isCollected ? 'fa-undo' : 'fa-check'; ?>"></i>
								  <?php echo $isCollected ? (_l('lims_mark_uncollected') ?: 'Mark Pending')
														  : (_l('lims_mark_collected') ?: 'Mark Collected'); ?>
								</button>
							  </div>
							</div>
						  </div>
						<?php endforeach; ?>
					  </div>
					<?php endif; ?>

		  </div>

		<div class="col-md-6">
		  <h5 class="mbot10"><?php echo _l('lims_appointments'); ?></h5>

		  <?php
			// βρες συνδεδεμένο appointment (αν έχει) — παίρνουμε το πιο πρόσφατο με ίδιο order_id
			$ap = $this->db->where('order_id',(int)$row->id)
						   ->order_by('appointment_at','DESC')
						   ->limit(1)
						   ->get(db_prefix().'lims_appointments')->row();
		  ?>

		  <?php if ($ap): ?>
			<div class="panel_s">
			  <div class="panel-body">
				<div class="row">
				  <div class="col-xs-8">
					<strong>#<?php echo (int)$ap->id; ?></strong>
					<div class="text-muted">
					  <i class="fa fa-calendar"></i>
					  <?php echo _dt($ap->appointment_at); ?>
					  &nbsp;·&nbsp;
					  <i class="fa fa-map-marker"></i>
					  <?php echo html_escape($ap->location_text ?: '—'); ?>
					</div>
					<div class="text-muted mtop5">
					  <small>
						<?php echo _l('status'); ?>:
						<span class="label label-default"><?php echo ucfirst($ap->status); ?></span>
						<?php if ($ap->assigned_staff): ?>
						  &nbsp;·&nbsp;<i class="fa fa-user"></i> <?php echo get_staff_full_name($ap->assigned_staff); ?>
						<?php endif; ?>
					  </small>
					</div>
				  </div>
				  <div class="col-xs-4 text-right">
					<a class="btn btn-default btn-sm" href="<?php echo admin_url('lims/appointments/show/'.(int)$ap->id); ?>">
					  <i class="fa fa-eye"></i> <?php echo _l('view'); ?>
					</a>
					<button class="btn btn-danger btn-sm js-unlink-appt" data-appt="<?php echo (int)$ap->id; ?>">
					  <i class="fa fa-unlink"></i> <?php echo _l('unlink'); ?>
					</button>
				  </div>
				</div>
			  </div>
			</div>
		    <?php else: ?>
			<?php
				// Υπολόγισε upcoming count για το SUBJECT αν υπάρχει, αλλιώς για τον client
				$upcoming_count = 0;
				$has_upcoming   = false;

				if (!empty($row->subject_id) || !empty($row->client_id)) {
					$now = date('Y-m-d H:i:s');

					if (!empty($row->subject_id)) {
						// new model: appointments δεμένα με subject
						$this->db->where('subject_id', (int)$row->subject_id);
					} else {
						// fallback: παλιό μοντέλο client-based
						$this->db->where('client_id', (int)$row->client_id);
					}

					$this->db->where('appointment_at >=', $now);
					$this->db->where_not_in('status', ['canceled','no_show']);
					$upcoming_count = (int)$this->db->count_all_results(db_prefix().'lims_appointments');
					$has_upcoming   = $upcoming_count > 0;
				}
          ?>

		  <div class="btn-group">
			<button
			  class="btn btn-default btn-sm js-open-link-appt"
			  <?php echo $has_upcoming ? '' : 'disabled title="No upcoming appointments for this client"'; ?>>
			  <i class="fa fa-link"></i>
			  <?php echo _l('link_existing_order') ?: 'Link existing Appointment'; ?>
			  <?php if ($has_upcoming): ?>
				<span class="badge"><?php echo $upcoming_count; ?></span>
			  <?php endif; ?>
			</button>

			<button class="btn btn-default btn-sm js-open-create-appt">
			  <i class="fa fa-plus"></i> <?php echo _l('new_appointment'); ?>
			</button>
		  </div>
		<?php endif; ?>

		</div>

        </div>

        <hr/>

        <!-- Audit / Chain log -->
        <div class="panel_s mtop20">
          <div class="panel-body">
            <h4 class="mbot10"><i class="fa fa-history"></i> <?php echo _l('lims_activity_log'); ?></h4>
            <?php
              $activity = isset($activity) ? $activity : $this->orders_model->get_activity($row->id, 200);
              if (!empty($activity)):
            ?>
            <ul class="timeline">
              <?php foreach ($activity as $a):
                $who = $a->staff_id ? get_staff_full_name($a->staff_id) : _l('system');
                $metaTxt = '';
                if (!empty($a->meta)) {
                  $m = json_decode($a->meta, true);
                  if (json_last_error() === JSON_ERROR_NONE && is_array($m)) {
                    $metaTxt = '<small class="text-muted">'.html_escape(print_r($m, true)).'</small>';
                  }
                }
              ?>
              <li class="timeline-item">
                <div class="timeline-time"><?php echo _dt($a->created_at); ?></div>
                <div class="timeline-icon"><i class="fa fa-circle"></i></div>
                <div class="timeline-content">
                  <strong><?php echo _l('lims_activity_who'); ?>:</strong> <?php echo html_escape($who); ?><br/>
                  <strong><?php echo _l('lims_activity_action'); ?>:</strong> <?php echo html_escape($a->action); ?><br/>
                  <?php if (!empty($a->message)): ?>
                    <div class="mtop5"><?php echo nl2br(html_escape($a->message)); ?></div>
                  <?php endif; ?>
                  <?php if ($metaTxt): ?>
                    <div class="mtop5"><?php echo $metaTxt; ?></div>
                  <?php endif; ?>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php else: ?>
              <p class="text-muted"><?php echo _l('no_activity_found') ?: 'No activity yet.'; ?></p>
            <?php endif; ?>
          </div>
        </div>
		<?php
		  // CSRF helpers
		  $csrf_name = $this->security->get_csrf_token_name();
		  $csrf_hash = $this->security->get_csrf_hash();
		?>

		<!-- NOTES MODAL -->
		<div class="modal fade" id="modal-notes" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document"><div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			  <h4 class="modal-title"><?php echo _l('lims_notes'); ?></h4>
			</div>
			<form id="form-notes" method="post">
			  <div class="modal-body">
				<input type="hidden" name="<?php echo html_escape($csrf_name); ?>" value="<?php echo html_escape($csrf_hash); ?>">
				<div class="form-group">
				  <textarea class="form-control" name="notes" rows="5"><?php echo html_escape($row->notes ?? ''); ?></textarea>
				  <small class="help-block text-muted"><?php echo _l('lims_notes_inline_hint'); ?></small>
				</div>
			  </div>
			  <div class="modal-footer">
				<button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('lims_cancel'); ?></button>
			  </div>
			</form>
		  </div></div>
		</div>

		<!-- CONTRACT MODAL -->
		<div class="modal fade" id="modal-contract" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document"><div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			  <h4 class="modal-title"><?php echo _l('lims_contract'); ?></h4>
			</div>
			<form id="form-contract" method="post">
			  <div class="modal-body">
				<input type="hidden" name="<?php echo html_escape($csrf_name); ?>" value="<?php echo html_escape($csrf_hash); ?>">
				<div class="form-group">
				  <?php
					// λίστα συμβολαίων
					$contracts = $this->db->order_by('priority','DESC')->order_by('name','ASC')->get(db_prefix().'lims_contracts')->result();
					// προσπάθησε να ανιχνεύσεις active contract από τις γραμμές
					$currentContractId = null;
					if (!empty($lines)) {
					  foreach ($lines as $ln) { if (!empty($ln->from_contract_id)) { $currentContractId = (int)$ln->from_contract_id; break; } }
					}
					if (!$currentContractId && !empty($row->contract_id)) $currentContractId = (int)$row->contract_id;
				  ?>
				  <label><?php echo _l('lims_contract'); ?></label>
				  <select name="contract_id" class="form-control selectpicker" data-live-search="true" data-size="10">
					<option value=""><?php echo _l('dropdown_non_selected_tex') ?: '—'; ?></option>
					<?php foreach($contracts as $c): ?>
					  <option value="<?php echo (int)$c->id; ?>" <?php echo ($currentContractId===(int)$c->id?'selected':''); ?>>
						<?php echo html_escape($c->name); ?>
					  </option>
					<?php endforeach; ?>
				  </select>
				  <small class="help-block text-muted"><?php echo _l('lims_contract_hint'); ?></small>
				</div>
			  </div>
			  <div class="modal-footer">
				<button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('lims_cancel'); ?></button>
			  </div>
			</form>
		  </div></div>
		</div>

		<!-- ITEMS MODAL -->
		<div class="modal fade" id="modal-items" tabindex="-1" role="dialog">
		  <div class="modal-dialog modal-lg" role="document"><div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			  <h4 class="modal-title"><?php echo _l('items'); ?></h4>
			</div>
			<form id="form-items" method="post">
			  <div class="modal-body">
				<input type="hidden" name="<?php echo html_escape($csrf_name); ?>" value="<?php echo html_escape($csrf_hash); ?>">
				<div id="items-modal-body">
				  <div class="text-center text-muted mtop20 mbot20"><i class="fa fa-spinner fa-spin"></i></div>
				</div>
			  </div>
			  <div class="modal-footer">
				<button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('lims_cancel'); ?></button>
			  </div>
			</form>
		  </div></div>
		</div>


      </div>
    </div>
  </div>
</div>
<!-- Link Existing Appointment Modal -->
<div class="modal fade" id="modalLinkAppt" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', ['id'=>'formLinkAppt']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-link"></i> <?php echo _l('link_existing_order'); ?></h4>
      </div>
      <div class="modal-body" id="linkApptBody">
        <div class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i></div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
               value="<?php echo $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="order_id" value="<?php echo (int)$row->id; ?>">
        <input type="hidden" name="appointment_id" id="link_appt_id" value="">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('link'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- NEW APPOINTMENT (INLINE) MODAL -->
<div class="modal fade" id="modal-create-appt" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document"><div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      <h4 class="modal-title"><i class="fa fa-calendar"></i> <?php echo _l('new_appointment'); ?></h4>
    </div>
    <div class="modal-body">
      <form id="lims-new-appt-form">
        <input type="hidden" name="<?php echo html_escape($csrf_name); ?>" value="<?php echo html_escape($csrf_hash); ?>">
        <input type="hidden" name="client_id" value="<?php echo (int)$row->client_id; ?>">
        <input type="hidden" name="subject_id" value="<?php echo (int)($row->subject_id ?? 0); ?>">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><?php echo _l('lims_appointment_at'); ?></label>
              <input type="text" class="form-control datetimepicker" name="appointment_at"
                     value="<?php echo date('Y-m-d H:i'); ?>" required>
              <small class="text-muted"><?php echo _l('Pick the date and time of the visit.'); ?></small>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_visit_type'); ?></label>
              <select name="visit_type" class="form-control selectpicker">
                <option value="lab" selected><?php echo _l('lims_visit_type_lab'); ?></option>
                <option value="home"><?php echo _l('lims_visit_type_home'); ?></option>
              </select>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_assigned_staff'); ?></label>
              <select name="assigned_staff" class="form-control selectpicker" data-live-search="true" data-size="8">
                <option value=""><?php echo _l('dropdown_non_selected_tex') ?: '—'; ?></option>
                <?php
                  $staff = $this->db->where('active',1)->order_by('firstname','ASC')->get(db_prefix().'staff')->result();
                  $prefAssigned = get_staff_user_id() ?: '';
                  foreach($staff as $s):
                ?>
                  <option value="<?php echo (int)$s->staffid; ?>" <?php echo ((int)$prefAssigned===(int)$s->staffid?'selected':''); ?>>
                    <?php echo html_escape(get_staff_full_name($s->staffid)); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <!-- Map + address -->
        <div class="form-group">
          <label><?php echo _l('location'); ?></label>
          <div class="input-group">
            <input type="text" name="location_text" id="appt_location_text" class="form-control"
                   placeholder="<?php echo _l('lims_map_address_placeholder'); ?>">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button" id="btn-appt-geosearch">
                <i class="fa fa-search"></i> <?php echo _l('lims_search_on_map'); ?>
              </button>
            </span>
          </div>
          <div id="appt_map" style="height:320px" class="mtop10"></div>

          <div class="row mtop10">
            <div class="col-md-6">
              <label>Lat</label>
              <input type="text" class="form-control" name="lat" id="appt_lat" readonly>
            </div>
            <div class="col-md-6">
              <label>Lng</label>
              <input type="text" class="form-control" name="lng" id="appt_lng" readonly>
            </div>
          </div>
        </div>

        <div class="checkbox checkbox-inline">
          <input type="checkbox" id="appt_create_task" name="create_task" value="1" checked>
          <label for="appt_create_task"><?php echo _l('create_task'); ?></label>
        </div>

        <div class="form-group mtop10">
          <label><?php echo _l('note'); ?></label>
          <textarea class="form-control" rows="3" name="notes"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      <button class="btn btn-primary" id="btn-save-appt"><i class="fa fa-check"></i> <?php echo _l('save'); ?></button>
    </div>
  </div></div>
</div>


<?php init_tail(); ?>

<!-- Leaflet + basic geocode -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
(function($){
  $(function(){
    var code = $('#order-barcode-text').text();
    if (code && window.JsBarcode && document.getElementById('order-barcode-svg')) {
      try {
        JsBarcode("#order-barcode-svg", code, {
          format: "CODE128",
          displayValue: true,
          fontSize: 14,
          height: 60,
          margin: 10
        });
      } catch(e){}
    }
    $('#btn-copy-barcode').on('click', function(){
      var txt = $('#order-barcode-text').text();
      if (!txt) return;
      var ta = document.createElement('textarea');
      ta.value = txt; document.body.appendChild(ta); ta.select();
      try { document.execCommand('copy'); } catch(e){}
      document.body.removeChild(ta);
    });
	var apptMap = null, apptMarker = null;
	/* κάν’ το διαθέσιμο global για να μπορούμε να το καλέσουμε μετά το shown.bs.modal */
	window.initLimsApptMap = function(){
	  var map, marker;
	  var latEl = document.getElementById('appt_lat');
	  var lngEl = document.getElementById('appt_lng');
	  var addrEl= document.getElementById('appt_location_text');
	  var container = document.getElementById('appt_map');
	  if (!window.L || !container) return;

	  // Κύπρος περίπου
	  var start = {lat: 35.166667, lng: 33.366667};
	  var lat = parseFloat(latEl.value) || start.lat;
	  var lng = parseFloat(lngEl.value) || start.lng;

	  // Αν ξανα-ανοίξει το modal, καθάρισε προϋπάρχον χάρτη
	  if (window.__LIMS_APPT_MAP__) {
		try { window.__LIMS_APPT_MAP__.remove(); } catch(e){}
		window.__LIMS_APPT_MAP__ = null;
	  }
		
	  map = L.map(container).setView([lat, lng], (latEl.value && lngEl.value) ? 15 : 12);
	  window.__LIMS_APPT_MAP__ = map;

	  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);

	  marker = L.marker([lat, lng], {draggable:true}).addTo(map);

	  function reverseGeocode(lat, lng){
		fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat='+lat+'&lon='+lng)
		  .then(r=>r.json()).then(function(d){
			if (d && d.display_name) addrEl.value = d.display_name;
		  }).catch(function(){});
	  }

	  marker.on('dragend', function(){
		var p = marker.getLatLng();
		latEl.value = p.lat.toFixed(6);
		lngEl.value = p.lng.toFixed(6);
		reverseGeocode(p.lat, p.lng);
	  });

	  // search button
	  var btn = document.getElementById('btn-appt-geosearch');
	  if (btn) {
		btn.addEventListener('click', function(){
		  var q = addrEl.value;
		  if (!q || q.trim()==='') return;
		  fetch('https://nominatim.openstreetmap.org/search?format=json&q='+encodeURIComponent(q))
			.then(r=>r.json()).then(function(res){
			  if (res && res.length){
				var p = {lat: parseFloat(res[0].lat), lng: parseFloat(res[0].lon)};
				map.setView([p.lat, p.lng], 15);
				marker.setLatLng([p.lat, p.lng]);
				latEl.value = p.lat.toFixed(6);
				lngEl.value = p.lng.toFixed(6);
				addrEl.value = res[0].display_name || q;
			  }
			}).catch(function(){});
		});
	  }

	  // refresh pickers
	  if (window.jQuery && jQuery.fn.selectpicker) {
		jQuery('.selectpicker').selectpicker('render').selectpicker('refresh');
	  }
	  if (window.app && window.app.init_datetimepicker) {
		app.init_datetimepicker();
	  }
	if (window.app && window.app.init_datetimepicker) {
	  app.init_datetimepicker();
	}
	  // πολύ σημαντικό σε modal: invalidateSize μετά το shown
	  setTimeout(function(){
		try { map.invalidateSize(); } catch(e){}
	  }, 200);
	};
	

	  // Όταν το modal εμφανιστεί, φτιάξε/ανανεώσε τον χάρτη
	  $('#limsApptNewModal').on('shown.bs.modal', function () {
		initApptMap();
		// δώσε λίγο χρόνο στο layout και μετά κάνε reflow
		setTimeout(function(){
		  apptMap.invalidateSize();
		  // αν έχεις ήδη lat/lng από το form, κάνε και setView
		  var lat = parseFloat($('#limsApptLat').val()), lng = parseFloat($('#limsApptLng').val());
		  if (!isNaN(lat) && !isNaN(lng)) {
			var ll = L.latLng(lat, lng);
			apptMap.setView(ll, 15);
			apptMarker.setLatLng(ll);
		  }
		}, 150);
	  });
  });

  var BASE = (typeof window.admin_url !== 'undefined' && window.admin_url) ? window.admin_url : '<?php echo admin_url(); ?>';
  var ORDER_ID = <?php echo (int)$row->id; ?>;
  var ORDER = ORDER_ID;

  $(document).on('click', '.lnk-change-status', function(e){
    e.preventDefault();
    var st = $(this).data('status');
    $.post(BASE + 'lims/orders/change_status', {id: ORDER_ID, status: st})
      .done(function(res){
        try { var r = JSON.parse(res); } catch(e){ r = {success:false}; }
        if (r.success) { safeReload(); }
      });
  });

  // NOTES
  $(document).on('click','#btn-edit-notes', function(e){
    e.preventDefault();
    $('#modal-notes').modal('show');
  });
  $(document).on('submit','#form-notes', function(e){
    e.preventDefault();
    $.post(BASE + 'lims/orders/inline_update_notes/' + ORDER, $(this).serialize())
      .done(function(res){
        try{var r=JSON.parse(res);}catch(e){r={success:false};}
        if(r.success){ safeReload(); }
      });
  });

  // CONTRACT
  $(document).on('click','#btn-edit-contract', function(e){
    e.preventDefault();
    if ($.fn.selectpicker) { $('.selectpicker').selectpicker('render').selectpicker('refresh'); }
    $('#modal-contract').modal('show');
  });
  $(document).on('submit','#form-contract', function(e){
    e.preventDefault();
    $.post(BASE + 'lims/orders/inline_update_contract/' + ORDER, $(this).serialize())
      .done(function(res){
        try{var r=JSON.parse(res);}catch(e){r={success:false};}
        if(r.success){ safeReload(); }
      });
  });

  // ITEMS
  $(document).on('click','#btn-edit-items', function(e){
    e.preventDefault();
    $('#modal-items').modal('show');
    $('#items-modal-body').html('<div class="text-center text-muted mtop20 mbot20"><i class="fa fa-spinner fa-spin"></i></div>');
    $.get(BASE + 'lims/orders/inline_items_picker/' + ORDER)
      .done(function(html){
        $('#items-modal-body').html(html);
      });
  });
  $(document).on('submit','#form-items', function(e){
    e.preventDefault();
    $.post(BASE + 'lims/orders/inline_update_items/' + ORDER, $(this).serialize())
      .done(function(res){
        try{var r=JSON.parse(res);}catch(e){r={success:false};}
        if(r.success){ safeReload(); }
      });
  });
  // Open "Link Existing"
    $('.js-open-link-appt').on('click', function(){
      $('#modalLinkAppt').modal('show');
      $('#linkApptBody').html('<div class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i></div>');
      $('#linkApptBody').load(BASE + 'lims/orders/appointments_picker/'+ORDER_ID);
    });

    // Pick appointment row from modal list
    $(document).on('click', '.js-pick-appt', function(){
      var apid = $(this).data('id');
      $('#link_appt_id').val(apid);
      // highlight επιλογή
      $('.js-pick-appt').removeClass('btn-success').addClass('btn-default');
      $(this).removeClass('btn-default').addClass('btn-success');
    });

    // Submit link
    $('#formLinkAppt').on('submit', function(e){
      e.preventDefault();
      var apid = $('#link_appt_id').val();
      if(!apid){ return; }
      var data = $(this).serialize();
      $.post(BASE + 'lims/orders/link_appointment', data)
      .done(function(res){
        try{ var r = JSON.parse(res); }catch(e){ r={success:false}; }
        if(r.success){ safeReload(); }
      });
    });

    // Unlink
    $('.js-unlink-appt').on('click', function(){
      if(!confirm('Unlink appointment?')) return;
      var apid = $(this).data('appt');
      var data = {
        order_id: ORDER_ID,
        appointment_id: apid,
        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
      };
      $.post(BASE + 'lims/orders/unlink_appointment', data)
      .done(function(res){
        try{ var r = JSON.parse(res); }catch(e){ r={success:false}; }
        if(r.success){ safeReload(); }
      });
    });

    
    // Submit create
    $('#formCreateAppt').on('submit', function(e){
      e.preventDefault();
      var data = $(this).serialize();
      $.post(BASE + 'lims/orders/create_appointment_from_order', data)
      .done(function(res){
        try{ var r = JSON.parse(res); }catch(e){ r={success:false}; }
        if(r.success){ safeReload(); }
      });
    });
	
	// Άνοιγμα inline modal (χωρίς AJAX)
	$(document).on('click', '.js-open-create-appt', function(e){
	  e.preventDefault();
	  // refresh selectpicker πριν εμφανιστεί
	  if ($.fn.selectpicker) { $('.selectpicker').selectpicker('render').selectpicker('refresh'); }
	  $('#modal-create-appt').modal('show');
	});

	// Init Leaflet + datetimepicker όταν **φανεί** το modal
	$('#modal-create-appt').on('shown.bs.modal', function () {
	  try {
		// init datetimepicker (Perfex helper)
		if (window.app && app.init_datetimepicker) {
		  app.init_datetimepicker();
		}
		// init selectpicker (ασφάλεια)
		if ($.fn.selectpicker) { $('.selectpicker').selectpicker('render').selectpicker('refresh'); }

		// init map (μία φορά ανά άνοιγμα)
		if (window.initLimsApptMap) { window.initLimsApptMap(); }
	  } catch(e){}
	});

	// Save new appointment
	$(document).on('click', '#btn-save-appt', function(e){
	  e.preventDefault();
	  var $btn  = $(this);
	  var $form = $('#lims-new-appt-form');
	  $btn.prop('disabled', true);

	  $.post(BASE + 'lims/orders/ajax_create_appointment_inline/' + ORDER_ID, $form.serialize())
		.done(function(res){
		  var r; try { r = JSON.parse(res); } catch(e){ r = {success:false}; }
		  if (r.success) {
			// κλείσε modal πριν το reload για να μην «κρεμάει» ο browser
			

			$('#modal-create-appt').modal('hide');
			safeReload();
		  } else {
			alert(r.message || 'Error');
			$btn.prop('disabled', false);
		  }
		})
		.fail(function(){
		  alert('Error');
		  $btn.prop('disabled', false);
		});
	});

	// Save
	$(document).on('click', '#btn-save-appt', function(e){
	  e.preventDefault();
	  var $btn  = $(this);
	  var $form = $('#lims-new-appt-form');
	  $btn.prop('disabled', true);

	  $.post(BASE + 'lims/orders/ajax_create_appointment_inline/' + ORDER_ID, $form.serialize())
		.done(function(res){
		  var r;
		  try { r = (typeof res === 'object') ? res : JSON.parse(res); } catch(e){ r = {success:false}; }
		  if (r.success) {
			$('#_global_modal').modal('hide');        // κλείστο πρώτα
			safeReload();                        // μετά reload
		  } else {
			alert(r.message || 'Error');
			$btn.prop('disabled', false);
		  }
		})
		.fail(function(xhr){
		  // Αν παρά ταύτα έσκασε (λόγω server output), κάνε graceful fallback:
		  $('#_global_modal').modal('hide');
		  safeReload();
		});
	});
	function safeReload() {
		try { $(window).off('beforeunload'); } catch(e){}
		try { window.onbeforeunload = null; } catch(e){}
		setTimeout(function(){ window.location.reload(); }, 80);
	}
	$(document).on('click', '.js-sample-collected', function(e){
	  e.preventDefault();
	  var $btn = $(this), id = $btn.data('id');
	  if (!id) return;
	  $btn.prop('disabled', true);
	  $.post('<?php echo admin_url('lims/orders/ajax_sample_collected'); ?>', {
		sample_id: id,
		'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
	  }).done(function(res){
		var r; try { r = JSON.parse(res); } catch(e){ r = {success:false}; }
		if (r.success) {
		  // ενημέρωσε inline το row
		  var $row = $('#sample-row-'+id);
		  $row.find('td:nth-child(4) .label').removeClass('label-default').addClass('label-success').text('Collected');
		  var now = new Date();
		  $row.find('td:nth-child(5)').text(
			now.getFullYear()+'-'+String(now.getMonth()+1).padStart(2,'0')+'-'+String(now.getDate()).padStart(2,'0')+' '+
			String(now.getHours()).padStart(2,'0')+':'+String(now.getMinutes()).padStart(2,'0')
		  );
		} else {
		  alert('Failed to mark collected');
		  $btn.prop('disabled', false);
		}
	  }).fail(function(){
		alert('Error');
		$btn.prop('disabled', false);
	  });
	});
	// Toggle sample collected (no reload)
	$(document).on('click', '.js-toggle-sample', function(e){
	  e.preventDefault();
	  var $btn = $(this);
	  var sampleId = parseInt($btn.data('id'), 10);
	  var toCollected = parseInt($btn.data('collected'), 10) === 1;

	  $btn.prop('disabled', true);

	  $.post(BASE + 'lims/orders/toggle_sample_collected', {
		  sample_id: sampleId,
		  collected: toCollected ? 1 : 0,
		  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		})
		.done(function(res){
		  var r; try{ r = JSON.parse(res); }catch(e){ r = {success:false}; }
		  if(!r.success){ alert('Error'); $btn.prop('disabled', false); return; }

		  // Update UI inline
		  var $status = $('.js-sp-status-' + sampleId);
		  var $colAt  = $('.js-sp-collected-at-' + sampleId);

		  if (r.status === 'collected') {
			$status.removeClass('label-default').addClass('label-success').text('Collected');
			if (r.collected_at) {
			  $colAt.text('<?php echo _l('lims_collected_at') ?: 'Collected At'; ?>: ' + r.collected_at).show();
			}
			$btn.removeClass('btn-success').addClass('btn-warning')
				.html('<i class="fa fa-undo"></i> <?php echo _l('lims_mark_uncollected') ?: 'Mark Pending'; ?>')
				.data('collected', 0);
		  } else {
			$status.removeClass('label-success').addClass('label-default').text('Pending');
			$colAt.hide().text('');
			$btn.removeClass('btn-warning').addClass('btn-success')
				.html('<i class="fa fa-check"></i> <?php echo _l('lims_mark_collected') ?: 'Mark Collected'; ?>')
				.data('collected', 1);
		  }
		})
		.fail(function(){
		  alert('Error');
		})
		.always(function(){
		  $btn.prop('disabled', false);
		});
	});

  
})(jQuery);


</script>
