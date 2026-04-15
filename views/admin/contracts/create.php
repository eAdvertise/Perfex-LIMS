<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_contract_create'); ?>
          <?php echo isset($contract->id) ? ' #'.(int)$contract->id : ''; ?>
        </h4>

       <?php echo form_open(admin_url('lims/save_contract'.(isset($contract->id)?'/'.$contract->id:''))); ?>
			<?php
			  // αν ήρθαμε από καρτέλα πελάτη με ?client_id=..&return_to=client_tab, πέρασέ τα σαν hidden
			  $return_to = $this->input->get('return_to');
			  $prefill_client = (int)$this->input->get('client_id');
			?>
			<input type="hidden" name="return_to" value="<?php echo ($return_to === 'client_tab' ? 'client_tab' : ''); ?>">
			<?php if ($prefill_client > 0): ?>
			  <input type="hidden" name="client_id" value="<?php echo $prefill_client; ?>">
			<?php endif; ?>
          <div class="row">
            <!-- Client (optional) -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_client'); ?></label>
                <select name="client_id" class="form-control selectpicker" data-live-search="true">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($clients)) foreach($clients as $cl): ?>
                    <option value="<?php echo (int)$cl->userid; ?>"
                      <?php echo (isset($contract->client_id) && (int)$contract->client_id === (int)$cl->userid) ? 'selected' : ''; ?>>
                      <?php echo html_escape($cl->company).' (#'.(int)$cl->userid.')'; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Contract name (required) -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('lims_contract_name'); ?></label>
                <input type="text" class="form-control" name="name"
                  value="<?php echo isset($contract->name)?html_escape($contract->name):''; ?>" required>
              </div>
            </div>

            <!-- Code (optional) -->
            <div class="col-md-4">
              <div class="form-group">
                <label>Code</label>
                <input type="text" class="form-control" name="code"
                  value="<?php echo isset($contract->code)?html_escape($contract->code):''; ?>">
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Discount % (optional) -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_contract_discount_percent'); ?></label>
                <input type="number" step="0.01" class="form-control" name="discount_percent"
                  value="<?php echo isset($contract->discount_percent)?html_escape($contract->discount_percent):''; ?>">
              </div>
            </div>

            <!-- Priority -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_contract_priority'); ?></label>
                <input type="number" class="form-control" name="priority"
                  value="<?php echo isset($contract->priority)?(int)$contract->priority:0; ?>">
              </div>
            </div>

            <!-- Valid from (optional) -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_contract_valid_from'); ?></label>
                <input type="date" class="form-control" name="valid_from"
                  value="<?php echo isset($contract->valid_from)?html_escape($contract->valid_from):''; ?>">
              </div>
            </div>

            <!-- Valid to (optional) -->
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo _l('lims_contract_valid_to'); ?></label>
                <input type="date" class="form-control" name="valid_to"
                  value="<?php echo isset($contract->valid_to)?html_escape($contract->valid_to):''; ?>">
              </div>
            </div>
          </div>

          <!-- Active switch (default ON) -->
          <div class="form-group mtop10">
            <label class="control-label mright10"><?php echo _l('lims_contract_active'); ?></label>
            <div class="onoffswitch">
              <input type="checkbox" name="active" class="onoffswitch-checkbox" id="lims_contract_active"
                <?php echo (!isset($contract) || (isset($contract->active) && (int)$contract->active === 1)) ? 'checked' : ''; ?>>
              <label class="onoffswitch-label" for="lims_contract_active"></label>
            </div>
          </div>

          <hr/>
          <h5 class="mbot10"><?php echo _l('lims_contract_add_price'); ?></h5>

          <?php
            // --- Dynamic column classes: currencies fixed col-md-2, item fills the rest ---
            $curCount = !empty($currencies) ? count($currencies) : 0;
            $currencyColClass = 'col-md-2';                 // fixed for each currency
            $itemColSpan = 12 - (2 * $curCount);            // fill remaining width
            if ($curCount === 0) { $itemColSpan = 12; }
            if ($itemColSpan < 2) { $itemColSpan = 2; }     // minimum
            $itemColClass = 'col-md-' . $itemColSpan;
          ?>

          <div id="price-rows">
            <?php
              $rows_items = (!empty($existing_prices) && is_array($existing_prices)) ? array_keys($existing_prices) : [0];
              $curCountForRemove = $curCount;
              foreach($rows_items as $row_item_id):
            ?>
            <div class="row price-row mtop10">
              <!-- Item dropdown -->
              <div class="<?php echo $itemColClass; ?>">
                <label><?php echo _l('lims_contract_item'); ?></label>
                <select name="item_id[]" class="form-control selectpicker item-select" data-live-search="true">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php if(!empty($items)) foreach($items as $it): ?>
                    <option value="<?php echo (int)$it->id; ?>"
                      <?php echo ((int)$row_item_id === (int)$it->id) ? 'selected' : ''; ?>>
                      <?php
                        $label = $it->description;
                        if ($it->rate !== null && $it->rate !== '') { $label .= ' — '._l('lims_tbl_price').': '.(float)$it->rate; }
                        echo html_escape($label);
                      ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- One input per currency (fixed col-md-2) -->
              <?php
                if(!empty($currencies)):
                  $idx = 0;
                  foreach($currencies as $cur):
                    $curName = $cur->name;
                    $prefill = ($row_item_id && !empty($existing_prices[$row_item_id][$curName])) ? (float)$existing_prices[$row_item_id][$curName] : '';
                    $idx++;
              ?>
                <div class="<?php echo $currencyColClass; ?>">
                  <label><?php echo _l('lims_tbl_price'); ?> (<?php echo html_escape($curName); ?>)</label>
                  <div class="input-group">
                    <input type="number" step="0.0001" class="form-control price-input"
                           name="fixed_price[<?php echo html_escape($curName); ?>][]" 
                           value="<?php echo html_escape($prefill); ?>" placeholder="0.00">
                    <?php if($idx === $curCountForRemove): ?>
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-remove-row" title="Remove">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
              <?php
                  endforeach;
                endif;
              ?>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="mtop10">
            <button type="button" class="btn btn-default" id="btn-add-row">+ Add Row</button>
          </div>

          <hr/>
          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <?php
			  $cancel_url = admin_url('lims/contracts');
			  if ($return_to === 'client_tab' && $prefill_client > 0) {
				  $cancel_url = admin_url('clients/client/'.$prefill_client.'?group=lims-contracts');
			  }
			?>
			<a href="<?php echo $cancel_url; ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>
