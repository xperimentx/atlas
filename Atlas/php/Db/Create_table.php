<?php
/**
 * xperimentX atlas php toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 - 2018 Roberto González Vázquez
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
    public function __construct(string $table, Db $db_object = null)
    {
        $this->table = $table            ;
        $this->db    = $db_object ?? Db::Obj();

        if ($this->db && $this->db->cfg)
        {
            $cfg = $this->db->cfg;
            $this->engine  = $cfg->engine;
            $this->charset = $cfg->charset;
            $this->collate = $cfg->collation;
        }
    }


    /**
     * Adds a column field.
     * Create a column field sql helper
     * @param string $field_type
     *          Type of column:
     *          TINIYINT, INT, BIGINT , DECIMAL(10,2),
     *          CHAR(50), VARCHAR(50)
     *          DATE, TIME, DATETIME ...
     *
     * @param string $field_name Field name.  `` will be added.
     * @param scalar|null $default_value  Scalar default value.
     * @param bool  $is_null_allowed Can the value of this field can be null?
     *
     * @return Column Added column.
     */
    public function Add_column (string $field_type, string $field_name, $default_value=null, bool $is_null_allowed=true) :Column
    {
        $this->columns[] = $col = new Column($field_type, $field_name, $default_value, $is_null_allowed);
        return $col;
    }


    /**
     * Adds column auto increment  primary key.
     * @param bool $is_auto_increment
     * @param string $field_name
     * @return Column Added column
     */
    public function Add_column_id (string $field_name='id') :Column
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
     * Adds an index.
     * @param string  $index_name Index name. `` will be added.
     * @param string  $fields Coma separated field names.
     * @param string  $type Index type: normal, UNIQUE, FULLTEXT, SPATIAL
     */
    public function Add_index(string $index_name, string $fields , string $type=self::INDEX_NORMAL)
    {
        $this->items[]="$type INDEX `$index_name` ($fields)";
    }


    /**
     * Adds the primary key.
     * @param string  $fields Comas separated field names.
     */
    public function Add_primary_key (string $fields)
    {
        $this->items[]="PRIMARY KEY ($fields)";
    }


    const FOREIGN_KEY_RESTRICT    = 'RESTRICT'   ;
    const FOREIGN_KEY_CASCADE     = 'CASCADE'    ;
    const FOREIGN_KEY_SET_NULL    = 'SET NULL'   ;
    const FOREIGN_KEY_SET_DEFAULT = 'SET DEFAULT';


    /**
     * Adds a foreign key.
     * @param string|null $symbol Key name. `` will be added.
     * @param string $fields Coma separated field names.
     * @param string $foreign_table Foreign table.`` will be added.
     * @param string $foreign_fields Coma separated foreign  field names.
     * @param string $on_delete Reference option
     * @param string $on_update Reference option
     */
    public function Add_foreign_key ($symbol, string $fields, string $foreign_table, string $foreign_fields,
                                     string $on_delete=self::FOREIGN_KEY_NO_RESTRICT, string $on_update=self::FOREIGN_KEY_RESTRICT)
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
     * Runs create table query
     * @return int Affected rows.
     * @param $db Db Instance or Db object, null:for default DB::$db.
     * @return int Affected rows.
     */
    function Run() : int
    {
        return $this->db->Query_ar((string)$this);
    }


    /**
     * Runs create table query if not exists the table.
     * @return int Affected rows.
     */
    function Run_if_not_exists() :int
    {
        $this->if_not_exists = true;
        return $this->db->Query_ar((string)$this);
    }
}
