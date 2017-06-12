<?php
namespace App\Console;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

class BaseConsole
{
    public function __construct()
    {
        ini_set('display_errors','On');
        register_shutdown_function([$this, 'shutdown_function']);
        require_once(__DIR__ . '/../../vendor/autoload.php');
        require_once(__DIR__.'/../../BladePHP/Blade.php');
    }

    public function shutdown_function()  
    {  
        $e = error_get_last();    

        print_r($e);  
    }

}

