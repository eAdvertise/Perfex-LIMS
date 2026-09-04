<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$metrics = $CI->lims_dashboard_model->turnaround_metrics(30);
$changeIsBetter = $metrics['change_percent'] <= 0;
?>
<div class="widget" id="widget-lims-turnaround-time" data-name="<?php echo html_escape(_l('lims_dashboard_turnaround_time')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_turnaround_time'); ?></h4>
            <p class="text-muted"><?php echo _l('lims_dashboard_last_30_days'); ?></p>
            <hr class="hr-panel-heading">
            <div class="row text-center">
                <div class="col-xs-4">
                    <strong class="tw-text-2xl tw-block"><?php echo number_format((float)$metrics['average_hours'], 1); ?>h</strong>
                    <span class="text-muted"><?php echo _l('lims_dashboard_average_completion'); ?></span>
                </div>
                <div class="col-xs-4">
                    <strong class="tw-text-2xl tw-block text-success"><?php echo number_format((float)$metrics['on_time_percent'], 1); ?>%</strong>
                    <span class="text-muted"><?php echo _l('lims_dashboard_on_time'); ?></span>
                </div>
                <div class="col-xs-4">
                    <strong class="tw-text-2xl tw-block"><?php echo (int)$metrics['completed_orders']; ?></strong>
                    <span class="text-muted"><?php echo _l('lims_dashboard_completed_orders'); ?></span>
                </div>
            </div>
            <div class="text-center mtop15 <?php echo $changeIsBetter ? 'text-success' : 'text-danger'; ?>">
                <i class="fa fa-arrow-<?php echo $changeIsBetter ? 'down' : 'up'; ?>"></i>
                <?php echo number_format(abs((float)$metrics['change_percent']), 1); ?>%
                <span class="text-muted"><?php echo _l('lims_dashboard_vs_previous_period'); ?></span>
            </div>
        </div>
    </div>
</div>
