<!-- lims/views/admin/appointments/show.php -->
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="clearfix mtop10 mbot10">
          <h3 class="pull-left">
            <?php echo _l('lims_appointment'); ?>
            <?php echo isset($row->id) ? ' #'.(int)$row->id : ''; ?>
          </h3>
          <div class="pull-right">
            <?php if (isset($row->id)): ?>
              <a class="btn btn-default" href="<?php echo admin_url('lims/appointments/create/'.(int)$row->id); ?>">
                <i class="fa fa-pencil"></i> <?php echo _l('edit'); ?>
              </a>
              <a class="btn btn-danger _delete" href="<?php echo admin_url('lims/appointments/delete/'.(int)$row->id); ?>">
                <i class="fa fa-trash"></i> <?php echo _l('delete'); ?>
              </a>
            <?php endif; ?>
            <a class="btn btn-default" href="<?php echo admin_url('lims/appointments'); ?>">
              <i class="fa fa-long-arrow-left"></i> <?php echo _l('back'); ?>
            </a>
          </div>
        </div>

        <?php if (!isset($row) || !$row): ?>
          <div class="alert alert-warning"><?php echo _l('no_results_found') ?: 'Not found.'; ?></div>
        <?php else: ?>

        <div class="row">
          <div class="col-md-6">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th style="width:35%"><?php echo _l('client'); ?></th>
                  <td>
                    <?php if (!empty($row->client_id)): ?>
                      <a href="<?php echo admin_url('clients/client/'.(int)$row->client_id); ?>" target="_blank">
                        <?php echo html_escape($row->client_name ?? ('#'.(int)$row->client_id)); ?>
                      </a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo _l('created_at'); ?></th>
                  <td><?php echo !empty($row->created_at) ? _dt($row->created_at) : '-'; ?></td>
                </tr>
                <tr>
                  <th><?php echo _l('status'); ?></th>
                  <td><?php echo ucfirst($row->status ?? ''); ?></td>
                </tr>
                <tr>
                  <th><?php echo _l('location'); ?></th>
                  <td><?php echo !empty($row->location_text) ? nl2br(html_escape($row->location_text)) : '-'; ?></td>
                </tr>
				<tr>
				  <th><?php echo _l('coordinates') ?: 'Coordinates'; ?></th>
				  <td>
					<?php
					  $lat = isset($row->lat) ? (string)$row->lat : '';
					  $lng = isset($row->lng) ? (string)$row->lng : '';
					  echo ($lat !== '' && $lng !== '') ? (html_escape($lat).', '.html_escape($lng)) : '-';
					?>
				  </td>
				</tr>

              </tbody>
            </table>
          </div>

          <div class="col-md-6">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th style="width:35%"><?php echo _l('staff'); ?></th>
                  <td>
                    <?php if (!empty($row->assigned_staff)): ?>
                      <a href="<?php echo admin_url('staff/member/'.(int)$row->assigned_staff); ?>" target="_blank">
                        <?php echo html_escape(($row->staff_firstname ?? '').' '.($row->staff_lastname ?? '')). ' (#'.(int)$row->assigned_staff.')'; ?>
                      </a>
                    <?php else: ?>-<?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo _l('order'); ?></th>
                  <td>
                    <?php if (!empty($row->order_id)): ?>
                      <a href="<?php echo admin_url('lims/orders/view/'.(int)$row->order_id); ?>">#<?php echo (int)$row->order_id; ?></a>
                    <?php else: ?>-<?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo _l('task'); ?></th>
                  <td>
                    <?php if (!empty($row->task_id)): ?>
                      <a href="<?php echo admin_url('tasks/view/'.(int)$row->task_id); ?>" target="_blank">#<?php echo (int)$row->task_id; ?></a>
                    <?php else: ?>-<?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo _l('type'); ?></th>
                  <td><?php echo ($row->visit_type === 'home') ? 'Home' : 'Lab'; ?></td>
                </tr>
                <tr>
                  <th><?php echo _l('date'); ?></th>
                  <td><?php echo !empty($row->appointment_at) ? _dt($row->appointment_at) : '-'; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
		
			<?php if (!empty($row->lat) && !empty($row->lng)): ?>
			<div class="clearfix mtop10 mbot10">
				<h3 class="mbot10"><i class="fa fa-map-marker"></i> <?php echo _l('location'); ?></h3>
				<hr/>
				<div id="ap-show-map" style="height:320px;border:1px solid #eee;border-radius:4px;"></div>
			</div> 
			<?php endif; ?>
			
			<div class="clearfix mtop10 mbot10">
			
				<div class="clearfix mbot10 mtop10">
				  <h2 class="pull-left"><?php echo _l('notes'); ?></h2>
				  <div class="pull-right">
					<button type="button" class="btn btn-default" id="ap-notes-edit">
					  <i class="fa fa-pencil"></i> <?php echo _l('edit'); ?>
					</button>
					<button type="button" class="btn btn-success hide" id="ap-notes-save">
					  <i class="fa fa-check"></i> <?php echo _l('save'); ?>
					</button>
					<button type="button" class="btn btn-default hide" id="ap-notes-cancel">
					  <i class="fa fa-times"></i> <?php echo _l('cancel'); ?>
					</button>
				  </div>
				</div>
				  <hr/>
				<div id="ap-notes-view" class="tc-content">
				  <?php
					if (!empty($row->notes)) {
					  echo nl2br(html_escape($row->notes));
					} else {
					  echo '<span class="text-muted">' . (_l('no_notes_found') ?: 'No notes') . '</span>';
					}
				  ?>
				</div>

				<div id="ap-notes-edit-wrap" class="hide">
				  <textarea class="form-control" rows="5" id="ap-notes-text"><?php echo html_escape($row->notes ?? ''); ?></textarea>
				  <small class="text-muted"><?php echo _l('lims_notes_inline_hint') ?: 'Write your notes and click Save.'; ?></small>
				</div>
			 
			</div> 
			

        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
