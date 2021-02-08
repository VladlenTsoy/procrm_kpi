<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Api extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('staff_model');
        $this->load->model('Procrm_kpi_leads', 'kpi_leads');
        $this->load->model('Procrm_kpi_clients', 'kpi_clients');
        $this->load->model('Procrm_kpi_tasks', 'kpi_tasks');
        $this->load->model('Procrm_kpi_projects', 'kpi_projects');
        $this->load->model('Procrm_kpi_contracts', 'kpi_contracts');
        $this->load->model('Procrm_kpi_telephones', 'kpi_telephones');
        $this->load->model('Procrm_kpi_asterisk_cdr_model', 'kpi_cdr');
    }


    public function index()
    {
        $post = $this->input->post();
        $calls = has_permission(PROCRM_KPI_MODULE_NAME, '', 'telephone') ? $this->getCalls($post) : false;
        $tasks = has_permission(PROCRM_KPI_MODULE_NAME, '', 'tasks') ? $this->getTasks($post) : false;
        $leads = has_permission(PROCRM_KPI_MODULE_NAME, '', 'leads') ? $this->getLeads($post) : false;
        $projects = has_permission(PROCRM_KPI_MODULE_NAME, '', 'projects') ? $this->getProjects($post) : false;
        $clients = has_permission(PROCRM_KPI_MODULE_NAME, '', 'clients') ? $this->getClients($post) : false;
        $contracts = has_permission(PROCRM_KPI_MODULE_NAME, '', 'contracts') ? $this->getContracts($post) : false;

        echo json_encode([
            'calls' => $calls,
            'tasks' => $tasks,
            'leads' => $leads,
            'projects' => $projects,
            'clients' => $clients,
            'contracts' => $contracts,
        ], JSON_NUMERIC_CHECK);
    }

    /**
     * Звонки
     * @param $post
     * @return array
     */
    public function getCalls($post)
    {
        $telephones = $this->kpi_telephones->get();
        $where = [];

        $_telephones = [];
        // Телефонные номера
        foreach ($telephones as $telephone)
            $_telephones[] = $telephone['telephone'];
        if (count($_telephones)) {
            $_numbers = implode("', '", $_telephones);
            $where[] = "(src IN ('{$_numbers}') OR dst IN ('{$_numbers}'))";
        }

        // Сортировка по сотрудникам
        if (isset($post['staff_ids']) && $post['staff_ids'] !== '') {
            $staff = $this->staff_model->get($post['staff_ids']);
            if ($staff)
                $where[] = "(src = " . $staff->sip_telephone . " OR dstchannel LIKE '%" . $staff->sip_telephone . "%' OR dst = " . $staff->sip_telephone . " OR cnum = " . $staff->sip_telephone . ")";
        }
//        $staff = $this->staff_model->get('', "phonenumber LIKE '%" . $tel . "%'");

        // Фильтрация по дате
        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(calldate BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        list($count, $answered, $noAnswered, $outgoing, $incoming) = $this->kpi_cdr->getCountByDisposition($where);
        list($total, $types) = $this->kpi_cdr->getCountByWeek($where);

        $a = [];
        $weekDays = [0, 1, 2, 3, 4, 5, 6];
        if ($types) {
            foreach ($types as $type => $typeVal) {
                $a[$type] = $a[$type] ?? [];
                foreach ($weekDays as $day) {
                    $a[$type][$day] = isset($a[$type][$day]) ? $a[$type][$day] : 0;
                    $a[$type][$day] = $a[$type][$day] + (isset($typeVal[$day]['COUNT(cdr.clid)']) ? $typeVal[$day]['COUNT(cdr.clid)'] : 0);
                }
            }
        }


        return [
            'data' => [
                'total' => $count,
                'radialBar' => [
                    'labels' => [
                        _l('Входящих') . ' ' . $incoming,
                        _l('Исходящих') . ' ' . $outgoing,
                        _l('Отвеченных') . ' ' . $answered,
                        _l('Пропущенных') . ' ' . $noAnswered
                    ],
                    'series' => [
                        $incoming ? round($incoming / ($count / 100)) : $incoming,
                        $outgoing ? round($outgoing / ($count / 100)) : $outgoing,
                        $answered ? round($answered / ($count / 100)) : $answered,
                        $noAnswered ? round($noAnswered / ($count / 100)) : $noAnswered
                    ],
                ],
                'growth' => [
                    'series' => (array)$a
                ]
            ]
        ];
    }

    /**
     * Вывод заданий
     * @param $post
     * @return array
     */
    public function getTasks($post)
    {
        $where = ['id = id'];

        if (isset($post['staff_ids']) && $post['staff_ids'] !== '') {
            $where[] = "(id IN (SELECT taskid FROM " . db_prefix() . "task_assigned WHERE staffid IN (" . $post['staff_ids'] . ")))";
        }

        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(startdate BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        list($count, $notStarted, $progress, $check, $waiting, $completed) = $this->kpi_tasks->get($where);

        return [
            'total' => $count,
            'data' => [
                'names' => [_l('task_status_1'), _l('task_status_4'), _l('task_status_3'), _l('task_status_2'), _l('task_status_5')],
                'values' => [$notStarted, $progress, $check, $waiting, $completed]
            ],
        ];
    }


    /**
     * Вывод проект
     * @return array
     */
    public function getProjects($post)
    {
        $where = ['id = id'];

        if (isset($post['staff_ids']) && $post['staff_ids'] !== '') {
            $where[] = "(id IN (SELECT project_id FROM " . db_prefix() . "project_members WHERE staff_id IN (" . $post['staff_ids'] . ")))";
        }

        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(start_date BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        list($count, $notStarted, $progress, $check, $waiting, $completed) = $this->kpi_projects->get($where);

        return [
            'total' => $count,
            'data' => [
                'names' => [_l('project_status_1'), _l('project_status_4'), _l('project_status_3'), _l('project_status_2'), _l('project_status_5')],
                'values' => [$notStarted, $progress, $check, $waiting, $completed]
            ],
        ];
    }

    /**
     * Лиды
     * @param $post
     * @return array
     */
    public function getLeads($post)
    {
        $where = ['is_public = 0'];

        if (isset($post['staff_ids']) && $post['staff_ids'] !== '') {
            $where[] = "(assigned IN (" . $post['staff_ids'] . "))";
        }

        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(dateadded BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        return [
            'statuses' => $this->kpi_leads->getStatus($where),
            'sources' => $this->kpi_leads->getSource($where)
        ];
    }


    /**
     * @param $post
     * @return array
     */
    public function getClients($post)
    {
        $where = ['userid = userid'];

        if (isset($post['staff_ids']) && $post['staff_ids'] !== '') {
            $where[] = "(userid IN (SELECT customer_id FROM " . db_prefix() . "customer_admins WHERE staff_id IN (" . $post['staff_ids'] . ")))";
        }

        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(datecreated BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        list($count, $active, $notActive) = $this->kpi_clients->get($where);

        return [
            'total' => $count,
            'view' => [
                'active' => [
                    'value' => $active,
                    'name' => _l('Активных'),
                    'color' => 'success'
                ],
                'notActive' => [
                    'value' => $notActive,
                    'name' => _l('Неактивных'),
                    'color' => 'danger'
                ],
            ],
        ];
    }


    /**
     * @param $post
     * @return array
     */
    public function getContracts($post)
    {
        $where = ['id = id'];

        if (isset($post['staff_ids']) && $post['staff_ids'] !== '') {
            $where[] = "(addedfrom IN (" . $post['staff_ids'] . "))";
        }

        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(dateadded BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        list($count, $active, $_2, $_3, $_4) = $this->kpi_contracts->get($where);

        return [
            'total' => $count,
            'data' => [
                'names' => [_l('Активность'), _l('Истек срок действия'), _l('Истекает срок действия'), _l('Недавно добавленные')],
                'values' => [$active, $_2, $_3, $_4]
            ]
        ];
    }
}