<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\InterfaceModel;
use App\Model\InterfaceStatisticsModel;
use App\Model\SystemModel;

class SlowController extends Controller 
{
    public function index() 
    {
        $systems = SystemModel::getAllSystem();

        $system_id = $this->getRequest('system_id');
        $min_consume = $this->getRequest('min_consume', 1000);

        $interfaces = InterfaceModel::where('avg_request_time', '>=', $min_consume);
        if(!is_null($system_id)) {
            $interfaces = $interfaces->where('system_id', $system_id);
        }
        $interfaces = $interfaces->orderBy('avg_request_time', 'desc')->take(15)->get();
        
        $data = [
            'interfaces' => $interfaces,
            'systems'    => $systems,
        ];

        $this->assign('data', $data);
        $this->display('View/slow.php');
	}

}
