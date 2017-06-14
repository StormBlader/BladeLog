<!DOCTYPE html>
<html lang="zh-ch">
<?php include ROOT . 'View/widgets/head.php';?>
<link rel="stylesheet" href="/static/plugins/datatables/dataTables.bootstrap.css">
<?php include ROOT . 'View/widgets/header.php';?>
<?php include ROOT . 'View/widgets/leftmenu.php';?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      慢接口
      <small>比较平均耗时</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">慢接口</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">慢接口列表</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>接口系统</th>
                <th>接口uri</th>
                <th>最短耗时(ms)</th>
                <th>最长耗时(ms)</th>
                <th>平均耗时(ms)</th>
                <th>请求次数</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data['interfaces'] as $interface) { ?>
              <tr>
                <td><?=$data['systems'][$interface->system_id]?></td>
                <td><?=$interface->uri?></td>
                <td><?=$interface->min_request_time?></td>
                <td><?=$interface->max_request_time?></td>
                <td><?=$interface->avg_request_time?></td>
                <td><?=$interface->request_count?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    <div class="row">
      <div class="col-sm-5">
        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">共有个慢接口</div>
      </div>
      <div class="col-sm-7">
      </div>
    </div>
  </section>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer"></footer>
<?php include ROOT . 'View/widgets/rightside.php';?>
<?php include ROOT . 'View/widgets/exportjs.php';?>

</html>
