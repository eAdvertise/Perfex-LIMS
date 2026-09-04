<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <div class="clearfix mbot15">
          <h4 class="pull-left mtop5">
            <?php echo _l('lims_test_statuses'); ?>
          </h4>
          <a href="<?php echo admin_url('lims/teststatuses/create'); ?>" class="btn btn-primary pull-right">
            <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
          </a>
        </div>

        <div class="table-responsive">
          <table class="table dt-table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('code'); ?></th>
                <th><?php echo _l('color'); ?></th>
                <th><?php echo _l('default'); ?></th>
                <th><?php echo _l('active'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php if (!empty($rows)) : ?>
              <?php foreach ($rows as $r) : ?>
                <tr>
                  <td><?php echo (int) $r->id; ?></td>
                  <td><?php echo html_escape($r->name); ?></td>
                  <td><code><?php echo html_escape($r->code); ?></code></td>
                  <td>
                    <?php if ($r->color) : ?>
                      <span class="label" style="background:<?php echo html_escape($r->color); ?>;">&nbsp;&nbsp;&nbsp;</span>
                      <small class="text-muted mleft5"><?php echo html_escape($r->color); ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="onoffswitch">
                      <input type="checkbox"
                             class="onoffswitch-checkbox sw-default"
                             id="def_<?php echo (int) $r->id; ?>"
                             data-id="<?php echo (int) $r->id; ?>"
                             <?php echo (int) $r->is_default === 1 ? 'checked' : ''; ?>>
                      <label class="onoffswitch-label" for="def_<?php echo (int) $r->id; ?>"></label>
                    </div>
                  </td>
                  <td>
                    <div class="onoffswitch">
                      <input type="checkbox"
                             class="onoffswitch-checkbox sw-active"
                             id="act_<?php echo (int) $r->id; ?>"
                             data-id="<?php echo (int) $r->id; ?>"
                             <?php echo (int) $r->active === 1 ? 'checked' : ''; ?>>
                      <label class="onoffswitch-label" for="act_<?php echo (int) $r->id; ?>"></label>
                    </div>
                  </td>
                  <td>
                    <a href="<?php echo admin_url('lims/teststatuses/create/' . (int) $r->id); ?>"
                       class="btn btn-default btn-sm"
                       data-toggle="tooltip"
                       title="<?php echo _l('edit'); ?>">
                      <i class="fa fa-pencil"></i>
                    </a>
                    <a href="<?php echo admin_url('lims/teststatuses/delete/' . (int) $r->id); ?>"
                       class="btn btn-danger btn-sm _delete"
                       data-toggle="tooltip"
                       title="<?php echo _l('delete'); ?>">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
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
  "use strict";

  $(function(){

    // Active switch
    $(document).on('change', '.sw-active', function(){
      $.post(admin_url + 'lims/teststatuses/toggle_active', {
        id: $(this).data('id'),
        active: $(this).is(':checked') ? 1 : 0
      });
    });

    // Default switch
    $(document).on('change', '.sw-default', function(){
      var $el = $(this);
      if ($el.is(':checked')) {
        $.post(admin_url + 'lims/teststatuses/set_default', {
          id: $el.data('id')
        }).done(function(){
          window.location.reload();
        });
      } else {
        // Δεν επιτρέπουμε να μην υπάρχει default – απλά κάνε reload.
        window.location.reload();
      }
    });

    // Move up/down (position)
    $(document).on('click', '.btn-move', function(e){
      e.preventDefault();
      $.post(admin_url + 'lims/teststatuses/move', {
        id:  $(this).data('id'),
        dir: $(this).data('dir')
      }).done(function(){
        window.location.reload();
      });
    });

  });
})(jQuery);
</script>
