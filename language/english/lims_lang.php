<?php defined('BASEPATH') or exit('No direct script access allowed');
$lang['lims_menu'] = 'LIMS';
$lang['lims_setup_menu'] = 'LIMS';
$lang['lims_orders'] = 'Orders';
$lang['lims_settings'] = 'LIMS Settings';
$lang['lims_contracts'] = 'Contracts';
$lang['lims_create_invoice'] = 'Create Invoice';
$lang['lims_new_order'] = 'New Order';
$lang['lims_order'] = 'Order';
$lang['lims_tests'] = 'Tests';
$lang['lims_results'] = 'Results';
$lang['lims_status'] = 'Status';
$lang['lims_actions'] = 'Actions';
$lang['lims_save'] = 'Save';
$lang['lims_cancel'] = 'Cancel';
$lang['lims_back'] = 'Back';
$lang['lims_edit'] = 'Edit';
$lang['lims_delete'] = 'Delete';
$lang['lims_view'] = 'View';
$lang['lims_search'] = 'Search';
$lang['lims_filter'] = 'Filter';
$lang['lims_client'] = 'Client';
$lang['lims_priority'] = 'Priority';
$lang['lims_received_at'] = 'Received At';
$lang['lims_due_at'] = 'Due At';
$lang['lims_notes'] = 'Notes';
$lang['lims_barcode'] = 'Barcode';
$lang['lims_sample_type'] = 'Sample Type';
$lang['lims_collected_at'] = 'Collected At';
$lang['lims_verified_at'] = 'Verified At';
$lang['lims_approved_at'] = 'Approved At';
$lang['lims_method'] = 'Method';
$lang['lims_instrument'] = 'Instrument';
$lang['lims_unit'] = 'Unit';
$lang['lims_ref_range'] = 'Reference Range';
$lang['lims_flag'] = 'Flag';
$lang['lims_value'] = 'Value';
$lang['lims_unit_price'] = 'Unit Price';
$lang['lims_currency'] = 'Currency';
$lang['lims_status_draft'] = 'Draft';
$lang['lims_status_submitted'] = 'Submitted';
$lang['lims_status_accessioned'] = 'Accessioned';
$lang['lims_status_testing'] = 'Testing';
$lang['lims_status_verified'] = 'Verified';
$lang['lims_status_approved'] = 'Approved';
$lang['lims_status_reported'] = 'Reported';
$lang['lims_test_pending'] = 'Pending';
$lang['lims_test_in_progress'] = 'In Progress';
$lang['lims_test_completed'] = 'Completed';
$lang['lims_test_verified'] = 'Verified';
$lang['lims_flag_low'] = 'Low';
$lang['lims_flag_high'] = 'High';
$lang['lims_flag_crit_low'] = 'Critical Low';
$lang['lims_flag_crit_high'] = 'Critical High';
$lang['lims_flag_abnormal'] = 'Abnormal';
$lang['lims_contract_create'] = 'Create Contract';
$lang['lims_contract_name'] = 'Contract Name';
$lang['lims_contract_discount_percent'] = 'Discount (%)';
$lang['lims_contract_fixed_price'] = 'Fixed Price';
$lang['lims_contract_currency'] = 'Currency';
$lang['lims_contract_active'] = 'Active';
$lang['lims_contract_priority'] = 'Priority';
$lang['lims_contract_valid_from'] = 'Valid From';
$lang['lims_contract_valid_to'] = 'Valid To';
$lang['lims_contract_item'] = 'Item';
$lang['lims_contract_add_price'] = 'Add Price';
$lang['lims_appointment_at'] = 'Appointment At';
$lang['lims_visit_type'] = 'Visit Type';
$lang['lims_visit_type_lab'] = 'Lab Visit';
$lang['lims_visit_type_home'] = 'Home Visit';
$lang['lims_assigned_staff'] = 'Assigned Staff';
$lang['lims_location_text'] = 'Location/Address';
$lang['lims_status_pending'] = 'Pending';
$lang['lims_status_confirmed'] = 'Confirmed';
$lang['lims_status_completed'] = 'Completed';
$lang['lims_status_canceled'] = 'Canceled';
$lang['lims_status_no_show'] = 'No Show';
$lang['lims_tbl_analyte'] = 'Analyte';
$lang['lims_tbl_result'] = 'Result';
$lang['lims_tbl_unit'] = 'Unit';
$lang['lims_tbl_method'] = 'Method';
$lang['lims_tbl_instrument'] = 'Instrument';
$lang['lims_tbl_ref_range'] = 'Reference Range';
$lang['lims_tbl_flag'] = 'Flag';
$lang['lims_tbl_price'] = 'Price';
$lang['lims_tbl_qty'] = 'Qty';
$lang['lims_order_created'] = 'Order created successfully.';
$lang['lims_invoice_created'] = 'Invoice created successfully.';
$lang['lims_invoice_failed'] = 'Failed to create invoice.';
$lang['lims_saved'] = 'Changes saved.';
$lang['lims_deleted'] = 'Deleted successfully.';
$lang['lims_error_generic'] = 'An error occurred. Please try again.';
$lang['lims_access_denied'] = 'You do not have access.';
$lang['lims_contract_prices_optional'] = 'Item prices can be added later from the full form.';
$lang['advanced_settings']             = 'Full form';
$lang['lims_sample_types'] = 'Sample Types';
$lang['lims_analyses']     = 'Analyses';
$lang['no_items_found']     = 'No selected Items';


$lang['lims_min_volume']                   = 'Minimum Volume';
$lang['lims_container']                    = 'Container';
$lang['lims_stability_hours']              = 'Stability (hours)';
$lang['lims_storage_temp']                 = 'Storage Temperature';
$lang['collection']                        = 'Collection';
$lang['instructions']                      = 'Instructions';

/* Descriptions (help text) */
$lang['lims_sampletype_name_desc']         = 'Human-friendly name of the specimen type (e.g., Serum, Plasma, Urine).';
$lang['lims_sampletype_code_desc']         = 'Short code/alias used in labels or exports (optional).';
$lang['lims_sampletype_snomed_desc']       = 'Optional SNOMED CT specimen code (for interoperability).';
$lang['lims_sampletype_active_desc']       = 'Toggle to enable/disable this specimen type in forms and orders.';
$lang['lims_min_volume_desc']              = 'Minimum acceptable sample volume (include unit, e.g., 2 mL).';
$lang['lims_container_desc']               = 'Recommended container/tube (e.g., Serum tube, EDTA tube).';
$lang['lims_stability_hours_desc']         = 'Estimated sample stability at recommended storage (in hours).';
$lang['lims_storage_temp_desc']            = 'Recommended storage temperature (e.g., 2–8°C, -20°C).';
$lang['lims_collection_instructions_desc'] = 'Special collection instructions for staff or patients.';

$lang['lims_sampletype_info_title']             = 'About Sample Types';
$lang['lims_sampletype_info_desc']              = 'Sample Types define the kind of specimen collected for testing (e.g., Serum, Plasma, Urine). They help normalize ordering, labeling, handling, and result interpretation across the LIMS.';
$lang['lims_sampletype_info_purpose_title']     = 'Purpose';
$lang['lims_sampletype_info_purpose_1']         = 'Standardize specimen naming for orders, labels, and reports.';
$lang['lims_sampletype_info_purpose_2']         = 'Drive collection/handling rules (container, storage, stability).';
$lang['lims_sampletype_info_purpose_3']         = 'Filter available analyses per specimen type and pick correct reference intervals.';
$lang['lims_sampletype_info_guidelines_title']  = 'Guidelines';
$lang['lims_sampletype_info_guideline_1']       = 'Use clear, human-friendly names (e.g., “Serum”, not cryptic codes).';
$lang['lims_sampletype_info_guideline_2']       = 'Fill container, storage temperature, and stability to guide staff.';
$lang['lims_sampletype_info_guideline_3']       = 'If you need interoperability, add a SNOMED CT specimen code.';
$lang['lims_sampletype_info_guideline_4']       = 'Deactivate types you no longer use instead of deleting them.';


