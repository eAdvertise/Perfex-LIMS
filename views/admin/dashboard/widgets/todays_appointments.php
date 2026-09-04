<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
$CI->load->model('lims/lims_dashboard_model');
$appointments = $CI->lims_dashboard_model->todays_appointments();
?>
<div class="widget" id="widget-lims-todays-appointments" data-name="<?php echo html_escape(_l('lims_dashboard_todays_appointments')); ?>">
    <div class="panel_s">
        <div class="panel-body">
            <div class="widget-dragger"></div>
            <h4 class="no-margin"><?php echo _l('lims_dashboard_todays_appointments'); ?></h4>
            <hr class="hr-panel-heading">
            <div class="table-responsive">
                <table class="table table-hover no-mtop">
                    <thead><tr><th><?php echo _l('time'); ?></th><th><?php echo _l('lims_subject'); ?></th><th><?php echo _l('client'); ?></th><th><?php echo _l('status'); ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><a href="<?php echo admin_url('lims/appointments/show/' . (int)$appointment->id); ?>"><?php echo date('H:i', strtotime($appointment->appointment_at)); ?></a></td>
                            <td><?php echo html_escape($appointment->subject_name); ?></td>
                            <td><?php echo html_escape($appointment->customer_name ?: '-'); ?></td>
                            <td><?php echo html_escape(ucfirst((string)$appointment->status)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$appointments): ?><tr><td colspan="4" class="text-muted text-center"><?php echo _l('no_results_found'); ?></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
