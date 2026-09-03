<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI =& get_instance();
$CI->load->model('lims/teststatuses_model','ts_model');
$rows = $CI->ts_model->all();
?>
<div class="row">
  <div class="col-md-12">
    <div class="clearfix mbot15">
      <h4 class="pull-left mtop5"><?php echo _l('lims_test_statuses'); ?></h4>
      <button class="btn btn-primary pull-right"
              type="button"
              data-toggle="modal"
              data-target="#tsModal"
              data-mode="create">
        <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
      </button>
    </div>

    <div class="table-responsive">
      <table class="table dt-table">
        <thead>
        <tr>
          <th>#</th>
          <th><?php echo _l('name'); ?></th>
          <th><?php echo _l('code'); ?></th>
          <th><?php echo _l('color'); ?></th>
          <th><?php echo _l('position'); ?></th>
          <th><?php echo _l('default'); ?></th>
          <th><?php echo _l('active'); ?></th>
          <th><?php echo _l('options'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $r): ?>
          <tr
            data-id="<?php echo (int)$r->id; ?>"
            data-name="<?php echo html_escape($r->name); ?>"
            data-code="<?php echo html_escape($r->code); ?>"
            data-color="<?php echo html_escape($r->color); ?>"
            data-reqres="<?php echo (int)$r->requires_result; ?>"
            data-reqver="<?php echo (int)$r->requires_verification; ?>"
            data-reqapp="<?php echo (int)$r->requires_approval; ?>"
            data-terminal="<?php echo (int)$r->is_terminal; ?>"
            data-active="<?php echo (int)$r->active; ?>"
          >
            <td><?php echo (int)$r->id; ?></td>
            <td><?php echo html_escape($r->name); ?></td>
            <td><code><?php echo html_escape($r->code); ?></code></td>
            <td>
              <?php if($r->color): ?>
                <span class="label" style="background:<?php echo html_escape($r->color); ?>;">&nbsp;&nbsp;&nbsp;</span>
                <small class="text-muted mleft5"><?php echo html_escape($r->color); ?></small>
              <?php endif; ?>
            </td>
            <td>
              <div class="btn-group btn-group-xs">
                <button type="button" class="btn btn-default btn-move" data-id="<?php echo (int)$r->id; ?>" data-dir="up">
                  <i class="fa fa-chevron-up"></i>
                </button>
                <button type="button" class="btn btn-default btn-move" data-id="<?php echo (int)$r->id; ?>" data-dir="down">
                  <i class="fa fa-chevron-down"></i>
                </button>
              </div>
            </td>
            <td>
              <div class="onoffswitch">
                <input type="checkbox"
                       class="onoffswitch-checkbox sw-default"
                       id="def_<?php echo (int)$r->id; ?>"
                       data-id="<?php echo (int)$r->id; ?>"
                       <?php echo (int)$r->is_default===1?'checked':''; ?>>
                <label class="onoffswitch-label" for="def_<?php echo (int)$r->id; ?>"></label>
              </div>
            </td>
            <td>
              <div class="onoffswitch">
                <input type="checkbox"
                       class="onoffswitch-checkbox sw-active"
                       id="act_<?php echo (int)$r->id; ?>"
                       data-id="<?php echo (int)$r->id; ?>"
                       <?php echo (int)$r->active===1?'checked':''; ?>>
                <label class="onoffswitch-label" for="act_<?php echo (int)$r->id; ?>"></label>
              </div>
            </td>
            <td>
              <button type="button"
                      class="btn btn-default btn-sm btn-edit"
                      data-toggle="modal"
                      data-target="#tsModal"
                      data-mode="edit"
                      data-id="<?php echo (int)$r->id; ?>">
                <i class="fa fa-pencil"></i>
              </button>
              <a href="<?php echo admin_url('lims/teststatuses/delete/'.$r->id); ?>"
                 class="btn btn-danger btn-sm _delete">
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

