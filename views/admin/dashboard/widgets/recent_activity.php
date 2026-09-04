<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$activities = $CI->lims_dashboard_model->recent_activity();
$activityIcons = [
    'signed' => 'fa-signature', 'status_changed' => 'fa-arrows-rotate',
    'sample_collected' => 'fa-vial-circle-check', 'sample_uncollected' => 'fa-vial',
    'appointment_created' => 'fa-calendar-plus', 'invoice_created' => 'fa-file-invoice',
    'items_updated' => 'fa-pen-to-square',
];
?>
<div class="widget" id="widget-lims-recent-activity" data-name="<?php echo html_escape(_l('lims_dashboard_recent_activity')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_recent_activity'); ?></h4>
            <hr class="hr-panel-heading">
            <ul class="list-unstyled no-margin">
                <?php foreach ($activities as $activity): ?>
                    <li class="tw-flex tw-gap-3 tw-py-2 tw-border-b tw-border-solid tw-border-neutral-200">
                        <span class="text-muted"><i class="fa <?php echo $activityIcons[$activity->action] ?? 'fa-clock-rotate-left'; ?>"></i></span>
                        <div class="tw-grow">
                            <a href="<?php echo admin_url('lims/orders/view/' . (int)$activity->order_id); ?>"><strong><?php echo html_escape($activity->order_barcode ?: ('#' . $activity->order_id)); ?></strong></a>
                            <div><?php echo html_escape($activity->message ?: ucwords(str_replace('_', ' ', $activity->action))); ?></div>
                            <small class="text-muted"><?php echo html_escape(trim($activity->staff_name)); ?><?php if (!empty($activity->created_at)): ?> · <?php echo _dt($activity->created_at); ?><?php endif; ?></small>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php if (!$activities): ?><li class="text-muted text-center"><?php echo _l('no_results_found'); ?></li><?php endif; ?>
            </ul>
        </div>
    </div>
</div>
