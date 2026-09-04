<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-8">
                <h4 class="no-margin"><?php echo html_escape($title ?? _l('lims_report_notes')); ?></h4>
                <p class="text-muted mtop5"><?php echo _l('lims_report_notes_help'); ?></p>
              </div>
              <div class="col-md-4 text-right">
                <button type="button" class="btn btn-info" id="rn-btn-add">
                  <i class="fa fa-plus"></i> <?php echo _l('lims_report_note_new'); ?>
                </button>
              </div>
            </div>

            <hr class="hr-panel-heading" />

            <table class="table table-report-notes" id="report-notes-table">
              <thead>
                <tr>
                  <th><?php echo _l('id'); ?></th>
                  <th><?php echo _l('lims_report_note_code'); ?></th>
                  <th><?php echo _l('lims_report_note_greek'); ?></th>
                  <th><?php echo _l('lims_report_note_english'); ?></th>
                  <th><?php echo _l('active'); ?></th>
                  <th><?php echo _l('order'); ?></th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rn-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <?php echo form_open('#', ['id' => 'rn-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title" id="rn-modal-title"><?php echo _l('lims_report_note'); ?></h4>
      </div>

      <div class="modal-body">
        <?php echo form_hidden('id', 0); ?>

        <?php echo render_input('code', _l('lims_report_note_code_optional'), '', 'text'); ?>

        <div class="row">
          <div class="col-md-6">
            <?php echo render_textarea('note_el', _l('lims_report_note_text_el'), '', ['rows' => 5]); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_textarea('note_en', _l('lims_report_note_text_en'), '', ['rows' => 5]); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <?php echo render_input('sort_order', _l('lims_report_note_sort_order'), '0', 'number'); ?>
          </div>
          <div class="col-md-3">
            <div class="checkbox checkbox-primary mtop25">
              <input type="checkbox" name="active" id="rn-active" value="1" checked>
              <label for="rn-active"><?php echo _l('active'); ?></label>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('save'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<script>
(function($){
  "use strict";

  var table;

  var RN = {
    newTitle: <?php echo json_encode(_l('lims_report_note_new')); ?>,
    editTitle: <?php echo json_encode(_l('lims_report_note_edit')); ?>,
    confirmDelete: <?php echo json_encode(_l('lims_report_note_delete_confirm')); ?>,
    deleteFailed: <?php echo json_encode(_l('lims_report_note_delete_failed')); ?>,
    saveFailed: <?php echo json_encode(_l('lims_report_note_save_failed')); ?>
  };

  $(function(){
    table = initDataTable('#report-notes-table', admin_url + 'lims/report_notes/table', [6], [6]);

    function resetForm(){
      var $f = $('#rn-form');
      $f[0].reset();
      $f.find('input[name="id"]').val(0);
      $('#rn-active').prop('checked', true);
      $f.find('input[name="sort_order"]').val('0');
    }

    $('#rn-btn-add').on('click', function(){
      resetForm();
      $('#rn-modal-title').text(RN.newTitle);
      $('#rn-modal').modal('show');
    });

    $(document).on('click', '.js-rn-edit', function(e){
      e.preventDefault();
      resetForm();

      var $b = $(this);
      $('#rn-modal-title').text(RN.editTitle);

      $('#rn-form input[name="id"]').val($b.data('id'));
      $('#rn-form input[name="code"]').val($b.data('code') || '');
      $('#rn-form textarea[name="note_el"]').val($b.data('note_el') || '');
      $('#rn-form textarea[name="note_en"]').val($b.data('note_en') || '');
      $('#rn-form input[name="sort_order"]').val($b.data('sort_order') || 0);
      $('#rn-active').prop('checked', parseInt($b.data('active'),10) === 1);

      $('#rn-modal').modal('show');
    });

    $(document).on('click', '.js-rn-delete', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      if (!id) return;

      if (!confirm(RN.confirmDelete)) return;

      requestGetJSON(admin_url + 'lims/report_notes/delete/' + id).done(function(resp){
        if (resp && resp.success) {
          table.ajax.reload(null, false);
        } else {
          alert((resp && resp.message) ? resp.message : RN.deleteFailed);
        }
      });
    });

    $('#rn-form').on('submit', function(e){
      e.preventDefault();

      var data = $(this).serializeArray();

      // active checkbox normalize
      data.push({name:'active', value: $('#rn-active').is(':checked') ? 1 : 0});

      $.post(admin_url + 'lims/report_notes/save', $.param(data)).done(function(resp){
        try { resp = JSON.parse(resp); } catch(e) {}

        if (resp && resp.success) {
          $('#rn-modal').modal('hide');
          table.ajax.reload(null, false);
        } else {
          alert((resp && resp.message) ? resp.message : RN.saveFailed);
        }
      });
    });
  });

})(jQuery);
</script>

</body>
</html>
