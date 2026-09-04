<?php defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: LIMS Module
Description: Laboratory Information Management System
Version: 3.0.9
Requires at least: 3.4.*
Author: eAdvertise
*/

define('LIMS_MODULE_VERSION', '3.0.9');
define('LIMS_MODULE_NAME', 'lims');

register_activation_hook(LIMS_MODULE_NAME, 'lims_module_activation_hook');
register_language_files(LIMS_MODULE_NAME, [LIMS_MODULE_NAME]);
hooks()->add_action('update_module_' . LIMS_MODULE_NAME, 'lims_module_update');
if (function_exists('register_uninstall_hook')) {
    register_uninstall_hook(LIMS_MODULE_NAME, 'lims_module_uninstall_hook');
}
/**
 * Ενεργοποίηση module
 */
function lims_module_activation_hook()
{
    $CI = &get_instance();

    // Προσπαθούμε να φορτώσουμε το install.php, αλλά ΔΕΝ θεωρούμε δεδομένο
    // ότι ορίζει lims_install().
    $installPath = __DIR__ . '/install.php';
    if (is_file($installPath)) {
        require_once($installPath);
    }

    // Εκτέλεση install (fresh installs) - υποστηρίζουμε και function και class-based installer.
    if (function_exists('lims_install')) {
        lims_install();
    } elseif (class_exists('Lims_install')) {
        try {
            (new Lims_install())->install();
        } catch (Throwable $e) {
            // best-effort; migrations below may still run
        }
    }

    // Προσπαθούμε να τρέξουμε migrations με ασφαλή wrapper
    lims_run_migrations();

    // Αποθήκευση έκδοσης module
    if (!get_option('lims_module_version')) {
        add_option('lims_module_version', LIMS_MODULE_VERSION);
    } else {
        update_option('lims_module_version', LIMS_MODULE_VERSION);
    }
	
	$defaults = [
		'lims_report_font_family' => 'dejavuserif',
		'lims_report_font_size' => '10',
		'lims_report_background_image' => '',
		'lims_report_footer_image' => '',
		'lims_report_footer_text' => '',
		'lims_report_show_signature' => '1',
		'lims_report_signature_width_mm' => '42',
		'lims_report_language_from_subject' => '1',
		'lims_report_default_language' => 'greek',
		'lims_report_header_subtitle_el' => '', 
		'lims_report_header_subtitle_en' => '',
		'lims_report_heading_el' => '', 
		'lims_report_heading_en' => '',
		'lims_report_topright_line1' => '', 
		'lims_report_topright_line2' => '',
		'lims_report_footer_img_y' => '',
		'lims_report_footer_img_w' => '',
		'lims_report_logo_x' => '',
		'lims_report_pdf_logo_y' => '',
        'lims_report_footer_gap_mm'            => 20,
        'lims_report_footer_bottom_margin_mm'  => 10,
        'lims_report_footer_line_thickness_mm' => 0.4,
        'lims_report_footer_line_x1_mm'        => 20,
        'lims_report_footer_line_x2_mm'        => 190,
        'lims_report_footer_line_offset_mm'    => 2,
	];

	foreach ($defaults as $k => $v) {
	  if (get_option($k) === '') {
		add_option($k, $v);
	  }
	}
	$keys = [
	  'lims_report_footer_text_greek',
	  'lims_report_footer_text_english',
	  'lims_report_pre_footer_note_greek',
	  'lims_report_pre_footer_note_english',
	];

	foreach ($keys as $k) {
		if (get_option($k) === null) {
			add_option($k, '');
		}
	}
		// color (hex "#RRGGBB" or "r,g,b")
		// Set a safe default on activation if the option is missing.
		if (get_option("lims_report_footer_line_color") === null) {
			add_option("lims_report_footer_line_color", "#009600");
		}




}

/**
 * Update module hook
 */
