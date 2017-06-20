<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- search form -->
    <form action="/index/search" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="hidden" name="search_uri_id" id="search_uri_id" value="0"/>
        <input type="text" id="search_uri" class="form-control" placeholder="输入接口uri搜索" oninput="searchUri()"  AUTOCOMPLETE="off">
        
        <span class="input-group-btn">
          <button type="submit" id="search-btn" class="btn btn-flat">
            <i class="fa fa-search"></i>
          </button>
        </span>
      </div>
      <ul class="on_changes" id="searchList" style="position:relative; list-style:none; background:#FFF; border:1px solid #000; padding:3px; display:none;">
      </ul>
    </form>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">菜单 </li>
      <li>
        <a href="/">
          <i class="fa fa-dashboard"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="/slow">
          <i class="fa fa-th"></i> <span>慢接口排名</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-files-o"></i>
          <span>各系统接口耗时</span>
          <span class="pull-right-container">
            <span class="label label-primary pull-right"><?=count($systems)?></span>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php foreach($systems as $key_system_id => $system) { ?>
          <li><a href="/consume/getList?system_id=<?=$key_system_id?>"><i class="fa fa-circle-o"></i><?=$system?></a></li>
          <?php } ?>
        </ul>
      </li>
      <li>
        <a href="/log/getList">
          <i class="fa fa-th"></i> <span>access_log查看</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<script type="text/javascript">
$(document).ready(function(){
    /*导航高亮*/
    var path = window.location.pathname;

    $('ul.treeview-menu>li').find('a[href="'+path+'"]').closest('li').addClass('active');  //二级链接高亮
    $('ul.treeview-menu>li').find('a[href="'+path+'"]').closest('li.treeview').addClass('active');  //一级栏目[含二级链接]高亮
    $('.sidebar-menu>li').find('a[href="'+path+'"]').closest('li').addClass('active');  //一级栏目[不含二级链接]高亮
});
function searchUri()
{
  var uri = $("#search_uri").val();
  // 删除，保证每次都是最新的数据
  $("#searchList li").remove();
  $("#searchList").show();

  if(uri == '') {
    $("#searchList li").remove();
    $("#searchList").hide();
  }else {
    $.post(
      '/index/ajaxsearch',
      {
        uri : uri
      },
      function(data) {
        $.each(data, function(idx, item){
          var li = "<li onclick='getValue(&apos;"
            +item.id+"&apos;,&apos;"
            +item.uri+"&apos;)' onmouseover='this.style.backgroundColor=\"#ffff66\";'onmouseout='this.style.backgroundColor=\"#fff\";'>"
            +item.uri+"</li>";
          
          $("#searchList").append(li);
        });
      }
    );
  }
}

function getValue(id, uri)
{
  $("#search_uri").val(uri);
  $("#search_uri_id").val(id);
  $("#searchList").hide();
}
</script>