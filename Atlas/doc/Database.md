[Documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Accessing your main database from anywhere](#accessing-your-main-database-from-anywhere)
* [Configure the database connection](#configure-the-database-connection)
* [Establish the connection](#establish-the-connection)
* [Db Properties](#db-properties)
* [Connect](#connect) 
* [Generic queries](#generic-queries) 
* [Get Data](#get-data)
* [Safe sql](#safe-sql)
* [Create and update rows](#create-and-update-rows)
* [Other tools](#other-tools) 
* [Database Info](#database-info)
* [Database Forge](#database-forge)
  * [Create a table](#create-a-table)
  * [Alter a table](#alter-a-table)
  * [Forge methods from Db object](#forge-db-methods)
* [Migrations](Database-migrations.md)
* [Benchmarking, query metrics](#benchmarking--query-metrics) 



# Database Reference
## Accessing your main database from anywhere

'Xperimentx\Atlas\Db` is the class used to manage your databases.

The first created `DB` object will be registered in the static and public 
property `Db::$db` as the main database.

This simplifies the development by making this main database easily accessible.

Atlas Database objects use this main database by default. 
You can use as many `Db` object as you wish.


## Configure the database connection

### Option 1, setting the cfg property

Create an object and configure the connection using the `cfg` property.

```php
use Xperimentx\Atlas\Db;

$db = new Db();
$db->cfg->user_name = 'atlas_db_user';
$db->cfg->password  = 'atlas_db_passwd';
$db->cfg->db_name   = 'atlas_demo_db';
```

### Option 2, whit a configuration object

Create a database configuration object `Db_cfg`

And then assign it to a `DB` object using the constructor 
or assign it to the ``$cfg` property.


### Using the constructor.

```php
$db_a = new Db($db_cfg);
```

### Assigning the configuration to the cfg property

```
$db_b = new Db();
$db->cfg = $db_cfg;
````



## Establish the connection

|Db method   |Info   |
|:-----------|:------|
|**Connect**  () :bool|Creates a new mysqli object and connects it to the MySQL server.|

```php
use Xperimentx\Atlas\Db;

$db = new Db($my_database_configuration);

if ($db->Connect())
{
     echo "DB connected \n";
}
else
{
    echo "Error \n";
    print_r($db->last_error);
    die (":( \n");
}
```



## Db Properties

|Db property        |Type           |Info            |
|:------------------|:--------------|:---------------|
| $mysqli           |mysqli         |MySQLi Handler.                                |
| $profiles         |Profile_item[] |Profiles or successful calls, benchmarking.    |
| $errors           |Profile_item[] |Errors.                                        |
| $last_error       |Profile_item   |Last error. Null if last call is successful.   |
| $last_profile     |Profile_item   |Last profile. Null if error in last call.      |
| $cfg              |Db_cfg         |Configuration, options.                        |
| $throw_exceptions |bool           |Throw Db_exception exceptions on mysqli errors.|
| static $db        |Db             |First object connected. The main Db object.    |




## Generic queries

|Db method   |Info   |
|:-----------|:------|
|**Query**    ($query, $caller_method=null) :mixed,null| Performs a query on the database.|
|**Query_ar** ($query, $caller_method=null) :int,null| Performs a query on the database en returns the number of affected rows.|


## Get Data

|Db method   |Info   |
|:-----------|:------|
|**Scalar** ($query ) :scalar            | Returns first column of first row of a query result.|
|**Row**    ($query, $class_name) :object| Returns first row for a query as an object.|
|**Rows**   ($query, $class_name) :array | Returns array of objects for a query statement|    
|**Column** ($query)              :array | Returns a the first column of a query as array.|
|**Vector** ($query)              :array | Returns a simple array index=>scalar_value from a query.|    

## Safe sql

|Db method   |Info   |
|:-----------|:------|
|**Str**       ($scalar) :string|Escapes special characters in a string for use in an SQL statement, between single quotes '.|    
|**Safe**      ($value) :string| Returns a safe value from a scalar for an SQL statement.|    

## Create and update rows.

|Db method   |Info   |
|:-----------|:------|
|**Insert**           ($table, $data, $do_safe) :int|Insert into statement.|    
|**Update**           ($table, $data, $where, $do_safe ) :int|Update statement.|    
|**Update_or_insert** ($table, $data, &$key_value , $key_field_name, $do_safe) :int|Update a row (key!=null) or Insert a new row  (key value=null)|    


### Other tools

|Db method   |Info   |
|:-----------|:------|
|**Is_unique** ($table_name, $field_value , $field_name, $key_value_to_ignore, $key_field_name) :bool|Checks if the value of the field is unique in the table.|    


 


## Database Info

|Db method   |Info   |
|:-----------|:------|
|**Active_record_class_maker** ($table, $class_name, $parent_class_name) :string |Generates a base code for an active record class based in the fields of a table.|
|**Show_columns**         ($table) :object[]|Shows columns info form a table.|    
|**Show_column_names**    ($table) :string[]|Shows columns names form a table.|    
|**Show_create_database** ($database_name, $if_not_exists) :string| Creates a database.|
|**Show_create_table**    ($table)|Shows CREATE TABLE for a table.|
|**Show_tables**          ($like=null, $database_name) :string[]| Show table names from a database.|





---

## Database forge.

### Create a table.

```php
use Xperimentx\Atlas\Db;

$maker = new Db\Create_table('table_name');

$maker->Add_column ('INT'        , 'id'  )->Set_auto_increment()->Set_comment('asdasdasda');
$maker->Add_column ('VARCHAR(50)', 'name');
$maker->Add_column ('DATETIME'   , 'creation_date');
$maker->Add_column ('TINYINT'    , 'delete_me');
$maker->engine = Db::ENGINE_MYISAM;

if ($maker->Run())
     echo "Databas created";

else print_r(Db::$db->last_error);
```

### Alter a table
```php
use Xperimentx\Atlas\Db;

$alter = new Db\Alter_table('table_name');
$alter->Change_column ('VARCHAR(250)', 'name');
$alter->Add_column    ('TEXT'        , 'notes');
$alter->Drop_column   ('delete_me');
$alter->Set_engine    (Db::ENGINE_INNODB);

$maker->Run(); 

if (Db::$db->last_error)
    print_r(Db::$db->last_error);

else echo "Success \n";

```

### Forge Db methods

|Db method   |Info   |
|:-----------|:------| 
|**Create_database** ($database_name, $collate, $if_not_exists) :int |Creates a new data base|    
|**Drop_database**  ($database_name, $if_exists) :int|Drops a database.|    
|**Drop_table**     ($table, $if_exists) :int|Drops a table.|    
|**Drop_view**      ($view_name, $if_exists) :int|Drops a view.|    
|**Truncate_table** ($table) :int|Truncates a table.| 

---

## Benchmarking, query metrics

|Db method   |Info   |
|:-----------|:------|
|**Describe**            ($query) :object[] | Describes a query.|
|**Describe_html_table** ($query) :string   | Describes a query in a html table.|
|**Pofiles_html_table**    () :string| Returns a basic report of profiles as a html table.|
|**Pofiles_describe_html** () :string| Returns a report with query description as html.|
 

