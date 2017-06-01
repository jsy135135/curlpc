<meta charset="utf-8">
<?php
//phpQuery匹配类库
require './phpQuery/phpQuery.php';
//引入配置文件
require './conf/config.php';
//引入类文件
require './pc.class.php';
$pc = new Pc();
var_dump($pc->request('http://wx.baibiannijiang.com:8080/testip.php',false,true));
// var_dump($pc->getProxy());