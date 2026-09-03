<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <div class="clearfix mtop10 mbot10">
          <h4 class="pull-left">
            <?php echo _l('lims_samples'); ?>
            <?php if (!empty($order_id)): ?>
              <small class="text-muted">— <?php echo _l('order'); ?> #<?php echo (int)$order_id; ?></small>
            <?php endif; ?>
          </h4>
          <div class="pull-right">
            <a href="<?php echo admin_url('lims/samples/create'.(!empty($order_id)?'?order_id='.(int)$order_id.'&return=order':'')); ?>" class="btn btn-primary">
              <i class="fa fa-plus"></i> <?php echo _l('lims_sample_add'); ?>
            </a>
            <?php if (!empty($order_id)): ?>
              <a href="<?php echo admin_url('lims/orders/view/'.(int)$order_id.'#samples'); ?>" class="btn btn-default">
                <i class="fa fa-long-arrow-left"></i> <?php echo _l('back'); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

        <table class="table dt-table" data-order-col="4" data-order-type="desc">
          <thead>
            <tr>
              <th>#</th>
              <th><?php echo _l('lims_sample_uid'); ?></th>
              <th><?php echo _l('barcode'); ?></th>
              <th><?php echo _l('lims_sample_type'); ?></th>
              <th><?php echo _l('created_at'); ?></th>
              <th><?php echo _l('status'); ?></th>
              <th><?php echo _l('options'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($rows)) foreach($rows as $r): ?>
              <tr>
                <td><?php echo (int)$r->id; ?></td>
                <td><?php echo html_escape($r->sample_uid); ?></td>
                <td>
                  <?php if(!empty($r->barcode)): ?>
                    <span class="label label-success"><?php echo html_escape($r->barcode); ?></span>
                  <?php else: ?>
                    <a class="btn btn-default btn-xs" href="<?php echo admin_url('lims/samples/generate_barcode/'.(int)$r->id.(!empty($order_id)?'?return=order':'')); ?>">
                      <i class="fa fa-barcode"></i> <?php echo _l('lims_generate_barcode'); ?>
                    </a>
                  <?php endif; ?>
                </td>
                <td><?php echo html_escape($r->sample_type_name ?: '-'); ?></td>
                <td data-order="<?php echo strtotime($r->created_at ?? ''); ?>"><?php echo _dt($r->created_at); ?></td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      <?php echo ucfirst($r->status ?: 'draft'); ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <?php foreach(['draft','collected','received','rejected'] as $st): ?>
                      <li><a href="#" class="ap-change-status" data-id="<?php echo (int)$r->id; ?>" data-status="<?php echo $st; ?>"><?php echo ucfirst($st); ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </td>
                <td>
                  <a href="<?php echo admin_url('lims/samples/create/'.(int)$r->id.(!empty($order_id)?'?order_id='.(int)$order_id.'&return=order':'')); ?>" class="btn btn-default" title="<?php echo _l('edit'); ?>">
                    <i class="fa fa-pencil"></i>
                  </a>
                  <a href="<?php echo admin_url('lims/samples/delete/'.(int)$r->id.(!empty($order_id)?'?return=order':'')); ?>" class="btn btn-danger _delete" title="<?php echo _l('delete'); ?>">
                    <i class="fa fa-trash"></i>
                  </a>
                  <?php if(empty($r->barcode)): ?>
                    <a href="<?php echo admin_url('lims/samples/generate_barcode/'.(int)$r->id.(!empty($order_id)?'?return=order':'')); ?>" class="btn btn-default " title="<?php echo _l('lims_generate_barcode'); ?>">
                      <i class="fa fa-barcode"></i>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  $(function(){
    $(document).on('click','.ap-change-status', function(e){
      e.preventDefault();
      var id = $(this).data('id'), st = $(this).data('status');
      $.post('<?php echo admin_url('lims/samples/change_status'); ?>', {id:id, status:st})
       .done(function(resp){ try{resp=JSON.parse(resp)}catch(e){}; if(resp && resp.success){ window.location.reload(); } else { alert_float('danger','<?php echo _l('problem_updating'); ?>'); } })
       .fail(function(){ alert_float('danger','<?php echo _l('problem_updating'); ?>'); });
    });
  });
})(jQuery);
</script>
