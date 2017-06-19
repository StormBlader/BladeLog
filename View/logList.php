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
          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
              <form>
                <div class="col-sm-12">
                  选择系统搜索：
                  <select name="system_id" id="system_id" onchange="selectSystem(0)">
                    <option value="0">选择系统</option>
                    <?php foreach($systems as $key_system_id => $system) { ?>
                    <option  <?php if($key_system_id == $data['system_id']) { ?> selected="selected" <?php } ?> value="<?=$key_system_id?>"><?=$system?></option>
                    <?php } ?>
                  </select>
                  &nbsp;&nbsp;
                  <span id="interface_select_span" style="display:none">
                    选择接口：
                    <select name="interface_id" id="interface_select" style="width:150px;"></select>
                    &nbsp;&nbsp;
                  </span>
                  接口耗时标准：
                  <input type="number" name="min_consume" placeholder="接口耗时标准" value="<?=$data['min_consume']?>"/>&nbsp;ms
                  &nbsp;&nbsp;
                  http code：
                  <input type="number" name="http_code" placeholder="返回http_code" value="<?=$data['http_code']?>"/>
                  &nbsp;&nbsp;
                  日志时间：
                  <input type="text" class="datepicker" name="date" placeholder="开始时间" value="<?=$data['date']?>" />
                  &nbsp;&nbsp;
                  <br>
                  时间段：
                  <input type="text" name="begin_time" value="<?=$data['begin_time']?>">h - <input type="text" name="end_time" value="<?=$data['end_time']?>">h
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
              <div class="col-sm-5"></div>
              <div class="col-sm-7">
                <ul class="pagination">
                  <li><a href="<?=$data['previous_page']?>">«</a></li>
                  <li class="active"><span><?=$data['page']?></span></li>
                  <li><a href="<?=$data['next_page']?>">»</a></li>
                </ul>
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
selectSystem("<?=$data['interface_id']?>");
//Date picker
$('.datepicker').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true
});



function selectSystem(interface_id)
{
  var system_id = $("#system_id").val();
  var $interface_info = $("#interface_select_span");
  if(system_id == 0) {
    $("#interface_select").empty();
    $interface_info.hide();
    return false;
  }

  $.post(
    '/index/ajaxSearchInterface',
    {
      system_id : system_id
    },
    function(data) {
      $("#interface_select").append('<option value="0">请选择</option>');
      $.each(data, function(idx, item){
        if(interface_id == item.id) {
          var option = '<option value="' + item.id + '" selected="selected"> ' + item.uri +' </option>';
        }else{
          var option = '<option value="' + item.id + '"> ' + item.uri +' </option>';
        }
        
        $("#interface_select").append(option);
      });
      $interface_info.show();
    });
}


</script>
</html>

