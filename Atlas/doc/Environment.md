[xperimentX atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Environmet.](#environment)
* [Stage.](#stage)

# Environment, hostname, Stage

Do not blindly trust in $_SERVER['HTTP_HOST'] or $_SERVER['SERVER_NAME'] ,
under certain circumstances these values reflects the hostname supplied by the client,
which can be spoofed. 
 
## Environment

| Environment class |     |
|:-------|:----------|
|static **Set_host** (string $host_name)     |  Sets host name. <br>For security reasons is recommend set this value in production stage.     |
| | |
|static **Get_host** () :string     |  Gets host name.          |
|static **Get_host_uri** () :string|  Returns scheme://host[:port]    |
|static **Get_method** (): string  |  Method used to access the page: 'CLI', 'GET', 'HEAD', 'POST', 'PUT'...|
|static **Get_method_code** (): int     |  Int code corresponding to method code used to access the page: 'CLI', 'GET', 'HEAD', 'POST', 'PUT'...|
|static **Get_port** (): int     |  Gets server port.
|static **Get_protocol** (): string   |  Protocol which the page was requested: ex: 'HTTP/1.1'.  |
|static **Get_uri ** () :string     |  Returns  the URI requested scheme://host[:port]|
|static **Get_uri_friendly_obj ** () :Http\Uri_friendly     |  Returns a new Uri object form the requested URI.|
|static **Get_uri_obj ** () :Http\Uri     |  Returns a new Uri object form the requested URI.|
| | |
|static **Is_ajax ** () :bool     |  Check if the page is requested via ajax. Unsafe.|
|static **Is_cli** () : bool     | Checks is via is command line.                                                                               | 
|static **Is_https** ():bool     |  Returns if https is used in the request of this page         |
| | |
|static **Get_request_time** (): float     |  Timestamp of the start of the request, with microsecond precision.    |
|static **Time_from_Request** (): float     |  Seconds from  the star of the request, , with microsecond precision.    |
   

## Stage
 
**¿A simple method for decide the sate of the current enviroment?**

Hostname is also not very useful in command line and can be spoofed.

A simple method is to rely on the existence of a file that is only found in one of the environments.

In this sample if **.development** file exists, we are in the development stage environment.

```php 

if (file_exists(__DIR__.'/.development'))
     Stage::Set_development();
else Stage::Set_production ('demo.xperimentx.com');

```


 
|class Stage| |
|:-----------|:-------|
|    const PRODUCTION  = 'production'; | |
|    const DEVELOPMENT = 'development';| |
|    const TESTING     = 'testing';    | |
| | |
|static **Set_development** (string $host_name=null, bool $report_all_errors=true) | Sets environment to DEVELOPMENT stage.|
|static **Set_testing** (string $host_name=null, bool $report_all_errors=true) | Sets environment to TESTING stage.
|static **Set_production** (string $host_name=null, bool $report_all_errors=true)| Sets environment to PRODUCTION stage.<br>For security reasons is recommend set host name  this value in production stage.|        
|static **Get** () :string |      Gets the stage |    
|static **Is_development** () :bool |  Checks if stage is DEVELOPMENT.|
|static **Is_testing** () :bool |  Checks if stage is TESTING. |
|static **Is_production** () :bool | Checks if stage is PRODUCTION. |
 

