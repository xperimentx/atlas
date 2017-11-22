# Autoloader

Atlas Autoloader is  PSR-4 compatible.

* Translates full qualified class name to  file name following PSR-4 specification
* Load from Altas::$root_path.
* Mappings to translate namespace prefixes in base directories.
* Load from from include path.
* Uses spl_autoload_register.


## Maps

**Atlas\Autoloader::$map** is an array that maps namespaces prefixes with their base directories.

* Base directories must not have trailing **/** .
* Namespace prefixes must not have leading or trailing **\** .

Sample structure of Atlas\Autoloader::$map:
````json
[
    'Namespace_prefix'   => [base directories],
    'Acme\Couso\Chimes   => ['./vendor/couso/chimes-src' ],
    'Name_space\Complex  => ['complex/src_1', 'complex/test: 2' ]
 ];
...

```php
Atlas\Autoloader::Add_to_map ('Foo_app2ns', __DIR__.'/fake/dir');
Atlas\Autoloader::Add_to_map ('Foo_app2ns', [Atlas::$root_path.'/devel/autoloader/src', __DIR__.'/src/x']);
```

## Load from include path
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

