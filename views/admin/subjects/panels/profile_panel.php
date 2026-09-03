<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
  <div>
    <div class="tw-text-xs tw-font-semibold tw-uppercase tw-text-neutral-500">
      <?php echo _l('lims_subject'); ?>
    </div>
    <div class="tw-text-xl tw-font-semibold tw-text-neutral-800">
      <?php echo html_escape($fullName); ?>
    </div>
    <?php if (!empty($typeLabel)): ?>
      <div class="tw-text-xs tw-mt-1 tw-inline-flex tw-items-center tw-rounded-full tw-bg-neutral-100 tw-px-2 tw-py-0.5 tw-text-neutral-700">
        <i class="fa fa-tag tw-mr-1 tw-text-[11px]"></i>
        <?php echo html_escape($typeLabel); ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if (!empty($subject->internal_code)): ?>
    <div class="tw-text-right">
      <div class="tw-text-xs tw-uppercase tw-text-neutral-500">
        <?php echo _l('lims_subject_code') ?: 'Subject Code'; ?>
      </div>
      <div class="tw-font-mono tw-font-semibold tw-text-neutral-800">
        <?php echo html_escape($subject->internal_code); ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<hr class="hr-panel-heading" />

<div class="row">
  <!-- ΣΤΗΛΗ 1: Demographics -->
  <div class="col-md-6">
    <h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
      <?php echo _l('lims_subject_details') ?: 'Subject details'; ?>
    </h5>

    <table class="table no-margin">
      <tbody>
        <tr>
          <td class="text-muted"><?php echo _l('lims_subject_type'); ?></td>
          <td><?php echo html_escape($typeLabel ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_firstname'); ?></td>
          <td><?php echo html_escape($subject->first_name ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_lastname'); ?></td>
          <td><?php echo html_escape($subject->last_name ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('lims_subject_id_number'); ?></td>
          <td><?php echo html_escape($subject->id_number ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('lims_subject_nationality'); ?></td>
          <td><?php echo html_escape($subject->nationality ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('lims_subject_gender'); ?></td>
          <td><?php echo html_escape($subject->gender ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('lims_subject_social_insurance_no'); ?></td>
          <td><?php echo html_escape($subject->social_insurance_no ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('lims_subject_dob'); ?></td>
          <td>
            <?php
            if (!empty($subject->date_of_birth) && $subject->date_of_birth !== '0000-00-00') {
                echo _d($subject->date_of_birth);
                if (!empty($ageText)) {
                    echo ' <span class="text-muted">(' . html_escape($ageText) . ')</span>';
                }
            } else {
                echo '—';
            }
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- ΣΤΗΛΗ 2: Contact & Address -->
  <div class="col-md-6">
    <h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
      <?php echo _l('customer_profile'); ?>
    </h5>

    <table class="table no-margin">
      <tbody>
        <tr>
          <td class="text-muted"><?php echo _l('clients_phone'); ?></td>
          <td><?php echo html_escape($subject->phone ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('clients_email'); ?></td>
          <td>
            <?php if (!empty($subject->email)) : ?>
              <a href="mailto:<?php echo html_escape($subject->email); ?>">
                <?php echo html_escape($subject->email); ?>
              </a>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_address'); ?></td>
          <td><?php echo nl2br(html_escape($subject->address ?: '—')); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_city'); ?></td>
          <td><?php echo html_escape($subject->city ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_state'); ?></td>
          <td><?php echo html_escape($subject->state ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_zip'); ?></td>
          <td><?php echo html_escape($subject->zip ?: '—'); ?></td>
        </tr>
        <tr>
          <td class="text-muted"><?php echo _l('client_country'); ?></td>
          <td><?php echo html_escape($countryName ?: '—'); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<hr />

<!-- Linked customer + notes -->
<div class="row">
  <div class="col-md-6">
    <h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
      <?php echo _l('client'); ?>
    </h5>
    <?php if (!empty($client)): ?>
      <p>
        <a href="<?php echo admin_url('clients/client/' . (int)$client->userid); ?>">
          <?php echo html_escape($client->company); ?> (#<?php echo (int)$client->userid; ?>)
        </a>
      </p>
    <?php else: ?>
      <p class="text-muted">—</p>
    <?php endif; ?>
  </div>

  <div class="col-md-6">
    <h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
      <?php echo _l('notes'); ?>
    </h5>
    <p>
      <?php echo nl2br(html_escape($subject->notes ?: '—')); ?>
    </p>
  </div>
</div>
