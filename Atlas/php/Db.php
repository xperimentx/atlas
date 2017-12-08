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

namespace Xperimentx\Atlas;

use mysqli;
use Xperimentx\Atlas\Db\Db_cfg;
use Xperimentx\Atlas\Db\Error_item;


/**
 * MySQL and MariaDB helper
 *
 * @author Roberto González Vázquez
 */
class Db
{
    /** @var mysqli       MySQLi Handler                                 */  public $mysqli           = null;
    /** @var Error_item[] Errors                                         */  public $errors           = [];
    /** @var Error_item   Last error. Null if last call is successful .  */  public $last_error       = null;
    /** @var string       Last SQL statement.                            */  public $last_sql         = null;
    /** @var Db_cfg       Configuration, options.                        */  public $cfg              = null;
    /** @var bool         Throw exceptions on mysqli errors.             */  public $throw_exceptions = false;
    /** @var Db           First object connected. The main Db object.    */  public static $db        = null;

    const ENGINE_MYISAM = 'MyISAM';
    const ENGINE_INNODB = 'InnoDB';
    const ENGINE_ARIA   = 'Aria';


    /**
     * @param Db_cfg $cfg Configuration, options for create mysqli connection.
     */
    function __construct($cfg=null)
    {
        if (!self::$db)
            self::$db = $this;

        $this->cfg = $cfg ?? new Db_cfg();

        $this->throw_exceptions = $this->cfg->throw_exceptions;
    }


    /**
     * Creates a new mysqli object and connects it to the MySQL server.
     * @param Db_cfg Configuration.
     * @return bool  Is connection successful.
     */
    public function Connect ()
    {
        $cfg = $this->cfg;

        $this->last_error = null;
        $this->mysqli     = @new mysqli($cfg->server   ,
                                        $cfg->user_name,
                                        $cfg->password ,
                                        $cfg->db_name  ,
                                        $cfg->port     ,
                                        $cfg->socket   );


        if ($this->mysqli->connect_error)            //error de conexión
        {
            $this->errors[] = $this->last_error = new Error_item(  __METHOD__,
                                                                    $this->mysqli->connect_errno,
                                                                    $this->mysqli->connect_error );
            if ($this->throw_exceptions)
                throw new Db\Db_exception($this->last_error);
        }


        if ($cfg->charset)
            $this->mysqli->set_charset($cfg->charset);

        return true;
    }



    protected function Error($method, $query)
    {
        $this->errors[] = $this->last_error = new Error_item ( $method,
                                                               $this->mysqli->errno,
                                                               $this->mysqli->error,
                                                               $query);
    }


    /**
     * Performs a query on the database.
     *
     * @param string  $query            Sql query statement.
     * @param string $caller_method Caller name for log errors, __METHOD__.
     * @return mixed|null  null:error or mysqli::query result.
     */
    protected function Query ($query, $caller_method=null)
    {
        $this->last_error = null;
        $this->last__sql  = $query;

        if ($result = @$this->mysqli->query($query)) //:=
        {
            return $result ;
        }

        $this->Error($caller_method, $query);

        if ($this->throw_exceptions)
            throw new Db\Db_exception($this->last_error);

        return null;
    }


    /**
     * Performs a query on the database en returns the number of affected rows.
     *
     * @param string  $query   Sql query statement.
     * @param string $caller_method Caller name for log errors, __METHOD__.
     *
     * @return int|null  Affected rows by query, null if error
     */
    public function Query_ar ($query, $caller_method=null)
    {
        return  $this->Query($query, $caller_method)
                ? $this->mysqli->affected_rows
                : null;
    }


    /**
     * Returns first column of first row of a query result.
     *
     * @param string  $query  SELECT sql query statement.
     *
     * @return mixed|null  Scalar value, null if no query result or error.
     */
    public function Scalar ($query )
    {
        if ($result = $this->Query($query, __METHOD__))  //:=
        {
            $row = $result->fetch_row();
            $result->close();

            return $row ? $row[0] : null ;
        }

        return null;
    }


    /**
     * Return first row for a query as an object.
     * @param string  $query       Select query statement.
     * @param string  $class_name  Class Name of object.
     * @return object|null Row data, null if no data or error.
     */
    public function Row ($query, $class_name='stdClass')
    {
        if ($result = $this->Query($query, __METHOD__))  //:=
        {
            $row    = $result->fetch_object($class_name);
            $result->close();
            return $row;
        }

       return null;
    }


    /**
     * Return array of objects for a query statement
     * @param string  $query       Select query statement.
     * @param string  $class_name  Class name of objects.
     * @return array
     */
    public function  Rows ($query, $class_name='stdClass')
    {
        $lista = [];

        if ($result = $this->Query($query, __METHOD__))  //:=
        {
            while ($obj = $result->fetch_object($class_name))
                $lista[]= $obj;

            $result->close();
            return $lista;
        }

        return [];
    }
    

    /**
     * Returns a the first column of a query as array
     * @param string  $query Select query statement.
     * @return array
     */
    public function Column ($query )
    {
        $lista = [];

        if ($result = $this->Query($query, __METHOD__))  //:=
        {
            while ($row= $result->fetch_row())
                $lista[] = $row[0];

            $result->close();
        }

        return $lista;
    }

    
    /**
     * Returns a simple array index=>scalar_value from a query.
     * Query must have a 'id' column for index and  a 'name' column or values.
     * example: SELECT id, name FROM items
     * @param string $query    Select query statement with 'id' and 'name' columns.
     * @return array
    */
    public function Vector ($query)
    {
        $lista = array();
        if ($result = $this->Query($query, __METHOD__))  //:=
        {
            while ($row= $result->fetch_assoc())
                $lista [$row['index'] ] = $row['value'];

            $result->close();

            return $lista;
        }

        return [];
    }






