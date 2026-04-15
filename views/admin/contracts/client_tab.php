<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
// Fallbacks όταν το view φορτώνεται απευθείας από το core client.php
$CI = &get_instance();

if (!isset($client_id)) {
    // Στο client profile υπάρχει συνήθως $client object
    if (isset($client) && isset($client->userid)) {
        $client_id = (int)$client->userid;
    } else {
        $client_id = (int)$CI->input->get('userid'); // εναλλακτικά από το URL, αν υπάρχει
    }
}

if (!isset($contracts)) {
    $CI->load->model('lims/Lims_contracts_model', 'lims_contracts_model');
    $contracts = $CI->lims_contracts_model->all($client_id);
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="mbot15">
      <?php if(has_permission('lims','','billing')): ?>
      <button class="btn btn-primary" data-toggle="modal" data-target="#limsNewContractModal">
        <i class="fa fa-plus"></i> <?php echo _l('lims_contract_create'); ?>
      </button>
      <?php endif; ?>
    </div>

       <hr class="hr-panel-heading"/>

        <?php if(empty($contracts)): ?>
          <p class="text-muted"><?php echo _l('no_results_found'); ?></p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table dt-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th><?php echo _l('lims_contract_name'); ?></th>
                  <th><?php echo _l('client'); ?></th>
                  <th><?php echo _l('lims_contract_discount_percent'); ?></th>
                  <th><?php echo _l('lims_contract_valid_from'); ?></th>
                  <th><?php echo _l('lims_contract_valid_to'); ?></th>
                  <th><?php echo _l('lims_contract_active'); ?></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($contracts as $c): ?>
                  <tr>
                    <td><?php echo (int)$c->id; ?></td>
                    <td><?php echo html_escape($c->name); ?></td>
                    <td>#<?php echo (int)$c->client_id; ?></td>
                    <td><?php echo $c->discount_percent !== null ? floatVal($c->discount_percent).' %' : '-'; ?></td>
                    <td><?php echo $c->valid_from ?: '-'; ?></td>
                    <td><?php echo $c->valid_to ?: '-'; ?></td>
                    <td>
					  <div class="onoffswitch">
						<input type="checkbox"
							   class="onoffswitch-checkbox lims-status-toggle"
							   id="contract_active_<?php echo (int)$c->id; ?>"
							   data-id="<?php echo (int)$c->id; ?>"
							   <?php echo (int)$c->active === 1 ? 'checked' : ''; ?>
							   <?php echo has_permission('lims','','billing') ? '' : 'disabled'; ?>>
						<label class="onoffswitch-label" for="contract_active_<?php echo (int)$c->id; ?>"></label>
					  </div>
					</td>

					<td class="text-right">
						<a href="<?php echo admin_url('lims/contracts/create/'.$c->id.'?return_to=client_tab&client_id='.(int)$client_id); ?>" class="btn btn-default btn-sm">
							<i class="fa fa-pencil"></i>
						</a>
						<a href="<?php echo admin_url('lims/delete_contract/'.$c->id.'?return_to=client_tab&client_id='.(int)$client_id); ?>" class="btn btn-danger btn-sm _delete">
							<i class="fa fa-trash"></i>
						</a>
					</td>

                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

  </div>
</div>

<!-- Modal: Quick Create Contract for this client -->
<div class="modal fade" id="limsNewContractModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('lims/save_contract'), ['id'=>'limsQuickContractForm']); ?>
	<input type="hidden" name="client_id" value="<?php echo (int)$client_id; ?>">
	<input type="hidden" name="return_to" value="client_tab">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><?php echo _l('lims_contract_create'); ?></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="client_id" value="<?php echo (int)$client_id; ?>">

        <div class="form-group">
          <label><?php echo _l('lims_contract_name'); ?></label>
          <input type="text" class="form-control" name="name" required>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Code</label>
              <input type="text" class="form-control" name="code">
            </div>
          </div>
          <div class="col-md-6">
            <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
            <div class="onoffswitch">
              <input type="checkbox" name="active" class="onoffswitch-checkbox" id="lims_quick_active" checked>
              <label class="onoffswitch-label" for="lims_quick_active"></label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label><?php echo _l('lims_contract_discount_percent'); ?> (<?php echo _l('optional'); ?>)</label>
          <input type="number" step="0.01" class="form-control" name="discount_percent">
        </div>

        <div class="row">
          <div class="col-md-6">
            <label><?php echo _l('lims_contract_valid_from'); ?> (<?php echo _l('optional'); ?>)</label>
            <input type="date" class="form-control" name="valid_from">
          </div>
          <div class="col-md-6">
            <label><?php echo _l('lims_contract_valid_to'); ?> (<?php echo _l('optional'); ?>)</label>
            <input type="date" class="form-control" name="valid_to">
          </div>
        </div>

        <p class="text-muted mtop10">
          <?php echo _l('note'); ?>: <?php echo _l('lims_contract_prices_optional'); // βάλε στο lang "Item prices can be added later." ?>
        </p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" href="<?php echo admin_url('lims/contracts/create?client_id='.(int)$client_id.'&return_to=client_tab'); ?>">
		  <?php echo _l('advanced_settings'); ?>
		</a>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
(function(){
  // μετά από quick-create, μείνε στην καρτέλα του πελάτη
  document.getElementById('limsQuickContractForm')?.addEventListener('submit', function(){
    // αφήνουμε το submit να πάει στο controller (save_contract) – όλα τα πεδία items/prices είναι προαιρετικά
  });
})();
</script>

<?php init_tail(); ?>
<script>
(function($){
  $(document).on('change', '.lims-status-toggle', function(){
    var $cb = $(this);
    var id = $cb.data('id');
    var val = $cb.is(':checked') ? 1 : 0;

    $cb.prop('disabled', true);

    $.post(admin_url + 'lims/toggle_contract_status', { id:id, active:val })
      .done(function(resp){
        try { resp = JSON.parse(resp); } catch(e){}
        if (!resp || resp.success !== true) {
          // revert on error
          $cb.prop('checked', !val);
          if (window.alert_float) alert_float('danger', '<?php echo _l('lims_error_generic'); ?>');
          else alert('<?php echo _l('lims_error_generic'); ?>');
        } else {
          if (window.alert_float) {
            alert_float('success', val ? '<?php echo _l('settings_yes'); ?>' : '<?php echo _l('settings_no'); ?>');
          }
        }
      })
      .fail(function(){
        $cb.prop('checked', !val);
        if (window.alert_float) alert_float('danger', '<?php echo _l('lims_error_generic'); ?>');
        else alert('<?php echo _l('lims_error_generic'); ?>');
      })
      .always(function(){
        $cb.prop('disabled', false);
      });
  });
})(jQuery);
</script>