</div>

<?php
// ===== Server-side template for new row (with remove icon in last currency) =====
$curCountTpl = !empty($currencies) ? count($currencies) : 0;
$currencyColClassTpl = 'col-md-2';
$itemColSpanTpl = 12 - (2 * $curCountTpl);
if ($curCountTpl === 0) { $itemColSpanTpl = 12; }
if ($itemColSpanTpl < 2) { $itemColSpanTpl = 2; }
$itemColClassTpl = 'col-md-' . $itemColSpanTpl;

ob_start();
?>
<div class="row price-row mtop10">
  <div class="<?php echo $itemColClassTpl; ?>">
    <label><?php echo _l('lims_contract_item'); ?></label>
    <select name="item_id[]" class="form-control selectpicker item-select" data-live-search="true">
      <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
      <?php if(!empty($items)) foreach($items as $it): ?>
        <option value="<?php echo (int)$it->id; ?>">
          <?php
            $label = $it->description;
            if ($it->rate !== null && $it->rate !== '') { $label .= ' — '._l('lims_tbl_price').': '.(float)$it->rate; }
            echo html_escape($label);
          ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php
    if(!empty($currencies)):
      $idxTpl = 0;
      foreach($currencies as $cur):
        $idxTpl++;
  ?>
    <div class="<?php echo $currencyColClassTpl; ?>">
      <label><?php echo _l('lims_tbl_price'); ?> (<?php echo html_escape($cur->name); ?>)</label>
      <div class="input-group">
        <input type="number" step="0.0001" class="form-control price-input"
               name="fixed_price[<?php echo html_escape($cur->name); ?>][]" value="" placeholder="0.00">
        <?php if($idxTpl === $curCountTpl): ?>
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger btn-remove-row" title="Remove">
              <i class="fa fa-times"></i>
            </button>
          </span>
        <?php endif; ?>
      </div>
    </div>
  <?php
      endforeach;
    endif;
  ?>
</div>
<?php
$ROW_TEMPLATE = ob_get_clean();
?>

<?php init_tail(); ?>

