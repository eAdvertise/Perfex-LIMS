<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('lims_orders'); ?>
</h5>

<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('client'); ?></th>
        <th><?php echo _l('status'); ?></th>
        <th><?php echo _l('priority'); ?></th>
        <th><?php echo _l('due_date'); ?></th>
        <th><?php echo _l('date_created'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($orders)) : ?>
        <?php foreach ($orders as $o) : ?>
          <tr>
            <td>
              <a href="<?php echo admin_url('lims/orders/view/' . (int)$o->id); ?>">
                #<?php echo (int)$o->id; ?>
              </a>
            </td>
            <td>
              <?php if (!empty($o->client_id)) : ?>
                <a href="<?php echo admin_url('clients/client/' . (int)$o->client_id); ?>">
                  <?php echo html_escape($o->client_company ?: ('#' . (int)$o->client_id)); ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td><?php echo ucfirst(html_escape($o->status ?: 'draft')); ?></td>
            <td><?php echo (int)($o->priority ?? 0); ?></td>
            <td><?php echo !empty($o->due_at) ? _dt($o->due_at) : '—'; ?></td>
            <td><?php echo !empty($o->created_at) ? _dt($o->created_at) : '—'; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted">
            <?php echo _l('no_items_found'); ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
