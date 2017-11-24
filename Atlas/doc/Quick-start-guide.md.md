[Documentation](README.md) 

![xperimentx atlas toolkit](images/atlas.png) 

# Quick start guide

## Using Atlas Autoloader
```php
include 'Xperimentx/php/Autoloader.php';
Xperimentx\Atlas\Autoloader::Register(__DIR__);

echo "Hola! \n";
```
[More info about Atlas Autoloader](Autoloader.md) 
 


## Atlas whit other autoloaders

##  I use other autoloder  with namespace mapping support 

You must map the `Xperimentx\Atlas` namespace to the directory `Atlas/php`.


## I use other "spl " autoloader without namespacing  mappig 

Atlas use *spl_autoload_register()*.
You can  register  the Atlas Autoloader for work together with your autoloader
using:

``` 
Xperimentx\Atlas\Autoloader::Register()
```
 
