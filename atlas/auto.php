<?php
namespace Atlas;
/**
 * ATLAS auto initilize a loader
 * @author Roberto Gonzalez Vazquez
 */
 

/** @const Application root path */
define('Atlas\ROOT_PATH', substr (__DIR__, 0, -6 )); //-"/atlas"


// Autoloader 
if (!defined('Atlas\IGNORE_AUTOLOADER'))  
    include ROOT_PATH."/atlas/autoloader.php";

// Load main confifuration file
if (file_exists(ROOT_PATH. '/cfg/atlas.php'))
    include_once ROOT_PATH. '/cfg/atlas.php';


// Load optional configuration file
if (defined('Atlas\CFG_FILE'))          
     include_once ROOT_PATH. CFG_FILE ;

               
 


 