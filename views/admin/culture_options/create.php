<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_culture_options'); ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/culture_options/create'.(isset($row->id)?'/'.$row->id:''))); ?>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label><?php echo _l('name'); ?></label>
              <input type="text" class="form-control" name="name"
                     value="<?php echo html_escape($row->name ?? ''); ?>" required>
              <small class="help-block text-muted">
                <?php echo _l('lims_culture_option_name_hint') ?: 'Display name of the option set (e.g. Culture Result).'; ?>
              </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label><?php echo _l('code'); ?></label>
              <input type="text" class="form-control" name="code"
                     value="<?php echo html_escape($row->code ?? ''); ?>" placeholder="CULTURE_RESULT">
              <small class="help-block text-muted">
                <?php echo _l('lims_culture_option_code_hint') ?: 'Internal code (A–Z, 0–9, underscore).'; ?>
              </small>
            </div>
          </div>
          <div class="col-md-3">
            <label class="control-label mright10"><?php echo _l('active'); ?></label>
            <div class="onoffswitch">
              <input type="checkbox" name="active" class="onoffswitch-checkbox" id="copt_active"
                <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
              <label class="onoffswitch-label" for="copt_active"></label>
            </div>
            <small class="help-block text-muted">
              <?php echo _l('lims_culture_option_active_hint') ?: 'Inactive sets will not be shown on cultures.'; ?>
            </small>
          </div>
        </div>

        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <label><?php echo _l('description'); ?></label>
              <textarea class="form-control" name="description" rows="2"><?php
                echo html_escape($row->description ?? '');
              ?></textarea>
            </div>
          </div>
        </div>

        <hr/>
        <h5 class="mbot10"><?php echo _l('lims_culture_option_values'); ?></h5>
        <p class="text-muted">
          <?php echo _l('lims_culture_option_values_hint') ?: 'Define the selectable values for this set (e.g. No growth, Normal flora, 1+, 2+).'; ?>
        </p>

        <div class="table-responsive">
          <table class="table table-bordered" id="culture-option-values-table">
            <thead>
              <tr>
                <th style="width:25%"><?php echo _l('lims_value_code') ?: 'Value code'; ?></th>
                <th style="width:55%"><?php echo _l('lims_value_label') ?: 'Label'; ?></th>
                <th style="width:10%"><?php echo _l('lims_sort_order') ?: 'Order'; ?></th>
                <th style="width:10%"></th>
              </tr>
            </thead>
            <tbody>
              <?php
                if (empty($values)) {
                    $values = [
                        (object)['value'=>'','label'=>'','sort_order'=>10],
                    ];
                }
                foreach ($values as $idx => $v):
              ?>
                <tr>
                  <td>
                    <input type="text" class="form-control"
                           name="values[value][]" value="<?php echo html_escape($v->value); ?>"
                           placeholder="NO_GROWTH">
                  </td>
                  <td>
                    <input type="text" class="form-control"
                           name="values[label][]" value="<?php echo html_escape($v->label); ?>"
                           placeholder="No growth">
                  </td>
                  <td>
                    <input type="number" class="form-control"
                           name="values[sort_order][]" value="<?php echo (int)($v->sort_order ?? (($idx+1)*10)); ?>">
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row">
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <button type="button" class="btn btn-default" id="btn-add-copt-row">
          <i class="fa fa-plus"></i> <?php echo _l('add'); ?>
        </button>

        <hr/>
        <div class="text-right">
          <button type="submit" class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
          <a href="<?php echo admin_url('lims/culture_options'); ?>" class="btn btn-default">
            <?php echo _l('lims_cancel'); ?>
          </a>
        </div>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  $(function(){

    function addRow(value, label, sort) {
      var $tb = $('#culture-option-values-table tbody');
      var idx = $tb.find('tr').length;
      var html = '' +
        '<tr>' +
          '<td><input type="text" class="form-control" name="values[value][]" ' +
              'value="'+(value||'')+'" placeholder="NO_GROWTH"></td>' +
          '<td><input type="text" class="form-control" name="values[label][]" ' +
              'value="'+(label||'')+'" placeholder="No growth"></td>' +
          '<td><input type="number" class="form-control" name="values[sort_order][]" ' +
              'value="'+(sort||((idx+1)*10))+'"></td>' +
          '<td class="text-center">' +
            '<button type="button" class="btn btn-danger btn-sm btn-remove-row">' +
              '<i class="fa fa-trash"></i>' +
            '</button>' +
          '</td>' +
        '</tr>';
      $tb.append(html);
    }

    $('#btn-add-copt-row').on('click', function(e){
      e.preventDefault();
      addRow('', '', '');
    });

    $(document).on('click', '.btn-remove-row', function(){
      var $tb = $('#culture-option-values-table tbody');
      if ($tb.find('tr').length <= 1) {
        // άφησε τουλάχιστον ένα κενό row
        $tb.find('tr').eq(0).find('input').val('');
        return;
      }
      $(this).closest('tr').remove();
    });

  });
})(jQuery);
</script>