// Labels
$lang['lims_loinc_code']          = 'LOINC Code';
$lang['lims_department']          = 'Department';
$lang['lims_tat_hours']           = 'TAT (hours)';
$lang['lims_decimal_places']      = 'Decimal Places';
$lang['lims_units_ucum']          = 'Units (UCUM)';
$lang['lims_result_type']         = 'Result Type';

// Field descriptions
$lang['lims_analysis_name_desc']  = 'Human-friendly name of the analysis/test as it should appear in orders and reports.';
$lang['lims_analysis_code_desc']  = 'Short internal code or alias used for search or exports (optional).';
$lang['lims_loinc_code_desc']     = 'Optional LOINC code for interoperability and standardized reporting.';
$lang['lims_analysis_active_desc']= 'Enable/disable this analysis in ordering and catalog listings.';
$lang['lims_department_desc']     = 'Discipline or laboratory section responsible for this analysis (e.g., Chemistry).';
$lang['lims_method_desc']         = 'Analytical method or technique (e.g., Immunoassay, PCR).';
$lang['lims_tat_hours_desc']      = 'Expected turnaround time from accessioning to verification, in hours.';
$lang['lims_decimal_places_desc'] = 'Maximum number of decimal places to display for numeric results.';
$lang['lims_units_ucum_desc']     = 'Measurement units using UCUM notation (e.g., mg/dL, mmol/L).';
$lang['lims_result_type_desc']    = 'Choose the type of result: numeric value, free text, or selection list.';
$lang['lims_item_link_desc']      = 'Link this analysis to a Item for billing. Pricing resolves via LIMS Contracts first, then falls back to the Item rate.';

// Info section
$lang['lims_analysis_info_title']             = 'About Analyses (Tests)';
$lang['lims_analysis_info_desc']              = 'Analyses describe laboratory tests available for ordering, including method, units, result type, and billing item linkage.';
$lang['lims_analysis_info_purpose_title']     = 'Purpose';
$lang['lims_analysis_info_purpose_1']         = 'Provide a standardized catalog of tests for ordering and reporting.';
$lang['lims_analysis_info_purpose_2']         = 'Define units, precision, and methods to guide result entry and validation.';
$lang['lims_analysis_info_purpose_3']         = 'Connect tests to billing items to ensure consistent pricing.';
$lang['lims_analysis_info_guidelines_title']  = 'Guidelines';
$lang['lims_analysis_info_guideline_1']       = 'Use clear names and codes that match how staff search for tests.';
$lang['lims_analysis_info_guideline_2']       = 'Set UCUM units and decimals for accurate and consistent results.';
$lang['lims_analysis_info_guideline_3']       = 'If interoperability is required, provide the LOINC code.';
$lang['lims_analysis_info_guideline_4']       = 'Deactivate tests you no longer offer instead of deleting them.';


$lang['lims_settings_general']          = 'General';
$lang['lims_settings_barcode_prefix']   = 'Sample Barcode Prefix';
$lang['lims_settings_barcode_desc']     = 'Prefix used when generating sample barcodes (e.g., SMP).';
$lang['lims_settings_default_dept']     = 'Default Department';
$lang['lims_settings_default_dept_desc']= 'Default lab section used when creating new analyses (optional).';
$lang['lims_settings_enable_contracts'] = 'Enable LIMS Contracts';
$lang['lims_settings_enable_contracts_desc'] = 'If enabled, pricing resolves from LIMS Contracts first, then falls back to Item rate.';

$lang['lims_panels']                 = 'Panels';
$lang['lims_panel_name_desc']        = 'Human-friendly name for the analysis group (e.g., Basic Metabolic Panel).';
$lang['lims_panel_code_desc']        = 'Short internal code/alias for the panel (optional).';
$lang['lims_panel_department_desc']  = 'Lab section responsible for this panel.';
$lang['lims_panel_active_desc']      = 'Enable/disable this panel.';
$lang['lims_panel_item_desc']        = 'Link to a Item for billing this panel as a single service.';
$lang['lims_panel_analyses_title']   = 'Panel Analyses';
$lang['lims_panel_analyses_desc']    = 'Add the analyses that compose this panel. You can mark some as required and control the display order.';
$lang['lims_analysis']               = 'Analysis';
$lang['lims_sort_order']             = 'Sort Order';
$lang['lims_panel_info_title']       = 'About Panels';
$lang['lims_panel_info_desc']        = 'Panels are groups of analyses ordered together. You can bill the panel as a single item or use the sum of analyses if no item is linked.';


// Departments
$lang['lims_departments']             = 'Departments';
$lang['lims_department_name_desc']    = 'Unique department name (e.g., Chemistry, Hematology).';
$lang['lims_department_code_desc']    = 'Short code or alias (optional).';

$lang['lims_billing']                = 'Billing';
$lang['lims_billing_note']           = 'This analysis will be linked to a billing item automatically. Fill in the details below so invoices show the correct information.';
$lang['lims_unit_note']              = 'Example: test, service, panel.';
$lang['lims_rates_note']             = 'The price in the default currency is used for billing. Other currency prices are stored for reference and contracts (LIMS Contracts).';

$lang['lims_analysis_name_desc']     = 'Short, clear name of the analysis as seen by the client.';
$lang['lims_analysis_code_desc']     = 'Optional internal/lab code.';
$lang['lims_loinc_code']             = 'LOINC';
$lang['lims_loinc_code_desc']        = 'Optional LOINC code when available.';
$lang['lims_analysis_active_desc']   = 'If active, the analysis can be used in orders/contracts.';
$lang['lims_department']             = 'Department';
$lang['lims_department_desc']        = 'Choose the department this analysis belongs to.';
$lang['lims_method']                 = 'Method';
$lang['lims_method_desc']            = 'Optionally describe the measurement method (e.g., Immunoassay).';
$lang['lims_tat_hours']              = 'Turnaround Time (hours)';
$lang['lims_tat_hours_desc']         = 'Estimated time from sample receipt to result availability.';
$lang['lims_decimal_places']         = 'Decimal places';
$lang['lims_decimal_places_desc']    = 'Number of decimals for numeric results.';
$lang['lims_units_ucum']             = 'Units (UCUM)';
$lang['lims_units_ucum_desc']        = 'E.g., mg/dL, IU/L, following UCUM where possible.';
$lang['lims_result_type']            = 'Result type';
$lang['lims_result_type_desc']       = 'Select whether the result is numeric, text, or from a pick list.';

$lang['tax_1']                       = 'Tax 1';
$lang['tax_2']                       = 'Tax 2';
$lang['long_description']            = 'Long Description';
$lang['unit']                        = 'Unit';
$lang['price']                       = 'Price';
$lang['currency']                    = 'Currency';
$lang['billing']                     = 'Billing';
$lang['note']                        = 'Note';
$lang['description']                 = 'Description';

$lang['lims_select_analyses']        = 'Select analyses';
$lang['lims_panel_longdesc_note']    = 'By default, it lists the selected analyses.';

