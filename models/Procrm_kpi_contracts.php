<?php


class Procrm_kpi_contracts extends App_Model
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
        $selectQuery = 'SELECT COUNT(' . db_prefix() . 'contracts.id) FROM ' . db_prefix() . 'contracts';
        $sQuery = $selectQuery;

        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db->query($sQuery)->row();

        // Активность
        $query_1 = $selectQuery;
        $query_1 .= ' WHERE (DATE(dateend) >"' . date('Y-m-d') . '" OR DATE(dateend) IS NULL) AND trash=0 AND ' . $where;

        $count_1 = $this->db->query($query_1)->row();

        // Истек срок действия
        $query_4 = $selectQuery;
        $query_4 .= ' WHERE DATE(dateend) >"' . date('Y-m-d') . '" AND trash=0 AND ' . $where;

        $count_4 = $this->db->query($query_4)->row();

        // Истекает срок действия
        $days = 7;
        $diff1 = date('Y-m-d', strtotime('-' . $days . ' days'));
        $diff2 = date('Y-m-d', strtotime('+' . $days . ' days'));

        $query_5 = $selectQuery;
        $query_5 .= ' WHERE dateend >= ' . $diff1 . ' AND dateend <= ' . $diff2 . ' AND trash=0 AND ' . $where;

        $count_5 = $this->db->query($query_5)->row();

        // Недавно добавленные
        $days = 7;
        $diff1 = date('Y-m-d', strtotime('-' . $days . ' days'));
        $diff2 = date('Y-m-d', strtotime('+' . $days . ' days'));

        $query_6 = $selectQuery;
        $query_6 .= ' WHERE dateadded BETWEEN "' . $diff1 . '" AND "' . $diff2 . '" AND trash=0 AND ' . $where;

        $count_6 = $this->db->query($query_6)->row();

        return [
            $count->{'COUNT(' . db_prefix() . 'contracts.id)'},
            $count_1->{'COUNT(' . db_prefix() . 'contracts.id)'},
            $count_4->{'COUNT(' . db_prefix() . 'contracts.id)'},
            $count_5->{'COUNT(' . db_prefix() . 'contracts.id)'},
            $count_6->{'COUNT(' . db_prefix() . 'contracts.id)'},
        ];
    }
}