[xperimentX atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Accessing your main database from anywhere](#accessing-your-main-database-from-anywhere)

* [Configure and connect](#configure-and-connect)
    * [Configuration class Db\Db_cfg](#configuration-class-db-db-cfg)
    * [Setting the cfg property](#setting-the-cfg-property)
    * [Pass Db\Db_cfg configuration to Db constructor ](#pass-db-db-cfg-configuration-to-db-constructor)
    * [Assigning the configuration to the cfg property](#assigning-the-configuration-to-the-cfg-property)
    * [Establish the connection](#establish-the-connection)

* [Re-use an existing mysqli connection.](#re-use-an-existing-mysqli-connection)

* [Db Properties](#db-properties)
* [Generic queries](#generic-queries) 
* [Get Data](#get-data)
* [Safe sql](#safe-sql)
* [Create and update rows](#create-and-update-rows)
* [Other tools](#other-tools) 
* [Database Info](#database-info)
* [Benchmarking, query metrics](#benchmarking--query-metrics) 


* [Database Forge](#database-forge)
  * [Forge methods from Db object](#forge-db-methods)
  * [Create a table](#create-a-table)
  * [Alter a table](#alter-a-table)

* [Migrations](Database-migrations.md)


# Database Reference
Namespace Xperimentx\Atlas\Db;

### Accessing your main database from anywhere

**Xperimentx\Atlas\Db** is the class used to manage your databases.

The first **Db** object created  will registered as the default Object.

You can change this default object using *Make_default()* method.

*Db::Obj()* returns the default Db.

This simplifies the development by making this main database easily accessible.

Atlas Database objects use this main database by default. 
You can use as many **Db** object as you wish.

|                      |                                      |
|:---------------------|:-------------------------------------|
| static **Obj**() :Db |Returns the Default Db.               |
| **Make_default**()   |Sets this Db as the default Db object.|
    



## Configure and connect
The **Db\Db_cfg** class is used to configure the database connection.

You can  can setup the **Db cfg** property, or assign Db\Db_cfg to de Db object.

### Configuration class Db\Db_cfg.

|        | properties        | Default value     |                                    |
|:-------|-------------------|:------------------|:-----------------------------------|
| string | $server           | 'localhost'       | Database host. 'p:host' for persistent. |                 
| string | $user_name        | null              | User name.                         |        
| string | $password         | null              | Password.                          |        
| string | $db_name          | null              | Database name.                     |        
|        |                   |                   |                                    |        
| string | $port             | 3306              | Port.                              |        
| string | $socket           | null              | Socket.                            |        
| string | $charset          | 'utf8'            | Charset.                           |          
| string | $collation        | 'utf8_general_ci' | Collation.                         |                     
| string | $engine           | 'InnoDB'          | Engine                             |    
|        |                   |                   |                                    |        
| bool   | $throw_exceptions | false             | Throw exceptions on mysqli errors. Do not affect to connect errors |         
| bool   | $throw_exceptions_on_connect| false   | Throw exceptions on connect errors.|


### Setting the cfg property.
 
```php
use Xperimentx\Atlas\Db;

$db = new Db();
$db->cfg->user_name = 'atlas_db_user';
$db->cfg->password  = 'atlas_db_passwd';
$db->cfg->db_name   = 'atlas_demo_db';
```

### Assigning the configuration to the cfg property

```
use Xperimentx\Atlas\Db;

$db_b = new Db();
$db->cfg = $my_db_cfg;
````

### Pass Db\Db_cfg configuration to Db constructor 

```php
$db_a = new Db($my_db_cfg);
```


### Pass configuration class name to Db constructor 

In this case based on the current environment.

```php
use Xperimentx\Atlas\Db;
use Xperimentx\Atlas\Enviroment;

$db = new Atlas\Db('Config\Database_'.Environment::Get_stage());
```



### Establish the connection

|Db method   |Info   |
|:-----------|:------|
|**Connect**  () :bool|Connects to the MySQL server.|
|**Connect_or_die**  ([$message]) |Connects to the MySQL server, if not connection terminates the current script.|

### Connect

```php
use Xperimentx\Atlas\Db;

$db = new Db($my_database_configuration);

if ($db->Connect())
{
     echo "DB connected \n";
}
else
{    
    print_r($db->last_error);
    exit();
}
```

### Connect_or_die

A deadly alternative, if can't connect terminates the current script.

If [Environment::Is_development()](Environment.md) shows the error message.

```php
use Xperimentx\Atlas\Db;

$db = new Db($my_database_configuration);
$db->Connect_or_die();
```

---

## Re-use an existing mysqli connection.

```php
use Xperimentx\Atlas\Db;

$db = new Db();
$bd->mysqli = My_mysqli_object;

```

---

## Db Properties

|Db property        |Type           |Info            |
|:------------------|:--------------|:---------------|
| $mysqli           |mysqli         |MySQLi Handler.                                |
| $profiles         |Profile_item[] |Profiles or successful calls, benchmarking.    |
| $errors           |Profile_item[] |Errors.                                        |
| $last_error       |Profile_item   |Last error. Null if last call is successful.   |
| $last_profile     |Profile_item   |Last profile. Null if error in last call.      |
| $cfg              |Db_cfg         |Configuration, options.                        |
| static $db        |Db             |First object connected. The main Db object.    |



---



## Generic queries

|Db method   |Info   |
|:-----------|:------|
|**Query**    ($query, [$caller_method]) :mixed,null| Performs a query on the database.|
|**Query_ar** ($query, [$caller_method]) :int| Performs a query on the database and returns the number of affected rows.|


---


## Get Data

|Db method   |Info   |
|:-----------|:------|
|**Scalar** ($query ) :scalar,null            | Returns first column of first row of a query result.|
|**Row**    ($query, [$class_name]) :object,null| Returns first row for a query as an object.|
|**Rows**   ($query, [$class_name]) :array | Returns an array of objects for a query statement|    
|**Column** ($query)              :array | Returns a the first column of a query as array.|
|**Vector** ($query)              :array | Returns a simple array index=>scalar_value from a query.|    


---

## Safe sql

|Db method   |Info   |
|:-----------|:------|
|**Str**       ($scalar) :string|Escapes special characters in a string for use in an SQL statement, between single quotes '.|    
|**Safe**      ($value) :string| Returns a safe value from a scalar for an SQL statement.|    


---


## Create and update rows.

|Db method   |Info   |
|:-----------|:------|
|**Insert**           ($table, $data, [$do_safe]) :int|Insert into statement.|    
|**Update**           ($table, $data, $where, [$do_safe]) :int|Update statement.|    
|**Update_or_insert** ($table, $data, &$key_value , [$key_field_name], [$do_safe]) :int|Update a row (key!=null) or Insert a new row  (key value=null)|    


---


## Other tools

|Db method   |Info   |
|:-----------|:------|
|**Is_unique** ($table_name, $field_value , $field_name, [$key_value_to_ignore], [$key_field_name]) :bool|Checks if the value of the field is unique in the table.|    
|**Active_record_class_maker** ($table, $class_name, [$parent_class_name]) :string |Generates a base code for an active record class based in the fields of a table.|
|**Throw_exceptions () | Configure to throw exceptions on mysqli errors and on connect errors.|

 
---



## Database Info

|Db method   |Info   |
|:-----------|:------|
|**Show_columns**         ($table) :object[]|Shows columns info form a table.|    
|**Show_column_names**    ($table) :string[]|Shows columns names form a table.|    
|**Show_create_database** ($database_name, [$if_not_exists]) :string| Creates a database.|
|**Show_create_table**    ($table) :string  |Shows CREATE TABLE for a table.|
|**Show_tables**          ([$like], [$database_name]) :string[]| Show table names from a database.|



---

## Benchmarking, query metrics

|Db method   |Info   |
|:-----------|:------|
|**Describe**            ($query) :object[] | Describes a query.|
|**Describe_html_table** ($query) :string   | Describes a query in a html table.|
|**Pofiles_html_table**    () :string| Returns a basic report of profiles as a html table.|
|**Pofiles_describe_html** () :string| Returns a report with query description as html.|
 

---

# Database forge.

## Forge Db methods

|Db method   |Info   |
|:-----------|:------| 
|**Create_database** ($database_name, $collate, $if_not_exists) :int |Creates a new data base|    
|**Drop_database**  ($database_name, $if_exists) :int|Drops a database.|    
| |  |
|**Alter_table**      ($table) :Alter_table  |Returns a handler to alter a table |
|**Create_table**     ($table) :Create_table |Returns a handler to create a table |
|**Optimize_table** ($table) :array|Optimizes a table.|
|**Drop_table**     ($table, $if_exists) :int|Drops a table.|    
|**Truncate_table** ($table) :int|Truncates a table.| 
|   |  |
|**Drop_view**      ($view_name, $if_exists) :int|Drops a view.|    
     

## Create a table.

```php
use Xperimentx\Atlas\Db;

$maker = new Db\Create_table('table_name', $my_db);
$maker->Add_column_id ();
$maker->Add_column ('VARCHAR(50)', 'name')->Set_comment('Lore ipsum');
$maker->Add_column ('DATETIME'   , 'creation_date');
$maker->Add_column ('TINYINT'    , 'delete_me');
$maker->engine = Db::ENGINE_MYISAM;
$maker->Run();     
```

or

```php
$maker = $my_db->Create_table('table_name');
$maker->Add_column_id ();
//...
$maker->Run();     
```



## Alter a table

```php
use Xperimentx\Atlas\Db;
use Xperimentx\Atlas\Db\Column;

$alter = new Db\Alter_table('table_name', $my_db);
$alter->Change_column ('name_2' , new Column('VARCHAR(250)', 'last_name'));
$alter->Add_column    (new Column ('TEXT', 'notes'));
$alter->Drop_column   ('delete_me');
$alter->Set_engine    (Db::ENGINE_INNODB);
$alter->Run(); 
```

```php
$alter = $my_db->Alter_table('table_name');
$alter->Drop_column   ('delete_me');
$alter->Run();     
```

## Db\Create_table

| Db\Create_table   |       | Properties                       |
|:---------------|:------|:---------------------------------|
|$table          |string | Table name                       |
|$charset        |string | Charset.                         |
|$collation      |string | Collation.                       |
|$engine         |string | Engine                           |
|$auto_increment |string | Auto increment.                  |
|$comment        |string | Comment.                         |

| Db\Create_table |Methods                     |
|:----------------|:---------------------------------------|
|**__construct** ($table, [$db_object]);| |
|   |  |
|**Add_column** ($field_type, $field_name, [$default_value], [$is_null_allowed]); |Adds a column field.  Create a column field sql helper |
|**Add_column_id** ([$field_name]) :Column | Adds column auto increment  primary key.     | 
|   |  |
|**Add_index($index_name, $fields ,[$type]);| Adds an index |
|**Add_primary_key** ($fields) | Adds the primary key | 
|**Add_foreign_key** ($symbol, $fields, $foreign_table, $foreign_fields,$on_delete,$on_update): int | Adds a foreign key |
|   |  |
|**Run** () ;int| Runs create table query
|**Run_if_not_exists** () :int |Runs create table query if not exists the table |


## Db\Alter_table

| Db\Alter_table |Methods                     |
|:----------------|:---------------------------------------| 
| **__construct** ($table, [$db_object]) | |
| **Rename** ($new_table_name) :$this | Renames the table|
| **Set_engine** ($engine) :$this |  Changes table engine |
| **Set_comment** ($comment) :$this | Set comment |
|   |  |
| **Add_column** ($column) :$this |  Adds a field / column. |
| **Change_column** ($old_column_name, $column) :$this | Changes a column |
| **Drop_column** ($field)  :$this | Drops a column or a set of columns from the table |
|   |  |
| **Add_index** ($index_name, $fields ,[$type]) :$this |   Adds an index |
| **Drop_index** ($index_name) :$this | Drops an index |
|   |  |
| **Add_primary_key** ($fields) :$this |  Adds the primary key  |
| **Drop_primary_key** ($index_name)  :$this | Drops the primary key |
|   |  |
| **Add_foreign_key** ($symbol, $fields, $foreign_table, $foreign_fields,$on_delete, $on_update ) :$this | Adds a foreign key |
| **Drop_foreign_key** ($symbol) :$this |  Drops a foreign key |
|   |  |
| **Run** () :int| Runs alter table query |


  
## Db\Column

|Db\Column property| Default | Related Method  |          
|:------------------|:--------|:---------|
|$collation         | null  | **Set_collation**      ($value)      :$this |      
|$comment           | null  | **Set_comment**        ($value)      :$this |          
|$default_value     | null  | **Set_default_value**  ($value)      :$this |   
|$default_raw       | null  | **Set_default_raw**    ($value)      :$this |   
|$field_name        | null  | **Set_field_name**     ($value)      :$this | 
|$is_auto_increment | null  | **Set_auto_increment** ($value=true) :$this |
|$is_null_allowed   | true  | **Set_nullable**       ($value=true) :$this |
|$is_unsigned       | false | **Set_unsigned**       ($value=true) :$this |
|$is_zerofill       | false | **Set_zerofill**       ($value=true) :$this |
|$type              | null  | **Set_type**           ($value)      :$this | 
|*                  |       | **Set** ($attribute_name,$value) :$this|



### Db::\Column::__construct (...)
($field_type, $field_name, $default_value=null, $is_null_allowed=true)

|Type   |Param            |       |
|:------|:----------------|:------|
|string |$field_type      | Type of column:<br>TINIYINT, INT, BIGINT , DECIMAL(10,2),<br>CHAR(50), VARCHAR(50)<br>DATE, TIME, DATETIME ...|
|string |$field_name      | Field name.  `` will be added.|
|scalar |$default_value   | Scalar default value.|
|bool   |$is_null_allowed | Can the value of this field can be null?  |
 





