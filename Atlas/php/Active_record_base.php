<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */
namespace Atlas;



/**
 * AR_BASE - Active Record Base
 *
 * Basado en la simplificación de varios traits y clases de Atlas 2016 Auto_properties, Active_record, Model_ER.
 * Se han eliminado las relaciones bd y mapeo bd, log, integración von formularios, listados y mensajes automáticos.
 *

 * Id, La clave siempre sera el campo id autonumérico
 *
 * Los atributos que empizan con '_' no entraran en contacto con la base de datos.
 * los usaremos para campos informativos, confiuración o auxiliares.
 *
 * Por compatibilidad con Atlas 3 se añade el atributo $db de tibo DB
 *
 *
 * <b>Cache durante la ejecuión:</b>
 *
 * Se puede dar valor TRUE a la propiedad $_cache_used_by_default para que rureante la ejecuión de un script cuando se
 * llame  a obj_loat($id) si ya se cargo previamente ese id retorne el objeto previamente cargado.
 *
 * @author Roberto González Vázquez
 *
 */

class Active_record_base  extends  Auto_properties
{
     /** Identificador unívoco de la información, clave en la base de datos
        @var int        */
    public $id                  = NULL ;

    /** Nombre de la tabla en la bd
      * @var string   */
    protected $_table = NULL;



    /** Nombre del campo usado como "nombre"  en la BD
      * @var string        */
    protected $_name_field    =  'name';


    /** Nombre del campo usado como "id" en la BD
      * @var string        */
    protected $_id_field    =  'id';


    /**
     * Chaché de objetos
     * @see obj_load_cached()
     * @see cache_load
     */
    static private $_cache = array();

    /**
     * Se usa por defecto la cache cuando se llama a obj_load
     * @var bool
     */
    static protected $_cache_used_by_default = FALSE;



    /**
     *
     * @param NULL|int $id Identificador unívoco de la información, clave en la base de datos. Null: nuevo elemento
     *
     * @param $basic_l
     */
    function __construct($id_or_data=null)
    {
        if(null!==$id_or_data)
            $this->load($id_or_data);
    }


    /**
    * Asigna automáticamente los valores desde un vector a un objeto.
    * Primero se intenta usar métodos _set_{índice}, en su defecto se intenta con el atributo{índice}
    * @param csv|array $csv ´´
    * @param $data array||object vector:indice=nombre_atributo
    */
    public function assign_data ($data)
    {
        if(isset($data))
        {
            foreach ($data as $index => $value)
            {
                if     (method_exists  ($this, "_set_$index"))   $this->{"_set_$index"}($value);
                elseif (property_exists($this, $index))          $this->{$index} = $value;
            }
        }
    }


    /**
     * Carga los datos.
     * Llama a <b>on_load</b> cuando se carga con éxito.
     * @param int|array|object $id_or_data Identificador unívoco el la base de datos, vector u objetos con lso datos de la BD
     * @reuturn bool
     */
    public function load($id_or_data)
    {
        $this->id=null;

        if (!$id_or_data) return FALSE;

        if (is_numeric($id_or_data))
        {
            $this->assign_data(DB::$db->Row_object("SELECT * FROM $this->_table WHERE $this->_id_field=".(int)$id_or_data));
        }
        elseif (is_array($id_or_data) or is_object($id_or_data))
        {
            $this->assign_data($id_or_data);
        }

        if (!is_null($this->id))
        {
            $this->on_load();
            return TRUE;

        }
        return FALSE;
    }


    /**
     * Carga los datos pasando campo y filtrado WHERE
     *
     * Llama a On_load cuando se carga con éxito.
     * @param string $where_sql filtos sql ,"SELECT * FROM table WHERE $where_sql"
     * @return boolean
     */
    public function load_by_where_sql($where_sql)
    {
        $this->id=null;
        $data = DB::$db->Row_object("SELECT * FROM $this->_table WHERE $where_sql");

        if (!$data) return FALSE;

        $this->assign_data($data);

        if (!is_null($this->id))   { $this->on_load(); return TRUE;}
        return FALSE;
    }

    /**
     * Carga los datos pasando campo y valor
     *
     * Llama a On_load cuando se carga con éxito.
     * @param string $field_name
     * @param misc $field_value
     * @return boolean
     */
    public function load_by_field($field_name, $field_value)
    {
        return $this->load_by_where_sql("$field_name=". DB::$db->Safe($field_value));
    }




    /**
     * Obtiene una instacia de un objeto usando el id
     *
     * Si no existen los datos o el id cargado es NULL devolverá NULL
     * @param int $id Identificador
     * @param bool|NULL $use_cache Usar cache, NULL=usa el valor de $_cache_used_by_default
     * @return static
     */
    static public function  obj_load($id, $use_cache=NULL)
    {
        if ($use_cache or $use_cache===NULL && static::$_cache_used_by_default )
        {
            $cached_id=get_called_class().' - '.$id;

            if (isset(self::$_cache[$cached_id]))
            return self::$_cache[$cached_id];

            $obj = self::$_cache[$cached_id] = new static($id);
        }

        else
        {
            $obj = new static($id);
        }

        return is_null($obj->id) ? NULL: $obj;
    }


    /**
     * Obtiene una instacia de un objeto usando un filtro WHERE SQL
     *
     * Si no existen los datos o el id cargado es NULL devolverá NULL
     *
     * @param type $where_sql filtos sql ,"SELECT * FROM table WHERE $where_sql"
     * @return static
     */
    static public function obj_load_by_where_sql($where_sql,$use_cache=NULL,$cache_id=NULL)
    {
        if ($use_cache or $use_cache===NULL && static::$_cache_used_by_default )
        {
            $cached_id=($cache_id)?get_called_class().' - '.$cache_id :get_called_class().' - '.crc32($where_sql);

            if (isset(self::$_cache[$cached_id]))
                return self::$_cache[$cached_id];
            $obj = new static();
            $obj->load_by_where_sql($where_sql) ;
            self::$_cache[$cached_id]=$obj;
            return is_null($obj->id) ? NULL: $obj;

        }

        $obj = new static();
        $obj->load_by_where_sql($where_sql) ;
        return is_null($obj->id) ? NULL: $obj;
    }



