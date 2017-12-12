[xperimentX Atlas documentation](README.md) 
\ [Database reference](Database-reference.md)

![xperimentx atlas](images/atlas.png) 

* [Migrator CLi](#migrator-cli)
* [Database Forge](Database.md#database-forge)


# Database migrations

The challenge to achieve the objective of migrations has begun. :smiley: 

## Migrator CLi

![xperimentx atlas](images/db/migrator-help.png) 

![xperimentx atlas](images/db/migrator-list.png) 

## Usage of migrators

## Example structure
```
www
├── Config           
│   ├── Autoload.php
│   └── Database.php
│
├── migrator.php    
└── Migrations
    ├── 001-Create_catalog_table.php
    ├── 002-Create_users_table.php
    ├── ...
    ├── 3-Create_users_table.php
    ├── 4-Create_users_table.php
    ├── ...
    ├── 201701130015-Create_users_table.php
    └── 201711051758-Create_users_table.php
```

### Main file: migrator.php
```php
<?php

use Xperimentx\Atlas\Autoloader;
use Xperimentx\Atlas\Db;
use Xperimentx\Atlas\Db\Migrations;

// Autoloader
// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
include __DIR__.'/Xperimentx/Atlas/php/Autoloader.php';
Autoloader::Register(__DIR__);


// Loading config
// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
Config\Autoloader ::Load();


// Connect database
// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
$db = new Db(new Config\Database());

if (!$db->Connect())
{
    die ("Database connection error \n");
}


// Main migrator part
// ¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨¨
class  My_migrator_cfg extends Migrations\Migrator_cfg
{
    function __construct()
    {
        $this->root       = __DIR__.'/Migrations';
        $this->namespace  = 'Migrations';
        $this->use_colors = true;
    }
}

$migrator= new Migrations\Migrator_cli(new My_migrator_cfg());
$migrator->Run();
```


### Example Step: 001-Create_catalog_table.php

```php
<?php
namespace Migrations;
use Xperimentx\Atlas\Db;

class Create_catalog_table extends Db\Migrations\Step
{
    public function Up()
    {
        $maker = new Db\Create_table('catalog');
        $maker->Add_column ('INT'        , 'id'  )->Set_auto_increment();
        $maker->Add_column ('VARCHAR(50)', 'name');
        $maker->Add_index  ('auto_id', 'id');
        $maker->Run();

    }

    public function Down()
    {
        Db::$db->Drop_table('catalog');
    }
}
```