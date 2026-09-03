<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <h4 class="mtop5">
          <?php echo _l('lims_subject') ?: 'Subject'; ?>
          #<?php echo (int)$subject->id; ?>
          <small class="text-muted">
            &mdash; <?php echo html_escape($subject->display_name); ?>
          </small>
        </h4>
        <hr />

        <div class="row mtop10">
          <div class="col-md-6">
            <h5><?php echo _l('lims_subject_details') ?: 'Details'; ?></h5>
            <table class="table table-striped table-condensed">
              <tbody>
                <tr>
                  <td><strong><?php echo _l('client'); ?>:</strong></td>
                  <td>
                    <?php if (!empty($subject->client_id)) : ?>
                      <a href="<?php echo admin_url('clients/client/' . (int)$subject->client_id); ?>" target="_blank">
                        <?php echo 'Client #' . (int)$subject->client_id; ?>
                      </a>
                    <?php else : ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td><strong><?php echo _l('type'); ?>:</strong></td>
                  <td><?php echo html_escape($subject->type); ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo _l('email'); ?>:</strong></td>
                  <td><?php echo html_escape($subject->email ?: '—'); ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo _l('clients_phone'); ?>:</strong></td>
                  <td><?php echo html_escape($subject->phone ?: '—'); ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo _l('address'); ?>:</strong></td>
                  <td><?php echo html_escape($subject->address ?: '—'); ?></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="col-md-6">
            <h5><?php echo _l('notes'); ?></h5>
            <div class="well">
              <?php if (!empty($subject->notes)) : ?>
                <?php echo nl2br(html_escape($subject->notes)); ?>
              <?php else : ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <hr />
        <p class="text-muted">
          <?php echo _l('lims_subject_placeholder_next_steps')
            ?: 'Here we will later add tabs for Orders, Invoices, Payments, Samples, Reports etc.'; ?>
        </p>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
