<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$results = $CI->lims_dashboard_model->critical_results();
?>
<div class="widget" id="widget-lims-critical-results" data-name="<?php echo html_escape(_l('lims_dashboard_critical_results')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <div class="tw-flex tw-items-center tw-justify-between">
                <h4 class="no-margin"><i class="fa fa-triangle-exclamation text-danger"></i> <?php echo _l('lims_dashboard_critical_results'); ?></h4>
                <a href="<?php echo admin_url('lims/tests'); ?>" class="text-muted"><?php echo _l('view_all'); ?></a>
            </div>
            <hr class="hr-panel-heading">
            <div class="table-responsive">
                <table class="table table-hover no-mtop">
                    <thead><tr><th><?php echo _l('lims_order'); ?></th><th><?php echo _l('lims_analysis'); ?></th><th><?php echo _l('result'); ?></th><th><?php echo _l('status'); ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($results as $result): ?>
                        <?php $value = $result->value_numeric !== null ? $result->value_numeric : $result->value_text; ?>
                        <tr>
                            <td><a href="<?php echo admin_url('lims/tests/order/' . (int)$result->order_id . '?test=' . (int)$result->test_id); ?>"><?php echo html_escape($result->order_barcode ?: ('#' . $result->order_id)); ?></a></td>
                            <td><?php echo html_escape($result->analysis_name ?: '-'); ?></td>
                            <td><strong><?php echo html_escape((string)$value); ?></strong> <?php echo html_escape((string)$result->unit); ?></td>
                            <td><span class="label label-<?php echo in_array($result->flag, ['LL', 'HH', 'A'], true) ? 'danger' : 'warning'; ?>"><?php echo html_escape($result->flag); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$results): ?><tr><td colspan="4" class="text-muted text-center"><?php echo _l('lims_dashboard_no_abnormal_results'); ?></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
