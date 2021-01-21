<?php


class Procrm_voip_asterisk_cdr_model extends App_Model
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

    public function getCdr($where)
    {
        $where = implode(" AND ", $where);

        // Всего
        $sQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db_asterisk->query($sQuery)->row();

        // Отвеченных
        $anQuery = 'SELECT COUNT(cdr.clid) FROM cdr';
        $anQuery .= ' WHERE disposition = "ANSWERED" AND ' . $where;

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
}