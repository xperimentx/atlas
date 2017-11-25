[Documentation](README.md)

![xperimentx atlas](images/atlas.png)

Atlas is designed to give you freedom in the structure and implementation of your website.

[Atlas Sample](https://github.com/xperimentx/atlas-sample) is designed to give you freedom in the structure and implementation of your website.


## Sample structure for a web application using Atlas
```
www
├── index.php        ....... Main php
│
├── Config           ....... Configuration files
│   ├── Autoload.php
│   ├── Database.php
│   ├── Migration.php
│   └── Routes.php
│
├── App              ....... Application stuff
│   ├── App.php      ....... Main class
│   ├── Common_class.php ... Auxiliary classes
│   ├── Tools.php
│   ├── ...
│   ├── Models       ....... The hard work 
│   ├── Controllers  
│   ├── Views
│   └── Templates
|
├── Web              ....... Public web
│   ├── Common_class.php ... Auxiliary classes
│   ├── Tools.php
│   ├── ...
│   ├── Models       ....... The hard work 
│   ├── Controllers  
│   ├── Views
│   └── Templates|
|
|
├── migration.php    ....... Migration tool
├── Migrations
│   ├── 001-Create_catalog_table.php
│   ├── 002-Create_users_table.php
│   ├── ...
│   ├── View
│   └── 013-Alter_users_add_gps_position.php
│
│
├── media
│   └── images
│
├── Xperimentx
│   ├── Atlas
│   ├── Control
│   └── Crud
│
└── vendor           ....... Other libraries
    └── Acme
        └── src

```


## Configuration of database /Config/Database.php

```php
<?php
namespace Config;

class Database extends \Xperimentx\Atlas\Db\Cfg
{
    function __construct()
    {
        $this->user_name = 'atlas_db_user';
        $this->password  = 'atlas_db_passwd';
        $this->db_name   = 'atlas_demo_db';
    }
}
```

## Configuration of autoloader /Config/Autoloader.php

```php
<?php
namespace Config;

use Xperimentx\Atlas\Autoloader as X;

class Autoloader
{
    static public function Load()
    {
        X::Add_namespace ('Xperiment\\Atlas\\Control', 'Xperiment/Control/php');
        X::Add_namespace ('Xperiment\\Atlas\\Crud'   , 'Xperiment/Crud/php'   );
    }
}
```



## Use this configurations files in /index.php

```php
<?php
include __DIR__.'/Xperiment/Atlas/php/Autoloader.php';

use Xperimentx\Atlas;

Atlas\Autoloader::Register(__DIR__);


// Load config files
Config\Autoloader ::Load();
Config\Routes     ::Load();

// Connect the main database
$db = new Atlas\Db(new Config\Database());



if (!$db->Connect())
{
    die ("Database connection error \n");
}


echo "Hola mundo!";
```


