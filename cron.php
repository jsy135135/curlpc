<?php
// 读取数据,并写入到mongodb中
// cli下设置当前目录
$current_dir = dirname(__FILE__);
chdir($current_dir);
$data = json_decode(file_get_contents('./data/info.json'));
$mongo = new mongoClient();
$con = $mongo->curl->zhilian;
foreach ($data as $key => $value) {
    $con->insert($value);
}