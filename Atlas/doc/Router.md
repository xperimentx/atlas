[xperimentX atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Patterns](#patterns)
* [Establish the connection](#establish-the-connection)
* [Router Methods](#router-methods)
* [Samples](#samples)


#Router 

## Patters
**Router::Replace()** uses  **preg_replace**.

**Router::Add()**     uses  **preg_match**, 
If a match is successful, the Controller::Method  or the function closure is called ,
sending the matches of the preg_match as param;

Can use a raw regular expression or use the "easy" version.

### **"Easy"**  version
* Regular expression will be concatenated wiht  ````php '#^'.$pattern '$#';```.
* And replace the strings  ':aplha)' ':any)' ':num)'...  whit a predefined substitutions.

```
/(:alphaext)/(:num)/(:any)

/(?<controller>:alphaext)/(?<action>:segment)/(:any)

/(perro|gato|animal)/(?<action>:\w+)/(:any)

```

#### Default place-holders/wildcards for "easy" version.
```
    ':alpha)'	 => '[a-zA-Z]+)',
    ':alphanum)' => '[a-zA-Z0-9]+)',
    ':alphaext)' => '[a-zA-Z0-9_-]+)',
    ':any)'		 => '.*)',
    ':num)'		 => '[0-9]+)',
    ':segment)'	 => '[^/]+)'
```
You can add a new place-holder or edit an exiting one whith  **Router::Add_placeholder**


## Router Methods

|Router class | Setup / Run Methods  |
|:-------------------|:-------------------------|
|static **Run**() | |
|  |  |
|static **Add_placeholder** ($template, $regex) |Adds or redefine a place holder/ wildcard|
|  |  |
|static **Set_uri**(string $uri)|Sets the current URI path |
|static **Set_method**(int $method)|Sets the current http method |
|static **Stop_routing**()   |Stops the routing loop|


|Router class -  Routing methods |
|:-------------------|
|public static function Prefix(string $patter_prefix='', $call_to_prefix=''):Router_item
|  |  
|static **Replace** (string $pattern, string $replacement, bool $is_raw_reg_exp=false):Router_item |
|static **Add** (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |
|  | 
|static **Add_methods** (int $method_mask, string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item|   
|static **Add_connect** (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_delete** (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_get**    (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_head**   (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_options** (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_patch**  (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_post**   (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_put**    (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_trace**  (string $pattern, $call_to, bool $is_raw_reg_exp=false) :Router_item |     
|static **Add_patch_put** (string $pattern, $call_to, bool $is_raw_reg_exp=false):Router_item |    


## Router_item structure

|Router_item structure| |
|:--------|:-----|
|$data  | |
|$is_raw_exp | | 
|$method_mask  | | 
|$mode           | | 
|$pattern  | | 
|$stops_routing  | | 
|Methods (int $method_mask) | Set http method mask <br>Method::GET|Method::POST|


## Samples
### Force URI path and method
```php
use Xperimentx\Atlas\Router;

Router::Set_uri('/es/blog/post/135/ave-rapido');
Router::Set_method(Atlas\Http\Methods::GET);
```
### Some sampless
```php
Router::Replace('/es/(:segment)/(:segment)/(:any)', '/es/$2/$1/$3');
// /es/post/blog/135/ave-rapido
```

```php
Router::Add ('/(?<lang>es|pt|en)/(:any)', 'Ctr::Demo');
Router::Add ('/(?<lang>es|pt|en)/(.*)', function ($result) { print_r ($result);});
/*
    [0] => /es/post/blog/135/ave-rapido
    [lang] => es
    [1] => es
    [2] => post/blog/135/ave-rapido
*/
```

```php
Router::Add ('/(?<lang>es|pt|en)/(?<controller>:segment)/(?<path>:any)',null);
/*
    [0] => /es/post/blog/135/ave-rapido
    [lang] => es
    [1] => es
    [controller] => post
    [2] => post
    [path] => blog/135/ave-rapido
    [3] => blog/135/ave-rapido
*/
```

### Force another URI path
```php
Router::Set_uri('/gato/blog/view-blue/714-adsjkh/fjs/df');
```


```php
Router::Add ('/(amigo|gato)/(?<maria>:alpha)/(:alphaext)/(:num)-(?<slug>:any)',null);
/*
    [0] => /gato/blog/view-blue/714-adsjkh/fjs/df
    [1] => gato
    [maria] => blog
    [2] => blog
    [3] => view-blue
    [4] => 714
    [slug] => adsjkh/fjs/df
    [5] => adsjkh/fjs/df
*/

```
### Another replace
```php
Router::Replace('/(gato)/(:any)', '/api/$1/$2');
// /api/gato/blog/view-blue/714-adsjkh/fjs/df
```

### Add prefixes

```php
Router::Prefix('/api', 'Crontrollers\\Api\\');

Router::Add ('/(gato|perro)/(?<slug>:any)'       ,null);
/*
    [0] => /api/gato/blog/view-blue/714-adsjkh/fjs/df
    [1] => gato
    [slug] => blog/view-blue/714-adsjkh/fjs/df
    [2] => blog/view-blue/714-adsjkh/fjs/df
*/
```

### Elimitate  prefixes

```php
Router::Prefix();
```

## Raw regular expresions
```php
Router::Add ('#blue#                            ',null, true);
/*
    [0] => blue
*/

Router::Add ('#blue.*/(.*)$#'                ,null, true);
/*
    [0] => blue/714-adsjkh/fjs/df
    [1] => df
*/
```


### A last one

```php
Router::Add ('(:any)'                      ,null);
/*
    [0] => /api/gato/blog/view-blue/714-adsjkh/fjs/df
    [1] => /api/gato/blog/view-blue/714-adsjkh/fjs/df
*/
```


 



```
