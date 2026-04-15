<?php //views/admin/analyses/create.php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_analyses'); ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/analyses/create'.(isset($row->id)?'/'.$row->id:''))); ?>

          <!-- ====== Basic meta ====== -->
          <div class="row">
            <!-- Name -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text" class="form-control" name="name"
                       value="<?php echo html_escape($row->name ?? ''); ?>" required>
                <small class="help-block text-muted"><?php echo _l('lims_analysis_name_desc'); ?></small>
              </div>
            </div>

            <!-- Code -->
            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('code'); ?></label>
                <input type="text" class="form-control" name="code"
                       value="<?php echo html_escape($row->code ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_analysis_code_desc'); ?></small>
              </div>
            </div>

            <!-- LOINC -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_loinc_code'); ?></label>
                <input type="text" class="form-control" name="loinc_code"
                       value="<?php echo html_escape($row->loinc_code ?? ''); ?>" placeholder="e.g. 718-7">
                <small class="help-block text-muted"><?php echo _l('lims_loinc_code_desc'); ?></small>
              </div>
            </div>

            <!-- Active -->
            <div class="col-md-3">
              <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox" name="active" class="onoffswitch-checkbox" id="an_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="an_active"></label>
              </div>
              <small class="help-block text-muted"><?php echo _l('lims_analysis_active_desc'); ?></small>
            </div>
          </div>

          <!-- ====== Technical details ====== -->
          <div class="row">
            <!-- Department (dropdown) -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_department'); ?></label>
                <?php $selectedDept = $row->department_id ?? null; ?>
                <select name="department_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php foreach($departments as $d): ?>
                    <option value="<?php echo (int)$d->id; ?>"
                      <?php echo (!empty($selectedDept) && (int)$selectedDept===(int)$d->id)?'selected':''; ?>>
                      <?php echo html_escape($d->name . ($d->code ? " [{$d->code}]" : '')); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help-block text-muted"><?php echo _l('lims_department_desc'); ?></small>
              </div>
            </div>

            <!-- Sample Type (NEW) -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_sample_type'); ?></label>
                <?php $selectedST = $row->sample_type_id ?? null; ?>
                <select name="sample_type_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($sample_types)) foreach($sample_types as $st): ?>
                    <option value="<?php echo (int)$st->id; ?>"
                      <?php echo (!empty($selectedST) && (int)$selectedST===(int)$st->id)?'selected':''; ?>>
                      <?php echo html_escape($st->name . ($st->code ? " [{$st->code}]" : '')); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help-block text-muted">
                  <?php echo _l('lims_sample_type_hint') ?: 'Select the primary specimen type appropriate for this analysis.'; ?>
                </small>
              </div>
            </div>

            <!-- Method -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_method'); ?></label>
                <input type="text" class="form-control" name="method"
                       value="<?php echo html_escape($row->method ?? ''); ?>" placeholder="e.g. Immunoassay">
                <small class="help-block text-muted"><?php echo _l('lims_method_desc'); ?></small>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- TAT hours -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_tat_hours'); ?></label>
                <input type="number" class="form-control" name="tat_hours"
                       value="<?php echo html_escape($row->tat_hours ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_tat_hours_desc'); ?></small>
              </div>
            </div>

            <!-- Decimals -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_decimal_places'); ?></label>
                <input type="number" class="form-control" name="decimal_places"
                       value="<?php echo html_escape($row->decimal_places ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_decimal_places_desc'); ?></small>
              </div>
            </div>

            <!-- Units UCUM -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_units_ucum'); ?></label>
                <input type="text" class="form-control" name="units_ucum"
                       value="<?php echo html_escape($row->units_ucum ?? ''); ?>" placeholder="e.g. mg/dL">
                <small class="help-block text-muted"><?php echo _l('lims_units_ucum_desc'); ?></small>
              </div>
            </div>

            <!-- Result type -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_result_type'); ?></label>
                <?php $rt = $row->result_type ?? 'numeric'; ?>
                <select name="result_type" class="form-control selectpicker">
                  <option value="numeric" <?php echo $rt==='numeric'?'selected':''; ?>>Numeric</option>
                  <option value="text"    <?php echo $rt==='text'?'selected':''; ?>>Text</option>
                  <option value="select"  <?php echo $rt==='select'?'selected':''; ?>>Select</option>
                </select>
                <small class="help-block text-muted"><?php echo _l('lims_result_type_desc'); ?></small>
              </div>
            </div>
			
          </div>
			<?php
			  // decode select options (αν υπάρχουν)
			  $selectOpts = [];
			  if (!empty($row->select_options)) {
				  $tmp = json_decode($row->select_options, true);
				  if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
					  $selectOpts = $tmp;
				  }
			  }
			?>

			<!-- ====== Select Values (μόνο όταν result_type = select) ====== -->
			<div class="row mtop10" id="select-values-block" style="<?php echo (isset($rt) && $rt==='select') ? '' : 'display:none;'; ?>">
			  <div class="col-md-12">
				<label><?php echo _l('lims_result_select_values'); ?></label>
				<p class="text-muted small"><?php echo _l('lims_result_select_desc'); ?></p>

				<div id="select-values-rows">
				  <?php if (!empty($selectOpts)): ?>
					<?php foreach ($selectOpts as $opt): ?>
					  <div class="row mtop5 select-value-row">
						<div class="col-md-5">
						  <input type="text" class="form-control"
								 name="select_values[value][]"
								 value="<?php echo html_escape($opt['value'] ?? ''); ?>"
								 placeholder="<?php echo _l('lims_result_select_value'); ?>">
						</div>
						<div class="col-md-5">
						  <input type="text" class="form-control"
								 name="select_values[label][]"
								 value="<?php echo html_escape($opt['label'] ?? ''); ?>"
								 placeholder="<?php echo _l('lims_result_select_label'); ?>">
						</div>
						<div class="col-md-2 text-right">
						  <button type="button" class="btn btn-default btn-sm js-add-select-value">
							<i class="fa fa-plus"></i>
						  </button>
						  <button type="button" class="btn btn-danger btn-sm js-remove-select-value">
							<i class="fa fa-times"></i>
						  </button>
						</div>
					  </div>
					<?php endforeach; ?>
				  <?php else: ?>
					<div class="row mtop5 select-value-row">
					  <div class="col-md-5">
						<input type="text" class="form-control"
							   name="select_values[value][]"
							   placeholder="<?php echo _l('lims_result_select_value'); ?>">
					  </div>
					  <div class="col-md-5">
						<input type="text" class="form-control"
							   name="select_values[label][]"
							   placeholder="<?php echo _l('lims_result_select_label'); ?>">
					  </div>
					  <div class="col-md-2 text-right">
						<button type="button" class="btn btn-default btn-sm js-add-select-value">
						  <i class="fa fa-plus"></i>
						</button>
						<button type="button" class="btn btn-danger btn-sm js-remove-select-value">
						  <i class="fa fa-times"></i>
						</button>
					  </div>
					</div>
				  <?php endif; ?>
				</div>
			  </div>
			</div>

		    <?php
			  $specs = isset($specs) && is_array($specs) ? $specs : [];
			?>
			<hr/>

			<h5 class="mbot10"><?php echo _l('lims_analysis_reference_ranges'); ?></h5>
			<p class="text-muted small"><?php echo _l('lims_analysis_reference_ranges_help'); ?></p>

			<div class="panel panel-default">
			  <div class="panel-heading clearfix">
				<span><?php echo _l('lims_analysis_reference_ranges'); ?></span>
				<button type="button" class="btn btn-default btn-xs pull-right js-add-ref-row">
				  <i class="fa fa-plus"></i>
				</button>
			  </div>
			  <div class="panel-body ptop10 pbottom10">
				<div class="row mtop5">
				  <div class="col-md-2"><strong><?php echo _l('lims_analysis_ref_gender'); ?></strong></div>
				  <div class="col-md-2"><strong><?php echo _l('lims_analysis_ref_age_from'); ?></strong></div>
				  <div class="col-md-2"><strong><?php echo _l('lims_analysis_ref_age_to'); ?></strong></div>
				  <div class="col-md-2"><strong><?php echo _l('lims_analysis_ref_critical_low'); ?></strong></div>
				  <div class="col-md-2"><strong><?php echo _l('lims_analysis_ref_normal'); ?></strong></div>
				  <div class="col-md-2"><strong><?php echo _l('lims_analysis_ref_critical_high'); ?></strong></div>
				</div>

				<div id="ref-range-rows">
				  <?php if (!empty($specs)): ?>
					<?php foreach ($specs as $sp): ?>
					  <div class="row mtop5 ref-row">
						<div class="col-md-2">
						  <select name="spec[sex][]" class="form-control input-sm">
							<option value="U" <?php echo $sp->sex==='U'?'selected':''; ?>><?php echo _l('lims_gender_both'); ?></option>
							<option value="M" <?php echo $sp->sex==='M'?'selected':''; ?>><?php echo _l('lims_gender_male'); ?></option>
							<option value="F" <?php echo $sp->sex==='F'?'selected':''; ?>><?php echo _l('lims_gender_female'); ?></option>
						  </select>
						</div>
						<div class="col-md-2">
						  <input type="number" step="0.01" class="form-control input-sm"
								 name="spec[age_min][]"
								 value="<?php echo html_escape($sp->age_min); ?>"
								 placeholder="<?php echo _l('lims_analysis_ref_age_from'); ?>">
						</div>
						<div class="col-md-2">
						  <input type="number" step="0.01" class="form-control input-sm"
								 name="spec[age_max][]"
								 value="<?php echo html_escape($sp->age_max); ?>"
								 placeholder="<?php echo _l('lims_analysis_ref_age_to'); ?>">
						</div>
						<div class="col-md-2">
						  <input type="number" step="0.000001" class="form-control input-sm"
								 name="spec[critical_low][]"
								 value="<?php echo html_escape($sp->critical_low); ?>"
								 placeholder="<?php echo _l('lims_analysis_ref_critical_low'); ?>">
						</div>
						<div class="col-md-2">
						  <div class="row">
							<div class="col-xs-6">
							  <input type="number" step="0.000001" class="form-control input-sm"
									 name="spec[ref_low][]"
									 value="<?php echo html_escape($sp->ref_low); ?>"
									 placeholder="<?php echo _l('lims_analysis_ref_normal_low'); ?>">
							</div>
							<div class="col-xs-6">
							  <input type="number" step="0.000001" class="form-control input-sm"
									 name="spec[ref_high][]"
									 value="<?php echo html_escape($sp->ref_high); ?>"
									 placeholder="<?php echo _l('lims_analysis_ref_normal_high'); ?>">
							</div>
						  </div>
						</div>
						<div class="col-md-2">
						  <div class="input-group">
							<input type="number" step="0.000001" class="form-control input-sm"
								   name="spec[critical_high][]"
								   value="<?php echo html_escape($sp->critical_high); ?>"
								   placeholder="<?php echo _l('lims_analysis_ref_critical_high'); ?>">
							<span class="input-group-btn">
							  <button type="button" class="btn btn-danger btn-sm js-remove-ref-row">
								<i class="fa fa-times"></i>
							  </button>
							</span>
						  </div>
						</div>
					  </div>
					<?php endforeach; ?>
				  <?php else: ?>
					<!-- empty template row -->
					<div class="row mtop5 ref-row">
					  <div class="col-md-2">
						<select name="spec[sex][]" class="form-control input-sm">
						  <option value="U"><?php echo _l('lims_gender_both'); ?></option>
						  <option value="M"><?php echo _l('lims_gender_male'); ?></option>
						  <option value="F"><?php echo _l('lims_gender_female'); ?></option>
						</select>
					  </div>
					  <div class="col-md-2">
						<input type="number" step="0.01" class="form-control input-sm"
							   name="spec[age_min][]" placeholder="<?php echo _l('lims_analysis_ref_age_from'); ?>">
					  </div>
					  <div class="col-md-2">
						<input type="number" step="0.01" class="form-control input-sm"
							   name="spec[age_max][]" placeholder="<?php echo _l('lims_analysis_ref_age_to'); ?>">
					  </div>
					  <div class="col-md-2">
						<input type="number" step="0.000001" class="form-control input-sm"
							   name="spec[critical_low][]" placeholder="<?php echo _l('lims_analysis_ref_critical_low'); ?>">
					  </div>
					  <div class="col-md-2">
						<div class="row">
						  <div class="col-xs-6">
							<input type="number" step="0.000001" class="form-control input-sm"
								   name="spec[ref_low][]" placeholder="<?php echo _l('lims_analysis_ref_normal_low'); ?>">
						  </div>
						  <div class="col-xs-6">
							<input type="number" step="0.000001" class="form-control input-sm"
								   name="spec[ref_high][]" placeholder="<?php echo _l('lims_analysis_ref_normal_high'); ?>">
						  </div>
						</div>
					  </div>
					  <div class="col-md-2">
						<div class="input-group">
						  <input type="number" step="0.000001" class="form-control input-sm"
								 name="spec[critical_high][]" placeholder="<?php echo _l('lims_analysis_ref_critical_high'); ?>">
						  <span class="input-group-btn">
							<button type="button" class="btn btn-danger btn-sm js-remove-ref-row">
							  <i class="fa fa-times"></i>
							</button>
						  </span>
						</div>
					  </div>
					</div>
				  <?php endif; ?>
				</div>
			  </div>
			</div>
     
	
          <hr/>

          <!-- ====== Billing (auto Item) ====== -->
          <h5 class="mbot10"><?php echo _l('billing') ?: 'Billing'; ?></h5>
          <p class="text-muted"><?php echo _l('lims_billing_note'); ?></p>

          <!-- Rates per active currency -->
          <div class="row mtop10">
            <div class="col-md-12">
              <label class="mbot5"><?php echo _l('price') ?: 'Rate'; ?> (<?php echo _l('currency') ?: 'Currency'; ?>)</label>
              <div class="row">
                <?php
                  // εντόπισε default
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
              <small class="help-block text-muted"><?php echo _l('lims_rates_note'); ?></small>
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
                <small class="help-block text-muted"><?php echo _l('lims_unit_note'); ?></small>
              </div>
            </div>

            <!-- Tax 1 -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('tax_1') ?: 'Tax 1'; ?></label>
                <?php $tax1sel = isset($item->taxid) ? (int)$item->taxid : null; ?>
                <select name="item_tax" class="form-control selectpicker" data-live_search="true" data-size="10">
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
                <select name="item_tax2" class="form-control selectpicker" data-live_search="true" data-size="10">
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

          <div class="row">
            <!-- Long Description -->
            <div class="col-md-12">
              <div class="form-group">
                <label><?php echo _l('long_description') ?: 'Long Description'; ?></label>
                <textarea class="form-control" name="item_long_description" rows="3"
                  placeholder="e.g. LIMS Analysis: <?php echo html_escape($row->name ?? ''); ?>"><?php
                  echo html_escape($item->long_description ?? '');
                ?></textarea>
                <small class="help-block text-muted"><?php echo _l('lims_item_link_desc'); ?></small>
              </div>
            </div>
          </div>

          <hr/>
          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/analyses'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>
        <?php echo form_close(); ?>

        <!-- ===== CULTURES SECTION ===== -->
        <?php if (!empty($culturesBySample)): ?>
          <hr class="mtop30" />
          <h4 class="mbot15 mtop15">
            <?php echo _l('lims_cultures'); ?>
          </h4>

          <div class="table-responsive">
            <table class="table table-striped table-condensed">
              <thead>
                <tr>
                  <th><?php echo _l('lims_test_field_sample'); ?></th>
                  <th><?php echo _l('lims_cultures'); ?></th>
                  <th><?php echo _l('lims_culture_type') ?: 'Culture Type'; ?></th>
                  <th><?php echo _l('lims_sample_type'); ?></th>
                  <th><?php echo _l('lims_method'); ?></th>
                  <th><?php echo _l('lims_tat_hours'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($samples as $sample): ?>
                  <?php
                    $sid       = (int)$sample->id;
                    $sampleCul = isset($culturesBySample[$sid]) ? $culturesBySample[$sid] : [];
                    if (empty($sampleCul)) {
                      continue;
                    }
                  ?>
                  <!-- Sample header row -->
                  <tr class="info">
                    <td colspan="6">
                      <strong><?php echo _l('lims_test_field_sample'); ?>:</strong>
                      <?php echo html_escape($sample->sample_uid); ?>
                      <?php if (!empty($sample->sample_type_name)): ?>
                        <span class="text-muted mleft5">
                          (<?php echo html_escape($sample->sample_type_name); ?>)
                        </span>
                      <?php endif; ?>
                      <?php if (!empty($sample->barcode)): ?>
                        <span class="label label-default mleft5">
                          <i class="fa fa-barcode"></i> <?php echo html_escape($sample->barcode); ?>
                        </span>
                      <?php endif; ?>
                    </td>
                  </tr>

                  <?php foreach ($sampleCul as $cu): ?>
                    <tr>
                      <td><?php echo html_escape($sample->sample_uid); ?></td>
                      <td>
                        <?php echo html_escape($cu->name); ?>
                        <?php if (!empty($cu->code)): ?>
                          <small class="text-muted">(<?php echo html_escape($cu->code); ?>)</small>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php
                          // αν θες και culture_type_name/code μπορείς να τα προσθέσεις στο query του model
                          echo ''; // placeholder
                        ?>
                      </td>
                      <td>
                        <?php echo html_escape($cu->st_name ?? ''); ?>
                        <?php if (!empty($cu->st_code)): ?>
                          <small class="text-muted">(<?php echo html_escape($cu->st_code); ?>)</small>
                        <?php endif; ?>
                      </td>
                      <td><?php echo html_escape($cu->method ?? ''); ?></td>
                      <td>
                        <?php echo !empty($cu->tat_hours) ? (int)$cu->tat_hours : ''; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>

                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <!-- ====== Info section ====== -->
        <hr class="mtop20" />
        <div class="mtop20">
          <h5 class="mbot10"><?php echo _l('lims_analysis_info_title'); ?></h5>
          <p class="text-muted"><?php echo _l('lims_analysis_info_desc'); ?></p>

          <h6 class="mtop15"><?php echo _l('lims_analysis_info_purpose_title'); ?></h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_analysis_info_purpose_1'); ?></li>
            <li>• <?php echo _l('lims_analysis_info_purpose_2'); ?></li>
            <li>• <?php echo _l('lims_analysis_info_purpose_3'); ?></li>
          </ul>

          <h6 class="mtop15"><?php echo _l('lims_analysis_info_guidelines_title'); ?></h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_analysis_info_guideline_1'); ?></li>
            <li>• <?php echo _l('lims_analysis_info_guideline_2'); ?></li>
            <li>• <?php echo _l('lims_analysis_info_guideline_3'); ?></li>
            <li>• <?php echo _l('lims_analysis_info_guideline_4'); ?></li>
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

    var $rt = $('select[name="result_type"]');
    var $optsRow = $('#select-options-row');

    function toggleSelectOpts() {
      var v = $rt.val();
      if (v === 'select') {
        $optsRow.show();
      } else {
        $optsRow.hide();
      }
    }

    // για bootstrap-select
    $rt.on('changed.bs.select', toggleSelectOpts);
    // fallback
    $rt.on('change', toggleSelectOpts);

    toggleSelectOpts(); // αρχικό state
  });
    // Toggle select-values block
  var $rt = $('select[name="result_type"]');
  function toggleSelectValues() {
    if ($rt.val() === 'select') {
      $('#select-values-block').slideDown();
    } else {
      $('#select-values-block').slideUp();
    }
  }
  $rt.on('change', toggleSelectValues);
  toggleSelectValues();

  // Add/remove select value rows
  $(document).on('click', '.js-add-select-value', function(e){
    e.preventDefault();
    var $row = $(this).closest('.select-value-row');
    var $clone = $row.clone();
    $clone.find('input').val('');
    $('#select-values-rows').append($clone);
  });
  $(document).on('click', '.js-remove-select-value', function(e){
    e.preventDefault();
    var $rows = $('#select-values-rows .select-value-row');
    if ($rows.length <= 1) {
      // άφησε πάντα τουλάχιστον 1
      $rows.first().find('input').val('');
      return;
    }
    $(this).closest('.select-value-row').remove();
  });

  // Add/remove reference range rows
  $(document).on('click', '.js-add-ref-row', function(e){
    e.preventDefault();
    var $last = $('#ref-range-rows .ref-row:last');
    var $clone = $last.clone();
    $clone.find('input').val('');
    $('#ref-range-rows').append($clone);
  });
  $(document).on('click', '.js-remove-ref-row', function(e){
    e.preventDefault();
    var $rows = $('#ref-range-rows .ref-row');
    if ($rows.length <= 1) {
      // κράτα τουλάχιστον μία
      $rows.first().find('input').val('');
      return;
    }
    $(this).closest('.ref-row').remove();
  });

})(jQuery);
</script>

