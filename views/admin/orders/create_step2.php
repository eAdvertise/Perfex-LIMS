<!-- lims/views/admin/orders/create_step2.php -->
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .lims-colbox{max-height:520px;overflow:auto;border:1px solid #eee;border-radius:4px;padding:10px;}
  .lims-colbox table{margin-bottom:0;}
  .lims-colbox thead th{position:sticky;top:0;background:#fff;z-index:2;}
</style>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5"><?php echo _l('lims_new_order'); ?> – <?php echo _l('lims_select_services'); ?></h4>

        <?php echo form_open(admin_url('lims/orders/create?step=2')); ?>
        <input type="hidden" name="action" value="save_submit"/>

        <!-- Draft summary -->
        <div class="row mbot15">
			<div class="col-md-6">
			  <strong><?php echo _l('lims_subject'); ?>:</strong>
			  <?php
				if (!empty($draft['subject_id'])) {
				  $s = $this->db->where('id', (int)$draft['subject_id'])
								->get(db_prefix().'lims_subjects')
								->row();
					
				  if ($s) {
					// Αν έχεις firstname/lastname:
					if (isset($s->last_name) || isset($s->first_name)) {
						$main = trim(($s->last_name ?? '') . ' ' . ($s->first_name ?? ''));
					}

					$parts = [];
					if ($main === '') {
						if (isset($s->subject_name) && $s->subject_name !== '') {
						  $parts[] = $s->subject_name;
						} elseif (isset($s->name) && $s->name !== '') {
						  $parts[] = $s->name;
						}
					}else{
						$parts[] = $main;
					}
					
					if (isset($s->internal_code) && $s->internal_code !== '') {
					  $parts[] = '['.$s->internal_code.']';
					}
					$label = trim(implode(' ', $parts));
					if ($label === '') { $label = 'Subject'; }

					echo html_escape($label).' (#'.(int)$s->id.')';
				  } else {
					echo '#'.(int)$draft['subject_id'];
				  }
				} else {
				  echo '-';
				}
			  ?>
			</div>

          <div class="col-md-6">
            <strong><?php echo _l('lims_contract'); ?>:</strong>
            <?php
              if (!empty($draft['contract_id'])) {
                $cn = $this->db->select('name')->where('id',(int)$draft['contract_id'])->get(db_prefix().'lims_contracts')->row();
                echo html_escape($cn->name ?? ('#'.$draft['contract_id']));
              } else { echo _l('no_link'); }
            ?>
          </div>
        </div>

        <!-- Three side-by-side columns -->
        <div class="row">

          <!-- PANELS -->
          <div class="col-md-4">
            <h5 class="mbot10"><i class="fa fa-layer-group"></i> <?php echo _l('lims_panels'); ?></h5>
            <div class="lims-colbox">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width:40px;"></th>
                    <th><?php echo _l('name'); ?></th>
                    <th><?php echo _l('code'); ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($panels)): foreach($panels as $r): $key='p-'.(int)$r->id; ?>
					<tr>
					  <td><input type="checkbox" name="lines[<?php echo $key; ?>][checked]" value="1"></td>
					  <td><?php echo html_escape($r->name); ?></td>
					  <td><?php echo html_escape($r->code ?? ''); ?></td>
					</tr>
					<input type="hidden" name="lines[<?php echo $key; ?>][type]" value="panel">
					<input type="hidden" name="lines[<?php echo $key; ?>][id]"   value="<?php echo (int)$r->id; ?>">
                <?php endforeach; else: ?>
                  <tr><td colspan="3" class="text-center text-muted">—</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- ANALYSES -->
          <div class="col-md-4">
            <h5 class="mbot10"><i class="fa fa-vial"></i> <?php echo _l('lims_analyses'); ?></h5>
            <div class="lims-colbox">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width:40px;"></th>
                    <th><?php echo _l('name'); ?></th>
                    <th><?php echo _l('code'); ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($analyses)): foreach($analyses as $r): $key='a-'.(int)$r->id; ?>
					<tr>
					  <td><input type="checkbox" name="lines[<?php echo $key; ?>][checked]" value="1"></td>
					  <td><?php echo html_escape($r->name); ?></td>
					  <td><?php echo html_escape($r->code ?? ''); ?></td>
					</tr>
					<input type="hidden" name="lines[<?php echo $key; ?>][type]" value="analysis">
					<input type="hidden" name="lines[<?php echo $key; ?>][id]"   value="<?php echo (int)$r->id; ?>">
                <?php endforeach; else: ?>
                  <tr><td colspan="3" class="text-center text-muted">—</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- CULTURES -->
          <div class="col-md-4">
            <h5 class="mbot10"><i class="fa fa-bacteria"></i> <?php echo _l('lims_cultures'); ?></h5>
            <div class="lims-colbox">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width:40px;"></th>
                    <th><?php echo _l('name'); ?></th>
                    <th><?php echo _l('lims_sample_type'); ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($cultures)): foreach($cultures as $r): $key='c-'.(int)$r->id; ?>
					<tr>
					  <td><input type="checkbox" name="lines[<?php echo $key; ?>][checked]" value="1"></td>
					  <td>
						<?php echo html_escape($r->name); ?>
						<?php if(!empty($r->code)): ?><br><small class="text-muted"><?php echo html_escape($r->code); ?></small><?php endif; ?>
					  </td>
					  <td>
						<?php
						  if (!empty($r->sample_type_id)) {
							$st = $this->db->select('name')->where('id',(int)$r->sample_type_id)->get(db_prefix().'lims_sample_types')->row();
							echo html_escape($st->name ?? '');
						  } else { echo '-'; }
						?>
					  </td>
					</tr>
					<input type="hidden" name="lines[<?php echo $key; ?>][type]" value="culture">
					<input type="hidden" name="lines[<?php echo $key; ?>][id]"   value="<?php echo (int)$r->id; ?>">
				<?php endforeach; else: ?>
                  <tr><td colspan="3" class="text-center text-muted">—</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div><!-- /row -->

        <div class="text-right mtop15">
          <a href="<?php echo admin_url('lims/orders/create'); ?>" class="btn btn-default">
            <?php echo _l('lims_back'); ?>
          </a>
          <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
        </div>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  $(function(){
    // Στο submit, αφαίρεσε τα hidden για όσα δεν είναι checked
    $('form').on('submit', function(){
      $('input[name="lines[][checked]"]').each(function(){
        if(!this.checked){
          var rowKey = $(this).data('row');
          $('input[data-for="'+rowKey+'"]').remove();
          $(this).remove();
        }
      });
    });
  });
})(jQuery);
</script>
