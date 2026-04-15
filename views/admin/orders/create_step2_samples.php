<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="no-margin">
              <?php echo _l('lims_new_order'); ?> – <?php echo _l('lims_entry_mode_samples') ?: 'Order by Samples'; ?>
            </h4>
            <hr />

            <?php
            // Μικρό summary από το draft (subject/client)
            $subject = null;
            if (!empty($draft['subject_id'])) {
                $subject = $this->db
                    ->where('id', (int)$draft['subject_id'])
                    ->get(db_prefix().'lims_subjects')
                    ->row();
            }
            ?>
            <?php if ($subject) { ?>
              <div class="mbot15">
                <strong><?php echo _l('lims_subject'); ?>:</strong>
                <?php echo html_escape($subject->subject_name ?: trim(($subject->first_name.' '.$subject->last_name))); ?>
                <?php if (!empty($subject->internal_code)) { ?>
                  <span class="text-muted"> (<?php echo html_escape($subject->internal_code); ?>)</span>
                <?php } ?>
              </div>
            <?php } ?>

            <?php echo form_open(admin_url('lims/orders/create?step=2'), ['id' => 'lims-order-create-step2-samples']); ?>
              <input type="hidden" name="action" value="save_submit" />

              <div class="table-responsive" style="min-height: 50vh;">
                <table class="table table-bordered" id="samples-table">
                  <thead>
                    <tr>
                      <th style="width:60px;">#</th>
                      <th style="width:180px;"><?php echo _l('lims_sample_type') ?: 'Sample type'; ?></th>
                      <th><?php echo _l('lims_panels') ?: 'Panels'; ?></th>
                      <th><?php echo _l('lims_analyses') ?: 'Analyses'; ?></th>
                      <th><?php echo _l('lims_cultures') ?: 'Cultures'; ?></th>
                      <th style="width:60px;"></th>
                    </tr>
                  </thead>
                  <tbody id="sample-rows">
                    <!-- Οι γραμμές θα προστεθούν δυναμικά από JS, με βάση το template -->
                  </tbody>
                </table>
              </div>
				<!-- Λίγος χώρος πριν το κουμπί -->
				<div class="clearfix mtop20"></div>
              <div class="mtop15">
                <button type="button" class="btn btn-default" id="add-sample-row">
                  <i class="fa fa-plus"></i>
                  <?php echo _l('lims_add_sample') ?: 'Add sample'; ?>
                </button>
              </div>

              <hr />

              <div class="text-right">
                <a href="<?php echo admin_url('lims/orders/create'); ?>" class="btn btn-default">
                  <?php echo _l('back'); ?>
                </a>
                <button type="submit" class="btn btn-primary">
                  <?php echo _l('submit'); ?>
                </button>
              </div>

            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TEMPLATE για νέο sample (2 σειρές: main row + notes row) -->
<script type="text/template" id="sample-row-template">
<tr class="sample-row" data-index="__INDEX__">
  <td class="sample-index text-center vtop">
    <span>__ROWNUM__</span>
  </td>

  <!-- Sample type -->
  <td class="vtop">
    <select name="samples[__INDEX__][sample_type_id]" class="form-control">
      <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
      <?php foreach ($sample_types as $st) { ?>
        <option value="<?php echo (int)$st->id; ?>">
          <?php echo html_escape($st->name . ($st->code ? ' ('.$st->code.')' : '')); ?>
        </option>
      <?php } ?>
    </select>
  </td>

    <!-- Panels -->
	  <td class="vtop">
		<select
		  name="samples[__INDEX__][panels][]"
		  class="selectpicker"
		  multiple
		  data-width="100%"
		  data-live-search="true"
		  data-container="body"
		  data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
		>
		  <?php foreach ($panels as $p) { ?>
			<option value="<?php echo (int)$p->id; ?>">
			  <?php echo html_escape($p->name . ($p->code ? ' ('.$p->code.')' : '')); ?>
			</option>
		  <?php } ?>
		</select>
	  </td>

	  <!-- Analyses -->
	  <td class="vtop">
		<select
		  name="samples[__INDEX__][analyses][]"
		  class="selectpicker"
		  multiple
		  data-width="100%"
		  data-live-search="true"
		  data-container="body"
		  data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
		>
		  <?php foreach ($analyses as $a) { ?>
			<option value="<?php echo (int)$a->id; ?>">
			  <?php echo html_escape($a->name . ($a->code ? ' ('.$a->code.')' : '')); ?>
			</option>
		  <?php } ?>
		</select>
	  </td>

	  <!-- Cultures -->
	  <td class="vtop">
		<select
		  name="samples[__INDEX__][cultures][]"
		  class="selectpicker"
		  multiple
		  data-width="100%"
		  data-live-search="true"
		  data-container="body"
		  data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
		>
		  <?php foreach ($cultures as $c) { ?>
			<option value="<?php echo (int)$c->id; ?>">
			  <?php echo html_escape($c->name . ($c->code ? ' ('.$c->code.')' : '')); ?>
			</option>
		  <?php } ?>
		</select>
	  </td>


	<!-- Actions -->
	<td class="text-center vtop">
		<button type="button" class="btn btn-danger remove-sample-row" title="<?php echo _l('delete'); ?>">
			<i class="fa fa-trash"></i>
		</button>
	</td>
</tr>

<!-- Notes row (κάτω από τα selects, full width) -->
<tr class="sample-notes-row" data-index="__INDEX__">
  <td></td>
  <td colspan="4">
    <textarea
      name="samples[__INDEX__][notes]"
      class="form-control"
      rows="2"
      placeholder="<?php echo _l('lims_notes') ?: 'Notes for this sample'; ?>"></textarea>
  </td>
  <td></td>
</tr>
</script>

<?php init_tail(); ?>
<script>
  (function($) {
    "use strict";

    var sampleIndex = 0;

    function addSampleRow() {
	  var tpl = $('#sample-row-template').html();
	  if (!tpl) { return; }

	  sampleIndex++;

	  var rownum = $('#sample-rows').find('tr.sample-row').length + 1;
	  tpl = tpl.replace(/__INDEX__/g, sampleIndex);
	  tpl = tpl.replace(/__ROWNUM__/g, rownum);

	  $('#sample-rows').append(tpl);

	  // init/refresh για τα νέα selectpicker
	  var $newRows = $('#sample-rows').find('tr[data-index="' + sampleIndex + '"]');
	  var $selects = $newRows.find('select.selectpicker');

	  if (typeof appSelectPicker !== 'undefined') {
		appSelectPicker($selects);
	  } else if ($.fn.selectpicker) {
		$selects.selectpicker('refresh');
	  }
	}


    $(function() {
      // Αρχικά τουλάχιστον 1 sample
      addSampleRow();

      $('#add-sample-row').on('click', function() {
        addSampleRow();
      });

      // Remove sample (και τις 2 σειρές)
      $('#sample-rows').on('click', '.remove-sample-row', function() {
        var $tr = $(this).closest('tr.sample-row');
        var idx = $tr.data('index');
        $tr.remove();
        $('#sample-rows').find('tr.sample-notes-row[data-index="'+idx+'"]').remove();

        // Ανανεώνουμε την αρίθμηση (#) μόνο για εμφάνιση
        $('#sample-rows').find('tr.sample-row').each(function(i){
          $(this).find('.sample-index span').text(i+1);
        });
      });
    });
  })(jQuery);
</script>