    /**
     * Escapes special characters in a string for use in an SQL statement, between single quotes '.
     * @param string $scalar Scalar value to process.
     * @return string
     * @see Safe()
     */
    public function Str ($scalar)
    {
        return '\''.$this->mysqli->real_escape_string($scalar).'\'';
    }



    /**
     * Return a safe value from a scalar for an SQL statement.
     * @param string|int|float|bool|null $value Scalar value to process.
     * @see Str()
     */
    public function  Safe($value)
    {
        if      (is_null   ($value))
            return 'NULL';

        else if (is_bool   ($value))
            return (int)$value;

        else if (is_numeric($value) and substr($value,0,1)!=='0')
            return      $value;

        else
            return $this->Str($value);
    }


    /**
     * Checks if the value of the field is unique in the table.
     *
     * @param string $table_name           Table name.
     * @param string $field_value          Value to check.
     * @param string $field_name           Name of column to check.
     * @param string $key_value_to_ignore  Value of key  to ignore a row, for updates. Null checks all table.
     * @param string $key_field_name       Name of key field.
     * @return bool
     */
    public function Is_unique($table_name, $field_value , $field_name, $key_value_to_ignore=null, $key_field_name='id')
    {
        $value_db = $this->Safe($field_value);
        $ignore   = $key_value_to_ignore !==NULL ? "AND `$key_field_name`!=". $this->Safe($key_value_to_ignore) : '' ;

        return $this->Scalar("SELECT COUNT(*) FROM `$table_name` WHERE `$field_name` = $value_db $ignore" )<1;
    }




    /**
     * Insert into statement
     * @param string  $table    Table for update
     * @param array   $data     Data. Index:field name.  Value:field value.
     * @param bool    $do_safe  This values will be processed by Safe().
     * @return int|null         Affected rows or null if error
     */
    public function  Insert($table, $data, $do_safe = true )
    {
        if ($do_safe)
        {
            foreach ($data as $k=>$v)
                $data[$k]= $this->Safe($v);
        }

        $sql = "INSERT INTO `$table` (\n`".join("`,\n `",array_keys($data))."`) \nVALUES ( \n".join(",\n ", $data)."\n) ;";
        return $this->Query_ar($sql);
    }




    /**
     * Update statement
     * @param string  $table    Table for update
     * @param array   $data     Data. Index:field name.  Value:field value.
     * @param string  $where    WHERE conditions
     * @param bool    $do_safe  This values will be processed by Safe().
     * @return int|null         Affected rows or null if error
     */
    public function  Update($table, $data, $where=null, $do_safe=true )
    {
        $sets = [];

        if ($do_safe)
                foreach ($data as $field=>$value) { $sets [] = "`$field` = ".$this->Safe($value); }
        else    foreach ($data as $field=>$value) { $sets [] = "`$field` = ".$value;              }

        $where_sql = $where  ? "WHERE $where ":'';

        $sql = "UPDATE `$table` SET ".join("\n, ", $sets).($where ?"\n $where_sql ;":' ;');

        return $this->Query_ar($sql, __METHOD__);
    }



    /**
     * Update a row (key!=null) or Insert a new row  (key value=null)
     * @param string  $table            Table for update
     * @param array   $data             Data. Index:field name.  Value:field value.
     * @param string  $key_value        Value of key  to  update. By reference for inserts ( key=null) => get insert_id if autonumeric
     * @param string  $key_field_name  Name of key field.
     * @param bool    $do_safe         This values will be processed by Safe().
     * @return   int Number of  affected rows.
     * @since 2.0
     */

    public function  Update_or_insert($table, $data, &$key_value , $key_field_name='id', $do_safe=true)
    {
        if (null !==$key_value)
        {
            return $this->Update($table, $data, "`$key_field_name`=".$this->Safe($key_value)  );
        }

        if ($ok=$this->Insert($table, $data,$do_safe ))  //:=
        {
            if ($this->mysqli->insert_id)
                $key_value = $this->mysqli->insert_id;
        }

        return $ok;
    }


    // --------------------------------------------------------------------------- FORGE ----

    /**
     * Drops a table.
     * @param string $table
     * @return int Affected rows
     */
    public function Drop_table($table)
    {
        return $this->Query_ar("DROP TABLE `$table`;");
    }


    /**
     * Truncates a table.
     * @param string $table
     * @return int Affected rows
     */
    public function Truncate_table($table)
    {
        return $this->Query_ar("TRUNCATE TABLE `$table`;");
    }


    /**
     * Drops a database.
     * @param string $database_name
     * @return int Affected rows
     */
    public function Drop_database($database_name)
    {
        return $this->Query_ar("DROP DATABASE `$database_name`;");
    }


    /**
     * Creates a new data base.
     * @param string $database_name
     * @param string $collate Default collation, false equivalent if not collation
     * @return int Affected rows
     */
    public function Create_database($database_name, $collate='utf8_general_ci')
    {
        return $this->Query_ar(" CREATE DATABASE `$database_name` ". $collate ? " /*!40100 COLLATE '$collate' */;":';');
    }
}
