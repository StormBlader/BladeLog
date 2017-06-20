<?php
namespace App\Controller;
use App\Model\InterfaceModel;
use App\Model\SystemModel;

class SlowController extends BaseController 
{
    public function index() 
    {
        $system_id = $this->getRequest('system_id');
        $min_consume = $this->getRequest('min_consume', 1000);

        $interface_model = InterfaceModel::needPage();
        $interfaces = $interface_model::where('avg_request_time', '>=', $min_consume);
        if(!empty($system_id)) {
            $interfaces = $interfaces->where('system_id', $system_id);
        }

        $interfaces = $interfaces->orderBy('avg_request_time', 'desc')->paginate(15)->addQuery('min_consume', $min_consume);
        if(!is_null($system_id)) {
            $interfaces->addQuery('system_id', $system_id);
        }
        
        $data = [
            'interfaces'  => $interfaces,
            'system_id'   => $system_id,
            'min_consume' => $min_consume,
        ];

        $this->assign('data', $data);
        $this->display('View/slow.php');
	}

}
