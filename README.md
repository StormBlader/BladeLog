BladePhp 
名如其实

刀锋所致，所向披靡

一个轻量级的php框架

1. git clone
2. composer install
3. mysql导入blade_log.sql
4. 配置nginx的rewrite
   try_files $uri $uri/ @rewrite;
   location @rewrite {
       rewrite ^(.*)$ /index.php?_url=$1;
   }
5. 修改app/Console/importData的脚本进行数据采集
6. 每天增量进行解析导入
