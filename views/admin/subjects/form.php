<?php defined('BASEPATH') or exit('No direct script access allowed'); 
//lims/views/admin/subjects/form.php
?>
<?php init_head(); ?>

<?php
$subject = isset($subject) ? $subject : null;
$clients = isset($clients) ? $clients : [];

$subject_id = $subject ? (int)$subject->id : null;

$action = $subject_id
    ? admin_url('lims/subjects/create/' . $subject_id)
    : admin_url('lims/subjects/create');

$active = ($subject && isset($subject->active))
    ? (int)$subject->active === 1
    : true;

// subject type options
$subject_type = $subject->subject_type ?? 'patient';
$subject_type_options = [
    ['id' => 'patient',    'name' => 'Patient'],
    ['id' => 'doctor',     'name' => 'Doctor'],
    ['id' => 'lab',        'name' => 'Laboratory'],
    ['id' => 'farm',       'name' => 'Farm'],
    ['id' => 'restaurant', 'name' => 'Restaurant'],
    ['id' => 'other',      'name' => 'Other'],
];

// clients select options
$client_options = [];
foreach ($clients as $c) {
    $client_options[] = [
        'id'   => (int)$c['userid'],
        'name' => $c['company'] . ' (#' . (int)$c['userid'] . ')',
    ];
}

// countries
$countries = get_all_countries(); // perfex helper

// date of birth
$dob = '';
if ($subject && !empty($subject->date_of_birth) && $subject->date_of_birth != '0000-00-00') {
    $dob = _d($subject->date_of_birth);
}

