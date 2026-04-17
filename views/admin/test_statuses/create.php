<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php // Lims/views/admin/test_statuses/create.php ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_test_statuses'); ?>
          <?php echo isset($row->id) ? ' #' . (int) $row->id : ''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/teststatuses/create' . (isset($row->id) ? '/' . (int)$row->id : ''))); ?>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text"
                       class="form-control"
                       name="name"
                       required
                       value="<?php echo html_escape($row->name ?? ''); ?>">
                <small class="help-block text-muted">
                  <?php echo _l('lims_ts_name_desc') ?: 'Display name shown to users.'; ?>
                </small>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('code'); ?></label>
                <input type="text"
                       class="form-control"
                       name="code"
                       required
                       value="<?php echo html_escape($row->code ?? ''); ?>"
                       placeholder="e.g. in_progress">
                <small class="help-block text-muted">
                  <?php echo _l('lims_ts_code_desc') ?: 'Unique slug (latin, no spaces).'; ?>
                </small>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label><?php echo _l('color'); ?></label>
                <input type="text"
                       class="form-control"
                       name="color"
                       value="<?php echo html_escape($row->color ?? ''); ?>"
                       placeholder="#3a87ad">
              </div>
            </div>

            <div class="col-md-3">
              <label class="control-label mright10"><?php echo _l('active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox"
                       name="active"
                       class="onoffswitch-checkbox"
                       id="ts_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int) $row->active === 1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="ts_active"></label>
              </div>
            </div>
          </div>

          <div class="row mtop10">
            <div class="col-md-2">
              <div class="checkbox checkbox-primary">
                <input type="checkbox"
                       name="requires_result"
                       id="requires_result"
                  <?php echo !empty($row->requires_result) ? 'checked' : ''; ?>>
                <label for="requires_result">
                  <?php echo _l('lims_ts_requires_result'); ?>
                </label>
              </div>
            </div>

            <div class="col-md-2">
              <div class="checkbox checkbox-primary">
                <input type="checkbox"
                       name="requires_verification"
                       id="requires_verification"
                  <?php echo !empty($row->requires_verification) ? 'checked' : ''; ?>>
                <label for="requires_verification">
                  <?php echo _l('lims_ts_requires_verification'); ?>
                </label>
              </div>
            </div>

            <div class="col-md-2">
              <div class="checkbox checkbox-primary">
                <input type="checkbox"
                       name="requires_approval"
                       id="requires_approval"
                  <?php echo !empty($row->requires_approval) ? 'checked' : ''; ?>>
                <label for="requires_approval">
                  <?php echo _l('lims_ts_requires_approval'); ?>
                </label>
              </div>
            </div>

            <div class="col-md-2">
              <div class="checkbox checkbox-primary">
                <input type="checkbox"
                       name="is_terminal"
                       id="is_terminal"
                  <?php echo !empty($row->is_terminal) ? 'checked' : ''; ?>>
                <label for="is_terminal">
                  <?php echo _l('lims_ts_is_terminal'); ?>
                </label>
              </div>
            </div>
          </div>

          <div class="text-right mtop15">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/teststatuses'); ?>" class="btn btn-default">
              <?php echo _l('lims_cancel'); ?>
            </a>
          </div>

        <?php echo form_close(); ?>

        <hr class="mtop20" />

        <div class="mtop20">
          <h5 class="mbot10">
            <?php echo _l('lims_ts_about_title') ?: 'About Test Statuses'; ?>
          </h5>
          <p class="text-muted">
            <?php echo _l('lims_ts_about_desc') ?: 'Configure the lifecycle and requirements for test progression.'; ?>
          </p>

          <h6 class="mtop15">
            <?php echo _l('lims_ts_guidelines_title') ?: 'Guidelines'; ?>
          </h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_ts_gl_1') ?: 'Keep codes unique and in lowercase (e.g., in_progress).'; ?></li>
            <li>• <?php echo _l('lims_ts_gl_2') ?: 'Mark terminal statuses for final states (e.g., reported).'; ?></li>
            <li>• <?php echo _l('lims_ts_gl_3') ?: 'Use the flags to enforce results/verification/approval before advancing.'; ?></li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
