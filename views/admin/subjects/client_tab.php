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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
