<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$tests = $CI->lims_dashboard_model->assigned_tests(get_staff_user_id());
?>
<div class="widget" id="widget-lims-my-assigned-tests" data-name="<?php echo html_escape(_l('lims_dashboard_my_assigned_tests')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <div class="tw-flex tw-items-center tw-justify-between">
                <h4 class="no-margin"><i class="fa fa-user-check text-info"></i> <?php echo _l('lims_dashboard_my_assigned_tests'); ?></h4>
                <a href="<?php echo admin_url('lims/tests'); ?>" class="text-muted"><?php echo _l('view_all'); ?></a>
            </div>
            <hr class="hr-panel-heading">
            <div class="table-responsive">
                <table class="table table-hover no-mtop">
                    <thead>
                        <tr><th><?php echo _l('lims_order'); ?></th><th><?php echo _l('lims_analysis'); ?></th><th><?php echo _l('lims_sample'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('due_date'); ?></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tests as $test): ?>
                        <?php $overdue = !empty($test->due_at) && strtotime($test->due_at) < time(); ?>
                        <tr<?php echo $overdue ? ' class="danger"' : ''; ?>>
                            <td>
                                <a href="<?php echo admin_url('lims/tests/order/' . (int)$test->order_id . '?test=' . (int)$test->id); ?>"><?php echo html_escape($test->order_barcode ?: ('#' . $test->order_id)); ?></a>
                                <?php if ((int)$test->priority > 0): ?><i class="fa fa-flag text-danger" title="<?php echo html_escape(_l('priority')); ?>"></i><?php endif; ?>
                            </td>
                            <td><?php echo html_escape($test->analysis_name ?: '-'); ?><small class="text-muted tw-block"><?php echo html_escape($test->department_name ?: ''); ?></small></td>
                            <td><?php echo html_escape($test->sample_uid ?: '-'); ?></td>
                            <td><span class="label label-<?php echo $test->test_status === 'in_progress' ? 'info' : 'warning'; ?>"><?php echo html_escape(ucwords(str_replace('_', ' ', $test->test_status))); ?></span></td>
                            <td><?php echo !empty($test->due_at) ? _dt($test->due_at) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$tests): ?><tr><td colspan="5" class="text-muted text-center"><?php echo _l('lims_dashboard_no_assigned_tests'); ?></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
