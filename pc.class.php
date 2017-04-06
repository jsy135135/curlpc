<?php
require './phpQuery/phpQuery.php';
class Pc{
    //请求方法
  public function request($url,$https=true,$method='get',$data=null){
    //1.初始化
    $ch = curl_init($url);
    //2.设置curl
    //返回数据不输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.zhaopin.com/');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36');
    //满足https
    if($https === true){
      //绕过ssl验证
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    //满足post
    if($method === 'post'){
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //3.发送请求
    $content = curl_exec($ch);
    //4.关闭资源
    curl_close($ch);
    return $content;
  }
  //获取首页
  public function getIndex($keyword='php',$page=1){
    //定义一个篮子,用来存储所有的招聘链接
    $hrefsArray = array();
    //遍历,确定取几页
    for ($i=1; $i <= $page; $i++) {
      $url = 'http://sou.zhaopin.com/jobs/searchresult.ashx?kw='.$keyword.'&p='.$i;
      $content = $this->request($url,false);
      // var_dump($content);die();
      $doc = phpQuery::newDocumentHTML($content);
      // phpQuery::selectDocument($doc);
      $hrefs = array();
      foreach (pq('a', $doc) as $one) {
        $href = $one->getAttribute('href');
        //过滤出具体招聘链接
        if(strpos($href, 'http://jobs.zhaopin.com/') !== false && strpos($href, '.htm') !== false){
          $hrefs[] = $href;
        }
      }
      array_push($hrefsArray, $hrefs);
    }
    return $hrefsArray;
  }
  //访问并获取每一页的招聘信息
  public function getInfo(){
    //读取文件，或者直接调用抓取所有的招聘信息链接
    $hrefsArray = $this->getIndex();
    $InfoArray = array();
    foreach ($hrefsArray as $key => $value) {
      $pageInfo = array();
      foreach ($value as $k => $v) {
        //访问获取每一页的具体信息
        $content = $this->request($v,false);
        $doc = phpQuery::newDocumentHTML($content);
        $obj = pq($doc);
        $title = $obj->find('h1:eq(0)')->text();
        $companyName = $obj->find('h2:eq(0)')->text();
        //薪资
        $salary = $obj->find('.terminal-ul li:eq(0)')->text();
        //工作地点
        $location = $obj->find('.terminal-ul li:eq(1)')->text();
        //发布时间
        $time = $obj->find('.terminal-ul li:eq(2)')->text();
        //工作性质
        $jobType = $obj->find('.terminal-ul li:eq(3)')->text();
        //工作经验
        $experience = $obj->find('.terminal-ul li:eq(4)')->text();
        //最低学历
        $education = $obj->find('.terminal-ul li:eq(5)')->text();
        //招聘人数
        $nums = $obj->find('.terminal-ul li:eq(6)')->text();
        //职位类别
        $jobCategory = $obj->find('.terminal-ul li:eq(7)')->text();
        //招聘信息
        $jobInfo = $obj->find('.tab-inner-cont:eq(0)')->html();
        $jobInfo = pq($jobInfo)->not('b,h2')->html();
        $jobInfo = pq($jobInfo)->not('button')->html();
        //工作地址
        $address = $obj->find('h2:eq(1)')->text();
        $oneInfo = array(
            'title' => $title,
            'companyName' => $companyName,
            'salary' => $salary,
            'location' => $location,
            'time' => $time,
            'jobType' => $jobType,
            'experience' => $experience,
            'education' => $education,
            'nums' => $nums,
            'jobCategory' => $jobCategory,
            'jobInfo' => $jobInfo,
            'address' => $address,
            'url' => '<a href="'.$v.'" target="_blank"/>点击跳转到网页</a>',
        );
        $pageInfo[] = $oneInfo;
        echo '访问到第'.($k+1).'个<br />';
        ob_flush();
        flush();
      }
    }
    $InfoArray[] = $pageInfo;
    echo file_put_contents('./info.json', json_encode($InfoArray));
  }
}