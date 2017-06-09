<?php
namespace App\Console;
require_once "BaseConsole.php";
use App\Model\SystemModel;
use App\Model\InterfaceModel;
use App\Model\RequestLogModel;

class generateExcel extends BaseConsole
{
    private $file;

    public function __construct()
    {
        parent::__construct();
        $this->file = "/Users/hanxueming/www/BladeLog/data/access_log.log";
    }

    public function execute()
    {
        $fp = fopen($this->file, "r");  
        if($fp === false) {
            return false;
        }
        while(!feof($fp)) {
            $line = fgets($fp);
            $ret = $this->_handleLog($line);
            //echo "$ret success \n";
        }
        fclose($fp);
    }

    private function _handleLog($line)
    {
        $system_name = $this->_getSystemByLine($line);
        if(empty($system_name)) {
            return false;
        }
        $system_id = $this->_handleSystem($system_name);

        $method = $this->_getMethodByLine($line);
        $uri = $this->_getUriByLine($line);
        $interface_id = $this->_handleInterface($system_id, $method, $uri);
    }

    private function _getSystemByLine($line)
    {
        $pattern = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*-\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*(\w*)\s*/';
        if(preg_match($pattern, $line, $arr)) {
            if(isset($arr[1])) {
                return $arr[1];
            }
        }

        return false;
    }

    private function _handleSystem($system_name)
    {
        if(empty($system_name)) {
            return false;
        }
        $system_name = trim($system_name);
        $system = SystemModel::where('name', $system_name)->first();

        if(is_null($system)) {
            $system = new SystemModel();
            $system->name = $system_name;
            $system->save();
        }

        return $system->id;
    }

    private function _getMethodByLine($line)
    {
        $pattern = '/^.*\[.*\]\s+\d*\.\d*\s+\"(\w*)\s+.*\"/';
        if(preg_match($pattern, $line, $arr)) {
            if(isset($arr[1])) {
                return $arr[1];
            }
        }
        
        return false;
    }

    private function _getUriByLine($line)
    {
        $pattern = '/^.*\[.*\]\s+\d*\.\d*\s+\"(\w*)\s+(\/.*)\s+HTTP\/1\.1\"\s+/';
        if(preg_match($pattern, $line, $arr)) {
            if(!isset($arr[2])) {
                return false;
            }
        }

        return $arr[2];
    }

    private function _handleInterface($system_id, $method, $uri)
    {
        if(empty($system_id) || empty($uri) || empty($method)) {
            return false;
        }
        $interface = InterfaceModel::where('system_id', $system_id)->where('method', $method)->where('uri', $uri)->first();
        if(is_null($interface)) {
            $interface = new InterfaceModel();
            $interface->system_id = $system_id;
            $interface->method    = $method;
            $interface->uri       = $uri;
            $interface->save();
        }

        return $interface->id;
    }
}

$obj = new generateExcel();
$obj->execute();
