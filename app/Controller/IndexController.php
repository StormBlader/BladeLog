<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\InterfaceModel;
use App\Model\InterfaceStatisticsModel;
use App\Model\SystemModel;

class IndexController extends Controller 
{
    public function index() 
    {
        $overtime_count = InterfaceModel::getOvertimeCount();
        $recent_overtime_count = InterfaceStatisticsModel::getRecentOvertimeCount();
        $err_5xx_count = InterfaceStatisticsModel::getRecent5xxCount();
        $err_499_count = InterfaceStatisticsModel::getRecent499Count();
        $oneweek_overtime = InterfaceStatisticsModel::getOneWeekOvertimeInfo();
        $oneweek_errorinfo = InterfaceStatisticsModel::getOneWeekErrorInfo();

        $data = [
            'overtime_count'        => $overtime_count,
            'recent_overtime_count' => $recent_overtime_count,
            'err_5xx_count'         => $err_5xx_count,
            'err_499_count'         => $err_499_count,
            'oneweek_overtime'      => $oneweek_overtime,
            'oneweek_errorinfo'     => $oneweek_errorinfo,
        ];
        $this->assign('data', $data);
		$this->display('View/index.php');
	}

    public function search()
    {
        $interface_id = $this->getRequest('search_uri_id');
        $interface = InterfaceModel::find($interface_id);
        if(is_null($interface)) {
            echo "no interface";
            exit;
        }

        header("location: /consume/detail?interface_id={$interface_id}");
    }

    public function ajaxsearch()
    {
        $uri = $this->getRequest('uri');
        if(empty($uri)) {
            return $this->response([]);
        }

        $interfaces = InterfaceModel::where('uri', 'like', "$uri%")->take(20)->get(['id', 'uri'])->toArray();
        return $this->response($interfaces);
    }

    public function ajaxSearchInterface()
    {
        $system_id = $this->getRequest('system_id', 0);
        $interfaces = InterfaceModel::where('system_id', $system_id)->select(['id', 'uri'])->get()->toArray();

        return $this->response($interfaces);
    }

}
