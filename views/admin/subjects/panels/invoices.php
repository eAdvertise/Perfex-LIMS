<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('invoices'); ?>
</h5>

<div class="table-responsive">
  <table class="table table-striped dt-table table-subject-invoices" data-order-col="0" data-order-type="desc">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('invoice_dt_table_heading_amount'); ?></th>
        <th><?php echo _l('invoice_status'); ?></th>
        <th><?php echo _l('invoice_date'); ?></th>
        <th><?php echo _l('invoice_data_duedate'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($billing_invoices)) : ?>
        <?php foreach ($billing_invoices as $inv) : ?>
          <tr>
            <td>
              <a href="<?php echo admin_url('invoices/list_invoices/' . (int)$inv->id); ?>">
                <?php echo format_invoice_number($inv->id); ?>
              </a>
            </td>
            <td><?php echo app_format_money($inv->total, $inv->currency_name); ?></td>
            <td><?php echo format_invoice_status($inv); ?></td>
            <td><?php echo !empty($inv->date) ? _d($inv->date) : '—'; ?></td>
            <td><?php echo !empty($inv->duedate) ? _d($inv->duedate) : '—'; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
