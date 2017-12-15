[xperimentX atlas documentation](README.md) 

![xperimentx atlas toolkit](images/atlas.png) 

# Quick start guide

Atlas only need an autoloader for star to work

## Using Atlas Autoloader

```
www
├── index.php
│
└── Xperimentx
       └── Atlas
```

### inlude atlas in my index.php 
```php
include 'Xperimentx/php/Autoloader.php';

use Xperimentx\Atlas;

Atlas\Autoloader::Register(__DIR__);

echo "Hola! \n";

Atlas:
```
[More info about Atlas Autoloader](Autoloader.md).
 


## Atlas with other autoloaders

###  Use other autoloder PSR-4 compatible

You must map the `Xperimentx\Atlas` namespace to the directory `Atlas/php`.


### Use other "spl " autoloader without namespacing  mappig 

Atlas use *spl_autoload_register()*.
You can  register  the Atlas Autoloader for work together with your autoloader
using:

``` 
Xperimentx\Atlas\Autoloader::Register()
```
 
