<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-12">
    <h4 class="no-margin"><?php echo _l('lims_settings_general'); ?></h4>
	
    <p class="text-muted">
      <?php echo _l('lims_settings_general_help'); ?>
    </p>
    <hr class="hr-panel-heading" />
  </div>

  <!-- Barcode prefix -->
  <div class="col-md-6">
    <div class="form-group">
      <label for="lims_barcode_prefix"><?php echo _l('lims_settings_barcode_prefix'); ?></label>
      <input type="text" class="form-control" id="lims_barcode_prefix"
             name="settings[lims_barcode_prefix]"
             value="<?php echo get_option('lims_barcode_prefix'); ?>">
      <small class="text-muted"><?php echo _l('lims_settings_barcode_desc'); ?></small>
    </div>
  </div>

  <!-- Default Department -->
  <div class="col-md-6">
    <div class="form-group">
      <label for="lims_default_department"><?php echo _l('lims_settings_default_dept'); ?></label>
      <input type="text" class="form-control" id="lims_default_department"
             name="settings[lims_default_department]"
             value="<?php echo get_option('lims_default_department'); ?>"
             placeholder="e.g. Chemistry">
      <small class="text-muted"><?php echo _l('lims_settings_default_dept_desc'); ?></small>
    </div>
  </div>

  <!-- Enable Contracts -->
  <div class="col-md-6">
    <div class="form-group">
      <div class="checkbox checkbox-primary">
        <input type="checkbox" id="lims_enable_contracts"
               name="settings[lims_enable_contracts]" value="1"
               <?php echo get_option('lims_enable_contracts') == '1' ? 'checked' : ''; ?>>
        <label for="lims_enable_contracts"><?php echo _l('lims_settings_enable_contracts'); ?></label>
      </div>
      <small class="text-muted"><?php echo _l('lims_settings_enable_contracts_desc'); ?></small>
    </div>
  </div>
</div>

<?php
$prefix      = get_option('lims_subject_prefix');
$next_number = get_option('lims_subject_next_number');

// defaults
if ($prefix === '') {
    $prefix = 'SUB-';
}
if ($next_number === '' || (int)$next_number <= 0) {
    $next_number = 1;
}
?>

<div class="row">
  <div class="col-md-4">
    <?php
    echo render_input(
        'settings[lims_subject_prefix]',
        'lims_subject_prefix',
        $prefix
    );
    ?>
    <p class="text-muted no-mtop">
      <?php echo _l('lims_subject_prefix_help'); ?>
    </p>
  </div>

  <div class="col-md-3">
    <?php
    echo render_input(
        'settings[lims_subject_next_number]',
        'lims_subject_next_number',
        $next_number,
        'number',
        ['min' => 1, 'step' => 1]
    );
    ?>
    <p class="text-muted no-mtop">
      <?php echo _l('lims_subject_next_number_help'); ?>
    </p>
  </div>
</div>

<!-- Χρησιμοποιούμε το core Save του Settings, δεν βάζουμε δικό μας form -->
