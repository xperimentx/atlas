[Documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Accessing your main database from anywhere](#accessing-your-main-database-from-anywhere)
* [Configure the database connection](#configure-the-database-connection)
* [Establish the connection](#establish-the-connection)
* [Database Forge](Database-forge.md)


# Database Reference
## Accessing your main database from anywhere

'Xperimentx\Atlas\Db` is the class useed to manage your databases.

The first created `DB` object will be registered in the static and public property `Db::$db` as the main database.

This simplifies the development by making this main database easily accessible.

Atlas Database objects use this main database by default. 
You can use as many `Db` object as you wish.


## Configure the database connection

### Option 1, seting the cfg property

Create an object and configure the connection using the `cfg` property.

```php
use Xperimentx\Atlas\Db;

$db = new Db();
$db->cfg->user_name = 'atlas_db_user';
$db->cfg->password  = 'atlas_db_passwd';
$db->cfg->db_name   = 'atlas_demo_db';
```

### Option 2, whit a configuration object

Create a database configuration object `Db\Cfg`

And then assign it to a `DB` object using the constructor or assign it to the ``$cfg` property.
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
