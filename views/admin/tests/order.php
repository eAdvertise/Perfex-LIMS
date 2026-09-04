<?php defined('BASEPATH') or exit('No direct script access allowed'); 
// modules/lims/views/admin/tests/order.php

$CI =& get_instance();
$CI->load->model('lims/teststatuses_model','ts_model');
$__ts_rows = $CI->ts_model->all();
$__TS_STATUS_MAP = [];
if (!empty($__ts_rows)) {
    foreach ($__ts_rows as $s) {
        if (!empty($s->code)) {
            $__TS_STATUS_MAP[$s->code] = $s; // object με name, color, κτλ
        }
    }
}
?>
<?php init_head(); ?>
<style>
  .lims-notes-wrap {
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
  }
</style>

<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <!-- HEADER -->
        <div class="row">
          <div class="col-md-8">
            <h4 class="mtop5">
              <?php echo _l('lims_order'); ?> #<?php echo (int)$order->id; ?>
              <small>&mdash; <?php echo _l('lims_tests'); ?></small>
              <?php
                $status     = $order->status ?? 'draft';
                $statusCode = (string)$status;

                // Default label settings
                $labelClass = 'default';
                $labelStyle = '';
                $labelText  = ucfirst($statusCode);

                // 1) Προσπάθεια: από lims_test_statuses
                if (isset($__TS_STATUS_MAP[$statusCode])) {
                    $st = $__TS_STATUS_MAP[$statusCode];
                    if (!empty($st->name)) {
                        $labelText = $st->name;
                    }
                    if (!empty($st->color)) {
                        $hex = html_escape($st->color);
                        // Inline style (Bootstrap .label χωρίς extra class)
                        $labelClass = ''; // δεν χρησιμοποιούμε label-success/info κτλ
                        $labelStyle = ' style="background:' . $hex . ';border-color:' . $hex . ';"';
                    }
                } else {
                    // 2) Fallback: παλιό mapping σε Bootstrap label classes
                    switch ($statusCode) {
                        case 'submitted':   $labelClass='info';    break;
                        case 'accessioned': $labelClass='primary'; break;
                        case 'testing':     $labelClass='warning'; break;
                        case 'verified':    $labelClass='purple';  break;
                        case 'approved':    $labelClass='success'; break;
                        case 'reported':    $labelClass='inverse'; break;
                        case 'canceled':    $labelClass='danger';  break;
                        case 'completed':   $labelClass='success'; break; // Complete
                        case 'signed':      $labelClass='success'; break; // Signed (τελικό)
                        case 'draft':
                        default:
                            $labelClass='default';
                            break;
                    }
                }

                $labelClassAttr = $labelClass !== '' ? ' label-' . $labelClass : '';
              ?>
              <span class="label<?php echo $labelClassAttr; ?> mleft5"<?php echo $labelStyle; ?>>
                <?php echo html_escape($labelText); ?>
              </span>
            </h4>
            <div class="text-muted">
              <small>
                <?php echo _l('date_created'); ?>:
                <?php echo !empty($order->created_at) ? _dt($order->created_at) : '—'; ?>
                &nbsp;|&nbsp;
                <?php echo _l('due_date'); ?>:
                <?php echo !empty($order->due_at) ? _dt($order->due_at) : '—'; ?>
                &nbsp;|&nbsp;
                <?php echo _l('priority'); ?>:
                <?php echo (int)($order->priority ?? 0); ?>
              </small>
            </div>
          </div>
          <div class="col-md-4 text-right">
            <div class="btn-group">
              <a href="<?php echo admin_url('lims/tests'); ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?>
              </a>
              <a href="<?php echo admin_url('lims/orders/view/'.(int)$order->id); ?>" class="btn btn-default" target="_blank">
                <i class="fa fa-folder-open"></i> <?php echo _l('lims_open_order') ?: 'Open Order'; ?>
              </a>
              <a class="btn btn-default" href="<?php echo admin_url('lims/orders/report_pdf/' . (int)$order->id); ?>" target="_blank">
                <i class="fa fa-file-pdf"></i> <?php echo _l('lims_report_pdf_button'); ?>
              </a>
              <?php if (has_permission('lims', '', 'enter_results')): ?>
                <?php if (!empty($canSign)): ?>
                  <a href="<?php echo admin_url('lims/tests/sign_order/' . (int)$order->id); ?>"
                     class="btn btn-success"
                     onclick="return confirm('<?php echo _l('lims_sign_confirm'); ?>');">
                    <i class="fa fa-signature"></i> <?php echo _l('lims_sign_report'); ?>
                  </a>
                <?php else: ?>
                  <button type="button"
                          class="btn btn-success disabled"
                          title="<?php echo _l('lims_sign_not_ready_hint'); ?>">
                    <i class="fa fa-signature"></i> <?php echo _l('lims_sign_report'); ?>
                  </button>
                <?php endif; ?>
              <?php endif; ?>
              <a href="<?php echo admin_url('lims/orders/print_sample_labels/'.(int)$order->id.'?print=1'); ?>" target="_blank" class="btn btn-default">
                <i class="fa fa-tags"></i> <?php echo _l('lims_pdf_sample_labels') ?: 'Sample Labels'; ?>
              </a>
            </div>
          </div>

        </div>

        <hr class="mbot10" />

        <!-- Subject + Notes + Barcode -->
        <div class="row">
          <div class="col-md-4">
            <h5 class="mbot10"><?php echo _l('lims_subject') ?: _l('client'); ?></h5>

            <?php if (!empty($order->subject_id)): ?>
              <?php
                // Κύριο όνομα subject
                $subjectLabelParts = [];

                if (!empty($order->subject_name)) {
                    $subjectLabelParts[] = $order->subject_name;
                } else {
                    $nameBits = [];
                    if (!empty($order->first_name)) $nameBits[] = $order->first_name;
                    if (!empty($order->last_name))  $nameBits[] = $order->last_name;
                    if ($nameBits) {
                        $subjectLabelParts[] = implode(' ', $nameBits);
                    }
                }

                if (empty($subjectLabelParts)) {
                    $subjectLabelParts[] = 'Subject #' . (int)$order->subject_id;
                }

                $subjectMain = implode(' — ', $subjectLabelParts);

                // Extra meta (ID/passport, internal code, email, phone)
                $subjectMeta = [];
                if (!empty($order->id_number)) {
                    $subjectMeta[] = ((_l('lims_subject_id_number') ?: 'ID') . ': ' . $order->id_number);
                }
                if (!empty($order->internal_code)) {
                    $subjectMeta[] = ((_l('lims_subject_internal_code') ?: 'Code') . ': ' . $order->internal_code);
                }
                if (!empty($order->email)) {
                    $subjectMeta[] = $order->email;
                }
                if (!empty($order->phone)) {
                    $subjectMeta[] = $order->phone;
                }

                // Link προς subject
                $subjectUrl = admin_url('lims/subjects/view/' . (int)$order->subject_id);
              ?>

              <p class="no-mbot">
                <a href="<?php echo $subjectUrl; ?>">
                  <?php echo html_escape($subjectMain); ?>
                </a><br>
                <?php if (!empty($subjectMeta)): ?>
                  <small class="text-muted">
                    <?php echo html_escape(implode(' · ', $subjectMeta)); ?>
                  </small>
                <?php endif; ?>
              </p>
            <?php else: ?>
              <p class="text-muted">—</p>
            <?php endif; ?>
          </div>

          <div class="col-md-4">
            <h5 class="mbot10"><?php echo _l('notes'); ?></h5>
            <p class="no-mbot">
              <?php echo !empty($order->notes)
                ? nl2br(html_escape($order->notes))
                : '<span class="text-muted">—</span>'; ?>
            </p>
          </div>

          <div class="col-md-4">
            <h5 class="mbot10"><?php echo _l('barcode'); ?></h5>
            <?php if (empty($order->order_barcode)): ?>
              <span class="text-muted">—</span>
            <?php else: ?>
              <div>
                <svg id="order-barcode-svg"></svg>
                <div class="mtop10">
                  <code id="order-barcode-text" style="display:none;"><?php echo html_escape($order->order_barcode); ?></code>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <hr/>

        <?php
          $testsBySample          = isset($testsBySample) ? $testsBySample : [];
          $resultsByTest          = isset($resultsByTest) ? $resultsByTest : [];
          $culturesBySample       = isset($culturesBySample) ? $culturesBySample : [];
          $cultureResultsByKey    = isset($cultureResultsByKey) ? $cultureResultsByKey : [];
          $cultureSelectionsByKey = isset($cultureSelectionsByKey) ? $cultureSelectionsByKey : [];
        ?>

        <?php
          // Report Notes (Order-level)
          $reportNotes      = isset($reportNotes) ? $reportNotes : [];
          $orderReportNotes = isset($orderReportNotes) && is_array($orderReportNotes) ? $orderReportNotes : ['free_text' => '', 'note_ids' => []];
          $reportFreeText   = isset($orderReportNotes['free_text']) ? (string)$orderReportNotes['free_text'] : '';
          $reportNoteIds    = isset($orderReportNotes['note_ids']) && is_array($orderReportNotes['note_ids']) ? $orderReportNotes['note_ids'] : [];
        ?>

        <!-- =================== -->
        <!-- LABORATORY TESTS    -->
        <!-- =================== -->
        <h4 class="mbot15"><?php echo _l('lims_tests'); ?></h4>

        <?php echo form_open(admin_url('lims/tests/save_results/' . (int)$order->id)); ?>

        <div class="table-responsive">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th><?php echo _l('lims_test_field_sample'); ?></th>
                <th><?php echo _l('lims_test_table_col_test'); ?></th>
                <th><?php echo _l('lims_test_table_col_department'); ?></th>
                <th><?php echo _l('lims_test_result_value'); ?></th>
                <th><?php echo _l('lims_test_result_unit'); ?></th>
                <th><?php echo _l('lims_test_flag'); ?></th>
                <th><?php echo _l('lims_test_measured_at'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($samples)): ?>
                <tr><td colspan="7" class="text-muted text-center">—</td></tr>
              <?php else: ?>
                <?php foreach ($samples as $sample): ?>
                  <?php
                    $sampleTests = isset($testsBySample[$sample->id]) ? $testsBySample[$sample->id] : [];
                    if (empty($sampleTests)) {
                        continue;
                    }
                  ?>
                  <!-- Sample header row -->
                  <tr class="info">
                    <td colspan="7">
                      <strong><?php echo _l('lims_test_field_sample'); ?>:</strong>
                      <?php echo html_escape($sample->sample_uid); ?>
                      <?php if (!empty($sample->sample_type_name)): ?>
                        <span class="text-muted mleft5">
                          (<?php echo html_escape($sample->sample_type_name); ?>)
                        </span>
                      <?php endif; ?>
                      <?php if (!empty($sample->barcode)): ?>
                        <span class="label label-default mleft5">
                          <i class="fa fa-barcode"></i> <?php echo html_escape($sample->barcode); ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>

                  <?php foreach ($sampleTests as $t): ?>
                    <?php
                      $results   = isset($resultsByTest[$t->id]) ? $resultsByTest[$t->id] : [];
                      $last      = !empty($results) ? $results[0] : null;
                      $value     = '';
                      $unit      = '';
                      $flag      = '';
                      $measured  = '';

                      if ($last) {
                          if ($t->result_type === 'numeric' && $last->value_numeric !== null) {
                              $value = (float)$last->value_numeric;
                          } elseif (!empty($last->value_text)) {
                              $value = $last->value_text;
                          }
                          $unit = $last->unit ?: $t->units_ucum;
                          $flag = $last->flag ?: '';
                          if (!empty($last->measured_at)) {
                              $measured = _dt($last->measured_at);
                          }
                      } else {
                          $unit = $t->units_ucum;
                      }

                      // step για numeric
                      $step = 'any';
                      if ($t->result_type === 'numeric' && $t->decimal_places !== null) {
                          $dp = (int)$t->decimal_places;
                          if ($dp > 0) {
                              $step = '0.' . str_repeat('0', max(0, $dp - 1)) . '1';
                          }
                      }
                    ?>
                    <tr>
                      <td><?php echo html_escape($sample->sample_uid); ?></td>
                      <td>
                        <?php echo html_escape($t->analysis_name); ?>
                        <?php if (!empty($t->analysis_code)): ?>
                          <small class="text-muted">(<?php echo html_escape($t->analysis_code); ?>)</small>
                        <?php endif; ?>
                      </td>
                      <td><?php echo html_escape($t->department_name); ?></td>
                      <td>
                        <?php if ($t->result_type === 'numeric'): ?>
                          <input type="number"
                                 class="form-control input-sm"
                                 name="result_value[<?php echo (int)$t->id; ?>]"
                                 step="<?php echo $step; ?>"
                                 value="<?php echo html_escape($value); ?>">
                        <?php elseif ($t->result_type === 'text'): ?>
                          <input type="text"
                                 class="form-control input-sm"
                                 name="result_value[<?php echo (int)$t->id; ?>]"
                                 value="<?php echo html_escape($value); ?>">
                        <?php else: // select - προς το παρόν ελεύθερο text ?>
                          <input type="text"
                                 class="form-control input-sm"
                                 name="result_value[<?php echo (int)$t->id; ?>]"
                                 value="<?php echo html_escape($value); ?>"
                                 placeholder="<?php echo _l('lims_result_select_placeholder') ?: 'Type or select...'; ?>">
                        <?php endif; ?>
                      </td>
                      <td>
                        <input type="text"
                               class="form-control input-sm"
                               name="result_unit[<?php echo (int)$t->id; ?>]"
                               value="<?php echo html_escape($unit); ?>">
                      </td>
                      <td>
                        <select class="form-control input-sm"
                                name="result_flag[<?php echo (int)$t->id; ?>]">
                          <option value=""></option>
                          <?php foreach (['L','H','LL','HH','A'] as $fl): ?>
                            <option value="<?php echo $fl; ?>" <?php echo ($flag === $fl ? 'selected' : ''); ?>>
                              <?php echo $fl; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td>
                        <input type="text"
                               class="form-control input-sm datetimepicker"
                               name="result_measured_at[<?php echo (int)$t->id; ?>]"
                               value="<?php echo html_escape($measured); ?>">
                      </td>
                    </tr>
                  <?php endforeach; ?>

                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- =================== -->
        <!-- CULTURES SECTION    -->
        <!-- =================== -->
        <hr class="mtop20" />
        <h4 class="mbot15"><?php echo _l('lims_cultures'); ?></h4>

        <div class="table-responsive">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th><?php echo _l('lims_test_field_sample'); ?></th>
                <th><?php echo _l('lims_culture'); ?></th>
                <th><?php echo _l('lims_culture_type'); ?></th>
                <th><?php echo _l('lims_culture_options'); ?></th>
                <th><?php echo _l('lims_culture_comment'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $hasCultures = false;
              if (!empty($samples)):
                foreach ($samples as $sample):
                  $sampleCultures = isset($culturesBySample[$sample->id]) ? $culturesBySample[$sample->id] : [];
                  if (empty($sampleCultures)) {
                    continue;
                  }
                  $hasCultures = true;
                  ?>
                  <!-- Sample header row for cultures -->
                  <tr class="info">
                    <td colspan="5">
                      <strong><?php echo _l('lims_test_field_sample'); ?>:</strong>
                      <?php echo html_escape($sample->sample_uid); ?>
                      <?php if (!empty($sample->sample_type_name)): ?>
                        <span class="text-muted mleft5">
                          (<?php echo html_escape($sample->sample_type_name); ?>)
                        </span>
                      <?php endif; ?>
                      <?php if (!empty($sample->barcode)): ?>
                        <span class="label label-default mleft5">
                          <i class="fa fa-barcode"></i> <?php echo html_escape($sample->barcode); ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>

                  <?php foreach ($sampleCultures as $cu): ?>
                    <?php
                      $key  = (int)$sample->id . ':' . (int)$cu->culture_id;
                      $cres = isset($cultureResultsByKey[$key]) ? $cultureResultsByKey[$key] : null;
                      $comment = $cres && !empty($cres->comment) ? $cres->comment : '';

                      // Επιλεγμένες τιμές για κάθε option set του συγκεκριμένου sample+culture
                      $selectedForThis = isset($cultureSelectionsByKey[$key])
                        ? $cultureSelectionsByKey[$key]
                        : [];
                    ?>
                    <tr>
                      <td><?php echo html_escape($sample->sample_uid); ?></td>
                      <td>
                        <?php echo html_escape($cu->culture_name); ?>
                        <?php if (!empty($cu->culture_code)): ?>
                          <small class="text-muted">(<?php echo html_escape($cu->culture_code); ?>)</small>
                        <?php endif; ?>
                      </td>
                      <td><?php echo html_escape($cu->culture_type_name); ?></td>
                      <td>
                        <?php if (!empty($cu->option_sets)): ?>
                          <?php foreach ($cu->option_sets as $set): ?>
                            <?php
                              $setId   = (int)$set['set_id'];
                              $setName = $set['set_name'];
                              $values  = isset($set['values']) ? $set['values'] : [];
                              $selVal  = isset($selectedForThis[$setId]) ? (int)$selectedForThis[$setId] : 0;
                            ?>
                            <div class="mbot5">
                              <label class="control-label block small">
                                <?php echo html_escape($setName); ?>
                              </label>
                              <select class="form-control input-sm"
                                      name="culture_option[<?php echo (int)$sample->id; ?>][<?php echo (int)$cu->culture_id; ?>][<?php echo $setId; ?>]">
                                <option value=""></option>
                                <?php foreach ($values as $val): ?>
                                  <?php $vid = (int)$val['id']; ?>
                                  <option value="<?php echo $vid; ?>" <?php echo ($selVal === $vid ? 'selected' : ''); ?>>
                                    <?php echo html_escape($val['label']); ?>
                                  </option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <span class="text-muted">—</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <textarea
                          class="form-control input-sm"
                          name="culture_comment[<?php echo (int)$sample->id; ?>][<?php echo (int)$cu->culture_id; ?>]"
                          rows="2"
                        ><?php echo html_escape($comment); ?></textarea>
                      </td>
                    </tr>
                  <?php endforeach; ?>

                <?php
                endforeach;
              endif;

              if (!$hasCultures): ?>
                <tr><td colspan="5" class="text-muted text-center">—</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <hr />

        <!-- =================== -->
        <!-- REPORT NOTES        -->
        <!-- =================== -->
        <h4 class="mbot15"><?php echo _l('lims_report_notes') ?: 'Report Notes'; ?></h4>

        <div class="row">

          <div class="col-md-12">
            <label for="report_note_ids"><?php echo _l('lims_report_notes_select') ?: 'Select predefined notes'; ?></label>
            <select
              id="report_note_ids"
              name="report_note_ids[]"
              class="selectpicker"
              multiple
              data-live-search="true"
              data-width="100%"
              data-actions-box="true"
            >
              <?php if (!empty($reportNotes)): ?>
                <?php foreach ($reportNotes as $rn): ?>
                  <?php
                    $nid   = (int)($rn->id ?? 0);
                    $title = (string)($rn->title ?? '');
                    if ($nid <= 0) {
                      continue;
                    }
					$code = trim((string)($rn->code ?? ''));
					$label = ($code !== '') ? $code : ('#' . $rid);
                    $selected = in_array($nid, $reportNoteIds, true) ? 'selected' : '';
                  ?>
                  <option value="<?php echo $nid; ?>" <?php echo $selected; ?>>
                    <?php echo $label; ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>

            <p class="text-muted mtop5">
              <small><?php echo _l('lims_report_notes_select_help') ?: 'Selected notes will appear in the Report PDF under the Notes section (Σημ.).'; ?></small>
            </p>
          </div>
          <div class="col-md-12">
            <label for="report_notes_text"><?php echo _l('lims_report_notes_free_text') ?: 'Free text (prints under results)'; ?></label>
            <textarea
              id="report_notes_text"
              name="report_notes_text"
              class="form-control"
              rows="4"
            ><?php echo html_escape($reportFreeText); ?></textarea>
            <p class="text-muted mtop5">
              <small><?php echo _l('lims_report_notes_free_text_help') ?: 'This text will appear under the results section of the Report PDF.'; ?></small>
            </p>
          </div>
        </div>


        <div class="text-right mtop10">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> <?php echo _l('save'); ?>
          </button>
        </div>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<!-- JsBarcode για τον γραμμωτό κώδικα -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
  (function($){
    $(function(){

      if (window.app && app.init_datetimepicker) {
        app.init_datetimepicker();
      }

      if (typeof init_selectpicker === 'function') {
        init_selectpicker();
      }

      // Γραμμωτός κώδικας παραγγελίας
      var $codeEl = $('#order-barcode-text');
      if ($codeEl.length && window.JsBarcode && document.getElementById('order-barcode-svg')) {
        var code = $codeEl.text().trim();
        if (code) {
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
      }
    });
  })(jQuery);
</script>
