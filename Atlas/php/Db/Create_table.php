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
 * Create table helper
 *
 * @author Roberto González Vázquez
 */
class Create_table
{
    /** @var array  $changes Changes to perform    */  protected $items        = null;

    /** @var Db  Db object                         */  public $db              = null;
    /** @var string $table   Table name            */  public $table           = null;
    /** @var string Charset.                       */  public $charset         = null;
    /** @var string Collation.                     */  public $collation       = null;
    /** @var string Engine                         */  public $engine          = null;
    /** @var string Autoincrement                  */  public $autoincrement   = null;
    /** @var string Comment                        */  public $comment         = null;


    public $comment = null ;

    /**
     * @param string $table Table name
     * @param Db     Instance or Db object, null:for default Db
     */
    public function __construct($table, $db_object = null)
    {
        $this->table = $table            ;
        $this->db    = $db_object ?? Atlas::$db;

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
        $sql = "CREATE TABLE `$this->table` ("
             . implode (',' ,$this->items)
             . ')'   ;

        if (null!==$this->autoincrement)   $sql .= ' AUTO_INCREMENT='.$this->autoincrement;
        if (null!==$this->comment      )   $sql .= ' COMMENT \''.addslashes($this->comment  ).'\'';
        if (null!==$this->charset      )   $sql .= ' CHARSET=' .$this->charset;
        if (null!==$this->collation    )   $sql .= ' COLLATE=' .$this->collation;
        if (null!==$this->engine       )   $sql .= ' ENGINE='  .$this->engine;
    }

}
