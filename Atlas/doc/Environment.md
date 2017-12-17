[xperimentX atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 


# Environment


Do not blindly trust in $_SERVER['HTTP_HOST'] or $_SERVER['SERVER_NAME'] ,
under certain circumstances these values reflects the hostname supplied by the client,
which can be spoofed. 
 
Hostname is also not very useful in command line.

A simple method is to rely on the existence of a file that is only found in one of the environments.

In this sample if **.development** file exists, we are in the development stage environment.

```php 
if (file_exists(__DIR__.'/.development'))
     Environment::Set_development_stage();
else Environment::Set_production_stage('production-site.com');


if (file_exists(__DIR__.'/.development'))
{
     Stage::Development_stage();
}
else 
{
    Stage::Set_production_stage();
    X::Set_host('production-site.com');
}



```




