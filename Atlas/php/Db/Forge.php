<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas\Db;

use Atlas;
use Atlas\Db;

/**
 * MySQL profiling.
 * Deprecated in php 5.7
 * @author Roberto González Vázquez
 */

class Forge extends Extension
{
    /**
     * Creates alter table helper.
     * @param type $table
     * @return Alter_table
     */
    public function Alter_table($table)
    {
        return new Alter_table($table, $this->db);
    }



    /**
     * Drops table.
     * @param string $table
     * @return int Affected rows
     */
    public function Drop_table($table)
    {
        return $this->db->Query_ar("DROP TAPLE `$table`;");
    }


    /**
     * Truncates table.
     * @param string $table
     * @return int Affected rows
     */
    public function Truncate_table($table)
    {
        return $this->db->Query_ar("TRUNCATE TABLE `$table`;");
    }



    /**
     * Drops database.
     * @param string $database_name
     * @return int Affected rows
     */
    public function Drop_database($database_name)
    {
        return $this->db->Query_ar("DROP DATABASE `$database_name`;");
    }

    /**
     * Creates a new data base.
     * @param string $database_name
     * @param string $collate Default collation, false equivalent if not collation
     * @return int Affected rows
     */
    public function Create_database($database_name, $collate='utf8_general_ci')
    {
        return $this->db->Query_ar(" CREATE DATABASE `$database_name` ". $collate ? " /*!40100 COLLATE '$collate' */;":';');
    }

}