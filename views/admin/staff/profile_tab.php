<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
// $member περνάει από το core view (member.php)
$staff_id = isset($member) ? (int)$member->staffid : (int)get_staff_user_id();

// φέρε την υπογραφή (αν έχει)
$CI =& get_instance();
$CI->db->select('lims_signature');
$CI->db->from(db_prefix().'staff');
$CI->db->where('staffid', $staff_id);
$row = $CI->db->get()->row();

$signature_path = ($row && !empty($row->lims_signature)) ? $row->lims_signature : null;
?>

<div class="row">
  <div class="col-md-6">
    <h4 class="tw-mb-4"><?php echo _l('lims_staff_tab_title') ?: 'LIMS'; ?></h4>

    <?php echo form_open_multipart(admin_url('lims/signatures/upload/'.$staff_id), [
        'id'    => 'lims-staff-signature-form',
        'class' => 'lims-staff-signature-form',
    ]); ?>

      <div class="form-group">
        <label for="lims_signature_file">
          <?php echo _l('lims_staff_signature_label') ?: 'Signature image'; ?>
        </label>
        <input type="file"
               name="lims_signature_file"
               id="lims_signature_file"
               class="form-control" />
        <p class="help-block">
          <?php echo _l('lims_staff_signature_help') ?: 'PNG/JPG, μικρό πλάτος (π.χ. 400px).'; ?>
        </p>
      </div>

      <button type="submit" class="btn btn-primary">
        <?php echo _l('submit'); ?>
      </button>

    <?php echo form_close(); ?>
  </div>

  <div class="col-md-6">
    <h4 class="tw-mb-4"><?php echo _l('lims_staff_signature_preview') ?: 'Current signature'; ?></h4>

    <?php if ($signature_path): ?>
      <p class="tw-mb-2">
        <?php echo _l('lims_staff_signature_current') ?: 'Current image:'; ?>
      </p>
      <img src="<?php echo base_url($signature_path); ?>"
           alt="Signature"
           class="img img-responsive"
           style="max-width: 300px; border:1px solid #ddd; padding:5px; background:#fff;" />
    <?php else: ?>
      <p class="text-muted">
        <?php echo _l('lims_staff_signature_none') ?: 'No signature uploaded yet.'; ?>
      </p>
    <?php endif; ?>
  </div>
</div>
