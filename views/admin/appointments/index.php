<!-- lims/views/admin/appointments/index.php -->
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.form-inline .bootstrap-select, .form-inline .bootstrap-select.form-control:not([class*=col-]){min-width:220px;}
</style>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="clearfix mtop10 mbot10">
		  <h4 class="pull-left"><?php echo _l('lims_appointments'); ?></h4>
		  <div class="pull-right">
			<a href="<?php echo admin_url('lims/appointments/create'); ?>" class="btn btn-primary">
			  <i class="fa fa-plus"></i> <?php echo _l('new_appointment') ?: 'New Appointment'; ?>
			</a>
		  </div>
		</div>

		<form method="get" action="<?php echo admin_url('lims/appointments'); ?>" class="form-inline mbot15">
		  <div class="form-group mright10">
			<label class="mright5"><?php echo _l('filter_by') ?: 'Filter by'; ?></label>
			<select name="range" id="ap-range" class="form-control selectpicker">
			  <?php
				$ranges = [
				  'upcoming'     => _l('upcoming') ?: 'Upcoming',
				  'last_month'   => _l('last_month') ?: 'Last month',
				  'last_2_months'=> _l('last_2_months') ?: 'Last 2 months',
				  'this_year'    => _l('this_year') ?: 'This year',
				  'last_year'    => _l('last_year') ?: 'Last year',
				  'next_month'   => _l('next_month') ?: 'Next month',
				  'custom'       => _l('custom') ?: 'Custom',
				];
				foreach ($ranges as $key=>$label):
			  ?>
			  <option value="<?php echo $key; ?>" <?php echo ($range === $key ? 'selected' : ''); ?>><?php echo $label; ?></option>
			  <?php endforeach; ?>
			</select>
		  </div>

		  <div id="ap-custom-range" class="form-group mright10" style="<?php echo ($range==='custom'?'':'display:none;'); ?>">
			<div class="input-group">
			  <span class="input-group-addon"><?php echo _l('from'); ?></span>
			  <input type="date" class="form-control" name="from" value="<?php echo html_escape($from ?? ''); ?>">
			</div>
			<div class="input-group mleft10">
			  <span class="input-group-addon"><?php echo _l('to'); ?></span>
			  <input type="date" class="form-control" name="to" value="<?php echo html_escape($to ?? ''); ?>">
			</div>
		  </div>

		  <button type="submit" class="btn btn-default mleft10">
			<i class="fa fa-filter"></i> <?php echo _l('apply') ?: 'Apply'; ?>
		  </button>

		  <?php if($range!=='upcoming' || !empty($from) || !empty($to)): ?>
		  <a href="<?php echo admin_url('lims/appointments'); ?>" class="btn btn-default mleft5">
			<?php echo _l('reset') ?: 'Reset'; ?>
		  </a>
		  <?php endif; ?>
		</form>


        <div class="table-responsive">
          <table class="table dt-table" data-order-col="2" data-order-type="desc">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo _l('lims_subject'); ?></th>
                <th><?php echo _l('client'); ?></th>
                <th><?php echo _l('date'); ?></th>
                <th><?php echo _l('type'); ?></th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('staff'); ?></th>
                <th><?php echo _l('order'); ?></th>
                <th style="text-align:right;"><?php echo _l('options'); ?></th>
              </tr>
            </thead>
            <tbody>
               <?php if(!empty($rows)) foreach($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r->id; ?></td>
				
				<td>
				  <?php if (!empty($r->subject_id)): ?>
					<?php
					  $subj = $this->db
						->where('id', (int)$r->subject_id)
						->get(db_prefix().'lims_subjects')
						->row();

					  if ($subj) {
						  // Προσπαθούμε να βρούμε λογικό "όνομα"
						  $name = '';
						  if (!empty($subj->subject_name)) {
							  $name = $subj->subject_name;
						  } elseif (!empty($subj->first_name) || !empty($subj->last_name)) {
							  $name = trim(($subj->first_name ?? '').' '.($subj->last_name ?? ''));
						  } elseif (!empty($subj->name)) {
							  $name = $subj->name;
						  } else {
							  $name = 'Subject #'.(int)$r->subject_id;
						  }

						  $subname = html_escape($name).' (#'.(int)$r->subject_id.')';
					  } else {
						  $subname = 'Subject #'.(int)$r->subject_id;
					  }
					  
					?>
					<a href="<?php echo admin_url('lims/subjects/view/' . (int)$r->subject_id); ?>">
					  <?php echo $subname; ?>
					</a>
				  <?php else: ?>
					<span class="text-muted">—</span>
				  <?php endif; ?>
				</td>
				
                <td><?php echo html_escape($r->client_name).' (#'.(int)$r->client_id.')'; ?></td>
                <td data-order="<?php echo strtotime($r->appointment_at ?? ''); ?>"><?php echo _dt($r->appointment_at); ?></td>
                <td><?php echo $r->visit_type==='home'?'Home':'Lab'; ?></td>
                <td><?php echo ucfirst($r->status); ?></td>
                <td><?php echo $r->firstname ? html_escape($r->firstname.' '.$r->lastname) : '-'; ?></td>
                <td>
                  <?php if(!empty($r->order_id)): ?>
                    <a href="<?php echo admin_url('lims/orders/view/'.(int)$r->order_id); ?>">Order - #<?php echo (int)$r->order_id; ?></a>
                  <?php else: ?>-<?php endif; ?>
                </td>
               <td style="text-align:right;">
				  <a href="<?php echo admin_url('lims/appointments/show/'.(int)$r->id); ?>" class="btn btn-default" title="<?php echo _l('view'); ?>">
					<i class="fa fa-eye"></i>
				  </a>
				  <a href="<?php echo admin_url('lims/appointments/create/'.(int)$r->id); ?>" class="btn btn-default" title="<?php echo _l('edit'); ?>">
					<i class="fa fa-pencil"></i>
				  </a>
				  <a href="<?php echo admin_url('lims/appointments/delete/'.(int)$r->id); ?>" class="btn btn-danger _delete" title="<?php echo _l('delete'); ?>">
					<i class="fa fa-trash"></i>
				  </a>
				</td>

              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  $(function(){
    $('#ap-range').on('change', function(){
      if ($(this).val()==='custom') $('#ap-custom-range').slideDown(120);
      else $('#ap-custom-range').slideUp(120);
    });
  });
})(jQuery);
</script>
