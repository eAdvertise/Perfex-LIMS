<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$orders = $CI->lims_dashboard_model->attention_orders();
?>
<div class="widget" id="widget-lims-attention-orders" data-name="<?php echo html_escape(_l('lims_dashboard_attention_orders')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_attention_orders'); ?></h4>
            <hr class="hr-panel-heading">
            <div class="table-responsive">
                <table class="table table-hover no-mtop">
                    <thead><tr><th><?php echo _l('lims_order'); ?></th><th><?php echo _l('client'); ?></th><th><?php echo _l('lims_subject'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('due_date'); ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr<?php echo !empty($order->due_at) && strtotime($order->due_at) < time() ? ' class="danger"' : ''; ?>>
                            <td><a href="<?php echo admin_url('lims/orders/view/' . (int)$order->id); ?>"><?php echo html_escape($order->order_barcode ?: ('#' . $order->id)); ?></a><?php if ((int)$order->priority > 0): ?> <i class="fa fa-flag text-danger"></i><?php endif; ?></td>
                            <td><?php echo html_escape($order->customer_name ?: '-'); ?></td>
                            <td><?php echo html_escape($order->subject_name); ?></td>
                            <td><?php echo html_escape(ucfirst((string)$order->status)); ?></td>
                            <td><?php echo !empty($order->due_at) ? _dt($order->due_at) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$orders): ?><tr><td colspan="5" class="text-muted text-center"><?php echo _l('no_results_found'); ?></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
