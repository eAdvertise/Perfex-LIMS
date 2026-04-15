<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_cultures'); ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/cultures/create'.(isset($row->id)?'/'.$row->id:''))); ?>

          <!-- ====== Basic meta ====== -->
          <div class="row">
            <!-- Name -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text" class="form-control" name="name"
                       value="<?php echo html_escape($row->name ?? ''); ?>" required>
                <small class="help-block text-muted"><?php echo _l('lims_analysis_name_desc') ?: 'Clear name as displayed in orders/reports.'; ?></small>
              </div>
            </div>

            <!-- Code -->
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('code'); ?></label>
                <input type="text" class="form-control" name="code"
                       value="<?php echo html_escape($row->code ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_analysis_code_desc') ?: 'Optional internal code.'; ?></small>
              </div>
            </div>

            <!-- Active -->
            <div class="col-md-2">
              <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox" name="active" class="onoffswitch-checkbox" id="cu_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="cu_active"></label>
              </div>
              <small class="help-block text-muted"><?php echo _l('lims_analysis_active_desc') ?: 'Enable/disable culture in catalog.'; ?></small>
            </div>
          </div>

          <div class="row">
            <!-- Sample Type -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_sample_type'); ?></label>
                <select name="sample_type_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($sample_types)) foreach($sample_types as $st): ?>
                    <option value="<?php echo (int)$st->id; ?>"
                      <?php echo ((int)($row->sample_type_id ?? 0) === (int)$st->id) ? 'selected' : ''; ?>>
                      <?php echo html_escape($st->name . (!empty($st->code) ? " [{$st->code}]" : '')); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help-block text-muted"><?php echo _l('lims_sample_type_hint') ?: 'Choose the specimen this culture applies to.'; ?></small>
              </div>
            </div>

            <!-- Culture Type -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_culture_type') ?: 'Culture Type'; ?></label>
                <select name="culture_type_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($culture_types)) foreach($culture_types as $ct): ?>
                    <option value="<?php echo (int)$ct->id; ?>"
                      <?php echo ((int)($row->culture_type_id ?? 0) === (int)$ct->id) ? 'selected' : ''; ?>>
                      <?php echo html_escape($ct->name . (!empty($ct->code) ? " [{$ct->code}]" : '')); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help-block text-muted"><?php echo _l('lims_department_desc') ?: 'Category/group of culture.'; ?></small>
              </div>
            </div>

            <!-- Method -->
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('lims_method'); ?></label>
                <input type="text" class="form-control" name="method"
                       value="<?php echo html_escape($row->method ?? ''); ?>" placeholder="e.g. Culture, MIC">
                <small class="help-block text-muted"><?php echo _l('lims_method_desc') ?: 'Analytical procedure (e.g., Culture, MIC).'; ?></small>
              </div>
            </div>

            <!-- TAT hours -->
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('lims_tat_hours'); ?></label>
                <input type="number" class="form-control" name="tat_hours"
                       value="<?php echo html_escape($row->tat_hours ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_tat_hours_desc') ?: 'Expected turnaround time (hours).'; ?></small>
              </div>
            </div>
          </div>
		  <div class="row">
            <!-- Culture option sets -->
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo _l('lims_culture_option_sets'); ?></label>
                <select name="culture_option_sets[]"
                        class="form-control selectpicker"
                        data-live-search="true"
                        data-actions-box="true"
                        multiple
                        data-size="10">
                  <?php
                    $selectedSets = $culture_option_selected ?? [];
                    if (!empty($culture_option_sets)):
                      foreach ($culture_option_sets as $os):
                        $sid = (int)$os->id;
                  ?>
                    <option value="<?php echo $sid; ?>"
                      <?php echo in_array($sid, $selectedSets, true) ? 'selected' : ''; ?>>
                      <?php echo html_escape($os->name . ($os->code ? " [{$os->code}]" : '')); ?>
                    </option>
                  <?php
                      endforeach;
                    endif;
                  ?>
                </select>
                <small class="help-block text-muted">
                  <?php echo _l('lims_culture_option_sets_hint')
                    ?: 'Select which predefined option sets (e.g. Culture Result, Semi-quantitative growth) apply to this culture.'; ?>
                </small>
              </div>
            </div>
          </div>

          <hr/>

          <!-- ====== Billing (auto Item) ====== -->
          <h5 class="mbot10"><?php echo _l('billing') ?: 'Billing'; ?></h5>
          <p class="text-muted"><?php echo _l('lims_billing_note') ?: 'This culture will be linked to a billing item automatically.'; ?></p>

          <!-- Rates per active currency -->
          <div class="row mtop10">
            <div class="col-md-12">
              <label class="mbot5"><?php echo _l('price') ?: 'Rate'; ?> (<?php echo _l('currency') ?: 'Currency'; ?>)</label>
              <div class="row">
                <?php
                  $defId = null; foreach($currencies as $c){ if(!empty($c->isdefault)){ $defId=(int)$c->id; break; } }
                ?>
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
              <small class="help-block text-muted"><?php echo _l('lims_rates_note') ?: 'Default currency price is used for billing. Others stored for reference.'; ?></small>
            </div>
          </div>

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
                <small class="help-block text-muted"><?php echo _l('lims_unit_note') ?: 'Example: test, culture, service.'; ?></small>
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
                <small class="help-block text-muted"><?php echo _l('note') ?: 'Choose primary tax if applicable.'; ?></small>
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
                <small class="help-block text-muted"><?php echo _l('note') ?: 'Optional second tax.'; ?></small>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Long Description -->
            <div class="col-md-12">
              <div class="form-group">
                <label><?php echo _l('long_description') ?: 'Long Description'; ?></label>
                <textarea class="form-control" name="item_long_description" rows="3"
                  placeholder="e.g. LIMS Culture: <?php echo html_escape($row->name ?? ''); ?>"><?php
                  echo html_escape($item->long_description ?? '');
                ?></textarea>
                <small class="help-block text-muted">
                  <?php echo _l('lims_item_link_desc') ?: 'Used on invoices/quotes; kept in sync with the culture.'; ?>
                </small>
              </div>
            </div>
          </div>

          <hr/>
          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/cultures'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>

        <!-- ====== Info section ====== -->
        <hr class="mtop20" />
        <div class="mtop20">
          <h5 class="mbot10"><?php echo _l('lims_cultures_about_title') ?: 'About Cultures'; ?></h5>
          <p class="text-muted">
            <?php echo _l('lims_cultures_about_desc') ?: 'Cultures capture microbiology workflows such as organism growth and susceptibility. Configure name, specimen, culture type, method, and TAT to standardize ordering and reporting.'; ?>
          </p>

          <h6 class="mtop15"><?php echo _l('lims_panel_purpose_title') ?: 'Purpose'; ?></h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_cultures_purpose_1') ?: 'Provide clear, standardized culture options for orders.'; ?></li>
            <li>• <?php echo _l('lims_cultures_purpose_2') ?: 'Support pricing and contracting via linked items.'; ?></li>
            <li>• <?php echo _l('lims_cultures_purpose_3') ?: 'Guide staff with consistent methods and expected TAT.'; ?></li>
          </ul>

          <h6 class="mtop15"><?php echo _l('lims_panel_guidelines_title') ?: 'Guidelines'; ?></h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_cultures_guideline_1') ?: 'Pick the correct specimen and culture type for clarity.'; ?></li>
            <li>• <?php echo _l('lims_cultures_guideline_2') ?: 'Configure prices and taxes at the culture level; contracts can override.'; ?></li>
            <li>• <?php echo _l('lims_cultures_guideline_3') ?: 'Use descriptive long descriptions for client-facing documents.'; ?></li>
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
  });
})(jQuery);
</script>