function lims_module_update($module)
{
    $CI = &get_instance();

    $installPath = __DIR__ . '/install.php';
    if (is_file($installPath)) {
        require_once($installPath);
    }

    // Σε update συνήθως δεν χρειάζεται ξανά lims_install(), αλλά δεν πειράζει
    // αν υπάρχει και θέλεις να διασφαλίσεις schema.
    if (function_exists('lims_update')) {
        // αν έχεις βοηθητική lims_update()
        lims_update();
    }

    lims_run_migrations();
    update_option('lims_module_version', LIMS_MODULE_VERSION);
}

/**
 * Ασφαλής κλήση module migrations για διάφορες εκδόσεις Perfex
 */
function lims_run_migrations()
{
    static $hasRun = false;

    // Activation/update hooks can be triggered in the same request. Loading a
    // migration class twice causes a fatal "class name is already in use" error.
    if ($hasRun) {
        return;
    }

    // Perfex can load the pending migration before it invokes the module update
    // hook. In that case this wrapper must not start a second migration pass.
    // The static flag above only protects calls made through this function, so
    // also check for migration classes that were loaded by Perfex itself.
    foreach (glob(__DIR__ . '/migrations/*_version_*.php') ?: [] as $migrationFile) {
        if (preg_match('/^[0-9]+_version_([0-9]+)\.php$/', basename($migrationFile), $matches)
            && class_exists('Migration_Version_' . $matches[1], false)) {
            $hasRun = true;
            return;
        }
    }

    $CI = &get_instance();

    if (!class_exists('App_module_migration')) {
        // Καμία βιβλιοθήκη migrations - δεν κάνουμε τίποτα
        return;
    }

    $CI->load->library('app_module_migration');

    $migrator = $CI->app_module_migration;
    $hasRun = true;

    // Configure older migration APIs before invoking them.
    if (method_exists($migrator, 'set_module')) {
        $migrator->set_module(LIMS_MODULE_NAME);
    }
    if (method_exists($migrator, 'set_path')) {
        $migrator->set_path(__DIR__ . '/migrations/');
    }

    if (method_exists($migrator, 'migrate')) {
        $method = new ReflectionMethod($migrator, 'migrate');
        if ($method->getNumberOfParameters() >= 1) {
            $migrator->migrate(LIMS_MODULE_NAME);
        } else {
            $migrator->migrate();
        }
        return;
    }

    if (method_exists($migrator, 'run')) {
        $migrator->run();
    }
}

// ======================================================
// ΓΛΩΣΣΑ
// ======================================================

hooks()->add_action('app_init', 'lims_register_language_files');
function lims_register_language_files()
{
    register_language_files('lims', ['lims']);
}


// ======================================================
// PERFEX DASHBOARD WIDGETS
// ======================================================

hooks()->add_filter('get_dashboard_widgets', 'lims_register_dashboard_widgets');
function lims_register_dashboard_widgets($widgets)
{
    if (!has_permission('lims', '', 'view')) {
        return $widgets;
    }

    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/overview',
        'container' => 'top-12',
    ];
    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/attention_orders',
        'container' => 'left-8',
    ];

    if (has_permission('lims', '', 'enter_results') || has_permission('lims', '', 'admin')) {
        $widgets[] = [
            'path'      => 'lims/admin/dashboard/widgets/ready_to_sign',
            'container' => 'right-4',
        ];
    }

    if (has_permission('lims', '', 'appointments') || has_permission('lims', '', 'admin')) {
        $widgets[] = [
            'path'      => 'lims/admin/dashboard/widgets/todays_appointments',
            'container' => 'left-8',
        ];
    }

    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/orders_by_status',
        'container' => 'right-4',
    ];
    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/recent_activity',
        'container' => 'left-8',
    ];
    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/tests_by_department',
        'container' => 'right-4',
    ];
    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/turnaround_time',
        'container' => 'right-4',
    ];
    $widgets[] = [
        'path'      => 'lims/admin/dashboard/widgets/activity_trend',
        'container' => 'left-8',
    ];

    return $widgets;
}

