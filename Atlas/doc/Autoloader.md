[xperimentX Atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Autoloader](#Autoloader)
* [Namespace Maps](#namespace-maps)
* [Add a directory to include path](#add-a-directory-to-the-include-path)
* [Include autoloader](#include-autoloader-in-your-indexphp-file)
* [Composer like project](#composer-like-project-using-atlas-autoloader)
 

# Autoloader

Atlas Autoloader is  PSR-4 compatible.

* Translates full qualified class name to  file name following PSR-4 specification.
* Try to load files form include path.
* `Atlas::$root_path` is automatically added to include path.
* You can define a mapping from namespaces to paths



## Namespace Maps

` Add_namespace($namespace_prefix, $base_dir, $prepend = false)` adds a new base directory or an array of base directories.

* Base directories must not have trailing `/`.
* Namespace prefixes must end in `\`.

```php
use Xperimentx\Atlas\Autoloader;

Autoloader::Add_namespace ('Namespace_prefix\\', 'My_libraries/Name_space_dir');
Autoloader::Add_namespace ('Acme\\'            , Atlas::$root.'/vendor/Acme/src');
Autoloader::Add_namespace ('Acme\\Special\\'   , [ __DIR__.'/Special', 'vendor/Acme/test/Special']');
```

## Class Maps

`Add_class($full_qualified_class_name, $filename_with_path)`* Adds a filename for a full qualified class name.

`Add_class_array($class_map)` Add an array with a class map to the current class map.



`Atlas\Autoloader::Add_class($full)` adds a class mapping

* Base directories must not have trailing `/`.
* Namespace prefixes must end in `\`.

```php
use Xperimentx\Atlas\Autoloader;

Autoloader::Add_class ('No_psr4_class', 'Lib/No_psr4_class.php');

Autoloader::Add_class_array (['Class_A' => __DIR__.'/A/Class_A.php', 
                              'Class_B' =>'vendor/CB/src/cb.php ]);

```



## Add a directory to the include path

You can add a directory to include path with `\set_include_path()`
or use  `Add_include_path ($base_dir, $prepend = true)` method.

```php
Xperimentx\Atlas\Autoloader::Add_include_path(__DIR__.'/src/path_1' );
```


 
## Include autoloader in your index.php file
```
www
├── index.php
│
├── other-apps
│   └──Api
│       └── api-main.php
│
└── Xperimentx
       └── Atlas
```

* Atlas is installed in    `www/vendor/Xperimentx` directory.
* My main file is `index.php`. in the root directoy of my application
* My main file is `api-main.php`. is 2 levels up direrctory than the root diretory.


###  www/ index.php

```php
include 'Xperimentx/php/Autoloader.php';
Atlas\Autoloader::Register(__DIR__);
```


###  www/other-apps/index.php

```php
include '../../Xperimentx/php/Autoloader.php';
Xperimentx\Atlas\Autoloader::Register(__DIR__, 2); 
```


## Composer like project, using Atlas autoloader

```
www
├── index.php
│
├── vendor
│   ├── Xperimentx
│   │   └── Atlas
│   │
│   └── Acme
│       └── src
```

* My main file is `www/index.php`.
* Atlas is installed in    `www/vendor/Xperimentx` directory.
* Acme library sources in  `www/vendor/Acme/scr` directory.



```php
include 'vendor/Xperimentx/php/Autoloader.php';

use Xperimentx\Atlas\Autoloader;

Autoloader::Register(__DIR__);
Autoloader::Add_namespace ('Acme\\', Atlas::$root.'/vendor/Acme/src');
```
