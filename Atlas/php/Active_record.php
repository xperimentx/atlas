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

namespace Xperimentx\Atlas;

/**
 * Active record - Active Record Base
 *
 * Id, primary key, auto numeric.
 *
 * Attributes that begin with '_' do not belong to the record in the database,
 * they will be configuration properties or auxiliary properties.
 *
 * Load_from** functions call  On_load() when they load successfully.
 *
 * Model setup:
 * - Redeclare $_table to set the model table.
 * - If name in not your name field redeclare $_name_field;
 * - In not use the default Db object redeclare $_db.
 * - In your object use Obj_* cache redeclare  $_obj_cache_used_by_default.
 *
 *
  * <b> Cache during the execution: </ b>
 *
 * _obj_cache_used_by_default = TRUE, when Obj_load($id) is called the object is registered,
 * if an object with the same id is requested again, the previously loaded object is returned.
 * You must redeclare this property*
 *
 *
 * Field names.
 * id            Required. Primary key.
 * date_created. Opcional. Date time
 * date_modified Opcional. Last modification date
 *
 *
 * @author Roberto González Vázquez
 */
class Active_record
{
     /** @var int Primary key, auto increment */
    public $id  = NULL ;

    /** @var string Db table name. Redeclare in your model */
    static public $_table = NULL;

    /** @var string  Name field. Redeclare in your model if not is name */
    static public $_name_field    =  'name';

    /** @var Db  Database. Redeclare in not use the default Db*/
    static public $_db=null;

    /** @var bool  Use obj cache by default. Redeclare if needed.     */
    static protected $_obj_cache_used_by_default = FALSE;

    /** @var Active_record[] Object cache     */
    static private $_obj_cache = [];


    /**
     *
     * @param NULL|int   int: id - primary key.
     *                   Null: new record;
     *
     */
    function __construct($id=null)
    {
        if(null!==$id)
            $this->Load_from_id($id);
    }


    /**
    * Automatically assign values from a vector or from  an object.
    *
    * Ignores index/attributes that begin with '_'.
    *
    * Attempt to use setters Set_{index/attibute} methods.
    *
    * @param array|object $data
    */
    public function Assign($data)
    {
        if(!$data or !(is_array($data) or is_object($data)))
            return;

        foreach ($data as $index => $value)
        {
            if  ($index[0]==='_') continue;

            if     (method_exists  ($this, "Set_$index"))   $this->{"Set_$index"}($value);
            elseif (property_exists($this, $index))         $this->$index = $value;
        }
    }


    /**
     * Loads data from db an object or an array.
     * Calls <b>On_load</b> when it loads successfully.
     * @param array|object|null $data
     * @return bool
     * @see Assing()
     */
    public function Load_from_data($data)
    {
        $this->id=null;

        $this->Assign_data($data);


        if (!is_null($this->id))
        {
            $this->On_load();
            return true;
        }

        return false;
    }


    /**
     * Loads data from db.
     *
     * Calls <b>On_load</b> when it loads successfully.
     *
     * @param string $where_sql Safe WHERE statement.
     *                          SELECT * FROM `$this->_table` WHERE $where_sql
     *
     * @return bool
     */
    public function Load_from_where($where_sql)
    {
        return $this->Load_from_data(static::$_db->Row("SELECT * FROM `$this->_table` WHERE $where_sql"));
    }


    /**
     * Loads data from an Id
     * Calls <b>On_load</b> when it loads successfully.
     * @param int Primary key
     * @return bool
     */
    public function Load_from_id($id)
    {
        return $this->Load_from_where('id='.(int)$id);
    }


    /**
     * Loads data from an unique field value
     * @param string $field_name
     * @param misc $field_value
     * @return bool
     */
    static public function Load_from_field($field_name, $field_value)
    {
        return $this->Load_from_where("`$field_name`=".static::$_db->Safe($field_value));
    }

    /**
     * Gets a new object.
     * @return static
     */
     static public function Obj_new()
    {
        return new static;
    }

