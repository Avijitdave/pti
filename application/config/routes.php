<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Pti';
$route['trans/(:any)'] = 'Pti/trans/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
