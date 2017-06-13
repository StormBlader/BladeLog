<?php
namespace App\Model;

class InterfaceStatisticsModel extends BaseModel
{
    protected $table = 'interface_statistics';

    public function updateRequestInfo($request_consume, $http_code)
    {
        if(empty($request_consume)) {
            return false;
        }

        if($request_consume > $this->max_request_time) {
            $this->max_request_time = $request_consume;
        }

        if($this->min_request_time == 0 || $request_consume < $this->min_request_time) {
            $this->min_request_time = $request_consume;
        }

        $cal_avg_request_time = $this->avg_request_time * $this->request_count;
        $this->request_count += 1;
        $this->avg_request_time = intval(($cal_avg_request_time + $request_consume) / $this->request_count);

        if($http_code == '200') {
            $this->code_200_count += 1;
        }
        if($http_code == '499') {
            $this->code_499_count += 1;
        }
        if($http_code >= 400) {
            $this->code_4xx_count += 1;
        }
        if($http_code >= 500) {
            $this->code_5xx_count += 1;
        }

        return $this->save();
    }

    public static function getRecentOvertimeCount()
    {
        $begin_time = date('Y-m-d', strtotime('-5 day'));
        $now_time = date('Y-m-d');

        return self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('avg_request_time', '>', '1000')->count();
    }

    public static function getRecent5xxCount()
    {
        $begin_time = date('Y-m-d', strtotime('-5 day'));
        $now_time = date('Y-m-d');

        return self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('code_5xx_count', '>', '0')->count();
    }

    public static function getRecent499Count()
    {
        $begin_time = date('Y-m-d', strtotime('-5 day'));
        $now_time = date('Y-m-d');

        return self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('code_499_count', '>', '0')->count();
    }

    public static function getOneWeekOvertimeInfo()
    {
        $begin_time = date('Y-m-d', strtotime('-7 day'));
        $now_time = date('Y-m-d');

        $ret = self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('avg_request_time', '>', '1000')->get();
    }

    public static function getOneWeekErrorInfo()
    {

    }

}
