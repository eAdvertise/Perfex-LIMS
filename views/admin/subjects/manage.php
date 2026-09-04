<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
$canManage = has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin');
$capitalizeHeading = static function ($heading) {
    $heading = (string)$heading;

    if ($heading === '') {
        return '';
    }

    if (function_exists('mb_substr') && function_exists('mb_strtoupper')) {
        return mb_strtoupper(mb_substr($heading, 0, 1, 'UTF-8'), 'UTF-8')
            . mb_substr($heading, 1, null, 'UTF-8');
    }

    return ucfirst($heading);
};
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix mbot15">
                            <?php if ($canManage): ?>
                                <a href="<?php echo admin_url('lims/subjects/create'); ?>" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
                                </a>
                                <div class="btn-group mleft5">
                                    <select class="selectpicker" id="subjects-bulk-action" data-width="160px" title="<?php echo $capitalizeHeading(_l('bulk_actions')); ?>">
                                        <option value="active"><?php echo $capitalizeHeading(_l('active')); ?></option>
                                        <option value="inactive"><?php echo $capitalizeHeading(_l('inactive')); ?></option>
                                        <option value="delete"><?php echo $capitalizeHeading(_l('delete')); ?></option>
                                    </select>
                                    <button type="button" class="btn btn-default" id="subjects-apply-bulk-action"><?php echo $capitalizeHeading(_l('apply')); ?></button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-table table-subjects" data-order-col="1" data-order-type="desc">
                                <thead>
                                <tr>
                                    <th class="not-export"><input type="checkbox" id="subjects-select-all"></th>
                                    <th>#</th>
                                    <th><?php echo $capitalizeHeading(_l('lims_subject_internal_code') ?: 'Internal code'); ?></th>
                                    <th><?php echo $capitalizeHeading(_l('name')); ?></th>
                                    <th><?php echo $capitalizeHeading(_l('type')); ?></th>
                                    <th><?php echo $capitalizeHeading(_l('client')); ?></th>
                                    <th><?php echo $capitalizeHeading(_l('phonenumber')); ?></th>
                                    <th><?php echo $capitalizeHeading(_l('status')); ?></th>
                                    <th><?php echo $capitalizeHeading(_l('options')); ?></th>
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
                                        <td><input type="checkbox" class="subject-row-select" value="<?php echo (int)$s->id; ?>"></td>
                                        <td><?php echo (int)$s->id; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('lims/subjects/view/' . (int)$s->id); ?>">
                                                <?php echo html_escape((string)($s->internal_code ?? '')); ?>
                                            </a>
                                        </td>
                                        <td><?php echo html_escape($display); ?></td>
                                        <td><?php echo html_escape((string)($s->subject_type ?? '-')); ?></td>
                                        <td>
                                            <?php if (!empty($s->client_id) && !empty($s->client_company)): ?>
                                                <a href="<?php echo admin_url('clients/client/' . (int)$s->client_id); ?>">
                                                    <?php echo html_escape($s->client_company); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">&mdash;</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo html_escape((string)($s->phone ?? '')); ?></td>
                                        <td>
                                            <span class="hide"><?php echo (int)$s->active === 1 ? $capitalizeHeading(_l('active')) : $capitalizeHeading(_l('inactive')); ?></span>
                                            <div class="onoffswitch">
                                                <input type="checkbox"
                                                       class="onoffswitch-checkbox subject-status-toggle"
                                                       id="subject_active_<?php echo (int)$s->id; ?>"
                                                       data-id="<?php echo (int)$s->id; ?>"
                                                       <?php echo (int)$s->active === 1 ? 'checked' : ''; ?>
                                                       <?php echo $canManage ? '' : 'disabled'; ?>>
                                                <label class="onoffswitch-label" for="subject_active_<?php echo (int)$s->id; ?>"></label>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <a href="<?php echo admin_url('lims/subjects/view/' . (int)$s->id); ?>" class="btn btn-default btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <?php if ($canManage): ?>
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
<script>
    (function () {
        "use strict";
        var jq = window.jQuery || window.$;
        if (!jq) {
            return;
        }

        function showSubjectsMessage(type, message) {
            if (typeof alert_float === 'function') {
                alert_float(type, message);
                return;
            }

            window.alert(message);
        }

        jq(document).on('change', '.subject-status-toggle', function () {
            var $toggle = jq(this);
            var active = $toggle.is(':checked') ? 1 : 0;

            $toggle.prop('disabled', true);
            jq.post("<?php echo admin_url('lims/subjects/toggle_status'); ?>", {
                id: parseInt($toggle.data('id'), 10),
                active: active
            }, null, 'json').done(function (response) {
                if (!response || !response.success) {
                    $toggle.prop('checked', !active);
                    showSubjectsMessage('danger', response && response.message ? response.message : 'Status could not be updated.');
                }
            }).fail(function () {
                $toggle.prop('checked', !active);
                showSubjectsMessage('danger', 'Status could not be updated.');
            }).always(function () {
                $toggle.prop('disabled', false);
            });
        });

        jq(document).on('change', '#subjects-select-all', function () {
            jq('.subject-row-select').prop('checked', jq(this).is(':checked'));
        });

        jq(document).on('change', '.subject-row-select', function () {
            var total = jq('.subject-row-select').length;
            var selected = jq('.subject-row-select:checked').length;
            jq('#subjects-select-all').prop('checked', total > 0 && total === selected);
        });

        jq(document).on('click', '#subjects-apply-bulk-action', function () {
            var action = jq('#subjects-bulk-action').val();
            var ids = jq('.subject-row-select:checked').map(function () {
                return parseInt(this.value, 10);
            }).get();

            if (!action || !ids.length) {
                showSubjectsMessage('warning', 'Select subjects and a bulk action first.');
                return;
            }

            if (action === 'delete' && !window.confirm('Delete the selected subjects and all of their linked records?')) {
                return;
            }

            var $button = jq(this).prop('disabled', true);
            jq.post("<?php echo admin_url('lims/subjects/bulk_action'); ?>", {
                action: action,
                ids: ids
            }, null, 'json').done(function (response) {
                if (response && response.success) {
                    window.location.reload();
                    return;
                }

                showSubjectsMessage('danger', response && response.message ? response.message : 'The bulk action could not be completed.');
            }).fail(function () {
                showSubjectsMessage('danger', 'The bulk action could not be completed.');
            }).always(function () {
                $button.prop('disabled', false);
            });
        });

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
