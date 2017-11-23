<?php
/**
 * Atlas Toolkit
 *
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 *
 * @copyright 2017 Roberto González Vázquez
 */

namespace Xperimentx\Atlas;


/**
 * Intenta imitar la funcionalidad de las propiedades de usar gettesr y seters automatizados.
 *
 * Atlas 2016
 *
 * Permite que las variables privadas se puedan leer pero no escribir..
 * Rompe el encapsulamientosimplifica la creación de clases con muchos atributos de sólo lectura.
 *
 * Para una propiedad privada o protegida zzz:
 * <ol>
 * <li>Si existe _set_zzz se prorá asignar.</li>
 * <li>Se podrá leer si existe _get_zzz, en su defecto se devolverá el valor de zzz
 *     (rompe el encapsulamientosimplifica la creación de clases con muchos atributos de sólo lectura).</li>
 * <li>Se podrá leer una versión html usando el atributo virtual zzz_html.Se podrá ller si existe Get_zzz,
 *      en su defecto se devolverá el valor de zzz .</li>
 *      Usando el método Get_zzz_html o, en su defecto, htmlspecialchars(zzz) </li>
*
 * </ul>
 */

class Auto_properties
{
    public function __get($var_name)
    {

        $upper = ucfirst($var_name);

        if (method_exists($this, '_Get_'.$var_name))
            return $this->{'_Get_'.$var_name}();

        if (method_exists($this, 'Get_'.$var_name))
            return $this->{'Get_'.$var_name}();

        if (substr($var_name,-5)==='_lang')
        {
           $var_name = substr($var_name,0,strlen ($var_name)-4);
        return $this->{$var_name.\ATLAS::$lang};
        }

        if (substr($var_name,-10)==='_lang_html')
        {
           $var_name = substr($var_name,0,strlen ($var_name)-9);
           return htmlspecialchars($this->{$var_name. Reg::$lang});
        }

        if (substr($var_name,-7)==='_lang_br')
        {
           $var_name = substr($var_name,0,strlen ($var_name)-6);
           return nl2br(htmlspecialchars($this->{$var_name. Reg::$lang}));
        }

        if (substr($var_name,-5)==='_html')
        {
           $var_name = substr($var_name,0,strlen ($var_name)-5);
           return htmlspecialchars($this->$var_name);
        }

        if (substr($var_name,-3)==='_br')
        {
           $var_name = substr($var_name,0,strlen ($var_name)-3);
           return nl2br(htmlspecialchars($this->$var_name));
        }


        //if (!property_exists($this, $var_name)) \ATLAS::Trace(1);

        return $this->$var_name;
    }



    public function __set($var_name,$value)
    {
        if (method_exists($this, '_Set_'.$var_name))
             $this->{'_Set_'.$var_name}($value);
        else $this->{ 'Set_'.$var_name}($value);
    }

}






