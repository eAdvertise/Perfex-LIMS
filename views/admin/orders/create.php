<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <h4><?php echo _l('lims_new_order'); ?></h4>
        <?php echo form_open(admin_url('lims/create_order')); ?>
          <div class="form-group">
            <label><?php echo _l('lims_client'); ?></label>
            <input type="number" name="client_id" class="form-control" required>
          </div>
          <div class="form-group">
            <label><?php echo _l('lims_received_at'); ?></label>
            <input type="datetime-local" name="received_at" class="form-control">
          </div>
          <div class="form-group">
            <label><?php echo _l('lims_notes'); ?></label>
            <textarea name="notes" class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
          <a href="<?php echo admin_url('lims/orders'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
