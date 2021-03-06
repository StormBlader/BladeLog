<?php
namespace App\Console;
require_once "BaseConsole.php";
use App\Model\SystemModel;
use App\Model\InterfaceModel;
use App\Model\InterfaceStatisticsModel;
use App\Model\RequestLogModel;

class importData extends BaseConsole
{
    private $file;
    private $_file_name;
    public static $static_extension = [
        'css', 'js', 'jpeg', 'jpg', 'png', 'html', 'htm', 'swf', 'ttf', 'gif', 'ico', 'txt'
    ];


    public function __construct($argv)
    {
        parent::__construct();
        if(!isset($argv[1])) {
            echo "no files\n";
            exit;
        }

        $this->file = $argv[1];
        if(!is_file($this->file)) {
            echo "error file\n";
            exit;
        }

        $this->_file_name = pathinfo($this->file, PATHINFO_BASENAME);
    }

    public function execute()
    {
        $fp = fopen($this->file, "r");  
        if($fp === false) {
            return false;
        }
        $i = 1;
        while(!feof($fp)) {
            $line = fgets($fp);
            $ret = $this->_handleLog($line, $i);
            if($ret === true) {
                echo $this->_file_name . " \t {$i} line has existed\n";
            }elseif($ret !== false) {
                echo "$ret \t success \n";
            }
            $i ++;
        }
        fclose($fp);
    }

    private function _handleLog($line, $offset)
    {
        $client_ip = $this->_getRequestIp($line, 'client_ip');
        $request_time = $this->_getRequestTime($line);
        $log_flag = RequestLogModel::isLogExists($request_time, $this->_file_name, $offset, $client_ip);
        if($log_flag) {
            return true;
        }

        $system_name = $this->_getSystemByLine($line);
        if(empty($system_name)) {
            return false;
        }
        $system_id = $this->_handleSystem($system_name);

        $method = $this->_getMethodByLine($line);
        $uri = $this->_getUriByLine($line);
        //静态资源忽略
        if($this->_isUriStatic($uri)) {
            return false;
        }

        $interface = $this->_handleInterface($system_id, $method, $uri);
        if(is_null($interface) || !isset($interface->id)) {
            return false;
        }

        $request_log = [
            'system_id'        => $system_id,
            'interface_id'     => $interface->id,
            'accesslog_file'   => $this->_file_name,
            'accesslog_offset' => $offset,
            'server_ip'        => $this->_getRequestIp($line, 'server_ip'),
            'client_ip'        => $client_ip,
            'request_header'   => $this->_getRequestHeader($line),
            'request_time'     => $request_time,
            'request_querystr' => json_encode($this->_getQueryStr($line), JSON_UNESCAPED_UNICODE),
            'http_code'        => $this->_getHttpCode($line),
            'country'          => $this->_getCountry($line),
            'region'           => $this->_getRegion($line),
            'city'             => $this->_getCity($line),
            'request_consume'  => $this->_getConsume($line, 'request_consume'),
            'upstream_consume' => $this->_getConsume($line, 'upstream_consume'),
        ];
        $ret = RequestLogModel::createLog($request_log);
        $interface->updateRequestInfo($request_log['request_consume']);

        $statistics = $this->_getInterfaceStatistics($system_id, $interface->id, $request_log['request_time'], $request_log['http_code']);
        $statistics->updateRequestInfo($request_log['request_consume'], $request_log['http_code']);

        return $ret;
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
        $pattern = '/^.*\[.*\]\s+\d*\.\d*\s+\"\w*\s+(\/.*)\s+HTTP\/1\.1\"/';
        if(preg_match($pattern, $line, $arr)) {
            if(isset($arr[1])) {
                if(strpos($arr[1], '?') === false) {
                    return $arr[1];
                }else {
                    $tmp_arr = explode('?', $arr[1]);
                    return $tmp_arr[0];
                }
            }
        }

        return false;
    }

    private function _isUriStatic($uri)
    {
        $arr = explode('/', $uri);
        $url_suffix = array_pop($arr);
        if(strpos($url_suffix, '.') !== false) {
            $extension_arr = explode('.', $url_suffix);
            $extension = array_pop($extension_arr);
            if(in_array($extension, self::$static_extension)) {
                return true;
            }
        }

        return false;
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

        return $interface;
    }

    private function _getRequestIp($line, $flag = 'server_ip')
    {
        $pattern = '/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\s*-\s*(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}).*/';
        if(preg_match($pattern, $line, $arr)) {
            if($flag == 'server_ip' && isset($arr[2])) {
                return $arr[2];
            }elseif($flag == 'client_ip' && isset($arr[1])) {
                return $arr[1];
            }
        }

        return '';
    }

