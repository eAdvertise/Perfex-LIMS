<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_sample_types'); ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/sampletypes/create'.(isset($row->id)?'/'.$row->id:''))); ?>

          <div class="row">
            <!-- Name -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text" class="form-control" name="name"
                  value="<?php echo html_escape($row->name ?? ''); ?>" required>
                <small class="help-block text-muted"><?php echo _l('lims_sampletype_name_desc'); ?></small>
              </div>
            </div>

            <!-- Code -->
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('code'); ?></label>
                <input type="text" class="form-control" name="code"
                  value="<?php echo html_escape($row->code ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_sampletype_code_desc'); ?></small>
              </div>
            </div>

            <!-- SNOMED -->
            <div class="col-md-3">
              <div class="form-group">
                <label>SNOMED</label>
                <input type="text" class="form-control" name="snomed_specimen_code"
                  value="<?php echo html_escape($row->snomed_specimen_code ?? ''); ?>" placeholder="e.g. 122554006">
                <small class="help-block text-muted"><?php echo _l('lims_sampletype_snomed_desc'); ?></small>
              </div>
            </div>

            <!-- Active switch -->
            <div class="col-md-3">
              <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox" name="active" class="onoffswitch-checkbox" id="st_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="st_active"></label>
              </div>
              <small class="help-block text-muted"><?php echo _l('lims_sampletype_active_desc'); ?></small>
            </div>
          </div>

          <div class="row">
            <!-- Min Volume -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_min_volume'); ?></label>
                <input type="text" class="form-control" name="min_volume"
                  value="<?php echo html_escape($row->min_volume ?? ''); ?>" placeholder="e.g. 2 mL">
                <small class="help-block text-muted"><?php echo _l('lims_min_volume_desc'); ?></small>
              </div>
            </div>

            <!-- Container -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_container'); ?></label>
                <input type="text" class="form-control" name="container"
                  value="<?php echo html_escape($row->container ?? ''); ?>" placeholder="e.g. Serum tube">
                <small class="help-block text-muted"><?php echo _l('lims_container_desc'); ?></small>
              </div>
            </div>

            <!-- Stability (hours) -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_stability_hours'); ?></label>
                <input type="number" class="form-control" name="stability_hours"
                  value="<?php echo html_escape($row->stability_hours ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_stability_hours_desc'); ?></small>
              </div>
            </div>

            <!-- Storage Temp -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_storage_temp'); ?></label>
                <input type="text" class="form-control" name="storage_temp"
                  value="<?php echo html_escape($row->storage_temp ?? ''); ?>" placeholder="e.g. 2–8°C">
                <small class="help-block text-muted"><?php echo _l('lims_storage_temp_desc'); ?></small>
              </div>
            </div>
          </div>

          <!-- Collection Instructions -->
          <div class="form-group">
            <label><?php echo _l('collection'); ?> <?php echo _l('instructions'); ?></label>
            <textarea class="form-control" name="collection_instructions" rows="3"><?php echo html_escape($row->collection_instructions ?? ''); ?></textarea>
            <small class="help-block text-muted"><?php echo _l('lims_collection_instructions_desc'); ?></small>
          </div>

          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/sampletypes'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>
		<hr class="mtop20" />

		<div class="mtop20">
		  <h5 class="mbot10"><?php echo _l('lims_sampletype_info_title'); ?></h5>
		  <p class="text-muted"><?php echo _l('lims_sampletype_info_desc'); ?></p>

		  <h6 class="mtop15"><?php echo _l('lims_sampletype_info_purpose_title'); ?></h6>
		  <ul class="list-unstyled text-muted">
			<li>• <?php echo _l('lims_sampletype_info_purpose_1'); ?></li>
			<li>• <?php echo _l('lims_sampletype_info_purpose_2'); ?></li>
			<li>• <?php echo _l('lims_sampletype_info_purpose_3'); ?></li>
		  </ul>

		  <h6 class="mtop15"><?php echo _l('lims_sampletype_info_guidelines_title'); ?></h6>
		  <ul class="list-unstyled text-muted">
			<li>• <?php echo _l('lims_sampletype_info_guideline_1'); ?></li>
			<li>• <?php echo _l('lims_sampletype_info_guideline_2'); ?></li>
			<li>• <?php echo _l('lims_sampletype_info_guideline_3'); ?></li>
			<li>• <?php echo _l('lims_sampletype_info_guideline_4'); ?></li>
		  </ul>
		</div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
