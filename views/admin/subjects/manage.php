<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="mbot15">
                            <?php if (has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin')): ?>
                                <a href="<?php echo admin_url('lims/subjects/create'); ?>" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
                                </a>
                            <?php endif; ?>
                        </div>

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
                                <?php foreach (($rows ?? []) as $s): ?>
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
                                                <a href="<?php echo admin_url('lims/subjects/delete/' . (int)$s->id); ?>"
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    (function () {
        "use strict";
        var jq = window.jQuery || window.$;
        if (!jq) {
            return;
        }

        function buildDeleteUrl(subjectId, mode, targetId) {
            var url = "<?php echo admin_url('lims/subjects/delete/'); ?>" + subjectId
                + "?mode=" + encodeURIComponent(mode);
            if (targetId) {
                url += "&target_subject_id=" + encodeURIComponent(targetId);
            }
            return url;
        }

        jq(document).on('click', '.js-lims-subject-delete', function (e) {
            e.preventDefault();

            var $btn = jq(this);
            var subjectId = parseInt($btn.data('subject-id'), 10) || 0;
            var deleteUrl = $btn.attr('href');

            if (!subjectId || !deleteUrl) {
                return;
            }

            jq.getJSON("<?php echo admin_url('lims/subjects/delete_dependencies/'); ?>" + subjectId, function (resp) {
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
                    + "Cancel = More options (Transfer / Archive)";

                if (confirm(msg)) {
                    window.location.href = buildDeleteUrl(subjectId, 'delete_all');
                    return;
                }

                var action = prompt("Type action: transfer / archive");
                if (!action) {
                    return;
                }

                action = (action + '').toLowerCase().trim();
                if (action === 'archive') {
                    window.location.href = buildDeleteUrl(subjectId, 'archive');
                    return;
                }

                if (action === 'transfer') {
                    var target = prompt("Enter target Subject ID to transfer linked records:");
                    if (target && parseInt(target, 10) > 0) {
                        window.location.href = buildDeleteUrl(subjectId, 'transfer', parseInt(target, 10));
                    }
                }
            }).fail(function () {
                if (confirm("Delete subject?")) {
                    window.location.href = deleteUrl;
                }
            });
        });
    })();
</script>
