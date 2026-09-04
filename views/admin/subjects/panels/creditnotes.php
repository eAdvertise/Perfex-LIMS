<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('credit_notes'); ?>
</h5>

<div class="table-responsive">
  <table class="table table-striped dt-table table-subject-creditnotes" data-order-col="0" data-order-type="desc">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('credit_note_amount'); ?></th>
        <th><?php echo _l('credit_note_status'); ?></th>
        <th><?php echo _l('credit_note_date'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($billing_creditnotes)) : ?>
        <?php foreach ($billing_creditnotes as $cn) : ?>
          <tr>
            <td>
              <a href="<?php echo admin_url('credit_notes/list_credit_notes/' . (int)$cn->id); ?>">
                <?php echo format_credit_note_number($cn->id); ?>
              </a>
            </td>
            <td><?php echo app_format_money($cn->total, $cn->currency_name); ?></td>
            <td><?php echo format_credit_note_status($cn->status); ?></td>
            <td><?php echo !empty($cn->date) ? _d($cn->date) : '—'; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
