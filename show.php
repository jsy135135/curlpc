<?php
$jobInfo = json_decode(file_get_contents('./info.json'),true);
?>
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
    </style>
</head>
<body>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    模态框（Modal）标题
                </h4>
            </div>
            <div class="modal-body">
                在这里添加一些文本
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<?php
foreach ($jobInfo as $k => $v) {
    foreach ($v as $kk => $vv) {
        if($k == 0){
           $index = $kk+1;
        }else{
           $index = $k*60+($kk+1);
        }?>
    <div class="col-sm-4" data-toggle="modal" data-target=".bs-example-modal-lg">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span><?php echo $index.'.'.$vv['companyName']?><button class="mybutton btn btn-primary btn-small" style="float: right;margin-top: -8px">
                    技能要求
                </button></h3>
            </div>
            <div class="panel-body">
                <div style="display: none;" id='aaa'><?php echo $vv['jobInfo'] ?></div>
                <p title="<?php echo $vv['title']?>"><?php echo $vv['title']?></p>
                <p><?php echo $vv['salary']?></p>
                <p><?php echo $vv['location']?></p>
                <p><?php echo $vv['time']?></p>
                <p><?php echo $vv['jobType']?></p>
                <p><?php echo $vv['experience']?></p>
                <p><?php echo $vv['nums']?></p>
                <p><?php echo $vv['jobCategory']?></p>
                <p title="<?php echo trim(str_replace('查看职位地图', '', $vv['address']))?>">地址:<?php echo str_replace('查看职位地图', '', $vv['address'])?></p>
                <p><?php echo $vv['url']?><span class="glyphicon glyphicon-link" aria-hidden="true"></span></p>
            </div>
        </div>
    </div>
<?php
        }
    }
?>
    <script type="text/javascript">
    //随机出现颜色
    var panelColorArray = ['panel-success', 'panel-danger', 'panel-primary'];
    $('.panel').each(function(index, el) {
        $(this).addClass(function() {
            if(panelColorArray.length == 0){
                panelColorArray = ['panel-success', 'panel-danger', 'panel-primary'];
            }
                var rand = Math.floor(Math.random() * panelColorArray.length);
                var panelColor = panelColorArray[rand];
                panelColorArray.splice(rand,1);
                return panelColor;
        })
    });
    $('.mybutton').click(function(event) {
        //修改模态框里的相关具体信息
        var jobInfo = $(this).parent().parent().parent().find('#aaa').text();
        // jobInfo = jobInfo.trim().replace(';','<br />');
        console.log(jobInfo);
        $('#myModalLabel').text('招聘信息');
        $('.modal-body').html(jobInfo);
        $('#myModal').modal();
    });
    </script>
</body>

</html>
