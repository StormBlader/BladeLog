<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\InterfaceModel;
use App\Model\SystemModel;

class IndexController extends Controller 
{
	private $config_model;

	public function _initialize(){
	}

	public function index() {
        $worst_interfaces = InterfaceModel::getWorstAvgRequestInterface();
		$this->display('View/index.php');
	}

}