// ======================================================
// MAIN MENU
// ======================================================

hooks()->add_action('admin_init', 'lims_module_init_menu');
function lims_module_init_menu()
{
    if (!has_permission('lims', '', 'view')) {
        return;
    }

    $CI = &get_instance();
    $CI->app_menu->add_sidebar_menu_item('lims', [
        'name'     => _l('lims_menu'),
        'icon'     => 'fa fa-vial',
        'href'     => admin_url('lims'),
        'position' => 20,
        'collapse' => true,
    ]);

    $CI->app_menu->add_sidebar_children_item('lims', [
        'slug'     => 'lims-subjects',
        'name'     => _l('lims_subjects') ?: 'Patients / Subjects',
        'href'     => admin_url('lims/subjects'),
        'position' => 10,
    ]);

    $CI->app_menu->add_sidebar_children_item('lims', [
        'slug'     => 'lims-orders',
        'name'     => _l('lims_orders'),
        'href'     => admin_url('lims/orders'),
        'position' => 20,
    ]);

    $CI->app_menu->add_sidebar_children_item('lims', [
        'slug'     => 'lims-contracts',
        'name'     => _l('lims_contracts'),
        'href'     => admin_url('lims/contracts'),
        'position' => 30,
    ]);

    $CI->app_menu->add_sidebar_children_item('lims', [
        'slug'     => 'lims-appointments',
        'name'     => _l('lims_appointments'),
        'href'     => admin_url('lims/appointments'),
        'position' => 40,
    ]);

    $CI->app_menu->add_sidebar_children_item('lims', [
        'slug'     => 'lims-tests-queue',
        'name'     => _l('lims_tests'),
        'href'     => admin_url('lims/tests'),
        'position' => 50,
    ]);

    $CI->app_menu->add_sidebar_children_item('lims', [
        'slug'     => 'lims-samples',
        'name'     => _l('lims_samples'),
        'href'     => admin_url('lims/samples'),
        'position' => 60,
    ]);
}

// ======================================================
// PERMISSIONS (σωστή δομή για staff/permissions.php)
// ======================================================

hooks()->add_filter('staff_permissions', 'lims_register_permissions');
function lims_register_permissions($permissions)
{
    // Όνομα για την ομάδα δικαιωμάτων
    $permissions['lims'] = [
        'name' => _l('lims_permissions_group_name') ?: 'LIMS',

        // κλειδί => label (ό,τι θες να φαίνεται στη λίστα)
        'capabilities' => [
            'view'            => _l('permission_view') ?: 'View',
            'manage_orders'   => _l('lims_permission_manage_orders') ?: 'Manage Orders',
            'manage_samples'  => _l('lims_permission_manage_samples') ?: 'Manage Samples',
            'enter_results'   => _l('lims_permission_enter_results') ?: 'Enter Results',
            'verify'          => _l('lims_permission_verify') ?: 'Verify Results',
            'approve'         => _l('lims_permission_approve') ?: 'Approve / Sign',
            'qc_manage'       => _l('lims_permission_qc_manage') ?: 'Quality Control',
            'billing'         => _l('lims_permission_billing') ?: 'Billing',
            'appointments'    => _l('lims_permission_appointments') ?: 'Appointments',
            'admin'           => _l('lims_permission_admin') ?: 'LIMS Admin',
        ],
    ];

    return $permissions;
}

// ======================================================
// Customer profile tab (Contracts)
// ======================================================

hooks()->add_filter('customer_profile_tabs', 'lims_customer_profile_tabs');
function lims_customer_profile_tabs($tabs)
{
    $tabs[] = [
        'slug'     => 'lims-subjects',
        'name'     => _l('lims_subjects') ?: 'Subjects',
        'icon'     => 'fa fa-user-injured',
        'position' => 9,
        'view'     => 'lims/admin/subjects/client_tab',
    ];

    $tabs[] = [
        'slug'     => 'lims-contracts',
        'name'     => _l('lims_contracts'),
        'icon'     => 'fa fa-vial',
        'position' => 10,
        'view'     => 'lims/admin/contracts/client_tab',
    ];
    return $tabs;
}

