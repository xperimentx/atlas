<?php

/**
 *  Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas;

/**
 * Error info for Mysql::$errors items
 *
 * @author Roberto González Vázquez
 */

class Mysql
{

    /** @var \mysqli */ public $mysqli = null;


    /** @var Mysql_error Errors                                       */  public $errors     = [];
    /** @var Mysql_error Last error. Null if last call is succesfull .*/  public $last_error = null;




    /**
     * Creates a new mysqli object and connects it to the MySQL server
     *
     * @param string $host        Host name or an IP address of MySQL server.
     * @param string $user_name  The MySQL user name.
     * @param string $password
     * @param string $db_name     Default database to be used when performing queries.
     * @return bool
     */
    public function Connect($host, $user_name, $password, $db_name='', $charset='utf8')
    {
        $this->mysqli = new \mysqli($host, $user_name, $password, $db_name);


        if ($this->mysqli->connect_error)            //error de conexión
        {
            $this->errors[] = $this->last_error = new Mysql_error(  __METHOD__,
                                                                    $this->mysqli->connect_errno,
                                                                    $this->mysqli->connect_error );

            return false;
        }

        $this->last_error = null;

        if ($charset)
            $this->mysqli->set_charset($charset);

        return true;
    }


    protected function Error($method, $query)
    {
        $this->errors[] = $this->last_error = new Mysql_error( $method,
                                                               $this->mysqli->errno,
                                                               $this->mysqli->error,
                                                               $query);
    }


    /**
     * Performs a query on the database
     *
     * @param string  $query             Consulta sql
     * @return mixed|null  null:error or mysqli::query result
     */
    public function Query ($query )
    {
        $this->last_error = null;

        if ($result = @$this->mysqli->query($query)) //:=
        {
            return $result ;
        }

        $this->Error(__METHOD__, $query);
        return null;
    }



    /**
     * Return first column of first row of a query
     *
     * @param string  $query  SELECT sql query
     *
     * @return mixed|null  Scalar value, null if no query result or error
     */
    public function Scalar ($query )
    {
        $this->last_error = null;

        if ($result = @$this->mysqli->query($query))  //:=
        {
            $row = $result->fetch_row();
            $result->close();

            return $row? $row[0] : null ;
        }

        $this->Error(__METHOD__, $query);

        return null;
    }



