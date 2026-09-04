<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$summary = $CI->lims_dashboard_model->billing_summary();
$items = [
    ['label' => _l('lims_dashboard_uninvoiced_orders'), 'value' => $summary['uninvoiced'], 'color' => 'warning', 'url' => admin_url('lims/orders')],
    ['label' => _l('lims_dashboard_draft_invoices'), 'value' => $summary['draft'], 'color' => 'default', 'url' => admin_url('invoices?status=6')],
    ['label' => _l('lims_dashboard_unpaid_invoices'), 'value' => $summary['unpaid'], 'color' => 'info', 'url' => admin_url('invoices?status=1')],
    ['label' => _l('lims_dashboard_overdue_invoices'), 'value' => $summary['overdue'], 'color' => 'danger', 'url' => admin_url('invoices?status=4')],
];
?>
<div class="widget" id="widget-lims-billing-summary" data-name="<?php echo html_escape(_l('lims_dashboard_billing_summary')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_billing_summary'); ?></h4>
            <hr class="hr-panel-heading">
            <div class="row text-center">
                <?php foreach ($items as $item): ?>
                    <div class="col-xs-6 tw-mb-4">
                        <a href="<?php echo $item['url']; ?>" class="tw-block tw-text-neutral-700">
                            <strong class="tw-text-2xl tw-block text-<?php echo $item['color']; ?>"><?php echo (int)$item['value']; ?></strong>
                            <span class="text-muted"><?php echo html_escape($item['label']); ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
