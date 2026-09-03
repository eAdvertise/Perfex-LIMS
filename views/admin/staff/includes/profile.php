<div class="panel_s">
  <div class="panel-heading"><?php echo _l('lims_signature'); ?></div>
  <div class="panel-body">
    <?php echo form_open_multipart(admin_url('lims/staff/save_signature/' . $member->staffid)); ?>
      <div class="form-group">
        <label><?php echo _l('lims_signature_image'); ?></label>
        <?php if (!empty($member->lims_signature)): ?>
          <div class="mbot10">
            <img src="<?php echo base_url('uploads/lims_signatures/' . $member->lims_signature); ?>"
                 style="max-width:220px;max-height:80px;border:1px solid #eee;padding:4px;background:#fff;">
          </div>
        <?php endif; ?>
        <input type="file" name="signature" class="form-control">
        <small class="text-muted"><?php echo _l('lims_signature_hint'); ?></small>
      </div>
      <button type="submit" class="btn btn-default"><?php echo _l('save'); ?></button>
    <?php echo form_close(); ?>
  </div>
</div>
