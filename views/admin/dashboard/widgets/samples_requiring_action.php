<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$samples = $CI->lims_dashboard_model->samples_requiring_action();
?>
<div class="widget" id="widget-lims-samples-requiring-action" data-name="<?php echo html_escape(_l('lims_dashboard_samples_requiring_action')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <div class="tw-flex tw-items-center tw-justify-between">
                <h4 class="no-margin"><i class="fa fa-vial text-warning"></i> <?php echo _l('lims_dashboard_samples_requiring_action'); ?></h4>
                <a href="<?php echo admin_url('lims/samples'); ?>" class="text-muted"><?php echo _l('view_all'); ?></a>
            </div>
            <hr class="hr-panel-heading">
            <div class="table-responsive">
                <table class="table table-hover no-mtop">
                    <thead><tr><th><?php echo _l('lims_sample'); ?></th><th><?php echo _l('lims_order'); ?></th><th><?php echo _l('lims_subject'); ?></th><th><?php echo _l('status'); ?></th><th><?php echo _l('lims_dashboard_next_action'); ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($samples as $sample): ?>
                        <?php
                        $overdue = !empty($sample->due_at) && strtotime($sample->due_at) < time();
                        $nextAction = $sample->status === 'collected' ? _l('lims_dashboard_receive_sample') : _l('lims_dashboard_collect_sample');
                        ?>
                        <tr<?php echo $overdue ? ' class="danger"' : ''; ?>>
                            <td><a href="<?php echo admin_url('lims/samples/create/' . (int)$sample->id); ?>"><strong><?php echo html_escape($sample->sample_uid ?: ('#' . $sample->id)); ?></strong></a><small class="text-muted tw-block"><?php echo html_escape($sample->sample_type_name ?: ''); ?></small></td>
                            <td><a href="<?php echo admin_url('lims/orders/view/' . (int)$sample->order_id); ?>"><?php echo html_escape($sample->order_barcode ?: ('#' . $sample->order_id)); ?></a><?php if ((int)$sample->priority > 0): ?> <i class="fa fa-flag text-danger"></i><?php endif; ?></td>
                            <td><?php echo html_escape($sample->subject_name); ?></td>
                            <td><span class="label label-<?php echo $sample->status === 'collected' ? 'info' : 'warning'; ?>"><?php echo html_escape(ucfirst((string)$sample->status)); ?></span></td>
                            <td><a href="<?php echo admin_url('lims/samples/create/' . (int)$sample->id); ?>" class="btn btn-default btn-xs"><?php echo html_escape($nextAction); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$samples): ?><tr><td colspan="5" class="text-muted text-center"><?php echo _l('lims_dashboard_no_pending_samples'); ?></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
