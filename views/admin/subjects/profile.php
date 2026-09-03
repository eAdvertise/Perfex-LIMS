<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
$subjectId = (int)$subject->id;
$canManage = has_permission('lims', '', 'manage_orders') || has_permission('lims', '', 'admin');
$tabs = [
    'profile' => ['icon' => 'fa fa-user', 'label' => _l('profile')],
    'orders' => ['icon' => 'fa fa-flask', 'label' => _l('lims_subject_orders')],
    'samples' => ['icon' => 'fa fa-vial', 'label' => _l('lims_subject_samples')],
    'invoices' => ['icon' => 'fa fa-file-text-o', 'label' => _l('lims_subject_invoices')],
    'creditnotes' => ['icon' => 'fa fa-file-o', 'label' => _l('lims_subject_credit_notes')],
    'receipts_payments' => ['icon' => 'fa fa-money', 'label' => _l('lims_subject_payments')],
    'waybills' => ['icon' => 'fa fa-truck', 'label' => _l('delivery_notes') ?: 'Delivery notes'],
    'notes' => ['icon' => 'fa fa-sticky-note-o', 'label' => _l('notes')],
    'files' => ['icon' => 'fa fa-paperclip', 'label' => _l('lims_subject_files')],
    'reminders' => ['icon' => 'fa fa-bell-o', 'label' => _l('lims_subject_reminders')],
];
$displayValue = static function ($value) {
    return $value !== null && $value !== '' ? html_escape((string)$value) : '<span class="text-muted">—</span>';
};
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix">
                            <div class="pull-left">
                                <h4 class="no-margin"><?php echo html_escape($title); ?></h4>
                                <span class="text-muted"><?php echo html_escape($subject->internal_code ?? ('#' . $subjectId)); ?></span>
                            </div>
                            <div class="pull-right">
                                <?php if ($canManage): ?>
                                    <a href="<?php echo admin_url('lims/subjects/create/' . $subjectId); ?>" class="btn btn-default"><i class="fa fa-pencil"></i> <?php echo _l('edit'); ?></a>
                                <?php endif; ?>
                                <a href="<?php echo admin_url('lims/subjects'); ?>" class="btn btn-default"><?php echo _l('go_back'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="panel_s">
                    <div class="panel-body">
                        <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
                            <?php foreach ($tabs as $tab => $tabData): ?>
                                <li class="<?php echo $group === $tab ? 'active' : ''; ?>">
                                    <a href="<?php echo admin_url('lims/subjects/view/' . $subjectId . '?group=' . $tab); ?>">
                                        <i class="<?php echo $tabData['icon']; ?> menu-icon"></i> <?php echo $tabData['label']; ?>
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
                        <?php if ($group === 'profile'): ?>
                            <h4><?php echo _l('profile'); ?></h4>
                            <hr class="hr-panel-heading" />
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tr><td><strong><?php echo _l('lims_subject_type') ?: 'Type'; ?></strong></td><td><?php echo $displayValue($subject->subject_type ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('first_name'); ?></strong></td><td><?php echo $displayValue($subject->first_name ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('last_name'); ?></strong></td><td><?php echo $displayValue($subject->last_name ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('lims_subject_name') ?: _l('name'); ?></strong></td><td><?php echo $displayValue($subject->subject_name ?? ''); ?></td></tr>
                                        <tr><td><strong>ID / Passport</strong></td><td><?php echo $displayValue($subject->id_number ?? ''); ?></td></tr>
                                        <tr><td><strong>Social insurance</strong></td><td><?php echo $displayValue($subject->social_insurance_no ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('date_of_birth'); ?></strong></td><td><?php echo !empty($subject->date_of_birth) ? _d($subject->date_of_birth) : $displayValue(''); ?></td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tr><td><strong><?php echo _l('client'); ?></strong></td><td><?php if ($client): ?><a href="<?php echo admin_url('clients/client/' . (int)$client->userid); ?>"><?php echo html_escape($client->company); ?></a><?php else: echo $displayValue(''); endif; ?></td></tr>
                                        <tr><td><strong><?php echo _l('email'); ?></strong></td><td><?php echo $displayValue($subject->email ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('phonenumber'); ?></strong></td><td><?php echo $displayValue($subject->phone ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('client_address'); ?></strong></td><td><?php echo $displayValue($subject->address ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('client_city'); ?></strong></td><td><?php echo $displayValue($subject->city ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('client_state'); ?></strong></td><td><?php echo $displayValue($subject->state ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('client_postal_code'); ?></strong></td><td><?php echo $displayValue($subject->zip ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('country'); ?></strong></td><td><?php echo !empty($subject->country) ? html_escape(get_country_short_name((int)$subject->country)) : $displayValue(''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('language'); ?></strong></td><td><?php echo $displayValue($subject->language ?? ''); ?></td></tr>
                                        <tr><td><strong><?php echo _l('status'); ?></strong></td><td><span class="label label-<?php echo (int)$subject->active === 1 ? 'success' : 'default'; ?>"><?php echo (int)$subject->active === 1 ? _l('active') : _l('inactive'); ?></span></td></tr>
                                    </table>
                                </div>
                            </div>
                            <?php if (!empty($subject->notes)): ?>
                                <hr /><h5><?php echo _l('notes'); ?></h5>
                                <div class="text-muted"><?php echo nl2br(html_escape($subject->notes)); ?></div>
                            <?php endif; ?>

                        <?php elseif ($group === 'orders'): ?>
                            <h4><?php echo _l('lims_orders'); ?></h4><hr class="hr-panel-heading" />
                            <div class="table-responsive">
                                <table class="table dt-table" data-order-col="0" data-order-type="desc">
                                    <thead><tr><th>#</th><th><?php echo _l('client'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('date_created'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
                                    <tbody><?php foreach ($orders as $order): ?><tr>
                                        <td><?php echo (int)$order->id; ?></td><td><?php echo html_escape($order->client_company ?? ''); ?></td>
                                        <td><?php echo html_escape($order->status ?? ''); ?></td><td data-order="<?php echo strtotime($order->created_at ?? ''); ?>"><?php echo !empty($order->created_at) ? _dt($order->created_at) : ''; ?></td>
                                        <td><a class="btn btn-default btn-sm" href="<?php echo admin_url('lims/orders/view/' . (int)$order->id); ?>"><i class="fa fa-eye"></i></a></td>
                                    </tr><?php endforeach; ?></tbody>
                                </table>
                            </div>

                        <?php elseif ($group === 'samples'): ?>
                            <h4><?php echo _l('lims_samples'); ?></h4><hr class="hr-panel-heading" />
                            <div class="table-responsive">
                                <table class="table dt-table" data-order-col="0" data-order-type="desc">
                                    <thead><tr><th>#</th><th><?php echo _l('lims_sample_type'); ?></th><th><?php echo _l('lims_barcode'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('date_created'); ?></th></tr></thead>
                                    <tbody><?php foreach ($samples as $sample): ?><tr>
                                        <td><?php echo (int)$sample->id; ?></td><td><?php echo html_escape($sample->sample_type_name ?? ''); ?></td><td><?php echo html_escape($sample->barcode ?? ''); ?></td>
                                        <td><?php echo html_escape($sample->status ?? ''); ?></td><td data-order="<?php echo strtotime($sample->created_at ?? ''); ?>"><?php echo !empty($sample->created_at) ? _dt($sample->created_at) : ''; ?></td>
                                    </tr><?php endforeach; ?></tbody>
                                </table>
                            </div>

                        <?php elseif ($group === 'notes'): ?>
                            <h4><?php echo _l('notes'); ?></h4><hr class="hr-panel-heading" />
                            <?php if ($canManage): ?>
                                <?php echo form_open(admin_url('lims/subjects/add_note/' . $subjectId)); ?>
                                <?php echo render_textarea('description', _l('note_description'), '', ['required' => true, 'rows' => 4]); ?>
                                <div class="text-right mbot20"><button class="btn btn-primary" type="submit"><?php echo _l('add_note'); ?></button></div>
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                            <?php if (empty($user_notes)): ?><p class="text-muted"><?php echo _l('no_results_found'); ?></p><?php endif; ?>
                            <?php foreach (($user_notes ?? []) as $note): ?>
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <?php if ($canManage): ?><a class="pull-right text-danger _delete" href="<?php echo admin_url('lims/subjects/delete_note/' . $subjectId . '/' . (int)$note['id']); ?>"><i class="fa fa-remove"></i></a><?php endif; ?>
                                        <p><?php echo nl2br(html_escape($note['description'])); ?></p>
                                        <small class="text-muted"><?php echo !empty($note['dateadded']) ? _dt($note['dateadded']) : ''; ?><?php if (!empty($note['firstname']) || !empty($note['lastname'])): ?> · <?php echo html_escape(trim(($note['firstname'] ?? '') . ' ' . ($note['lastname'] ?? ''))); ?><?php endif; ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php elseif ($group === 'files'): ?>
                            <h4><?php echo _l('customer_attachments') ?: _l('files'); ?></h4><hr class="hr-panel-heading" />
                            <?php if ($canManage): ?>
                                <?php echo form_open_multipart(admin_url('lims/subjects/upload_attachment/' . $subjectId), ['class' => 'dropzone mbot20', 'id' => 'subject-attachments-upload']); ?>
                                <div class="fallback"><input type="file" name="file[]" multiple><button type="submit" class="btn btn-primary mtop10"><i class="fa fa-upload"></i> <?php echo _l('upload'); ?></button></div>
                                <div class="dz-message"><i class="fa fa-cloud-upload fa-2x"></i><br><?php echo _l('drop_files_here_to_upload') ?: 'Drop files here to upload'; ?></div>
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                            <div class="table-responsive"><table class="table dt-table" data-order-col="2" data-order-type="desc">
                                <thead><tr><th><?php echo _l('file_name'); ?></th><th><?php echo _l('file_type'); ?></th><th><?php echo _l('date_created'); ?></th><th><?php echo _l('options'); ?></th></tr></thead>
                                <tbody><?php foreach (($attachments ?? []) as $attachment): ?><tr>
                                    <td><a target="_blank" rel="noopener" href="<?php echo !empty($attachment->external) ? html_escape($attachment->external_link) : base_url('uploads/lims_subjects/' . $subjectId . '/' . rawurlencode($attachment->file_name)); ?>"><?php echo html_escape($attachment->file_name); ?></a></td>
                                    <td><?php echo html_escape($attachment->filetype ?? ''); ?></td><td data-order="<?php echo strtotime($attachment->dateadded ?? ''); ?>"><?php echo !empty($attachment->dateadded) ? _dt($attachment->dateadded) : ''; ?></td>
                                    <td><?php if ($canManage): ?><a class="btn btn-danger btn-sm _delete" href="<?php echo admin_url('lims/subjects/delete_attachment/' . $subjectId . '/' . (int)$attachment->id); ?>"><i class="fa fa-trash"></i></a><?php endif; ?></td>
                                </tr><?php endforeach; ?></tbody>
                            </table></div>

                        <?php elseif ($group === 'reminders'): ?>
                            <div class="clearfix"><h4 class="pull-left"><?php echo _l('reminders'); ?></h4><?php if ($canManage): ?><a href="#" class="btn btn-primary pull-right" onclick="init_relation_reminder(<?php echo $subjectId; ?>, 'lims_subject'); return false;"><i class="fa fa-bell"></i> <?php echo _l('set_reminder'); ?></a><?php endif; ?></div>
                            <hr class="hr-panel-heading" />
                            <div class="table-responsive"><table class="table dt-table" data-order-col="0" data-order-type="desc">
                                <thead><tr><th><?php echo _l('date'); ?></th><th><?php echo _l('description'); ?></th><th><?php echo _l('staff'); ?></th><th><?php echo _l('status'); ?></th></tr></thead>
                                <tbody><?php foreach (($reminders ?? []) as $reminder): ?><tr><td data-order="<?php echo strtotime($reminder['date']); ?>"><?php echo _dt($reminder['date']); ?></td><td><?php echo html_escape($reminder['description']); ?></td><td><?php echo html_escape(trim(($reminder['firstname'] ?? '') . ' ' . ($reminder['lastname'] ?? ''))); ?></td><td><?php echo !empty($reminder['isnotified']) ? _l('reminder_is_notified') : _l('reminder_is_not_notified'); ?></td></tr><?php endforeach; ?></tbody>
                            </table></div>

                        <?php else: ?>
                            <?php
                            $billingMap = [
                                'invoices' => $billing_invoices ?? [], 'creditnotes' => $billing_creditnotes ?? [],
                                'receipts_payments' => !empty($billing_receipts) ? $billing_receipts : ($billing_payments ?? []),
                                'waybills' => $billing_delivery_notes ?? [],
                            ];
                            $billingRows = $billingMap[$group] ?? [];
                            ?>
                            <h4><?php echo $tabs[$group]['label']; ?></h4><hr class="hr-panel-heading" />
                            <div class="table-responsive"><table class="table dt-table"><thead><tr><th>#</th><th><?php echo _l('date'); ?></th><th><?php echo _l('status'); ?></th></tr></thead><tbody>
                                <?php foreach ($billingRows as $row): ?><tr><td><?php echo (int)($row->id ?? 0); ?></td><td><?php echo $displayValue($row->date ?? ($row->payment_date ?? '')); ?></td><td><?php echo $displayValue($row->status ?? ''); ?></td></tr><?php endforeach; ?>
                            </tbody></table></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php if ($group === 'files' && $canManage): ?>
<script>
(function ($) {
    "use strict";
    $(function () {
        if (typeof Dropzone === 'undefined' || typeof appCreateDropzoneOptions !== 'function') {
            return;
        }
        Dropzone.autoDiscover = false;
        new Dropzone('#subject-attachments-upload', appCreateDropzoneOptions({
            paramName: 'file',
            success: function () {
                window.location.reload();
            }
        }));
    });
})(jQuery);
</script>
<?php endif; ?>
