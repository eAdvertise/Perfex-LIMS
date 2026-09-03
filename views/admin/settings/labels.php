<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
// Εδώ είμαστε ΜΕΣΑ στο core settings form, άρα χρησιμοποιούμε render_input(name => "settings[key]")
?>

<div class="row">
  <div class="col-md-12">
    <h4 class="no-margin"><?php echo _l('lims_label_settings_title'); ?></h4>
    <hr class="hr-panel-heading" />
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <h5 class="bold"><?php echo _l('lims_label_page_setup'); ?></h5>
    <?php
      echo render_input('settings[lims_label_page_width_mm]',  _l('lims_label_page_width_mm'),  get_option('lims_label_page_width_mm'),  'number', ['step'=>'0.1','min'=>'10']);
      echo render_input('settings[lims_label_page_height_mm]', _l('lims_label_page_height_mm'), get_option('lims_label_page_height_mm'), 'number', ['step'=>'0.1','min'=>'10']);
      echo render_input('settings[lims_label_columns]',        _l('lims_label_columns'),        get_option('lims_label_columns'),       'number', ['min'=>'1']);
      echo render_input('settings[lims_label_rows]',           _l('lims_label_rows'),           get_option('lims_label_rows'),          'number', ['min'=>'1']);
    ?>
  </div>

  <div class="col-md-6">
    <h5 class="bold"><?php echo _l('lims_label_size'); ?></h5>
    <?php
      echo render_input('settings[lims_label_width_mm]',  _l('lims_label_width_mm'),  get_option('lims_label_width_mm'),  'number', ['step'=>'0.1','min'=>'1']);
      echo render_input('settings[lims_label_height_mm]', _l('lims_label_height_mm'), get_option('lims_label_height_mm'), 'number', ['step'=>'0.1','min'=>'1']);
      echo render_input('settings[lims_label_hgap_mm]',   _l('lims_label_hgap_mm'),   get_option('lims_label_hgap_mm'),   'number', ['step'=>'0.1','min'=>'0']);
      echo render_input('settings[lims_label_vgap_mm]',   _l('lims_label_vgap_mm'),   get_option('lims_label_vgap_mm'),   'number', ['step'=>'0.1','min'=>'0']);
    ?>
  </div>
</div>

<hr />

<div class="row">
  <div class="col-md-6">
    <h5 class="bold"><?php echo _l('lims_label_margins'); ?></h5>
    <?php
      echo render_input('settings[lims_label_left_margin_mm]', _l('lims_label_left_margin_mm'), get_option('lims_label_left_margin_mm'), 'number', ['step'=>'0.1','min'=>'0']);
      echo render_input('settings[lims_label_top_margin_mm]',  _l('lims_label_top_margin_mm'),  get_option('lims_label_top_margin_mm'),  'number', ['step'=>'0.1','min'=>'0']);
    ?>
  </div>

  <div class="col-md-6">
    <h5 class="bold"><?php echo _l('lims_label_fonts'); ?></h5>
    <?php
      echo render_input('settings[lims_label_font_size]',      _l('lims_label_font_size'),      get_option('lims_label_font_size'),      'number', ['step'=>'0.5','min'=>'6']);
      echo render_input('settings[lims_label_barcode_height]', _l('lims_label_barcode_height'), get_option('lims_label_barcode_height'), 'number', ['step'=>'0.5','min'=>'6']);
    ?>

    <div class="checkbox checkbox-primary mtop15">
      <input type="checkbox" id="lims_label_show_received" name="settings[lims_label_show_received]" value="1" <?php if(get_option('lims_label_show_received') == '1'){echo 'checked';} ?>>
      <label for="lims_label_show_received"><?php echo _l('lims_label_show_received'); ?></label>
    </div>

    <div class="checkbox checkbox-primary">
      <input type="checkbox" id="lims_label_show_sampletype" name="settings[lims_label_show_sampletype]" value="1" <?php if(get_option('lims_label_show_sampletype') == '1'){echo 'checked';} ?>>
      <label for="lims_label_show_sampletype"><?php echo _l('lims_label_show_sampletype'); ?></label>
    </div>

    <div class="checkbox checkbox-primary">
      <input type="checkbox" id="lims_label_show_analysis" name="settings[lims_label_show_analysis]" value="1" <?php if(get_option('lims_label_show_analysis') == '1'){echo 'checked';} ?>>
      <label for="lims_label_show_analysis"><?php echo _l('lims_label_show_analysis'); ?></label>
    </div>
  </div>
</div>
