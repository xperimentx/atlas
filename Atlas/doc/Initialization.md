# How to include Atlas Toolkit in your php main file

## Basic example 

```php
include_ "Atlas/Atlas.php"       // Load Atlas pre initializer
Atlas::Initialize()              // Initialize atlas;

echo 'Hello World!';             
```


## Ignore Atlas autoloader
```php
define('Atlas\IGNORE_AUTOLOADER',1);  // Ignore autoloader

include_ "Atlas/Atlas.php"            // Load Atlas pre initializer
Atlas::Initialize()                   // Initialize atlas;

echo 'Hello World!';             
```


## Change configuration files
```php
include_ "Atlas/Atlas.php"            
                                       
// You can select configuration files changing values 
// of Atlas\Cfg::$cfg_files* properties 
// before call Atlas::Initialize()

Atlas\Cfg::$cfg_file = '/my_spcial_cfg/awesome_cfg.php';

Atlas::Initialize()  

echo 'Hello World!';             
```

 