    private function _getRequestHeader($line)
    {
        $pattern = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*-\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*HTTP\/1\.1\"\s+.*\d+\s+\d+\s+\d+\s+\".*?\"\s*?\"(.*?)\"/';
        preg_match($pattern, $line, $arr);

        return isset($arr[1]) ? $arr[1] : '';
    }

    private function _getRequestTime($line)
    {
        $pattern = '/\[(.*?)\]/';
        if(preg_match($pattern, $line, $arr)) {
            if(isset($arr[1])) {
                $time = date('Y-m-d H:i:s', strtotime($arr[1]));
                return $time;
            }
        }

        return '0000-00-00 00:00:00';
    }

    private function _getQueryStr($line)
    {
        $ret_query_str = [];
        //先去url中的query_str
        $pattern = '/^.*\[.*\]\s+\d*\.\d*\s+\"\w*\s+(\/.*)\s+HTTP\/1\.1\"/';
        if(preg_match($pattern, $line, $arr)) {
            if(isset($arr[1]) && strpos($arr[1], '?') !== false) {
                $tmp_arr = explode('?', $arr[1]);
                $url_query_str = $tmp_arr[1];
                $url_query_arr = explode('&', $url_query_str);
                foreach($url_query_arr as $query) {
                    $tmp_sigle_query = explode('=', $query);
                    if(isset($tmp_sigle_query[0]) && isset($tmp_sigle_query[1])) {
                        $ret_query_str[trim($tmp_sigle_query[0])] = trim($tmp_sigle_query[1]);

                    }
                }
            }
        }

        //再取post中的参数
        $pattern = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*-\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*HTTP\/1\.1\"\s+\{(.*)\}\s+\d+\s+\d+\s+\d+\s+\".*?\"\s*?\".*?\"/';
        preg_match($pattern, $line, $arr);
        if(isset($arr[1])) {
            $tmp_str = str_replace(["\\x22", "\\x5C"], '', $arr[1]);
            $tmp_arr = explode(',', $tmp_str);
            foreach($tmp_arr as $post_str) {
                $tmp_sigle_query = explode(':', $post_str);
                if(isset($tmp_sigle_query[0]) && isset($tmp_sigle_query[1])) {
                    $ret_query_str[trim($tmp_sigle_query[0])] = trim($tmp_sigle_query[1]);

                }
            }
        }

        return $ret_query_str;
    }

    private function _getHttpCode($line)
    {
        $pattern = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*-\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*HTTP\/1\.1\"\s+.*\s+(\d+)\s+\d+\s+\d+\s+\".*?\"\s*?\".*?\"/';
        preg_match($pattern, $line, $arr);

        return isset($arr[1]) ? $arr[1] : 0;
    }

    private function _getCountry($line)
    {
        $pattern = '/.*country\:(.*)\s+region/';
        preg_match($pattern, $line, $arr);

        return isset($arr[1]) ? $arr[1] : '';
    }

    private function _getRegion($line)
    {
        $pattern = '/.*region\:(.*)\s+city/';
        preg_match($pattern, $line, $arr);

        return isset($arr[1]) ? $arr[1] : '';
    }

    private function _getCity($line)
    {
        $pattern = '/.*city\:(.*)\s+member_id/';
        preg_match($pattern, $line, $arr);

        return isset($arr[1]) ? $arr[1] : '';
    }

    private function _getConsume($line, $consume_flag = 'request_consume')
    {
        $pattern = '/.*(\d+\.\d+)\s+(\d+\.\d+)\s+.*country/';
        preg_match($pattern, $line, $arr);
        if($consume_flag == 'request_consume' && isset($arr[1]))
        {
            return $arr[1] * 1000;
        }elseif($consume_flag == 'upstream_consume' && isset($arr[2])) {
            return $arr[2] * 1000;
        }

        return 0;
    }

    private function _getInterfaceStatistics($system_id, $interface_id, $request_time, $http_code)
    {
        $request_time = date('Y-m-d 00:00:00', strtotime($request_time));
        $statistics = InterfaceStatisticsModel::where('system_id', $system_id)->where('interface_id', $interface_id)->where('date', $request_time)->first();
        if(is_null($statistics)) {
            $statistics = new InterfaceStatisticsModel();
            $statistics->system_id = $system_id;
            $statistics->interface_id = $interface_id;
            $statistics->date = $request_time;
            $statistics->save();
        }

        return $statistics;
    }

}

$obj = new importData($argv);
$obj->execute();
