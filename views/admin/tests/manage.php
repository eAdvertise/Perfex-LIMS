<?php defined('BASEPATH') or exit('No direct script access allowed'); 
// modules/lims/views/admin/tests/manage.php
?>

<?php
// Φέρνουμε τα Test Statuses από τον πίνακα tbllims_test_statuses
$CI =& get_instance();
$CI->load->model('lims/teststatuses_model', 'ts_model');
$test_statuses = $CI->ts_model->all();

// Χτίζουμε options για το render_select
$statusOptions = [];
$statusOptions[] = [
    'id'   => '',
    'name' => _l('dropdown_non_selected_tex') ?: '—',
];

if (!empty($test_statuses)) {
    foreach ($test_statuses as $ts) {
        // Μόνο active ή και inactive; Για την ώρα, βάζουμε όλα
        $statusOptions[] = [
            'id'   => $ts->code,          // αυτό θα στέλνουμε στο server
            'name' => $ts->name,          // φιλικό όνομα
        ];
    }
}
?>

<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">

            <h4 class="mbot15">
              <?php echo _l('lims_tests'); ?>
              <small class="text-muted">
                (<?php echo _l('lims_orders'); ?> / <?php echo _l('lims_samples'); ?> / <?php echo _l('lims_analyses'); ?>)
              </small>
            </h4>

            <!-- ΦΙΛΤΡΑ -->
            <div class="row mtop10">
              <div class="col-md-2">
                <?php
                // Status από lims_test_statuses
                echo render_select(
                    'status',
                    $statusOptions,
                    ['id','name'],
                    _l('status'),
                    '',
                    ['data-none-selected-text' => _l('dropdown_non_selected_tex')]
                );
                ?>
              </div>

              <div class="col-md-2">
                <?php echo render_select(
                    'department_id',
                    $departments ?? [],
                    ['id','name'],
                    _l('lims_department') ?: 'Department',
                    '',
                    ['data-none-selected-text'=>_l('dropdown_non_selected_tex')]
                ); ?>
              </div>

              <div class="col-md-3">
                <?php echo render_select(
                    'assigned_staff',
                    $staff ?? [],
                    ['staffid','full_name'],
                    _l('lims_assigned_staff') ?: 'Assigned staff',
                    '',
                    ['data-none-selected-text'=>_l('dropdown_non_selected_tex')]
                ); ?>
              </div>

              <div class="col-md-5">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="date_from"><?php echo _l('from_date'); ?></label>
                      <input type="text" class="form-control datepicker" id="date_from" name="date_from">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="date_to"><?php echo _l('to_date'); ?></label>
                      <input type="text" class="form-control datepicker" id="date_to" name="date_to">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr class="mbot15" />

            <?php
              $table_data = [
                _l('lims_order') . ' #',                   // 1
                _l('barcode') ?: 'Barcode',                // 2
                _l('lims_subject') ?: _l('client'),        // 3
                _l('lims_samples') ?: 'Samples',           // 4
                _l('lims_tests') ?: 'Tests',               // 5
                _l('lims_tests_open') ?: 'Open tests',     // 6
                _l('lims_first_received') ?: 'First rec.', // 7
                _l('lims_last_received')  ?: 'Last rec.',  // 8
                _l('status'),                              // 9
                _l('options'),                             // 10
              ];
              render_datatable($table_data, 'lims-tests');
            ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<script>
  (function($){
    "use strict";

    var serverParams = {
      status:         '[name="status"]',
      department_id:  '[name="department_id"]',
      assigned_staff: '[name="assigned_staff"]',
      date_from:      '[name="date_from"]',
      date_to:        '[name="date_to"]'
    };

    // initDataTable επιστρέφει DataTable instance
    var testsTable = initDataTable(
      '.table-lims-tests',
      admin_url + 'lims/tests',
      undefined,
      undefined,
      serverParams
    );

    // Όταν αλλάζουν τα selects
    $('select[name="status"], select[name="department_id"], select[name="assigned_staff"]').on('change', function(){
      if (testsTable && testsTable.ajax) {
        testsTable.ajax.reload();
      }
    });

    // Όταν αλλάζουν οι ημερομηνίες
    $('input[name="date_from"], input[name="date_to"]').on('change', function(){
      if (testsTable && testsTable.ajax) {
        testsTable.ajax.reload();
      }
    });

    // Datepicker init (Perfex helper)
    if (window.app && app.init_datepicker) {
      app.init_datepicker();
    }

  })(jQuery);
</script>
