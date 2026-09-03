<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();

// Hidden fields ώστε το core settings save να κρατάει τις τιμές και να μην τις μηδενίζει
echo form_hidden('settings[lims_report_background_image]', get_option('lims_report_background_image'));
echo form_hidden('settings[lims_report_footer_image]', get_option('lims_report_footer_image'));
echo form_hidden('settings[lims_report_logo]', get_option('lims_report_logo'));

// Asset folders (standard)
$logoFolderRel   = 'uploads/lims/report/logo/';
$bgFolderRel     = 'uploads/lims/report/background/';
$footerFolderRel = 'uploads/lims/report/footer/';

$currLogo     = (string)get_option('lims_report_logo');
$currBg       = (string)get_option('lims_report_background_image');
$currFooter   = (string)get_option('lims_report_footer_image');

$currLogoUrl   = $currLogo   ? base_url($logoFolderRel . ltrim($currLogo, '/')) : '';
$currBgUrl     = $currBg     ? base_url($bgFolderRel . ltrim($currBg, '/')) : '';
$currFooterUrl = $currFooter ? base_url($footerFolderRel . ltrim($currFooter, '/')) : '';
?>

<div class="row">
	<div class="col-md-12">
  <!-- LEFT COLUMN: texts + typography -->
  
        <h4 class="no-margin"><?php echo _l('lims_report_pdf_texts') ?: 'Texts'; ?></h4>
        <hr class="hr-panel-heading" />

        <div class="row">
          <div class="col-md-6">
            <?php echo render_input(
              'settings[lims_report_header_subtitle_el]',
              _l('lims_report_header_subtitle_el') ?: 'Subheader (Greek)',
              get_option('lims_report_header_subtitle_el')
            ); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_input(
              'settings[lims_report_header_subtitle_en]',
              _l('lims_report_header_subtitle_en') ?: 'Subheader (English)',
              get_option('lims_report_header_subtitle_en')
            ); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <?php echo render_input(
              'settings[lims_report_heading_el]',
              _l('lims_report_heading_el') ?: 'Heading (Greek)',
              get_option('lims_report_heading_el') ?: 'ΕΚΘΕΣΗ ΕΡΓΑΣΤΗΡΙΟΥ'
            ); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_input(
              'settings[lims_report_heading_en]',
              _l('lims_report_heading_en') ?: 'Heading (English)',
              get_option('lims_report_heading_en') ?: 'LABORATORY REPORT'
            ); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <?php echo render_input(
              'settings[lims_report_topright_line1]',
              _l('lims_report_topright_line1') ?: 'Top-right line 1',
              get_option('lims_report_topright_line1') ?: 'ΕΣΔ-15/1'
            ); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_input(
              'settings[lims_report_topright_line2]',
              _l('lims_report_topright_line2') ?: 'Top-right line 2',
              get_option('lims_report_topright_line2') ?: 'ΑΝΑΘ. 00'
            ); ?>
          </div>
        </div>

        <hr />

        <?php echo render_select(
          'settings[lims_report_font_family]',
          [
            ['id' => 'dejavuserif', 'name' => 'dejavuserif (Greek-safe, serif)'],
            ['id' => 'dejavusans',  'name' => 'dejavusans (Greek-safe, sans)'],
          ],
          ['id', 'name'],
          _l('lims_report_font_family') ?: 'Font family',
          get_option('lims_report_font_family') ?: 'dejavuserif'
        ); ?>

        <?php echo render_input(
          'settings[lims_report_font_size]',
          _l('lims_report_font_size') ?: 'Font size',
          get_option('lims_report_font_size') ?: '10',
          'number',
          ['step' => '0.5', 'min' => '7', 'max' => '14']
        ); ?>

        <?php echo render_yes_no_option('lims_report_show_signature', _l('lims_report_show_signature') ?: 'Show signature'); ?>

        <?php echo render_input(
          'settings[lims_report_signature_width_mm]',
          _l('lims_report_signature_width_mm') ?: 'Signature width (mm)',
          get_option('lims_report_signature_width_mm') ?: '42',
          'number',
          ['step' => '1', 'min' => '20', 'max' => '80']
        ); ?>

        <?php echo render_yes_no_option('lims_report_language_from_subject', _l('lims_report_language_from_subject') ?: 'Language from Subject'); ?>

        <?php echo render_select(
          'settings[lims_report_default_language]',
          [
            ['id' => 'greek',   'name' => 'Greek'],
            ['id' => 'english', 'name' => 'English'],
          ],
          ['id', 'name'],
          _l('lims_report_default_language') ?: 'Default language',
          get_option('lims_report_default_language') ?: 'greek'
        ); ?>

  <!-- RIGHT COLUMN: images + positioning + footer texts -->
        <hr class="hr-panel-heading" />
        <h4 class="no-margin"><?php echo _l('lims_report_pdf_images') ?: 'Images'; ?></h4>
        <hr class="hr-panel-heading" />

        <!-- Custom Report Logo -->
        <div class="form-group">
          <label for="lims_report_logo_file"><?php echo _l('lims_report_pdf_logo') ?: 'Custom Report Logo'; ?></label>
          <input type="file"
                 id="lims_report_logo_file"
                 name="lims_report_logo_file"
                 class="form-control"
                 accept=".png,.jpg,.jpeg" />
          <p class="text-muted"><?php echo _l('lims_report_pdf_logo_help') ?: 'If set, this logo will be used in the Report PDF.'; ?></p>

          <?php if (!empty($currLogo)) : ?>
            <p class="text-muted">
              <strong><?php echo _l('lims_report_pdf_logo_current') ?: 'Current custom logo:'; ?></strong><br>
              <a href="<?php echo $currLogoUrl; ?>" target="_blank"><?php echo html_escape($currLogo); ?></a><br>
              <span><?php echo 'Folder: ' . html_escape($logoFolderRel); ?></span>
            </p>

            <div class="checkbox checkbox-primary mtop10">
              <input type="checkbox" id="lims_report_logo_remove" name="lims_report_logo_remove" value="1">
              <label for="lims_report_logo_remove"><?php echo _l('lims_report_pdf_logo_remove') ?: 'Remove custom logo'; ?></label>
            </div>
          <?php else: ?>
            <p class="text-muted"><?php echo 'Folder: ' . html_escape($logoFolderRel); ?></p>
          <?php endif; ?>
        </div>

        <div class="row">
          <div class="col-md-4">
            <?php echo render_input(
              'settings[lims_report_logo_width]',
              _l('lims_report_pdf_logo_width') ?: 'Logo width (mm)',
              get_option('lims_report_logo_width') ?: '90',
              'number',
              ['min' => '10', 'step' => '1']
            ); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_input(
              'settings[lims_report_logo_x]',
              _l('lims_report_pdf_logo_x') ?: 'Logo X (mm) (empty = auto-center)',
              get_option('lims_report_logo_x') ?: '',
              'number',
              ['min' => '0', 'step' => '1', 'placeholder' => 'auto-center']
            ); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_input(
              'settings[lims_report_logo_y]',
              _l('lims_report_pdf_logo_y') ?: 'Logo Y (mm)',
              get_option('lims_report_logo_y') ?: '8',
              'number',
              ['min' => '0', 'step' => '1']
            ); ?>
          </div>
        </div>

        <hr />

        <!-- Background -->
        <div class="form-group">
          <label for="lims_report_background_image_file"><?php echo _l('lims_report_pdf_background_image') ?: 'Background image (A4)'; ?></label>
          <input type="file"
                 id="lims_report_background_image_file"
                 name="lims_report_background_image_file"
                 class="form-control"
                 accept=".png,.jpg,.jpeg" />
          <p class="text-muted"><?php echo _l('lims_report_pdf_background_image_help') ?: 'Used as full-page background.'; ?></p>

          <?php if (!empty($currBg)) : ?>
            <p class="text-muted">
              <strong><?php echo _l('lims_report_pdf_background_image_current') ?: 'Current background:'; ?></strong><br>
              <a href="<?php echo $currBgUrl; ?>" target="_blank"><?php echo html_escape($currBg); ?></a><br>
              <span><?php echo 'Folder: ' . html_escape($bgFolderRel); ?></span>
            </p>

            <div class="checkbox checkbox-primary mtop10">
              <input type="checkbox" id="lims_report_background_image_remove" name="lims_report_background_image_remove" value="1">
              <label for="lims_report_background_image_remove"><?php echo _l('lims_report_pdf_background_image_remove') ?: 'Remove background image'; ?></label>
            </div>
          <?php else: ?>
            <p class="text-muted"><?php echo 'Folder: ' . html_escape($bgFolderRel); ?></p>
          <?php endif; ?>
        </div>

        <hr />

        <!-- Footer image -->
        <h4 class="mbot15"><?php echo _l('lims_report_pdf_footer_image_section') ?: 'Footer image positioning'; ?></h4>

        <div class="form-group">
          <label for="lims_report_footer_image_file"><?php echo _l('lims_report_pdf_footer_image') ?: 'Footer image (upload)'; ?></label>
          <input type="file"
                 id="lims_report_footer_image_file"
                 name="lims_report_footer_image_file"
                 class="form-control"
                 accept=".png,.jpg,.jpeg" />
          <p class="text-muted"><?php echo _l('lims_report_pdf_footer_image_help') ?: 'This image is placed at the bottom of each page.'; ?></p>

          <?php if (!empty($currFooter)) : ?>
            <p class="text-muted">
              <strong><?php echo _l('lims_report_pdf_footer_image_current') ?: 'Current footer image:'; ?></strong><br>
              <a href="<?php echo $currFooterUrl; ?>" target="_blank"><?php echo html_escape($currFooter); ?></a><br>
              <span><?php echo 'Folder: ' . html_escape($footerFolderRel); ?></span>
            </p>

            <div class="checkbox checkbox-primary mtop10">
              <input type="checkbox" id="lims_report_footer_image_remove" name="lims_report_footer_image_remove" value="1">
              <label for="lims_report_footer_image_remove"><?php echo _l('lims_report_pdf_footer_image_remove') ?: 'Remove footer image'; ?></label>
            </div>
          <?php else: ?>
            <p class="text-muted"><?php echo 'Folder: ' . html_escape($footerFolderRel); ?></p>
          <?php endif; ?>
        </div>

        <div class="row">
          <div class="col-md-4">
            <?php echo render_input(
              'settings[lims_report_footer_img_x]',
              _l('lims_report_footer_img_x') ?: 'Footer image X (mm)',
              get_option('lims_report_footer_img_x') !== '' ? get_option('lims_report_footer_img_x') : '10',
              'number',
              ['min' => '0', 'step' => '1']
            ); ?>
          </div>

          <div class="col-md-4">
            <?php echo render_input(
              'settings[lims_report_footer_img_y]',
              _l('lims_report_footer_img_y') ?: 'Footer image Y (mm)',
              get_option('lims_report_footer_img_y') !== '' ? get_option('lims_report_footer_img_y') : '282',
              'number',
              ['min' => '0', 'step' => '1']
            ); ?>
          </div>

          <div class="col-md-4">
            <?php echo render_input(
              'settings[lims_report_footer_img_w]',
              _l('lims_report_footer_img_w') ?: 'Footer image width (mm)',
              get_option('lims_report_footer_img_w') !== '' ? get_option('lims_report_footer_img_w') : '190',
              'number',
              ['min' => '10', 'step' => '1']
            ); ?>
          </div>
        </div>

        <p class="text-muted">
          <?php echo _l('lims_report_pdf_footer_image_section_help') ?: 'Coordinates in mm for A4 (210x297). Adjust to align with your background.'; ?>
        </p>

        <hr class="hr-panel-heading" />
        <h4 class="no-margin"><?php echo _l('lims_report_pdf_footer') ?: 'Footer'; ?></h4>
        <p class="text-muted">
          <?php echo _l('lims_report_pdf_footer_help') ?: 'Configure footer texts per language.'; ?>
        </p>
        <hr class="hr-panel-heading" />

        <div class="row">
          <div class="col-md-12">
            <label for="lims_report_footer_lang_ui"><?php echo _l('language') ?: 'Language'; ?></label>
            <select id="lims_report_footer_lang_ui" class="selectpicker" data-width="100%">
              <option value="greek">Ελληνικά</option>
              <option value="english">English</option>
            </select>
          </div>
        </div>

        <div class="row mtop15" data-lims-footer-lang="greek">
          <div class="col-md-12">
            <?php echo render_textarea(
              'settings[lims_report_pre_footer_note_greek]',
              _l('lims_report_pre_footer_note_greek') ?: 'Note before footer (Ελληνικά)',
              (string)get_option('lims_report_pre_footer_note_greek'),
              ['rows' => 4]
            ); ?>
          </div>

          <div class="col-md-12">
            <?php echo render_textarea(
              'settings[lims_report_footer_text_greek]',
              _l('lims_report_footer_text_greek') ?: 'Footer text (Ελληνικά)',
              (string)get_option('lims_report_footer_text_greek'),
              ['rows' => 4]
            ); ?>
          </div>
        </div>

        <div class="row mtop15" data-lims-footer-lang="english" style="display:none;">
          <div class="col-md-12">
            <?php echo render_textarea(
              'settings[lims_report_pre_footer_note_english]',
              _l('lims_report_pre_footer_note_english') ?: 'Note before footer (English)',
              (string)get_option('lims_report_pre_footer_note_english'),
              ['rows' => 4]
            ); ?>
          </div>

          <div class="col-md-12">
            <?php echo render_textarea(
              'settings[lims_report_footer_text_english]',
              _l('lims_report_footer_text_english') ?: 'Footer text (English)',
              (string)get_option('lims_report_footer_text_english'),
              ['rows' => 4]
            ); ?>
          </div>
        </div>

		
		 <?php echo render_textarea('settings[lims_report_footer_text]', 'Footer text', get_option('lims_report_footer_text')); ?>

		<hr class="hr-panel-heading" />
		<h4 class="no-margin"><?php echo _l('lims_report_pdf_footer_section'); ?></h4>
		<p class="text-muted"><?php echo _l('lims_report_pdf_footer_section_help'); ?></p>

		<div class="row">
		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_gap_mm]',
			  _l('lims_report_footer_gap_mm'),
			  get_option('lims_report_footer_gap_mm') ?: 20,
			  'number',
			  ['step'=>'0.1','min'=>'0']
			); ?>
			<p class="text-muted"><?php echo _l('lims_report_footer_gap_mm_help'); ?></p>
		  </div>

		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_bottom_margin_mm]',
			  _l('lims_report_footer_bottom_margin_mm'),
			  get_option('lims_report_footer_bottom_margin_mm') ?: 10,
			  'number',
			  ['step'=>'0.1','min'=>'0']
			); ?>
			<p class="text-muted"><?php echo _l('lims_report_footer_bottom_margin_mm_help'); ?></p>
		  </div>

		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_line_thickness_mm]',
			  _l('lims_report_footer_line_thickness_mm'),
			  get_option('lims_report_footer_line_thickness_mm') ?: 0.4,
			  'number',
			  ['step'=>'0.1','min'=>'0']
			); ?>
			<p class="text-muted"><?php echo _l('lims_report_footer_line_thickness_mm_help'); ?></p>
		  </div>
		</div>

		<div class="row">
		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_line_color]',
			  _l('lims_report_footer_line_color'),
			  get_option('lims_report_footer_line_color') ?: '#009600',
			  'text',
			  ['placeholder'=>'#009600 or 0,150,0']
			); ?>
			<p class="text-muted"><?php echo _l('lims_report_footer_line_color_help'); ?></p>
		  </div>

		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_line_x1_mm]',
			  _l('lims_report_footer_line_x1_mm'),
			  get_option('lims_report_footer_line_x1_mm') ?: 20,
			  'number',
			  ['step'=>'0.1','min'=>'0']
			); ?>
		  </div>

		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_line_x2_mm]',
			  _l('lims_report_footer_line_x2_mm'),
			  get_option('lims_report_footer_line_x2_mm') ?: 190,
			  'number',
			  ['step'=>'0.1','min'=>'0']
			); ?>
		  </div>
		</div>

		<div class="row">
		  <div class="col-md-4">
			<?php echo render_input(
			  'settings[lims_report_footer_line_offset_mm]',
			  _l('lims_report_footer_line_offset_mm'),
			  get_option('lims_report_footer_line_offset_mm') ?: 2,
			  'number',
			  ['step'=>'0.1','min'=>'0']
			); ?>
			<p class="text-muted"><?php echo _l('lims_report_footer_line_offset_mm_help'); ?></p>
		  </div>
		</div>
	</div>
</div>

