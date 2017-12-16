[xperimentX atlas documentation](README.md) 

![xperimentx atlas](images/atlas.png) 

# Uri

## Htt\Uri class

 Parses an URI string to part  and generates the URI string  from parts.


*URI format:*
```
scheme:[//[user[:password]@]host[:port]][/path][?query][#fragment]
```

This class does not urlencode or urldecoded. Only splits or merges the URI parts.

More info:
* [Uri RFC 3986](https://tools.ietf.org/html/rfc3986#section-3.2.2)
* [URI wikipedia](https://en.wikipedia.org/wiki/Uniform_Resource_Identifier)


|Uri Properties|          |             |
|:------------|:----------|:-----------------------|
| string|null |$uri       |Full URI parsed or built|
|             |           |             |
| string,null |$scheme    |Scheme       |
| string,null |$host      |Host name.   |
| string,null |$port      |Port         |
| string,null |$user      |User         |
| string,null |$password  |Password.    |
| string,null |$path      |Path         |
| string,null |$query     |Query        |
| string,null |$fragment  |Fragment     |

    
| Uri Methods         |                       |
|:--------------------|:----------------------|
|**__construct** (string $url=null)           |          |
|**Parse** (string $uri)                      |Parses an URI and assign the result in the object properties. |
|**Build** ([$hide_password]) :string         |Builds the URI string  from the parts and sets the $uri property.|
|                                             |    |
|**Get_user_information** ([$hide_password], [$add_at_symbol]) :string| Gets the user info. user[:password]|
|**Get_authority** ([$hide_password]) :string | Gets the authority. [user[:password]@]:]host[:port] |
    

    