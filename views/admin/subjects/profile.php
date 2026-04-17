<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold">
                    <?php echo html_escape($title ?? (_l('lims_subject') ?: 'Subject')); ?>
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="panel_s">
                    <div class="panel-heading">
                        <strong>
                            <?php echo html_escape($subject_details_card_title ?? (_l('lims_subject') ?: 'Subject') . ' Details'); ?>
                        </strong>
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = isset($subject_details_rows) && is_array($subject_details_rows) ? $subject_details_rows : [];
                        if (empty($rows) && isset($subject)) {
                            $fallbackName = trim((string)($subject->subject_name ?? ''));
                            if ($fallbackName === '') {
                                $fallbackName = trim((string)($subject->first_name ?? '') . ' ' . (string)($subject->last_name ?? ''));
                            }
                            if ($fallbackName !== '') {
                                $rows[] = ['label' => _l('lims_subject_name') ?: (_l('name') ?: 'Name'), 'value' => $fallbackName];
                            }
                        }
                        ?>

                        <?php if (!empty($subject_type_normalized)): ?>
                            <p class="text-muted mtop0">
                                <strong><?php echo _l('lims_subject_type') ?: _l('type') ?: 'Type'; ?>:</strong>
                                <?php echo html_escape(ucfirst((string)$subject_type_normalized)); ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($rows)): ?>
                            <?php foreach ($rows as $r): ?>
                                <?php
                                $label = isset($r['label']) ? (string)$r['label'] : '';
                                $value = isset($r['value']) ? trim((string)$r['value']) : '';
                                if ($value === '') {
                                    continue;
                                }
                                ?>
                                <p class="no-margin">
                                    <strong><?php echo html_escape($label); ?>:</strong>
                                    <?php echo html_escape($value); ?>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted"><?php echo _l('no_data_available') ?: 'No details available.'; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                $subjectId = isset($subject->id) ? (int)$subject->id : 0;
                $items = [
                    'profile'           => _l('profile') ?: 'Profile',
                    'orders'            => _l('lims_orders') ?: 'Orders',
                    'samples'           => _l('lims_samples') ?: 'Samples',
                    'invoices'          => _l('invoices') ?: 'Invoices',
                    'creditnotes'       => _l('credit_notes') ?: 'Credit Notes',
                    'receipts_payments' => _l('payments') ?: 'Receipts / Payments',
                    'waybills'          => _l('delivery_notes') ?: 'Waybills',
                    'notes'             => _l('notes') ?: 'Notes',
                    'files'             => _l('customer_files') ?: 'Files',
                    'reminders'         => _l('reminders') ?: 'Reminders',
                ];
                ?>
                <div class="panel_s">
                    <div class="panel-body p-0">
                        <ul class="nav navbar-pills nav-stacked no-margin">
                            <?php foreach ($items as $key => $label): ?>
                                <li class="<?php echo (($group ?? 'profile') === $key) ? 'active' : ''; ?>">
                                    <a href="<?php echo admin_url('lims/subjects/view/' . $subjectId . '?group=' . $key); ?>">
                                        <?php echo html_escape($label); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php $activeGroup = $group ?? 'profile'; ?>

                        <?php if ($activeGroup === 'orders'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('lims_orders') ?: 'Orders'; ?></h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo _l('date'); ?></th>
                                        <th><?php echo _l('status'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($orders)): ?>
                                        <?php foreach ($orders as $o): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo admin_url('lims/orders/view/' . (int)$o->id); ?>">
                                                        #<?php echo (int)$o->id; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo !empty($o->created_at) ? _dt($o->created_at) : '-'; ?></td>
                                                <td><?php echo html_escape((string)($o->status ?? '-')); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-muted"><?php echo _l('no_records_found'); ?></td></tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php elseif ($activeGroup === 'samples'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('lims_samples') ?: 'Samples'; ?></h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo _l('lims_sample_type') ?: 'Sample Type'; ?></th>
                                        <th><?php echo _l('date'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($samples)): ?>
                                        <?php foreach ($samples as $s): ?>
                                            <tr>
                                                <td><?php echo (int)$s->id; ?></td>
                                                <td><?php echo html_escape((string)($s->sample_type_name ?? '-')); ?></td>
                                                <td><?php echo !empty($s->created_at) ? _dt($s->created_at) : '-'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-muted"><?php echo _l('no_records_found'); ?></td></tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php elseif ($activeGroup === 'invoices'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('invoices'); ?></h4>
                            <p class="text-muted"><?php echo (int)count($billing_invoices ?? []); ?> <?php echo _l('records'); ?></p>

                        <?php elseif ($activeGroup === 'creditnotes'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('credit_notes') ?: 'Credit Notes'; ?></h4>
                            <p class="text-muted"><?php echo (int)count($billing_creditnotes ?? []); ?> <?php echo _l('records'); ?></p>

                        <?php elseif ($activeGroup === 'receipts_payments'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('payments') ?: 'Receipts / Payments'; ?></h4>
                            <p class="text-muted">
                                <?php echo (int)count($billing_receipts ?? []) + (int)count($billing_payments ?? []); ?>
                                <?php echo _l('records'); ?>
                            </p>

                        <?php elseif ($activeGroup === 'waybills'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('delivery_notes') ?: 'Waybills'; ?></h4>
                            <p class="text-muted"><?php echo (int)count($billing_delivery_notes ?? []); ?> <?php echo _l('records'); ?></p>

                        <?php elseif ($activeGroup === 'files'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('customer_files') ?: 'Files'; ?></h4>
                            <?php if (!empty($attachments)): ?>
                                <ul class="list-unstyled">
                                    <?php foreach ($attachments as $a): ?>
                                        <li>
                                            <i class="fa fa-paperclip"></i>
                                            <?php echo html_escape((string)($a->file_name ?? '')); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted"><?php echo _l('no_files_found') ?: 'No files found.'; ?></p>
                            <?php endif; ?>

                        <?php elseif ($activeGroup === 'reminders'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('reminders'); ?></h4>
                            <?php if (!empty($reminders)): ?>
                                <ul class="list-unstyled">
                                    <?php foreach ($reminders as $r): ?>
                                        <li>
                                            <strong><?php echo html_escape((string)($r['description'] ?? '')); ?></strong>
                                            <span class="text-muted">
                                                (<?php echo html_escape((string)($r['date'] ?? '')); ?>)
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted"><?php echo _l('no_reminders_for_this_customer') ?: 'No reminders.'; ?></p>
                            <?php endif; ?>

                        <?php elseif ($activeGroup === 'notes'): ?>
                            <h4 class="tw-mt-0"><?php echo _l('notes'); ?></h4>
                            <?php if (!empty($user_notes)): ?>
                                <ul class="list-unstyled">
                                    <?php foreach ($user_notes as $n): ?>
                                        <li><?php echo nl2br(html_escape((string)($n['description'] ?? ''))); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted"><?php echo _l('no_notes_found'); ?></p>
                            <?php endif; ?>

                        <?php else: ?>
                            <h4 class="tw-mt-0"><?php echo _l('profile') ?: 'Profile'; ?></h4>
                            <p class="text-muted">
                                <?php echo _l('lims_subject') ?: 'Subject'; ?> #<?php echo (int)($subject->id ?? 0); ?>
                            </p>
                            <?php if (!empty($client)): ?>
                                <p>
                                    <strong><?php echo _l('customer'); ?>:</strong>
                                    <?php echo html_escape((string)($client->company ?? '-')); ?>
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
