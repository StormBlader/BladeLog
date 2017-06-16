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
          <div class="row">
            <form>
              <div class="col-sm-8">
                选择系统搜索：
                <select name="system_id">
                  <option value="0">选择系统</option>
                  <?php foreach($systems as $key_system_id => $system) { ?>
                  <option  <?php if($key_system_id == $data['system_id']) { ?> selected="selected" <?php } ?> value="<?=$key_system_id?>"><?=$system?></option>
                  <?php } ?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                慢接口标准：
                <input type="number" name="min_consume" placeholder="慢接口标准" value="<?=$data['min_consume']?>"/>&nbsp;ms
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
                <th>最短耗时(ms)</th>
                <th>最长耗时(ms)</th>
                <th>平均耗时(ms)</th>
                <th>总请求次数</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data['interfaces'] as $interface) { ?>
              <tr>
                <td><?=$systems[$interface->system_id]?></td>
                <td><?=$interface->uri?></td>
                <td><?=$interface->min_request_time?></td>
                <td><?=$interface->max_request_time?></td>
                <td><?=$interface->avg_request_time?></td>
                <td><?=$interface->request_count?></td>
                <td><a href="/consume/detail?interface_id=<?=$interface->id?>">查看</a></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="row">
            <div class="col-sm-5">
              <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">共有<?=$data['interfaces']->total();?>个慢接口</div>
            </div>
            <div class="col-sm-7">
              <?=$data['interfaces']->render();?>
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

</html>