function lims_render_customer_contracts_tab($client_id)
{
    $CI = &get_instance();
    if (!has_permission('lims', '', 'billing')) {
        echo '<div class="alert alert-warning">' . _l('access_denied') . '</div>';
        return;
    }
    $CI->load->model('lims/Lims_contracts_model', 'lims_contracts_model');
    $data['client_id'] = (int) $client_id;
    $data['contracts'] = $CI->lims_contracts_model->all($data['client_id']);
    $CI->load->view('lims/admin/contracts/client_tab', $data);
}

// ======================================================
// Setup menu (Sample Types, Analyses, Panels, Departments, Cultures κλπ.)
// ======================================================

hooks()->add_action('admin_init', 'lims_register_setup_menu');
function lims_register_setup_menu()
{
    $CI = &get_instance();

    // Μόνο όποιος έχει LIMS admin
    if (!has_permission('lims', '', 'admin')) {
        return;
    }

    $CI->app_menu->add_setup_menu_item('lims-setup', [
        'name'     => _l('lims_setup_menu'),
        'position' => 1,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-setup-sampletypes',
        'name'     => _l('lims_sample_types'),
        'href'     => admin_url('lims/sampletypes'),
        'position' => 10,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-setup-analyses',
        'name'     => _l('lims_analyses'),
        'href'     => admin_url('lims/analyses'),
        'position' => 20,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-panels',
        'name'     => _l('lims_panels'),
        'href'     => admin_url('lims/panels'),
        'position' => 30,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-departments',
        'name'     => _l('lims_departments'),
        'href'     => admin_url('lims/departments'),
        'position' => 40,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-culturetypes',
        'name'     => _l('lims_culture_types') ?: 'Culture Types',
        'href'     => admin_url('lims/culturetypes'),
        'position' => 50,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-cultures',
        'name'     => _l('lims_cultures') ?: 'Cultures',
        'href'     => admin_url('lims/cultures'),
        'position' => 60,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-culture-options',
        'name'     => _l('lims_culture_options'),
        'href'     => admin_url('lims/culture_options'),
        'position' => 70,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-partners',
        'name'     => _l('lims_partners'),
        'href'     => admin_url('lims/partners'),
        'position' => 80,
    ]);

    $CI->app_menu->add_setup_children_item('lims-setup', [
        'slug'     => 'lims-test-statuse',
        'name'     => _l('lims_test_statuses'),
        'href'     => admin_url('lims/teststatuses'),
        'position' => 90,
    ]);
	$CI->app_menu->add_setup_children_item('lims-setup', [
		'slug'     => 'lims-report-notes',
		'name'     => _l('lims_report_notes') ?: 'Report Notes',
		'href'     => admin_url('lims/report_notes'),
		'position' => 95,
	]);

}

// ======================================================
// Settings → LIMS main tab + sub-tabs (Report, Test Statuses, Labels κ.ά.)
// ======================================================

hooks()->add_action('admin_init', function () {
    $CI = &get_instance();

    if (!defined('LIMS_MODULE')) {
        define('LIMS_MODULE', 'lims');
    }

    $settings_tab = [
        'slug'     => 'lims',
        'title'    => _l('lims_settings') ?: 'LIMS',
        'name'     => _l('lims_settings') ?: 'LIMS',
        'icon'     => 'fa fa-vial',
        'view'     => LIMS_MODULE . '/admin/settings',
        'position' => 3,
    ];

    if (method_exists($CI->app, 'add_settings_section_child')) {
        if (method_exists($CI->app, 'add_settings_section')) {
            $CI->app->add_settings_section('lims', [
                'title'    => _l('lims_setup_menu') ?: 'LIMS',
                'name'     => _l('lims_setup_menu') ?: 'LIMS',
                'icon'     => 'fa fa-vials',
                'position' => 3,
            ]);
        }
        $CI->app->add_settings_section_child('lims', LIMS_MODULE, $settings_tab);
    } else {
        if (isset($CI->app_tabs) && method_exists($CI->app_tabs, 'add_settings_tab')) {
            $CI->app_tabs->add_settings_tab(LIMS_MODULE, $settings_tab);
        }
    }
}, PHP_INT_MAX);


