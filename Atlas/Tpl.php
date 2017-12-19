<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Xperimentx\Atlas;


/**
 * Description of Tpl
 *
 * @author rgonz
 */
class Tpl
{
    static protected $cache = [];
    static protected $root_path;


    /**
     * Devuelve el contenido de una plantilla htt
     *
     * Devuelve el contenido de una plantilla htt, cacheando los resultados para no llamar tanto a disco

     * La primera vez que se usa una plantilla la carga y la guarda en cache.
     * En sucesivas llamadas a la misma plantilla se reutiliza la copia cacheada.
     *
     * @param string $template_name	Nombre y ruta del archivo con respeto a la raíz  de atlas
     * @param array  $htt	Vector de sustitución: índice=cadena sustituida, valor=cadena sustituta
     * @param $template_name_alternative true: si la plantilla no existe devuelve cadena vacía. string:nombre y ruta del archivo con la plantilla que se cargará en caso de que no exista $template_name
     * @return string  Plantilla procesada
    */
   static function Htt ($template_filename, $htt=null, $template_alternative_content='', $root_path=null)
   {
       if (!$template_filename and $template_alternative_content)
       {
           return $htt
                  ? str_replace(array_keys($htt), $htt, $template_alternative_content)
                  : $template_alternative_content ;
       }

       $key = 'file:'.$template_filename;

       if (!isset(self::$cache[$key]))
       {
           self::$cache[$key] = @\file_get_contents(($root_path?:self::$root_path).$template_filename);

           if (false === self::$cache[$key])
               self::$cache[$key] = $template_alternative_content;
       }

       return $htt
              ? str_replace(array_keys($htt), $htt, self::$cache[$key])
              : $cache[$key];
   }


    /**
     * @internalx
     */
    public static function __initialize()
    {
        self:
    }
}

Tpl::__initialize();



