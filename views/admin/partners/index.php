<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="clearfix mbot15">
          <h4 class="pull-left mtop5"><?php echo _l('lims_partners'); ?></h4>
          <a href="<?php echo admin_url('lims/partners/create'); ?>" class="btn btn-primary pull-right">
            <i class="fa fa-plus"></i> <?php echo _l('new_partner'); ?>
          </a>
        </div>

        <div class="table-responsive">
          <table class="table dt-table">
            <thead>
              <tr>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('customer'); ?></th>
                <th><?php echo _l('email'); ?></th>
                <th><?php echo _l('phone'); ?></th>
                <th><?php echo _l('active'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($rows)) foreach($rows as $r): ?>
              <tr>
                <td><?php echo html_escape($r->name); ?></td>
                <td>
                  <?php if(!empty($r->customer_id)): ?>
                    <a href="<?php echo admin_url('clients/client/'.$r->customer_id); ?>">
                      <?php echo html_escape($r->customer_name).' (#'.(int)$r->customer_id.')'; ?>
                    </a>
                  <?php endif; ?>
                </td>
                <td><?php echo html_escape($r->email); ?></td>
                <td><?php echo html_escape($r->phone); ?></td>
                <td>
                  <div class="onoffswitch">
                    <input type="checkbox" class="onoffswitch-checkbox partner-active"
                           data-id="<?php echo (int)$r->id; ?>" id="p_<?php echo (int)$r->id; ?>"
                           <?php echo (int)$r->active === 1 ? 'checked' : ''; ?>>
                    <label class="onoffswitch-label" for="p_<?php echo (int)$r->id; ?>"></label>
                  </div>
                </td>
                <td>
                  <a href="<?php echo admin_url('lims/partners/create/'.$r->id); ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-pencil"></i>
                  </a>
                  <a href="<?php echo admin_url('lims/partners/delete/'.$r->id); ?>" class="btn btn-danger btn-sm _delete">
                    <i class="fa fa-trash"></i>
                  </a>
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
  $(function(){
    $('.partner-active').on('change', function(){
      $.post(admin_url + 'lims/partners/toggle_status', {
        id: $(this).data('id'),
        active: $(this).is(':checked') ? 1 : 0
      });
    });
  });
})(jQuery);
</script>
