<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\RequestLogModel;

class LogController extends Controller 
{
    public function getList()
    {
        $request_log = new RequestLogModel();
        $logs = $request_log->find(1);
    }
}
