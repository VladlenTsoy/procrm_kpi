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
    if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'view')) {
        $widgets[] = [
            'path' => 'procrm_kpi/widget',
            'container' => 'top-12',
        ];

        return $widgets;
    }
    return $widgets;
}

// Добавление в хедер
hooks()->add_action('app_admin_head', 'procrm_kip_add_head_components');

function procrm_kip_add_head_components()
{
    if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'view')) {
        echo '<link href="' . module_dir_url('procrm_kpi', 'assets/css/apexcharts.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url('procrm_kpi', 'assets/css/css-circular-prog-bar.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url('procrm_kpi', 'assets/css/procrm_kpi.css' . '?v=' . PROCRM_KPI_VERSIONING . '') . '"  rel="stylesheet" type="text/css" />';
    }
}

// Добавление в футер
hooks()->add_action('app_admin_footer', 'procrm_kip_load_js');

function procrm_kip_load_js()
{
    if (has_permission(PROCRM_KPI_MODULE_NAME, '', 'view')) {
        echo '<script src="' . module_dir_url('procrm_kpi', 'assets/js/apexcharts.min.js') . '"></script>';
        echo '<script src="' . module_dir_url('procrm_kpi', 'assets/js/procrm_kpi.js' . '?v=' . PROCRM_KPI_VERSIONING . '') . '"></script>';
    }
}

// Добавить в ролях права
hooks()->add_filter('staff_permissions', 'procrm_kpi_init_permissions');

/**
 * Добавить права в ролях
 * @param $data
 * @return mixed
 */
function procrm_kpi_init_permissions($data)
{
    $data[PROCRM_KPI_MODULE_NAME] = [
        'name' => _l('KPI'),
        'capabilities' => [
            'view' => _l('view'),
            'telephone' => _l('telephone'),
            'tasks' => _l('tasks'),
            'projects' => _l('projects'),
            'leads' => _l('leads'),
            'contracts' => _l('contracts'),
            'clients' => _l('clients'),
            'sales' => _l('sales'),
            'sales_pos' => _l('sales_pos'),
        ],
    ];

    return $data;
}

/**
 * Зарегистрируйте языковые файлы, необходимо зарегистрировать, если модуль использует языки
 */
register_language_files(PROCRM_KPI_MODULE_NAME, [PROCRM_KPI_MODULE_NAME]);