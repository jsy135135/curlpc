<style>
  .info {
    /*float: left;*/
    border: solid;
    color: rebeccapurple;
    border-color: green;
  }
</style>
<?php
//查看抓取到的信息
// var_dump(json_decode(file_get_contents('./data/1/jobInfo1.json')));
$jobInfo = json_decode(file_get_contents('./info.json'),true);
// array_foreach($jobInfo);
dumpValue($jobInfo);
// get_array_elems($jobInfo);
// self
function array_foreach($array){
    if(!is_array($array)){
        return false;
    }
    foreach ($array as $key => $value) {
        if(is_array($value)){
            array_foreach($value);
        }else{
            echo $value.'<br />';
        }
    }
}
function get_array_elems($arrResult, $where="array"){
 while(list($key,$value)=each($arrResult)){
    if (is_array($value)){
      echo '<div class="info">';
      // echo '第'.$key.'条';
      get_array_elems($value, $where."[$key]");
      echo '</div>';
    }
    else {
      for ($i=0; $i<count($value);$i++){
      echo $value.'<br />';
      }
    }
 }
}
function dumpValue($value){
    foreach ($value as $k => $v) {
        // var_dump($v);die();
        foreach ($v as $kk => $vv) {
            // var_dump($vv);die();
            echo '<div class="info">';
            echo '<div style="color:red;">第',$kk+1,'条</div>';
            // die();
            foreach ($vv as $kkk => $vvv) {
                echo $vvv.'<br />';
                // switch ($kkk) {
                //     case 'title':
                //         echo '<div style="color:pink">'.$vvv.'<div><br />';
                //         break;
                //     default:
                //         echo $vvv.'<br />';
                //         break;
                // }
            }
            echo '</div>';
        }

    }
}