<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>正在努力获取数据中......</title>
  <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="progress progress-striped active">
  <div class="progress-bar progress-bar-success" role="progressbar"
     aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
     style="width: 1%;">
    <span class="sr-only">40% 完成</span>
  </div>
</div>
<?php
//phpQuery匹配类库
require './phpQuery/phpQuery.php';
//引入配置文件
require './conf/config.php';
//引入类文件
require './spider.class.php';
//临时设置脚本无超时时间
set_time_limit(0);
$area = isset($_POST['area']) && !empty($_POST['area']) ? $_POST['area']:AREA;
$keywords = isset($_POST['keywords']) && !empty($_POST['area']) ? $_POST['keywords']:KEYWORD;
$spider = new Spider($area,$keywords);
// $spider->getInfo();
$spider->getInfoByMulti();
?>
</body>
</html>