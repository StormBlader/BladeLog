<?php
namespace App\Model;

class RequestLogModel extends BaseModel
{
    protected $table = 'request_log';

    public static function createLog($data)
    {
        $log                   = new self();
        $log->system_id        = $data['system_id'];
        $log->interface_id     = $data['interface_id'];
        $log->server_ip        = $data['server_ip'];
        $log->client_ip        = $data['client_ip'];
        $log->request_header   = $data['request_header'];
        $log->request_time     = $data['request_time'];
        $log->request_querystr = $data['request_querystr'];
        $log->http_code        = $data['http_code'];
        $log->country          = $data['country'];
        $log->region           = $data['region'];
        $log->city             = $data['city'];
        $log->request_consume  = $data['request_consume'];
        $log->upstream_consume = $data['upstream_consume'];
        $ret = $log->save();
        if($ret) {
            return $log->id;
        }

        return false;
    }
}
