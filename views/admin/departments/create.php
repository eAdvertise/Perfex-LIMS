<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5"><?php echo _l('lims_departments'); ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?></h4>
        <?php echo form_open(admin_url('lims/departments/create'.(isset($row->id)?'/'.$row->id:''))); ?>

          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text" class="form-control" name="name"
                  value="<?php echo html_escape($row->name ?? ''); ?>" required>
                <small class="help-block text-muted"><?php echo _l('lims_department_name_desc'); ?></small>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('code'); ?></label>
                <input type="text" class="form-control" name="code"
                  value="<?php echo html_escape($row->code ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_department_code_desc'); ?></small>
              </div>
            </div>
            <div class="col-md-4">
              <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox" name="active" class="onoffswitch-checkbox" id="dep_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="dep_active"></label>
              </div>
            </div>
          </div>

          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/departments'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