// Labels tab
hooks()->add_action('admin_init', function () {
    $CI = &get_instance();

    $tab = [
        'name'     => _l('lims_settings_labels') ?: 'Labels',
        'view'     => 'lims/admin/settings/labels',
        'position' => 40,
        'icon'     => 'fa fa-barcode',
    ];

    if (method_exists($CI->app, 'add_settings_section_child')) {
        $CI->app->add_settings_section_child('lims', 'lims-labels', $tab);
    } else {
        if (isset($CI->app_tabs) && method_exists($CI->app_tabs, 'add_settings_tab')) {
            $CI->app_tabs->add_settings_tab('lims-labels', array_merge($tab, [
                'slug'  => 'lims-labels',
                'title' => _l('lims_settings_labels') ?: 'Labels',
            ]));
        }
    }
}, PHP_INT_MAX);

// ======================================================
// Hooks για Items / Invoices / Cultures (όπως τα είχες)
// ======================================================

hooks()->add_action('after_item_updated', function ($payload) {
    $CI =& get_instance();
    if (empty($payload['id'])) {
        return;
    }
    $itemId = (int) $payload['id'];

    $item = $CI->db->select('i.id,i.description,i.rate,i.tax,i.tax2,i.unit,i.long_description,i.group_id,g.name as group_name')
        ->from(db_prefix() . 'items AS i')
        ->join(db_prefix() . 'items_groups AS g', 'g.id=i.group_id', 'left')
        ->where('i.id', $itemId)->get()->row();
    if (!$item) {
        return;
    }

    $an = $CI->db->where('item_id', $itemId)->get(db_prefix() . 'lims_analyses')->row();
    if (!$an) {
        return;
    }

    $isCleared = (
        (is_null($item->rate) || (float) $item->rate == 0.0) &&
        (empty($item->tax) || (int) $item->tax == 0) &&
        (empty($item->tax2) || (int) $item->tax2 == 0) &&
        (is_null($item->unit) || trim((string) $item->unit) === '') &&
        (is_null($item->long_description) || trim((string) $item->long_description) === '')
    );
    $leftAnalysiesGroup = (isset($item->group_name) && $item->group_name !== 'Analysies');

    if ($isCleared || $leftAnalysiesGroup) {
        $CI->db->where('analysis_id', (int) $an->id)->delete(db_prefix() . 'lims_analysis_specs');
        $CI->db->where('analysis_id', (int) $an->id)->delete(db_prefix() . 'lims_panel_items');
        $CI->db->where('id', (int) $an->id)->delete(db_prefix() . 'lims_analyses');

        log_activity('LIMS: Deleted Analysis due to Item cleared/moved [analysis_id:' . $an->id . ', item_id:' . $itemId . ']');
        return;
    }

    if (trim((string) $an->name) !== trim((string) $item->description)) {
        $CI->db->where('id', (int) $an->id)->update(db_prefix() . 'lims_analyses', ['name' => $item->description]);
        log_activity('LIMS: Synced Analysis name from Item update [analysis_id:' . $an->id . ', item_id:' . $itemId . ']');
    }
}, PHP_INT_MAX);

