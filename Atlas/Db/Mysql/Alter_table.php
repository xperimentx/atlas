<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas\Db\Mysql;

/**
 * Error info for Mysql::$errors items
 *
 * @author Roberto González Vázquez
 */

class Alter_table
{
    /**var \Atlas\Db\Mysql  Db\Mysqli object    */  public $db          = NULL;
    /**@var string $table   Table name          */  public $table       = NULL;

    /**@var array  $changes Chasnges to perform */  protected $changes  = NULL;


    public function __construct($table, $db_mysqli_object)
    {
        $this->table = $table            ;
        $this->db    = $db_mysqli_object ;
    }


    /**
     * Drops a column or a set of columns from the table
     * @param string $field Field name of column
     * @return $this
     */
    public function  Drop_column ($field)
    {
        $this->changes[] ="DROP COLUMN `$field`";
        return $this;
    }

    /**
     * Renames the table
     * @param string $new_table_name
     * @return $this
     */
    public function Rename($new_table_name)
    {
        $this->changes[] ="RENAME `$new_table_name`";
        return $this;
    }



    /**
     * Run alter table query
     */
    function Run()
    {
        return $this->db->Query("ALTER TABLE `$this->table` ". join(', ', $this->changes).';');
    }

}