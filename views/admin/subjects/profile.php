<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
/**
 * Keep native/Core UI exactly as-is and only patch the Subject Details box content.
 * IMPORTANT: include APPPATH core view directly to avoid recursive module override loading.
 */
$coreProfileView = rtrim(APPPATH, '/\\') . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'subjects' . DIRECTORY_SEPARATOR . 'profile.php';
if (is_file($coreProfileView)) {
    include $coreProfileView;
} else {
    echo '<div class="alert alert-warning mtop15">Core profile view was not found at: ' . html_escape($coreProfileView) . '</div>';
}

$detailsTitle = isset($subject_details_card_title) ? (string)$subject_details_card_title : 'Subject Details';
$detailsRows  = isset($subject_details_rows) && is_array($subject_details_rows) ? $subject_details_rows : [];
$detailsType  = isset($subject_type_normalized) ? (string)$subject_type_normalized : '';
?>
<script>
(function($){
  "use strict";

  var detailsTitle = <?php echo json_encode($detailsTitle); ?>;
  var detailsRows  = <?php echo json_encode($detailsRows); ?>;
  var detailsType  = <?php echo json_encode($detailsType); ?>;

  function escHtml(str) {
    return $('<div/>').text((str || '').toString()).html();
  }

  function findDetailsPanel() {
    // Try semantic match by heading text first.
    var $panel = $('.panel_s').filter(function(){
      var t = $(this).find('.panel-heading, .panel-title').first().text().toLowerCase();
      return t.indexOf('subject details') !== -1
        || t.indexOf('details') !== -1
        || t.indexOf('στοιχεία') !== -1;
    }).first();

    // Fallback: first panel in left column (same UI, only content swap)
    if (!$panel.length) {
      $panel = $('.col-md-3 .panel_s, .col-md-4 .panel_s').first();
    }

    return $panel;
  }

  function buildRowsHtml() {
    var html = '';

    if (detailsType) {
      html += '<p class="text-muted mtop0"><strong>Type:</strong> ' + escHtml(detailsType) + '</p>';
    }

    if (!Array.isArray(detailsRows) || !detailsRows.length) {
      return html;
    }

    detailsRows.forEach(function(row){
      var label = row && row.label ? row.label.toString() : '';
      var value = row && row.value ? row.value.toString() : '';
      if (!value.trim()) return;
      html += '<p class="no-margin"><strong>' + escHtml(label) + ':</strong> ' + escHtml(value) + '</p>';
    });

    return html;
  }

  function patchDetailsPanel() {
    var $panel = findDetailsPanel();
    if (!$panel.length) return;

    var $heading = $panel.find('.panel-heading, .panel-title').first();
    var $body    = $panel.find('.panel-body').first();
    if (!$body.length) return;

    if ($heading.length && detailsTitle) {
      $heading.text(detailsTitle);
    }

    var html = buildRowsHtml();
    if (!html) return;
    $body.html(html);
  }

  $(function(){
    patchDetailsPanel();
  });
})(jQuery);
</script>