$lang['lims_panel_about_title']       = 'About Panels';
$lang['lims_panel_about_desc']        = 'Panels group analyses that are commonly requested together, simplifying ordering, billing, and contracting.';
$lang['lims_panel_purpose_title']     = 'Purpose';
$lang['lims_panel_purpose_1']         = 'Bundle related analyses into a single orderable item.';
$lang['lims_panel_purpose_2']         = 'Provide unified pricing across multiple analyses.';
$lang['lims_panel_purpose_3']         = 'Improve client clarity on what is included.';
$lang['lims_panel_guidelines_title']  = 'Guidelines';
$lang['lims_panel_guideline_1']       = 'Include analyses that make sense to be performed together.';
$lang['lims_panel_guideline_2']       = 'Configure prices and taxes at the panel level; contracts can override them.';
$lang['lims_panel_guideline_3']       = 'On create, the long description is auto-filled with the selected analyses.';
$lang['lims_panel_guideline_4']       = 'On edit, change only prices and taxes to keep orders consistent.';

$lang['lims_partners']                     = 'Partners';
$lang['new_partner']                       = 'New Partner';
$lang['lims_partner_form_title']           = 'Partner';
$lang['lims_partner_name_desc']            = 'Name of the collaborating lab/organization.';
$lang['lims_partner_customer_select_desc'] = 'Link to an existing core customer.';
$lang['lims_partner_customer_new_desc']    = 'If no customer is selected, you can create a new one by entering the company name.';
$lang['select_existing']                   = 'select existing';
$lang['or_create_new_customer']            = 'Or create new customer';
$lang['generate']                          = 'Generate';
$lang['lims_partner_api_desc']             = 'Key for future LIMS-to-LIMS API integration.';
$lang['lims_partners_about_title']         = 'About Partners';
$lang['lims_partners_about_desc']          = 'Register collaborating labs/organizations for executing or subcontracting analyses and future system-to-system integrations.';
$lang['lims_partners_purpose_title']       = 'Purpose';
$lang['lims_partners_purpose_1']           = 'Maintain a central list of collaborating labs.';
$lang['lims_partners_purpose_2']           = 'Link with core customers for unified view and billing.';
$lang['lims_partners_purpose_3']           = 'Foundation for future data exchange (API).';
$lang['lims_partners_guidelines_title']    = 'Guidelines';
$lang['lims_partners_guideline_1']         = 'Use accurate contact details.';
$lang['lims_partners_guideline_2']         = 'Link to existing customers whenever possible.';
$lang['lims_partners_guideline_3']         = 'Keep the API key secure (do not share).';
$lang['lims_error_name_required']          = 'Partner name is required.';

$lang['lims_test_statuses']        = 'Test Statuses';
$lang['lims_ts_name_desc']         = 'Display name shown to users.';
$lang['lims_ts_code_desc']         = 'Unique slug (latin, no spaces).';
$lang['lims_ts_requires_result']   = 'Requires result';
$lang['lims_ts_requires_verification'] = 'Requires verification';
$lang['lims_ts_requires_approval'] = 'Requires approval';
$lang['lims_ts_is_terminal']       = 'Terminal status';
$lang['lims_ts_about_title']       = 'About Test Statuses';
$lang['lims_ts_about_desc']        = 'Configure the lifecycle and requirements for test progression.';
$lang['lims_ts_guidelines_title']  = 'Guidelines';
$lang['lims_ts_gl_1']              = 'Keep codes unique and in lowercase (e.g., in_progress).';
$lang['lims_ts_gl_2']              = 'Mark terminal statuses for final states (e.g., reported).';
$lang['lims_ts_gl_3']              = 'Use the flags to enforce results/verification/approval before advancing.';

$lang['lims_select_services'] = 'Select Panels and/or Analyses to include in this order.';
$lang['lims_contract'] = 'Contract';
$lang['lims_contract_hint'] = 'Optional: If selected, contract pricing will override base prices when applicable.';


$lang['lims_invoice_create'] = 'Generate Invoice';
$lang['lims_panel'] = 'Panel';
$lang['lims_contract'] = 'Contract';
$lang['lims_analyses'] = 'Analyses';
$lang['lims_schedule_appointment'] = 'Schedule Appointment';
$lang['lims_add_sample'] = 'Add Sample';
$lang['activity_log'] = 'Activity Log';

$lang['generate'] = 'Generate';
$lang['regenerate'] = 'Regenerate';
$lang['barcode'] = 'Barcode';
$lang['lims_barcode_placeholder'] = 'Barcode image coming soon';


$lang['invoice_create'] = 'Create Invoice';
$lang['created_successfully'] = 'created successfully';
$lang['problem_creating'] = 'Problem creating';

$lang['lims_convert_as_draft']   = 'Convert as Draft';
$lang['lims_convert']            = 'Convert';
$lang['lims_convert_and_pay']    = 'Convert & Pay';

$lang['Generate Barcode'] = 'Generate Barcode';
$lang['Regenerate']       = 'Regenerate';
$lang['Order Barcode']    = 'Order Barcode';

$lang['lims_activity_log']           = 'Activity Log';
$lang['lims_activity_who']           = 'Who';
$lang['lims_activity_action']        = 'Action';
$lang['lims_action_status_changed']  = 'Status changed to %s';
$lang['lims_action_barcode_generated']= 'Barcode generated: %s';
$lang['lims_action_barcode_regenerated']= 'Barcode regenerated: %s';
$lang['lims_action_invoice_created'] = 'Invoice created';
$lang['lims_action_invoice_unlinked']= 'Invoice link removed (ID: %s)';
$lang['system']                      = 'System';
$lang['no_activity_found']           = 'No activity yet.';

$lang['lims_appointments']     = 'Appointments';
$lang['lims_appointment']      = 'Appointment';
$lang['new_appointment']       = 'New Appointment';
$lang['link']                  = 'Link';
$lang['no_link']               = 'No link';
$lang['link_existing_order']   = 'Link existing Order';
$lang['create_new_order']      = 'Create new Order';
$lang['location']              = 'Location';
$lang['create_task']           = 'Create Task';

$lang['lims_map_address_placeholder'] = 'Type an address or drop a pin...';
$lang['lims_search_on_map']           = 'Search on map';
$lang['lims_pick_on_map_hint']        = 'Drop the pin on the map or type an address.';
$lang['coordinates'] = 'Coordinates';

$lang['lims_notes_inline_hint'] = 'Write your notes and click Save.';
$lang['problem_updating']       = 'Problem updating';

$lang['filter_by']       = 'Filter by';
$lang['upcoming']        = 'Upcoming';
$lang['last_month']      = 'Last month';
$lang['last_2_months']   = 'Last 2 months';
$lang['this_year']       = 'This year';
$lang['last_year']       = 'Last year';
$lang['next_month']      = 'Next month';
$lang['custom']          = 'Custom';
$lang['from']            = 'From';
$lang['to']              = 'To';
$lang['apply']           = 'Apply';
$lang['reset']           = 'Reset';

$lang['lims_samples']        = 'Samples';
$lang['lims_sample']         = 'Sample';
$lang['lims_sample_add']     = 'Add Sample';
$lang['lims_sample_uid']     = 'Sample UID';
$lang['lims_sample_type']    = 'Sample Type';
$lang['lims_generate_barcode'] = 'Generate Barcode';
$lang['lims_barcode_generated'] = 'Barcode generated';
$lang['lims_barcode_hint']     = 'Generate a barcode after saving the sample.';
$lang['lims_sample_order_hint'] = 'Order ID that the sample belongs to.';
$lang['lims_sample_uid_hint']   = 'Leave empty to auto-generate a UID.';
$lang['lims_collected_at']      = 'Collected At';
$lang['lims_received_at']       = 'Received At';
$lang['lims_mark_uncollected']	= 'Colected';
$lang['lims_mark_collected']	= 'Uncolected';
$lang['lims_pdf_sample_labels']	= 'Labels';


