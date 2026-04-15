<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="clearfix">
          <h4 class="pull-left mtop5"><?php echo _l('lims_cultures'); ?></h4>
          <a href="<?php echo admin_url('lims/cultures/create'); ?>" class="btn btn-primary pull-right">
            <i class="fa-regular fa-plus"></i> <?php echo _l('new') ?: 'New'; ?>
          </a>
        </div>
        <hr/>

        <div class="table-responsive">
          <table class="table table-striped table-cultures">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('code'); ?></th>
                <th><?php echo _l('lims_sample_type'); ?></th>
                <th><?php echo _l('lims_culture_type') ?: 'Culture Type'; ?></th>
                <th><?php echo _l('lims_method'); ?></th>
                <th><?php echo _l('lims_tat_hours'); ?></th>
                <th><?php echo _l('lims_contract_active'); ?></th>
                <th class="text-right"><?php echo _l('lims_actions'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($rows)) foreach($rows as $r): ?>
                <tr>
                  <td><?php echo (int)$r->id; ?></td>
                  <td><?php echo html_escape($r->name); ?></td>
                  <td><?php echo html_escape($r->code); ?></td>
                  <td><?php echo html_escape($r->sample_type_name ?? ''); ?></td>
                  <td><?php echo html_escape($r->culture_type_name ?? ''); ?></td>
                  <td><?php echo html_escape($r->method); ?></td>
                  <td><?php echo html_escape($r->tat_hours); ?></td>
                  <td>
                    <div class="onoffswitch">
                      <input type="checkbox" class="onoffswitch-checkbox js-toggle"
                             data-id="<?php echo (int)$r->id; ?>"
                             id="ct_<?php echo (int)$r->id; ?>"
                             <?php echo ((int)$r->active===1)?'checked':''; ?>>
                      <label class="onoffswitch-label" for="ct_<?php echo (int)$r->id; ?>"></label>
                    </div>
                  </td>
                  <td class="text-right">
                    <a href="<?php echo admin_url('lims/cultures/create/'.$r->id); ?>" class="btn btn-default btn-sm">
                      <i class="fa-regular fa-pen-to-square"></i> <?php echo _l('lims_edit'); ?>
                    </a>
                    <a href="<?php echo admin_url('lims/cultures/delete/'.$r->id); ?>" class="btn btn-danger btn-sm _delete">
                      <i class="fa-regular fa-trash-can"></i> <?php echo _l('lims_delete'); ?>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if(empty($rows)): ?>
                <tr><td colspan="9" class="text-center text-muted"><?php echo _l('no_items_found') ?: 'No records.'; ?></td></tr>
              <?php endif; ?>
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
  $(function(){
    $(document).on('change','.js-toggle', function(){
      var id = $(this).data('id');
      var active = $(this).is(':checked') ? 1 : 0;
      $.post(admin_url + 'lims/cultures/toggle_status', {id:id, active:active});
    });
  });
})(jQuery);
</script>
