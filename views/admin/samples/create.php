<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<?php
  // Safeguards για κενές μεταβλητές
  $row          = isset($row) ? $row : null;
  $orders       = isset($orders) ? $orders : [];
  $appointments = isset($appointments) ? $appointments : [];
  $clients      = isset($clients) ? $clients : [];
  $types        = isset($types) ? $types : [];
  $order_id     = isset($order_id) ? (int)$order_id : 0;
  $mode         = isset($mode) ? $mode : (($row && !empty($row->appointment_id)) ? 'appointment' : 'order');

  // Προ-αρχικοποίηση εμφάνισης sections
  $showOrderSec = ($mode === 'order');
  $showApptSec  = ($mode === 'appointment');
?>

<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo isset($row->id) ? _l('edit').' '._l('lims_sample').' #'.(int)$row->id : _l('lims_sample_add'); ?>
        </h4>

        <?php
          $action = admin_url('lims/samples/create'.(isset($row->id)?'/'.$row->id:''));
          $qs = [];
          if (!empty($order_id)) $qs[] = 'order_id='.(int)$order_id;
          if (!empty($_GET['return'])) $qs[] = 'return='.urlencode($_GET['return']);
          if ($qs) $action .= '?'.implode('&',$qs);
          echo form_open($action);
        ?>

        <!-- Link mode -->
        <div class="row">
          <div class="col-md-12">
            <label class="mright10"><?php echo _l('link_with') ?: 'Link with'; ?>:</label>
            <label class="radio-inline mright15">
              <input type="radio" name="link_mode" value="order" <?php echo $showOrderSec ? 'checked' : ''; ?>>
              <?php echo _l('order') ?: 'Order'; ?>
            </label>
            <label class="radio-inline">
              <input type="radio" name="link_mode" value="appointment" <?php echo $showApptSec ? 'checked' : ''; ?>>
              <?php echo _l('appointment') ?: 'Appointment'; ?>
            </label>
          </div>
        </div>

        <hr class="hr-panel-separator"/>

        <!-- SECTION: ORDER -->
        <div id="sec-order" style="<?php echo $showOrderSec ? '' : 'display:none;'; ?>">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('order'); ?></label>
                <select name="order_id" class="form-control selectpicker" data-live-search="true">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($orders)) foreach($orders as $o): ?>
                  <option value="<?php echo (int)$o->id; ?>"
                    <?php
                      $selOrder = $row->order_id ?? $order_id ?? 0;
                      echo ((int)$selOrder === (int)$o->id) ? 'selected' : '';
                    ?>>
                    #<?php echo (int)$o->id; ?> — <?php echo html_escape($o->order_barcode ?? ''); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <small class="text-muted">
                  <?php echo _l('lims_sample_order_hint') ?: 'Order ID that the sample belongs to.'; ?>
                </small>
              </div>
            </div>

            <div class="col-md-3">
              <div class="checkbox mtop25">
                <input type="checkbox" id="make-order" name="create_order" value="1">
                <label><?php echo _l('create_new_order') ?: 'Create new Order'; ?></label>
              </div>
            </div>
          </div>

          <div id="sec-order-fields" style="display:none;">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label><?php echo _l('client'); ?></label>
                  <select name="order_client_id" class="form-control selectpicker" data-live-search="true">
                    <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                    <?php if(!empty($clients)) foreach($clients as $c): ?>
                    <option value="<?php echo (int)$c->userid; ?>"><?php echo html_escape($c->company).' (#'.(int)$c->userid.')'; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label><?php echo _l('priority'); ?></label>
                  <input type="number" class="form-control" name="order_priority" value="0">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo _l('due_date'); ?></label>
                  <input type="date" class="form-control" name="order_due_at" value="">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo _l('notes'); ?></label>
                  <textarea class="form-control" name="order_notes" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION: APPOINTMENT -->
        <div id="sec-appointment" style="<?php echo $showApptSec ? '' : 'display:none;'; ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo _l('appointment'); ?></label>
                <select name="appointment_id" class="form-control selectpicker" data-live-search="true">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($appointments)) foreach($appointments as $a): ?>
                  <option value="<?php echo (int)$a->id; ?>"
                    <?php echo ((int)($row->appointment_id ?? 0) === (int)$a->id) ? 'selected' : ''; ?>>
                    #<?php echo (int)$a->id; ?> — <?php echo _dt($a->appointment_at); ?> — <?php echo html_escape($a->client_name ?? ''); ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <small class="text-muted">
                  <?php echo _l('choose_appointment') ?: 'Choose an appointment to link.'; ?>
                </small>
              </div>
            </div>

            <div class="col-md-4">
              <div class="checkbox mtop25">
                <label>
                  <input type="checkbox" id="make-order-appt" name="create_order" value="1">
                  <?php echo _l('create_order_from_appointment') ?: 'Create Order from Appointment'; ?>
                </label>
              </div>
            </div>
          </div>

          <div id="sec-appt-order-fields" style="display:none;">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label><?php echo _l('priority'); ?></label>
                  <input type="number" class="form-control" name="order_priority" value="0">
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo _l('due_date'); ?></label>
                  <input type="date" class="form-control" name="order_due_at" value="">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo _l('notes'); ?></label>
                  <textarea class="form-control" name="order_notes" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr class="hr-panel-separator"/>

        <!-- SAMPLE FIELDS -->
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_sample_uid'); ?></label>
              <input type="text" class="form-control" name="sample_uid"
                     value="<?php echo html_escape($row->sample_uid ?? ''); ?>"
                     placeholder="Auto if empty">
              <small class="text-muted">
                <?php echo _l('lims_sample_uid_hint') ?: 'Leave blank to auto-generate.'; ?>
              </small>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('barcode'); ?></label>
              <input type="text" class="form-control"
                     value="<?php echo html_escape($row->barcode ?? ''); ?>"
                     placeholder="<?php echo _l('auto_after_save') ?: 'Auto after save'; ?>" readonly>
              <small class="text-muted">
                <?php echo _l('lims_barcode_hint') ?: 'Order barcode will be used automatically.'; ?>
              </small>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_sample_type'); ?></label>
              <select name="sample_type_id" class="form-control selectpicker" data-live-search="true">
                <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                <?php if(!empty($types)) foreach($types as $t): ?>
                <option value="<?php echo (int)$t->id; ?>"
                  <?php echo (isset($row->sample_type_id) && (int)$row->sample_type_id===(int)$t->id)?'selected':''; ?>>
                  <?php echo html_escape($t->name); ?>
                </option>
                <?php endforeach; ?>
              </select>
              <small class="text-muted">
                <?php echo _l('lims_sample_type_hint') ?: 'Choose the specimen type.'; ?>
              </small>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('status'); ?></label>
              <select name="status" class="form-control selectpicker">
                <?php $stSel = $row->status ?? 'draft'; foreach(['draft','collected','received','rejected'] as $st): ?>
                <option value="<?php echo $st; ?>" <?php echo ($stSel===$st?'selected':''); ?>>
                  <?php echo ucfirst($st); ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_collected_at') ?: 'Collected At'; ?></label>
              <input type="datetime-local" class="form-control" name="collected_at"
                     value="<?php echo !empty($row->collected_at)?date('Y-m-d\TH:i', strtotime($row->collected_at)):''; ?>">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('lims_received_at') ?: 'Received At'; ?></label>
              <input type="datetime-local" class="form-control" name="received_at"
                     value="<?php echo !empty($row->received_at)?date('Y-m-d\TH:i', strtotime($row->received_at)):''; ?>">
            </div>
          </div>
          <div class="col-md-6">
            <label class="control-label"><?php echo _l('notes'); ?></label>
            <textarea class="form-control" name="notes" rows="2"><?php echo html_escape($row->notes ?? ''); ?></textarea>
          </div>
        </div>
		<div class="row">
          <div class="col-md-12">
				<div class="text-right">
				  <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
				  <?php if (!empty($order_id)): ?>
					<a href="<?php echo admin_url('lims/orders/view/'.(int)$order_id.'#samples'); ?>" class="btn btn-default"><?php echo _l('back'); ?></a>
				  <?php else: ?>
					<a href="<?php echo admin_url('lims/samples'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
				  <?php endif; ?>
				</div>
			</div>
		</div>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
