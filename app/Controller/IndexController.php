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

}
