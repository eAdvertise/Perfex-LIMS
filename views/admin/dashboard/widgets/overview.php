<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$overview = $CI->lims_dashboard_model->overview();
$cards = [
    ['label' => _l('lims_dashboard_orders_today'), 'value' => $overview['orders_today'], 'icon' => 'fa-cart-shopping', 'color' => 'info', 'url' => admin_url('lims/orders')],
    ['label' => _l('lims_dashboard_pending_samples'), 'value' => $overview['pending_samples'], 'icon' => 'fa-vial', 'color' => 'warning', 'url' => admin_url('lims/samples')],
    ['label' => _l('lims_dashboard_tests_progress'), 'value' => $overview['tests_progress'], 'icon' => 'fa-flask', 'color' => 'primary', 'url' => admin_url('lims/tests')],
    ['label' => _l('lims_dashboard_ready_to_sign'), 'value' => $overview['ready_to_sign'], 'icon' => 'fa-signature', 'color' => 'success', 'url' => admin_url('lims/tests')],
    ['label' => _l('lims_dashboard_overdue'), 'value' => $overview['overdue'], 'icon' => 'fa-triangle-exclamation', 'color' => 'danger', 'url' => admin_url('lims/orders')],
    ['label' => _l('lims_dashboard_completed_today'), 'value' => $overview['completed_today'], 'icon' => 'fa-circle-check', 'color' => 'success', 'url' => admin_url('lims/orders')],
];
?>
<div class="widget" id="widget-lims-overview" data-name="<?php echo html_escape(_l('lims_dashboard_overview')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_overview'); ?></h4>
            <hr class="hr-panel-heading">
            <div class="row">
                <?php foreach ($cards as $card): ?>
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <a href="<?php echo $card['url']; ?>" class="tw-block tw-text-neutral-700 hover:tw-text-neutral-900">
                            <div class="tw-flex tw-items-center tw-gap-3 tw-mb-4">
                                <span class="text-<?php echo $card['color']; ?>"><i class="fa <?php echo $card['icon']; ?> fa-2x"></i></span>
                                <span><strong class="tw-text-2xl tw-block"><?php echo (int)$card['value']; ?></strong><?php echo html_escape($card['label']); ?></span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