<!-- Modal: Create/Edit -->
<div class="modal fade" id="tsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('', ['id'=>'tsForm']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title"><?php echo _l('lims_test_statuses'); ?></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="from" value="lims-test-statuses">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><?php echo _l('name'); ?></label>
              <input type="text" class="form-control" name="name" required>
              <small class="help-block text-muted">
                <?php echo _l('lims_ts_name_desc') ?: 'Display name shown to users.'; ?>
              </small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><?php echo _l('code'); ?></label>
              <input type="text" class="form-control" name="code" required placeholder="e.g. in_progress">
              <small class="help-block text-muted">
                <?php echo _l('lims_ts_code_desc') ?: 'Unique slug (latin, no spaces).'; ?>
              </small>
            </div>
          </div>
        </div>

        <div class="row mtop10">
          <div class="col-md-4">
            <div class="form-group">
              <label><?php echo _l('color'); ?></label>
              <input type="text" class="form-control" name="color" placeholder="#3a87ad">
            </div>
          </div>
          <div class="col-md-8">
            <div class="checkbox checkbox-primary mtop25">
              <input type="checkbox" name="requires_result" id="requires_result">
              <label for="requires_result"><?php echo _l('lims_ts_requires_result'); ?></label>

              <input type="checkbox" name="requires_verification" id="requires_verification" class="mleft20">
              <label for="requires_verification"><?php echo _l('lims_ts_requires_verification'); ?></label>

              <input type="checkbox" name="requires_approval" id="requires_approval" class="mleft20">
              <label for="requires_approval"><?php echo _l('lims_ts_requires_approval'); ?></label>

              <input type="checkbox" name="is_terminal" id="is_terminal" class="mleft20">
              <label for="is_terminal"><?php echo _l('lims_ts_is_terminal'); ?></label>

              <div class="pull-right">
                <div class="onoffswitch">
                  <input type="checkbox" name="active" class="onoffswitch-checkbox" id="ts_active">
                  <label class="onoffswitch-label" for="ts_active"></label>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="tsSubmit"><?php echo _l('save'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script>
(function waitForJq(){
  // Περιμένουμε μέχρι να φορτώσει το jQuery στο settings page
  if (typeof window.jQuery === 'undefined') {
    setTimeout(waitForJq, 50);
    return;
  }

  var $ = window.jQuery;
  var editId = null;
  var BASE_ADMIN_URL = (typeof window.admin_url !== 'undefined' && window.admin_url)
    ? window.admin_url
    : '<?php echo rtrim(admin_url(), '/'); ?>/';

  function fillFormFromRow($tr){
    $('input[name="name"]').val($tr.data('name') || '');
    $('input[name="code"]').val($tr.data('code') || '');
    $('input[name="color"]').val($tr.data('color') || '');

    $('#requires_result').prop('checked', !!parseInt($tr.data('reqres') || 0, 10));
    $('#requires_verification').prop('checked', !!parseInt($tr.data('reqver') || 0, 10));
    $('#requires_approval').prop('checked', !!parseInt($tr.data('reqapp') || 0, 10));
    $('#is_terminal').prop('checked', !!parseInt($tr.data('terminal') || 0, 10));

    var active = $tr.data('active');
    $('#ts_active').prop('checked', (typeof active === 'undefined') ? true : !!parseInt(active, 10));
  }

  // Άνοιγμα modal (create / edit)
  $('#tsModal').on('show.bs.modal', function(e){
    var $trigger = $(e.relatedTarget || this);

    // Αν το click ήταν πάνω στο <i>, ανεβαίνουμε στο button που έχει data-mode
    $trigger = $trigger.closest('[data-mode]');
    var mode = $trigger.data('mode') || 'create';
    editId   = null;

    if (mode === 'edit') {
      var $tr = $trigger.closest('tr');
      editId = parseInt($tr.data('id') || 0, 10);

      if ($('#tsForm')[0]) {
        $('#tsForm')[0].reset();
      }
      fillFormFromRow($tr);

      $('#tsForm').attr('action', BASE_ADMIN_URL + 'lims/teststatuses/create/' + editId + '?from=lims-test-statuses');
    } else {
      // +New
      if ($('#tsForm')[0]) {
        $('#tsForm')[0].reset();
      }
      $('#ts_active').prop('checked', true);
      $('#tsForm').attr('action', BASE_ADMIN_URL + 'lims/teststatuses/create?from=lims-test-statuses');
    }
  });

  // Intercept submit (για να μην γίνει submit του μεγάλου settings-form)
  $('#tsForm').on('submit', function(e){
    e.preventDefault();
    e.stopPropagation();

    var $form = $(this);
    var url   = $form.attr('action') || (BASE_ADMIN_URL + 'lims/teststatuses/create?from=lims-test-statuses');

    $.post(url, $form.serialize())
      .done(function(resp){
        // Δεν μας νοιάζει το περιεχόμενο, απλά ανανεώνουμε τη λίστα
        $('#tsModal').modal('hide');
        setTimeout(function(){ location.reload(); }, 200);
      })
      .fail(function(){
        alert('Error saving test status.');
      });
  });

  // Active toggle
  $(document).on('change', '.sw-active', function(){
    $.post(BASE_ADMIN_URL + 'lims/teststatuses/toggle_active', {
      id: $(this).data('id'),
      active: $(this).is(':checked') ? 1 : 0
    });
  });

  // Default toggle
  $(document).on('change', '.sw-default', function(){
    if ($(this).is(':checked')) {
      $.post(BASE_ADMIN_URL + 'lims/teststatuses/set_default', {
        id: $(this).data('id')
      }).done(function(){
        location.reload();
      });
    } else {
      // Δεν επιτρέπουμε να μην υπάρχει default
      location.reload();
    }
  });

  // Move up/down (Position)
  $(document).on('click', '.btn-move', function(e){
    e.preventDefault();
    $.post(BASE_ADMIN_URL + 'lims/teststatuses/move', {
      id:  $(this).data('id'),
      dir: $(this).data('dir')
    }).done(function(){
      location.reload();
    });
  });

})();
</script>
