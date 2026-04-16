<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$CI = &get_instance();

if (!isset($client_id)) {
    if (isset($client) && isset($client->userid)) {
        $client_id = (int)$client->userid;
    } else {
        $client_id = (int)$CI->input->get('userid');
    }
}

$subjects = $CI->db
    ->select('id, internal_code, subject_type, subject_name, first_name, last_name, email, phone, active, created_at')
    ->from(db_prefix() . 'lims_subjects')
    ->where('client_id', (int)$client_id)
    ->order_by('id', 'DESC');

if ($CI->db->field_exists('is_deleted', db_prefix() . 'lims_subjects')) {
    $CI->db->where('is_deleted', 0);
}

$subjects = $CI->db->get()->result();
?>

<div class="row">
    <div class="col-md-12">
        <div class="mbot15">
            <?php if (has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin')): ?>
                <a href="<?php echo admin_url('lims/subjects/create'); ?>" class="btn btn-primary">
                    <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
                </a>
            <?php endif; ?>
        </div>

        <hr class="hr-panel-heading"/>

        <?php if (empty($subjects)): ?>
            <p class="text-muted"><?php echo _l('no_results_found'); ?></p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table dt-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo _l('lims_subject_internal_code') ?: 'Code'; ?></th>
                        <th><?php echo _l('lims_subject_name') ?: _l('name'); ?></th>
                        <th><?php echo _l('lims_subject_type') ?: _l('type'); ?></th>
                        <th><?php echo _l('email'); ?></th>
                        <th><?php echo _l('phonenumber'); ?></th>
                        <th><?php echo _l('status'); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subjects as $s): ?>
                        <?php
                        $display = trim((string)($s->subject_name ?? ''));
                        if ($display === '') {
                            $display = trim((string)($s->first_name ?? '') . ' ' . (string)($s->last_name ?? ''));
                        }
                        if ($display === '') {
                            $display = '#' . (int)$s->id;
                        }
                        ?>
                        <tr>
                            <td><?php echo (int)$s->id; ?></td>
                            <td><?php echo html_escape((string)($s->internal_code ?? '')); ?></td>
                            <td><?php echo html_escape($display); ?></td>
                            <td><?php echo html_escape((string)($s->subject_type ?? '-')); ?></td>
                            <td><?php echo html_escape((string)($s->email ?? '')); ?></td>
                            <td><?php echo html_escape((string)($s->phone ?? '')); ?></td>
                            <td>
                                <?php if ((int)$s->active === 1): ?>
                                    <span class="label label-success"><?php echo _l('active'); ?></span>
                                <?php else: ?>
                                    <span class="label label-default"><?php echo _l('inactive'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <a href="<?php echo admin_url('lims/subjects/view/' . (int)$s->id); ?>" class="btn btn-default btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <?php if (has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin')): ?>
                                    <a href="<?php echo admin_url('lims/subjects/delete/' . (int)$s->id . '?return_to=client_tab&client_id=' . (int)$client_id); ?>"
                                       class="btn btn-danger btn-sm js-lims-subject-delete"
                                       data-subject-id="<?php echo (int)$s->id; ?>">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="limsSubjectDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Subject</h4>
            </div>
            <div class="modal-body">
                <p class="text-muted js-delete-modal-summary"></p>

                <div class="js-delete-modal-counts hide">
                    <div class="alert alert-warning">
                        Η επιλογή <strong>Delete all</strong> θα διαγράψει και τα σχετικά
                        <strong>Appointments, Samples, Orders, Tests</strong> και τα child δεδομένα τους.
                    </div>
                    <ul class="list-unstyled mtop10">
                        <li><strong>Orders:</strong> <span data-k="orders">0</span></li>
                        <li><strong>Contracts:</strong> <span data-k="contracts">0</span></li>
                        <li><strong>Appointments:</strong> <span data-k="appointments">0</span></li>
                        <li><strong>Tests:</strong> <span data-k="tests">0</span></li>
                        <li><strong>Samples:</strong> <span data-k="samples">0</span></li>
                    </ul>

                    <hr/>

                    <div class="radio radio-primary">
                        <input type="radio" id="subject-action-delete-all" name="subject_delete_action" value="delete_all" checked>
                        <label for="subject-action-delete-all">Delete subject + όλα τα συνδεδεμένα στοιχεία (appointments/samples/orders/tests)</label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" id="subject-action-transfer" name="subject_delete_action" value="transfer">
                        <label for="subject-action-transfer">Μεταφορά συνδεδεμένων στοιχείων σε άλλο Subject και διαγραφή</label>
                    </div>
                    <div class="radio radio-primary">
                        <input type="radio" id="subject-action-archive" name="subject_delete_action" value="archive">
                        <label for="subject-action-archive">Archive (Mark as Deleted) και κράτα ιστορικό</label>
                    </div>

                    <div class="form-group mtop10 js-transfer-target-wrap hide">
                        <label for="subject-transfer-target-id">Target Subject ID</label>
                        <input type="number" min="1" class="form-control" id="subject-transfer-target-id" placeholder="π.χ. 123">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger js-confirm-subject-delete">Confirm</button>
            </div>
        </div>
    </div>
</div>
