<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$showReceipts = !empty($billing_receipts); // υπάρχει πίνακας & data
?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php
  echo $showReceipts
    ? (_l('lims_subject_receipts') ?: 'Receipts')
    : (_l('payments') ?: 'Payments');
  ?>
</h5>

<div class="table-responsive">
  <?php if ($showReceipts) : ?>
    <!-- RECEIPTS TABLE -->
    <table class="table table-striped dt-table table-subject-receipts" data-order-col="0" data-order-type="desc">
      <thead>
        <tr>
          <th>#</th>
          <th><?php echo _l('payment_date'); ?></th>
          <th><?php echo _l('payment_mode'); ?></th>
          <th><?php echo _l('payments_table_amount_heading'); ?></th>
          <th><?php echo _l('invoice'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($billing_receipts as $r) : ?>
          <tr>
            <td><?php echo (int)$r->id; ?></td>
            <td><?php echo !empty($r->payment_date) ? _d($r->payment_date) : '—'; ?></td>
            <td><?php echo html_escape($r->payment_mode ?: '—'); ?></td>
            <td><?php echo app_format_money($r->amount, $r->currency_name ?? ''); ?></td>
            <td>
              <?php if (!empty($r->invoice_id)) : ?>
                <a href="<?php echo admin_url('invoices/list_invoices/' . (int)$r->invoice_id); ?>">
                  <?php echo format_invoice_number($r->invoice_id); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else : ?>
    <!-- PAYMENTS TABLE (invoicepaymentrecords) -->
    <table class="table table-striped dt-table table-subject-payments" data-order-col="0" data-order-type="desc">
      <thead>
        <tr>
          <th>#</th>
          <th><?php echo _l('payment_date'); ?></th>
          <th><?php echo _l('payment_mode'); ?></th>
          <th><?php echo _l('payments_table_amount_heading'); ?></th>
          <th><?php echo _l('invoice'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($billing_payments)) : ?>
          <?php foreach ($billing_payments as $p) : ?>
            <tr>
              <td><?php echo (int)$p->id; ?></td>
              <td><?php echo !empty($p->date) ? _d($p->date) : '—'; ?></td>
              <td><?php echo html_escape($p->paymentmode ?: '—'); ?></td>
              <td><?php echo app_format_money($p->amount, $p->currency_name ?? ''); ?></td>
              <td>
                <?php if (!empty($p->invoiceid)) : ?>
                  <a href="<?php echo admin_url('invoices/list_invoices/' . (int)$p->invoiceid); ?>">
                    <?php echo format_invoice_number($p->invoiceid); ?>
                  </a>
                <?php else : ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
