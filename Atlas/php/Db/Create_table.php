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
 * CREATE TABLE helper
 *
 * @author Roberto González Vázquez
 */
class Create_table
{
    /** @var Column[] Columns to add  */  protected $columns      = [];
    /** @var string[] Items           */  protected $items        = [];

    /** @var Db     Db object         */  public $db              = null;
    /** @var string Table name        */  public $table           = null;
    /** @var string Charset.          */  public $charset         = null;
    /** @var string Collation.        */  public $collation       = null;
    /** @var string Engine            */  public $engine          = null;
    /** @var string Autoincrement     */  public $autoincrement   = null;
    /** @var string Comment           */  public $comment         = null;


    /**
     * @param string $table Table name
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


    function Render_sql()
    {
        $cols= [];

        foreach($this->columns as $col)
            $cols[]=$col->Render_sql();


        $sql = "CREATE TABLE `$this->table` ("
             . implode (',' , array_merge($cols,$this->items))
             . ')'   ;

        if (null!==$this->autoincrement)   $sql .= ' AUTO_INCREMENT='.$this->autoincrement;
        if (null!==$this->comment      )   $sql .= ' COMMENT \''.addslashes($this->comment).'\'';
        if (null!==$this->charset      )   $sql .= ' CHARSET=' .$this->charset;
        if (null!==$this->collation    )   $sql .= ' COLLATE=' .$this->collation;
        if (null!==$this->engine       )   $sql .= ' ENGINE='  .$this->engine;

        return $sql;
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
     * @param string $field_name
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
     * Returns SQL for CREATE TABLE query
     * @return int Affected rows.
     */
    function __toString ()
    {
        $sql = "CREATE TABLE `$this->table` ("
             . implode (",\n" , array_merge($this->columns, $this->items))
             . ') '   ;

        if (null!==$this->autoincrement)   $sql .= ' AUTO_INCREMENT='.$this->autoincrement;
        if (null!==$this->comment      )   $sql .= ' COMMENT \''.addslashes($this->comment).'\'';
        if (null!==$this->charset      )   $sql .= ' CHARSET=' .$this->charset;
        if (null!==$this->collation    )   $sql .= ' COLLATE=' .$this->collation;
        if (null!==$this->engine       )   $sql .= ' ENGINE='  .$this->engine;

        return $sql.";\n\n";
    }


    /**
     * Run alter table query
     * @return int Affected rows.
     * @param $db Db Instance or Db object, null:for default DB::$db.
     */
    function Run($db)
    {
        return ($db ?? DB::$db)->Query_ar((string)$this);
    }

}



