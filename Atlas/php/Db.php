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
use Xperimentx\Atlas\Db\Profile_item;


/**
 * MySQL and MariaDB helper
 *
 * @author Roberto González Vázquez
 */
class Db
{
    /** @var mysqli         MySQLi Handler.                                */  public $mysqli           = null;
    /** @var Profile_item[] Profiles or successful calls, benchmarking.    */  public $profiles           = [];
    /** @var Profile_item[] Errors.                                        */  public $errors           = [];
    /** @var Profile_item   Last error. Null if last call is successful.   */  public $last_error       = null;
    /** @var Profile_item   Last profile. Null if error in last call       */  public $last_profile     = null;
    /** @var Db_cfg         Configuration, options.                        */  public $cfg              = null;
    /** @var bool           Throw Db_exception exceptions on mysqli errors.*/  public $throw_exceptions = false;
    /** @var Db             First object connected. The main Db object.    */  public static $db        = null;

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
        $cfg    = $this->cfg;
        $m_time = microtime(true);

        $this->last_error   = null;
        $this->last_profile = null;

        $this->mysqli     = @new mysqli
                            (
                                $cfg->server   ,
                                $cfg->user_name,
                                $cfg->password ,
                                $cfg->db_name  ,
                                $cfg->port     ,
                                $cfg->socket
                            );

        if ($this->mysqli->connect_error)            //error de conexión
        {
            $this->errors[] =
                $this->last_error =
                    new Profile_item
                    (
                        __METHOD__,
                        null,
                        $m_time,
                        $this->mysqli->connect_errno,
                        $this->mysqli->connect_error
                    );

            if ($this->throw_exceptions)
                throw new Db\Db_exception($this->last_error);

            return false;
        }

        $this->profiles[] = $this->last_profile = new Profile_item(__METHOD__, null, $m_time);

        if ($cfg->charset)
            $this->mysqli->set_charset($cfg->charset);

        return true;
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
        $m_time = microtime(true);

        $this->last_error   = null;
        $this->last_profile = null;

        if ($result = @$this->mysqli->query($query)) //:=
        {
            $this->profiles[] = $this->last_profile =
                    new Profile_item
                    (
                        $caller_method ??__METHOD__,
                        $query,
                        $m_time
                    );

            return $result ;
        }

        $this->errors[] =
                $this->last_error =
                    new Profile_item
                    (
                        $caller_method ?:__METHOD__,
                        $query,
                        $m_time,
                        $this->mysqli->errno,
                        $this->mysqli->error
                    );


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
        return  $this->Query($query, $caller_method?:__METHOD__)
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
     * Returns first row for a query as an object.
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
     * Returns array of objects for a query statement.
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
     * Returns a the first column of a query as array.
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
     * Returns a safe value from a scalar for an SQL statement.
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
        $ignore_key   = $key_value_to_ignore !==NULL ? " AND `$key_field_name`!=". $this->Safe($key_value_to_ignore) : '' ;

