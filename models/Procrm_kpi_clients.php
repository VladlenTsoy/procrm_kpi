<?php


class Procrm_kpi_clients extends App_Model
{
    /**
     * Вывод всех
     * @param $where
     * @return array
     */
    public function get($where)
    {
        $where = implode(" AND ", $where);

        // Всего
        $selectQuery = 'SELECT COUNT(' . db_prefix() . 'clients.userid) FROM ' . db_prefix() . 'clients';
        $sQuery = $selectQuery;

        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db->query($sQuery)->row();

        // Не начата
        $query_1 = $selectQuery;
        $query_1 .= ' WHERE active = 1 AND ' . $where;

        $count_1 = $this->db->query($query_1)->row();

        // В процессе
        $query_4 = $selectQuery;
        $query_4 .= ' WHERE active = 0 AND ' . $where;

        $count_4 = $this->db->query($query_4)->row();

        return [
            $count->{'COUNT(' . db_prefix() . 'clients.userid)'},
            $count_1->{'COUNT(' . db_prefix() . 'clients.userid)'},
            $count_4->{'COUNT(' . db_prefix() . 'clients.userid)'},
        ];
    }
}