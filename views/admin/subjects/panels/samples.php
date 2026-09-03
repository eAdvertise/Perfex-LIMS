<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h5 class="tw-text-sm tw-font-semibold tw-text-neutral-700 tw-mb-3">
  <?php echo _l('lims_samples'); ?>
</h5>

<div class="table-responsive">
  <table class="table table-striped dt-table table-subject-samples" data-order-col="0" data-order-type="desc">
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
            <td>
              <?php if (!empty($s->order_id)) : ?>
                <a href="<?php echo admin_url('lims/orders/view/' . (int)$s->order_id); ?>">
                  #<?php echo (int)$s->order_id; ?>
                </a>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td><?php echo html_escape($s->sample_type_name ?: '—'); ?></td>
            <td><?php echo !empty($s->created_at) ? _dt($s->created_at) : '—'; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