$lang['link_with'] = 'Link with';
$lang['appointment'] = 'Appointment';
$lang['create_new_order'] = 'Create new Order';
$lang['create_order_from_appointment'] = 'Create Order from Appointment';
$lang['choose_appointment'] = 'Choose an appointment to link.';
$lang['auto_after_save'] = 'Auto after save';

// ==== Cultures & Culture Types ====
$lang['lims_cultures']                     = 'Cultures';
$lang['lims_culture']                      = 'Culture';
$lang['lims_culture_types']                = 'Culture Types';
$lang['lims_culture_type']                 = 'Culture Type';


// Form labels & hints (Cultures)
$lang['lims_culture_incubation_temp'] = 'Incubation Temperature';
$lang['lims_culture_incubation_time'] = 'Incubation Time';
$lang['lims_culture_incubation_temp_desc'] = 'E.g., 35–37°C.';
$lang['lims_culture_incubation_time_desc'] = 'E.g., 24–48 hours.';
$lang['lims_culture_loinc_code_desc'] = 'Optional LOINC code for interoperability.';
$lang['lims_culture_name_desc']            = 'Clear, human-friendly name of the culture as shown in orders and reports.';
$lang['lims_culture_code_desc']            = 'Optional internal code/alias for search or exports.';
$lang['lims_culture_active_desc']          = 'Enable/disable this culture in the catalog and ordering.';
$lang['lims_culture_sample_type_desc']     = 'Choose the specimen this culture applies to.';
$lang['lims_culture_type_desc']            = 'Select the category/group of the culture (e.g., Aerobic, Anaerobic).';
$lang['lims_culture_method_desc']          = 'Analytical procedure or workflow (e.g., Culture, MIC).';
$lang['lims_culture_tat_hours_desc']       = 'Expected turnaround time from accessioning to result, in hours.';
$lang['lims_culture_billing_note']         = 'This culture will be linked to a billing item automatically. Set price/taxes below.';

$lang['lims_cultures_about_title']         = 'About Cultures';
$lang['lims_cultures_about_desc']          = 'Cultures define microbiology workflows such as organism growth and susceptibility, standardizing ordering, pricing, and reporting.';
$lang['lims_cultures_purpose_1']           = 'Provide clear, standardized culture options for orders.';
$lang['lims_cultures_purpose_2']           = 'Support pricing and contracts via a linked billing item.';
$lang['lims_cultures_purpose_3']           = 'Guide staff with consistent methods and turnaround times.';
$lang['lims_cultures_guideline_1']         = 'Pick the correct specimen and culture type for clarity.';
$lang['lims_cultures_guideline_2']         = 'Configure prices and taxes at the culture level; contracts may override.';
$lang['lims_cultures_guideline_3']         = 'Use descriptive long descriptions for client-facing documents.';

$lang['lims_sample_type_hint']             = 'Choose the specimen this configuration applies to.';


// Buttons / actions
$lang['lims_new_culture_type']        = 'New Culture Type';
$lang['lims_new_culture']             = 'New Culture';
$lang['lims_toggle_active']           = 'Toggle active';

// Form labels & hints (Culture Types)
$lang['lims_culturetype_name_desc']   = 'Human-friendly name for this culture type (e.g., Bacterial Culture).';
$lang['lims_culturetype_code_desc']   = 'Short code/alias used in exports or labels (optional).';
$lang['lims_culturetype_description_desc'] = 'Optional description/guidelines for using this culture type.';
$lang['lims_culturetype_active_desc'] = 'Enable/disable this culture type in forms and orders.';


// Sample Type dropdown (used also in Analyses)
$lang['lims_sample_type']             = 'Sample Type';
$lang['lims_sample_type_hint']        = 'Choose the specimen type required for this test.';

// Generic flash messages
$lang['lims_created']                 = 'Created successfully.';
$lang['lims_updated']                 = 'Updated successfully.';

// Setup → LIMS (submenu labels already covered; added here for completeness)
$lang['lims_setup_culture_types']     = 'Culture Types';
$lang['lims_setup_cultures']          = 'Cultures';

$lang['lims_settings_labels']       = 'Labels';
$lang['lims_label_settings_title']  = 'Sample Label Settings';
$lang['lims_label_page_setup']      = 'Page & Grid';
$lang['lims_label_size']            = 'Label Size (mm)';
$lang['lims_label_margins']         = 'Margins / Spacing (mm)';
$lang['lims_label_fonts']           = 'Fonts & Barcode';
$lang['lims_label_page_width_mm']   = 'Page width (mm)';
$lang['lims_label_page_height_mm']  = 'Page height (mm)';
$lang['lims_label_columns']         = 'Columns per row';
$lang['lims_label_rows']            = 'Rows per page';
$lang['lims_label_width_mm']        = 'Label width (mm)';
$lang['lims_label_height_mm']       = 'Label height (mm)';
$lang['lims_label_hgap_mm']         = 'Horizontal gap (mm)';
$lang['lims_label_vgap_mm']         = 'Vertical gap (mm)';
$lang['lims_label_left_margin_mm']  = 'Left margin (mm)';
$lang['lims_label_top_margin_mm']   = 'Top margin (mm)';
$lang['lims_label_font_size']       = 'Text font size (pt)';
$lang['lims_label_barcode_height']  = 'Barcode height (mm)';
$lang['lims_label_show_received']   = 'Show Received At';
$lang['lims_label_show_sampletype'] = 'Show Sample Type';
$lang['lims_label_show_analysis']   = 'Show Analysis name';


$lang['lims_tests']                       = 'Laboratory Tests';
$lang['lims_tests_queue']                 = 'Test Queue';

$lang['lims_test']                        = 'Test';
$lang['lims_test_not_found']              = 'Test not found';

$lang['lims_test_table_col_id']           = '#';
$lang['lims_test_table_col_order']        = 'Order';
$lang['lims_test_table_col_sample']       = 'Sample';
$lang['lims_test_table_col_test']         = 'Test';
$lang['lims_test_table_col_department']   = 'Department';
$lang['lims_test_table_col_status']       = 'Status';
$lang['lims_test_table_col_assigned_to']  = 'Technician';
$lang['lims_test_table_col_collected_at'] = 'Collection Date';

$lang['lims_department']                  = 'Department';
$lang['lims_sample_type']                 = 'Sample type';
$lang['lims_assigned_to']                 = 'Assigned to';

$lang['lims_tab_meta']                    = 'Details';
$lang['lims_tab_result_entry']            = 'Result Entry';
$lang['lims_tab_attachments']             = 'Attachments';
$lang['lims_tab_audit_trail']             = 'Audit Trail';

$lang['lims_test_status_pending']              = 'Pending';
$lang['lims_test_status_in_progress']          = 'In progress';
$lang['lims_test_status_awaiting_verification']= 'Awaiting verification';
$lang['lims_test_status_verified']             = 'Verified';
$lang['lims_test_status_approved']             = 'Approved';
$lang['lims_test_status_cancelled']            = 'Cancelled';

$lang['lims_btn_save_draft']              = 'Save draft';
$lang['lims_btn_submit_for_verification'] = 'Submit for verification';

$lang['lims_test_result_value']           = 'Result';
$lang['lims_test_result_unit']            = 'Unit';
$lang['lims_test_ref_range_low']          = 'Reference range (low)';
$lang['lims_test_ref_range_high']         = 'Reference range (high)';
$lang['lims_test_flag']                   = 'Flag';
$lang['lims_test_flag_high']              = 'High';
$lang['lims_test_flag_low']               = 'Low';
$lang['lims_test_flag_abnormal']          = 'Abnormal';
$lang['lims_test_comments']               = 'Comments';

