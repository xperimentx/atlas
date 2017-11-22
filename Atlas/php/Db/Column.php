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

/**
 * Column helper of alter table and  create table.
 * @author Roberto González Vázquez
 */
class Column
{
    public $collation         = null ;
    public $comment           = null ;
    public $default           = null ;
    public $default_raw       = null ;
    public $field_name        = null ;
    public $is_auto_increment = null ;
    public $is_null_allowed   = true ;
    public $is_unsigned       = false;
    public $is_zerofill       = false;
    public $length            = null ;
    public $type              = null ;
    //public $unique            = null ;


    /**
     * Render field part for alter table and  create table ....
     * @return string SQL
     */
    function Render_sql()
    {
        $out = "`$this->field_name` $this->type";

        if ($this->length)                 $out .="($this->length)";
        if ($this->is_unsigned)            $out .=' UNSIGNED';
        if ($this->is_zerofill)            $out .=' ZEROFILL';

                                           $out .= $this->null_allowed ? ' NULL': ' NOT NULL';

        if     ($this->is_auto_increment)  $out .= ' AUTO_INCREMENT';
        elseif ($this->default_raw)        $out .= ' '.$this->default_raw;
        elseif (null!==$this->default)     $out .= ' DEFAULT T \''.addslashes($this->default ).'\'';
        elseif ($this->is_null_allowed)    $out .= ' DEFAULT NULL';

        if ($this->comment)                $out .=' COMMENT \''.addslashes($this->comment  ).'\'';
        if ($this->collation)              $out .=" COLLATE $this->collation";
    }



    /** Sets $collation         */ public function Set_collation     ($value)      {  $this->collation       = $value; return $this; }
    /** Sets $comment           */ public function Set_comment       ($value)      {  $this->comment         = $value; return $this; }
    /** Sets $default           */ public function Set_default       ($value)      {  $this->default         = $value; return $this; }
    /** Sets $field_name        */ public function Set_field_name    ($value)      {  $this->field_name      = $value; return $this; }
    /** Sets $is_auto_increment */ public function Set_auto_increment($value)      {  $this->auto_increment  = $value; return $this; }
    /** Sets $is_null_allowed   */ public function Set_nullable      ($value=true) {  $this->is_null_allowed = $value; return $this; }
    /** Sets $is_unsigned       */ public function Set_unsigned      ($value=true) {  $this->is_unsigned     = $value; return $this; }
    /** Sets $is_zerofill       */ public function Set_zerofill      ($value=true) {  $this->is_zerofill     = $value; return $this; }
    /** Sets $length            */ public function Set_length        ($value)      {  $this->length          = $value; return $this; }
    /** Sets $type              */ public function Set_type          ($value)      {  $this->type            = $value; return $this; }
    /** Sets unique             */ public function Set_unique        ($value)      {  $this->unique          = $value; return $this; }


    /** Basic builder */
    public static function Basic ($type, $field_name, $default_value=null, $length=null)
    {
        $obj =  new static ();
        $obj->field_name = $field_name;
        $obj->type       = $type;
        $obj->lenght     = $length;
        $obj->default    = $default_value;
        return $obj;
    }


    /** Basic builder for Char     */ public static function Char     ($field_name, $default_value=null, $length=250      ) { return  static::Basic('CHAR'    , $field_name, $default_value, $length);}
    /** Basic builder for Date     */ public static function Date     ($field_name, $default_value=null                   ) { return  static::Basic('DATA'    , $field_name, $default_value         );}
    /** Basic builder for Datetime */ public static function Datetime ($field_name, $default_value=null                   ) { return  static::Basic('DATETINE', $field_name, $default_value         );}
    /** Basic builder for Decimal  */ public static function Decimal  ($field_name, $default_value=null, $length='10,2'   ) { return  static::Basic('DECIMAL' , $field_name, $default_value, $length);}
    /** Basic builder for Int      */ public static function Int      ($field_name, $default_value=null, $length='11'     ) { return  static::Basic('INT'     , $field_name, $default_value, $length);}
    /** Basic builder for Text     */ public static function Text     ($field_name, $default_value=null                   ) { return  static::Basic('TEXT'    , $field_name, $default_value         );}
    /** Basic builder for Tinyint  */ public static function Tinyint  ($field_name, $default_value=null, $length='4'      ) { return  static::Basic('TINYINT' , $field_name, $default_value, $length);}
    /** Basic builder for Varchar  */ public static function Varchar  ($field_name, $default_value=null, $length=250      ) { return  static::Basic('VARCHAR' , $field_name, $default_value, $length);}
}