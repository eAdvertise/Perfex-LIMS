<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
  .h1{font-size:18px;font-weight:bold;margin-bottom:6px}
  .muted{color:#666}
  .mb10{margin-bottom:10px}
  .mb5{margin-bottom:5px}
  .tbl{width:100%;border-collapse:collapse;margin-top:8px}
  .tbl th,.tbl td{border:1px solid #ddd;padding:6px;font-size:11px}
  .tags .tag{display:inline-block;background:#f2f2f2;border:1px solid #e0e0e0;border-radius:3px;padding:2px 6px;margin-right:4px;margin-bottom:3px;font-size:10px}
</style>

<div>
  <div class="h1">Work Order #<?php echo (int)$order->id; ?></div>
  <div class="muted mb10">
    Status: <?php echo ucfirst($order->status ?: 'draft'); ?> ·
    Created: <?php echo !empty($order->created_at)? _dt($order->created_at):'—'; ?> ·
    Due: <?php echo !empty($order->due_at)? _dt($order->due_at):'—'; ?>
  </div>

  <table class="tbl">
    <tr>
      <th width="50%">Client</th>
      <th width="50%">Primary Contact</th>
    </tr>
    <tr>
      <td>
        <?php if(!empty($client)): ?>
          <strong><?php echo html_escape($client->company); ?></strong><br/>
          <?php echo html_escape($client->address ?? ''); ?><br/>
          <?php echo html_escape(trim(($client->city??'').' '.($client->zip??''))); ?>
        <?php else: ?>—<?php endif; ?>
      </td>
      <td>
        <?php if(!empty($contact)): ?>
          <strong><?php echo html_escape(trim(($contact->firstname??'').' '.($contact->lastname??''))); ?></strong><br/>
          <?php if(!empty($contact->email)): ?><?php echo html_escape($contact->email); ?><br/><?php endif; ?>
          <?php if(!empty($contact->phonenumber)): ?><?php echo html_escape($contact->phonenumber); ?><?php endif; ?>
        <?php else: ?>—<?php endif; ?>
      </td>
    </tr>
  </table>

  <div class="mb10"></div>

  <div class="mb5"><strong>Notes</strong></div>
  <div class="muted"><?php echo !empty($order->notes) ? nl2br(html_escape($order->notes)) : '—'; ?></div>

  <div class="mb10"></div>

  <div class="mb5"><strong>Selected Services</strong></div>
  <table class="tbl">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Type</th>
      <th>Qty</th>
      <th>Unit</th>
    </tr>
    <?php $i=1; foreach($lines as $ln): ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo html_escape($ln->name); ?></td>
        <td><?php echo html_escape(ucfirst($ln->source_type)); ?></td>
        <td>1</td>
        <td><?php echo html_escape($ln->unit ?? ''); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="mb10"></div>

  <div class="mb5"><strong>Samples</strong></div>
  <?php if (empty($samples)): ?>
    <div class="muted">—</div>
  <?php else: ?>
    <table class="tbl">
      <tr>
        <th>#</th>
        <th>UID</th>
        <th>Type</th>
        <th>Min Volume</th>
        <th>Status</th>
        <th>Collected At</th>
      </tr>
      <?php foreach($samples as $sp): ?>
        <tr>
          <td><?php echo (int)$sp->id; ?></td>
          <td><?php echo html_escape($sp->sample_uid ?: '—'); ?></td>
          <td><?php echo html_escape(($sp->st_name ?: '—').(!empty($sp->st_code)?' ('.$sp->st_code.')':'')); ?></td>
          <td><?php echo isset($sp->st_min_volume)? (float)$sp->st_min_volume.' ml' : '—'; ?></td>
          <td><?php echo ucfirst($sp->status ?: 'pending'); ?></td>
          <td><?php echo !empty($sp->collected_at)? _dt($sp->collected_at) : '—'; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
