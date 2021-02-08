<?php


class Procrm_kpi_asterisk_cdr_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

        // Подлючение бд asterisk
        $config['hostname'] = get_option('asterisk_hostname');
        $config['port'] = get_option('asterisk_port');
        $config['username'] = get_option('asterisk_username');
        $config['password'] = get_option('asterisk_password');
        $config['database'] = get_option('asterisk_database');
        $config['dbdriver'] = 'mysqli';
        $config['db_debug'] = false;

        $this->db_asterisk = $this->load->database($config, true);
    }

    public function getCountByDisposition($where)
    {
        $where = implode(" AND ", $where);

        // Всего
        $sQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db_asterisk->query($sQuery)->row();

        // Отвеченных
        $anQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        $anQuery .= ' WHERE disposition = "ANSWERED" AND amaflags = 2 AND ' . $where;

        $anCount = $this->db_asterisk->query($anQuery)->row();

        // Пропущенных
        $noAnQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        $noAnQuery .= ' WHERE disposition = "NO ANSWER" AND amaflags = 2 AND ' . $where;

        $noAnCount = $this->db_asterisk->query($noAnQuery)->row();

        // Исходящих
        $outQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        $outQuery .= ' WHERE amaflags = 3 AND ' . $where;

        $outQuery = $this->db_asterisk->query($outQuery)->row();

        // Входящих
        $inQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        $inQuery .= ' WHERE amaflags = 2 AND ' . $where;

        $inQuery = $this->db_asterisk->query($inQuery)->row();

        return [
            $count->{'COUNT(cdr.clid)'},
            $anCount->{'COUNT(cdr.clid)'},
            $noAnCount->{'COUNT(cdr.clid)'},
            $outQuery->{'COUNT(cdr.clid)'},
            $inQuery->{'COUNT(cdr.clid)'},
        ];
    }

    public function getCountByWeek($where)
    {
        $where = implode(" AND ", $where) . ' GROUP BY WEEKDAY(`calldate`)';

        // Всего
        $sQuery = 'SELECT COUNT(cdr.clid), WEEKDAY(calldate) as week FROM cdr';
        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db_asterisk->query($sQuery)->result_array();

        // Отвеченных
        $anQuery = 'SELECT COUNT(cdr.clid), WEEKDAY(calldate) as week FROM cdr';
        $anQuery .= ' WHERE disposition = "ANSWERED" AND amaflags = 2 AND ' . $where;

        $anCount = $this->db_asterisk->query($anQuery)->result_array();

        // Пропущенных
        $noAnQuery = 'SELECT COUNT(cdr.clid), WEEKDAY(calldate) as week FROM cdr';
        $noAnQuery .= ' WHERE disposition = "NO ANSWER" AND amaflags = 2 AND ' . $where;

        $noAnCount = $this->db_asterisk->query($noAnQuery)->result_array();

        // Исходящих
        $outQuery = 'SELECT COUNT(cdr.clid), WEEKDAY(calldate) as week FROM cdr';
        $outQuery .= ' WHERE amaflags = 3 AND ' . $where;

        $outQuery = $this->db_asterisk->query($outQuery)->result_array();

        // Входящих
        $inQuery = 'SELECT COUNT(cdr.clid), WEEKDAY(calldate) as week FROM cdr';
        $inQuery .= ' WHERE amaflags = 2 AND ' . $where;

        $inQuery = $this->db_asterisk->query($inQuery)->result_array();

        return [
            $count,
            [
                $anCount,
                $noAnCount,
                $outQuery,
                $inQuery,
            ]
        ];
    }
}