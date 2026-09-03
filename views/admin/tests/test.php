<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">

            <!-- HEADER -->
            <div class="row">
              <div class="col-md-8">
                <h4 class="no-margin">
                  <?php echo _l('lims_test'); ?> #<?php echo (int) $test->id; ?>

                  <?php if (!empty($test->analysis_name)) { ?>
                    <small class="text-muted">
                      &mdash; <?php echo html_escape($test->analysis_name); ?>
                      <?php if (!empty($test->analysis_code)) { ?>
                        (<?php echo html_escape($test->analysis_code); ?>)
                      <?php } ?>
                    </small>
                  <?php } ?>
                </h4>

                <?php if (!empty($test->sample_uid)) { ?>
                  <p class="text-muted mtop5">
                    <?php echo _l('lims_test_field_sample'); ?>:
                    <strong><?php echo html_escape($test->sample_uid); ?></strong>
                    <?php if (!empty($test->sample_type_name)) { ?>
                      (<?php echo html_escape($test->sample_type_name); ?>)
                    <?php } ?>
                    <?php if (!empty($test->sample_barcode)) { ?>
                      &mdash; <?php echo _l('lims_sample_barcode'); ?>:
                      <?php echo html_escape($test->sample_barcode); ?>
                    <?php } ?>
                  </p>
                <?php } ?>
              </div>

              <div class="col-md-4 text-right">
                <?php
                  $statusLabel = !empty($test->status_name) ? $test->status_name : ucfirst($test->status);
                  $statusColor = !empty($test->status_color) ? $test->status_color : '#777';
                ?>
                <span class="label" style="background:<?php echo html_escape($statusColor); ?>;">
                  <?php echo html_escape($statusLabel); ?>
                </span>
              </div>
            </div>

            <hr class="hr-panel-heading" />

            <!-- TABS -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#tab_meta" aria-controls="tab_meta" role="tab" data-toggle="tab">
                  <?php echo _l('lims_tab_meta'); ?>
                </a>
              </li>
              <li role="presentation">
                <a href="#tab_results" aria-controls="tab_results" role="tab" data-toggle="tab">
                  <?php echo _l('lims_tab_result_entry'); ?>
                </a>
              </li>
              <li role="presentation">
                <a href="#tab_attachments" aria-controls="tab_attachments" role="tab" data-toggle="tab">
                  <?php echo _l('lims_tab_attachments'); ?>
                </a>
              </li>
              <li role="presentation">
                <a href="#tab_audit" aria-controls="tab_audit" role="tab" data-toggle="tab">
                  <?php echo _l('lims_tab_audit_trail'); ?>
                </a>
              </li>
            </ul>

            <div class="tab-content mtop20">

              <!-- META TAB -->
              <div role="tabpanel" class="tab-pane active" id="tab_meta">
                <div class="row">
                  <div class="col-md-6">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td><?php echo _l('lims_test_field_id'); ?></td>
                          <td>#<?php echo (int) $test->id; ?></td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_analysis'); ?></td>
                          <td>
                            <?php echo html_escape($test->analysis_name); ?>
                            <?php if (!empty($test->analysis_code)) { ?>
                              (<?php echo html_escape($test->analysis_code); ?>)
                            <?php } ?>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_sample'); ?></td>
                          <td>
                            <?php if (!empty($test->sample_uid)) { ?>
                              <?php echo html_escape($test->sample_uid); ?>
                            <?php } ?>
                            <?php if (!empty($test->sample_type_name)) { ?>
                              (<?php echo html_escape($test->sample_type_name); ?>)
                            <?php } ?>
                            <?php if (!empty($test->sample_barcode)) { ?>
                              <br/>
                              <small class="text-muted">
                                <?php echo _l('lims_sample_barcode'); ?>:
                                <?php echo html_escape($test->sample_barcode); ?>
                              </small>
                            <?php } ?>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_department'); ?></td>
                          <td><?php echo !empty($test->department_name) ? html_escape($test->department_name) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_assigned_to'); ?></td>
                          <td>
                            <?php
                              if (!empty($test->assigned_staff)) {
                                  echo get_staff_full_name($test->assigned_staff);
                              } else {
                                  echo '-';
                              }
                            ?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="col-md-6">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <td><?php echo _l('lims_test_field_order_barcode'); ?></td>
                          <td>
                            <?php
                              if (!empty($test->order_barcode)) {
                                  echo html_escape($test->order_barcode);
                              } else {
                                  echo '-';
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_status'); ?></td>
                          <td>
                            <span class="label" style="background:<?php echo html_escape($statusColor); ?>;">
                              <?php echo html_escape($statusLabel); ?>
                            </span>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_sample_collected_at'); ?></td>
                          <td><?php echo $test->sample_collected_at ? _dt($test->sample_collected_at) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_sample_received_at'); ?></td>
                          <td><?php echo $test->sample_received_at ? _dt($test->sample_received_at) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_started_at'); ?></td>
                          <td><?php echo $test->started_at ? _dt($test->started_at) : '-'; ?></td>
                        </tr>
                        <tr>
                          <td><?php echo _l('lims_test_field_completed_at'); ?></td>
                          <td><?php echo $test->completed_at ? _dt($test->completed_at) : '-'; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- RESULT ENTRY TAB -->
              <div role="tabpanel" class="tab-pane" id="tab_results">
                <?php echo form_open(admin_url('lims/tests/save_result/' . $test->id), ['id' => 'lims-test-result-form']); ?>
                <input type="hidden" name="result_id" value="<?php echo isset($test->result_id) ? (int)$test->result_id : ''; ?>">

                <div class="row">
                  <div class="col-md-4">
                    <?php
                      $resultType = !empty($test->result_type) ? $test->result_type : 'numeric';

                      if ($resultType === 'numeric') {
                          $step = 'any';
                          if (!empty($test->decimal_places)) {
                              $step = '0.' . str_repeat('0', (int)$test->decimal_places - 1) . '1';
                          }

                          echo render_input(
                              'value_numeric',
                              'lims_test_result_value',
                              isset($test->value_numeric) ? $test->value_numeric : '',
                              'number',
                              ['step' => $step]
                          );
                      } elseif ($resultType === 'text') {
                          echo render_input(
                              'value_text',
                              'lims_test_result_value',
                              isset($test->value_text) ? $test->value_text : ''
                          );
                      } else {
                          echo render_input(
                              'value_text',
                              'lims_test_result_value',
                              isset($test->value_text) ? $test->value_text : ''
                          );
                      }
                    ?>
                  </div>

                  <div class="col-md-3">
                    <?php
                      $unitValue = '';
                      if (!empty($test->result_unit)) {
                          $unitValue = $test->result_unit;
                      } elseif (!empty($test->units_ucum)) {
                          $unitValue = $test->units_ucum;
                      }
                      echo render_input('unit', 'lims_test_result_unit', $unitValue);
                    ?>
                  </div>

                  <div class="col-md-3">
                    <?php
                      $flags = [
                          ['id' => '',   'name' => '-'],
                          ['id' => 'L',  'name' => _l('lims_test_flag_low')],
                          ['id' => 'H',  'name' => _l('lims_test_flag_high')],
                          ['id' => 'LL', 'name' => _l('lims_test_flag_very_low')],
                          ['id' => 'HH', 'name' => _l('lims_test_flag_very_high')],
                          ['id' => 'A',  'name' => _l('lims_test_flag_abnormal')],
                      ];
                      echo render_select(
                          'flag',
                          $flags,
                          ['id','name'],
                          'lims_test_flag',
                          isset($test->result_flag) ? $test->result_flag : ''
                      );
                    ?>
                  </div>

                  <div class="col-md-2">
                    <?php
                      echo render_input(
                          'measured_at',
                          'lims_test_measured_at',
                          isset($test->result_measured_at) ? $test->result_measured_at : ''
                      );
                    ?>
                  </div>
                </div>

                <div class="row mtop20">
                  <div class="col-md-12 text-right">
                    <button type="submit" name="action" value="save_draft" class="btn btn-default">
                      <?php echo _l('lims_btn_save_draft'); ?>
                    </button>
                    <button type="submit" name="action" value="complete" class="btn btn-info">
                      <?php echo _l('lims_btn_mark_completed'); ?>
                    </button>
                  </div>
                </div>

                <?php echo form_close(); ?>
              </div>

              <!-- ATTACHMENTS TAB -->
              <div role="tabpanel" class="tab-pane" id="tab_attachments">
                <?php echo form_open_multipart(admin_url('lims/tests/upload_attachment/' . $test->id)); ?>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="file"><?php echo _l('lims_test_add_attachment'); ?></label>
                        <input type="file" name="file" class="form-control" />
                      </div>
                    </div>
                    <div class="col-md-6 mtop25">
                      <button type="submit" class="btn btn-default">
                        <?php echo _l('upload'); ?>
                      </button>
                    </div>
                  </div>
                <?php echo form_close(); ?>

                <hr />

                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th><?php echo _l('lims_test_attachment_filename'); ?></th>
                      <th><?php echo _l('lims_test_attachment_added_by'); ?></th>
                      <th><?php echo _l('lims_test_attachment_date'); ?></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($attachments)) { ?>
                      <?php foreach ($attachments as $file) { ?>
                        <tr>
                          <td><?php echo html_escape($file->file_name); ?></td>
                          <td><?php echo get_staff_full_name($file->staffid); ?></td>
                          <td><?php echo _dt($file->dateadded); ?></td>
                          <td>
                            <a href="<?php echo site_url('download/file/lims_test/' . $file->id); ?>" class="btn btn-default btn-icon">
                              <i class="fa fa-download"></i>
                            </a>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php } else { ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted">
                          <?php echo _l('no_attachments_found'); ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>

              <!-- AUDIT TAB -->
              <div role="tabpanel" class="tab-pane" id="tab_audit">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th><?php echo _l('lims_audit_timestamp'); ?></th>
                      <th><?php echo _l('lims_audit_user'); ?></th>
                      <th><?php echo _l('lims_audit_action'); ?></th>
                      <th><?php echo _l('lims_audit_old_status'); ?></th>
                      <th><?php echo _l('lims_audit_new_status'); ?></th>
                      <th><?php echo _l('lims_audit_reason'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($audit)) { ?>
                      <?php foreach ($audit as $entry) { ?>
                        <tr>
                          <td><?php echo _dt($entry->created_at); ?></td>
                          <td><?php echo $entry->staff_id ? get_staff_full_name($entry->staff_id) : '-'; ?></td>
                          <td><?php echo html_escape($entry->action); ?></td>
                          <td><?php echo html_escape($entry->old_status); ?></td>
                          <td><?php echo html_escape($entry->new_status); ?></td>
                          <td><?php echo html_escape($entry->reason); ?></td>
                        </tr>
                      <?php } ?>
                    <?php } else { ?>
                      <tr>
                        <td colspan="6" class="text-center text-muted">
                          <?php echo _l('no_activities_found'); ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>

            </div><!-- /.tab-content -->

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  // JS hooks εδώ αν χρειαστούν αργότερα
</script>
