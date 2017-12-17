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

use mysqli;
use Xperimentx\Atlas\Db\Db_cfg;
use Xperimentx\Atlas\Db\Profile_item;


/**
 * MySQL and MariaDB helper.
 *
 * The first **Db** object created  will registered as the default Object.
 *
 * You can change this default object using *Make_default()* method.
 *
 * *Db::Obj()* returns the default Db.
 *
 *
 * @link https://github.com/xperimentx/atlas/blob/master/Atlas/doc/Database.md
 * @author Roberto González Vázquez
 */
class Db
{
    /** @var mysqli         MySQLi Handler.                                */  public $mysqli           = null;
    /** @var Profile_item[] Profiles or successful calls, benchmarking.    */  public $profiles           = [];
    /** @var Profile_item[] Errors.                                        */  public $errors           = [];
    /** @var Profile_item   Last error. Null if last call is successful.   */  public $last_error       = null;
    /** @var Profile_item   Last profile. Null if error in last call       */  public $last_profile     = null;
    /** @var bool           Throw Db_exception exceptions on mysqli errors.*/  public $throw_exceptions = false;
    /** @var Db_cfg         Configuration, options.                        */  public  $cfg             = null;
    /** @var Db             First object connected. The main Db object.    */  private static $db_default        = null;

    const ENGINE_MYISAM = 'MyISAM';
    const ENGINE_INNODB = 'InnoDB';
    const ENGINE_ARIA   = 'Aria';

    /**
     * Returns the Default Db
     * @see Set_
     * @return \Xperimentx\Atlas\Db
     */
    function Obj() :Db
    {
        return self::$db_default;
    }

    /**
     * Sets this Db as the default Db object.
     * @see Obj();
     */
    function Make_default()
    {
        self::$db_default_db = $this;
    }


    /**
     * @param Db_cfg|string $cfg Configuration object, or full class name of the configuration object
     */
    function __construct($cfg=null)
    {
        if (!self::$db_default)
            self::$db_default = $this;

        if (is_string($cfg))
            $cfg = new $cfg;

        $this->Set_cfg($cfg ?? new Db_cfg());
    }

    /**
     * Configure to throw exceptions on mysqli errors and on connect errors.
     */
    public function Trow_exceptions()
    {
        $this->cfg->throw_exceptions = true;
        $this->cfg->throw_exceptions_on_connect = true;
    }


    /**
     * Connects to the MySQL server.
     *
     * Creates a new mysqli object and connects it to the MySQL server.
     * @param Db_cfg Configuration.
     * @throws Db\Db_exception
     * @return bool  Is connection successful.
     */
    public function Connect () : bool
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

            if ($this->cfg->throw_exceptions_on_connect)
                throw new Db\Db_exception($this->last_error);