(function($){
  // Αν έχεις ήδη δηλώσει τις συναρτήσεις στα δικά σου scripts, απλώς κάνε safe-calls εδώ.
  function _safe(fn){ try { if (typeof fn === 'function') fn(); } catch(e){} }
  $(function(){
    // Αρχικοποιήσεις selectpicker
    if ($.fn.selectpicker) { $('.selectpicker').selectpicker('refresh'); }

    // Εφόσον το πρόσθεσες στο scripts.php:
    _safe(window.toggleLinkMode);
    _safe(window.toggleMakeOrder);
    _safe(window.toggleMakeOrderFromAppt);

    // Σε περίπτωση που δεν υπάρχουν, κάνε ένα lightweight fallback
    if (typeof window.toggleLinkMode !== 'function') {
      $('input[name="link_mode"]').on('change', function(){
        var m = $(this).val();
        $('#sec-order').toggle(m==='order');
        $('#sec-appointment').toggle(m==='appointment');
      });
    }
    if (typeof window.toggleMakeOrder !== 'function') {
      $('#make-order').on('change', function(){
        $('#sec-order-fields').toggle($(this).is(':checked'));
      });
    }
    if (typeof window.toggleMakeOrderFromAppt !== 'function') {
      $('#make-order-appt').on('change', function(){
        $('#sec-appt-order-fields').toggle($(this).is(':checked'));
      });
    }
  });
})(jQuery);
</script>