    /**
     * Obtiene una instacia de de objeto pasando campo y valor
     *
     * Usa WHERE fieldname=valor
     *
     * @param string $field_name
     * @param misc   $field_value
     * @return static
     */
    static public function obj_load_by_field($field_name, $field_value)
    {
        $obj = new static();
        $obj->load_by_field($field_name, $field_value);
        return is_null($obj->id) ? NULL: $obj;
    }




    /**
     * Comprueba si se puede eliminar el registro actual de la tabla
     * @return boolean
     */
    function can_delete()
    {
        // el id NULL, por no existir el registro y 0 por ser un id reservado no se puede eliminar

        return (bool)$this->id;
    }




    /**
     * Elimina este elmenento de la base de datos
     * @return bool
     */
    public function delete($check_if_can_delete=true)
    {
        if ($check_if_can_delete and !$this->can_delete())
            return FALSE;

        return DB::$db->Query("DELETE FROM $this->_table WHERE $this->_id_field=".(int)$this->id);
    }



     /**
     * Vector id=>name
     * @param string $field_name Campo nombre, NULL=>static::_name_field
     * @param string $order_by    sql "para ORDER BY"
     * @param string $sql_extra   entre "FROM <table>" y "ORDER BY"
     * @return array
     */
    static public function vector( $field_name=null, $sql_extra=NULL, $order_by='name')
    {
        $aux = new static();

        if (!$field_name) $field_name = $aux->_name_field;

        return DB::$db->Vector("SELECT $aux->_id_field id, $field_name  name FROM $aux->_table $sql_extra ORDER BY $order_by");
    }



    static public function name_from_id($id ,$field_name=null)
    {
        $aux = new static();

        if (!$field_name)
            $field_name = $aux->_name_field;
        return DB::$db->Scalar("SELECT $field_name FROM $aux->_table WHERE $aux->_id_field=".(int)$id);
    }




    /**
     * Guardar los datos
     *
     * Si $id !== NULL Update
     * Si $id === NULL Insert, el id se actualizará
     * @return bool
     */
    public function save()
    {
        $is_new =   (null == $this->id );

        //preparación
        if (!$this->on_save_preparation($is_new)) return FALSE;

        $data=array();
        $columns = DB::$db->Col("SHOW COLUMNS FROM $this->_table");

        foreach($this as $field=>$value)
        {
            if  (substr($field,0,1)=='_')     continue;  //internas
            if  (!in_array($field, $columns)) continue; //propiedades extra
            $data[$field]=$value;
        }


        if (DB::$db->Update_or_insert_id($this->_table, $data,$this->id,true, $this->_id_field))
        {
            $this->on_save($is_new);
            return  TRUE;
        }

        return FALSE;
    }


     /**
     * Guardar los datos de los campos seleccionados en la base de datos. Solo para UPDATE.
     * No llama a los on_....
     * @return bool
     */
    public function update_fields($fields_csv)
    {
        if (!$this->id ) return FALSE;

        $fields =\Atlas::Array_csv($fields_csv) ;

        foreach($fields as $field)
        {
            $data[$field]=$this->$field;
        }


        return DB::$db->Update($this->_table, $data,"$this->_id_field=$this->id",true);
    }



    /**
     * Copia datos de otro objeto,vector.
     *
     * Se sublican los atributos coincidentes.
     * Se ignoran las propiedades que empiezan por '_' y el campo id;
     */
    public function copy_data($obj_to_copy)
    {
        foreach($obj_to_copy as $field=>$value)
        {
            if  ($field !=$this->_id_field and substr($field,0,1)!='_' and property_exists($this, $field) )
            {
                $this->$field = $value;
            }
        }
    }



    /**
     * Asigna automáticamente los valores desde Post.
     * Primero se intenta usar Set_{índice}, en su defecto se intenta con el atributo{índice}
     */
    public function post()
    {
         \ATLAS::Fill_object($this, $_POST , 'id');
    }



    /**
     * Verifica si si el valor del campo es único en la tabla.
     * Ignorando el registro actual del cliente
     * @param string $field_name   Nombre del campo en la BD a comprobar
     * @param misc   $value        Valor a comprobar
     * @return bool
     */
    public function is_unique_field($field_name, $value )
    {
        return DB::$db->Is_unique($this->_table, $field_name, $value, $this->id, $this->_id_field );
    }



    // Hooks -----------------------------------------------------------------------------------------------------------

    /**
     * Es llamada cuando se realiza una llamada a "load", "load_by_field" o "load_by_where_sql" con éxito.
     */
    protected function on_load()
    {

    }



    /**
     * Es llamada cuando se realiza una llamada a "save" con éxito.
     * El id ya está actualizado al ser llamda esta función en el caso de nuevos registros
     * @param type $is_new TRUE:nuevo registro, crear; FALSe: update, actualizar
     */
    protected function on_save($is_new)
    {

    }

    /**
     * Es llamada cuando se realiza al prencipio de una llamada"save", antes de procesar los datos.
     * Aborta la ejecución del save si retorna FALSE
     * @param type $is_new TRUE:nuevo registro, crear; FALSe: update, actualizar
     * @return bool TRUE: se puede continuar con el save. FALSE: se debe abortar el guardado
     */
    protected function on_save_preparation($is_new)
    {
        return TRUE;
    }

}