    /**
     * Gets a object from db.
     *
     * If not data returns null.
     *
     * @param string $where_sql Safe WHERE statement.
     *                          SELECT * FROM `$this->_table` WHERE $where_sql
     *
     * @param bool|NULL $use_cache Use cache, Null=>$_cache_used_by_default
     *
     * @return static|null
     */
    static public function  Obj_where($where_sql, $use_cache=null)
    {
        if ($use_cache or $use_cache===NULL && static::$_obj_cache_used_by_default )
        {
            $cached_id=get_called_class().' - '.$where_sql;

            if (isset(self::$_obj_cache[$cached_id]))
            return self::$_obj_cache[$cached_id];
            $obj = self::$_obj_cache[$cached_id] = new static();
        }
        else $obj = new static();

        $obj->Load_from_where($where_sql);

        return is_null($obj->id) ? null: $obj;
    }


    /**
     * Gets a object from db using Id.
     * @param int Primary key
     * @return bool
     */
    public function Obj_id($id)
    {
        return static::Obj_where('id='.(int)$id);
    }


    /**
     * Gets a object from db using a field value.
     * @param string $field_name
     * @param misc $field_value
     * @return bool
     */
    static public function Obj_field($field_name, $field_value)
    {
        return static::Obj_where("`$field_name`=".static::$_db->Safe($field_value));
    }


    /**
     * Deletes current record fro db.
     * Calls on_delete if successfully.
     * @return bool
     */
    public function Delete()
    {
        if (!$this->Can_delete())
            return false;

        if (static::$_db->Query('DELETE FROM `'.static::$_table."` WHERE id=".(int)$this->id))
        {
            $this->On_delete();
            return true;
        }

        return false;
    }


    /**
     * Deletes records from db. Do not call On_delete().
     *
     * @param string $where_sql Safe WHERE statement.
     *                          DELETE FROM `$this->_table` WHERE $where_sql
     * @return int
     */
    public function Delete_where($where_sql)
    {
        return static::$_db->Query_ar('DELETE FROM `'.static::$_table."` WHERE $where_sql");
    }


    public function Truncate()
    {
        return static::$_db->Truncate_table($this->_table);
    }


