<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
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
        <a href="pages/widgets.html">
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
          <?php foreach($systems as $system) { ?>
          <li><a href=""><i class="fa fa-circle-o"></i><?=$system?></a></li>
          <?php } ?>
        </ul>
      </li>

      <li>
        <a href="">
          <i class="fa fa-th"></i> <span>请求log查看</span>
        </a>
      </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>