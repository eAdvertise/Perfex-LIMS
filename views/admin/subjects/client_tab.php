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
    ->order_by('id', 'DESC')
    ->get()
    ->result();

$can_manage_subjects = has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin');
?>

<div class="row">
    <div class="col-md-12">
        <div class="mbot15">
            <?php if ($can_manage_subjects): ?>
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
                                <?php if ($can_manage_subjects): ?>
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

<script>
    (function () {
        "use strict";

        function buildDeleteUrl(subjectId, mode, targetId) {
            var url = "<?php echo admin_url('lims/subjects/delete/'); ?>" + subjectId
                + "?mode=" + encodeURIComponent(mode)
                + "&return_to=client_tab"
                + "&client_id=<?php echo (int)$client_id; ?>";
            if (targetId) {
                url += "&target_subject_id=" + encodeURIComponent(targetId);
            }
            return url;
        }

        $(document).on('click', '.js-lims-subject-delete', function (e) {
            e.preventDefault();

            var $btn = $(this);
            var subjectId = parseInt($btn.data('subject-id'), 10) || 0;
            var deleteUrl = $btn.attr('href');

            if (!subjectId || !deleteUrl) {
                return;
            }

            $.getJSON("<?php echo admin_url('lims/subjects/delete_dependencies/'); ?>" + subjectId, function (resp) {
                if (!resp || !resp.success) {
                    if (confirm("Delete subject?")) {
                        window.location.href = deleteUrl;
                    }
                    return;
                }

                if (!resp.has_any) {
                    if (confirm("Delete subject?")) {
                        window.location.href = deleteUrl;
                    }
                    return;
                }

                var counts = resp.counts || {};
                var msg = "This subject has linked data:\n"
                    + "- Orders: " + (counts.orders || 0) + "\n"
                    + "- Contracts: " + (counts.contracts || 0) + "\n"
                    + "- Appointments: " + (counts.appointments || 0) + "\n"
                    + "- Tests: " + (counts.tests || 0) + "\n"
                    + "- Samples: " + (counts.samples || 0) + "\n\n"
                    + "OK = Delete all linked records\n"
                    + "Cancel = Transfer linked records to another Subject";

                if (confirm(msg)) {
                    window.location.href = buildDeleteUrl(subjectId, 'delete_all');
                    return;
                }

                var target = prompt("Enter target Subject ID to transfer linked records:");
                if (target && parseInt(target, 10) > 0) {
                    window.location.href = buildDeleteUrl(subjectId, 'transfer', parseInt(target, 10));
                }
            }).fail(function () {
                if (confirm("Delete subject?")) {
                    window.location.href = deleteUrl;
                }
            });
        });
    })();
</script>
