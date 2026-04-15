<!-- lims/views/admin/orders/create_step1.php -->

<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$CI = &get_instance();
$CI->load->model('lims/subjects_model');
$subject_code = $CI->subjects_model->generate_internal_code();

// Default χώρα (προσπάθησε πρώτα από ρυθμίσεις πελατών, μετά από εταιρεία)
$default_country = get_option('customer_default_country');
if (!$default_country) {
    $default_country = get_option('invoice_country');
}
?>

<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="panel_s">
			<div class="panel-body">
				<h4 class="mtop5"><?php echo _l('lims_new_order'); ?> — Step 1/2</h4>
				<?php echo form_open(admin_url('lims/orders/create')); ?>
				<input type="hidden" name="action" value="save_step1">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">
								<?php echo _l('lims_entry_mode'); ?>
							</label>
							<div>
								<?php
									$entry_mode = isset($draft['entry_mode']) ? $draft['entry_mode'] : 'tests';
								?>
								<label class="radio-inline">
									<input type="radio" name="entry_mode" value="tests"
										<?php echo $entry_mode === 'tests' ? 'checked' : ''; ?>>
									<?php echo _l('lims_entry_mode_tests'); ?>
								</label>
								&nbsp;&nbsp;
								<label class="radio-inline">
									<input type="radio" name="entry_mode" value="samples"
										<?php echo $entry_mode === 'samples' ? 'checked' : ''; ?>>
									<?php echo _l('lims_entry_mode_samples'); ?>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
					  <div class="form-group">
						<label><?php echo _l('lims_subject'); ?></label>
						<?php
							$selected_subject_id = 0;
							if (isset($draft['subject_id'])) {
								$selected_subject_id = (int)$draft['subject_id'];
							} elseif ($this->input->post('subject_id')) {
								$selected_subject_id = (int)$this->input->post('subject_id');
							}
						?>
						<div class="input-group">
							<select name="subject_id" id="subject_id" class="form-control selectpicker" data-live-search="true" required>
								<option value=""></option>
								<?php foreach($subjects as $s): ?>
								<?php
									$sel = ($selected_subject_id === (int)$s->id) ? 'selected' : ''; 
								  // === ΚΥΡΙΑ ΓΡΑΜΜΗ (όνομα subject) ===
								  // Προσαρμόζεις τα πεδία σύμφωνα με το table σου.
								  $main = '';

								  // Αν έχεις firstname/lastname:
								  if (isset($s->last_name) || isset($s->first_name)) {
									$main = trim(($s->last_name ?? '') . ' ' . ($s->first_name ?? ''));
								  }

								  // Εναλλακτικά display_name / name / code
								  if ($main === '') {
									if (!empty($s->subject_name)) {
									  $main = $s->subject_name;
									} elseif (!empty($s->subject_name)) {
									  $main = $s->subject_name;
									} elseif (!empty($s->internal_code)) {
									  $main = $s->internal_code;
									} else {
									  $main = 'Subject';
									}
								  }

								  // === ΔΕΥΤΕΡΗ ΓΡΑΜΜΗ (subtext) ===
								  // TODO: προσαρμόζεις τα ονόματα στηλών στον δικό σου πίνακα:
								  $social_insur = $s->social_insurance_no ?? '';     // π.χ. column id_passport ή id_number
								  $id_passport  = $s->id_number ?? '';     // π.χ. column id_passport ή id_number
								  $internal     = $s->internal_code   ?? $s->code      ?? '';     // π.χ. column internal_code ή code
								  $email        = $s->email           ?? '';                      // column email

								  $subParts = [];

								  if ($social_insur !== '') {
									$subParts[] = 'S/I: ' . $social_insur;
								  }
								  if ($id_passport !== '') {
									$subParts[] = 'ID: ' . $id_passport;
								  }
								  if ($internal !== '') {
									$subParts[] = 'Code: ' . $internal;
								  }
								  if ($email !== '') {
									$subParts[] = $email;
								  }

								  // Καλό είναι να βλέπεις και το numeric ID κάπου:
								  $subParts[] = 'ID: ' . (int)$s->id;

								  $subtext = implode(' • ', $subParts);

								  // === TOKENS για search ===
								  $tokensParts = [
									$main,
									$id_passport,
									$internal,
									$email,
									$social_insur,
									(string)$s->id,
								  ];
								  $tokensParts = array_filter($tokensParts, function($v){
									return (string)$v !== '';
								  });
								  $tokens = implode(' ', $tokensParts);
								?>
								<option
								  value="<?php echo (int)$s->id; ?>"
								  data-subtext="<?php echo html_escape($subtext); ?>"
								  data-tokens="<?php echo html_escape($tokens); ?>" <?php echo $sel; ?>
								>
								  <?php echo html_escape($main); ?>
								</option>
							  <?php endforeach; ?>
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lims-subject-quick">
									<i class="fa fa-user-plus"></i>
								</button>
							</span>
						</div>
					  </div>
					</div>

				<div class="col-md-3">
				  <div class="form-group">
					<label><?php echo _l('lims_contract'); ?></label>
					<select name="contract_id" id="contract_id" class="form-control selectpicker" data-live-search="true">
						  <option value=""></option>
						  <?php foreach($contracts as $k): ?>
						  <option value="<?php echo (int)$k->id; ?>"><?php echo html_escape($k->name); ?></option>
						  <?php endforeach; ?>
					</select>
					<small class="help-block text-muted"><?php echo _l('lims_contract_hint'); ?></small>
				  </div>
				</div>
				<div class="col-md-3">
					<?php
						$priorityCurrent = isset($order->priority) ? (int)$order->priority : 0;
						$priorityOpts = [];
						foreach (lims_priority_options() as $val => $label) {
							$priorityOpts[] = ['id' => $val, 'name' => $label];
						}
					?>
					<div class="form-group">
						<label for="priority"><?php echo _l('priority'); ?></label>
						<?php echo render_select(
							'priority',
							$priorityOpts,
							['id','name'],
							'',
							$priorityCurrent,
							[],
							[],
							'',
							'',
							false
						); ?>
					</div>

				</div>

			  </div>

			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label><?php echo _l('lims_partner') ?: 'Partner'; ?> (<?php echo _l('optional') ?: 'optional'; ?>)</label>
					<?php
						$selected_partner_id = 0;
						if (isset($draft['partner_id'])) {
							$selected_partner_id = (int)$draft['partner_id'];
						} elseif ($this->input->post('partner_id')) {
							$selected_partner_id = (int)$this->input->post('partner_id');
						}
						$partners = $partners ?? [];

					?>
					<select name="partner_id" id="partner_id" class="form-control selectpicker" data-live-search="true" data-size="10">
						<option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
						<?php foreach ($partners as $p) :
							$pid = is_object($p) ? $p->id : $p['id'];
							$pname = is_object($p) ? $p->name : $p['name'];
						?>
							<option value="<?php echo (int)$pid; ?>"><?php echo htmlspecialchars($pname); ?></option>
						<?php endforeach; ?>

					</select>
					<small class="help-block text-muted"><?php echo _l('lims_partner_order_hint') ?: 'If selected, samples mode is enforced and the order will be queued for sync to the partner.'; ?></small>
				  </div>
				</div>
			  </div>

			  <div class="row">

				<div class="col-md-3">
				  <div class="form-group">
					<label><?php echo _l('due_date'); ?></label>
					<input type="datetime-local" name="due_at" class="form-control">
				  </div>
				</div>
				<div class="col-md-9">
				  <div class="form-group">
					<label><?php echo _l('notes'); ?></label>
					<textarea name="notes" class="form-control" rows="2"></textarea>
				  </div>
				</div>
			  </div>

			  <div class="text-right">
				<button class="btn btn-primary"><?php echo _l('next'); ?></button>
				<a class="btn btn-default" href="<?php echo admin_url('lims/orders'); ?>"><?php echo _l('cancel'); ?></a>
			  </div>

			  <?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-lims-subject-quick" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <?php echo form_open(admin_url('lims/subjects/ajax_quick_create'), ['id' => 'form-lims-subject-quick']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <i class="fa fa-user-plus"></i>
          <?php echo _l('lims_quick_add_subject'); ?>
        </h4>
      </div>

      <div class="modal-body">

        <!-- CUSTOMER (ΠΡΟΑΙΡΕΤΙΚΟ) -->
		<div class="row">
		  <div class="col-md-12">
			<div class="form-group">
			  <label for="subject_client_id">
				<?php echo _l('lims_subject_client'); ?>
			  </label>

			  <select name="client_id"
					  id="subject_client_id"
					  class="form-control selectpicker"
					  data-live-search="true">
				<option value=""><?php echo _l('lims_subject_client_select'); ?></option>

				<?php if (!empty($clients)) : ?>
				  <?php foreach ($clients as $cl): ?>
					<?php
					  // Support both stdClass και array, για ασφάλεια
					 $cid   = isset($cl->userid) ? (int) $cl->userid : ((is_array($cl) && isset($cl['userid'])) ? (int) $cl['userid'] : 0);
					 $cname = isset($cl->company) ? $cl->company : ((is_array($cl) && isset($cl['company'])) ? $cl['company'] : '');

					  if (!$cid) { continue; }
					?>
					<option value="<?php echo $cid; ?>">
					  <?php echo html_escape($cname); ?>
					</option>
				  <?php endforeach; ?>
				<?php endif; ?>
			  </select>

			  <small class="help-block text-muted">
				<?php echo _l('lims_subject_client_optional_help'); ?>
			  </small>
			</div>
		  </div>
		</div>

        <!-- SUBJECT TYPE (ΥΠΟΧΡΕΩΤΙΚΟ) -->
        <div class="row">
          <div class="col-md-12">
            <?php
              $subject_type_options = [
                  ['id' => 'patient',    'name' => _l('lims_subject_type_patient')],
                  ['id' => 'doctor',     'name' => _l('lims_subject_type_doctor')],
                  ['id' => 'lab',        'name' => _l('lims_subject_type_lab')],
                  ['id' => 'farm',       'name' => _l('lims_subject_type_farm')],
                  ['id' => 'restaurant', 'name' => _l('lims_subject_type_restaurant')],
                  ['id' => 'other',      'name' => _l('lims_subject_type_other')],
              ];
              echo render_select(
                  'subject_type',
                  $subject_type_options,
                  ['id', 'name'],
                  'lims_subject_type',
                  'patient',
                  [],
                  [],
                  '',
                  '',
                  true
              );
            ?>
          </div>
        </div>

        <!-- NAME + INTERNAL CODE (auto) -->
        <div class="row">
          <div class="col-md-8">
            <?php echo render_input('subject_name', 'lims_subject_name'); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_input(
                'internal_code',
                'lims_subject_internal_code',
                $subject_code,
                'text',
                ['readonly' => true]
            ); ?>
          </div>
        </div>

        <!-- FIRST / LAST NAME -->
        <div class="row">
          <div class="col-md-6">
            <?php echo render_input('first_name', 'client_firstname'); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_input('last_name', 'client_lastname'); ?>
          </div>
        </div>

        <!-- ID + DOB -->
        <div class="row">
          <div class="col-md-6">
            <?php echo render_input('id_number', 'lims_subject_id_number'); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_date_input('date_of_birth', 'lims_subject_dob'); ?>
          </div>
        </div>

        <!-- PHONE + EMAIL -->
        <div class="row">
          <div class="col-md-6">
            <?php echo render_input('phone', 'client_phone'); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_input('email', 'client_email'); ?>
          </div>
        </div>

        <!-- ADDRESS + CITY + ZIP (city & zip required) -->
        <div class="row">
          <div class="col-md-6">
            <?php echo render_input('address', 'client_address'); ?>
          </div>
          <div class="col-md-3">
            <?php echo render_input('city', 'lims_subject_city', '', 'text', ['required' => true]); ?>
          </div>
          <div class="col-md-3">
            <?php echo render_input('zip', 'lims_subject_zip', '', 'text', ['required' => true]); ?>
          </div>
        </div>

        <!-- COUNTRY (hidden default) -->
        <?php echo form_hidden('country', (int)$default_country); ?>

      </div><!-- /.modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo _l('close'); ?>
        </button>
        <button type="submit" class="btn btn-primary">
          <?php echo _l('submit'); ?>
        </button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<?php init_tail(); ?>