// default customer mode
$customer_mode = 'existing';
if (!$subject || empty($subject->client_id)) {
    $customer_mode = 'none';
}
?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">

            <div class="row">
              <div class="col-md-8">
                <h4 class="mtop5">
                  <?php echo $subject_id
                    ? (_l('lims_subject_edit') ?: 'Edit Subject')
                    : (_l('lims_subject_new') ?: 'New Subject'); ?>
                </h4>
              </div>
              <div class="col-md-4 text-right">
                <a href="<?php echo $subject_id
                    ? admin_url('lims/subjects/view/' . $subject_id)
                    : admin_url('lims/subjects'); ?>"
                   class="btn btn-default mtop10">
                  <i class="fa fa-arrow-left"></i> <?php echo _l('back') ?: 'Back'; ?>
                </a>
              </div>
            </div>

            <hr class="hr-panel-heading" />

            <?php echo form_open($action, ['id' => 'subject-form']); ?>

            <div class="row">
              <div class="col-md-5">
                <!-- SUBJECT TYPE -->
                <?php
                echo render_select(
                    'subject_type',
                    $subject_type_options,
                    ['id', 'name'],
                    'lims_subject_type',
                    $subject_type,
                    [],
                    [],
                    '',
                    '',
                    false
                );
				
                // Language dropdown: δείχνουμε ΜΟΝΟ active languages από Localization.
				// Αν είναι <= 1, κρύβουμε dropdown αλλά στέλνουμε hidden value για να μη χαθεί σε update.
				$languages = (isset($languages) && is_array($languages)) ? $languages : [];

				$default_lang  = get_option('active_language') ?: 'english';
				$selected_lang = $subject->language ?? $default_lang;

				if (count($languages) > 1) {
					echo render_select(
						'language',
						$languages,
						['id', 'name'],
						_l('language') ?: 'Language',
						$selected_lang
					);
				} else {
					echo form_hidden('language', $selected_lang);
				}
                ?>
                <!-- SUBJECT NAME (non-patient) -->
                <div id="subject-name-wrapper" class="<?php echo $subject_type === 'patient' ? 'hide' : ''; ?>">
                  <?php
                  echo render_input(
                      'subject_name',
                      'lims_subject_name',
                      $subject->subject_name ?? ''
                  );
                  ?>
                </div>

                <!-- PATIENT FIRST/LAST NAME -->
                <div id="subject-patient-name-wrapper" class="<?php echo $subject_type === 'patient' ? '' : 'hide'; ?>">
                  <?php
                  echo render_input(
                      'first_name',
                      'client_firstname',
                      $subject->first_name ?? ''
                  );
                  echo render_input(
                      'last_name',
                      'client_lastname',
                      $subject->last_name ?? ''
                  );
                  ?>
                </div>

                <?php				
				// Για νέο subject (create) θα είναι κενό, και θα γεμίσει
				// όταν ξαναμπούμε στο edit μετά το πρώτο save.
				echo render_input(
					'internal_code',              // name = internal_code
					'lims_subject_code',          // lang key
					$internal_code,
					'text',
					['readonly' => true]
				);
				?>

                <div class="checkbox checkbox-primary mtop10">
                  <input type="checkbox" name="active" id="subject_active"
                         value="1" <?php echo $active ? 'checked' : ''; ?>>
                  <label for="subject_active">
                    <?php echo _l('active'); ?>
                  </label>
                </div>
              </div>

              <div class="col-md-7">
                <!-- CUSTOMER LINK OPTIONS -->
                <div class="form-group">
                  <label><?php echo _l('lims_subject_customer_link') ?: 'Customer link'; ?></label>

                  <div class="radio radio-primary">
                    <input type="radio" name="customer_mode" id="cust_mode_existing"
                           value="existing" <?php echo $customer_mode === 'existing' ? 'checked' : ''; ?>>
                    <label for="cust_mode_existing">
                      <?php echo _l('lims_subject_customer_existing') ?: 'Link to existing customer'; ?>
                    </label>
                  </div>

                  <div class="radio radio-primary">
                    <input type="radio" name="customer_mode" id="cust_mode_new" value="new">
                    <label for="cust_mode_new">
                      <?php echo _l('lims_subject_customer_new') ?: 'Create new customer'; ?>
                    </label>
                  </div>

                  <div class="radio radio-primary">
                    <input type="radio" name="customer_mode" id="cust_mode_none"
                           value="none" <?php echo $customer_mode === 'none' ? 'checked' : ''; ?>>
                    <label for="cust_mode_none">
                      <?php echo _l('lims_subject_customer_none') ?: 'No customer (for now)'; ?>
                    </label>
                  </div>
                </div>

                <!-- EXISTING CUSTOMER SELECT -->
				<div id="subject-existing-client" class="<?php echo $customer_mode === 'existing' ? '' : 'hide'; ?>">
				  <?php
				  echo render_select(
					  'client_id',
					  $client_options,
					  ['id', 'name'],
					  'client',
					  $subject->client_id ?? ''
				  );
				  ?>
				  <a href="#"
					 class="text-muted mtop5 inline-block"
					 id="btn-copy-existing-customer">
					 <i class="fa fa-copy"></i>
					 <?php echo _l('lims_subject_same_as_customer_info') ?: 'Same as customer info'; ?>
				  </a>
				</div>


                
              </div>
            </div>
			<!-- NEW CUSTOMER FIELDS -->
                <div id="subject-new-client" class="<?php echo $customer_mode === 'new' ? '' : 'hide'; ?>">
					<div class="row mtop10">
						<div class="col-md-12">
						  <a href="#"
							 class="text-muted"
							 id="btn-copy-new-customer">
							 <i class="fa fa-copy"></i>
							 <?php echo _l('lims_subject_same_as_customer_info') ?: 'Same as customer info'; ?>
						  </a>
						</div>
					</div>
                  <div class="row">
                    <div class="col-md-4">
                      <?php
                      echo render_input(
                          'new_customer_company',
                          'client_company',
                          ''
                      );?>
					  
                    </div>
                    <div class="col-md-2">
                      <?php echo render_input(
                          'new_customer_vat',
                          'client_vat_number',
                          ''
                      );
                      ?>
                    </div>
                    <div class="col-md-2">
                      <?php
                      echo render_input(
                          'new_customer_phone',
                          'clients_phone',
                          ''
                      );?>
					  
                    </div>
                    <div class="col-md-4">
                      <?php echo render_input(
                          'new_customer_email',
                          'clients_email',
                          ''
                      );
                      ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <?php
                      echo render_textarea(
                          'new_customer_address',
                          'client_address',
                          '',
                          ['rows' => 2]
                      );
                      ?>
                    </div>
                    <div class="col-md-3">
                      <?php
                      echo render_input(
                          'new_customer_city',
                          'client_city',
                          ''
                      );?>
                    </div>
                    <div class="col-md-2">
                      <?php 
						  echo render_input(
							  'new_customer_zip',
							  'client_zip',
							  ''
						  );
                      ?>
                    </div>
					
                    <div class="col-md-3">
                      <?php
                      echo render_select(
                          'new_customer_country',
                          $countries,
                          ['country_id', ['short_name']],
                          'client_country',
                          ''
                      );
                      ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <h4 class="mtop30 text-right"><?php echo _l('customer_admins'); ?></h4>
                    </div>
                    <div class="col-md-4">
                      <?php
                      echo render_input(
                          'new_customer_contact_firstname',
                          'client_firstname',
                          ''
                      );
                      ?>
                    </div>
                    <div class="col-md-4">
                      <?php
                      echo render_input(
                          'new_customer_contact_lastname',
                          'client_lastname',
                          ''
                      );
                      ?>
                    </div>
                  </div>
                </div>
            <hr />

            <!-- PATIENT-SPECIFIC INFO -->
            <div id="subject-patient-extra" class="<?php echo $subject_type === 'patient' ? '' : 'hide'; ?>">
              <div class="row">
                <div class="col-md-3">
                  <?php
                  echo render_input(
                      'id_number',
                      'lims_subject_id_number',
                      $subject->id_number ?? ''
                  );
                  ?>
                </div>
                <div class="col-md-3">
                  <?php
                  echo render_input(
                      'nationality',
                      'lims_subject_nationality',
                      $subject->nationality ?? ''
                  );
                  ?>
                </div>
                <div class="col-md-3">
                  <?php
                  // gender simple select
                  $gender_options = [
                      ['id' => 'male',   'name' => _l('male')   ?: 'Male'],
                      ['id' => 'female', 'name' => _l('female') ?: 'Female'],
                      ['id' => 'other',  'name' => _l('other')  ?: 'Other'],
                  ];
                  echo render_select(
                      'gender',
                      $gender_options,
                      ['id', 'name'],
                      'lims_subject_gender',
                      $subject->gender ?? ''
                  );
                  ?>
                </div>
                <div class="col-md-3">
                  <?php
                  echo render_input(
                      'social_insurance_no',
                      'lims_subject_social_insurance_no',
                      $subject->social_insurance_no ?? ''
                  );
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <?php
                  echo render_date_input(
                      'date_of_birth',
                      'lims_subject_dob',
                      $dob
                  );
                  ?>
                </div>
              </div>

              <hr />
            </div>

            <!-- CONTACT / ADDRESS OF SUBJECT ITSELF -->
            <div class="row">
              <div class="col-md-3">
                <?php
                echo render_input(
                    'phone',
                    'clients_phone',
                    $subject->phone ?? ''
                );
                ?>
              </div>
              <div class="col-md-3">
                <?php
                echo render_input(
                    'email',
                    'clients_email',
                    $subject->email ?? ''
                );
                ?>
              </div>
              <div class="col-md-6">
                <?php
                echo render_textarea(
                    'address',
                    'client_address',
                    $subject->address ?? '',
                    ['rows' => 2]
                );
                ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <?php
                echo render_input(
                    'city',
                    'client_city',
                    $subject->city ?? ''
                );
                ?>
              </div>
              <div class="col-md-3">
                <?php
                echo render_input(
                    'state',
                    'client_state',
                    $subject->state ?? ''
                );
                ?>
              </div>
              <div class="col-md-3">
                <?php
                echo render_input(
                    'zip',
                    'client_zip',
                    $subject->zip ?? ''
                );
                ?>
              </div>
              <div class="col-md-3">
                <?php
                echo render_select(
                    'country',
                    $countries,
                    ['country_id', ['short_name']],
                    'client_country',
                    $subject->country ?? ''
                );
                ?>
              </div>
            </div>

            <div class="row mtop15">
              <div class="col-md-12">
                <?php
                echo render_textarea(
                    'notes',
                    'notes',
                    $subject->notes ?? '',
                    ['rows' => 4]
                );
                ?>
              </div>
            </div>

            <div class="text-right mtop20">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-check"></i> <?php echo _l('save'); ?>
              </button>
            </div>

            <?php echo form_close(); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
