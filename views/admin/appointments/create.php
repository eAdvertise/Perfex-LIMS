<!-- lims/views/admin/appointments/create.php -->
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <h4 class="mtop5"><?php echo _l('lims_appointment'); ?><?php echo isset($row->id)?' #'.(int)$row->id:''; ?></h4>

        <?php echo form_open(admin_url('lims/appointments/save'.(isset($row->id)?'/'.$row->id:''))); ?>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_subject') ?: 'Subject'; ?></label>
				<select name="subject_id"
						class="form-control selectpicker"
						data-live-search="true"
						id="ap-subject">
				  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
				  <?php foreach($subjects as $sb): ?>
					<?php
					  $sel = $subject_id ?? ($row->subject_id ?? 0);
					  // Προσπάθεια για όνομα subject
						$name = '';
						if (!empty($sb->subject_name)) {
						  $name = $sb->subject_name;
						} elseif (!empty($sb->name)) {
						  $name = $sb->name;
						} else {
						  $fn = $sb->firstname ?? $sb->first_name ?? '';
						  $ln = $sb->lastname  ?? $sb->last_name  ?? '';
						  $name = trim($fn.' '.$ln);
						}
						if ($name === '') {
						  $name = 'Subject #'.(int)$sb->id;
						}
					?>
					<option value="<?php echo (int)$sb->id; ?>"
							<?php echo ((int)$sel === (int)$sb->id) ? 'selected' : ''; ?>>
					  <?php echo html_escape($name).' (#'.(int)$sb->id.')'; ?>
					</option>
				  <?php endforeach; ?>
				</select>

              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('date'); ?></label>
                <?php
				  $apptValue = '';
				  if (isset($row->appointment_at)) {
					  // edit mode
					  $apptValue = date('Y-m-d\TH:i', strtotime($row->appointment_at));
				  } else {
					  // new: default now
					  $apptValue = date('Y-m-d\TH:i');
				  }
				?>
				<input type="datetime-local" class="form-control" name="appointment_at"
					   value="<?php echo html_escape($apptValue); ?>" required>

              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('type'); ?></label>
                <select name="visit_type" class="form-control">
                  <option value="lab"  <?php echo (isset($row->visit_type) && $row->visit_type==='lab')?'selected':''; ?>>Lab</option>
                  <option value="home" <?php echo (isset($row->visit_type) && $row->visit_type==='home')?'selected':''; ?>>Home</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row mtop10">
            <div class="col-md-12">
              <label class="display-block"><?php echo _l('link'); ?></label>
              <?php
                $curMode = 'none';
                if(isset($row->order_id) && $row->order_id){ $curMode='existing'; }
              ?>
              <div class="radio radio-primary inline">
                <input type="radio" id="lm_none" name="link_mode" value="none" <?php echo $curMode==='none'?'checked':''; ?>>
                <label for="lm_none"><?php echo _l('no_link') ?: 'No link'; ?></label>
              </div>
              <div class="radio radio-primary inline mleft10">
                <input type="radio" id="lm_existing" name="link_mode" value="existing" <?php echo $curMode==='existing'?'checked':''; ?>>
                <label for="lm_existing"><?php echo _l('link_existing_order') ?: 'Link existing Order'; ?></label>
              </div>
              <div class="radio radio-primary inline mleft10">
                <input type="radio" id="lm_new" name="link_mode" value="new">
                <label for="lm_new"><?php echo _l('create_new_order') ?: 'Create new Order'; ?></label>
              </div>
            </div>
          </div>

          <div class="row mtop10" id="wrap-order-select" style="display:none;">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo _l('order'); ?></label>
                <select name="order_id" id="order_id" class="form-control selectpicker" data-live-search="true">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php foreach($orders as $o): ?>
                    <option value="<?php echo (int)$o->id; ?>" <?php
                      $pre = $order_id ?? ($row->order_id ?? 0);
                      echo ((int)$pre === (int)$o->id)?'selected':'';
                    ?>>#<?php echo (int)$o->id; ?> — <?php echo ucfirst($o->status); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mtop10">
			<div class="col-md-6">
			  <div class="form-group">
				<label><?php echo _l('location'); ?></label>
				<div class="input-group">
					<?php
					  $loc_val = $row->location_text
						  ?? ($subject_addr ?? '');
					?>
					<input type="text" class="form-control" id="ap-location" name="location_text"
						   value="<?php echo html_escape($loc_val); ?>"
						   placeholder="<?php echo _l('lims_map_address_placeholder') ?: 'Type an address or drop a pin...'; ?>">
						
				  <span class="input-group-btn">
					<button type="button" class="btn btn-default" id="ap-locate-btn" title="<?php echo _l('lims_search_on_map') ?: 'Search on map'; ?>">
					  <i class="fa fa-search"></i>
					</button>
				  </span>
				  
				</div>
			  </div>
			</div>
			
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('status'); ?></label>
                <select name="status" class="form-control">
                  <?php $st = $row->status ?? 'pending'; ?>
                  <?php foreach(['pending','confirmed','completed','canceled','no_show'] as $opt): ?>
                    <option value="<?php echo $opt; ?>" <?php echo $st===$opt?'selected':''; ?>><?php echo ucfirst($opt); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('staff'); ?></label>
                <select name="assigned_staff" class="form-control selectpicker" data-live-search="true">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php foreach($staff as $s): ?>
                    <option value="<?php echo (int)$s['staffid']; ?>" <?php echo (isset($row->assigned_staff) && (int)$row->assigned_staff===(int)$s['staffid'])?'selected':''; ?>>
                      <?php echo html_escape($s['firstname'].' '.$s['lastname']).' (#'.(int)$s['staffid'].')'; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
			<div class="col-md-12">
			  <div class="form-group">
				<?php
				  $lat_val = isset($row->lat)
					  ? $row->lat
					  : ($subject_lat ?? '');
				  $lng_val = isset($row->lng)
					  ? $row->lng
					  : ($subject_lng ?? '');
				?>
				<input type="hidden" name="lat" id="ap-lat" value="<?php echo html_escape($lat_val); ?>">
				<input type="hidden" name="lng" id="ap-lng" value="<?php echo html_escape($lng_val); ?>">

				<small class="text-muted"><?php echo _l('lims_pick_on_map_hint') ?: 'Drop the pin on the map or type an address.'; ?></small>

				<div id="ap-map" style="height:320px;margin-top:8px;border:1px solid #eee;border-radius:4px;"></div>
			  </div>
			</div>
          </div>

          <div class="checkbox checkbox-primary mtop10">
            <input type="checkbox" id="make_task" name="make_task" <?php echo isset($row->task_id) ? 'checked disabled' : 'checked'; ?>>
            <label for="make_task"><?php echo _l('create_task') ?: 'Create Task'; ?></label>
          </div>

          <div class="form-group mtop10">
            <label><?php echo _l('notes'); ?></label>
            <textarea class="form-control" rows="3" name="notes"><?php echo html_escape($row->notes ?? ''); ?></textarea>
          </div>

          <?php if(!empty($order_id)): ?>
            <input type="hidden" name="return_to_order" value="1">
          <?php endif; ?>

          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('save'); ?></button>
            <a href="<?php echo admin_url('lims/appointments'); ?>" class="btn btn-default"><?php echo _l('cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>
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
  var map, marker;
  var $addr = $('#ap-location');
  var $btn = $('#ap-locate-btn');

  function initMap() {
	  var defaultCenter = [35.1264, 33.4299]; // Κύπρος
	  var defaultZoom   = 8;

	  // προσπάθησε πρώτα από υπάρχον lat/lng (edit mode)
	  var initLat = parseFloat($('#ap-lat').val() || '');
	  var initLng = parseFloat($('#ap-lng').val() || '');
	  var start   = defaultCenter;
	  var zoom    = defaultZoom;

	  if (!isNaN(initLat) && !isNaN(initLng)) {
		start = [initLat, initLng];
		zoom  = 15;
	  }

	  map = L.map('ap-map').setView(start, zoom);

	  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; OpenStreetMap contributors'
	  }).addTo(map);

	  marker = L.marker(start, {draggable:true}).addTo(map);

	  marker.on('dragend', function(){
		var latlng = marker.getLatLng();
		reverseGeocode(latlng.lat, latlng.lng, true);
		writeLatLng(latlng.lat, latlng.lng);
	  });

	  map.on('click', function(e){
		marker.setLatLng(e.latlng);
		reverseGeocode(e.latlng.lat, e.latlng.lng, true);
		writeLatLng(e.latlng.lat, e.latlng.lng);
	  });

	  // Αν ΔΕΝ είχε lat/lng, τότε κάνε ό,τι είχες πριν (address ή geolocation)
	  if (isNaN(initLat) || isNaN(initLng)) {
		var initialAddr = $addr.val().trim();
		if (initialAddr) {
		  geocode(initialAddr, function(lat, lng){
			map.setView([lat,lng], 15);
			marker.setLatLng([lat,lng]);
			writeLatLng(lat, lng);
		  });
		} else if (navigator.geolocation) {
		  navigator.geolocation.getCurrentPosition(function(pos){
			var lat = pos.coords.latitude, lng = pos.coords.longitude;
			map.setView([lat,lng], 13);
			marker.setLatLng([lat,lng]);
			writeLatLng(lat, lng);
			reverseGeocode(lat, lng, false);
		  });
		}
	  }
	}


  function geocode(query, cb){
    if (!query) return;
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    fetch('https://nominatim.openstreetmap.org/search?format=jsonv2&q=' + encodeURIComponent(query))
      .then(function(r){ return r.json(); })
      .then(function(rows){
        if (rows && rows.length) {
          var r = rows[0];
          var lat = parseFloat(r.lat), lng = parseFloat(r.lon);
          if (cb) cb(lat, lng);
		  writeLatLng(lat, lng);
        }
      })
      .catch(function(){ /* ignore */ })
      .finally(function(){
        $btn.prop('disabled', false).html('<i class="fa fa-search"></i>');
      });
  }

  function reverseGeocode(lat, lng, write){
    // Ανάποδη γεωκωδικοποίηση για να ενημερώσουμε τη διεύθυνση
    fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lat+'&lon='+lng)
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (write && data && data.display_name) {
          $addr.val(data.display_name);
        }
      })
      .catch(function(){ /* ignore */ });
  }
	function writeLatLng(lat, lng){
	  $('#ap-lat').val(lat);
	  $('#ap-lng').val(lng);
	}


  $(function(){
    initMap();

    $btn.on('click', function(){
		var initLat = parseFloat($('#ap-lat').val() || '');
		var initLng = parseFloat($('#ap-lng').val() || '');
		if (!isNaN(initLat) && !isNaN(initLng)) {
		  map.setView([initLat, initLng], 15);
		  marker.setLatLng([initLat, initLng]);
		}

      var q = $addr.val().trim();
      if (q) {
        geocode(q, function(lat, lng){
          map.setView([lat,lng], 15);
          marker.setLatLng([lat,lng]);
        });
      }
    });
  });
  
	function toggleOrderSelect(){
	  var mode = $('input[name="link_mode"]:checked').val();
	  if(mode === 'existing'){ $('#wrap-order-select').slideDown(120); }
	  else { $('#wrap-order-select').slideUp(120); }
	}

	function reloadOrdersForSubject(subjectId, preselect){
	  if(!subjectId) return;
	  $.getJSON('<?php echo admin_url('lims/appointments/orders_by_subject/'); ?>'+subjectId, function(rows){
		var $sel = $('#order_id');
		$sel.empty().append(
		  $('<option/>',{
			value:'',
			text:'<?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?>'
		  })
		);
		$.each(rows, function(_, r){
		  var t = '#'+r.id+' — '+ (r.status ? r.status.charAt(0).toUpperCase()+r.status.slice(1) : '');
		  var opt = $('<option/>',{value:r.id,text:t});
		  if(preselect && parseInt(preselect,10)===parseInt(r.id,10)) opt.attr('selected','selected');
		  $sel.append(opt);
		});
		if($.fn.selectpicker){ $sel.selectpicker('refresh'); }
	  });
	}

	$(function(){
	  $('input[name="link_mode"]').on('change', toggleOrderSelect);
	  toggleOrderSelect();

	  $('#ap-subject').on('changed.bs.select change', function(){
		var sid = $(this).val();
		reloadOrdersForSubject(sid, null);
	  });

	  // initial load if subject preset
	  var initSubject = $('#ap-subject').val();
	  <?php if(!empty($order_id)): ?>
		reloadOrdersForSubject(initSubject, '<?php echo (int)$order_id; ?>');
	  <?php endif; ?>
	});
})(jQuery);
</script>
