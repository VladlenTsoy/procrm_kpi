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
        $this->load->model('Procrm_kpi_tasks', 'kpi_tasks');
        $this->load->model('Procrm_voip_telephone', 'telephone_model');
        $this->load->model('Procrm_voip_asterisk_cdr_model', 'cdr_model');
    }


    public function index()
    {
        $post = $this->input->post();
        $calls = $this->getCalls($post);
        $tasks = $this->getTasks($post);
        $leads = $this->getLeads($post);

        echo json_encode([
            'calls' => $this->load->view('block', ['data' => $calls], true),
            'tasks' => $this->load->view('block', ['data' => $tasks], true),
            'leads' => $leads
        ], JSON_NUMERIC_CHECK);
    }

    /**
     * Звонки
     * @param $post
     * @return array
     */
    public function getCalls($post)
    {
        $telephones = $this->telephone_model->get();
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
                $where[] = "(src IN (" . $staff->sip_telephone . ") OR dstchannel IN (" . $staff->sip_telephone . ") OR dst IN (" . $staff->sip_telephone . ") OR cnum IN (" . $staff->sip_telephone . "))";
        }

        // Фильтрация по дате
        if (isset($post['from_date']) && $post['from_date'] !== '' || isset($post['to_date']) && $post['to_date'] !== '') {
            $dateFrom = isset($post['from_date']) && $post['from_date'] ? $post['from_date'] : '0000-00-00';
            $dateTo = isset($post['to_date']) && $post['to_date'] !== '' ? date('Y-m-d', strtotime($post['to_date'] . ' + 1 day')) : date('Y-m-d', strtotime('+ 1 day'));
            $where[] = "(calldate BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "')";
        }

        list($count, $answered, $noAnswered, $outgoing, $incoming) = $this->cdr_model->getCdr($where);

        return [
            'total' => $count,
            'view' => [
                'missed' => [
                    'name' => _l('Пропущенных'),
                    'value' => $noAnswered,
                    'color' => 'danger',
                ],
                'answered' => [
                    'name' => _l('Отвеченных'),
                    'value' => $answered,
                    'color' => 'success',
                ],
                'outgoing' => [
                    'name' => _l('Исходящих'),
                    'value' => $outgoing,
                    'color' => 'primary',
                ],
                'incoming' => [
                    'name' => _l('Входящих'),
                    'value' => $incoming,
                    'color' => 'primary',
                ],
            ]
        ];
    }

    /**
     * Вывод заданий
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
            'view' => [
                'notStarted' => [
                    'value' => $notStarted,
                    'name' => _l('task_status_1'),
                    'color' => 'primary'
                ],
                'progress' => [
                    'value' => $progress,
                    'name' => _l('task_status_4'),
                    'color' => 'primary'
                ],
                'check' => [
                    'value' => $check,
                    'name' => _l('task_status_3'),
                    'color' => 'primary'
                ],
                'waiting' => [
                    'value' => $waiting,
                    'name' => _l('task_status_2'),
                    'color' => 'primary'
                ],
                'completed' => [
                    'value' => $completed,
                    'name' => _l('task_status_5'),
                    'color' => 'primary'
                ]
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
            'status' => $this->load->view('block', ['data' => [
                'total' => $this->kpi_leads->getAll($where),
                'view' => $this->kpi_leads->getStatus($where)
            ]], true),
            'source' => $this->load->view('block', ['data' => [
                'total' => $this->kpi_leads->getAll($where),
                'view' => $this->kpi_leads->getSource($where)
            ]], true),
        ];
    }
}