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

}
