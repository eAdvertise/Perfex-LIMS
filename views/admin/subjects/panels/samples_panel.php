<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('lims_samples'); ?>
</h5>

<div class="table-responsive">
  <table class="table dt-table">
    <thead>
      <tr>
        <th>#</th>
        <th><?php echo _l('lims_orders'); ?></th>
        <th><?php echo _l('lims_sample_types'); ?></th>
        <th><?php echo _l('date_created'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($samples)) : ?>
        <?php foreach ($samples as $s) : ?>
          <tr>
            <td><?php echo (int)$s->id; ?></td>
            <td><?php echo (int)$s->order_id ?: '—'; ?></td>
            <td><?php echo html_escape($s->sample_type_name ?: '—'); ?></td>
            <td><?php echo !empty($s->created_at) ? _dt($s->created_at) : '—'; ?></td>
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