            return false;
        }

        $this->profiles[] = $this->last_profile = new Profile_item(__METHOD__, null, $m_time);

        if ($cfg->charset)
            $this->mysqli->set_charset($cfg->charset);

        return true;
    }


    /**
     * Connects to the MySQL server, if not connection terminates the current script.
     *
     * If Environment::Is_development() shows the error message.
     *
     * @param Db_cfg|string $cfg Configuration object, or full class name of the configuration object
     * @param string $message Message to show.
     */
    public function Connect_or_die(string $message="Database connection error \n")
    {
        if($this->Connect()) return;

        if (Environment::Is_development_stage())
            print_r($this->last_error);

        exit($message);
    }


    /**
     * Performs a query on the database.
     *
     * @param string  $query            Sql query statement.
     * @param string $caller_method Caller name for log errors, __METHOD__.
     * @throws Db\Db_exception
     * @return mixed|null  null:error or mysqli::query result.
     */
    protected function Query (string $query, string $caller_method=null)
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
     * @return int  Affected rows by query, null if error
     */
    public function Query_ar (string $query, string $caller_method=null) : int
    {
        return  $this->Query($query, $caller_method?:__METHOD__)
                ? $this->mysqli->affected_rows
                : 0;
    }


    /**
     * Returns first column of first row of a query result.
     *
     * @param string  $query  SELECT sql query statement.
     *
     * @return scalar|null  Scalar value, null if no query result or error.
     */
    public function Scalar (string $query)
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
    public function Row (string $query, string $class_name='stdClass')
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
     * Returns an array of objects for a query statement.
     * @param string  $query       Select query statement.
     * @param string  $class_name  Class name of objects.
     * @return array
     */
    public function  Rows (string $query, string $class_name='stdClass') :array
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
    public function Column (string $query ) : array
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
    public function Vector (string $query): array
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
    public function Str (string $scalar) : string
    {
        return '\''.$this->mysqli->real_escape_string($scalar).'\'';
    }



    /**
     * Returns a safe value from a scalar for an SQL statement.
     *
     * All not scalars values are considered as null.
     * @param scalar|null $value Scalar value to process.
     * @see Str()
     */
    public function  Safe($value) :string
    {
        if     (!is_scalar($value))  return 'NULL';
        elseif (is_bool($value))     return $value ? '1':'0';
        elseif (is_numeric($value))  return (string)$value;
        else                         return '\''.$this->mysqli->real_escape_string($value).'\'';
    }


    /**
     * Checks if the value of the field is unique in the table.
     *
     * @param string $table_name           Table name. `` will be added.
     * @param string $field_value          Value to check.
     * @param string $field_name           Name of column to check.
     * @param scalar|null $key_value_to_ignore  Value of key  to ignore a row, for updates.
     *                                          Null checks all table.
     * @param string $key_field_name       Name of key field. `` will be added.
     * @return bool
     */
    public function Is_unique( string $table_name, string $field_value , string $field_name,
                               $key_value_to_ignore=null, string $key_field_name='id') : bool
    {
        $value_db = $this->Safe($field_value);
        $ignore_key   = $key_value_to_ignore !==NULL ? " AND `$key_field_name`!=". $this->Safe($key_value_to_ignore) : '' ;

        return $this->Scalar("SELECT COUNT(*) FROM `$table_name` WHERE `$field_name` = $value_db $ignore_key" )<1;
    }


    /**
     * Insert into statement.
     * @param string  $table    Table for update.`` will be added.
     * @param array   $data     Data. Index:field name.  Value:field value.
     * @param bool    $do_safe  This values will be processed by Safe().
     * @return int              Affected rows
     */
    public function  Insert(string $table, array $data, bool $do_safe = true ) : int
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
     * @param string  $table    Table for update.`` will be added.
     * @param array   $data     Data. Index:field name.  Value:field value.
     * @param string|null  $where    WHERE conditions
     * @param bool    $do_safe  This values will be processed by Safe().
     * @return int              Affected rows or null if error
     */
    public function  Update(string $table, array $data, string $where=null, bool $do_safe=true ) : int
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
     * @param string  $table            Table for update. `` will be added.
     * @param array   $data             Data. Index:field name.  Value:field value.
     * @param scalar  $key_value        Value of key  to  update. By reference for inserts ( key=null) => get insert_id if autonumeric
     * @param string  $key_field_name  Name of key field. `` will be added.
     * @param bool    $do_safe         This values will be processed by Safe().
     * @return   int Number of  affected rows.
     */
    public function  Update_or_insert(string $table, array $data, &$key_value , string $key_field_name='id', bool $do_safe=true) : int
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
     * Returns a handler to create a table.
     * @param string $table Table name. `` will be added.
     * @return \Xperimentx\Atlas\Db\Create_table
     */
    public function Create_table(string $table) : Db\Create_table
    {
        return new Db\Create_table($table, $this);
    }


    /**
     * Returns a handler to alter a table.
     * @param string $table Table name. `` will be added.
     * @return \Xperimentx\Atlas\Db\Alter_table
     */
    public function Alter_table(string $table) : Db\Alter_table
    {
        return new Db\Alter_table($table, $this);
    }


    /**
     * Drops a table.
     * @param string $table Table name. `` will be added.
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Drop_table(string $table, bool $if_exists=true) : int
    {
        return $this->Query_ar('DROP TABLE '.($if_exists?'IF EXISTS ':'')."`$table`;");
    }


    /**
     * Optimizes a table.
     * @param string $table Table name. `` will be added.
     * @return array MySql messages {Table, Op, Msg_type, Msg_text}[]
     */
    public function Optimize_table(string $table) : array
    {
        return $this->Rows("OPTIMIZE TABLE `{$table}`; ");

    }


    /**
     * Truncates a table.
     * @param string $table Table name. `` will be added.
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Truncate_table(string $table) : int
    {
        return $this->Query_ar("TRUNCATE TABLE `$table`;\n");
    }


    /**
     * Drops a database.
     * @param string $database_name Database name.  `` will be added.
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Drop_database(string $database_name, bool $if_exists=true) :int
    {
        return $this->Query_ar('DROP DATABASE '.($if_exists?'IF EXISTS ':'')."`$database_name`;");
    }


    /**
     * Drops a view.
     * @param string $view_name View name. `` will be added.
     * @param bool $if_exists
     * @return int Affected rows
     */
    public function Drop_view(string $view_name, bool $if_exists=true) : int
    {
        return $this->Query_ar('DROP VIEW '.($if_exists?'IF EXISTS ':'')."`$view_name`;");
    }


    /**
     * Creates a new data base.
     * @param string $database_name Database name. `` will be added.
     * @param string $collate Default collation, false equivalent if not collation
     * @param bool $if_not_exists
     * @return int Affected rows
     */
    public function Create_database(string $database_name, string $collate='utf8_general_ci', bool $if_not_exists=true) :int
    {
        return $this->Query_ar('CREATE DATABASE '.($if_not_exists?'IF NOT EXISTS ':'')."`$database_name` ". ($collate ? " /*!40100 COLLATE '$collate' */;\n":";"));
    }


    /**
     * Show columns info form a table.
     * @param string $table Table name. `` will be added.
     * @return object[] {Field, Type, Collation, Null, Key, Default, Extra, Privileges, Comment}
     */
    public function Show_columns (string $table) :array
    {
        return $this->Rows("SHOW FULL COLUMNS FROM `$table`;");
    }


    /**
     * Shows columns names form a table.
     * @param string $table Table name. `` will be added.
     * @return string[]
     */
    public function Show_column_names (string $table) :array
    {
        return $this->Column("SHOW COLUMNS FROM `$table`;");
    }


    /**
     * Shows CREATE TABLE for a table.
     * @param string $table Table name. `` will be added.
     * @return string
     */
    public function Show_create_table(string $table) :string
    {
        $x = $this->Row("SHOW CREATE TABLE `$table`");
        return $x->{"Create Table"}??'';
    }


    /**
     * Shows CREATE DATABASE for a database.
     * @param string $database_name Database name. `` will be added.
     * @param bool $if_not_exists
     * @return string
     */
    public function Show_create_database(string $database_name, bool $if_not_exists=true) :string
    {
        $x = $this->Row('SHOW CREATE DATABASE '.($if_not_exists?'IF NOT EXISTS ':'')."`$database_name` ");
        return $x->{"Create Database"}??'';
    }


    /**
     * Show table names from a database .
     *
     * SHOW TABLES FROM `$database_name` LIKE '$like';
     *
     * @param string|null $like Like pattern, optional.
     * @param string|null $database_name Database name. Null=current database, `` will be added.
     */
    public function Show_tables_like (string $like, string $database_name=null) : array
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
    public function Describe (string $query) :array
    {
        return $this->Rows("DESCRIBE $query ;");
    }


    /**
     * Describes a query in a html table.
     * @param string $query
     * @return string html Returns a html table, empty string if not description.
     */
    public function Describe_html_table (string $query) :string
    {
        $data = $this->Describe($query);

        if(!$data) return '';

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
     * @return string Html table.
     */
    public function Pofiles_html_table () :string
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
    public function Pofiles_describe_html () :string
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


    /**
     * Generates a base code for an active recod class based in the fields of a table.
     *
     * @param string $table             Table name, `` will be added.
     * @param string $class_name        Php class name for the active record class
     * @param string $parent_class_name Active recod class name to extend
     * @return string Php code of the active record class
     */
    function Active_record_class_maker(string $table, string $class_name, string $parent_class_name='\Xperimentx\Atlas\Active_record') :string
    {
        $cols = $this->Show_columns($table);

        if (!$cols) return '';
        $properties ='';

        foreach ($cols as $col)
        {
            $aux_type   = ($pos=strpos($col->Type,'('))  //:=
                        ? substr ($col->Type, 0, $pos)
                        : $col->Type;

            $php_type = 'string';

            $default = (null === $col->Default or 'CURRENT_TIMESTAMP'===$col->Default )
                       ? 'null'
                       : '"'.addslashes($col->Default).'"';

            if (strpos($col->Type, 'int')!==false)
                    $php_type='int';

            switch ($aux_type)
            {
                case 'tinyint':
                    if ('is_' == substr($col->Field, 0,3) or  'has_' == substr($col->Field, 0,4))
                    {
                        $php_type='bool';

                        if (null !== $col->Default )
                            $default =  $col->Default ? 'true':'false';
                    }
                    break;

                case 'float':
                case 'double':
                case 'decimal':
                    $php_type = 'float';
                    break;

            }

            $info = $col->Type;
            if ($col->Comment)       $info ="$col->Comment  -  $info";
            if ($col->Null==='YES')  $info.=', null allowed';
            if ($col->Extra)         $info.=', '.$col->Extra;
            if ($col->Key)           $info.=', Key '.$col->Key;

            $properties.="\n    /** @var $php_type $info */\n    public \${$col->Field} = $default;\n";
        }

        return <<<PHP
class $class_name extends $parent_class_name
{
    public static _table = '$table';
$properties
}
PHP;
    }
}
