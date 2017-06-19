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
        $begin_date  = $this->getRequest('begin_date', '');
        $end_date    = $this->getRequest('end_date', '');
        $min_consume = $this->getRequest('min_consume');
        $page        = $this->getRequest('page', 1);
        $system_id   = $this->getRequest('system_id', 0);
        $interface_id = $this->getRequest('interface_id', 0);

        $where = [];
        !empty($system_id) && $where['system_id'] = $system_id;
        !empty($interface_id) && $where['interface_id'] = $interface_id;

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

        $data = [
            'logs'        => $logs,
            'interfaces'  => $interfaces,
            'date'        => $date,
            'begin_date'  => $begin_date,
            'end_date'    => $end_date,
            'min_consume' => $min_consume,
            'page'        => $page,
            'system_id'   => $system_id,
        ];

        $this->assign('data', $data);
        $this->display('View/logList.php');
    }
}
