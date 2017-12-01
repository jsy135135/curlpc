<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>聚合招聘信息页面</title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- 可选的 Bootstrap 主题文件（一般不用引入） -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.js"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style type="text/css">
        p{
            overflow:hidden;
            white-space:nowrap;
            cursor:help;
        }
        .companyName{
            display:inline-block;
            width: 77%;
            overflow:hidden;
            white-space:nowrap;
            cursor:help;
        }
        .jobspoints{
            color: red;
        }
    </style>
</head>
<body>
<div style="font-size: 16px;color: red;margin-left: 20px;">
<?php
echo file_get_contents('./data/total.txt');
?>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h3 class="modal-title" id="myModalLabel">
                编号及其公司名称
                </h3>
            </div>
            <div class="modal-body" style="line-height:2;font-size: 16px;">
                招聘要求信息
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<?php
$jobInfo = json_decode(file_get_contents('./data/info.json'),true);
function assoc_unique(&$arr, $key)
{
    $rAr=array();
    for($i=0;$i<count($arr);$i++)
    {
        if(!isset($rAr[$arr[$i][$key]]))
        {
            $rAr[$arr[$i][$key]]=$arr[$i];
        }
    }
    // var_dump($rAr);die;
    $arr=array_values($rAr);
}
    assoc_unique($jobInfo,'companyName');
foreach ($jobInfo as $key => $value) {
?>
    <div class="col-sm-4" data-toggle="modal" data-target=".bs-example-modal-lg">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span><span class="companyName" title="<?php echo ($key+1).'.'.$value['companyName']?>"><?php echo ($key+1).'.'.$value['companyName']?></span><button class="mybutton btn btn-small" style="float: right;margin: -6px">
                    技能要求
                </button></h3>
            </div>
            <div class="panel-body">
                <div style="display: none;" id='aaa'><?php echo $value['jobInfo'] ?></div>
                <p title="<?php echo $value['title']?>"><?php echo $value['title']?></p>
                <p><?php echo $value['salary']?></p>
                <p><?php echo $value['location']?></p>
                <p><?php echo $value['time']?></p>
                <p><?php echo $value['jobType']?></p>
                <p><?php echo $value['experience']?></p>
                <p><?php echo $value['nums']?></p>
                <p><?php echo $value['jobCategory']?></p>
                <p title="<?php echo $value['address']?>">地址:<?php echo $value['address']?></p>
                <a href="<?php echo $value['url']?>"><span class="glyphicon glyphicon-link" aria-hidden="true"></span>点击查看详情</a>
            </div>
        </div>
    </div>
<?php
    }
?>
    <script type="text/javascript">
    var Colors = [];
    var ColorArray = [];
    for (var i = $('.panel').length - 1; i >= 0; i--) {
        if(ColorArray.length == 0){
            ColorArray = ['success', 'danger', 'primary'];
        }
            var rand = Math.floor(Math.random() * ColorArray.length);
            var Color = ColorArray[rand];
            ColorArray.splice(rand,1);
        Colors.push(Color);
    }
    // 随机出现颜色
    var panelColorArray = [];
    $('.panel').each(function(index, el) {
        $(this).addClass('panel-' + Colors[index]);
    });
    var btnColorArray = [];
    $('.mybutton').each(function(index, el) {
        $(this).addClass('btn-' + Colors[index]);
    });
    $('.mybutton').click(function(event) {
        // 修改模态框里的相关具体信息
        var jobInfo = $(this).parent().parent().parent().find('#aaa').text();
        // var obj = $(this).parent().clone();
        // obj.find(':nth-child(n)').remove();
        // var companyName = obj.text();
        var companyName = $(this).parent().find('.companyName').text();
        jobInfo = jobInfo.replace(/(：|:|；|;|。)/g,'$1<br />').trim();
        jobInfo = jobInfo.replace(/&nbsp;/ig,'');
        // 需要重点显示的字
        var points = ['要求','职位描述','职位要求','优先条件','工作职责','任职要求','岗位职责','memcached','redis','linux','lamp','mongo','mongodb','nosql','微信','shell','前端技术栈者'];
        for (var i = points.length - 1; i >= 0; i--) {
            var reg = new RegExp(points[i],'i');
            var rreg = reg.exec(jobInfo);
            // console.log(rreg);
            // console.log(RegExp);
            jobInfo = jobInfo.replace(reg,'<span class="jobspoints">'+rreg+'</span>');
        }
        $('#myModalLabel').text(companyName);
        $('.modal-body').html(jobInfo);
        $('#myModal').modal();
    });
    </script>
</body>

</html>
