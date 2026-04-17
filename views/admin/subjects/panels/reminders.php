<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (isset($subject)) : ?>
    <h4 class="customer-profile-group-heading">
        <?php echo _l('reminders'); ?>
    </h4>

    <div class="row">
        <div class="col-md-12">

            <a href="#"
               class="btn btn-primary mbot15"
               onclick="slideToggle('.subject-reminder-form'); return false;">
                <i class="fa-regular fa-plus tw-mr-1"></i>
                <?php echo _l('reminder_new'); ?>
            </a>

            <div class="subject-reminder-form hide">
                <?php echo form_open(admin_url('misc/add_reminder/' . (int)$subject->id . '/lims_subject')); ?>

                <!-- ΣΗΜΑΝΤΙΚΟ: δίνουμε rel_id & rel_type στο Misc_model -->
                <input type="hidden" name="rel_id" value="<?php echo (int)$subject->id; ?>">
                <input type="hidden" name="rel_type" value="lims_subject">

                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_datetime_input('date', 'reminder_date'); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="checkbox mtop25">
                            <input type="checkbox"
                                   name="notify_by_email"
                                   id="reminder_notify_by_email"
                                   value="1">
                            <label for="reminder_notify_by_email">
                                <?php echo _l('reminder_notify_me_by_email'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <?php echo render_textarea('description', 'reminder_description', '', ['rows' => 4]); ?>

                <!-- default assigned στον τρέχοντα χρήστη -->
                <input type="hidden" name="staff" value="<?php echo (int)get_staff_user_id(); ?>">

                <div class="text-right mtop15">
                    <button type="submit" class="btn btn-primary">
                        <?php echo _l('submit'); ?>
                    </button>
                </div>

                <?php echo form_close(); ?>
                <hr class="hr-panel-heading" />
            </div>

            <?php
            if (!isset($reminders) || !is_array($reminders)) {
                $reminders = [];
            }
            ?>

            <table class="table dt-table"
                   data-order-col="1"
                   data-order-type="desc">
                <thead>
                <tr>
                    <th><?php echo _l('reminder_description'); ?></th>
                    <th><?php echo _l('reminder_date'); ?></th>
                    <th><?php echo _l('reminder_staff'); ?></th>
                    <th><?php echo _l('reminder_is_notified'); ?></th>
                    <th><?php echo _l('options'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($reminders)) : ?>
                    <?php foreach ($reminders as $r): ?>
                        <?php
                        if (is_object($r)) {
                            $r = (array)$r;
                        }
                        if (!is_array($r)) {
                            continue;
                        }

                        $rid          = isset($r['id']) ? (int)$r['id'] : 0;
                        $rDescription = isset($r['description']) ? $r['description'] : '';
                        $rDate        = isset($r['date']) ? $r['date'] : '';
                        $rStaff       = isset($r['staff']) ? (int)$r['staff'] : 0;
                        $rIsNotified  = !empty($r['isnotified']);
                        ?>
                        <tr>
                            <td><?php echo nl2br(html_escape($rDescription)); ?></td>
                            <td data-order="<?php echo html_escape($rDate); ?>">
                                <?php echo $rDate ? _dt($rDate) : '—'; ?>
                            </td>
                            <td>
                                <?php
                                if ($rStaff) {
                                    echo '<a href="' . admin_url('profile/' . $rStaff) . '">'
                                        . html_escape(get_staff_full_name($rStaff))
                                        . '</a>';
                                } else {
                                    echo '—';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($rIsNotified): ?>
                                    <span class="text-success">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($rid): ?>
                                    <a href="<?php echo admin_url('misc/delete_reminder/' . $rid); ?>"
                                       class="text-danger _delete">
                                        <i class="fa-regular fa-trash-can fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
<?php endif; ?>
