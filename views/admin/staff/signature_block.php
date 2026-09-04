<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="lims-signature-block" class="panel_s mtop20">
  <div class="panel-body">
    <h4 class="mbot10">
      <i class="fa fa-pencil"></i>
      <?php echo _l('lims_staff_signature') ?: 'Signature'; ?>
    </h4>

    <div class="row">
      <div class="col-md-4">
        <div class="lims-signature-preview" style="border:1px solid #ddd;min-height:80px;padding:5px;text-align:center;">
          <?php if (!empty($signature_url)) : ?>
            <img src="<?php echo $signature_url . '?v=' . time(); ?>"
                 alt="Signature"
                 style="max-width:100%;max-height:120px;">
          <?php else : ?>
            <span class="text-muted">
              <?php echo _l('lims_no_signature_uploaded') ?: 'No signature uploaded'; ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-md-8">
        <form id="lims-signature-upload-form"
              enctype="multipart/form-data"
              method="post"
              action="<?php echo admin_url('lims/signatures/upload'); ?>">

          <input type="hidden" name="staff_id" value="<?php echo (int)$staff_id; ?>">

          <div class="form-group mtop10">
            <label><?php echo _l('lims_staff_signature_image') ?: 'Signature image'; ?></label>
            <input type="file"
                   name="signature_image"
                   class="form-control"
                   accept="image/*">
            <p class="help-block small text-muted">
              <?php echo _l('lims_staff_signature_hint') ?: 'Upload transparent PNG or JPG of your signature.'; ?>
            </p>
          </div>

          <button type="submit" class="btn btn-default">
            <i class="fa fa-upload"></i> <?php echo _l('upload'); ?>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
(function($){
  "use strict";

  $(function(){

    var $block = $('#lims-signature-block');
    if (!$block.length) { return; }

    // Προσπάθησε να το βάλεις στο tab "Profile"
    var $target = $('#tab_profile .panel-body').first();

    if (!$target.length) {
      // fallback: πρώτο panel-body στη σελίδα
      $target = $('.content .panel-body').first();
    }

    if ($target.length) {
      $target.append($block);
    }

    $('#lims-signature-upload-form').on('submit', function(e){
      e.preventDefault();

      var $form = $(this);
      var $btn  = $form.find('button[type="submit"]');
      var fd    = new FormData(this);

      $btn.prop('disabled', true)
          .append(' <i class="fa fa-spinner fa-spin"></i>');

      $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json'
      }).done(function(resp){
        if (resp && resp.success) {
          var $prev = $('.lims-signature-preview');
          var $img = $('<img/>', {
            src: resp.url,
            alt: 'Signature'
          }).css({maxWidth:'100%', maxHeight:'120px'});

          $prev.empty().append($img);
        } else {
          alert((resp && resp.message) || '<?php echo _l('problem_uploading') ?: 'Upload error'; ?>');
        }
      }).fail(function(){
        alert('<?php echo _l('problem_uploading') ?: 'Upload error'; ?>');
      }).always(function(){
        $btn.prop('disabled', false)
            .find('.fa-spinner').remove();
      });
    });

  });
})(jQuery);
</script>
