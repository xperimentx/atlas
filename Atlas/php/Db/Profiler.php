<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Xperimentx\Atlas\Db;

/**
 * MySQL profiling.
 * Deprecated in php 5.7
 * @author Roberto González Vázquez
 */
class Profiler extends Extension
{
    protected function beguin()
    {
        $this->db->Query("SET profiling_history_size=100");
        $this->db->Query("SET profiling=1");
    }

    /**
     * Returns profiles for queries
     * @return array {Query_ID, Duration, Query}[]
     */
    public function Show_profiles()
    {
        return $this->db->Rows_objects('SHOW PROFILES');
    }


    /**
     * Returns profile for a query
     * @return array {Status, Duration}[]
     */
    public function Show_profile($query_id)
    {
        return $this->db->Rows_objects('SHOW PROFILE ALL FOR QUERY '.(int)$query_id);
    }
}