<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('delivery_notes') ?: 'Waybills'; ?>
</h5>

<div class="table-responsive">
  <table class="table table-striped dt-table table-subject-waybills" data-order-col="0" data-order-type="desc">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('delivery_note_date') ?: _l('date'); ?></th>
        <th><?php echo _l('client'); ?></th>
        <th><?php echo _l('status'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($billing_delivery_notes)) : ?>
        <?php foreach ($billing_delivery_notes as $dn) : ?>
          <tr>
            <td>
              <a href="<?php echo admin_url('delivery_notes/view/' . (int)$dn->id); ?>">
                #<?php echo (int)$dn->id; ?>
              </a>
            </td>
            <td><?php echo !empty($dn->date) ? _d($dn->date) : '—'; ?></td>
            <td>
              <?php if (!empty($dn->client_id)) : ?>
                <a href="<?php echo admin_url('clients/client/' . (int)$dn->client_id); ?>">
                  <?php echo html_escape($dn->client_company ?? ('#' . (int)$dn->client_id)); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td><?php echo html_escape($dn->status ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
