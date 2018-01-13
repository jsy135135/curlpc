<?php

/**
* @Author: jsy135135
* @email:732677288@qq.com
* @Date:   2017-10-14 19:15:47
* @Last Modified by:   jsy135135
* @Last Modified time: 2018-01-13 19:04:47
*/

class Spider
{
    
    // 定义一些属性
    public function __construct($area,$keyword)
    {
        $this->area = $area;
        $this->keyword = $keyword;
        $this->page = PAGE;
    }
    
    // 获取免费代理
    public function getProxy()
    {
        // $url = 'http://www.kuaidaili.com/proxylist/1';
        $url = 'http://www.kuaidaili.com/free/intr/1/';
        $content = $this->request($url, false);
        // var_dump($content);die();
        $doc = phpQuery::newDocumentHTML($content);
        $proxyArray = array();
        foreach (pq('tr', $doc) as $trOne) {
            $proxyOne = array();
            foreach (pq('td', $trOne) as $tdOne) {
                $td = pq($tdOne)->text();
                $proxyOne[] = $td;
            }
            $proxyArray[] = $proxyOne;
        }
        return $proxyArray;
        // var_dump($proxyArray);
    }
    
    // 请求方法
    public function request($url, $https = true, $proxy = false, $method = 'get', $data = null)
    {
        // 1.初始化
        $ch = curl_init($url);
        // 2.设置curl
        // 返回数据不输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 开启支持gzip
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        // 设置超时限制
        // curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        // 根据url设置referer
        $host = parse_url($url);
        $host = $host['host'];
        curl_setopt($ch, CURLOPT_REFERER, 'http://' . $host);
        // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3218.0 Safari/537.36');
        // 确认是否开启代理
        if ($proxy === true) {
            // $proxyArray = $this->getProxy();
            // $proxyOne = $proxyArray[rand(1,(count($proxyArray)-1))];
            // file_put_contents('./dbug',json_encode($proxyOne));
            // 开启代理
            // curl_setopt($ch, CURLOPT_PROXY, $proxyOne[0]);
            // curl_setopt($ch, CURLOPT_PROXYPORT,$proxyOne[1]);
            curl_setopt($ch, CURLOPT_PROXY, '61.191.41.130');
            curl_setopt($ch, CURLOPT_PROXYPORT, 80);
        }
        // 支持https
        if ($https === true) {
            //绕过ssl验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        // 支持post
        if ($method === 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        // 3.发送请求
        $content = curl_exec($ch);
        // 4.关闭资源
        curl_close($ch);
        return $content;
    }
    
    // 写入数据库方法
    public function add($data, $mysqli)
    {
        $keys = implode(array_keys($data), ',');
        $keys = 'id,' . $keys;
        $values = implode(array_values($data), '\',\'');
        $values .= '\'';
        $values = 'null,\'' . $values;
        $sql = 'INSERT INTO '.DATABASE.'.'.TABLENAME.' (' . $keys . ') VALUES (' . $values . ');';
        return $mysqli->multi_query($sql);
    }
    
    // 获取lagou页面的所有单页链接
    public function getLagouIndex()
    {
        $url = 'https://www.lagou.com/jobs/positionAjax.json?px=default&city=北京&needAddtionalResult=false&isSchoolJob=0';
        // 1.初始化
        $ch = curl_init($url);
        // 2.设置curl
        // 返回数据不输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 开启支持gzip
        // curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        // 设置超时限制
        // curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        // 根据url设置referer
        $host = parse_url($url);
        $host = $host['host'];
        curl_setopt($ch, CURLOPT_REFERER, 'http://' . $host);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36');
        // 满足https
        // 绕过ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // 满足post
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 3.发送请求
        $content = curl_exec($ch);
        // 4.关闭资源
        curl_close($ch);
        echo $content;
    }
    
    // 通过招聘的列表页获取所有单页链接
    public function getIndex()
    {
        // 定义一个篮子,用来存储所有的招聘链接
        $hrefsArray = array();
        // 遍历,确定取几页
        for ($i = 1; $i <= $this->page; $i++) {
            $url = 'http://sou.zhaopin.com/jobs/searchresult.ashx?jl=' . $this->area . '&kw=' . $this->keyword . '&sm=0&p=' . $i;
            // echo $url;die();
            $content = $this->request($url, false);
            $doc = phpQuery::newDocumentHTML($content);
            // 获取数量并存储
            $obj = pq($doc);
            $total = trim($obj->find('.search_yx_tj')->text());
            file_put_contents('./data/total.txt', $total);
            $hrefs = array();
            foreach (pq('a', $doc) as $one) {
                $href = $one->getAttribute('href');
                $testa = array($href => pq($one)->text());
                // 过滤出具体招聘链接
                if (strpos($href, 'http://jobs.zhaopin.com/') !== false && strpos($href, '.htm') !== false) {
                    $hrefs[] = $href;
            }
        }
        array_push($hrefsArray, $hrefs);
    }
    return $hrefsArray;
}

// zhilian职位页信息规则
public function getOneInfo($array)
{
    $doc = phpQuery::newDocumentHTML($array['content']);
    $obj = pq($doc);
    $title = $obj->find('h1:eq(0)')->text();
    $companyName = $obj->find('h2:eq(0)')->text();
    // 薪资
    $salary = $obj->find('.terminal-ul li:eq(0)')->text();
    // 工作地点
    $location = $obj->find('.terminal-ul li:eq(1)')->text();
    // 发布时间
    $time = $obj->find('.terminal-ul li:eq(2)')->text();
    // 工作性质
    $jobType = $obj->find('.terminal-ul li:eq(3)')->text();
    // 工作经验
    $experience = $obj->find('.terminal-ul li:eq(4)')->text();
    // 最低学历
    $education = $obj->find('.terminal-ul li:eq(5)')->text();
    // 招聘人数
    $nums = $obj->find('.terminal-ul li:eq(6)')->text();
    // 职位类别
    $jobCategory = $obj->find('.terminal-ul li:eq(7)')->text();
    $jobInfo = $obj->find('.tab-inner-cont:eq(0)')->html();
    $jobInfo = pq($jobInfo)->not('b,h2')->html();
    $jobInfo = pq($jobInfo)->not('button')->html();
    $jobInfo = pq($jobInfo)->text();
    $jobInfo = str_replace('SWSStringCutStart', '', $jobInfo);
    $jobInfo = str_replace('SWSStringCutEnd', '', $jobInfo);
    $jobInfo = trim($jobInfo);
    // 工作地址
    $address = $obj->find('h2:eq(1)')->text();
    $address = trim(str_replace('查看职位地图', '', $address));
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
    'jobInfo' => addslashes($jobInfo),
    'address' => $address,
    'url' => $array['url'],
    );
    return $oneInfo;
}
// 访问并获取每一页的招聘信息
public function getInfo()
{
    if (USEMYSQL == 'yes') {
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    }
    // 读取文件，或者直接调用抓取所有的招聘信息链接
    $hrefsArray = $this->getIndex();
    foreach ($hrefsArray as $key => $value) {
        $pageInfo = array();
        foreach ($value as $k => $v) {
            // 访问获取每一页的具体信息
            $content = $this->request($v, false);
            // 调用规则获取数据
            $oneInfo = $this->getOneInfo(array('url' => $v,'content' => $content));
            // 把所有数据组合成为数组
            $pageInfo[] = $oneInfo;
            // 判断当前进度的index
            if ($key == 0) {
                $index = $k + 1;
            } else {
                $index = $key * 60 + ($k + 1);
            }
            // 计算总数量
            $count = count($hrefsArray) * count($value);
            $progress = round(($index / $count) * 100);
            echo "<script type=\"text/javascript\">$('.progress-bar').css('width','$progress%');</script>";
            ob_flush();
            flush();
        }
    }
    $resultLength = file_put_contents('./data/info.json', json_encode($pageInfo));
    if ($resultLength > 0) {
        echo "<script type=\"text/javascript\">self.location=\"http://localhost/curlpc/show.php\"</script>";
        exit();
    }
}

// 批量并发发送请求获取页面数据
public function getInfoByMulti()
{
    $temp = $this->getIndex();
    //返回的是多页的二维数组，转化为一维的
    $hrefsArray = array();
    foreach ($temp as $key => $value) {
        foreach ($value as $k => $v) {
            $hrefsArray[] = $v;
        }
    }
    // var_dump($hrefsArray);die;
    // // 模拟同时发送4条请求
    // $count = count($hrefsArray);
    // $times = $count/4;
    // // 存储所有页面数据的信息数组
    // $allHtmlArray = array();
    // for ($i=0; $i <= $times; $i++) {
    //     $jobsHtmlArray = $this->requestByMulti(array($hrefsArray[$i],$hrefsArray[$i+1],$hrefsArray[$i+2],$hrefsArray[$i+3]));
    //     echo '<pre>';
    //     var_dump($jobsHtmlArray);
    //     echo '<hr>';
    //     foreach ($jobsHtmlArray as $key => $value) {
    //         $allHtmlArray[$key] = $value;
    //     }
    // }
    // echo count($allHtmlArray);die();
    $allHtmlArray = $this->requestByMulti($hrefsArray);
    //清洗获取需要数据
    $pageInfo = array();
    // 入库mysql
    // if (USEMYSQL == 'yes') {
    //     $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
    // }
    foreach ($allHtmlArray as $key => $value) {
        $oneInfo = $this->getOneInfo(array('url' => $key,'content' => $value));
        //     // 把数据添加到mysql
        //     $this->add($oneInfo,$mysqli);
        $pageInfo[] = $oneInfo;
    }
    $resultLength = file_put_contents('./data/info.json', json_encode($pageInfo));
    if ($resultLength > 0) {
        echo "<script type=\"text/javascript\">self.location=\"http://".$_SERVER['HTTP_HOST']."/curlpc/show.php\"</script>";
        exit();
    }
}

// 批量发送请求
public function requestByMulti($urlArray)
{
    $mh = curl_multi_init();
    // 遍历数组，并同时创建生成
    foreach ($urlArray as $key => $url) {
        // 生成单个
        $ch[$key] = curl_init($url);
        curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch[$key], CURLOPT_HEADER,0);
        curl_setopt($ch[$key], CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3218.0 Safari/537.36');
        // 开启支持gzip
        curl_setopt($ch[$key], CURLOPT_ENCODING, 'gzip,deflate');
        // 设置超时限制
        // curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        // 根据url设置referer
        $host = parse_url($url);
        $host = $host['host'];
        curl_setopt($ch[$key], CURLOPT_REFERER, 'http://' . $host);
        curl_multi_add_handle($mh,$ch[$key]);
    }
    // 进行请求批量发送
    do {
        curl_multi_exec($mh,$active);
    } while ($active);
    // $active = null;
    // // 循环执行查看返回值
    // do {
    //     $mrc = curl_multi_exec($mh, $active);
    // }while ($mrc == CURLM_CALL_MULTI_PERFORM);
    // // 执行失败就继续执行
    // while ($active && $mrc == CURLM_OK) {
    //     if (curl_multi_select($mh) != -1) {
    //         do {
    //             $mrc = curl_multi_exec($mh, $active);
    //         } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    //     }
    // }
    // 创建存储返回数据的数组
    $jobsHtmlArray = array();
    // 获取请求返回的数据
    foreach ($urlArray as $key=>$url){
        $html = curl_multi_getcontent($ch[$key]);
        // 解析源文件名称
        // $host = parse_url($url);
        // $filename = $host['path'];
        // 保存源文件到本地
        // file_put_contents("./data".$filename,$html);
        $jobsHtmlArray[$url] = $html;
        curl_multi_remove_handle($mh, $ch[$key]);
        curl_close($ch[$key]);
    }
    curl_multi_close($mh);
    return $jobsHtmlArray;
}
}