<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/javascript" src="/Public/Home/js/jquery-1.12.0.min.js" ></script>

</head>
<body>
<form id="form" onsubmit="return false" method="post" enctype="multipart/form-data">
    <input type="file" name="img" id="img">
    <!--<input type="submit" id="submit" value="提交">-->
</form>
<button id="submit">上传</button>
<br/>
<!--<button id="scanQRCode">扫一扫</button>-->
</body>
<!--<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>-->

<script type="application/javascript">

    $(function(){



        $('#submit').click(function(){
//            var files = document.getElementById("img").files;
//            var a = $('#form').serialize();
//            var b = $('#img').val();
            var user_id = 175;


            var files = document.getElementById("img").files;
            // FormData 对象
            var form = new FormData();// 可以增加表单数据
            form.append("img", files[0]);// 文件对象
            form.append("user_id", user_id);// 文件对象

            $.ajax({
                type:'post',
                url:'https://www.hiwan.net.cn/Api/updateUserHeadImg',
                data:form,
//                dataType:'json',
                processData: false,
                contentType: false,
                success:function(data){
                    console.log(data);
//                    if(data.code == 200){
//                        alert('成功');
//                    }else {
//                        alert('失败');
//                    }
                }
            })
//            console.log(files[0]);
        });

    })


</script>
</html>