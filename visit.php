<?php
$mongo = new MongoClient();
$data = $mongo->curlpc->mobilelogs->find();
echo '访问次数：'.$mongo->curlpc->mobilelogs->count();
$i = 1;
foreach ($data as $key => $value) {
  // $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$value['REMOTE_ADDR'];
  // $area = json_decode(file_get_contents($url));
  // $area = $area->province.'|'.$area->city;
  // $mongo->curlpc->mobilelogs->update(array('_id' => $value['_id']),array('$set' => array('area' => $area)));
  // var_dump($area);die;
  // $area = $area->province.'·'$area->city;
  echo '<p>'.$i.'###'.date('Y-m-d H:i:s',$value['REQUEST_TIME']).'###'.$value['REMOTE_ADDR'].'###'.$value['area'].'</p>';
  $i++;
}