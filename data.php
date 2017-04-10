<?php
$jobInfo = json_decode(file_get_contents('./data/info.json'),true);
$jobs = array();
foreach ($jobInfo as $key => $value) {
  foreach ($value as $k => $v) {
     $jobs[] = $v;
  }
}
echo json_encode($jobs);