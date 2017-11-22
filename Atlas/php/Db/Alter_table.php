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
 * Alter table helper
 *
 * @author Roberto González Vázquez
 */
class Alter_table
{
    /** @var Db  Db object                       */  public $db          = NULL;
    /** @var string $table   Table name          */  public $table       = NULL;

    /** @var array  $changes Changes to perform  */  protected $changes  = NULL;

    /**
     * @param string $table Table name
     * @param Db     Instance or Db object, null:for default Db
     */
    public function __construct($table, $db_object = null)
    {
        $this->table = $table            ;
        $this->db    = $db_object ?? Atlas::$db;
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
     * @param string $engine 'MyISAM', 'InnoDB', 'Aria'
     * @return $this
     */
    public function Set_engine($engine)
    {
        $this->changes[] ="ENGINE=$engine";
        return $this;
    }


    /**
     * Set comment
     * @param string $comment
     * @return $this
     */
    public function Set_comment($comment)
    {
        $this->changes[] ="COMENT=$engine";
        return $this;
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

    public function Change_column($old_column_fame, $column)
    {
    CHANGE COLUMN
    }






    /*
     Add_column
    Add_key('id',TRUE);
    function Drop_index()
    function Add_index()*/


}


