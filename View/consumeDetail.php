<!DOCTYPE html>
<html lang="zh-ch">
<?php include ROOT . 'View/widgets/head.php';?>
<link rel="stylesheet" href="/static/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="/static/plugins/morris/morris.css">
<!-- bootstrap datepicker -->
<script src="/static/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="/static/plugins/datepicker/bootstrap-datepicker.js"></script>
<?php include ROOT . 'View/widgets/header.php';?>
<?php include ROOT . 'View/widgets/leftmenu.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      接口耗时详情
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">接口耗时详情</a></li>
    </ol>
  </section>
  <br>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <i class="fa fa-text-width"></i>

          <h3 class="box-title">接口详情</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <dl class="dl-horizontal">
            <dt>所属系统</dt>
            <dd><?=$systems[$data['interface']->system_id]?></dd>
            <dt>request uri</dt>
            <dd><?=$data['interface']->uri?></dd>
            <dt>http method</dt>
            <dd><?=$data['interface']->method?></dd>
            <dt>总体平均耗时</dt>
            <dd><?=$data['interface']->avg_request_time?>ms</dd>
            <dt>总体最长耗时</dt>
            <dd><?=$data['interface']->max_request_time?>ms</dd>
            <dt>总体最短耗时</dt>
            <dd><?=$data['interface']->min_request_time?>ms</dd>
          </dl>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

  <section class="content">
  <div class="row">
    <div class="box">
      <div class="box-header">
        <i class="fa fa-text-width"></i>
        <h3 class="box-title">当月请求量走势</h3>
      </div>
      <div class="box-body">
        <!-- /.col (LEFT) -->
        <div class="col-md-12">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">请求量</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="request_line" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>
    </div>

    <div class="box">
      <div class="box-header">
        <i class="fa fa-text-width"></i>
        <h3 class="box-title">当月平均耗时走势</h3>
      </div>
      <div class="box-body">
        <!-- /.col (LEFT) -->
        <div class="col-md-12">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">接口平均耗时</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="avg_time_line" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>
    </div>

    <div class="box">
      <div class="box-header">
        <i class="fa fa-text-width"></i>
        <h3 class="box-title">当月错误状态码走势</h3>
      </div>
      <div class="box-body">
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">499状态码走势</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="499_line" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col (RIGHT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">5xx状态码走势</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="5xx_line" style="height: 300px;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </div>
  </div>
  </section>

  <section class="content">
    <div class="row">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">接口耗时详情</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
              <form>
                <input type="hidden" name="interface_id" value="<?=$data['interface']['id']?>"/>
                <div class="col-sm-12">
                  开始时间：
                  <input type="text" class="datepicker" name="begin_date_search" placeholder="开始时间" value="<?=$data['begin_date_search']?>" />&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  结束时间：
                  <input type="text" class="datepicker" name="end_date_search" placeholder="结束时间" value="<?=$data['end_date_search']?>" />&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="submit" value="search"/>
                </div>
              </form>
            </div>
            <br>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>接口系统</th>
                  <th>接口uri</th>
                  <th>统计时间</th>
                  <th>最短耗时(ms)</th>
                  <th>最长耗时(ms)</th>
                  <th>平均耗时(ms)</th>
                  <th>总请求次数</th>
                  <th>http200数</th>
                  <th>http499数</th>
                  <th>http5xx数</th>
                </tr>
              </thead>
              <tbody>
                	<?php foreach($data['statistics_list'] as $statistics) { ?>
              	<tr>
              	  <td><?=$systems[$statistics->system_id]?></td>
                  <td><?=$data['interface']['uri']?></td>
              	  <td><?=date('Y-m-d', strtotime($statistics->date))?></td>
              	  <td><?=$statistics->min_request_time?></td>
              	  <td><?=$statistics->max_request_time?></td>
              	  <td><?=$statistics->avg_request_time?></td>
              	  <td><?=$statistics->request_count?></td>
              	  <td><?=$statistics->code_200_count?></td>
              	  <td><?=$statistics->code_499_count?></td>
              	  <td><?=$statistics->code_5xx_count?></td>
              	</tr>
              	<?php } ?>
              </tbody>
            </table>
            <div class="row">
              <div class="col-sm-5">
              </div>
              <div class="col-sm-7">
                <?=$data['statistics_list']->render();?>
              </div>
            </div>
          
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </section>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer"></footer>
<?php include ROOT . 'View/widgets/rightside.php';?>
<?php include ROOT . 'View/widgets/exportjs.php';?>

<script src="/static/plugins/morris/morris.min.js"></script>
<script type="text/javascript">
//Date picker
$('.datepicker').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true
});
var onemonth_requestcount = <?=json_encode($data['onemonth_requestcount'])?>;
var onemouth_requestcount_data = [];
$.each(onemonth_requestcount, function(key, item){
  var object = {};
  object.date = key;
  object.count = item;

  onemouth_requestcount_data.push(object);
});

var onemonth_avgtime = <?=json_encode($data['onemonth_avgtime'])?>;
var onemouth_avgtime_data = [];
$.each(onemonth_avgtime, function(key, item){
  var object = {};
  object.date = key;
  object.count = item;

  onemouth_avgtime_data.push(object);
});

var onemonth_code_499_count = <?=json_encode($data['onemonth_code_499_count'])?>;
var onemouth_499_data = [];
$.each(onemonth_code_499_count, function(key, item){
  var object = {};
  object.date = key;
  object.count = item;

  onemouth_499_data.push(object);
});

var onemonth_code_5xx_count = <?=json_encode($data['onemonth_code_5xx_count'])?>;
var onemouth_5xx_data = [];
$.each(onemonth_code_5xx_count, function(key, item){
  var object = {};
  object.date = key;
  object.count = item;

  onemouth_5xx_data.push(object);
});

// LINE CHART
var line = new Morris.Line({
  element: 'request_line',
  resize: true,
  data: onemouth_requestcount_data,
  xkey: 'date',
  ykeys: ['count'],
  labels: ['请求量'],
  lineColors: ['#3c8dbc'],
  hideHover: 'auto'
});
// LINE CHART
var line = new Morris.Line({
  element: 'avg_time_line',
  resize: true,
  data: onemouth_avgtime_data,
  xkey: 'date',
  ykeys: ['count'],
  labels: ['平均耗时'],
  lineColors: ['#3c8dbc'],
  hideHover: 'auto'
});
// LINE CHART
var line = new Morris.Line({
  element: '499_line',
  resize: true,
  data: onemouth_499_data,
  xkey: 'date',
  ykeys: ['count'],
  labels: ['499状态码'],
  lineColors: ['#3c8dbc'],
  hideHover: 'auto'
});
// LINE CHART
var line = new Morris.Line({
  element: '5xx_line',
  resize: true,
  data: onemouth_5xx_data,
  xkey: 'date',
  ykeys: ['count'],
  labels: ['5xx状态码'],
  lineColors: ['#3c8dbc'],
  hideHover: 'auto'
});

</script>
</html>

