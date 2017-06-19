<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\RequestLogModel;
use App\Model\InterfaceModel;

class LogController extends Controller 
{
    public function getList()
    {
        $yesterday   = date('Y-m-d', strtotime("-1 day"));
        $date        = $this->getRequest('date', $yesterday);
        $begin_time  = $this->getRequest('begin_time', '');
        $end_time    = $this->getRequest('end_time', '');
        $min_consume = $this->getRequest('min_consume');
        $page        = $this->getRequest('page', 1);
        $system_id   = $this->getRequest('system_id', 0);
        $interface_id = $this->getRequest('interface_id', 0);
        $http_code    = $this->getRequest('http_code');

        $where = [];
        $begin_date = '';
        $end_date = '';
        !empty($system_id)    && $where['system_id'] = $system_id;
        !empty($interface_id) && $where['interface_id'] = $interface_id;
        !empty($http_code)    && $where['http_code'] = $http_code;
        if(!empty($begin_time)) {
            $begin_date = date('Y-m-d H:i:s', strtotime($date . " $begin_time:00:00"));
        }
        if(!empty($end_time)) {
            $end_date = date('Y-m-d H:i:s', strtotime($date . "$end_time:00:00"));
        }

        $request_log = new RequestLogModel();
        $logs = $request_log->findByPage($date, $begin_date, $end_date, $min_consume, $where, $page);
        $interface_ids = [];

        foreach($logs as $log) {
            $interface_ids[] = $log['interface_id'];
        }
        $interfaces = [];
        $tmp_interfaces = InterfaceModel::whereIn('id', $interface_ids)->get();
        foreach($tmp_interfaces as $interface) {
            $interfaces[$interface->id] = $interface;
        }

        $current_uri = $_SERVER['REQUEST_URI'];
        if(strpos($current_uri, '?') !== false) {
            if(strpos($current_uri, 'page') !== false) {
                $previous_page = preg_replace('/page=\d*/', 'page=' .  (($page <= 1) ? 1 : $page - 1) , $current_uri);
                $next_page     = preg_replace('/page=\d*/', 'page=' . ($page + 1), $current_uri);
            }else {
                $previous_page = $current_uri . "&page =" . (($page <= 1) ? 1 : $page - 1);
                $next_page = $current_uri . "&page=" . ($page + 1);
            }
        }else {
            $previous_page = $current_uri . "?page =" . (($page <= 1) ? 1 : $page - 1) ;
            $next_page = $current_uri . "?page=" . ($page + 1);
        }

        $data = [
            'logs'          => $logs,
            'interfaces'    => $interfaces,
            'date'          => $date,
            'begin_time'    => $begin_time,
            'end_time'      => $end_time,
            'min_consume'   => $min_consume,
            'page'          => $page,
            'system_id'     => $system_id,
            'interface_id'  => $interface_id,
            'http_code'     => $http_code,
            'previous_page' => $previous_page,
            'next_page'     => $next_page,
        ];

        $this->assign('data', $data);
        $this->display('View/logList.php');
    }

}
