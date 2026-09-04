<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
/** @var object $member (συνήθως υπάρχει στο staff profile) */
$CI = &get_instance();

// Πάρε το staff id από $member ή από το URL (/admin/staff/member/{id})
$staff_id = isset($member) && isset($member->staffid)
    ? (int)$member->staffid
    : (int)$CI->uri->segment(4);

// Φέρε (αν υπάρχει) την υπογραφή από τη δική σου lims table
// Προσαρμόζεις το table/columns αν έχεις άλλο schema
$signature = $CI->db
    ->where('staff_id', $staff_id)
    ->get(db_prefix() . 'lims_signatures')
    ->row();

$title       = $signature->title       ?? '';
$license_no  = $signature->license_no  ?? '';
$extra_line  = $signature->extra_line  ?? '';
$image_file  = $signature->image_file  ?? ''; // π.χ. όνομα αρχείου

$img_url = '';
if ($image_file) {
    // Υποθέτουμε ότι τα αρχεία τα αποθηκεύεις κάπου τύπου /uploads/lims_signatures/
    $img_url = base_url('uploads/lims_signatures/' . $image_file);
}
?>

<div class="panel_s">
    <div class="panel-body">
        <h4 class="mbot20">
            <i class="fa fa-flask mright5"></i>
            <?php echo _l('lims_staff_signature_heading'); ?>
        </h4>

        <?php echo form_open_multipart(admin_url('lims/signatures/staff_profile_save/' . $staff_id)); ?>

        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input(
                            'title',
                            'lims_staff_signature_title',
                            $title
                        ); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input(
                            'license_no',
                            'lims_staff_signature_lic',
                            $license_no
                        ); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input(
                            'extra_line',
                            'lims_staff_signature_extra',
                            $extra_line
                        ); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="signature_file" class="control-label">
                        <?php echo _l('lims_staff_signature_image'); ?>
                    </label>
                    <input type="file"
                           name="signature_file"
                           id="signature_file"
                           class="form-control" />
                    <p class="help-block">
                        PNG με διαφανές background είναι ιδανικό.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                    <?php echo _l('lims_staff_signature_save'); ?>
                </button>
            </div>

            <div class="col-md-5">
                <h5><?php echo _l('preview'); ?></h5>
                <div style="border:1px solid #ddd;padding:15px;min-height:120px;">
                    <?php if ($img_url): ?>
                        <img src="<?php echo html_escape($img_url); ?>"
                             alt="Signature"
                             style="max-width:100%;height:auto;">
                        <hr/>
                    <?php else: ?>
                        <p class="text-muted">
                            <?php echo _l('no_preview_available'); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($title || $license_no || $extra_line): ?>
                        <div class="text-left mtop10">
                            <?php if ($title): ?>
                                <div><?php echo html_escape($title); ?></div>
                            <?php endif; ?>
                            <?php if ($license_no): ?>
                                <div><?php echo html_escape($license_no); ?></div>
                            <?php endif; ?>
                            <?php if ($extra_line): ?>
                                <div><?php echo html_escape($extra_line); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
