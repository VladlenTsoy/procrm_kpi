<?php


class Procrm_kpi_projects extends App_Model
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
        $selectQuery = 'SELECT COUNT(' . db_prefix() . 'projects.id) FROM ' . db_prefix() . 'projects';
        $sQuery = $selectQuery;

        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db->query($sQuery)->row();

        // Не начата
        $query_1 = $selectQuery;
        $query_1 .= ' WHERE status = 1 AND ' . $where;

        $count_1 = $this->db->query($query_1)->row();

        // В процессе
        $query_4 = $selectQuery;
        $query_4 .= ' WHERE status = 4 AND ' . $where;

        $count_4 = $this->db->query($query_4)->row();

        // На проверке
        $query_3 = $selectQuery;
        $query_3 .= ' WHERE status = 3 AND ' . $where;

        $count_3 = $this->db->query($query_3)->row();

        // В ожидании ответа
        $query_2 = $selectQuery;
        $query_2 .= ' WHERE status = 2 AND ' . $where;

        $count_2 = $this->db->query($query_2)->row();

        // Завершена
        $query_5 = $selectQuery;
        $query_5 .= ' WHERE status = 5 AND ' . $where;

        $count_5 = $this->db->query($query_5)->row();

        return [
            $count->{'COUNT(' . db_prefix() . 'projects.id)'},
            $count_1->{'COUNT(' . db_prefix() . 'projects.id)'},
            $count_4->{'COUNT(' . db_prefix() . 'projects.id)'},
            $count_3->{'COUNT(' . db_prefix() . 'projects.id)'},
            $count_2->{'COUNT(' . db_prefix() . 'projects.id)'},
            $count_5->{'COUNT(' . db_prefix() . 'projects.id)'},
        ];
    }
}