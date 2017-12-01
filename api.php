<?php

/**
 * curl多线程demo
 * @Author: jsy135135
 * @email:732677288@qq.com
 * @Date:   2017-10-14 19:15:47
 * @Last Modified by:   jsy135135
 * @Last Modified time: 2017-10-15 23:24:09
 */

$urls = array(
  'http://jobs.zhaopin.com/263929130250001.htm',
  'http://jobs.zhaopin.com/365281238250032.htm'
  );
var_dump($urls);die();
$save_to='./test.txt';   // 把抓取的代码写入该文件
$st = fopen($save_to,"a");

$mh = curl_multi_init();
foreach ($urls as $i => $url) {
  $conn[$i] = curl_init($url);
  curl_setopt($conn[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");
  curl_setopt($conn[$i], CURLOPT_HEADER ,0);
  curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT,60);
  curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,true);  // 设置不将爬取代码写到浏览器，而是转化为字符串
  curl_multi_add_handle ($mh,$conn[$i]);
}

do {
  curl_multi_exec($mh,$active);
} while ($active);

foreach ($urls as $i => $url) {
  $data = curl_multi_getcontent($conn[$i]); // 获得爬取的代码字符串
  fwrite($st,$data);  // 将字符串写入文件。当然，也可以不写入文件，比如存入数据库
} // 获得数据变量，并写入文件

foreach ($urls as $i => $url) {
  curl_multi_remove_handle($mh,$conn[$i]);
  curl_close($conn[$i]);
}

curl_multi_close($mh);
fclose($st);
?>