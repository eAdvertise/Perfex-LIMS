<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="row mtop5 mbot15">
          <div class="col-md-6"><h4 class="mtop5"><?php echo _l('lims_culture_types'); ?></h4></div>
          <div class="col-md-6 text-right">
            <a href="<?php echo admin_url('lims/culturetypes/create'); ?>" class="btn btn-primary">
              <i class="fa-regular fa-plus mright5"></i><?php echo _l('new') ?: 'New'; ?>
            </a>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table dt-table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('code'); ?></th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('lims_actions'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($rows)) foreach($rows as $r): ?>
                <tr>
                  <td><?php echo (int)$r->id; ?></td>
                  <td><?php echo html_escape($r->name); ?></td>
                  <td><?php echo html_escape($r->code); ?></td>
                  <td>
                    <span class="label <?php echo !empty($r->active)?'label-success':'label-default'; ?>">
                      <?php echo !empty($r->active) ? _l('active') : _l('inactive'); ?>
                    </span>
                  </td>
                  <td>
                    <a href="<?php echo admin_url('lims/culturetypes/create/'.$r->id); ?>" class="btn btn-default btn-icon">
                      <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <a href="<?php echo admin_url('lims/culturetypes/delete/'.$r->id); ?>" class="btn btn-danger btn-icon _delete">
                      <i class="fa-regular fa-trash-can"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if(empty($rows)): ?>
                <tr><td colspan="5" class="text-center text-muted"><?php echo _l('no_data_found'); ?></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