        return $this->Scalar("SELECT COUNT(*) FROM `$table_name` WHERE `$field_name` = $value_db $ignore_key" )<1;
    }




    /**
     * Insert into statement.
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
     * Update statement.
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
     * Update a row (key!=null) or Insert a new row  (key value=null).
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
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Drop_table($table, $if_exists=true)
    {
        return $this->Query_ar('DROP TABLE '.($if_exists?'IF EXISTS ':'')."`$table`;\n");
    }


    /**
     * Truncates a table.
     * @param string $table
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Truncate_table($table)
    {
        return $this->Query_ar("TRUNCATE TABLE `$table`;\n");
    }


    /**
     * Drops a database.
     * @param string $database_name
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Drop_database($database_name, $if_exists=true)
    {
        return $this->Query_ar('DROP DATABASE '.($if_exists?'IF EXISTS ':'')."`$database_name`;\n");
    }


    /**
     * Drops a view.
     * @param string $view_name
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Drop_view($view_name, $if_exists=true)
    {
        return $this->Query_ar('DROP VIEW '.($if_exists?'IF EXISTS ':'')."`$view_name`;\n");
    }


    /**
     * Creates a new data base.
     * @param string $database_name
     * @param string $collate Default collation, false equivalent if not collation
     * @param bool $if_not_exists
     * @return int Affected rows
     */
    public function Create_database($database_name, $collate='utf8_general_ci', $if_not_exists=true)
    {
        return $this->Query_ar('CREATE DATABASE '.($if_not_exists?'IF NOT EXISTS ':'')."`$database_name` ". ($collate ? " /*!40100 COLLATE '$collate' */;\n":";\n"));
    }


    /**
     * Show columns info form a table.
     * @param string $table
     * @return object[] {Field, Type, Null, Key, Default, Extra}
     */
    public function Show_columns ($table)
    {
        return $this->Rows("SHOW COLUMNS FROM `$table`");
    }


    /**
     * Shows columns names form a table.
     * @param string $table
     * @return string[]
     */
    public function Show_column_names ($table)
    {
        return $this->Column("SHOW COLUMNS FROM `$table`");
    }


    /**
     * Shows CREATE TABLE for a table.
     * @param string $table
     * @return string|null
     */
    public function Show_create_table($table)
    {
        $x = $this->Row("SHOW CREATE TABLE `$table`");
        return $x->{"Create Table"}??null;
    }


    /**
     * Shows CREATE DATABASE for a database.
     * @param string $database_name
     * @param bool $if_not_exists
     * @return string|null
     */
    public function Show_create_database($database_name, $if_not_exists=true)
    {
        $x = $this->Row('SHOW CREATE DATABASE '.($if_not_exists?'IF NOT EXISTS ':'')."`$database_name` ");
        return $x->{"Create Database"}??null;
    }

    /**
     * Show table names from a database
     *
     * SHOW TABLES FROM `$database_name` LIKE '$like';
     *
     * @param string $like Like pattern, optional.
     * @param string|null $database_name Null=current database;
     */
    public function Show_tables ($like=null, $database_name=null)
    {
        $from     = $database_name ? " FROM `$database_name` ":'';
        $like_sql = $like          ? " LIKE '$like'":'' ;
        return $this->Column("SHOW TABLES $from  $like_sql;");
    }


    // BENCHMARKING
    // ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨


    /**
     * Describes a query.
     * @param string $query
     * @return object[] {id, select_type, table, partitions,type,posible_keys,key_len,ref,rows. filtered, Extra}
     */
    public function Describe ($query)
    {
        return $this->Rows("DESCRIBE $query ;");
    }


    /**
     * Describes a query in a html table.
     * @param string $query
     * @return string html Return an htm tlable
     */
    public function Describe_html_table ($query)
    {
        $data = $this->Describe($query);

        if(!$data) return null;

        $out="<table class='xx-atlas-db-describe'>
            <tr><th style='text-align:right'>Id</th>
                <th style='text-align:left'>Select type</th>
                <th style='text-align:left'>Table</th>
                <th style='text-align:left'>Partitions</th>
                <th style='text-align:left'>Type</th>
                <th style='text-align:left'>Possible keys</th>
                <th style='text-align:left'>Key</th>
                <th style='text-align:right'>Key len</th>
                <th style='text-align:left'>Ref</th>
                <th style='text-align:right'>Rows</th>
                <th style='text-align:right'>Filtered</th>
                <th style='text-align:left'>Extra</th></tr>";

        foreach((array)$data as $d)
            $out.= "<tr><td style='text-align:right'>$d->id</td>
                <td style='text-align:left'>$d->select_type</td>
                <td style='text-align:left'>$d->table</td>
                <td style='text-align:left'>$d->partitions</td>
                <td style='text-align:left'>$d->type</td>
                <td style='text-align:left'>$d->possible_keys</td>
                <td style='text-align:left'>$d->key</td>
                <td style='text-align:right'>$d->key_len</td>
                <td style='text-align:left'>$d->ref</td>
                <td style='text-align:right'>$d->rows</td>
                <td style='text-align:right'>$d->filtered</td>
                <td style='text-align:left'>$d->Extra</td></tr>";

        return $out.'</table>';
    }


    /**
     * Returns a basic report of profiles as a html table.
     * @return string Html table
     */
    public function Pofiles_html_table ()
    {
        $out="<table class='xx-atlas-db-profiles'>
            <tr><th style='text-align:right'>Seconds</th>
                <th style='text-align:left'>Method</th>
                <th style='text-align:left'>Query</th></tr>";

        foreach ($this->profiles as $pro)
            $out.= sprintf("<tr><td style='text-align:right'>%.6f s</td>
                            <td style='text-align:left'>%s</td>
                            <td style='text-align:left'>%s</td></tr>\n",
                                $pro->seconds,
                                $pro->method,
                                nl2br(htmlspecialchars($pro->query)));

        return $out.'</table>';
    }
    

    /**
     * Returns a report with query description as html.
     * @return string Html report
     */
    public function Pofiles_describe_html ()
    {
        $out ='';
        foreach ($this->profiles as $pro)
        {
            if (!$pro->query) continue;

            $out.= nl2br(htmlspecialchars((string)$pro)).
                $this->Describe_html_table($pro->query).
                '<hr/>';
        }
        return $out;
    }
}
