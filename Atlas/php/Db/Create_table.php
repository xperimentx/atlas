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
 * CREATE TABLE helper
 *
 * @author Roberto González Vázquez
 */
class Create_table
{
    /** @var Column[] Columns to add                 */  protected $columns      = [];
    /** @var string[] Items                          */  protected $items        = [];
    /** @var Db     Db object                        */  protected $db           = null;

    /** @var string Table name                       */  public $table           = null;
    /** @var string Charset.                         */  public $charset         = null;
    /** @var string Collation.                       */  public $collation       = null;
    /** @var string Engine                           */  public $engine          = null;
    /** @var string Auto increment.                  */  public $auto_increment  = null;
    /** @var string Comment.                         */  public $comment         = null;
    /** @var bool   Only creates table if not exists.*/  public $if_not_exists   = null;


    /**
     * @param string $table Table name. `` will be added.
     */
    public function __construct($table, $db_object = null)
    {
        $this->table = $table            ;
        $this->db    = $db_object ?? Db::$db;

        if ($this->db && $this->db->cfg)
        {
            $cfg = $this->db->cfg;
            $this->engine  = $cfg->engine;
            $this->charset = $cfg->charset;
            $this->collate = $cfg->collation;
        }
    }


    /**
     * Add a column field.
     * Create a column field sql helper
     * @param string $field_type
     *          Type of column:
     *          TINIYINT, INT, BIGINT , DECIMAL(10,2),
     *          CHAR(50), VARCHAR(50)
     *          DATE, TIME, DATETIME ...
     *
     * @param string $field_name Field name.  `` will be added.
     * @param scalar $default_value
     * @param bool  $is_null_allowed
     *
     * @return Column Added column.
     */
    public function Add_column ($field_type, $field_name, $default_value=NULL, $is_null_allowed=true)
    {
        $this->columns[] = $col = new Column($field_type, $field_name, $default_value, $is_null_allowed);
        return $col;
    }


    /**
     * Adds column auto increment  pirmary key.
     * @param bool $is_auto_increment
     * @param string $field_name
     * @return Column Added column
     */
    public function Add_column_id ($field_name='id')
    {
        $col = new Column('int', $field_name);
        $col->is_unsigned       = true;
        $col->is_auto_increment = true;
        $col->is_null_allowed   = false;

        $this->columns[] = $col;

        $this->Add_primary_key($field_name);

        return $col;
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
     */
    public function Add_index($index_name, $fields ,$type=self::INDEX_NORMAL)
    {
        $this->items[]="$type INDEX `$index_name` ($fields)";
    }


    /**
     * Adds the primary key
     * @param string  $fields Comas separated field names.
     */
    public function Add_primary_key ($fields)
    {
        $this->items[]="PRIMARY KEY ($fields)";
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
     */
    public function Add_foreign_key ($symbol, $fields, $foreign_table, $foreign_fields,
                                     $on_delete=self::FOREIGN_KEY_NO_RESTRICT, $on_update=self::FOREIGN_KEY_RESTRICT)
    {
        $cs= $symbol? "CONSTRAINT `$symbol`":'';
        $this->items[]="$cs FOREIGN KEY ($fields) REFERENCES `$foreign_table'(`$foreign_fields`)".
                       "ON DELETE $on_delete ON_UPDATE $on_update";
    }


    /**
     * Returns SQL for CREATE TABLE query
     */
    public function __toString ()
    {
        $sql = $this->if_not_exists
                ? 'CREATE TABLE IF NOT EXISTS '
                : 'CREATE TABLE ';

        $sql .= "`$this->table` ("
             . implode (",\n" , array_merge($this->columns, $this->items))
             . ') '   ;

        if (null!==$this->auto_increment)   $sql .= ' AUTO_INCREMENT='.$this->autoincrement;
        if (null!==$this->comment       )   $sql .= ' COMMENT \''.addslashes($this->comment).'\'';
        if (null!==$this->charset       )   $sql .= ' CHARSET=' .$this->charset;
        if (null!==$this->collation     )   $sql .= ' COLLATE=' .$this->collation;
        if (null!==$this->engine        )   $sql .= ' ENGINE='  .$this->engine;

        return $sql.";\n\n";
    }


    /**
     * Run create table query
     * @return int Affected rows.
     * @param $db Db Instance or Db object, null:for default DB::$db.
     */
    function Run()
    {
        return $this->db->Query_ar((string)$this);
    }


    /**
     * Run create table if not exist query
     * @return int Affected rows.
     */
    function Run_if_not_exists()
    {
        $this->if_not_exists = true;
        return $this->db->Query_ar((string)$this);
    }
}
