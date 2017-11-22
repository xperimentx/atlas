[Atlas TOC](-Table_of_contens_.md)

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
Atlas\Autoloader::Add_to_map ('Namespace_prefix\\', 'My_libraries/Name_space_dir');
Atlas\Autoloader::Add_to_map ('Acme\\'            , Atlas::$root.'/vendor/Acme/src');
Atlas\Autoloader::Add_to_map ('Acme\\Special\\'   , [ __DIR__.'/Special', 'vendor/Acme/test/Special']');
```


## Load from include path

You can add a directory to include path with `\set_include_path()`
or use  `Atlas\Autoloader::Add_to_include_path()` method.

```php
Atlas\Autoloader::Add_to_include_path(__DIR__.'/src/path_1' );
```



## Ignore Atlas autoloader

```php
define('Atlas\IGNORE_AUTOLOADER',1);  // Ignore autoloader

include_ "Atlas/Atlas.php";            // Load Atlas pre initializer
Atlas::Initialize();                   // Initialize atlas;

echo 'Hello World!';
```

