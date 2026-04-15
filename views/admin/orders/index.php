<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$CI =& get_instance();
$CI->db->order_by('position','ASC');
$lims_status_rows = $CI->db->get(db_prefix().'lims_test_statuses')->result();

// Map by code για εύκολη πρόσβαση
$lims_status_map = [];
foreach ($lims_status_rows as $st) {
    if (!empty($st->code)) {
        $lims_status_map[$st->code] = $st;
    }
}
?>

<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <div class="clearfix mbot15">
            <h4 class="pull-left"><?php echo _l('lims_orders'); ?></h4>
            <a href="<?php echo admin_url('lims/orders/create'); ?>" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
            </a>
        </div>

        <div class="clearfix mbot15">
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_subject'); ?></label>
              <input type="text" id="filter-subject" class="form-control" placeholder="<?php echo _l('search'); ?>">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('status'); ?></label>
              <select id="filter-status" class="form-control">
                <option value=""><?php echo _l('dropdown_non_selected_tex') ?: '—'; ?></option>
                <?php foreach ($lims_status_rows as $st): ?>
                  <?php if ((int)$st->active !== 1) continue; ?>
                  <option value="<?php echo html_escape($st->code); ?>">
                    <?php echo html_escape($st->name); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('date_created'); ?> (from)</label>
              <input type="date" id="filter-created-from" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('date_created'); ?> (to)</label>
              <input type="date" id="filter-created-to" class="form-control">
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table dt-table" id="lims-orders-table" data-order-col="5" data-order-type="desc">
            <thead>
                <tr>
                    <th><?php echo _l('lims_barcode'); ?></th>
                    <th><?php echo _l('lims_subject'); ?></th>
                    <th><?php echo _l('client'); ?></th>
                    <th><?php echo _l('priority'); ?></th>
                    <th><?php echo _l('due_date'); ?></th>
                    <th><?php echo _l('date_created'); ?></th>
                    <th><?php echo _l('status'); ?></th>
                    <th><?php echo _l('summary') ?: 'Summary'; ?></th>
                    <th><?php echo _l('options'); ?></th>
                </tr>
            </thead>

            <tbody>
            <?php if(!empty($rows)) foreach($rows as $r): ?>
              <tr
                data-created="<?php echo !empty($r->created_at) ? date('Y-m-d', strtotime($r->created_at)) : ''; ?>"
              >
                <td>
                  <a href="<?php echo admin_url('lims/orders/view/'.(int)$r->id); ?>">
                    <?php 
                        if(!empty($r->order_barcode)){
                            echo $r->order_barcode; 
                        }
                        else{
                            echo (int)$r->id; 
                        }
                    ?>
                  </a>
                </td>

                <!-- SUBJECT COLUMN -->
                <td>
                  <?php if (!empty($r->subject_id)): ?>
                    <?php
                      $subj = $this->db
                        ->where('id', (int)$r->subject_id)
                        ->get(db_prefix().'lims_subjects')
                        ->row();

                      if ($subj) {
                          $name = '';
                          if (!empty($subj->subject_name)) {
                              $name = $subj->subject_name;
                          } elseif (!empty($subj->first_name) || !empty($subj->last_name)) {
                              $name = trim(($subj->first_name ?? '').' '.($subj->last_name ?? ''));
                          } elseif (!empty($subj->name)) {
                              $name = $subj->name;
                          } else {
                              $name = 'Subject #'.(int)$r->subject_id;
                          }

                          $subname = html_escape($name).' (#'.(int)$r->subject_id.')';
                      } else {
                          $subname = 'Subject #'.(int)$r->subject_id;
                      }
                    ?>
                    <a href="<?php echo admin_url('lims/subjects/view/' . (int)$r->subject_id); ?>">
                      <?php echo $subname; ?>
                    </a>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>

                <td>
                  <?php if (!empty($r->client_id)): ?>
                    <?php
                      $c = $this->db->select('company')
                                    ->where('userid',(int)$r->client_id)
                                    ->get(db_prefix().'clients')->row();
                      $company = $c ? $c->company : ('ID ' . (int)$r->client_id);
                    ?>
                    <a href="<?php echo admin_url('clients/client/'.(int)$r->client_id); ?>">
                      <?php echo html_escape($company) . ' (#'.(int)$r->client_id.')'; ?>
                    </a>
                  <?php endif; ?>
                </td>

                <td><?php echo lims_priority_label($r->priority); ?></td>

                <td>
                  <?php echo !empty($r->due_at) ? _dt($r->due_at) : '—'; ?>
                </td>

                <td><?php echo !empty($r->created_at) ? _dt($r->created_at) : '—'; ?></td>

                <!-- STATUS COLUMN -->
                <td>
                  <?php
                    $status_code = $r->status ?? 'draft';
                    if (isset($lims_status_map[$status_code])) {
                        $st    = $lims_status_map[$status_code];
                        $sname = $st->name ?: ucfirst($status_code);
                        $color = trim($st->color ?? '');
                        if ($color !== '') {
                            echo '<span class="label" style="background:'
                                . html_escape($color)
                                . ';"><span class="hidden">'
                                . html_escape($status_code)
                                . '</span> '
                                . html_escape($sname)
                                . '</span>';
                        } else {
                            echo '<span class="label label-default"><span class="hidden">'
                                . html_escape($status_code)
                                . '</span> '
                                . html_escape($sname)
                                . '</span>';
                        }
                    } else {
                        // Fallback: άγνωστο status, δείξε το code
                        echo '<span class="label label-default">'
                            . html_escape($status_code)
                            . '</span>';
                    }
                  ?>
                </td>

                <!-- SUMMARY COLUMN -->
                <td>
                  <?php
                    $p = db_prefix();

                    // samples
                    $samples_cnt = (int)$this->db
                      ->where('order_id',(int)$r->id)
                      ->count_all_results("{$p}lims_samples");

                    // invoices (distinct invoice_id from lims_billing_links)
                    $invoices_cnt = (int)$this->db
                      ->select('invoice_id')
                      ->where('order_id',(int)$r->id)
                      ->group_by('invoice_id')
                      ->get("{$p}lims_billing_links")
                      ->num_rows();

                    // reports: check tests via samples (tbllims_tests.sample_id)
                    $has_report = false;
                    if ($this->db->table_exists("{$p}lims_tests")) {

                        // 1) μάζεψε sample ids γι’ αυτό το order
                        $sampleIds = $this->db
                            ->select('id')
                            ->from("{$p}lims_samples")
                            ->where('order_id', (int)$r->id)
                            ->get()->result_array();

                        $sampleIds = array_map('intval', array_column($sampleIds, 'id'));

                        if (!empty($sampleIds)) {
                            // 2) βρες αν υπάρχει έστω 1 test με τελικό status
                            $this->db->where_in('sample_id', $sampleIds);
                            $this->db->where_in('status', ['reported','approved','complete']);
                            $has_report = $this->db->limit(1)->get("{$p}lims_tests")->num_rows() > 0;
                        }
                    }

                    $parts = [];
                    if ($samples_cnt > 0) {
                      $parts[] = $samples_cnt.' '._l('lims_samples');
                    }
                    if ($invoices_cnt > 0) {
                      $parts[] = $invoices_cnt.' '._l('invoices');
                    }
                    if ($has_report) {
                      $parts[] = _l('lims_report_ready') ?: 'Report ready';
                    }

                    if (empty($parts)) {
                      echo '<span class="text-muted">—</span>';
                    } else {
                      echo '<small>'.html_escape(implode(' · ', $parts)).'</small>';
                    }
                  ?>
                </td>

                <td>
                  <a href="<?php echo admin_url('lims/orders/view/'.(int)$r->id); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-eye"></i>
                  </a>
                  <?php if (has_permission('lims','','manage_orders') || has_permission('lims','','admin')): ?>
                    <a href="<?php echo admin_url('lims/orders/delete/'.(int)$r->id); ?>"
                       class="btn btn-danger btn-sm _delete"
                       data-toggle="tooltip"
                       title="<?php echo _l('delete'); ?>">
                      <i class="fa fa-trash"></i>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  "use strict";

  $(function(){

    var $table = $('#lims-orders-table');
    var dt = $table.length && $.fn.DataTable ? $table.DataTable() : null;
    if (!dt) { return; }

    // Subject filter -> column 1
    $('#filter-subject').on('keyup change', function(){
      dt.column(1).search(this.value).draw();
    });

    // Status filter -> column 6 (το status column)
    $('#filter-status').on('change', function(){
      var val = $(this).val();
      if (!val) {
        dt.column(6).search('').draw();
      } else {
        // τα status codes είναι π.χ. draft, in_progress κ.λπ.
        // το κείμενο του label περιέχει και το code (hidden span), οπότε ταιριάζει κανονικά
        dt.column(6).search(val, true, false).draw();
      }
    });

    // Date range filter (με χρήση του data-created στο <tr>)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
      if (settings.nTable.id !== 'lims-orders-table') {
        return true;
      }

      var min = $('#filter-created-from').val();
      var max = $('#filter-created-to').val();
      if (!min && !max) {
        return true;
      }

      var created = $(dt.row(dataIndex).node()).data('created') || '';
      if (!created) {
        return false;
      }

      // created, min, max είναι σε μορφή YYYY-MM-DD, οπότε string-compare είναι αρκετό
      if (min && created < min) return false;
      if (max && created > max) return false;

      return true;
    });

    $('#filter-created-from, #filter-created-to').on('change', function(){
      dt.draw();
    });

  });
})(jQuery);
</script>
