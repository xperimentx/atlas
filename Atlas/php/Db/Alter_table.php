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

use Xperimentx\Atlas\Db;

/**
 * ALTER TABLE helper
 *
 * @author Roberto González Vázquez
 */
class Alter_table
{
    /** @var Db  Db object                       */
    public $db          = NULL;

    /** @var string $table   Table name          */
    public $table       = NULL;

    /** @var string[]|Column[]  $changes Changes to perform  */
    protected $changes  = [];


    /**
     * @param string $table Table name. `` are added.
     * @param Db     Instance or Db object, null:for default Db
     */
    public function __construct($table, $db_object = null)
    {
        $this->table = $table            ;
        $this->db    = $db_object ?? Db::$db;
    }


    /**
     * Drops a column or a set of columns from the table
     * @param string $field Field name of column. `` are added.
     * @return $this
     */
    public function  Drop_column ($field)
    {
        $this->changes[] ="DROP COLUMN `$field`";
        return $this;
    }


     /**
     * Drops an index
     * @param string $index_name Index name. `` are added.
     * @return $this
     */
    public function  Drop_index ($index_name)
    {
        $this->changes[] ="DROP INDEX `$index_name`";
        return $this;
    }



    /**
     * Renames the table
     * @param string $new_table_name. `` are added.
     * @return $this
     */
    public function Rename ($new_table_name)
    {
        $this->changes[] ="RENAME `$new_table_name`";
        return $this;
    }



    /**
     * Changes table engine
     * @param string $engine 'MyISAM', 'InnoDB', 'Aria'
     * @return $this
     */
    public function Set_engine ($engine)
    {
        $this->changes[] ="ENGINE=$engine";
        return $this;
    }


    /**
     * Set comment
     * @param string $comment
     * @return $this
     */
    public function Set_comment ($comment)
    {
        $this->changes[] ='COMENT=\''. addslashes($comment).'\'';
        return $this;
    }


    /**
     * Returns SQL for ALTER TABLE query
     * @return int Affected rows.
     */
    function __toString ()
    {
        $items = [];

        foreach ($this->changes as $change)
        {
            if     (is_string($change))      $items [] = $change;
            elseif ($change->old_field_name) $items [] = "CHANGE COLUMN `$change->old_column_name` $change";
            else                             $items [] = "ADD COLUMN  $change";
        }

        return "ALTER TABLE `$this->table` ". join(",\n ", $items).";\n\n";
    }

    /**
     * Run alter table query
     * @return int Affected rows.
     */
    function Run()
    {
        return $this->db->Query_ar((string)$this);
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

    public function Change_column ($old_column_name, $column)
    {
        $this->changes[] ="CHANGE COLUMN `$old_column_name` ". $column->Render_sql();
        return $this;
    }






    /*
     Add_column
    Add_key('id',TRUE);
    function Drop_index()
    function Add_index()*/


}


