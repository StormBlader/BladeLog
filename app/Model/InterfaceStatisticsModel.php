<?php
namespace App\Model;

use Illuminate\Database\Capsule\Manager as DB;

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
        $begin_time = date('Y-m-d', strtotime('-7 day'));
        $now_time = date('Y-m-d');

        return self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('avg_request_time', '>', '1000')->count();
    }

    public static function getRecent5xxCount()
    {
        $begin_time = date('Y-m-d', strtotime('-7 day'));
        $now_time = date('Y-m-d');

        $ret = self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('code_5xx_count', '>', '0')->select(DB::raw('sum(code_5xx_count) as count'))->first()->toArray();

        return ($ret['count']) ? $ret['count'] : 0;
    }

    public static function getRecent499Count()
    {
        $begin_time = date('Y-m-d', strtotime('-7 day'));
        $now_time = date('Y-m-d');

        $ret = self::where('date' , '>=', $begin_time)->where('date', '<=', $now_time)->where('code_499_count', '>', '0')->select(DB::raw('sum(code_499_count) as count'))->first()->toArray();

        return ($ret['count']) ? $ret['count'] : 0;
    }

    public static function getOneWeekOvertimeInfo()
    {
        $begin_time = date('Y-m-d 00:00:00', strtotime('-7 day'));
        $now_time = date('Y-m-d 00:00:00');
        $arr_time = [];
        for($i = 7; $i > 0; $i --) {
            $arr_time[] = date('Y-m-d 00:00:00', strtotime("-$i day"));
        }
        $arr_time[] = $now_time;

        $tmp_ret = [];
        $group_ret =  self::where('avg_request_time', '>', 1000)->where('date', '>=', $begin_time)->where('date', '<=', $now_time)->groupBy('system_id')->groupBy('date')->select('system_id', 'date', DB::raw('count(*) as cnt'))->get()->toArray();
        $systems = SystemModel::getAllSystem();
        foreach($group_ret as $item) {
            $tmp_ret[$item['system_id']][$item['date']] = $item['cnt'];
        }

        $ret = [];
        foreach($tmp_ret as $system_id => $info) {
            foreach($arr_time as $time) {
                $time_formate = date('Y-m-d', strtotime((string)$time));
                $ret[$systems[$system_id]][$time_formate] = isset($info[$time]) ? $info[$time] : 0;
            }
        }

        return $ret;
    }

    public static function getOneWeekErrorInfo()
    {
        $begin_time = date('Y-m-d', strtotime('-7 day'));
        $now_time = date('Y-m-d');
        $arr_time = [];
        for($i = 7; $i > 0; $i --) {
            $arr_time[] = date('Y-m-d 00:00:00', strtotime("-$i day"));
        }
        $arr_time[] = $now_time;

        $tmp_ret = [];
        $group_ret = self::where('date', '>=', $begin_time)->where('date', '<=', $now_time)->groupBy('system_id')->groupBy('date')->select('system_id', 'date', DB::raw('sum(code_5xx_count) as cnt'))->get()->toArray();
        $systems = SystemModel::getAllSystem();
        foreach($group_ret as $item) {
            $tmp_ret[$item['system_id']][$item['date']] = $item['cnt'];
        }

        $ret = [];
        foreach($tmp_ret as $system_id => $info) {
            foreach($arr_time as $time) {
                $time_formate = date('Y-m-d', strtotime((string)$time));
                $ret[$systems[$system_id]][$time_formate] = isset($info[$time]) ? $info[$time] : 0;
            }
        }

        return $ret;
    }

}
