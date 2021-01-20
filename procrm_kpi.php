<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
    Module Name: PROCRM KPI Module
    Description: PROCRM KPI module - Key Performance Indicator.
    Author: Tsoy Vladlen
    Author URI: http://procrm.uz
    Version: 1.0.0
    Requires at least: 2.3.*
*/

// Название модуля
define('PROCRM_KPI_MODULE_NAME', 'procrm_kpi');

// Вывод виджета
hooks()->add_filter('get_dashboard_widgets', 'procrm_kpi_add_dashboard_widget');

function procrm_kpi_add_dashboard_widget($widgets)
{
    $widgets[] = [
        'path'      => 'procrm_kpi/widget',
        'container' => 'top-12',
    ];

    return $widgets;
}
