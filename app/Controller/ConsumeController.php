<?php
namespace App\Controller;
use Lib\Controller;
use App\Model\InterfaceStatisticsModel;
use App\Model\InterfaceModel;

use Illuminate\Database\Capsule\Manager as DB;

class ConsumeController extends Controller
{

    public function getList()
    {
        $system_id          = $this->getRequest('system_id', 1);
        $avg_consume_search = $this->getRequest('avg_consume_search', 1000);
        $begin_date_search  = $this->getRequest('begin_date_search');
        $end_date_search    = $this->getRequest('end_date_search');

        $interface_statistics_model = InterfaceStatisticsModel::needPage();
        $interface_statistics = $interface_statistics_model::where('system_id', $system_id)->where('avg_request_time', '>=', $avg_consume_search);
        if(!empty($begin_date_search)) {
            $interface_statistics = $interface_statistics->where('date', '>=', $begin_date_search);
        }

        if(!empty($end_date_search)) {
            $interface_statistics = $interface_statistics->where('date', '<=', $end_date_search);
        }

        $interface_statistics = $interface_statistics->orderBy('avg_request_time', 'desc')->orderBy('date', 'desc')->paginate(15)->appends(['system_id' => $system_id, 'avg_consume_search' => $avg_consume_search]);
        if(!empty($begin_date_search)) {
            $interface_statistics->appends(['date' => $begin_date_search]);
        }

        if(!empty($end_date_search)) {
            $interface_statistics->appends(['date' => $end_date_search]);
        }

        $interface_ids = [];
        foreach($interface_statistics->items() as $statics) {
            $interface_ids[] = $statics->interface_id;
        }
        $interface_ids = array_unique($interface_ids);

        $interfaces = [];
        $tmp_interfaces = InterfaceModel::whereIn('id', $interface_ids)->get();
        foreach($tmp_interfaces as $interface) {
            $interfaces[$interface->id] = $interface;
        }

        $data = [
            'system_id'            => $system_id,
            'avg_consume_search'   => $avg_consume_search,
            'begin_date_search'    => $begin_date_search,
            'end_date_search'      => $end_date_search,
            'interface_statistics' => $interface_statistics,
            'interfaces'           => $interfaces,
        ];

        $this->assign('data', $data);
        $this->display('View/consumeList.php');
    }

    public function detail()
    {
        $interface_id = $this->getRequest('interface_id', 0);
        $begin_date_search  = $this->getRequest('begin_date_search');
        $end_date_search    = $this->getRequest('end_date_search');

        $interface = InterfaceModel::findOrFail($interface_id);

        $interface_statistics_model = InterfaceStatisticsModel::needPage();
        $statistics_list = $interface_statistics_model::where('interface_id', $interface_id)->orderBy('date', 'desc');
        if(!empty($begin_date_search)) {
            $statistics_list = $statistics_list->where('date', '>=', $begin_date_search);
        }
        if(!empty($end_date_search)) {
            $statistics_list = $statistics_list->where('date', '<=', $end_date_search);
        }

        $statistics_list = $statistics_list->paginate(15)->appends(['interface_id' => $interface_id]);
        if(!empty($begin_date_search)) {
            $statistics_list->appends(['date' => $begin_date_search]);
        }
        if(!empty($end_date_search)) {
            $statistics_list->appends(['date' => $end_date_search]);
        }

        $data = [
            'begin_date_search'       => $begin_date_search,
            'end_date_search'         => $end_date_search,
            'statistics_list'         => $statistics_list,
            'interface'               => $interface,
            'onemonth_avgtime'        => $interface->getOneMonthInfo('avg_request_time'),
            'onemonth_requestcount'   => $interface->getOneMonthInfo('request_count'),
            'onemonth_code_499_count' => $interface->getOneMonthInfo('code_499_count'),
            'onemonth_code_5xx_count' => $interface->getOneMonthInfo('code_5xx_count'),
        ];

        $this->assign('data', $data);
        $this->display('View/consumeDetail.php');
    }

}
