<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
  h1,h2,h3,h4,h5 { margin: 0 0 6px 0; padding: 0; }
  .muted { color: #666; font-size: 11px; }
  .spacer { height: 8px; }
  table { width:100%; border-collapse: collapse; }
  th, td { border:1px solid #ddd; padding:6px; font-size:12px; }
  th { background:#f5f5f5; }
</style>

<h3><?php echo _l('lims_order'); ?> #<?php echo (int)$order->id; ?></h3>
<div class="muted">
  <?php echo _l('date_created'); ?>:
  <?php echo !empty($order->created_at) ? _dt($order->created_at) : '—'; ?>
  &nbsp;|&nbsp;
  <?php echo _l('status'); ?>: <?php echo ucfirst($order->status ?: 'draft'); ?>
</div>

<div class="spacer"></div>

<table>
  <tr>
    <th style="width:60%"><?php echo _l('item'); ?></th>
    <th style="width:20%"><?php echo _l('unit'); ?></th>
    <th style="width:10%; text-align:right;"><?php echo _l('quantity'); ?></th>
    <th style="width:10%; text-align:right;"><?php echo _l('rate'); ?></th>
  </tr>
  <?php if (!empty($lines)): ?>
    <?php foreach ($lines as $ln): ?>
      <tr>
        <td><?php echo html_escape($ln->name); ?></td>
        <td><?php echo html_escape($ln->unit); ?></td>
        <td style="text-align:right;"><?php echo (float)($ln->qty ?? 1); ?></td>
        <td style="text-align:right;"><?php echo app_format_money((float)$ln->unit_price, $ln->currency_id); ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="4" class="muted"><?php echo _l('no_items_found'); ?></td></tr>
  <?php endif; ?>
</table>

<?php if (!empty($order->notes)): ?>
  <div class="spacer"></div>
  <h4><?php echo _l('notes'); ?></h4>
  <div><?php echo nl2br(html_escape($order->notes)); ?></div>
<?php endif; ?>