<script>
(function($){
  "use strict";

  function forcePartnerSamplesMode(){
    var hasPartner = $.trim($('#partner_id').val() || '') !== '';
    if (hasPartner) {
      $('input[name="entry_mode"][value="samples"]').prop('checked', true);
      $('input[name="entry_mode"][value="tests"]').prop('disabled', true);
    } else {
      $('input[name="entry_mode"][value="tests"]').prop('disabled', false);
    }
    if ($.fn.selectpicker) { $('.selectpicker').selectpicker('refresh'); }
  }

  $(document).on('changed.bs.select', '#partner_id', forcePartnerSamplesMode);
  $(document).on('change', '#partner_id', forcePartnerSamplesMode);
  forcePartnerSamplesMode();


  // QUICK SUBJECT CREATE
  $('#form-lims-subject-quick').on('submit', function(e){
    e.preventDefault();

    var $form = $(this);
    var $btn  = $form.find('button[type="submit"]');

    var subjectType = $.trim($form.find('select[name="subject_type"]').val());
    var city        = $.trim($form.find('input[name="city"]').val());
    var zip         = $.trim($form.find('input[name="zip"]').val());

    // Front-end validation (Customer ΔΕΝ είναι υποχρεωτικό)
    if (!subjectType) {
      alert('<?php echo _l('lims_subject_type_required_alert'); ?>');
      return;
    }
    if (!city) {
      alert('<?php echo _l('lims_subject_city_required'); ?>');
      return;
    }
    if (!zip) {
      alert('<?php echo _l('lims_subject_zip_required'); ?>');
      return;
    }

    $btn.prop('disabled', true)
        .append(' <i class="fa fa-spinner fa-spin"></i>');

    $.post($form.attr('action'), $form.serialize())
      .done(function(resp){
        try { resp = JSON.parse(resp); } catch(e){}

        if (resp && resp.success) {
          // ενημέρωση του subject select στο main form
          var $select = $('#subject_id');
          if ($select.length === 0) {
            $select = $('select[name="subject_id"]');
          }

          var textLabel = resp.display_name || resp.name || ('#' + resp.id);

          var opt = $('<option/>', {
            value: resp.id,
            text:  textLabel,
            selected: true
          });

          if (resp.subtext) {
            opt.attr('data-subtext', resp.subtext);
          }

          $select.append(opt);

          if ($select.hasClass('selectpicker')) {
            $select.selectpicker('refresh');
          }

          $('#modal-lims-subject-quick').modal('hide');
          $form[0].reset();
        } else {
          alert('<?php echo _l('lims_quick_subject_create_error'); ?>');
        }
      })
      .fail(function(){
        alert('<?php echo _l('lims_quick_subject_create_error'); ?>');
      })
      .always(function(){
        $btn.prop('disabled', false)
            .find('.fa-spinner').remove();
      });
  });

  // QUICK CONTRACT CREATE – όπως πριν
  $('#form-lims-contract-quick').on('submit', function(e){
    e.preventDefault();
    var $form = $(this);
    var $btn  = $form.find('button[type="submit"]');

    $btn.prop('disabled', true).append(' <i class="fa fa-spinner fa-spin"></i>');

    $.post($form.attr('action'), $form.serialize())
      .done(function(resp){
        try { resp = JSON.parse(resp); } catch(e){}

        if (resp && resp.success) {
          var $select = $('#contract_id');
          if ($select.length === 0) {
            $select = $('select[name="contract_id"]');
          }

          var opt = $('<option/>', {
            value: resp.id,
            text:  resp.name,
            selected: true
          });

          $select.append(opt);
          if ($select.hasClass('selectpicker')) {
            $select.selectpicker('refresh');
          }

          $('#modal-lims-contract-quick').modal('hide');
          $form[0].reset();
        } else {
          alert('<?php echo _l('problem_creating') ?: 'Problem creating record'; ?>');
        }
      })
      .fail(function(){
        alert('<?php echo _l('problem_creating') ?: 'Problem creating record'; ?>');
      })
      .always(function(){
        $btn.prop('disabled', false)
            .find('.fa-spinner').remove();
      });
  });

})(jQuery);
</script>