hooks()->add_action('item_deleted', function ($itemId) {
    $CI =& get_instance();
    $itemId = (int) $itemId;

    $an = $CI->db->where('item_id', $itemId)->get(db_prefix() . 'lims_analyses')->row();
    if ($an) {
        $CI->db->where('analysis_id', (int) $an->id)->delete(db_prefix() . 'lims_analysis_specs');
        $CI->db->where('analysis_id', (int) $an->id)->delete(db_prefix() . 'lims_panel_items');
        $CI->db->where('id', (int) $an->id)->delete(db_prefix() . 'lims_analyses');

        log_activity('LIMS: Item deleted → Analysis fully removed [analysis_id:' . $an->id . ', item_id:' . $itemId . ']');
    }
}, PHP_INT_MAX);

hooks()->add_action('item_deleted', function ($itemId) {
    $CI =& get_instance();
    $itemId = (int) $itemId;

    $cu = $CI->db->where('item_id', $itemId)->get(db_prefix() . 'lims_cultures')->row();
    if ($cu) {
        $CI->db->where('id', (int) $cu->id)->delete(db_prefix() . 'lims_cultures');
        log_activity('LIMS: Item deleted → Culture fully removed [culture_id:' . $cu->id . ', item_id:' . $itemId . ']');
    }
}, PHP_INT_MAX);

hooks()->add_action('after_invoice_deleted', 'lims_unlink_billing_after_invoice_deleted', PHP_INT_MAX);
hooks()->add_action('invoice_deleted', 'lims_unlink_billing_after_invoice_deleted', PHP_INT_MAX);

function lims_unlink_billing_after_invoice_deleted($data)
{
    $CI = &get_instance();

    if (is_array($data)) {
        $invoice_id = (int) ($data['invoiceid'] ?? $data['id'] ?? 0);
    } else {
        $invoice_id = (int) $data;
    }
    if ($invoice_id <= 0) {
        return;
    }

    $orderLinks = $CI->db->where('invoice_id', $invoice_id)->get(db_prefix() . 'lims_billing_links')->result();
    $CI->db->where('invoice_id', $invoice_id)->delete(db_prefix() . 'lims_billing_links');

    foreach ($orderLinks as $lnk) {
        if (!isset($CI->orders_model)) {
            $CI->load->model('lims/orders_model');
        }
        $CI->orders_model->add_activity(
            $lnk->order_id,
            'invoice_unlinked',
            _l('lims_action_invoice_unlinked', $invoice_id),
            ['invoice_id' => (int) $invoice_id]
        );
    }
}
// Report PDF tab
hooks()->add_action('admin_init', function () {
    $CI = &get_instance();

    $tab = [
        'name'     => _l('lims_settings_report_pdf') ?: 'Report PDF',
        'view'     => 'lims/admin/settings/report_pdf',
        'position' => 50,
        'icon'     => 'fa fa-file-pdf',
    ];

    if (method_exists($CI->app, 'add_settings_section_child')) {
        $CI->app->add_settings_section_child('lims', 'lims-report-pdf', $tab);
    } else {
        if (isset($CI->app_tabs) && method_exists($CI->app_tabs, 'add_settings_tab')) {
            $CI->app_tabs->add_settings_tab('lims-report-pdf', array_merge($tab, [
                'slug'  => 'lims-report-pdf',
                'title' => _l('lims_settings_report_pdf') ?: 'Report PDF',
            ]));
        }
    }
}, PHP_INT_MAX);


// ======================================================
// LIMS tab στο Staff Profile (όπως ήδη είχαμε, με injection JS)
// ======================================================

hooks()->add_action('app_admin_head', 'lims_inject_staff_profile_tab_js', PHP_INT_MAX);

