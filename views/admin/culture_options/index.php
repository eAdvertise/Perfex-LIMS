<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-6">
            <h4 class="mtop5">
              <?php echo _l('lims_culture_options'); ?>
            </h4>
          </div>
          <div class="col-md-6 text-right">
            <a href="<?php echo admin_url('lims/culture_options/create'); ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
            </a>
          </div>
        </div>

        <hr/>

        <?php if (empty($rows)): ?>
          <p class="text-muted"><?php echo _l('no_items_found') ?: 'No culture option sets found.'; ?></p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th><?php echo _l('name'); ?></th>
                  <th><?php echo _l('code'); ?></th>
                  <th><?php echo _l('description'); ?></th>
                  <th><?php echo _l('active'); ?></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r): ?>
                  <tr>
                    <td><?php echo html_escape($r->name); ?></td>
                    <td><code><?php echo html_escape($r->code); ?></code></td>
                    <td><?php echo nl2br(html_escape($r->description)); ?></td>
                    <td>
                      <?php if ((int)$r->active === 1): ?>
                        <span class="label label-success"><?php echo _l('settings_yes'); ?></span>
                      <?php else: ?>
                        <span class="label label-default"><?php echo _l('settings_no'); ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-right">
                      <a href="<?php echo admin_url('lims/culture_options/create/'.$r->id); ?>" class="btn btn-default btn-sm">
                        <i class="fa fa-pencil"></i>
                      </a>
                      <a href="<?php echo admin_url('lims/culture_options/delete/'.$r->id); ?>"
                         class="btn btn-danger btn-sm _delete">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
