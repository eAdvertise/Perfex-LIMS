<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
$isEdit = !empty($subject) && !empty($subject->id);
$value = static function ($field, $default = '') use ($subject) {
    return isset($subject->{$field}) ? $subject->{$field} : $default;
};
$subjectTypes = [
    ['id' => 'patient', 'name' => _l('lims_subject_type_patient')],
    ['id' => 'doctor', 'name' => _l('lims_subject_type_doctor')],
    ['id' => 'lab', 'name' => _l('lims_subject_type_lab')],
    ['id' => 'farm', 'name' => _l('lims_subject_type_farm')],
    ['id' => 'restaurant', 'name' => _l('lims_subject_type_restaurant')],
    ['id' => 'other', 'name' => _l('lims_subject_type_other')],
];
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix">
                            <h4 class="pull-left no-margin"><?php echo html_escape($title); ?></h4>
                            <a href="<?php echo $isEdit ? admin_url('lims/subjects/view/' . (int)$subject->id) : admin_url('lims/subjects'); ?>" class="btn btn-default pull-right">
                                <?php echo _l('go_back'); ?>
                            </a>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php echo form_open(current_url()); ?>

                        <div class="row">
                            <div class="col-md-6"><?php echo render_input('internal_code', _l('lims_subject_internal_code') ?: 'Internal code', $internal_code, 'text', ['readonly' => true]); ?></div>
                            <div class="col-md-6"><?php echo render_select('subject_type', $subjectTypes, ['id', 'name'], _l('lims_subject_type') ?: 'Subject type', $value('subject_type', 'patient')); ?></div>
                            <div class="col-md-4"><?php echo render_input('first_name', _l('first_name'), $value('first_name')); ?></div>
                            <div class="col-md-4"><?php echo render_input('last_name', _l('last_name'), $value('last_name')); ?></div>
                            <div class="col-md-4"><?php echo render_input('subject_name', _l('lims_subject_name') ?: _l('name'), $value('subject_name')); ?></div>
                            <div class="col-md-4"><?php echo render_input('id_number', _l('lims_subject_id_number') ?: 'ID / Passport', $value('id_number')); ?></div>
                            <div class="col-md-4"><?php echo render_input('social_insurance_no', _l('lims_subject_social_insurance_no') ?: 'Social insurance no.', $value('social_insurance_no')); ?></div>
                            <div class="col-md-4"><?php echo render_date_input('date_of_birth', _l('lims_subject_date_of_birth') ?: 'Date of birth', $value('date_of_birth')); ?></div>
                            <div class="col-md-4"><?php echo render_select('gender', [['id' => 'male', 'name' => _l('lims_subject_gender_male')], ['id' => 'female', 'name' => _l('lims_subject_gender_female')], ['id' => 'other', 'name' => _l('lims_subject_gender_other')]], ['id', 'name'], _l('lims_subject_gender') ?: 'Gender', $value('gender')); ?></div>
                            <div class="col-md-4"><?php echo render_input('nationality', _l('lims_subject_nationality') ?: 'Nationality', $value('nationality')); ?></div>
                            <div class="col-md-4"><?php echo render_select('language', $languages ?? [], ['id', 'name'], _l('language'), $value('language')); ?></div>
                        </div>

                        <hr />
                        <h4><?php echo _l('customer'); ?></h4>
                        <div class="radio radio-primary radio-inline">
                            <input type="radio" id="customer-mode-existing" name="customer_mode" value="existing" checked>
                            <label for="customer-mode-existing"><?php echo _l('lims_subject_customer_existing'); ?></label>
                        </div>
                        <?php if (!$isEdit): ?>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="customer-mode-new" name="customer_mode" value="new">
                                <label for="customer-mode-new"><?php echo _l('lims_subject_customer_new'); ?></label>
                            </div>
                        <?php endif; ?>
                        <div id="existing-customer-fields" class="mtop15">
                            <?php echo render_select('client_id', $clients ?? [], ['userid', 'company'], _l('client'), $value('client_id'), ['data-live-search' => true]); ?>
                        </div>
                        <?php if (!$isEdit): ?>
                            <div id="new-customer-fields" class="hide mtop15">
                                <div class="row">
                                    <div class="col-md-6"><?php echo render_input('new_customer_company', _l('company')); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_vat', _l('client_vat_number')); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_firstname', _l('first_name')); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_lastname', _l('last_name')); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_email', _l('email'), '', 'email'); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_phone', _l('phonenumber')); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_website', _l('client_website')); ?></div>
                                    <div class="col-md-6"><?php echo render_input('new_customer_address', _l('client_address')); ?></div>
                                    <div class="col-md-4"><?php echo render_input('new_customer_city', _l('client_city')); ?></div>
                                    <div class="col-md-4"><?php echo render_input('new_customer_state', _l('client_state')); ?></div>
                                    <div class="col-md-4"><?php echo render_input('new_customer_zip', _l('client_postal_code')); ?></div>
                                    <div class="col-md-4"><?php echo render_select('new_customer_country', $countries ?? [], ['country_id', ['short_name']], _l('country'), '', ['data-live-search' => true]); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr />
                        <div class="row">
                            <div class="col-md-6"><?php echo render_input('phone', _l('phonenumber'), $value('phone')); ?></div>
                            <div class="col-md-6"><?php echo render_input('email', _l('email'), $value('email'), 'email'); ?></div>
                            <div class="col-md-12"><?php echo render_input('address', _l('client_address'), $value('address')); ?></div>
                            <div class="col-md-4"><?php echo render_input('city', _l('client_city'), $value('city')); ?></div>
                            <div class="col-md-4"><?php echo render_input('state', _l('client_state'), $value('state')); ?></div>
                            <div class="col-md-4"><?php echo render_input('zip', _l('client_postal_code'), $value('zip')); ?></div>
                            <div class="col-md-6"><?php echo render_select('country', $countries ?? [], ['country_id', ['short_name']], _l('country'), $value('country'), ['data-live-search' => true]); ?></div>
                        </div>
                        <?php echo render_textarea('notes', _l('notes'), $value('notes')); ?>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" id="active" name="active" value="1" <?php echo (int)$value('active', 1) === 1 ? 'checked' : ''; ?>>
                            <label for="active"><?php echo _l('active'); ?></label>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
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
(function ($) {
    "use strict";
    function toggleCustomerMode() {
        var isNew = $('input[name="customer_mode"]:checked').val() === 'new';
        $('#existing-customer-fields').toggleClass('hide', isNew);
        $('#new-customer-fields').toggleClass('hide', !isNew);
    }
    $(document).on('change', 'input[name="customer_mode"]', toggleCustomerMode);
    toggleCustomerMode();
})(jQuery);
</script>
