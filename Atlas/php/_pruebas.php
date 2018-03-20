<?php

/**
 * xperimentX atlas php toolkit
 *
 * @link      https://github.com/xperimentx/atlas
 * @link      https://xperimentX.com
 *
 * @author    Roberto González Vázquez, https://github.com/xperimentx
 * @copyright 2017 Roberto González Vázquez
 *
 * @license   MIT
 */

namespace Xperimentx\Atlas;

/*
$str = 'foobar: 2/008';

//preg_match('/(?P<name>\w+): (?P<digit>\d+)/', $str, $matches);
preg_match('/(?P<name>[^\/]+): (?P<digit>\d+)/', $str, $matches);

/* This also works in PHP 5.2.2 (PCRE 7.0) and later, however
 * the above form is recommended for backwards compatibility
// preg_match('/(?<name>\w+): (?<digit>\d+)/', $str, $matches);

print_r($matches);
*/
/*
\d	Cualquier carácter numérico	[0-9]
\D	Cualquier carácter no numérico	[^0-9]
\s	Cualquier espacio	[\t\n\r\f\v]
\S	Cualquiera que no sea espacio	[^ \t\n\r\f\v]
\w	Cualquier carácter alfanumérico	[a-zA-Z0-9_]
\W	Cualquier carácter no alfanumérico	[^a-zA-Z0-9_]

*/


$str = '/gato/blog/view-blue/714-adsjkh/fjs/df';

//preg_match('ff/amigo\/(?P<controller>[^\/]+)\/(?P<action>[^\/]+)\/(?P<slug>.*)/', $str, $matches);

$wild = [//	'/'              => '\/',
        ':alpha)'	 => '[a-zA-Z]+)',
		':alphanum)' => '[a-zA-Z0-9]+)',
		':alphaext)' => '[a-zA-Z0-9_-]+)',
		':any)'		 => '.*)',
		':num)'		 => '[0-9]+)',
		':segment)'	 => '[^/]+)'
    ];
$k = array_keys($wild);

//$pat = '^/(amigo|gato)/(?<maria>:alpha)/(:alphanum)/(?<id>:num)-(?<slug>:any)$';
$pat = '^/(amigo|gato)/(?<maria>:alpha)/(:alphanum)/(\d+)-(?<slug>:any)$';
$pat = '/(amigo|gato)/(?<maria>:alpha)/(:alphaext)/(:num)-(?<slug>:any)';
//$pat = '/amigo/(:alpha)/(?<method>\w+)/(:num)-(:any)';

$pat_2 = str_replace($k, $wild, $pat)   ;

echo $pat_2;

echo preg_match('#^'.$pat_2.'$#', $str, $matches);
print_r($matches);