$lang['lims_test_field_order']            = 'Order';
$lang['lims_test_field_sample']           = 'Sample';
$lang['lims_test_field_department']       = 'Department';
$lang['lims_test_field_priority']         = 'Priority';
$lang['lims_test_field_assigned_to']      = 'Assigned to';

$lang['lims_test_field_status']           = 'Status';
$lang['lims_test_field_started_at']       = 'Started at';
$lang['lims_test_field_completed_at']     = 'Completed at';
$lang['lims_test_field_verified_at']      = 'Verified at';
$lang['lims_test_field_approved_at']      = 'Approved at';

$lang['lims_test_add_attachment']         = 'Add attachment';
$lang['lims_test_attachment_filename']    = 'File name';
$lang['lims_test_attachment_added_by']    = 'Added by';
$lang['lims_test_attachment_date']        = 'Date';

$lang['lims_audit_timestamp']             = 'Timestamp';
$lang['lims_audit_user']                  = 'User';
$lang['lims_audit_action']                = 'Action';
$lang['lims_audit_reason']                = 'Reason';

// Result type - select values
$lang['lims_result_select_values']      = 'Select values';
$lang['lims_result_select_value']       = 'Value';
$lang['lims_result_select_label']       = 'Label (optional)';
$lang['lims_result_select_desc']        = 'When the result type is "Select", these values will appear as options in the results entry form.';

// Reference ranges
$lang['lims_analysis_reference_ranges']      = 'Reference range';
$lang['lims_analysis_reference_ranges_help'] = 'Define reference and critical ranges per sex and age for this analysis.';
$lang['lims_analysis_ref_gender']           = 'Gender';
$lang['lims_analysis_ref_age_from']         = 'Age from';
$lang['lims_analysis_ref_age_to']           = 'Age to';
$lang['lims_analysis_ref_critical_low']     = 'Critical low';
$lang['lims_analysis_ref_normal']           = 'Normal';
$lang['lims_analysis_ref_normal_low']       = 'Normal low';
$lang['lims_analysis_ref_normal_high']      = 'Normal high';
$lang['lims_analysis_ref_critical_high']    = 'Critical high';

// Genders (UI)
$lang['lims_gender_both']   = 'Both';
$lang['lims_gender_male']   = 'Male';
$lang['lims_gender_female'] = 'Female';

$lang['lims_culture_options']              = 'Culture Options';
$lang['lims_culture_option_name_hint']     = 'Display name of the culture option set (e.g. Culture Result).';
$lang['lims_culture_option_code_hint']     = 'Internal code for this option set (letters, numbers, underscore).';
$lang['lims_culture_option_active_hint']   = 'Inactive sets will not be available when configuring cultures.';
$lang['lims_culture_option_values']        = 'Option values';
$lang['lims_culture_option_values_hint']   = 'Define the selectable values for this culture option set.';
$lang['lims_value_code']                   = 'Value code';
$lang['lims_value_label']                  = 'Label';
$lang['lims_sort_order']                   = 'Sort order';

$lang['lims_culture_option_sets']          = 'Culture option sets';
$lang['lims_culture_option_sets_hint']     = 'Select which culture option sets (e.g. Culture Result, Semi-quantitative growth) apply to this culture.';
$lang['lims_culture_comment']     = 'Comments';
$lang['lims_culture_comment_placeholder']     = 'Culture Comments';
$lang['lims_test_measured_at']     = 'Test Measured at';
$lang['lims_open_order']     = 'Order';

$lang['lims_settings_report_pdf']              = 'Report PDF';
$lang['lims_settings_report_pdf_heading']      = 'Report PDF Settings';
$lang['lims_settings_report_pdf_subheading']   = 'Layout and appearance settings for laboratory report PDFs (header, fonts, footer, etc.).';

$lang['lims_settings_report_license_number']   = 'License number (top right)';
$lang['lims_settings_report_header_title']     = 'Header title (laboratory name)';
$lang['lims_settings_report_header_subtitle']  = 'Header subtitle (report type)';
$lang['lims_report_font_family']      = 'Report font family';
$lang['lims_report_font_size']        = 'Font size (pt)';
$lang['lims_settings_report_footer_text']      = 'Footer text';
$lang['lims_settings_report_footer_text_hint'] = 'This text will appear at the bottom of the report (address, phone numbers, email, etc.).';
$lang['lims_settings_report_footer_image']     = 'Footer image (stamp/logo)';
$lang['lims_settings_report_footer_image_hint']= 'Relative path to the image that will be used at the bottom of the report (e.g. stamp or logo).';
// ---------- LIMS Report PDF Settings ----------
$lang['lims_settings_pdf_report']              = 'Laboratory Report PDF';
$lang['lims_settings_pdf_preview_help']        = 'Configure how the final laboratory report PDF looks (license number, header, fonts, footer, footer image, etc.).';

$lang['lims_settings_pdf_license_number']      = 'License number';
$lang['lims_settings_pdf_license_number_help'] = 'Shown on the top-right corner of the report (e.g. lab license/certification number).';

$lang['lims_settings_pdf_header_title']        = 'Header main title';
$lang['lims_settings_pdf_header_subtitle']     = 'Header subtitle';

$lang['lims_settings_pdf_fonts']               = 'Fonts';
$lang['lims_settings_pdf_font_family']         = 'Font family';
$lang['lims_settings_pdf_font_size']           = 'Font size (body text)';

$lang['lims_settings_pdf_footer']              = 'Report footer';
$lang['lims_settings_pdf_footer_text']         = 'Footer text';

$lang['lims_settings_pdf_footer_image']        = 'Footer image';
$lang['lims_settings_pdf_footer_image_help']   = 'Path or URL to the image that will be used as the footer at the bottom of each report page.';
$lang['lims_report_pdf_button']         = 'Report';

$lang['lims_subjects']                      = 'Subjects';
$lang['lims_subject']                       = 'Subject';
$lang['lims_new_subject']                   = 'New subject';
$lang['lims_edit_subject']                  = 'Edit subject';

$lang['lims_subject_general']               = 'General information';
$lang['lims_subject_billing_client']        = 'Billing customer';
$lang['lims_subject_billing_client_help']   = 'Select the customer that will be invoiced for this subject\'s orders.';
$lang['lims_subject_link_to_existing_client'] = 'Link to existing customer';
$lang['lims_subject_create_new_client']     = 'Create new customer';

$lang['lims_subject_type']                  = 'Subject type';
$lang['lims_subject_type_patient']          = 'Patient';
$lang['lims_subject_type_lab']              = 'Laboratory';
$lang['lims_subject_type_farm']             = 'Farm';
$lang['lims_subject_type_doctor']           = 'Doctor';
$lang['lims_subject_type_restaurant']       = 'Restaurant';
$lang['lims_subject_type_other']            = 'Other';

$lang['lims_subject_name']                  = 'Subject name';
$lang['lims_subject_name_help']             = 'Used mainly for non-patient subjects (farm, restaurant, lab, etc.).';

$lang['lims_subject_first_name']            = 'First name';
$lang['lims_subject_last_name']             = 'Last name';
$lang['first_name']                         = 'First name';
$lang['last_name']                          = 'Last name';
$lang['date_of_birth']                      = 'Date of birth';

