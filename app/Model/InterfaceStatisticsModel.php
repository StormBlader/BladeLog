<?php
namespace App\Model;

class InterfaceStatisticsModel extends BaseModel
{
    protected $table = 'interface_statistics';

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

}
