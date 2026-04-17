<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <h4><?php echo _l('lims_settings'); ?> (v<?php echo LIMS_MODULE_VERSION ?? '0.0.1'; ?>)</h4>
        <?php echo form_open(admin_url('lims/settings')); ?>
          <div class="form-group">
            <label><?php echo _l('lims_setting_barcode_prefix'); ?></label>
            <input type="text" name="lims_barcode_prefix" class="form-control" value="<?php echo get_option('lims_barcode_prefix'); ?>">
          </div>
          <button class="btn btn-primary"><?php echo _l('lims_setting_save'); ?></button>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