    /**
     * Vector id=>name
     * SELECT id, $field_name name FROM `$_table` $sql_extra ORDER BY $order_by
     *
     * @return array
     */
    public function Vector($field_name=null, $sql_extra=NULL, $order_by='2')
    {
        return DB::$db->Vector("SELECT id, $field_name name
                                FROM `".static::$_table."`
                                $sql_extra
                                ORDER BY $order_by");
    }


    /**
     * Vector id=>name
     * @return array
     */
    static public function Vector_name($sql_extra=NULL, $order_by='2')
    {
        return static::Vector (static::$_name_field, $sql_extra, $order_by);
    }


    static public function Scalar_where($field_name,$where_sql)
    {
        return static::$_db->Scalar("SELECT $field_name FROM `".static::$_table."` WHERE $where_sql");
    }


    static public function Scalar_from_id($field_name)
    {
        return static::Scalar_where($field_name, 'id='.(int)$this->id);
    }


    /**
     * Gets row using this sql query:
     * SELECT $fields  FROM `_table`  $extra_sql
     *
     * @param string $fields     Fields, columns: null=>_table.*
     * @param string $extra_sql  Extra sql after FROM _table: joins, where, order by...
     * @return array
     */
    static public function Rows_fields($fields=null, $extra_sql=null,  $class_name='stdClass')
    {
        $fields_x = $fields ?: "`$this->_table`.*";

        return static::$_db->Rows ("SELECT $fields_x FROM `".static::$_table."` $extra_sql;", $class_name);
    }


    /**
     * Gets row using this sql query:
     * SELECT `$_table`.* FROM `$_table`  $extra_sql
     *
     * @param string $extra_sql  Extra sql after FROM _table: joins, where, order by...
     * @return static[]
     */
    static public function Rows ($extra_sql=null)
    {
        return static::$_db->Rows ("SELECT `$this->_table`.* FROM `".static::$_table."` $extra_sql;", get_called_class());
    }


    /**
     * Saves data
     *
     * If $id !== NULL Update
     * IF $id === NULL Insert, id will be update whit the new insert id
     * @return bool
     * @see Update_query()
     * @see Update_field()
     */
    public function Save()
    {
        $is_new =   (null == $this->id );

        if ($is_new && property_exists($this, 'date_created'))
            $this->date_created = date('Y-m-d H:i:s');

        if (!$is_new && property_exists($this, 'date_modified'))
            $this->date_modified = date('Y-m-d H:i:s');

        //preparación
        if (!$this->On_save_preparation($is_new)) return false;

        $data=array();


        foreach($this as $field=>$value)
        {
            if  (substr($field,0,1)=='_')     continue;  //internas
            $data[$field]=$value;
        }


        if (static::$_db->Update_or_insert(static::$_table, $data, $this->id, 'id', true))
        {
            $this->On_save($is_new);
            return  true;
        }

        return false;
    }


    /**
     * Updates only the selected field names.
     * Do not call On_save();
     * @param string[] $fields_names
     * @param bool $update_mdate_modified
     * @return boolean
     */
    public function Save_field_set($fields_names, $update_mdate_modified=true)
    {
        if ($this->id === null) return false;

        $data = [];

        foreach ($fields_names as  $index)
        {
            if  ($index[0]==='_') continue;
            elseif (property_exists($this, $index))         $data[$index] =$this->$index;
        }

        if (!$update_mdate_modified && property_exists($this, 'date_modified'))
            $data['date_modified'] = date('Y-m-d H:i:s');

        return static::Update_query($data,'id='.(int)$this->id, true);
    }


    /**
     * Updates a field of the current record and saves only this field in db
     * @param string $field_name
     * @param misc   $value
     * @return boolean
     */
    public function Update_field($field_name,$value)
    {
        $this->$field_name = $value;
        return static::Update_query([$field_name=>$value],'id='.(int)$$this->id,true);

    }


    /**
     * Update statement
     * Do not calls to On_save()
     * @param array   $data     Data. Index:field name.  Value:field value.
     * @param string  $where    WHERE conditions
     * @param bool    $do_safe  This values will be processed by Safe().
     * @return int|null         Affected rows or null if error
     */
    static public function Update_query($data, $where, $do_safe)
    {
        return static::$_db->Update(static::$_table, $data, $where, $do_safe);
    }


    /**
     * Checks if the value of the field is unique in the table.
     * @param string $field_name   Name of column to check.
     * @param string $value        Value to check.
     * @return bool
     */
    public function Is_unique_field($field_name, $value)
    {
        return static::$_db->Is_unique(static::$_table, $field_name, $value, $this->id, 'id');
    }


    /* Hooks -----------------------------------------------------------------------------------------------------------

    /**
     * Checks if the current record in the table can be deleted
     * @return boolean
     */
    public function Can_delete()
    {
        // Default, you can´t delete id=null (new record) or id=0.
        // Id=0 is reserved

        return (bool)$this->id;
    }


    /**
     * Called when Delete() was successful.
     */
    protected function On_delete()
    {

    }


    /**
     * Called when Load_from() was successful.
     * Constructor and Obj_*() use Load_*().
     */
    protected function On_load()
    {

    }


    /**
     * Called when Save() was successful.
     * @param type $is_new TRUE: new record, create; FALSE: update
     */
    protected function On_save($is_new)
    {

    }


    /**
     * Called when it is performed at the beginning of a "save" call, before processing the data.     
     *
     * Abort the execution of the save if it returns false.     
     * @param type $is_new TRUE: new record, create; FALSE: update     
     * @return bool TRUE: you can continue with the save. FALSE: the save must be aborted.
     */
    protected function On_save_preparation($is_new)
    {
        return true;
    }
}

//Initialize db
Active_record::$_db = DB::$db;
