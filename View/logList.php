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
      access_log
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">access_log</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">access_log</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
              <form>
                <div class="col-sm-12">
                  选择系统搜索：
                  <select name="system_id">
                    <option value="0">选择系统</option>
                    <?php foreach($systems as $key_system_id => $system) { ?>
                    <option  <?php if($key_system_id == $data['system_id']) { ?> selected="selected" <?php } ?> value="<?=$key_system_id?>"><?=$system?></option>
                    <?php } ?>
                  </select>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  接口耗时标准：
                  <input type="number" name="min_consume" placeholder="接口耗时标准" value="<?=$data['min_consume']?>"/>&nbsp;ms
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  日志时间：
                  <input type="text" class="datepicker" name="date" placeholder="开始时间" value="<?=$data['date']?>" />&nbsp;
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
                  <th>访问时间</th>
                  <th>服务器ip</th>
                  <th>客户端ip</th>
                  <th>来源地</th>
                  <th>返回http code</th>
                  <th>接口耗时(ms)</th>
                  <th>upstream耗时(ms)</th>
                </tr>
              </thead>
              <tbody>
                 <?php foreach($data['logs'] as $log) { ?>
              <tr>
                 <td><?=$systems[$log['system_id']]?></td>
                 <td><?=$data['interfaces'][$log['interface_id']]['uri']?></td>
                 <td><?=$log['request_time']?></td>
                 <td><?=$log['server_ip']?></td>
                 <td><?=$log['client_ip']?></td>
                 <td><?php echo sprintf('%s-%s-%s', $log['country'], $log['region'], $log['city'])?></td>
                 <td><?=$log['http_code']?></td>
                 <td><?=$log['request_consume']?></td>
                 <td><?=$log['upstream_consume']?></td>
               </tr>
                 <?php } ?>
              </tbody>
            </table>
            <div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">共有0个</div>
              </div>
              <div class="col-sm-7">
               
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

