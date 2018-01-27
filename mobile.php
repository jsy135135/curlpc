<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>招聘信息展示</title>
    <!-- 引入 WeUI -->
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css" />
</head>
<style type="text/css">
body {
    background: #f8f8f8;
}

.page__hd {
    padding: 40px;
}

.page__title {
    text-align: left;
    font-size: 20px;
    font-weight: 400;
}

.page__desc {
    margin-top: 5px;
    color: #888;
    text-align: left;
    font-size: 14px;
}
/*数据太多,滚动显示*/
.weui-dialog__bd{
  overflow: auto;
}
/*弹出框宽度设置*/
.weui-dialog{
  min-width: 95%;
}

</style>
<body>
    <!-- 使用 -->
    <!-- <a href="javascript:;" class="weui-btn weui-btn_primary">绿色按钮</a> -->
    <div class="page__hd">
        <h1 class="page__title">JobInfo</h1>
        <p class="page__desc">工作信息</p>
    </div>
    <?php
        // 获取数据
        $data = json_decode(file_get_contents('./data/info.json'),true);
        // var_dump($data);
        foreach ($data as $key => $value) {
          // var_dump($value);die();
          $newOne = array();
          foreach ($value as $k => $v) {
            // 判断是否进行分割处理
            if(!in_array($k, array('title','companyName','jobInfo','address','url'))){
              // 通过:进行分割并处理，key=>value对应
              $keyValue = explode('：',$v);
              // 前半部分key,后半部分value
              $newOne[$keyValue[0]] = $keyValue[1];
            }else{
              $enToCn = array('title' => '岗位名称','companyName' => '公司名称','jobInfo' => '技能要求','address' => '公司地址','url' => '来源网址');
              $newk = $enToCn[$k];
              $newOne[$newk] = $v;
            }
          }
    ?>
    <div class="page__bd">
        <div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label"></label>
                    <em class="weui-form-preview__value" style="font-size: 1em;"><?php echo $newOne['岗位名称']?></em>
                </div>
            </div>
            <div class="weui-form-preview__bd">
              <?php
                foreach ($newOne as $k => $v) {
                  // 判断一些数据不输出
                  if(!in_array($k,array('岗位名称','最低学历','技能要求'))){
                    if($k == '来源网址'){
                      ?>
                    <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label"><?php echo $k?></label>
                    <a href="<?php echo $v?>" class="weui-btn weui-btn_mini weui-btn_primary">点击查看</a>
                    </div>
                    <?php }else{
              ?>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label"><?php echo $k?></label>
                    <span class="weui-form-preview__value"><?php echo $v?></span>
                </div>
              <?php }}}?>
            </div>
            <div class="weui-form-preview__ft">
                <a class="weui-btn weui-btn_plain-primary" style="width: 60%;" href="javascript:" onclick="JobInfo('<?php $str = str_replace("：",":<br />",str_replace(array("\r\n", "\r", "\n"), "<br />", $newOne['技能要求']));echo $str;?>')">技能要求</a>
            </div>
        </div>
        <br>
        <?php }?>
    </div>
    <!-- <div class="weui-footer weui-footer_fixed-bottom"> -->
    <div class="weui-footer">
        <p class="weui-footer__links">
            <a href="" class="weui-footer__link">市场信息</a>
        </p>
        <p class="weui-footer__text">Copyright &copy; 1991-2018 Heart</p>
    </div>
    <script type="text/javascript" src="https://res.wx.qq.com/open/libs/weuijs/1.1.3/weui.min.js"></script>
    <script src="https://cdn.bootcss.com/zepto/1.0rc1/zepto.min.js"></script>
    <script type="text/javascript">
      function JobInfo(jobinfo){
        weui.alert(jobinfo);
        var dialogheight = (screen.height - 200);
        var dialog = $('.weui-dialog__bd');
        dialog.css('max-height',dialogheight).css('min-height',dialogheight);
      }
    </script>
<?php
    $info = $_SERVER;
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$_SERVER['REMOTE_ADDR'];
    $area = json_decode(file_get_contents($url));
    $area = $area->province.'|'.$area->city;
    $info['area'] = $area;
    $mongo = new MongoClient();
    $mongo->curlpc->mobilelogs->insert($info);
?>
</body>
</html>