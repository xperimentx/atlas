[Documentation](README.md)

![xperimentx atlas](images/atlas.png) 

* [Autoloader](#Autoloader)
* [Namespace Maps](#namespace-maps)
* [Add a directory to include path](#add-a-directory-to-include-path)
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


## Add a directory to include path

You can add a directory to include path with `\set_include_path()`
or use  `Atlas\Autoloader::Add_to_include_path()` method.

```php
Xperimentx\Atlas\Autoloader::Add_to_include_path(__DIR__.'/src/path_1' );
```


 
## Include autoloader in your index.php file
* `my_root` is the root directory of your project.
* Atlas is installed in `my_root/Xperimentx` directory.

### My file is `my_root/index.php`.

```php
include 'Xperimentx\php\Autoloader.php';
Atlas\Autoloader::Register(__DIR__);
```


### My file is `my_root/App/Api/index.php`.

`my_root` is 2 levels  up direrctory than `Api`.

```php
include 'Xperimentx\php\Autoloader.php';
Xperimentx\Atlas\Autoloader::Register(__DIR__,2); 
```


## Composer like project, using Atlas autoloader

* My file is `my_root/index.php`.
* Atlas is installed in    `my_root/vendor/Xperimentx` directory.
* Acme library sources in  `my_root/vendor/Acme/scr` directory.

```
my_root        
|-- index.php        ...... my file 
|-- vendor
|    |-- Xperimentz
|    |    + -- Atlas ...... Atlas toolkit
|    |-- Acme
          + -- src   ...... Acme library   
```

```php
use Xperimentx\Atlas\Autoloader;
include 'vendor/Xperimentx/php/Autoloader.php';
Autoloader::Register(__DIR__);
Autoloader::Add_to_map ('Acme\\', Atlas::$root.'/vendor/Acme/src');
```