function lims_inject_staff_profile_tab_js()
{
    $CI = &get_instance();

    if (!is_staff_logged_in()) {
        return;
    }

    if ($CI->router->class !== 'staff') {
        return;
    }

    $method = $CI->router->method;
    if (!in_array($method, ['member', 'edit_profile'], true)) {
        return;
    }

    if ($method === 'member') {
        $staff_id = (int) $CI->uri->segment(4);
        if ($staff_id <= 0) {
            return;
        }
    } else {
        $staff_id = get_staff_user_id();
    }

    $tabHtml = $CI->load->view('lims/admin/staff/profile_tab', [
        'staff_id' => $staff_id,
    ], true);

    $tabHtmlJs = json_encode($tabHtml);
    $tabTitle  = _l('lims_staff_tab_title') ?: 'LIMS';
    ?>
    <script>
    (function() {
        function limsAddStaffTab() {
            var tabsUl = document.querySelector('.horizontal-scrollable-tabs .horizontal-tabs .nav-tabs.nav-tabs-horizontal');
            if (!tabsUl) { return; }

            if (tabsUl.querySelector('a[href="#staff_lims_tab"]')) {
                return;
            }

            var li = document.createElement('li');
            li.setAttribute('role', 'presentation');
            li.innerHTML =
                '<a href="#staff_lims_tab" aria-controls="staff_lims_tab" role="tab" data-toggle="tab">' +
                    '<i class="fa fa-flask"></i> ' + <?php echo json_encode($tabTitle); ?> +
                '</a>';
            tabsUl.appendChild(li);

            var mainTabContent = document.querySelector('.panel_s .panel-body > .tab-content');
            if (!mainTabContent) { return; }

            if (document.getElementById('staff_lims_tab')) {
                return;
            }

            var pane = document.createElement('div');
            pane.setAttribute('role', 'tabpanel');
            pane.className = 'tab-pane';
            pane.id = 'staff_lims_tab';
            pane.innerHTML = <?php echo $tabHtmlJs; ?>;
            mainTabContent.appendChild(pane);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', limsAddStaffTab);
        } else {
            limsAddStaffTab();
        }
    })();
    </script>
    <?php
}
// ======================================================
// Helper upload function (must be in global scope)
// ======================================================
if (!function_exists('lims_report_handle_upload')) {
    function lims_report_handle_upload($inputName, $targetDir)
    {
        if (empty($_FILES[$inputName]['name'])) {
            return null;
        }

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $tmp  = $_FILES[$inputName]['tmp_name'];
        $name = $_FILES[$inputName]['name'];

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg'], true)) {
            return null;
        }

        $safe = uniqid('lims_', true) . '.' . $ext;
        $dest = rtrim($targetDir, '/').'/'.$safe;

        if (@move_uploaded_file($tmp, $dest)) {
            return $safe; // returns filename only
        }

        return null;
    }
}

hooks()->add_action('app_admin_footer', function () {
    $CI = &get_instance();

    // Τρέξε ΜΟΝΟ στη σελίδα Settings και ΜΟΝΟ στο group του tab
    if ($CI->router->fetch_class() !== 'settings') {
        return;
    }

    $group = $CI->input->get('group');
    if ($group !== 'lims-report-pdf') {
        return;
    }

    echo <<<HTML
<script>
(function($){
  function limsToggleFooterLang(lang){
    $('[data-lims-footer-lang]').hide();
    $('[data-lims-footer-lang="'+lang+'"]').show();
  }

  // works with normal select and selectpicker
  $(document).on('change changed.bs.select', '#lims_report_footer_lang_ui', function(){
    limsToggleFooterLang($(this).val());
  });

  $(function(){
    var \$sel = $('#lims_report_footer_lang_ui');

    // refresh selectpicker if present
    if (\$.fn.selectpicker) {
      \$sel.selectpicker('refresh');
    }

    limsToggleFooterLang(\$sel.val() || 'greek');
  });
})(jQuery);
</script>
HTML;
});

