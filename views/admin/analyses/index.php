<?php //views/admin/analyses/index.php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-6"><h4 class="no-margin"><?php echo _l('lims_analyses'); ?></h4></div>
          <div class="col-md-6 text-right">
            <a href="<?php echo admin_url('lims/analyses/create'); ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i> <?php echo _l('add_new'); ?>
            </a>
          </div>
        </div>
        <hr class="hr-panel-heading"/>

        <div class="table-responsive">
          <table class="table dt-table">
            <thead>
              <tr>
                <th>#</th>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('code'); ?></th>
                <th>LOINC</th>
                <th><?php echo _l('item'); ?></th>
                <th><?php echo _l('lims_tbl_price'); ?></th>
                <th><?php echo _l('lims_contract_active'); ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($rows as $r): ?>
                <?php
                  $item = null; $rate = null;
                  if (!empty($r->item_id)) {
                    $item = $this->db->select('description,rate')->where('id',$r->item_id)->get(db_prefix().'items')->row();
                    $rate = $item->rate ?? null;
                  }
                ?>
                <tr>
                  <td><?php echo (int)$r->id; ?></td>
                  <td><?php echo html_escape($r->name); ?></td>
                  <td><?php echo html_escape($r->code); ?></td>
                  <td><?php echo html_escape($r->loinc_code); ?></td>
                  <td><?php echo $item ? html_escape($item->description) : '-'; ?></td>
                  <td><?php echo $rate!==null ? (float)$rate : '-'; ?></td>
                  <td>
                    <div class="onoffswitch">
                      <input type="checkbox"
                        class="onoffswitch-checkbox an-status-toggle"
                        id="an_active_<?php echo (int)$r->id; ?>"
                        data-id="<?php echo (int)$r->id; ?>"
                        <?php echo ((int)$r->active===1?'checked':''); ?>
                        <?php echo has_permission('lims','','admin') ? '' : 'disabled'; ?>>
                      <label class="onoffswitch-label" for="an_active_<?php echo (int)$r->id; ?>"></label>
                    </div>
                  </td>
                  <td class="text-right">
                    <a href="<?php echo admin_url('lims/analyses/create/'.$r->id); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                    <a href="<?php echo admin_url('lims/analyses/delete/'.$r->id); ?>" class="btn btn-danger btn-sm _delete"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

<script>
(function($){
  $(document).on('change', '.an-status-toggle', function(){
    var $cb = $(this), id = $cb.data('id'), val = $cb.is(':checked') ? 1 : 0;
    $cb.prop('disabled', true);
    $.post(admin_url + 'lims/analyses/toggle_status', {id:id, active:val})
      .fail(function(){
        $cb.prop('checked', !val);
        alert_float('danger','<?php echo _l('lims_error_generic'); ?>');
      })
      .always(function(){ $cb.prop('disabled', false); });
  });
})(jQuery);
</script>
