<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$departments = $CI->lims_dashboard_model->tests_by_department();
?>
<div class="widget" id="widget-lims-tests-by-department" data-name="<?php echo html_escape(_l('lims_dashboard_tests_by_department')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <div class="tw-flex tw-items-center tw-justify-between">
                <h4 class="no-margin"><?php echo _l('lims_dashboard_tests_by_department'); ?></h4>
                <a href="<?php echo admin_url('lims/tests'); ?>" class="text-muted"><?php echo _l('view_all'); ?></a>
            </div>
            <hr class="hr-panel-heading">
            <div class="table-responsive">
                <table class="table table-hover no-mtop">
                    <thead>
                        <tr>
                            <th><?php echo _l('lims_departments'); ?></th>
                            <th class="text-center"><?php echo _l('lims_dashboard_pending'); ?></th>
                            <th class="text-center"><?php echo _l('lims_dashboard_in_progress'); ?></th>
                            <th class="text-center"><?php echo _l('lims_dashboard_completed'); ?></th>
                            <th class="text-center"><?php echo _l('total'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($departments as $department): ?>
                            <tr>
                                <td><strong><?php echo html_escape($department->department_name ?: _l('lims_dashboard_unassigned')); ?></strong></td>
                                <td class="text-center"><span class="label label-warning"><?php echo (int)$department->pending_tests; ?></span></td>
                                <td class="text-center"><span class="label label-info"><?php echo (int)$department->progress_tests; ?></span></td>
                                <td class="text-center"><span class="label label-success"><?php echo (int)$department->completed_tests; ?></span></td>
                                <td class="text-center"><strong><?php echo (int)$department->total_tests; ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$departments): ?><tr><td colspan="5" class="text-muted text-center"><?php echo _l('no_results_found'); ?></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
