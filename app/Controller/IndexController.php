<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\InterfaceModel;

class IndexController extends Controller 
{
	private $config_model;

	public function _initialize(){
	}

	public function index() {
        $worst_interfaces = InterfaceModel::getWorstAvgRequestInterface();
        var_dump($worst_interfaces);exit;
		$this->assign('title',$title);
		$this->display('View/index.php');
	}

}
