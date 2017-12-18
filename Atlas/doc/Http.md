[xperimentX atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

* [Ip class.](#ip-class)
* [Status Codes class.](#status-codes-class)
* [Uri class.](#uri-class)
* [Http Methods.](#http-methods)


# Http Namespace

## Ip class
|Http\Ip|         |
|static **Get_ip_remote** () :string|Returns IP from remote address. Not good for proxies but safe.|
|static **Get_ip_client** () :string |Returns IP of client sent in the Http request, unsafe, can be spoofed  but good for proxies.|
|static **Get_all_ip()** :array | Returns all IPs from client sent in Http request, unsafe, these values can be spoofed.|
 

## Status codes class

Status_codes_class contains constants for the principal status codes, 
and the **Str** method for return a short description of a status code.

```
Status_codes::Str(int $status_code, bool $show_family_if_unknow = true) : string
```

```php
use Xperimentx\Atlas\Http\Status_codes;

Status_codes::Str(404); 
//returns 'Not found';

Status_codes::STATUS_404_NOT_FOUND              ;
//returns 'Not found';

Status_codes::Str(288);
//returns:: '2xx Successful'
```

More info:
* https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
* https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
* https://en.wikipedia.org/wiki/List_of_HTTP_status_codes


## Uri class

 Parses an URI string to part  and generates the URI string  from parts.


*URI format:*
```
scheme:[//[user[:password]@]host[:port]][/path][?query][#fragment]
```

This class does not urlencode or urldecoded. Only splits or merges the URI parts.

More info:
* [Uri RFC 3986](https://tools.ietf.org/html/rfc3986#section-3.2.2)
* [URI wikipedia](https://en.wikipedia.org/wiki/Uniform_Resource_Identifier)


|Uri Properties|           |                        |
|:-------------|:----------|:-----------------------|
| string|null  |$uri       |Full URI parsed or built|
|              |           |                        |
| string,null  |$scheme    |Scheme                  |
| string,null  |$host      |Host name.              |
| string,null  |$port      |Port                    |
| string,null  |$user      |User                    |
| string,null  |$password  |Password.               |
| string,null  |$path      |Path                    |
| string,null  |$query     |Query                   |
| string,null  |$fragment  |Fragment                |

    
| Uri Methods                                 |                       |
|:--------------------------------------------|:----------------------|
|**__construct** (string $url=null)           |                       |
|**Parse** (string $uri)                      |Parses an URI and assign the result in the object properties. |
|**Build** ([$hide_password]) :string         |Builds the URI string  from the parts and sets the $uri property.|
|                                             |                       |
|**Get_user_information** ([$hide_password], [$add_at_symbol]) :string| Gets the user info. user[:password]|
|**Get_authority** ([$hide_password]) :string | Gets the authority. [user[:password]@]:]host[:port] |
    

## Http Methods

**Consts:**
ALL      , NONE   , CONNECT  , DELETE   , GET      , HEAD     , OPTIONS  , PATCH    , POST     , PUT      , TRACE    


|Http\Methods methods |  |
|:--------|:--------|      
|static **Str** (int $method_code ) :string |Returns method name for an atlas int method code |
|static **Get_code** (string $method_name) :int | Returns the atlas int code for a Http method.|
|static **Match** ($method_code_or_name, int $mask) :bool|Checks if a method code match the mask|

example of mask: ```Method::GET|Method::POST```
 