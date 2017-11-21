<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas\Mysql;

/**
 * Alter table helper
 *
 * @author Roberto González Vázquez
 */
class Alter_table
{
    /** @var \Atlas\Mysql  Db\Mysqli object      */  public $db          = NULL;
    /** @var string $table   Table name          */  public $table       = NULL;

    /** @var array  $changes Changes to perform */  protected $changes  = NULL;


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
     * Changes table engine
     * @param string $engine 'MyISAM', 'InnoDB'
     * @return $this
     */
    public function Set_engine($engine)
    {
        $this->changes[] ="ENGINE=$engine";
        return $this;
    }


    /**
     * Changes table engine to InnoDB.
     * @return $this
     */
    public function Set_engine_innodb()
    {
        return $this->Set_engine('InnoDB');
    }


    /**
     * Changes table engine to MyISAM
     * @return $this
     */
    public function Set_engine_myisam()
    {
        return $this->Set_engine('MyISAM');
    }



    /**
     * Run alter table query
     * @return int Affected rows.
     */
    function Run()
    {
        return $this->db->Query_ar("ALTER TABLE `$this->table` ". join(', ', $this->changes).';');
    }


    /**
     * Add a field
     * @param Column $column
     */
    public function Add_column ($column)
    {

        $this->changes[] ="ADD COLUMN ". $column->Render_sql();
        return $this;
    }





    /*
     Add_column
    Add_key('id',TRUE);
    function Drop_index()
    function Add_index()*/


}


