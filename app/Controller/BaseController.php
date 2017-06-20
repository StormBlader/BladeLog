<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\SystemModel;

class BaseController extends Controller
{
    public function init()
    {
        $systems = SystemModel::getAllSystem();
        $this->assign('systems', $systems);
    }
}
