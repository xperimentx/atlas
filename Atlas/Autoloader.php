<?php
/**
 *  Atlas Toolkit  
 * 
 * @link  https://github.com/xperimentx/atlas
 * @link  https://xperimentX.com
 * 
 * @copyright 2017 Roberto González Vázquez
 */

namespace Atlas;

/**
 * Autoloader, PSR-4 compatible
 * 
 * @author    Roberto González Vázquez
 */
class Autoloader
{
    /**
     * Maps namespaces prefixes with ther base directories
     * 
     * Base directories must not have trailing /  
     * Namespace prefixes must not have  leading or trailing \
     * 
     * [ 'Namespace_prefix'   => [base directories],
     *   'Acme\Couso\Chimes   => ['./vendor/couso/chimes-src' ],
     *   'Name_space\Complex  => ['complex/src',
     *                            'complex/test' ],
     *  ...
     * ];      
     * 
     * Ex: Atlas\Autoloader::$map['App_namespace']=[Atlas\ROOT_PATH.'/app', Atlas\ROOT_PATH.'/app/src'];
     * 
     * @see add_map()
     */
    static public $map = [];
            
            
    
    /**
     * Include php file for a given fully-qualified class name. 
     * 
     * spl_autoload_register compatible
     * 
     * @param string $class_name The fully-qualified class name.     
     */
    public static function Load_class($class_name)
    {     
        // Basic try to load direct full class name to file name        
        
        if (file_exists( $filename = ROOT_PATH.'/'.str_replace('\\','/',$class_name).'.php'))
        {
            include_once $filename;        
            return;
        }
        
        // Resolve namespaces ptefixes
        if (self::$map)
        {
            $prefix = $class_name;
            
            while (false !== $pos = strrpos($prefix, '\\')) 
            {
                $prefix = substr($prefix, 0 , $pos); // prefix part
     
                if (!isset(self::$map[$prefix])) continue;
                
                $sufix  = str_replace('\\','/',substr($class_name, $pos)).'.php'; // normal translation
                                             
                foreach (self::$map[$prefix] as $base_dir) 
                {                        
                    if (file_exists( $filename = $base_dir.$sufix))
                    {
                        include_once $filename;
                        return;
                    }
                }                
            }        
        }
        
        // load from include path        
        if ($filename = stream_resolve_include_path(str_replace('\\','/',$class_name).'.php'))                
            include_once $filename;                                
    }
    
    /**
     *  Adds a base directory for a namespace prefix.
     *
     * @param string      $namespace_prefix Namespace prefix.
     * @param string|aray $base_dir         A Base directory or a array of directorys fornamespace prefix class files .   
     *                                      If it is a array there will be no normalization,  base directories must not have trailing / 
     * @param bool        $prepend          If true, will prepend basedirt instead of appending it.          
     */
    static public function Add_to_map($namespace_prefix, $base_dir, $prepend = false)
    {        
        $namespace_prefix = trim($namespace_prefix, '\\') ;
        
        if (is_array($base_dir))
        {            
            if     (!isset(self::$map[$namespace_prefix]))  self::$map[$namespace_prefix] = $base_dir;
            elseif ($prepend)                               self::$map[$namespace_prefix] = array_merge  ($base_dir, self::$map[$namespace_prefix]);
            else                                            self::$map[$namespace_prefix] = array_merge  (self::$map[$namespace_prefix], $base_dir);       
        }
        
        else
        {
            $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) ;
            if (DIRECTORY_SEPARATOR!='/')
                $base_dir = rtrim($base_dir, '/') ;

            if     (!isset(self::$map[$namespace_prefix]))               self::$map[$namespace_prefix] = [$base_dir];
            elseif ($prepend)                              array_unshift(self::$map[$namespace_prefix], $base_dir);
            else                                           array_push   (self::$map[$namespace_prefix], $base_dir);                       
        }
    }
    
    
    
    /**
     * Adds a base directory to the include path
     * @param string $base_dir         A base directory to include 
     * @param bool   $prepend          If true, will prepend basedirt instead of appending it.          
     */
    static public function Add_to_include_path ($base_dir, $prepend = true)
    {
        if ($prepend)
              \set_include_path (rtrim($base_dir, '/'). PATH_SEPARATOR . \get_include_path());  
        else  \set_include_path (\get_include_path()  . PATH_SEPARATOR .rtrim($base_dir, '/'))  ;   
        
    }            
}


