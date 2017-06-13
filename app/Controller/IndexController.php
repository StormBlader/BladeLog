<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\InterfaceModel;
use App\Model\InterfaceStatisticsModel;
use App\Model\SystemModel;

class IndexController extends Controller 
{
	public function _initialize(){
	}

	public function index() {
        $worst_interfaces = InterfaceModel::getWorstAvgRequestInterface();
        $overtime_count = InterfaceModel::getOvertimeCount();
        $recent_overtime_count = InterfaceStatisticsModel::getRecentOvertimeCount();
        $err_5xx_count = InterfaceStatisticsModel::getRecent5xxCount();
        $err_499_count = InterfaceStatisticsModel::getRecent499Count();
        $oneweek_overtime = InterfaceStatisticsModel::getOneWeekOvertimeInfo();

        $data = [
            'worst_interfaces' => $worst_interfaces,
            'overtime_count'   => $overtime_count,
            'recent_overtime_count' => $recent_overtime_count,
            'err_5xx_count'        => $err_5xx_count,
            'err_499_count'        => $err_499_count,
        ];
        $this->assign('data', $data);
		$this->display('View/index.php');
	}

}
