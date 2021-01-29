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
// Версия
define('PROCRM_KPI_VERSIONING', '1.0.0');

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

// Добавление в хедер
hooks()->add_action('app_admin_head', 'procrm_kip_add_head_components');

function procrm_kip_add_head_components()
{
    echo '<link href="' . module_dir_url('procrm_kpi', 'assets/css/apexcharts.css') . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . module_dir_url('procrm_kpi', 'assets/css/css-circular-prog-bar.css') . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . module_dir_url('procrm_kpi', 'assets/css/procrm_kpi.css' . '?v=' . PROCRM_KPI_VERSIONING . '') . '"  rel="stylesheet" type="text/css" />';
}

// Добавление в футер
hooks()->add_action('app_admin_footer', 'procrm_kip_load_js');

function procrm_kip_load_js()
{
    echo '<script src="' . module_dir_url('procrm_kpi', 'assets/js/apexcharts.min.js') . '"></script>';
    echo '<script src="' . module_dir_url('procrm_kpi', 'assets/js/procrm_kpi.js' . '?v=' . PROCRM_KPI_VERSIONING . '') . '"></script>';
}