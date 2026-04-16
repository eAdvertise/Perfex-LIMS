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
                        <label for="subject-action-delete-all">Delete subject + όλα τα συνδεδεμένα στοιχεία</label>
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
<script>
    (function () {
        "use strict";
        var jq = window.jQuery || window.$;
        if (!jq) {
            return;
        }
        var state = {
            subjectId: 0,
            deleteUrl: '',
            hasLinks: false
        };

        var $modal = jq('#limsSubjectDeleteModal');
        var $summary = $modal.find('.js-delete-modal-summary');
        var $countsWrap = $modal.find('.js-delete-modal-counts');
        var $transferWrap = $modal.find('.js-transfer-target-wrap');
        var $transferInput = $modal.find('#subject-transfer-target-id');

        function buildDeleteUrl(subjectId, mode, targetId) {
            var url = "<?php echo admin_url('lims/subjects/delete/'); ?>" + subjectId
                + "?mode=" + encodeURIComponent(mode);
            if (targetId) {
                url += "&target_subject_id=" + encodeURIComponent(targetId);
            }
            return url;
        }

        function setCounts(counts) {
            counts = counts || {};
            $countsWrap.find('[data-k="orders"]').text(parseInt(counts.orders || 0, 10));
            $countsWrap.find('[data-k="contracts"]').text(parseInt(counts.contracts || 0, 10));
            $countsWrap.find('[data-k="appointments"]').text(parseInt(counts.appointments || 0, 10));
            $countsWrap.find('[data-k="tests"]').text(parseInt(counts.tests || 0, 10));
            $countsWrap.find('[data-k="samples"]').text(parseInt(counts.samples || 0, 10));
        }

        function toggleTransferInput() {
            var mode = $modal.find('input[name="subject_delete_action"]:checked').val();
            if (mode === 'transfer' && state.hasLinks) {
                $transferWrap.removeClass('hide');
            } else {
                $transferWrap.addClass('hide');
                $transferInput.val('');
            }
        }

        jq(document).on('click', '.js-lims-subject-delete', function (e) {
            e.preventDefault();

            var $btn = jq(this);
            var subjectId = parseInt($btn.data('subject-id'), 10) || 0;
            var deleteUrl = $btn.attr('href');

            if (!subjectId || !deleteUrl) {
                return;
            }
            state.subjectId = subjectId;
            state.deleteUrl = deleteUrl;
            state.hasLinks = false;

            jq.getJSON("<?php echo admin_url('lims/subjects/delete_dependencies/'); ?>" + subjectId, function (resp) {
                if (!resp || !resp.success) {
                    $summary.text("Δεν βρέθηκαν πληροφορίες για dependencies. Θες να γίνει απλό delete;");
                    $countsWrap.addClass('hide');
                    $modal.modal('show');
                    return;
                }

                if (!resp.has_any) {
                    $summary.text("Το Subject δεν έχει συνδεδεμένα στοιχεία. Μπορεί να διαγραφεί άμεσα.");
                    $countsWrap.addClass('hide');
                    $modal.modal('show');
                    return;
                }

                state.hasLinks = true;
                $summary.text("Το Subject έχει συνδεδεμένα στοιχεία. Διάλεξε ενέργεια:");
                setCounts(resp.counts || {});
                $countsWrap.removeClass('hide');
                $modal.find('input[name="subject_delete_action"][value="delete_all"]').prop('checked', true);
                toggleTransferInput();
                $modal.modal('show');
            }).fail(function () {
                $summary.text("Δεν ήταν δυνατός ο έλεγχος dependencies. Θες να γίνει απλό delete;");
                $countsWrap.addClass('hide');
                $modal.modal('show');
            });
        });

        $modal.on('change', 'input[name="subject_delete_action"]', toggleTransferInput);

        jq(document).on('click', '.js-confirm-subject-delete', function () {
            if (!state.subjectId || !state.deleteUrl) {
                return;
            }

            if (!state.hasLinks) {
                window.location.href = state.deleteUrl;
                return;
            }

            var mode = $modal.find('input[name="subject_delete_action"]:checked').val() || 'delete_all';
            if (mode === 'transfer') {
                var target = parseInt($transferInput.val(), 10) || 0;
                if (target <= 0 || target === state.subjectId) {
                    alert('Βάλε έγκυρο Target Subject ID.');
                    return;
                }
                window.location.href = buildDeleteUrl(state.subjectId, 'transfer', target);
                return;
            }

            if (mode === 'archive') {
                window.location.href = buildDeleteUrl(state.subjectId, 'archive');
                return;
            }

            window.location.href = buildDeleteUrl(state.subjectId, 'delete_all');
        });
    })();
</script>
