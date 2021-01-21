<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Api extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('staff_model');
        $this->load->model('Procrm_voip_telephone', 'telephone_model');
        $this->load->model('Procrm_voip_asterisk_cdr_model', 'cdr_model');
    }


    public function index()
    {
        $post = $this->input->post();
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

        echo json_encode([
            'calls' => [
                'total' => $count,
                'missed' => $noAnswered,
                'answered' => $answered,
                'outgoing' => $outgoing,
                'incoming' => $incoming,
            ]
        ], JSON_NUMERIC_CHECK);
    }
}