$lang['lims_subject_code']                  = 'Internal code';
$lang['lims_subject_id_number']             = 'ID / Passport number';
$lang['lims_subject_nationality']           = 'Nationality';
$lang['lims_subject_gender']                = 'Gender';
$lang['lims_subject_gender_male']           = 'Male';
$lang['lims_subject_gender_female']         = 'Female';
$lang['lims_subject_gender_other']          = 'Other';
$lang['lims_subject_social_insurance_no']   = 'Social insurance number';
$lang['lims_subject_date_of_birth']         = 'Date of birth';

$lang['lims_subject_phone']                 = 'Phone';
$lang['lims_subject_email']                 = 'Email';
$lang['lims_subject_address']               = 'Address';
$lang['lims_subject_city']                  = 'City';
$lang['lims_subject_state']                 = 'State / District';
$lang['lims_subject_zip']                   = 'Postal code';
$lang['lims_subject_country']               = 'Country';

$lang['lims_subject_notes']                 = 'Notes';
$lang['lims_subject_active']                = 'Active';

$lang['lims_subject_orders']                = 'Orders';
$lang['lims_subject_appointments']          = 'Appointments';
$lang['lims_subject_samples']               = 'Samples';
$lang['lims_subject_reports']               = 'Reports';
$lang['lims_subject_invoices']              = 'Invoices';
$lang['lims_subject_payments']              = 'Payments';
$lang['lims_subject_credit_notes']          = 'Credit notes';
$lang['lims_subject_files']                 = 'Files';
$lang['lims_subject_reminders']             = 'Reminders';

$lang['lims_subject_no_subjects_found']     = 'No subjects found.';
$lang['lims_subject_created_successfully']  = 'Subject created successfully.';
$lang['lims_subject_updated_successfully']  = 'Subject updated successfully.';
$lang['lims_subject_deleted_successfully']  = 'Subject deleted successfully.';
$lang['lims_subject_delete_confirm']        = 'Are you sure you want to delete this subject? Related orders will not be deleted.';

$lang['lims_subject_client_required']       = 'Please select or create a billing customer.';
$lang['lims_subject_validation_type_required'] = 'Subject type is required.';
$lang['lims_subject_validation_name_required'] = 'Name is required.';

$lang['lims_subject_tab_overview']          = 'Overview';
$lang['lims_subject_tab_orders']            = 'Orders';
$lang['lims_subject_tab_billing']           = 'Billing';
$lang['lims_subject_tab_files']             = 'Files';
$lang['lims_subject_tab_notes']             = 'Notes';
$lang['client_zip'] 						= 'Postcode';
$lang['client_country']   			        = 'Country';

$lang['lims_subject_customer_existing'] = 'Existing customer';
$lang['lims_subject_customer_new']      = 'New customer';
$lang['lims_subject_customer_none']     = 'No billing customer';

$lang['lims_subjects_help_text'] = 'Subjects represent the individuals or entities (patients, animals, farms, facilities, etc.) linked to LIMS orders. Each subject can optionally be linked to one or more billing customers.';
$lang['lims_subject_customer_link'] = 'Link to billing customer';
$lang['lims_subject_dob'] = 'Date of birth';
$lang['lims_subject_new'] = 'Create new Subject';
$lang['lims_settings_general']            = 'General';
$lang['lims_settings_general_help']       = 'Configure automatic numbering prefixes and other general LIMS settings.';

$lang['lims_subject_internal_code']       = 'Internal code';
$lang['lims_subject_prefix']              = 'Subject code prefix';
$lang['lims_subject_prefix_help']         = 'Prefix used when generating internal subject codes, e.g. SUB-, PAT-, FARM-.';
$lang['lims_subject_next_number']         = 'Next subject number';
$lang['lims_subject_next_number_help']    = 'The next sequential number that will be used for automatic subject code generation.';
$lang['lims_subject_code_help_auto'] = 'The internal code will be generated automatically when you save the subject.';
$lang['lims_subject_same_as_customer_info'] = 'Copy Details from Customer to Pantient';


$lang['lims_profile']          = 'Profile';
$lang['lims_order_delete_has_children'] = 'This order cannot be deleted because it has linked appointments, samples or tests.';

$lang['due_date']          = 'Due Date';

$lang['lims_status_draft']        = 'Draft';
$lang['lims_status_in_progress']  = 'In progress';
$lang['lims_status_appointment']  = 'Appointment scheduled';
$lang['lims_status_samples']      = 'Samples';
$lang['lims_status_reported']     = 'Reported';
$lang['lims_status_complete']     = 'Complete';
$lang['lims_status_canceled']     = 'Canceled';

// (προαιρετικά) activity messages
$lang['lims_action_status_changed'] = 'Status changed to %s';
$lang['lims_action_estimate_created'] = 'Estimate created from order.';
$lang['estimate_create'] = 'Create Estimate';
$lang['lims_subject_required_step1'] = 'Please select a subject in Step 1 before proceeding.';

// Order statuses
$lang['lims_order_status_draft']      = 'Draft';
$lang['lims_order_status_submitted']  = 'Submitted';
$lang['lims_order_status_accessioned']= 'Accessioned';
$lang['lims_order_status_testing']    = 'Testing';
$lang['lims_order_status_verified']   = 'Verified';
$lang['lims_order_status_approved']   = 'Approved';
$lang['lims_order_status_reported']   = 'Reported';
$lang['lims_order_status_completed']  = 'Completed';
$lang['lims_order_status_signed']     = 'Signed';
$lang['lims_order_status_canceled']   = 'Canceled';

// Test statuses (tbllims_tests.status / status_code)
$lang['lims_test_status_pending']     = 'Pending';
$lang['lims_test_status_in_progress']= 'In progress';
$lang['lims_test_status_completed']   = 'Completed';
$lang['lims_test_status_verified']    = 'Verified';
$lang['lims_test_status_signed']      = 'Signed';

$lang['lims_tests_open']      = 'Open';
$lang['lims_first_received']      = 'Received First';
$lang['lims_last_received']      = 'Received Last';

$lang['lims_priority_routine'] = 'Routine';
$lang['lims_priority_urgent']  = 'Urgent';
$lang['lims_priority_stat']    = 'STAT';
$lang['lims_priority_low']     = 'Low';

$lang['lims_signature']               = 'LIMS Signature';
$lang['lims_signature_image']         = 'Signature image';
$lang['lims_signature_hint']          = 'Upload a transparent PNG with your handwritten signature. It will be printed on LIMS reports you sign.';
$lang['lims_signature_saved']         = 'Signature saved successfully.';
$lang['lims_signature_upload_error']  = 'Error while uploading signature file.';
$lang['lims_signature_no_file']       = 'Please choose a signature file to upload.';

$lang['lims_sign_report']             = 'Sign report';
$lang['lims_sign_confirm']            = 'Sign this report? This will lock the results.';
$lang['lims_sign_not_ready_hint']     = 'Sign is available only when all Laboratory Tests and Cultures have results.';
$lang['lims_sign_not_ready_msg']      = 'Cannot sign: there are still open tests or cultures without results.';
$lang['lims_sign_success']            = 'Report signed successfully.';
$lang['lims_sign_failed']             = 'Failed to sign this report.';

$lang['lims_signed_section']          = 'Authorised signature';
$lang['lims_signed_at']               = 'Signed at';

$lang['lims_staff_signature']        = 'Signature';
$lang['lims_no_signature_uploaded']  = 'No signature uploaded.';
$lang['lims_staff_signature_image']  = 'Signature image';
$lang['lims_staff_signature_hint']   = 'Upload an image (preferably transparent PNG) of your signature.';