(function($){
  "use strict";

  function toggleSubjectTypeFields() {
    var type = $('#subject_type').val();
    if (type === 'patient') {
      $('#subject-name-wrapper').addClass('hide');
      $('#subject-patient-name-wrapper').removeClass('hide');
      $('#subject-patient-extra').removeClass('hide');
    } else {
      $('#subject-name-wrapper').removeClass('hide');
      $('#subject-patient-name-wrapper').addClass('hide');
      $('#subject-patient-extra').addClass('hide');
    }
  }

  function toggleCustomerMode() {
    var mode = $('input[name="customer_mode"]:checked').val();
    if (mode === 'existing') {
      $('#subject-existing-client').removeClass('hide');
      $('#subject-new-client').addClass('hide');
    } else if (mode === 'new') {
      $('#subject-existing-client').addClass('hide');
      $('#subject-new-client').removeClass('hide');
    } else {
      $('#subject-existing-client').addClass('hide');
      $('#subject-new-client').addClass('hide');
    }
  }

  $(function(){
    if (app && app.datepicker) {
      app.datepicker();
    }

    $('#subject_type').on('change', toggleSubjectTypeFields);
    $('input[name="customer_mode"]').on('change', toggleCustomerMode);

    toggleSubjectTypeFields();
    toggleCustomerMode();

    // -------------------------------------------------------
    // 1) Same as NEW customer info
    // -------------------------------------------------------
    $('#btn-copy-new-customer').on('click', function(e){
      e.preventDefault();

      var type = $('#subject_type').val();

      var company    = $('input[name="new_customer_company"]').val() || '';
      var firstNameC = $('input[name="new_customer_contact_firstname"]').val() || '';
      var lastNameC  = $('input[name="new_customer_contact_lastname"]').val() || '';
      var phone      = $('input[name="new_customer_phone"]').val() || '';
      var email      = $('input[name="new_customer_email"]').val() || '';
      var address    = $('textarea[name="new_customer_address"]').val() || '';
      var city       = $('input[name="new_customer_city"]').val() || '';
      var zip        = $('input[name="new_customer_zip"]').val() || '';
      var country    = $('select[name="new_customer_country"]').val() || '';

      // Όνομα subject ανάλογα με το subject_type
      if (type === 'patient') {
        $('input[name="first_name"]').val(firstNameC || company);
        $('input[name="last_name"]').val(lastNameC);
      } else {
        $('input[name="subject_name"]').val(company);
      }

      // Contact details του subject
      $('input[name="phone"]').val(phone);
      $('input[name="email"]').val(email);
      $('textarea[name="address"]').val(address);
      $('input[name="city"]').val(city);
      $('input[name="zip"]').val(zip);
      $('select[name="country"]').val(country).trigger('change');
    });

    // -------------------------------------------------------
    // 2) Same as EXISTING customer info (AJAX)
    // -------------------------------------------------------
    $('#btn-copy-existing-customer').on('click', function(e){
      e.preventDefault();

      var clientId = $('select[name="client_id"]').val();
      if (!clientId) {
        alert(app && app.lang ? (app.lang.customer + ' ?') : 'Please select a customer first.');
        return;
      }

      $.getJSON(
        admin_url + 'lims/subjects/ajax_customer_details/' + clientId,
        function(resp){
          if (!resp || !resp.success) {
            alert('Could not load customer info.');
            return;
          }

          var type = $('#subject_type').val();

          if (type === 'patient') {
            $('input[name="first_name"]').val(resp.firstname || resp.company);
            $('input[name="last_name"]').val(resp.lastname || '');
          } else {
            $('input[name="subject_name"]').val(resp.company || '');
          }

          $('input[name="phone"]').val(resp.phone || '');
          $('input[name="email"]').val(resp.email || '');
          $('textarea[name="address"]').val(resp.address || '');
          $('input[name="city"]').val(resp.city || '');
          $('input[name="zip"]').val(resp.zip || '');
          if (resp.country) {
            $('select[name="country"]').val(resp.country).trigger('change');
          }
        }
      );
    });

  });

})(jQuery);
</script>

