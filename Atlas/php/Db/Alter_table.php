<?php
/**
 * xperimentX Atlas Toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 Roberto González Vázquez
 *
 * @license   MIT
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
     * @param string $table Table name. `` will be added.
     * @param Db     Instance or Db object, null:for default Db
     */
    public function __construct($table, $db_object = null)
    {
        $this->table = $table            ;
        $this->db    = $db_object ?? Db::$db;
    }


    /**
     * Renames the table
     * @param string $new_table_name. `` will be added.
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
     * @return string
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
     * Adds a field / column.
     * @param Column $column
     */
    public function Add_column ($column)
    {
        $this->changes[] ="ADD COLUMN ". $column->Render_sql();
        return $this;
    }

    /**
     * Changes a column
     * @param string $old_column_name
     * @param Column $column
     * @return $this
     */
    public function Change_column ($old_column_name, $column)
    {
        $this->changes[] ="CHANGE COLUMN `$old_column_name` ". $column->Render_sql();
        return $this;
    }


    /**
     * Drops a column or a set of columns from the table
     * @param string $field Field name of column. `` will be added.
     * @return $this
     */
    public function  Drop_column ($field)
    {
        $this->changes[] ="DROP COLUMN `$field`";
        return $this;
    }


    const INDEX_NORMAL   = '';
    const INDEX_UNIQUE   = 'UNIQUE';
    const INDEX_SPATIAL  = 'SPATIAL';
    const INDEX_FULLTEXT = 'FULLTEXT';


    /**
     * Adds an index
     * @param string  $index_name Index name. `` will be added.
     * @param string  $fields Coma separated field names.
     * @param string  $type Index type: normal, UNIQUE, FULLTEXT, SPATIAL
     * @return $this
     */
    public function Add_index($index_name, $fields ,$type=self::INDEX_NORMAL)
    {
        $this->items[]="$type INDEX `$index_name` ($fields)";
        return $this;
    }


    /**
     * Drops an index
     * @param string $index_name Index name. `` will be added.
     * @return $this
     */
    public function  Drop_index ($index_name)
    {
        $this->changes[] ="DROP INDEX `$index_name`";
        return $this;
    }


    /**
     * Adds the primary key
     * @param string  $fields Comas separated field names.
     * @return $this
     */
    public function Add_primary_key ($fields)
    {
        $this->changes[]="ADD PRIMARY KEY ($fields)";
        return $this;
    }


    /**
     * Drops the primary key
     * @return $this
     */
    public function  Drop_primary_key ($index_name)
    {
        $this->changes[] ="DROP PRIMARY KEY";
        return $this;
    }


    const FOREIGN_KEY_RESTRICT    = 'RESTRICT'   ;
    const FOREIGN_KEY_CASCADE     = 'CASCADE'    ;
    const FOREIGN_KEY_SET_NULL    = 'SET NULL'   ;
    const FOREIGN_KEY_SET_DEFAULT = 'SET DEFAULT';


    /**
     * Adds a foreign key
     * @param string|null $symbol Key name. `` will be added.
     * @param string $fields Coma separated field names.
     * @param string $foreign_table Foreign table.`` will be added.
     * @param string $foreign_fields Coma separated foreign  field names.
     * @param string $on_delete Reference option
     * @param string $on_update Reference option
     * @return $this
     */
    public function Add_foreign_key ($symbol, $fields, $foreign_table, $foreign_fields,
                                     $on_delete=self::FOREIGN_KEY_NO_RESTRICT, $on_update=self::FOREIGN_KEY_RESTRICT)
    {
        $cs= $symbol? "CONSTRAINT `$symbol`":'';
        $this->changes[]="ADD $cs FOREIGN KEY ($fields) REFERENCES `$foreign_table'(`$foreign_fields`)".
                       "ON DELETE $on_delete ON_UPDATE $on_update";
        return $this;
    }


    /**
     * Drops a foreign key
     * @param string $symbol  Constraint symbol
     * @return $this
     */
    public function Drop_foreign_key ($symbol)
    {
        $this->changes[]="DROP FOREIGN KEY `$symbol` ";
        return $this;
    }
}