$lang['lims_staff_tab_title'] = 'LIMS';
$lang['lims_staff_signature_heading'] = 'Laboratory signature';
$lang['lims_staff_signature_title']   = 'Title / Position';
$lang['lims_staff_signature_lic']     = 'License / Registration no.';
$lang['lims_staff_signature_extra']   = 'Extra line (e.g. Department)';
$lang['lims_staff_signature_image']   = 'Signature image';
$lang['lims_staff_signature_save']    = 'Save LIMS signature';

$lang['lims_staff_tab_title']        = 'LIMS';
$lang['lims_staff_signature_label']  = 'Signature image';
$lang['lims_staff_signature_help']   = 'Upload a small PNG/JPG image of your handwritten signature. It will be printed on LIMS reports.';
$lang['lims_staff_signature_preview']= 'Current signature';
$lang['lims_staff_signature_current']= 'Current image:';
$lang['lims_staff_signature_none']   = 'No signature uploaded yet.';

$lang['lims_permissions_group_name']      = 'LIMS';
$lang['lims_permission_manage_orders']      = 'Manage LIMS orders';
$lang['lims_permission_manage_samples']     = 'Manage samples';
$lang['lims_permission_enter_results']      = 'Enter laboratory results';
$lang['lims_permission_verify']             = 'Verify results';
$lang['lims_permission_approve']           = 'Approve reports';
$lang['lims_permission_qc_manage']         = 'Manage quality control';
$lang['lims_permission_admin']             = 'LIMS administration';
$lang['lims_permission_billing']           = 'LIMS billing & invoicing';
$lang['lims_permission_appointments']      = 'Manage LIMS appointments';

// Quick Subject modal
$lang['lims_quick_add_subject']          = 'Quick add subject';
$lang['lims_subject_name']               = 'Subject name';
$lang['lims_subject_internal_code']      = 'Internal code';
$lang['lims_subject_id_number']          = 'ID / Passport';
$lang['lims_subject_dob']                = 'Date of birth';
$lang['lims_subject_nationality']        = 'Nationality';
$lang['lims_subject_gender']             = 'Gender';
$lang['lims_subject_social_insurance_no']= 'Social insurance no.';
$lang['lims_subject_type']               = 'Subject type';
$lang['lims_subject_type_patient']       = 'Patient';
$lang['lims_subject_type_donor']         = 'Donor';
$lang['lims_subject_type_employee']      = 'Employee';
$lang['lims_subject_type_other']         = 'Other';

// Client / contact fields reused in modal
$lang['client']              = 'Customer';
$lang['client_firstname']    = 'First name';
$lang['client_lastname']     = 'Last name';
$lang['client_phone']        = 'Phone';
$lang['client_email']        = 'Email';
$lang['client_address']      = 'Address';

// Quick Contract modal
$lang['lims_quick_add_contract']     = 'Quick add contract';
$lang['lims_contract']               = 'Contract';
$lang['lims_contract_hint']          = 'Select pricing/coverage contract (optional).';
$lang['lims_contract_name']          = 'Contract name';
$lang['lims_contract_start_date']    = 'Start date';
$lang['lims_contract_end_date']      = 'End date';
$lang['lims_description']            = 'Description';

// Generic
$lang['problem_creating']            = 'Problem while creating the record. Please try again.';

$lang['lims_quick_add_subject']              = 'Quick add Subject';
$lang['lims_subject_client']                 = 'Customer';
$lang['lims_subject_client_select']          = 'Select customer';
$lang['lims_subject_client_required_help']   = 'Customer is required to link the subject to billing.';
$lang['lims_subject_client_required_alert']  = 'Please select a customer.';

$lang['lims_subject_type']                   = 'Subject type';
$lang['lims_subject_type_patient']           = 'Patient';
$lang['lims_subject_type_doctor']            = 'Doctor';
$lang['lims_subject_type_lab']               = 'Laboratory';
$lang['lims_subject_type_farm']              = 'Farm';
$lang['lims_subject_type_restaurant']        = 'Restaurant';
$lang['lims_subject_type_other']             = 'Other';
$lang['lims_subject_type_required_alert']    = 'Please select a subject type.';

$lang['lims_subject_name']                   = 'Subject name';
$lang['lims_subject_internal_code']          = 'Internal code';
$lang['lims_subject_id_number']              = 'ID / Passport';
$lang['lims_subject_dob']                    = 'Date of birth';
$lang['lims_subject_city']                   = 'City';
$lang['lims_subject_zip']                    = 'Postal code';
$lang['lims_subject_city_required']          = 'City is required.';
$lang['lims_subject_zip_required']           = 'Postal code is required.';

$lang['lims_quick_subject_create_error']     = 'Error while creating subject. Please check required fields.';
$lang['lims_subject_client_optional_help'] = 'Optional. If left empty, a new customer and contact will be created automatically.';

$lang['lims_entry_mode']            = 'Order entry mode';
$lang['lims_entry_mode_tests']      = 'By tests / panels';
$lang['lims_entry_mode_samples']    = 'By samples';

$lang['lims_samples_required']      = 'You must add at least one sample.';
$lang['lims_sample_notes']          = 'Sample notes';
$lang['lims_analyses']              = 'Analyses';
$lang['lims_cultures']              = 'Cultures';
$lang['lims_add_sample']            = 'Add sample';
$lang['lims_entry_mode_samples_help'] = 'In this mode you define the samples first and then select which panels, analyses and cultures will be performed per sample. Billing still applies per test/panel/culture.';
$lang['lims_auto']                  = 'Auto';


$lang['lims_report_notes'] = 'Report Notes';
$lang['lims_report_notes_help'] = 'Create bilingual (EL/EN) notes that can be selected per Laboratory Test.';

$lang['lims_report_note'] = 'Report Note';
$lang['lims_report_note_new'] = 'New Note';
$lang['lims_report_note_edit'] = 'Edit Note';

$lang['lims_report_note_code'] = 'Code';
$lang['lims_report_note_code_optional'] = 'Code (optional)';

$lang['lims_report_note_greek'] = 'Greek';
$lang['lims_report_note_english'] = 'English';

$lang['lims_report_note_text_el'] = 'Greek text';
$lang['lims_report_note_text_en'] = 'English text';

$lang['lims_report_note_sort_order'] = 'Sort order';

$lang['lims_report_note_delete_confirm'] = 'Delete this note?';
$lang['lims_report_note_delete_failed'] = 'Delete failed.';
$lang['lims_report_note_save_failed'] = 'Save failed.';

$lang['lims_test_report_note_text'] = 'Report note (free text)';
$lang['lims_test_report_notes']     = 'Report Notes';
$lang['lims_test_report_notes_help']= 'Select which predefined notes will appear in the report.';

$lang['lims_report_notes'] = 'Σημειώσεις Report';
$lang['lims_report_notes_free_text'] = 'Κείμενο σημειώσεων (free text)';
$lang['lims_report_notes_free_text_help'] = 'Το κείμενο αυτό θα εμφανίζεται κάτω από τα αποτελέσματα στο Report.';
$lang['lims_report_notes_select'] = 'Επιλογή έτοιμων σημειώσεων';
$lang['lims_report_notes_select_help'] = 'Επίλεξε ποιες έτοιμες σημειώσεις θα εμφανίζονται στο Report (Σημ.:*).';
$lang['lims_report_notes_select_placeholder'] = 'Επίλεξε σημειώσεις...';

$lang['lims_report_pdf_header_section']    = 'Header';
$lang['lims_report_header_subtitle_el']    = 'Logo subtitle (Greek)';
$lang['lims_report_header_subtitle_en']    = 'Logo subtitle (English)';
$lang['lims_report_heading_el']            = 'Report heading (Greek)';
$lang['lims_report_heading_en']            = 'Report heading (English)';
$lang['lims_report_topright_line1']        = 'Top-right text – Line 1';
$lang['lims_report_topright_line2']        = 'Top-right text – Line 2';

