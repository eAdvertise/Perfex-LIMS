<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$orders = $CI->lims_dashboard_model->ready_to_sign();
?>
<div class="widget" id="widget-lims-ready-to-sign" data-name="<?php echo html_escape(_l('lims_dashboard_ready_to_sign')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_ready_to_sign'); ?></h4>
            <hr class="hr-panel-heading">
            <ul class="list-unstyled no-margin">
                <?php foreach ($orders as $order): ?>
                    <li class="tw-py-2 tw-border-b tw-border-solid tw-border-neutral-200">
                        <a href="<?php echo admin_url('lims/tests/order/' . (int)$order->id); ?>"><strong><?php echo html_escape($order->order_barcode ?: ('#' . $order->id)); ?></strong></a>
                        <div class="text-muted"><?php echo html_escape($order->customer_name ?: '-'); ?> · <?php echo html_escape($order->subject_name); ?></div>
                        <a class="btn btn-success btn-xs mtop5" href="<?php echo admin_url('lims/tests/order/' . (int)$order->id); ?>"><i class="fa fa-signature"></i> <?php echo _l('lims_dashboard_review_sign'); ?></a>
                    </li>
                <?php endforeach; ?>
                <?php if (!$orders): ?><li class="text-muted text-center"><?php echo _l('no_results_found'); ?></li><?php endif; ?>
            </ul>
        </div>
    </div>
</div>
