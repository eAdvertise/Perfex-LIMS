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

<script>
    (function () {
        "use strict";
        var modal = document.getElementById('limsSubjectDeleteModal');
        if (!modal) {
            return;
        }

        var state = {
            subjectId: 0,
            deleteUrl: '',
            hasLinks: false
        };

        var summaryEl = modal.querySelector('.js-delete-modal-summary');
        var countsWrap = modal.querySelector('.js-delete-modal-counts');
        var transferWrap = modal.querySelector('.js-transfer-target-wrap');
        var transferInput = modal.querySelector('#subject-transfer-target-id');
        var confirmBtn = modal.querySelector('.js-confirm-subject-delete');

        function showModal() {
            modal.style.display = 'block';
            modal.classList.add('in');
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade in';
                backdrop.addEventListener('click', hideModal);
                document.body.appendChild(backdrop);
            }
        }

        function hideModal() {
            modal.style.display = 'none';
            modal.classList.remove('in');
            document.body.classList.remove('modal-open');
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

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

        function setCounts(counts) {
            counts = counts || {};
            modal.querySelector('[data-k="orders"]').textContent = parseInt(counts.orders || 0, 10);
            modal.querySelector('[data-k="contracts"]').textContent = parseInt(counts.contracts || 0, 10);
            modal.querySelector('[data-k="appointments"]').textContent = parseInt(counts.appointments || 0, 10);
            modal.querySelector('[data-k="tests"]').textContent = parseInt(counts.tests || 0, 10);
            modal.querySelector('[data-k="samples"]').textContent = parseInt(counts.samples || 0, 10);
        }

        function toggleTransferInput() {
            var checked = modal.querySelector('input[name="subject_delete_action"]:checked');
            var mode = checked ? checked.value : 'delete_all';
            if (mode === 'transfer' && state.hasLinks) {
                transferWrap.classList.remove('hide');
            } else {
                transferWrap.classList.add('hide');
                transferInput.value = '';
            }
        }

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.js-lims-subject-delete');
            if (!btn) return;
            e.preventDefault();
            var subjectId = parseInt(btn.getAttribute('data-subject-id') || '0', 10) || 0;
            var deleteUrl = btn.getAttribute('href') || '';
            if (!subjectId || !deleteUrl) {
                return;
            }
            state.subjectId = subjectId;
            state.deleteUrl = deleteUrl;
            state.hasLinks = false;

            fetch("<?php echo admin_url('lims/subjects/delete_dependencies/'); ?>" + subjectId, {
                credentials: 'same-origin'
            }).then(function (r) {
                return r.json();
            }).then(function (resp) {
                if (!resp || !resp.success) {
                    summaryEl.textContent = "Δεν βρέθηκαν πληροφορίες για dependencies. Θες να γίνει απλό delete;";
                    countsWrap.classList.add('hide');
                    showModal();
                    return;
                }

                if (!resp.has_any) {
                    summaryEl.textContent = "Το Subject δεν έχει συνδεδεμένα στοιχεία. Μπορεί να διαγραφεί άμεσα.";
                    countsWrap.classList.add('hide');
                    showModal();
                    return;
                }

                state.hasLinks = true;
                summaryEl.textContent = "Το Subject έχει συνδεδεμένα στοιχεία. Διάλεξε ενέργεια:";
                setCounts(resp.counts || {});
                countsWrap.classList.remove('hide');
                var defaultAction = modal.querySelector('input[name="subject_delete_action"][value="delete_all"]');
                if (defaultAction) defaultAction.checked = true;
                toggleTransferInput();
                showModal();
            }).catch(function () {
                summaryEl.textContent = "Δεν ήταν δυνατός ο έλεγχος dependencies. Θες να γίνει απλό delete;";
                countsWrap.classList.add('hide');
                showModal();
            });
        });

        modal.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'subject_delete_action') {
                toggleTransferInput();
            }
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
            if (!state.subjectId || !state.deleteUrl) {
                return;
            }

            if (!state.hasLinks) {
                window.location.href = state.deleteUrl;
                return;
            }

            var checked = modal.querySelector('input[name="subject_delete_action"]:checked');
            var mode = checked ? checked.value : 'delete_all';
            if (mode === 'transfer') {
                var target = parseInt(transferInput.value, 10) || 0;
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
        }

        modal.querySelectorAll('[data-dismiss="modal"], .close').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                hideModal();
            });
        });
    })();
</script>
