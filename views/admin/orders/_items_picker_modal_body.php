<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
  <div class="col-md-4">
    <h5 class="mbot10"><?php echo _l('lims_panels'); ?></h5>
    <?php if(empty($panels)): ?>
      <p class="text-muted">—</p>
    <?php else: ?>
      <?php foreach($panels as $p): 
        $checked = in_array((int)$p->id, $preselected['panel']) ? 'checked' : '';
      ?>
        <div class="checkbox">         
            <input type="checkbox" id="p-<?php echo (int)$p->id; ?>"name="lines[p-<?php echo (int)$p->id; ?>][checked]" value="1" <?php echo $checked; ?>>
            <label for="p-<?php echo (int)$p->id; ?>"><?php echo html_escape($p->name); ?></label>            
        </div>
			<input type="hidden" name="lines[p-<?php echo (int)$p->id; ?>][type]" value="panel">
            <input type="hidden" name="lines[p-<?php echo (int)$p->id; ?>][id]" value="<?php echo (int)$p->id; ?>">
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="col-md-4">
    <h5 class="mbot10"><?php echo _l('lims_analyses'); ?></h5>
    <?php if(empty($analyses)): ?>
      <p class="text-muted">—</p>
    <?php else: ?>
      <?php foreach($analyses as $a):
        $checked = in_array((int)$a->id, $preselected['analysis']) ? 'checked' : '';
      ?>
        <div class="checkbox">
			<input type="checkbox" name="lines[a-<?php echo (int)$a->id; ?>][checked]" value="1" <?php echo $checked; ?>>
            <label><?php echo html_escape($a->name); ?></label>
        </div>
            <input type="hidden" name="lines[a-<?php echo (int)$a->id; ?>][type]" value="analysis">
            <input type="hidden" name="lines[a-<?php echo (int)$a->id; ?>][id]" value="<?php echo (int)$a->id; ?>">
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <div class="col-md-4">
    <h5 class="mbot10"><?php echo _l('lims_cultures'); ?></h5>
    <?php if(empty($cultures)): ?>
      <p class="text-muted">—</p>
    <?php else: ?>
      <?php foreach($cultures as $c):
        $checked = in_array((int)$c->id, $preselected['culture']) ? 'checked' : '';
      ?>
        <div class="checkbox">
			<input type="checkbox" name="lines[c-<?php echo (int)$c->id; ?>][checked]" value="1" <?php echo $checked; ?>>
            <label><?php echo html_escape($c->name); ?></label>
        </div>
            <input type="hidden" name="lines[c-<?php echo (int)$c->id; ?>][type]" value="culture">
            <input type="hidden" name="lines[c-<?php echo (int)$c->id; ?>][id]" value="<?php echo (int)$c->id; ?>">
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
