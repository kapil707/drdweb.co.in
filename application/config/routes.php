<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'main';
$route['login'] = 'user/login';
$route['logout'] = 'user/logout';


$route['chemist/android/Api_mobile30/insert_temp_order/(:any)'] = 'android/Api_mobile30/insert_temp_order/$1';
$route['chemist/android/Api_mobile30/get_online_cart/(:any)'] = 'android/Api_mobile30/get_online_cart/$1';
$route['chemist/android/Api_mobile30/get_online_cart2/(:any)'] = 'android/Api_mobile30/get_online_cart2/$1';
$route['chemist/android/Api_mobile30/delete_temp_order/(:any)'] = 'android/Api_mobile30/delete_temp_order/$1';
$route['chemist/android/Api_mobile30/deleteall_temp_order/(:any)'] = 'android/Api_mobile30/deleteall_temp_order/$1';
$route['chemist/android/Api_mobile30/save_order_to_server/(:any)'] = 'android/Api_mobile30/save_order_to_server/$1';


$route['invoice/(:any)/(:any)'] = 'invoice/index/$1/$2';
$route['invoice_download/(:any)/(:any)'] = 'invoice/invoice_download/$1/$2';
$route['all_invoice'] = 'User/local_server_all_invoice';
$route['pickedby'] = 'User/local_server_pickedby';
$route['deliverby'] = 'User/local_server_deliverby';

$route['category/(:any)'] = 'Category/index/$1';
$route['category/medicine_item_wise/(:any)']= 'Category/medicine_item_wise/$1';
$route['category/featured_brand/(:any)/(:any)']= 'Category/featured_brand/$1/$2';

// setting route for admin
$route['admin']='admin/admin';
$route['admin/logout']='admin/admin/logout';
//$route['admin'] = 'admin/auth';
$route['index'] = 'index/auth';
$route['autocomplete'] = 'index/auth/searchauto';
$route['autocomplete-another'] = 'index/auth/searchanother';
$route['get-data'] = 'index/auth/getting_value';
$route['calculate-data'] = 'index/auth/calculate_data';
$route['admin/dashboard2'] = 'admin/dashboard/index2';
$route['adminlte'] = 'admin/auth';
$route['adminlte/(:any)'] = 'admin/adminlte/$1';
$route['myinv/(:any)/(:any)/(:any)'] = 'api3/myinv/$1/$2/$3';


$route['check_sms'] = 'sms/index';
$route['top_sales_medicines'] = 'top_sales_medicines/index';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;