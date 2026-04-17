<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">

        <h4 class="mtop5">
          <?php echo _l('lims_partner_form_title'); ?>
          <?php echo isset($row->id)?' #'.(int)$row->id:''; ?>
        </h4>

        <?php echo form_open(admin_url('lims/partners/create'.(isset($row->id)?'/'.$row->id:''))); ?>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo _l('name'); ?></label>
                <input type="text" class="form-control" name="name" required
                       value="<?php echo html_escape($row->name ?? ''); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_partner_name_desc'); ?></small>
              </div>
            </div>
            <div class="col-md-6">
              <label class="control-label mright10"><?php echo _l('active'); ?></label>
              <div class="onoffswitch">
                <input type="checkbox" name="active" class="onoffswitch-checkbox" id="p_active"
                  <?php echo (!isset($row) || (isset($row->active) && (int)$row->active===1)) ? 'checked' : ''; ?>>
                <label class="onoffswitch-label" for="p_active"></label>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Existing Customer link -->
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo _l('customer'); ?> (<?php echo _l('select_existing'); ?>)</label>
                <select name="customer_id" class="form-control selectpicker" data-live-search="true" data-size="10">
                  <option value=""><?php echo _l('dropdown_non_selected_tex') ?: 'Select'; ?></option>
                  <?php foreach($customers as $c): ?>
                    <option value="<?php echo (int)$c->userid; ?>"
                      <?php echo (isset($row->customer_id) && (int)$row->customer_id===(int)$c->userid)?'selected':''; ?>>
                      <?php echo html_escape($c->company).' (#'.(int)$c->userid.')'; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help-block text-muted"><?php echo _l('lims_partner_customer_select_desc'); ?></small>
              </div>
            </div>

            <!-- OR create new customer -->
            <div class="col-md-6">
              <div class="form-group">
                <label><?php echo _l('or_create_new_customer'); ?></label>
                <input type="text" class="form-control" name="new_customer_company" placeholder="<?php echo _l('company'); ?>">
                <small class="help-block text-muted"><?php echo _l('lims_partner_customer_new_desc'); ?></small>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Contact info -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('email'); ?></label>
                <input type="email" class="form-control" name="email"
                       value="<?php echo html_escape($row->email ?? ''); ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('phone'); ?></label>
                <input type="text" class="form-control" name="phone"
                       value="<?php echo html_escape($row->phone ?? ''); ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('website'); ?></label>
                <input type="text" class="form-control" name="website"
                       value="<?php echo html_escape($row->website ?? ''); ?>">
              </div>
            </div>
          </div>

          <div class="row">
                        <!-- Address + API -->
            <div class="col-md-4">
              <div class="form-group">
                <label><?php echo _l('address'); ?></label>
                <input type="text" class="form-control" name="address"
                       value="<?php echo html_escape($row->address ?? ''); ?>">
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>API Base URL</label>
                <input type="text" class="form-control" name="api_base_url"
                       placeholder="https://partner.example.com"
                       value="<?php echo html_escape($row->api_base_url ?? ''); ?>">
                <small class="help-block text-muted">Base URL of the partner Perfex instance (receiver).</small>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label>Sync Enabled</label>
                <div class="onoffswitch">
                  <input type="checkbox" name="sync_enabled" class="onoffswitch-checkbox" id="p_sync"
                    <?php echo (!isset($row) || (isset($row->sync_enabled) && (int)$row->sync_enabled===1)) ? 'checked' : ''; ?>>
                  <label class="onoffswitch-label" for="p_sync"></label>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>API Key</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="api_key"
                         id="api_key" value="<?php echo html_escape($row->api_key ?? ''); ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn-gen-key"><?php echo _l('generate'); ?></button>
                  </span>
                </div>
                <small class="help-block text-muted"><?php echo _l('lims_partner_api_desc'); ?></small>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>API Secret</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="api_secret"
                         id="api_secret" value="<?php echo html_escape($row->api_secret ?? ''); ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn-gen-secret"><?php echo _l('generate'); ?></button>
                  </span>
                </div>
                <small class="help-block text-muted">Used for HMAC signing (recommended).</small>
              </div>
            </div>
</div>
          </div>

          <div class="form-group">
            <label><?php echo _l('notes'); ?></label>
            <textarea class="form-control" name="notes" rows="3"><?php echo html_escape($row->notes ?? ''); ?></textarea>
          </div>

          <div class="text-right">
            <button class="btn btn-primary"><?php echo _l('lims_save'); ?></button>
            <a href="<?php echo admin_url('lims/partners'); ?>" class="btn btn-default"><?php echo _l('lims_cancel'); ?></a>
          </div>

        <?php echo form_close(); ?>

        <hr class="mtop20" />
        <div class="mtop20">
          <h5 class="mbot10"><?php echo _l('lims_partners_about_title'); ?></h5>
          <p class="text-muted"><?php echo _l('lims_partners_about_desc'); ?></p>

          <h6 class="mtop15"><?php echo _l('lims_partners_purpose_title'); ?></h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_partners_purpose_1'); ?></li>
            <li>• <?php echo _l('lims_partners_purpose_2'); ?></li>
            <li>• <?php echo _l('lims_partners_purpose_3'); ?></li>
          </ul>

          <h6 class="mtop15"><?php echo _l('lims_partners_guidelines_title'); ?></h6>
          <ul class="list-unstyled text-muted">
            <li>• <?php echo _l('lims_partners_guideline_1'); ?></li>
            <li>• <?php echo _l('lims_partners_guideline_2'); ?></li>
            <li>• <?php echo _l('lims_partners_guideline_3'); ?></li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function($){
  $(function(){
    if ($.fn.selectpicker) { $('.selectpicker').selectpicker('render').selectpicker('refresh'); }
    $('#btn-gen-key').on('click', function(){
      $('#api_key').val(genToken(40));
    });
    $('#btn-gen-secret').on('click', function(){
      $('#api_secret').val(genToken(64));
    });

    function genToken(len){
      // Prefer Web Crypto if available
      if (window.crypto && window.crypto.getRandomValues) {
        var bytes = new Uint8Array(len);
        window.crypto.getRandomValues(bytes);
        var out = '';
        for (var i=0;i<bytes.length;i++){
          out += (bytes[i] % 16).toString(16);
        }
        return out.substr(0, len);
      }
      var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      var token = '';
      for (var i=0;i<len;i++){ token += chars.charAt(Math.floor(Math.random()*chars.length)); }
      return token;
    }
  });
})(jQuery);
</script>
