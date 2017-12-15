<?php
/**
 * xperimentX atlas php toolkit
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

/**
 * Column helper of alter table and  create table.
 * @author Roberto González Vázquez
 */
class Column
{
    public $collation         = null ;
    public $comment           = null ;
    public $default_value     = null ;
    public $default_raw       = null ;
    public $field_name        = null ;
    public $is_auto_increment = null ;
    public $is_null_allowed   = true ;
    public $is_unsigned       = false;
    public $is_zerofill       = false;
    public $type              = null ;
    public $old_field_name    = null ;


    /**
     * Creates a column field sql helper
     * @param string $field_type
     *          Type of column:
     *          TINIYINT, INT, BIGINT , DECIMAL(10,2),
     *          CHAR(50), VARCHAR(50)
     *          DATE, TIME, DATETIME ...
     *
     * @param string $field_name Field name.  `` will be added.
     * @param scalar $default_value
     * @param bool  $is_null_allowed Can the value of this field can be null?
     *
     * @return Column Added column.
     */
    function __construct (string $field_type, string $field_name, $default_value=null, bool $is_null_allowed=true)
    {
        $this->type            = $field_type;
        $this->field_name      = $field_name;
        $this->default_value   = $default_value;
        $this->is_null_allowed = $is_null_allowed;
    }


    /**
     * Returns a string whit a SQL segment for add or change column in ALTER TABLE or CREATE TABLE sentences.
     * @return string SQL
     */
    function __toString()
    {
        $out = "`$this->field_name` $this->type";

        if ($this->is_unsigned)              $out .=' UNSIGNED';
        if ($this->is_zerofill)              $out .=' ZEROFILL';

                                             $out .= $this->is_null_allowed ? ' NULL': ' NOT NULL';

        if     ($this->is_auto_increment)    $out .= ' AUTO_INCREMENT';
        elseif ($this->default_raw)          $out .= ' '.$this->default_raw;
        elseif (null!==$this->default_value) $out .= ' DEFAULT \''.addslashes($this->default_value ).'\'';
        elseif ($this->is_null_allowed)      $out .= ' DEFAULT NULL';

        if ($this->comment)                  $out .=' COMMENT \''.addslashes($this->comment).'\'';
        if ($this->collation)                $out .=" COLLATE $this->collation";

        return $out;
    }


    /**
     * Sets an attribute
     * @return $this
     */
    public function Set(string $attribute_name, $value)
    {
        $this->$attribute_name = $value;
        return $this;
    }


    /**
     * Sets $collation
     * @return $this
     */
    public function Set_collation(string $value)
    {
        $this->collation = $value;
        return $this;
    }


    /**
     * Sets $comment
     * @return $this
     */
    public function Set_comment(string $value)
    {
        $this->comment = $value;
        return $this;
    }


    /**
     * Sets $default_value
     * @return $this
     */
    public function Set_default_value($value)
    {
        $this->default_value = $value;
        return $this;
    }


    /**
     * Sets $default_raw
     * @return $this
     */
    public function Set_default_raw($value)
    {
        $this->default_raw = $value;
        return $this;
    }


    /**
     * Sets $field_name
     * @return $this
     */
    public function Set_field_name(string $value)
    {
        $this->field_name = $value;
        return $this;
    }


    /**
     * Sets $is_auto_increment
     */
    public function Set_auto_increment($value = true)
    {
        $this->auto_increment = $value;
        return $this;
    }


    /**
     * Sets $is_null_allowed
     * @return $this
     */
    public function Set_nullable(bool $value = true)
    {
        $this->is_null_allowed = $value;
        return $this;
    }


    /**
     * Sets $is_unsigned
     * @return $this
     */
    public function Set_unsigned(bool $value = true)
    {
        $this->is_unsigned = $value;
        return $this;
    }


    /**
     * Sets $is_zerofill
     * @return $this
     */
    public function Set_zerofill(bool $value = true)
    {
        $this->is_zerofill = $value;
        return $this;
    }


    /**
     * Sets $type
     * @param @value string Column type INT, VARCHAR(50) , DATETIME ....
     * @return $this
     */
    public function Set_type(string $value)
    {
        $this->type = $value;
        return $this;
    }
}