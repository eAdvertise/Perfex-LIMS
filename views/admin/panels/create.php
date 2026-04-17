<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_panels') ?: 'Panels'; ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/panels/create'.(isset($row->id)?'/'.$row->id:''))); ?>

          <div class="row">
            <!-- Name -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text" class="form-control" name="name" value="<?php echo html_escape($row->name ?? ''); ?>" required>
                <small class="help-block text-muted"><?php echo _l('lims_panel_name_desc') ?: 'Give the panel a clear name clients will recognize.'; ?></small>
              </div>
            </div>

            <!-- Code -->
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('code'); ?></label>
                <input type="text" class="form-control" name="code" value="<?php echo html_escape($row->code ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_panel_code_desc') ?: 'Optional internal code.'; ?></small>
              </div>
            </div>

            <!-- Department -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_department'); ?></label>
                <?php $selectedDept = $row->department_id ?? null; ?>
                <select name="department_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php foreach($departments as $d): ?>
                    <option value="<?php echo (int)$d->id; ?>" <?php echo (!empty($selectedDept) && (int)$selectedDept===(int)$d->id)?'selected':''; ?>>
                      <?php echo html_escape($d->name . ($d->code ? " [{$d->code}]" : '')); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help-block text-muted"><?php echo _l('lims_department_desc'); ?></small>
              </div>
            </div>

            <!-- Active -->
            <div class="col-md-3">
              <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox" name="active" class="onoffswitch-checkbox" id="panel_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="panel_active"></label>
              </div>
              <small class="help-block text-muted"><?php echo _l('lims_panel_active_desc') ?: 'Active panels can be used in orders and contracts.'; ?></small>
            </div>
          </div>

          <!-- Analyses selection -->
          <div class="form-group">
            <label><?php echo _l('lims_select_analyses') ?: 'Select analyses'; ?></label>
            <select name="analysis_ids[]" class="form-control selectpicker" data-live-search="true" data-actions-box="true" data-size="10" multiple>
              <?php
                $sel = isset($selected_ids) && is_array($selected_ids) ? $selected_ids : [];
                foreach($analyses as $a):
              ?>
                <option value="<?php echo (int)$a->id; ?>" <?php echo in_array((int)$a->id,$sel)?'selected':''; ?>>
                  <?php echo html_escape($a->name . ($a->code ? " [{$a->code}]" : '')); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small class="help-block text-muted"><?php echo _l('lims_panel_analyses_desc') ?: 'Pick the analyses included in this panel.'; ?></small>
          </div>

          <hr/>

          <!-- Billing -->
          <h5 class="mbot10"><?php echo _l('billing') ?: 'Billing'; ?></h5>
          <p class="text-muted"><?php echo _l('lims_billing_note'); ?></p>

          
            <!-- Unit + Taxes -->
              <div class="row">
                <!-- Unit -->
                <div class="col-md-4">
                  <div class="form-group">
                    <label><?php echo _l('unit') ?: 'Unit'; ?></label>
                    <input type="text" class="form-control" name="item_unit" list="lims_units_datalist"
                           value="<?php echo html_escape($item->unit ?? ''); ?>">
                    <datalist id="lims_units_datalist">
                      <?php if(!empty($units)) foreach($units as $u): ?>
                        <option value="<?php echo html_escape($u->unit); ?>"></option>
                      <?php endforeach; ?>
                    </datalist>
                    <small class="help-block text-muted"><?php echo _l('lims_unit_note'); ?></small>
                  </div>
                </div>

                <!-- Tax 1 -->
                <div class="col-md-4">
                  <div class="form-group">
                    <label><?php echo _l('tax_1') ?: 'Tax 1'; ?></label>
                    <?php $tax1sel = isset($item->taxid) ? (int)$item->taxid : null; ?>
                    <select name="item_tax" class="form-control selectpicker" data-live-search="true" data-size="10">
                      <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                      <?php foreach($taxes as $t): ?>
                        <option value="<?php echo (int)$t->id; ?>" <?php echo ($tax1sel===(int)$t->id)?'selected':''; ?>>
                          <?php echo html_escape($t->name.' ('.$t->taxrate.'%)'); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <!-- Tax 2 -->
                <div class="col-md-4">
                  <div class="form-group">
                    <label><?php echo _l('tax_2') ?: 'Tax 2'; ?></label>
                    <?php $tax2sel = isset($item->taxid_2) ? (int)$item->taxid_2 : null; ?>
                    <select name="item_tax2" class="form-control selectpicker" data-live-search="true" data-size="10">
                      <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                      <?php foreach($taxes as $t): ?>
                        <option value="<?php echo (int)$t->id; ?>" <?php echo ($tax2sel===(int)$t->id)?'selected':''; ?>>
                          <?php echo html_escape($t->name.' ('.$t->taxrate.'%)'); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

           </div>

          <!-- Rates per active currency -->
          <div class="row mtop10">
            <div class="col-md-12">
              <label class="mbot5"><?php echo _l('price') ?: 'Rate'; ?> (<?php echo _l('currency') ?: 'Currency'; ?>)</label>
              <div class="row">
                <?php $defId = null; foreach($currencies as $c){ if(!empty($c->isdefault)){ $defId=(int)$c->id; break; } } ?>
                <?php if(!empty($currencies)): foreach($currencies as $cur): ?>
                  <?php
                    $cid = (int)$cur->id;
                    $val = isset($item_rates_map[$cid]) ? $item_rates_map[$cid] : null;
                  ?>
                  <div class="col-md-3">
                    <div class="input-group mtop5">
                      <span class="input-group-addon">
                        <?php echo html_escape($cur->name); ?>
                        <?php if(isset($defId) && $defId === $cid): ?>
                          <span class="label label-info mleft5">default</span>
                        <?php endif; ?>
                      </span>
                      <input type="number" step="0.0001" class="form-control"
                             name="item_rates[<?php echo (int)$cid; ?>]" placeholder="0.00"
                             value="<?php echo $val !== null ? html_escape($val) : ''; ?>">
                    </div>
                  </div>
                <?php endforeach; endif; ?>
              </div>
              <small class="help-block text-muted"><?php echo _l('lims_rates_note'); ?></small>
            </div>
          </div>

          <hr/>
          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/panels'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>

        <hr class="mtop20" />
		<div class="mtop20">
		  <h5 class="mbot10"><?php echo _l('lims_panel_about_title'); ?></h5>
		  <p class="text-muted"><?php echo _l('lims_panel_about_desc'); ?></p>

		  <h6 class="mtop15"><?php echo _l('lims_panel_purpose_title'); ?></h6>
		  <ul class="list-unstyled text-muted">
			<li>• <?php echo _l('lims_panel_purpose_1'); ?></li>
			<li>• <?php echo _l('lims_panel_purpose_2'); ?></li>
			<li>• <?php echo _l('lims_panel_purpose_3'); ?></li>
		  </ul>

		  <h6 class="mtop15"><?php echo _l('lims_panel_guidelines_title'); ?></h6>
		  <ul class="list-unstyled text-muted">
			<li>• <?php echo _l('lims_panel_guideline_1'); ?></li>
			<li>• <?php echo _l('lims_panel_guideline_2'); ?></li>
			<li>• <?php echo _l('lims_panel_guideline_3'); ?></li>
			<li>• <?php echo _l('lims_panel_guideline_4'); ?></li>
		  </ul>
		</div>


      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  $(function(){
    if ($.fn.selectpicker) {
      $('.selectpicker').selectpicker('render').selectpicker('refresh');
    }
    // Auto-fill Long Description από τα ονόματα των επιλεγμένων analyses (on change)
    var $sel = $('select[name="analysis_ids[]"]');
    var $txt = $('#panel_long_desc');
    function updateLongDesc(){
      var names = [];
      $sel.find('option:selected').each(function(){ names.push($(this).text()); });
      if (names.length && ($txt.val().trim()==='' || $txt.data('autofilled')==='1')) {
        $txt.val(names.join(', '));
        $txt.data('autofilled','1');
      }
    }
    $sel.on('changed.bs.select change', updateLongDesc);
    // αν το textarea είναι κενό στην αρχή, προσπάθησε auto-fill
    if (($txt.val()||'').trim()==='') { $txt.data('autofilled','1'); updateLongDesc(); }
  });
})(jQuery);
</script>
