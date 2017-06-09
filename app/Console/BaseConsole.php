<?php
namespace App\Console;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

class BaseConsole
{
    public function __construct()
    {
        require_once(__DIR__ . '/../../vendor/autoload.php');
        ini_set('display_errors','On');
        require_once(__DIR__.'/../../BladePHP/Blade.php');
    }
}
