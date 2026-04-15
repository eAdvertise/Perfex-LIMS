<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-6"><h4><?php echo _l('lims_contracts'); ?></h4></div>
          <div class="col-md-6 text-right"><a href="<?php echo admin_url('lims/contracts/create'); ?>" class="btn btn-info"><?php echo _l('lims_contract_create'); ?></a></div>
        </div>
        <hr/>
        <table class="table table-striped">
          <thead><tr>
            <th>ID</th><th><?php echo _l('lims_contract_name'); ?></th><th><?php echo _l('lims_client'); ?></th>
            <th><?php echo _l('lims_contract_discount_percent'); ?></th><th><?php echo _l('lims_contract_priority'); ?></th>
            <th><?php echo _l('lims_status'); ?></th><th><?php echo _l('lims_actions'); ?></th>
          </tr></thead>
          <tbody>
          <?php if(!empty($contracts)): foreach($contracts as $c): ?>
            <tr>
              <td><?php echo (int)$c->id; ?></td>
              <td><?php echo html_escape($c->name); ?></td>
              <td><?php echo (int)$c->client_id; ?></td>
              <td><?php echo html_escape($c->discount_percent); ?></td>
              <td><?php echo (int)$c->priority; ?></td>
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
              <td>
                <a href="<?php echo admin_url('lims/contracts/create/'.$c->id); ?>" class="btn btn-default btn-sm"><?php echo _l('lims_edit'); ?></a>
                <a href="<?php echo admin_url('lims/contracts/delete/'.$c->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')"><?php echo _l('lims_delete'); ?></a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
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
