<?php
namespace App\Model;

class InterfaceModel extends BaseModel
{
    protected $table = 'interface_info';

    public function updateRequestInfo($request_consume)
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

        return $this->save();
    }

    public static function getWorstAvgRequestInterface()
    {
        return self::where('avg_request_time', '>', '1000')->orderBy('avg_request_time', 'desc')->take(20)->get();
    }

    public static function getOvertimeCount()
    {
        return self::where('avg_request_time', '>', '1000')->count();
    }

    public function getOneMonthInfo($info_attr)
    {
        $begin_time = date('Y-m-d 00:00:00', strtotime("-1 month"));
        $end_time = date('Y-m-d 00:00:00');

        $tmp_statictis = InterfaceStatisticsModel::where('date', '>=', $begin_time)->where('date', '<=', $end_time)->where('interface_id', $this->id)->get();
        $interface_statistics = [];
        foreach($tmp_statictis as $statictis) {
            $interface_statistics[$statictis->date] = $statictis[$info_attr];
        }

        $ret = [];
        $time_arr = getTimeArr($begin_time, $end_time);
        foreach($time_arr as $time_item) {
            if(isset($interface_statistics[$time_item])) {
                $ret[$time_item] = $interface_statistics[$time_item];
            }else {
                $ret[$time_item] = 0;
            }
        }

        return $ret;
    }

}
