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
// var_dump($jobInfo);die();
dumpValue($jobInfo);
function dumpValue($value){
    foreach ($value as $k => $v) {
        // var_dump($v);die();
        foreach ($v as $kk => $vv) {
            //第一页显示
            if($k == 0){
               $index = $kk+1;
            }else{
               $index = $k*60+($kk+1);
            }

            // var_dump($vv);die();
            echo '<div class="info">';
            echo '<div style="color:red;">第',$index,'条</div>';
            // die();
            foreach ($vv as $kkk => $vvv) {
                echo stripslashes($vvv).'<br />';
            }
            echo '</div>';
        }

    }
}