// ======================================================
// Report PDF uploads (logo/background/footer) - Settings tab: group=lims-report-pdf
// Storage: uploads/lims/report/{background|footer|logo}/
// Supports both legacy and new input names.
// ======================================================
hooks()->add_action('admin_init', function () {
    $CI = &get_instance();

    // Only in Settings controller
    if ($CI->router->fetch_class() !== 'settings') {
        return;
    }

    // Only our Report PDF tab
    $group = $CI->input->get('group');
    if ($group !== 'lims-report-pdf') {
        return;
    }

    // Only on POST (Save)
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }

    if (!$CI->input->post()) {
        return;
    }

    // Permissions
    if (!has_permission('settings', '', 'edit') && !is_admin()) {
        return;
    }

    $baseDir   = FCPATH . 'uploads/lims/report/';
    $bgDir     = $baseDir . 'background/';
    $footerDir = $baseDir . 'footer/';
    $logoDir   = $baseDir . 'logo/';

    // ---------------------------
    // Background remove
    // ---------------------------
    if ((int)$CI->input->post('lims_report_background_image_remove') === 1) {
        $prev = get_option('lims_report_background_image');
        if (!empty($prev)) {
            $abs = $bgDir . ltrim($prev, '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        update_option('lims_report_background_image', '');
        $_POST['settings']['lims_report_background_image'] = '';
    }

    // Background upload (new + legacy)
    $bg = lims_report_handle_upload('lims_report_background_image_file', $bgDir);
    if (!$bg) {
        $bg = lims_report_handle_upload('lims_report_background_upload', $bgDir);
    }
    if ($bg) {
        // cleanup previous
        $prev = get_option('lims_report_background_image');
        if (!empty($prev)) {
            $absPrev = $bgDir . ltrim($prev, '/');
            if (is_file($absPrev)) {
                @unlink($absPrev);
            }
        }

        update_option('lims_report_background_image', $bg);
        // prevent core settings save from overwriting
        $_POST['settings']['lims_report_background_image'] = $bg;
    }

    // ---------------------------
    // Footer remove
    // ---------------------------
    if ((int)$CI->input->post('lims_report_footer_image_remove') === 1) {
        $prev = get_option('lims_report_footer_image');
        if (!empty($prev)) {
            $abs = $footerDir . ltrim($prev, '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        update_option('lims_report_footer_image', '');
        $_POST['settings']['lims_report_footer_image'] = '';
    }

    // Footer upload (new + legacy)
    $fi = lims_report_handle_upload('lims_report_footer_image_file', $footerDir);
    if (!$fi) {
        $fi = lims_report_handle_upload('lims_report_footer_upload', $footerDir);
    }
    if ($fi) {
        // cleanup previous
        $prev = get_option('lims_report_footer_image');
        if (!empty($prev)) {
            $absPrev = $footerDir . ltrim($prev, '/');
            if (is_file($absPrev)) {
                @unlink($absPrev);
            }
        }

        update_option('lims_report_footer_image', $fi);
        $_POST['settings']['lims_report_footer_image'] = $fi;
    }

    // ---------------------------
    // Logo remove
    // ---------------------------
    if ((int)$CI->input->post('lims_report_logo_remove') === 1) {
        $prev = get_option('lims_report_logo');
        if (!empty($prev)) {
            $abs = $logoDir . ltrim($prev, '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
        }
        update_option('lims_report_logo', '');
        $_POST['settings']['lims_report_logo'] = '';
    }

    // Logo upload (new name)
    $lg = lims_report_handle_upload('lims_report_logo_file', $logoDir);
    if ($lg) {
        // cleanup previous
        $prev = get_option('lims_report_logo');
        if (!empty($prev)) {
            $absPrev = $logoDir . ltrim($prev, '/');
            if (is_file($absPrev)) {
                @unlink($absPrev);
            }
        }

        update_option('lims_report_logo', $lg);
        $_POST['settings']['lims_report_logo'] = $lg;
    }

}, PHP_INT_MAX);



/**
 * Uninstall hook (deletes LIMS data & options).
 */
function lims_module_uninstall_hook()
{
    $uninstallPath = __DIR__ . '/uninstall.php';
    if (is_file($uninstallPath)) {
        require_once($uninstallPath);
    }
    if (function_exists('lims_uninstall')) {
        // Default: remove data.
        lims_uninstall(false);
    }
}

