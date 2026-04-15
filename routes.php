<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * LIMS Module routes (admin area)
 * Προσοχή: τα paths είναι lowercase, οι controllers κεφαλαίοι (Perfex/CI HMVC).
 */

/* Root + Settings */
$route['admin/lims']                    = 'lims';
$route['admin/lims/settings']           = 'lims/settings';

/* Orders */
$route['admin/lims/orders']             = 'lims/orders';
$route['admin/lims/orders/create']      = 'lims/create_order';
$route['admin/lims/create_invoice/(:num)'] = 'lims/create_invoice/$1';

/* Contracts */
$route['admin/lims/contracts']                  = 'lims/contracts';
$route['admin/lims/contracts/create']           = 'lims/create_contract';
$route['admin/lims/contracts/create/(:num)']    = 'lims/create_contract/$1';
$route['admin/lims/save_contract']              = 'lims/save_contract';
$route['admin/lims/delete_contract/(:num)']     = 'lims/delete_contract/$1';
$route['admin/lims/toggle_contract_status']     = 'lims/toggle_contract_status';

/* Appointments (stubs/expand later) */
$route['admin/lims/appointments']               = 'lims/appointments';
$route['admin/lims/appointments/create']        = 'lims/create_appointment';

/* Sample Types */
$route['admin/lims/sampletypes']               = 'sampletypes/index';
$route['admin/lims/sampletypes/create']        = 'sampletypes/create';
$route['admin/lims/sampletypes/create/(:num)'] = 'sampletypes/create/$1';
$route['admin/lims/sampletypes/delete/(:num)'] = 'sampletypes/delete/$1';
$route['admin/lims/sampletypes/toggle_status'] = 'sampletypes/toggle_status';

/* Analyses */
$route['admin/lims/analyses']                  = 'analyses/index';
$route['admin/lims/analyses/create']           = 'analyses/create';
$route['admin/lims/analyses/create/(:num)']    = 'analyses/create/$1';
$route['admin/lims/analyses/delete/(:num)']    = 'analyses/delete/$1';
$route['admin/lims/analyses/toggle_status']    = 'analyses/toggle_status';

/* Panels (groups of analyses) */
$route['admin/lims/panels']                = 'panels';
$route['admin/lims/panels/create']         = 'panels/create';
$route['admin/lims/panels/create/(:num)']  = 'panels/create/$1';
$route['admin/lims/panels/delete/(:num)']  = 'panels/delete/$1';
$route['admin/lims/panels/toggle_status']  = 'panels/toggle_status';

/* Departments */
$route['admin/lims/departments']               = 'departments';
$route['admin/lims/departments/create']        = 'departments/create';
$route['admin/lims/departments/create/(:num)'] = 'departments/create/$1';
$route['admin/lims/departments/delete/(:num)'] = 'departments/delete/$1';
$route['admin/lims/departments/toggle_status'] = 'departments/toggle_status';
