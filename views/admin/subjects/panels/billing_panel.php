<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('lims_billing') ?: 'Billing'; ?>
</h5>

<!-- INVOICES -->
<h4 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mt-4 tw-mb-2">
  <?php echo _l('invoices'); ?>
</h4>
<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('invoice_dt_table_heading_date'); ?></th>
        <th><?php echo _l('client'); ?></th>
        <th><?php echo _l('invoice_dt_table_heading_amount'); ?></th>
        <th><?php echo _l('invoice_dt_table_heading_status'); ?></th>
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
            <td><?php echo _d($inv->date); ?></td>
            <td>
              <?php if (!empty($inv->clientid)) : ?>
                <a href="<?php echo admin_url('clients/client/' . (int)$inv->clientid); ?>">
                  <?php echo get_company_name($inv->clientid); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                if (isset($inv->total, $inv->currency)) {
                    echo app_format_money($inv->total, get_currency($inv->currency));
                } else {
                    echo '—';
                }
              ?>
            </td>
            <td><?php echo format_invoice_status($inv); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">
            <?php echo _l('no_items_found'); ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<hr />

<!-- CREDIT NOTES -->
<h4 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mt-4 tw-mb-2">
  <?php echo _l('credit_notes'); ?>
</h4>
<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('credit_note_dt_table_heading_date'); ?></th>
        <th><?php echo _l('client'); ?></th>
        <th><?php echo _l('credit_note_dt_table_heading_amount'); ?></th>
        <th><?php echo _l('credit_note_dt_table_heading_status'); ?></th>
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
            <td><?php echo _d($cn->date); ?></td>
            <td>
              <?php if (!empty($cn->clientid)) : ?>
                <a href="<?php echo admin_url('clients/client/' . (int)$cn->clientid); ?>">
                  <?php echo get_company_name($cn->clientid); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                if (isset($cn->total, $cn->currency)) {
                    echo app_format_money($cn->total, get_currency($cn->currency));
                } else {
                    echo '—';
                }
              ?>
            </td>
            <td><?php echo format_credit_note_status($cn); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">
            <?php echo _l('no_items_found'); ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<hr />

<!-- DELIVERY NOTES (αν υπάρχουν) -->
<h4 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mt-4 tw-mb-2">
  <?php echo _l('delivery_notes') ?: 'Delivery Notes'; ?>
</h4>
<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('date'); ?></th>
        <th><?php echo _l('client'); ?></th>
        <th><?php echo _l('status'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($billing_delivery_notes)) : ?>
        <?php foreach ($billing_delivery_notes as $dn) : ?>
          <tr>
            <td>
              <a href="<?php echo admin_url('delivery_notes/delivery_note/' . (int)$dn->id); ?>">
                #<?php echo (int)$dn->id; ?>
              </a>
            </td>
            <td><?php echo !empty($dn->date) ? _d($dn->date) : '—'; ?></td>
            <td>
              <?php if (!empty($dn->clientid)) : ?>
                <a href="<?php echo admin_url('clients/client/' . (int)$dn->clientid); ?>">
                  <?php echo get_company_name($dn->clientid); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td><?php echo !empty($dn->status) ? html_escape($dn->status) : '—'; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center text-muted">
            <?php echo _l('no_items_found'); ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<hr />

<!-- RECEIPTS (αν υπάρχουν) -->
<h4 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mt-4 tw-mb-2">
  <?php echo _l('receipts') ?: 'Receipts'; ?>
</h4>
<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('date'); ?></th>
        <th><?php echo _l('client'); ?></th>
        <th><?php echo _l('amount'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($billing_receipts)) : ?>
        <?php foreach ($billing_receipts as $rc) : ?>
          <tr>
            <td>
              <a href="<?php echo admin_url('receipts/receipt/' . (int)$rc->id); ?>">
                #<?php echo (int)$rc->id; ?>
              </a>
            </td>
            <td><?php echo !empty($rc->date) ? _d($rc->date) : '—'; ?></td>
            <td>
              <?php if (!empty($rc->client_id)) : ?>
                <a href="<?php echo admin_url('clients/client/' . (int)$rc->client_id); ?>">
                  <?php echo get_company_name($rc->client_id); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                if (isset($rc->total, $rc->currency)) {
                    echo app_format_money($rc->total, get_currency($rc->currency));
                } else {
                    echo '—';
                }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" class="text-center text-muted">
            <?php echo _l('no_items_found'); ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<hr />

<!-- PAYMENTS -->
<h4 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mt-4 tw-mb-2">
  <?php echo _l('payments') ?: 'Payments'; ?>
</h4>
<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('date'); ?></th>
        <th><?php echo _l('invoice'); ?></th>
        <th><?php echo _l('amount'); ?></th>
        <th><?php echo _l('payment_mode'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($billing_payments)) : ?>
        <?php foreach ($billing_payments as $pmt) : ?>
          <tr>
            <td><?php echo (int)$pmt->id; ?></td>
            <td><?php echo !empty($pmt->date) ? _d($pmt->date) : '—'; ?></td>
            <td>
              <?php if (!empty($pmt->invoiceid)) : ?>
                <a href="<?php echo admin_url('invoices/list_invoices/' . (int)$pmt->invoiceid); ?>">
                  <?php echo format_invoice_number($pmt->invoiceid); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                if (isset($pmt->amount, $pmt->currency)) {
                    echo app_format_money($pmt->amount, get_currency($pmt->currency));
                } else {
                    echo '—';
                }
              ?>
            </td>
            <td>
              <?php
                if (!empty($pmt->paymentmode)) {
                    echo html_escape($pmt->paymentmode);
                } else {
                    echo '—';
                }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">
            <?php echo _l('no_items_found'); ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