<script>
(function(){
  // Τρέχει όταν ΟΛΑ έχουν φορτώσει (jQuery, selectpicker, κλπ)
  window.addEventListener('load', function(){
    var $ = window.jQuery || null;

    var itemsMap = <?php echo isset($items_map_json) ? $items_map_json : '{}' ?>; // {itemId:{rate:float}}
    var currMap  = <?php echo isset($curr_map_json)  ? $curr_map_json  : '{}' ?>; // {CUR:{id,rate,is_base}}

    var container = document.getElementById('price-rows');
    var addBtn    = document.getElementById('btn-add-row');
    var ROW_HTML  = <?php echo json_encode($ROW_TEMPLATE); ?>;

    // Limit: max rows = number of system items (if 0 -> no limit)
    var MAX_ITEMS = <?php echo isset($items) ? (int)count($items) : 0; ?>;

    function initPickers(scope){
      if ($ && $.fn && $.fn.selectpicker) {
        $(scope).find('select.selectpicker').each(function(){
          try { $(this).selectpicker('destroy'); } catch(e){}
          $(this).selectpicker();
        });
        $(scope).find('select.selectpicker').selectpicker('render').selectpicker('refresh');
      }
    }

    function rowsCount(){
      return container ? container.querySelectorAll('.price-row').length : 0;
    }

    function canAddMore(){
      if (!MAX_ITEMS || MAX_ITEMS <= 0) return true;
      return rowsCount() < MAX_ITEMS;
    }

    function notifyLimit(){
      if (window.alert_float) {
        alert_float('warning', '<?php echo _l('warning') ?: 'Warning'; ?>: ' + 'Έφτασες το μέγιστο πλήθος σειρών (ίσο με τα διαθέσιμα items).');
      } else {
        alert('Έφτασες το μέγιστο πλήθος σειρών (ίσο με τα διαθέσιμα items).');
      }
    }

    function updateAddBtnState(){
      if (!addBtn) return;
      if (!canAddMore()) addBtn.setAttribute('disabled','disabled');
      else addBtn.removeAttribute('disabled');
    }

    function autofillRow(row){
      var sel = row.querySelector('select.item-select');
      var itemId = parseInt((sel && sel.value) || 0, 10);
      if (!itemId || !itemsMap[itemId]) return;
      var baseRate = parseFloat(itemsMap[itemId].rate || 0);

      row.querySelectorAll('input.price-input').forEach(function(inp){
        var name = inp.getAttribute('name') || '';
        var m = name.match(/fixed_price\[(.+)\]\[\]/);
        if (!m) return;
        var cur = m[1];
        var info = currMap[cur] || null;

        if (info && info.is_base) {
          inp.value = baseRate > 0 ? baseRate : '';
        } else if (info && typeof info.rate !== 'undefined' && baseRate > 0) {
          var v = baseRate * (parseFloat(info.rate) || 1);
          inp.value = v.toFixed(4);
        }
      });
    }

    // Init existing rows
    if (container) initPickers(container);

    // Add row (with limit)
    if (addBtn && container) {
      addBtn.addEventListener('click', function(e){
        e.preventDefault();
        if (!canAddMore()){
          notifyLimit();
          updateAddBtnState();
          return;
        }
        container.insertAdjacentHTML('beforeend', ROW_HTML);
        var rows = container.querySelectorAll('.price-row');
        var newRow = rows[rows.length - 1];
        initPickers(newRow);
        updateAddBtnState();
      });
    }

    // Change item -> autofill prices (with/without selectpicker)
    if (container) {
      container.addEventListener('change', function(e){
        if (e.target && e.target.matches('select.item-select')) {
          autofillRow(e.target.closest('.price-row'));
        }
      });
      if ($) {
        $(container).on('changed.bs.select', 'select.item-select', function(){
          autofillRow(this.closest('.price-row'));
        });
      }

      // Remove row (icon on last currency)
      container.addEventListener('click', function(e){
        var btn = e.target.closest('.btn-remove-row');
        if (!btn) return;
        e.preventDefault();
        var row = btn.closest('.price-row');
        if (!row) return;

        var count = rowsCount();
        if (count <= 1) {
          var sel = row.querySelector('select.item-select');
          if (sel) {
            sel.value = '';
            if ($ && $.fn && $.fn.selectpicker) $(sel).selectpicker('refresh');
          }
          row.querySelectorAll('input.price-input').forEach(function(i){ i.value=''; });
        } else {
          row.remove();
        }
        updateAddBtnState();
      });
    }

    // Initial state for add button
    updateAddBtnState();
  });
})();
</script>
