<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$trend = $CI->lims_dashboard_model->activity_trend(14);
$maximum = 1;
foreach ($trend as $day) {
    $maximum = max($maximum, $day['orders'], $day['samples'], $day['reports']);
}
?>
<div class="widget" id="widget-lims-activity-trend" data-name="<?php echo html_escape(_l('lims_dashboard_activity_trend')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_activity_trend'); ?></h4>
            <p class="text-muted"><?php echo _l('lims_dashboard_last_14_days'); ?></p>
            <hr class="hr-panel-heading">
            <div class="tw-flex tw-items-end tw-gap-1" style="height: 170px;">
                <?php foreach ($trend as $day): ?>
                    <div class="tw-flex-1 tw-flex tw-items-end tw-justify-center tw-gap-0.5" style="height: 140px;" title="<?php echo html_escape(_d($day['date']) . ' — ' . _l('lims_orders') . ': ' . $day['orders'] . ', ' . _l('lims_samples') . ': ' . $day['samples'] . ', ' . _l('lims_dashboard_reports') . ': ' . $day['reports']); ?>">
                        <span style="display:block;width:28%;height:<?php echo max(2, round(($day['orders'] / $maximum) * 100)); ?>%;background:#03a9f4;"></span>
                        <span style="display:block;width:28%;height:<?php echo max(2, round(($day['samples'] / $maximum) * 100)); ?>%;background:#ff9800;"></span>
                        <span style="display:block;width:28%;height:<?php echo max(2, round(($day['reports'] / $maximum) * 100)); ?>%;background:#84c529;"></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="tw-flex tw-justify-center tw-gap-4 text-muted">
                <span><i class="fa fa-square text-info"></i> <?php echo _l('lims_orders'); ?></span>
                <span><i class="fa fa-square text-warning"></i> <?php echo _l('lims_samples'); ?></span>
                <span><i class="fa fa-square text-success"></i> <?php echo _l('lims_dashboard_reports'); ?></span>
            </div>
        </div>
    </div>
</div>
