[Documentation](README.md)

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

`Atlas\Autoloader::Add_to_map()` adds a new base directory or an array of base directories.

* Base directories must not have trailing `/`.
* Namespace prefixes must end in `\`.

```php
use Xperimentx\Atlas\Autoloader;

Autoloader::Add_to_map ('Namespace_prefix\\', 'My_libraries/Name_space_dir');
Autoloader::Add_to_map ('Acme\\'            , Atlas::$root.'/vendor/Acme/src');
Autoloader::Add_to_map ('Acme\\Special\\'   , [ __DIR__.'/Special', 'vendor/Acme/test/Special']');
```


## Add a directory to the include path

You can add a directory to include path with `\set_include_path()`
or use  `Atlas\Autoloader::Add_to_include_path()` method.

```php
Xperimentx\Atlas\Autoloader::Add_to_include_path(__DIR__.'/src/path_1' );
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
Autoloader::Add_to_map ('Acme\\', Atlas::$root.'/vendor/Acme/src');
```