    /**
     * Devuelve la primera columna de una consulta SELECT
     *
     * @param string     $query             Consulta sql SELECT
     * @param bool      $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @return array|null     Vector con los valores de la primera columna devuelta por la consulta
     *
     *
     */
    public function Column ($query)
    {
        $this->last_error = null;

        $lista = array();

        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_row())
                $lista[]= $row[0];

            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query); return [];
    }



    /**
     * Devuelve la primera la primera fila de una consulta SELECT en forma de objeto
     *
     * @param string     $query             Consulta sql para obtener los valores
     * @param string     $query             Consulta sql para obten
     * @param string     $class_name        Nombre de la clase. 2016
     * @return object|null Primera fila, en caso de error o no encontrado devolverá null
     */
    public function Row_object ($query, $class_name='stdClass')
    {
        $this->last_error = null;

        if ($result = @$this->mysqli->query($query))
        {
            $row    = $result->fetch_object($class_name);

            $result->close();

            return $row;
        }

        return  $this->Error(__METHOD__, $query, FALSE);
    }


    /**
     * Devuelve la primera la primera fila de una consulta SELECT en forma de vector asociativo
     *
     * @param string     $query             Consulta sql para obtener los valores
     * @param bool      $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     *
     * @return array|null Primera fila, en caso de error o no encontrado devolverá null
     */
    public function Row_assoc ($query)
    {
        $this->last_error = null;

        if ($result = @$this->mysqli->query($query))
        {
            $row= $result->fetch_assoc();
            $result->close();

            return $row;
        }

        $this->Error(__METHOD__, $query);
        return null;
    }




    /**
     * Devuelve un vector de objetos con las filas de una consulta SELECT
     * @param string     $query             Consulta sql para obtener los valores
     * @param string     $class_name        Nombre de la clase. 2016
     * @return array|null    Objetos, en caso de error o no encontrado devolverá array vacío
     */
    public function  Rows_objects  ($query, $class_name='stdClass')
    {
        $this->last_error = null;

        $lista = array();
        if ($result = @$this->mysqli->query($query))
        {
            while ($obj = $result->fetch_object($class_name))  $lista[]= $obj;

            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query);
        return [];
    }




    public function Fetch_object($result, $class_name='stdClass')
    {
       if (!$result) return null;

       $obj = $result->fetch_object($class_name);

       if(!$obj) $result->close();

       return $obj;
    }

    

    public function Fetch_assoc($result)
    {
        if (!$result) return null;

        $obj = $result->fetch_assoc();

        if(!$obj) $result->close();

        return $obj;
    }



    /**
     * Devuelve un vector vectores asociativos con las filas de una consulta SELECT
     * @param string     $query             Consulta sql para obtener los valores
     * @param bool        $throw_exceptions Dice si lanzan excepciones en caso de error
     *
     * @return array[]array[string]    Resultado, en caso de error o no encontrado devolverá array vacío
     */
    public function Rows_assoc ($query )
    {
        $this->last_error = null;

        $lista = array();
        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_assoc())
            {
                $lista[]= $row;
                //echo ATLAS::Memory_usage();
            }
            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query);
        return [];
    }


    /**
     * Devuelve un vector con los datos de una columna
     * @param string     $query             Consulta sql para obtener la columna
     * @param bool        $throw_exceptions Dice si lanzan excepciones en caso de error
     *
     * @return array[]    Resultado, en caso de error o no encontrado devolverá array vacío
     */
    public function Col ($query )
    {
        $this->last_error = null;

        $lista = array();
        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_row())
            {
                $lista[] = $row[0];
            }
            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query);
        return [];
    }


    /**
     * Devuelve un vector vectores asociativos con las colmnas de una consulta SELECT
     * @param string     $query             Consulta sql para obtener las columnas
     * @param bool        $throw_exceptions Dice si lanzan excepciones en caso de error
     *
     * @return array[string]array[]    Resultado, en caso de error o no encontrado devolverá array vacío
     */
    public function Cols_assoc ($query )
    {
        $this->last_error = null;

        $lista = array();

        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_assoc())
            {
                foreach ($row as $i=>$v    )
                    $lista[$i][]= $v;
            }
            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query);
        return [];
    }


    /**
     * Devuelve un objeto, cada atributo será un vector con los datos de cada columna
     * @param string     $query             Consulta sql para obtener las columnas
     * @param bool      $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     *
     * @return object    Objeto, en caso de error o no encontrado devolverá null
     */
    public function Cols_object ($query)
    {
        $this->last_error = null;

        $lista = array();
        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_assoc())
            {
                foreach ($row as $i=>$v    )
                    $lista[$i][]= $v;
            }
            $result->close();

            return (object)$lista;
        }

        $this->Error(__METHOD__, $query);
        return null;
    }

    /**
     * La consulta debe devolver una columna con el índice (id) y otra con el valor (name)
     * @param string $query    ejemplo: SELECT id , name FROM provincias WHERE comunidad='galicia' ORDER BY name
     * @param bool      $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @return array|null  Los índices contendrán la columna 'id' y los valores la columna 'name', null si error
    */
    public function Vector ($query )
    {
        $this->last_error = null;

        $lista = array();
        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_assoc())
                $lista [$row['id'] ] = $row['name'];

            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query); return [];
    }



    /**
     * La consulta debe devolver una columna con el índice (id) , una con el nombre (name) y otra con el valor (value)
     * @param string $query    ejemplo: SELECT id , name FROM provincias WHERE comunidad='galicia' ORDER BY name
     * @param bool      $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @return array|null  Los índices contendrán la columna 'id' y los valores la columna 'name', null si error
    */
    public function Vector_pad ($width_chars, $pad_char, $unit, $query )
    {
        $this->last_error = null;

        // sustituir espacio por nbsp &#160
        //if ($pad_char==' ') $pad_char=' ';

        $lista = array();
        if ($result = @$this->mysqli->query($query))
        {
            while ($row= $result->fetch_assoc())
            {
                $lista [$row['id'] ] = str_replace(' ',' ',$row['name'].str_repeat($pad_char, $width_chars- mb_strlen($row['name'].$row['value'].$unit)).$row['value'].$unit);

            }

            $result->close();

            return $lista;
        }

        $this->Error(__METHOD__, $query); return [];
    }



    private static $str_replace=array("\\"  , "\0" , "\n" , "\r" , "\x1a", "'" , '"');
    private static $str_search =array("\\\\", "\\0", '\n' , '\r' , '\x1a', "\'", '\"');

    /**
     * Devuelve la cadena con un formato seguro para mysql entre comillas'
     * Escapa los caracteres "\\"  , "\0" , "\n" , "\r" , "\x1a", "'" , '"'
     * No usa mysql_real_escape_string, esta pensado para aqellas situaciones que prefiramos no abusar de la conexión con mysql
     * @param string $text Cadena a convertir
     * @see Str(), Escape()
     * @return string Cadena segura
     */
    public static function Str_ ($text)
    {
        return '\''.str_replace(self::$str_search, self::$str_replace, $text).'\'';
    }



    /**
     * Devuelve la cadena con un formato seguro para mysql entre comillas'
     * @param string $cadena Cadena a convertir
     * @return string Cadena segura
     * @uses real_escape_string
     * @see Str_,Escape()
     */
    public function Str ($cadena, $allow_null=true)
    {
        if ($allow_null and $cadena=== null)
            return ' NULL ';

        return '\''.$this->mysqli->real_escape_string($cadena).'\'';
    }



    /**
     * Llama a mysqli->real_escape_string  Devuelve la cadena con un formato seguro para mysql
     * @uses real_escape_string
     * @param string $cadena Cadena a convertir
     * @see Str(),Str_()
     * @return string Cadena segura
     */
    public function Escape ($cadena)
    {
        return $this->mysqli->real_escape_string($cadena);
    }





    /**
     * Crea un filtro basado en un texto con varias palabras separadas por espacios
     *
     * @param string|array     $fields    Campos separados por comas o vector con los campos
     * @param string         $text     Texto a buscar
     * @param string        $logic    AND o OR
     * @param int            $min_charts Número mínimo de caracteres que debe tener una palabra para ser buscada
     *
     * @return string Cadena para concatenar en el WHEREIS de una consulta SQL. Será ' FALSE ' en el caso que se considere que no hay ninguna palabra que buscar
    */
    public function Text_filter($fields, $text, $min_charts=2, $logic='AND')
    {
        //$text = strtr(trim($text), ',.;"-\'|\\/', '         ');         // limpiamos el texto a buscar
          $text = strtr(trim($text), ',;"-\'|\\/' , '        ');         // limpiamos el texto a buscar

        if (!$text)
        {
            ATLAS::Error('Caracteres de filtrado inválido');
            return  ' FALSE ' ;                                 // Sin texto a buscar no es útil
        }



        // conseguimos los campos con los que trabajar

        if (is_array($fields))
            $fields = implode(',', $fields);

        if (strpos($fields,',')>0)
            $fields =  "CONCAT_WS(' -|{ ', $fields )";                     // concatenamos con caracteres de raro uso para evitar falso positivos



        // conseguimos la palabras a buscar

        $palabras     = explode( " ", $text );
        $filtros     = array();

        foreach ($palabras as $palabra)
        {
            if (strlen($palabra)<$min_charts) continue;            // la palabra no da la talla

            $filtros[] = " $fields LIKE '%".$this->mysqli->real_escape_string($palabra)."%' "; // Añadimos el filtro
        }



        if (!$filtros)
        {
            ATLAS::Error("Las palabras de búsqueda deben tener al menos $min_charts caracteres");
            return  ' FALSE ' ;                                 // Si no hay palabras útiles
        }
        return implode(" $logic ", $filtros);                             // Unimos los filtros según la lógica para obtener el resultado pedido
    }




    /**
     * Texto del explicativo último error , o false si al última llamada a un método de la clase tuvo éxito
     * @return false|string
     */
    public function  Error_msg()
    {
        return $this->error_msg;
    }


    /**
     * Procesa un lote de consultas mysql,
     * parte por cada línea terminada en ';' que no sea comentario, lo cual
     * ejecutar los lotes basados en mysqldump
     * @param string $ignorar_errores 'false' para la ejecución si encuentra una consulta errónea. 'true' la ignora y continua con la siguiente
     * @return bool true: todas las consultas ejecutadas sin errores
     * @uses Query()
    */
    public function Multi_query($queries, $ignorar_errores=false)
    {
        /**
            alternativas encontradas en la web
            $queries =  preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $sql);
            $queries = preg_split("/s; (?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $sql)
        */
        $ok=true;
        $debug = true;
        $lines = explode("\n", str_replace ("\r\r\r\r", '', $queries));

        $query ='';
        $num = 0; // número de consultas
        $ok=true;
        if ($debug) echo 'num lines: '. count($lines).'<br/>';
        foreach($lines as $line)
        {
            $line = trim($line);
            if ($debug) echo htmlspecialchars($lines).'<br/><br/>';

            if (!$line  or $line[0]!='#' and substr($line, 0, 2)!='--' )
                $query .=' '.$line;

            if (substr($line,-1)==';')
            {
                $query=substr($query,0,-1);

                $num++;


                if (!$this->Query($query, $throw_exceptions))
                {
                    $ok=false;
                    if (!$ignorar_errores) return false; // aborta la ejecución si es necesario
                }

                $query =''; // preparados para la nueva consulta;
            }
        }

        if (trim($query)) return ($this->Query($query) and $ok);

        return $ok;

    }

    /**
     * Id insertado - Mysqli.insert_id
     * Devuelve el id autonumérico que se insertado en una llanada ISERT inmediatamente anterior
     * @return null|int
     */
    public function Insert_id()
    {
        return $this->mysqli->insert_id;
    }

    /**
     * Affected rows
     * ilas afectadas por una modificación, borrado...
     * @return null|int
     */
    public function Affected_rows()
    {
        return $this->mysqli->affected_rows;
    }



    /**
     * Bloquea la tabla para escritura
     * @param string, csv Tablas a bloquear
     * @param bool   $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @see Unlock()
     */
    public function Lock( $tables)
    {
        if (!$this->allow_locks) return;
        $this->locked = true;
        $tables=ATLAS::Array_csv($tables);
        $tbls = '';

        foreach ($tables as $t)
        {
            if ($tbls) $tbls.=', ';
            $tbls .= $t.' WRITE';
        }
           //echo "LOCK TABLES $tbls <br/>";
        $this->Query("LOCK TABLES $tbls", $throw_exceptions );
    }



    /**
     * Desloquea las tablas para escritura, si no está bloqueada se ignora la llamada
     * @param bool   $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @see Lock()
     */
    public function Unlock( $throw_exceptions=false )
    {
        $this->locked = false;
        $this->Query("UNLOCK TABLES", $throw_exceptions );
    }



    /**
     * Devuelve un csv con los valores de una columna
     * @param string $query consulta que devuelva una sóla columna
     * @param bool   $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @return csv
     * @uses Column()
     * @see Csv_str()
     */
    public function Csv ($query)
    {
        $data = $this->Column($query, $throw_exceptions);

        if (!$data) return null;

        return implode(',', $data);

    }



    /**
     * Devuelve un csv con los valores de una columna, siendo cada valor una cadena etrecomillada y segura con sql
     * @param string $query consulta que devuelva una sóla columna
     * @param bool   $throw_exceptions  true indica que se lanzarán excepciones en caso de error
     * @return csv
     * @uses Column()
     * @see Csv()
     */
    public function Csv_str ($query)
    {
        $data = $this->Column($query, $throw_exceptions);

        if (!$data) return null;

        $out = array();

        foreach ($datas as $d)
            $out[]= $this->Str($d);

        return implode(',', $out);

    }


    // ---- Funciones añadidas y adaptadas de atlas posteriores --

    /**
     *  Verifica que no hay otro registro en la tabla con ese mismo valor. 2014
      * Añadido $row_id de atlas modernos, pero cambiado orden para compatibilzar con atlas 3
     */
    public function  Is_unique_old ($old_value, $new_value, $table, $field, $where_extra='',  $row_id=null)
    {
        $old_sql =  $this->Safe($old_value);
        $new_sql =  $this->Safe($new_value);

        if (!$new_value )return true;

        if ($old_value and $old_sql===$new_sql )
            return true;

        $sql = "SELECT COUNT($field) FROM $table WHERE $field = $new_sql $where_extra ";

        if (null !==$row_id) $sql .= ' AND id!='.  $this->Safe ($row_id);

        //echo $sql;
        $count = $this->Scalar($sql);

        return $count<1;
    }


    /**
     * Verifica si si el valor del campo es único en la tabla. 2017
     * @param string $table_name   Tabla a comporbar
     * @param string $field_name   Nombre del campo en la BD a comprobar
     * @param misc   $value        Valor a comprobar
     * @param type   $id_to_ignore Ignora el registro de la fila indicada. NULL:si es único en toda la tabla.
     * @param string $id_field     Nombre del campo id en la BD
     * @return type
     */
    public function Is_unique($table_name, $field_name, $value , $id_to_ignore=null, $id_field='id')
    {
        $value_db = $this->Safe($value);

        $where  = $id_to_ignore !==NULL
                ? "AND $id_field!=". DB::$db->Safe($id_to_ignore)
                : '' ;

        return $this->Scalar("SELECT COUNT($id_field) FROM $table_name WHERE $field_name = $value_db $where  LIMIT 1" )<1;
    }




    /**
     * Retorna valores son seguros para una consulta sql.
     * @param misc $value datos a asegurar
     */
    public function  Safe($value)
    {
        if (is_null   ($value)) return 'NULL';
        else if (is_bool   ($value)) return (int)$value;
        else if (is_numeric($value) and substr($value,0,1)!=='0') return      $value;
        else                         return $this->Str($value);
    }



    /**
     * Crea una copia de un vector de tal manera que los valores son seguros para una consulta sql.
     * @param array $items Vector con los datos a asegurar
     */
    public function  Safe_vector ($items)
    {
        $out = array ();
        foreach($items as $index=>$value)
            $out[$index] = $this->Safe($value);

        return $out;
    }

    /**
     * Realiza una consulta para actualizar pasándole los datos en un vector
     * @param string  $condition        Si true realiza un update, si  false un insert
     * @param string  $table            Tabla a modificar
     * @param string  $where            Condiciones del WHERE
     * @param array[string]string $data Datos, indice: nombre del campo, valor:valor del campo, debe ser seguro con sql, no se procesa
     * @return mysqli_result|bool  false:ocurrió un error. Objeto mysqli_result para consultas SELECT, SHOW, DESCRIBE or EXPLAIN . True: par el resto de consultas exitosas
     * @since 2.0
     */
    public function  Update_or_insert($condition, $table, $data, $where , $do_safe=false)
    {
        if ($condition)
            return $this->Update($table, $data, $where , $do_safe);
        else
            return $this->Insert($table, $data, $do_safe);
    }


    /**
     * Realiza una consulta para actualizar pasándole los datos en un vector.
     * La tabla contendrá un campo id autonumérico.
     * @param string  $table            Tabla a modificar
     * @param array[string]string $data Datos, indice: nombre del campo, valor:valor del campo, debe ser seguro con sql, no se procesa
     * @param int    $id               Id a actualizar, si evaluea a false sera una creación. SE pasara por referencia, si el es una iserción se actualizara con el campo autonumérioco
     * @param bool $do_safe Pasar todos los valores por la función Safe o usar directamente;
     * @return   bool  Si se realizo la operación con exito
     * @since 2.0
     */

    public function  Update_or_insert_id($table, $data, &$id , $do_safe=false, $id_field='id')
    {
        if ($id!==null)
            return (bool) $this->Update($table, $data, "$id_field=$id" , $do_safe  );

        if (!$this->Insert($table, $data,$do_safe )) return false;

        $id = $this->Insert_id();
        return true;
    }





    /**
     * Realiza una consulta para insertar pasándole los datos en un vector
     * @param string  $table            Tabla a modificar
     * @param array[string]string $data Datos, indice: nombre del campo, valor:valor del campo
     * @param bool $do_safe Pasar todos los valores por la función Safe o usar directamente;
     * @return mysqli_result|bool  false:ocurrió un error. Objeto mysqli_result para consultas SELECT, SHOW, DESCRIBE or EXPLAIN . True: par el resto de consultas exitosas
     * @since 1.13
     */
    public function  Insert($table, $data, $do_safe = false )
    {
        if ($do_safe)
            foreach ($data as $k=>$v)
                $data[$k]= $this->Safe ($v);

        $sql = "INSERT INTO $table (".join(",\n ",array_keys($data)).') VALUES ('.join(",\n ", $data).')';
        return $this->Query($sql);
    }







    /**
     * Realiza una consulta para actualizarr pasándole los datos en un vector
     * @param string  $table            Tabla a modificar
     * @param array[string]string $data Datos, indice: nombre del campo, valor:valor del campo, debe ser seguro con sql, no se procesa
     * @param string  $where            Condiciones del WHERE
     * @return mysqli_result|bool  false:ocurrió un error. Objeto mysqli_result para consultas SELECT, SHOW, DESCRIBE or EXPLAIN . True: par el resto de consultas exitosas
     * @param bool $do_safe Pasar todos los valores por la función Safe o usar directamente;
     * @since 1.13
     */
    public  function  Update($table, $data, $where, $do_safe=false )
    {
        $sets = array ();

        if ($do_safe)
            foreach ($data as $k=>$v)
                $data[$k]= $this->Safe ($v);

        foreach ($data as $field=>$value)
            $sets [] = "$field = $value";

        $sql = "UPDATE $table SET ".join(', ', $sets)." WHERE $where";

        return $this->Query($sql);
    }





    /**
     * Asigna automáticamente los valores desde Post a un objeto.
     *
     * Atlas 2016
     * Primero se intenta usar índices post Set_{índice}, en su defecto se intenta con el atributo{índice}
     * @param $query string Consulta sql, tipo: "SELECT * FROM table WHERE id=x";
     * @param csv|array $csv ´´
     * @see \Atlas\Fil_object
     * @return bool false=fallo al obtener los datos
     */

    public function  Fill_object($object, $query, $csv_ignore=null, $csv_only=null)
    {
        $data = $this->Row_assoc($query);
        if (!$data) return false;

        ATLAS::Fill_object( $object, $data,$csv_ignore, $csv_only);

        return true;
    }



    public function profile_init()
    {
        self::$db->Query("set profiling_history_size=100");
        self::$db->Query("set profiling=1");
    }

    public function profile_result()
    {
        return self::$db->Rows_objects('SHOW PROFILES', FALSE);
    }



    public function profile_result_html($explain=FALSE)
    {
        $html = "<br/><hr><h1>Mysql profile</h1>". DB_REPORT::Html_('SHOW PROFILES' , FALSE, FALSE);

        $profiles = self::$db->Rows_objects('SHOW PROFILES', FALSE);

        if (!$explain)
            return $html;

        if ($profiles)
        {
            foreach ($profiles as $p)
            {
                if ($p->Duration<'0.01') continue;
                $html.="<h1>Query $p->Query_ID - &nbsp; $p->Duration s</h1><pre style='font-weight:bold'>".htmlspecialchars($p->Query)."<pre>";
                $html.=DB_REPORT::Html_('EXPLAIN '.$p->Query , FALSE, FALSE);
            }
        }

        return $html;
    }



}


