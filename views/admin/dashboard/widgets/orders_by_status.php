<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$statuses = $CI->lims_dashboard_model->orders_by_status();
$totalOrders = array_sum($statuses);
$statusColors = [
    'draft' => 'default', 'submitted' => 'info', 'accessioned' => 'info',
    'appointment' => 'primary', 'samples' => 'primary', 'testing' => 'warning',
    'in_progress' => 'warning', 'verified' => 'success', 'approved' => 'success',
    'complete' => 'success', 'signed' => 'success', 'reported' => 'success',
    'canceled' => 'danger',
];
?>
<div class="widget" id="widget-lims-orders-by-status" data-name="<?php echo html_escape(_l('lims_dashboard_orders_by_status')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_orders_by_status'); ?></h4>
            <hr class="hr-panel-heading">
            <?php foreach ($statuses as $status => $count): ?>
                <?php $percentage = $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0; ?>
                <a href="<?php echo admin_url('lims/orders?status=' . rawurlencode($status)); ?>" class="tw-block tw-mb-3 tw-text-neutral-700">
                    <div class="tw-flex tw-justify-between tw-mb-1">
                        <span><?php echo html_escape(ucwords(str_replace('_', ' ', $status))); ?></span>
                        <strong><?php echo (int)$count; ?></strong>
                    </div>
                    <div class="progress no-margin">
                        <div class="progress-bar progress-bar-<?php echo $statusColors[$status] ?? 'default'; ?>" role="progressbar" aria-valuenow="<?php echo (int)$percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo (int)$percentage; ?>%"></div>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if (!$statuses): ?><p class="text-muted text-center no-margin"><?php echo _l('no_results_found'); ?></p><?php endif; ?>
        </div>
    </div>
</div>
