<?php
namespace Atlas;
/**
 * xperimentX Atlas - auto initilize a loader
 * 
 * @author Roberto Gonzalez Vazquez
 * @author xperimentX https://github.com/xperimentx
 */
 

/** @const Application root path */
if (!defined('Atlas\ROOT_PATH'))
    define('Atlas\ROOT_PATH', substr (__DIR__, 0, -6 )); //-"/atlas"


// Autoloader 
if (!defined('Atlas\IGNORE_AUTOLOADER'))  
{
    include ROOT_PATH."/atlas/autoloader.php";
    
    // Add root path to autoload
    Autoloader::Add_to_include_path(ROOT_PATH, true);

    // register
    spl_autoload_register('Atlas\Autoloader::load_class');
}


// Load main confifuration file
if (file_exists(ROOT_PATH. '/cfg/atlas.php'))
    include_once ROOT_PATH. '/cfg/atlas.php';


// Load optional configuration file
if (defined('Atlas\CFG_FILE'))          
     include_once ROOT_PATH. CFG_FILE ;

               
 


 