$lang['lims_report_pdf_footer'] = 'Footer text';
$lang['lims_report_pdf_footer_help'] = 'This text will appear in the footer of the Report PDF. Multiple lines are supported.';

$lang['lims_report_pdf_logo'] = 'Custom Report Logo';
$lang['lims_report_pdf_logo_help'] = 'If you upload a logo here, it will be used in the Report PDF. If empty, the default company logo will be used.';
$lang['lims_report_pdf_logo_current'] = 'Current custom logo:';
$lang['lims_report_pdf_logo_remove'] = 'Remove custom logo';

$lang['lims_report_pdf_logo_width'] = 'Logo width (mm)';
$lang['lims_report_pdf_logo_x'] = 'Logo X (mm) (κενό = auto-center)';
$lang['lims_report_pdf_logo_y'] = 'Logo Y (mm)';

$lang['lims_report_pdf_footer_image_section'] = 'Footer image positioning';
$lang['lims_report_pdf_footer_image_section_help'] = 'Coordinates in mm for A4 (210x297). Adjust to align with your background.';

$lang['lims_report_footer_img_x'] = 'Footer image X (mm)';
$lang['lims_report_footer_img_y'] = 'Footer image Y (mm)';
$lang['lims_report_footer_img_w'] = 'Footer image width (mm)';

$lang['lims_report_pdf_footer_image'] = 'Footer image (upload)';
$lang['lims_report_pdf_footer_image_help'] = 'This image is placed at the bottom of each page of the Report PDF.';
$lang['lims_report_pdf_footer_image_current'] = 'Current footer image:';
$lang['lims_report_pdf_footer_image_remove'] = 'Remove footer image';

$lang['lims_report_pdf_footer_section'] = 'Footer';
$lang['lims_report_pdf_footer_section_help'] = 'Footer layout settings (units in mm).';

$lang['lims_report_footer_gap_mm'] = 'Zone spacing (mm)';
$lang['lims_report_footer_gap_mm_help'] = 'Spacing between pre-footer note / footer text (lang) / bottom footer text.';

$lang['lims_report_footer_bottom_margin_mm'] = 'Bottom margin for bottom footer text (mm)';
$lang['lims_report_footer_bottom_margin_mm_help'] = 'Distance from page bottom to the bottom edge of footer_text.';

$lang['lims_report_footer_line_thickness_mm'] = 'Green line thickness (mm)';
$lang['lims_report_footer_line_thickness_mm_help'] = 'Thickness of the line above the bottom footer text.';

$lang['lims_report_footer_line_color'] = 'Line color';
$lang['lims_report_footer_line_color_help'] = 'Use HEX (e.g. #009600) or RGB (e.g. 0,150,0).';

$lang['lims_report_footer_line_x1_mm'] = 'Line: X1 (mm)';
$lang['lims_report_footer_line_x2_mm'] = 'Line: X2 (mm)';

$lang['lims_report_footer_line_offset_mm'] = 'Line offset from bottom text (mm)';
$lang['lims_report_footer_line_offset_mm_help'] = 'How far above footer_text the line will be drawn.';

// Partner Sync (v3)
$lang['lims_partner'] = 'Partner';
$lang['lims_partner_order_hint'] = 'If selected, the order will be created as a Partner Order (samples mode enforced) and queued for sync.';

// --- Report PDF Settings (missing keys) ---
$lang['lims_report_pdf_texts'] = 'Texts';
$lang['lims_report_pdf_images'] = 'Images';

$lang['lims_report_language_from_subject'] = 'Language from Subject';
$lang['lims_report_default_language'] = 'Default language';

$lang['lims_report_show_signature'] = 'Show signature';
$lang['lims_report_signature_width_mm'] = 'Signature width (mm)';

$lang['lims_report_pdf_background_image'] = 'Background image (A4)';
$lang['lims_report_pdf_background_image_help'] = 'Used as full-page background.';
$lang['lims_report_pdf_background_image_current'] = 'Current background image:';
$lang['lims_report_pdf_background_image_remove'] = 'Remove background image';

$lang['lims_report_pre_footer_note_greek'] = 'Note before footer (Greek)';
$lang['lims_report_footer_text_greek'] = 'Footer text (Greek)';
$lang['lims_report_pre_footer_note_english'] = 'Note before footer (English)';
$lang['lims_report_footer_text_english'] = 'Footer text (English)';
$lang['lims_subject_details'] = 'Details';
$lang['customer_profile'] = 'Customer';
$lang['lims_subject_receipts_or_payments'] = 'Receipts';
$lang['delivery_notes'] = 'Delivery Notes';

$lang['lims_dashboard_overview'] = 'LIMS Overview';
$lang['lims_dashboard_orders_today'] = 'Orders today';
$lang['lims_dashboard_pending_samples'] = 'Pending samples';
$lang['lims_dashboard_tests_progress'] = 'Tests in progress';
$lang['lims_dashboard_ready_to_sign'] = 'Ready to sign';
$lang['lims_dashboard_overdue'] = 'Overdue';
$lang['lims_dashboard_completed_today'] = 'Completed today';
$lang['lims_dashboard_attention_orders'] = 'Orders requiring attention';
$lang['lims_dashboard_review_sign'] = 'Review';
$lang['lims_dashboard_todays_appointments'] = "Today's appointments";

$lang['lims_dashboard_orders_by_status'] = 'Orders by status';
$lang['lims_dashboard_recent_activity'] = 'Recent LIMS activity';

$lang['lims_dashboard_tests_by_department'] = 'Tests by department';
$lang['lims_dashboard_pending'] = 'Pending';
$lang['lims_dashboard_in_progress'] = 'In progress';
$lang['lims_dashboard_completed'] = 'Completed';
$lang['lims_dashboard_unassigned'] = 'Unassigned';

$lang['lims_dashboard_turnaround_time'] = 'Turnaround time';
$lang['lims_dashboard_last_30_days'] = 'Last 30 days';
$lang['lims_dashboard_average_completion'] = 'Average completion';
$lang['lims_dashboard_on_time'] = 'Completed on time';
$lang['lims_dashboard_completed_orders'] = 'Completed orders';
$lang['lims_dashboard_vs_previous_period'] = 'vs previous period';
$lang['lims_dashboard_activity_trend'] = 'Orders & samples trend';
$lang['lims_dashboard_last_14_days'] = 'Last 14 days';
$lang['lims_dashboard_reports'] = 'Reports';

$lang['lims_dashboard_critical_results'] = 'Critical & abnormal results';
$lang['lims_dashboard_no_abnormal_results'] = 'No abnormal results found.';
$lang['lims_dashboard_billing_summary'] = 'LIMS billing summary';
$lang['lims_dashboard_uninvoiced_orders'] = 'Uninvoiced orders';
$lang['lims_dashboard_draft_invoices'] = 'Draft invoices';
$lang['lims_dashboard_unpaid_invoices'] = 'Unpaid invoices';
$lang['lims_dashboard_overdue_invoices'] = 'Overdue invoices';

$lang['lims_dashboard_my_assigned_tests'] = 'My assigned tests';
$lang['lims_dashboard_no_assigned_tests'] = 'You have no pending assigned tests.';

$lang['lims_dashboard_samples_requiring_action'] = 'Samples requiring action';
$lang['lims_dashboard_next_action'] = 'Next action';
$lang['lims_dashboard_collect_sample'] = 'Collect sample';
$lang['lims_dashboard_receive_sample'] = 'Receive at lab';
$lang['lims_dashboard_no_pending_samples'] = 'No samples require action.';
