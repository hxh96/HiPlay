/*
*导航点击事件
*/

$(function(){

  $(".leftnav h2").click(function(){
      $(this).next().slideToggle(200);  
      $(this).toggleClass("on"); 
  })

  $(".leftnav ul li a").click(function(){
        $(".leftnav ul li a").removeClass("on");
        $(this).addClass("on");
        var url   = $(this).attr('attr-url');
        var title = $(this).attr('attr-title');
        return dialog.col(title,url);
  })

});

/*
*全选
*/
$("#checkall").click(function(){ 
  if(this.checked == true){
  $("input[name='id']").each(function(){
      this.checked = true;
  });
  }else{
      $("input[name='id']").each(function(){
      this.checked = false;
  }); 
  }
})
/**
 * 提交form表单操作
 */
$(".addFormBtn").click(function(){
    var data = $("#addForm").serializeArray();
    console.log(data)
    postData = {};
    $(data).each(function(i){
       postData[this.name] = this.value;
    });
    console.log(postData);
    // 将获取到的数据post给服务器
    url = SCOPE.redirect_url;
    jump_url = SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.code == 1) {
            //成功

            return dialog.success(result.message,jump_url);
        }else if(result.code == 0) {
            // 失败
            return dialog.error(result.message);
        }
    },"JSON");
});

/**
 * 删除操作JS
 */
$('.tp-delete').on('click',function(){
    var id = $(this).attr('attr-id');
    var name = $(this).attr('attr-name');
    var status = $(this).attr('attr-status');
    var url = SCOPE.redirect_url;
    var jump_url= SCOPE.jump_url;
    if(status){
        data = {'id':id,'status':status};
    }else {
        data = {'id':id};
    }
    layer.open({
        type : 0,
        btn: ['yes', 'no'],
        icon : 3,
        content: "是否确定"+name,
        scrollbar: true,
        yes: function(){
            // 执行相关跳转
            todelete(url, data,jump_url);
        },

    });

});
function todelete(url, data,jump_url) {
    $.post(url,data,function(s){
            if(s.code == 1) {
                return dialog.success(s.message,jump_url);
                // 跳转到相关页面
            }else {
                return dialog.error(s.message);
            }
        }
    ,"JSON");
}

/*
*搜索1
*/
$("#search").click(function(){
  var keyword = $("#keywords").val();
  var url     = SCOPE.jump_url+"?keyword="+keyword;
  window.location.href=url;
})

/*
 *排序
 */
 $("#sorts").click(function(){
    var str = "{";
   $("table tr td .ord").each(function(){
    var ord = $(this).val();
    var id  = $(this).attr('attr-id');
       str += '"'+id+'":'+ord+',';
   });
       str  = str.substring(0,str.length-1);
       str += "}";
    var url = SCOPE.ord_url;
    var jump_url=SCOPE.jump_url;
    var data= {'ord':str};
    if($.trim(str) == '}'){
      return dialog.error('找不到排序对象');
    }else{
      $.post(url,data,function(result){
         if(result.code == 0){
          return dialog.error(result.message);
         }else if(result.code == 1){
          return dialog.success(result.message,jump_url);
         }
      },'JSON');
    }
   
 })
 /*
  *批量删除操作
  */
 $("#del").click(function(){
  var str = "{";
  $("input[name='id']").each(function(i){
    if(this.checked){
      var id = this.value;
      str += '"'+i+'":'+id+','; 
    }
  });
      str  = str.substring(0,str.length-1);
      str += "}";
      var url = SCOPE.del_url;
      var jump_url=SCOPE.jump_url;
      var data= {'del':str};
      if($.trim(str) == '}'){
        return dialog.error('找不到删除对象');
      }else{
        $.post(url,data,function(result){
           if(result.code == 0){
            return dialog.error(result.message);
           }else if(result.code == 1){
            return dialog.success(result.message,jump_url);
           }
        },'JSON');
      }
 });
 
/**
 * 批量审核
 */
$("#look").click(function(){
  var str = "{";
  $("input[name='id']").each(function(i){
    if(this.checked){
      var id = this.value;
      str += '"'+i+'":'+id+','; 
    }
  });
      str  = str.substring(0,str.length-1);
      str += "}";
      var url = SCOPE.power_url;
      var jump_url=SCOPE.jump_url;
      var data= {'look':str};
      if($.trim(str) == '}'){
        return dialog.error('未选择审核对象');
      }else{
        $.post(url,data,function(result){
           if(result.code == 0){
            return dialog.error(result.message);
           }else if(result.code == 1){
            return dialog.success(result.message,jump_url);
           }
        },'JSON');
      }
 });

 /*
  *返回上一级
  */
  $("#reprev").click(function(){
    var url = SCOPE.jump_url;
    window.location.href=url;
  })
