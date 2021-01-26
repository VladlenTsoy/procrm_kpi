<?php


class Procrm_kpi_leads extends App_Model
{
    /**
     * Статусы
     * @param $where
     * @return array
     */
    public function getStatus($where)
    {
        $statusCount = [];
        $where = implode(" AND ", $where);

        // Всего
        $selectQuery = 'SELECT COUNT(' . db_prefix() . 'leads.id) FROM ' . db_prefix() . 'leads';
        $statuses = $this->db->get(db_prefix() . 'leads_status')->result_array();

        foreach ($statuses as $status) {
            //
            $query = $selectQuery;
            $query .= ' WHERE source = ' . $status['id'] . ' AND ' . $where;

            $sCount = $this->db->query($query)->row();
            $statusCount[$status['id']] = [
                'name' => $status['name'],
                'value' => $sCount->{'COUNT(' . db_prefix() . 'leads.id)'},
                'color' => 'primary'
            ];
        }

        return $statusCount;
    }

    /**
     * Откуда
     * @param $where
     * @return array
     */
    public function getSource($where)
    {
        $sourceCount = [];
        $where = implode(" AND ", $where);

        // Всего
        $selectQuery = 'SELECT COUNT(' . db_prefix() . 'leads.id) FROM ' . db_prefix() . 'leads';
        $sources = $this->db->get(db_prefix() . 'leads_sources')->result_array();

        foreach ($sources as $source) {
            //
            $query = $selectQuery;
            $query .= ' WHERE source = ' . $source['id'] . ' AND ' . $where;

            $sCount = $this->db->query($query)->row();
            $sourceCount[$source['id']] = [
                'name' => $source['name'],
                'value' => $sCount->{'COUNT(' . db_prefix() . 'leads.id)'},
                'color' => 'primary'
            ];
        }

        return $sourceCount;
    }

    /**
     * @param $where
     * @return array
     */
    public function getAll($where)
    {
        $where = implode(" AND ", $where);
        $sQuery = 'SELECT COUNT(' . db_prefix() . 'leads.id) FROM ' . db_prefix() . 'leads';

        if ($where)
            $sQuery .= ' WHERE ' . $where;

        $count = $this->db->query($sQuery)->row();

        return $count->{'COUNT(' . db_prefix() . 'leads.id)'};
    }
}