(function($){
  $(function(){
    var lat = <?php echo isset($row->lat) ? json_encode((float)$row->lat) : 'null'; ?>;
    var lng = <?php echo isset($row->lng) ? json_encode((float)$row->lng) : 'null'; ?>;
    if (lat !== null && lng !== null && document.getElementById('ap-show-map')) {
      var map = L.map('ap-show-map').setView([lat, lng], 15);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);
      L.marker([lat, lng]).addTo(map);
    }
	
    var $btnEdit   = $('#ap-notes-edit');
    var $btnSave   = $('#ap-notes-save');
    var $btnCancel = $('#ap-notes-cancel');
    var $view      = $('#ap-notes-view');
    var $wrapEdit  = $('#ap-notes-edit-wrap');
    var $ta        = $('#ap-notes-text');
    var original   = $ta.val();

    function toEditMode(){
      $btnEdit.addClass('hide'); $btnSave.removeClass('hide'); $btnCancel.removeClass('hide');
      $view.addClass('hide'); $wrapEdit.removeClass('hide');
      $ta.focus();
    }
    function toViewMode(){
      $btnEdit.removeClass('hide'); $btnSave.addClass('hide'); $btnCancel.addClass('hide');
      $wrapEdit.addClass('hide'); $view.removeClass('hide');
    }

    $btnEdit.on('click', function(){ toEditMode(); });

    $btnCancel.on('click', function(){
      $ta.val(original);
      toViewMode();
    });

    $btnSave.on('click', function(){
      var notes = $ta.val();
      $btnSave.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo _l('save'); ?>');
      $.post('<?php echo admin_url('lims/appointments/update_notes/'.(int)$row->id); ?>', {notes: notes})
       .done(function(resp){
          try { resp = (typeof resp === 'string') ? JSON.parse(resp) : resp; } catch(e){}
          if (resp && resp.success) {
            original = notes;
            // render escaped + nl2br
            var safe = $('<div/>').text(notes).html().replace(/\n/g,'<br>');
            if (safe.trim() === '') {
              $view.html('<span class="text-muted"><?php echo _l('no_notes_found') ?: 'No notes'; ?></span>');
            } else {
              $view.html(safe);
            }
            toViewMode();
            alert_float('success', resp.message || '<?php echo _l('updated_successfully', _l('notes')); ?>');
          } else {
            alert_float('danger', (resp && resp.message) ? resp.message : '<?php echo _l('problem_updating'); ?>');
          }
       })
       .fail(function(){
          alert_float('danger', '<?php echo _l('problem_updating'); ?>');
       })
       .always(function(){
          $btnSave.prop('disabled', false).html('<i class="fa fa-check"></i> <?php echo _l('save'); ?>');
       });
    });
  });
})(jQuery);
</script>
