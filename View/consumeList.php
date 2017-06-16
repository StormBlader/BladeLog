<!DOCTYPE html>
<html lang="zh-ch">
<?php include ROOT . 'View/widgets/head.php';?>
<link rel="stylesheet" href="/static/plugins/datatables/dataTables.bootstrap.css">
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
      系统接口耗时
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">系统接口耗时</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">系统接口耗时列表</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
              <form>
                <div class="col-sm-12">
                  选择系统搜索：
                  <select name="system_id">
                    <?php foreach($systems as $key_system_id => $system) { ?>
                    <option  <?php if($key_system_id == $data['system_id']) { ?> selected="selected" <?php } ?> value="<?=$key_system_id?>"><?=$system?></option>
                    <?php } ?>
                  </select>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  接口耗时标准：
                  <input type="number" name="avg_consume_search" placeholder="接口耗时标准" value="<?=$data['avg_consume_search']?>"/>&nbsp;ms
                  &nbsp;&nbsp;&nbsp;&nbsp;
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
                  <th>统计时间</th>
                  <th>接口uri</th>
                  <th>最短耗时(ms)</th>
                  <th>最长耗时(ms)</th>
                  <th>平均耗时(ms)</th>
                  <th>总请求次数</th>
                  <th>http200数</th>
                  <th>http499数</th>
                  <th>http5xx数</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                	<?php foreach($data['interface_statistics'] as $statistics) { ?>
              	<tr>
              	  <td><?=$systems[$statistics->system_id]?></td>
              	  <td><?=date('Y-m-d', strtotime($statistics->date))?></td>
              	  <td><?=$data['interfaces'][$statistics->interface_id]['uri']?></td>
              	  <td><?=$statistics->min_request_time?></td>
              	  <td><?=$statistics->max_request_time?></td>
              	  <td><?=$statistics->avg_request_time?></td>
              	  <td><?=$statistics->request_count?></td>
              	  <td><?=$statistics->code_200_count?></td>
              	  <td><?=$statistics->code_499_count?></td>
              	  <td><?=$statistics->code_5xx_count?></td>
              	  <td><a href="/consume/detail?interface_id=<?=$statistics->interface_id?>">查看</td>
              	</tr>
              	<?php } ?>
              </tbody>
            </table>
            <div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">共有<?=$data['interface_statistics']->total();?>个</div>
              </div>
              <div class="col-sm-7">
                <?=$data['interface_statistics']->render();?>
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
<script type="text/javascript">
//Date picker
$('.datepicker').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true
});
</script>
</html>

