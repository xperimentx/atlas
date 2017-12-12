[Documentation](README.md) 
\ [Database reference](Database-reference.md)

![xperimentx atlas](images/atlas.png) 

* [Create a table](#create-a-table)
* [Alter a table](#alter-a-table)
* [Forge methods from Db object](Database-reference.md#db-forge)


# Database forge

## Create a table

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

## Alter a table
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

