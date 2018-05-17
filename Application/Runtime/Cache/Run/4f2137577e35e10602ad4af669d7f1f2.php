<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo W('Public/web_name');?>|后台管理系统</title>
	<link rel="stylesheet" href="/Public/Run/css/reset.css">
	<link rel="stylesheet" href="/Public/Run/css/public.css">
	<link rel="stylesheet" href="/Public/Run/css/content.css" />
	<link rel="stylesheet" href="/Public/Run/css/laydate1.css" />
	<link rel="stylesheet" href="/Public/Run/css/laydate.css" />
	<script src="/Public/Run/js/jquery-1.12.0.min.js"></script>
	<script src="/Public/Run/js/layer/layer.js"></script>
	<script src="/Public/Run/js/dialog.js"></script>
	<script src="/Public/Run/js/laydate.js"></script>
	
</head>
<body>
<div class="public-header-warrp">
	<div class="public-header">
		<div class="content">
			<div class="public-header-logo"><a href=""><i><img src="<?php echo W('Public/web_logo');?>" width="50" height="50"/> </i><h3><?php echo W('Public/web_name');?></h3></a></div>
			<div class="public-header-admin fr">
				<p class="admin-name">管理员:<?php echo ($username); ?> 您好！</p>
				<div class="public-header-fun fr">
					<!--<a href="" class="public-header-man">管理</a>-->
					<a href="javaScript:;" id="loginOut" class="public-header-loginout">安全退出</a>
				</div>
				<script>
					//退出登录
					$('#loginOut').click(function(){
						var url  = "<?php echo U('Login/loginOut');?>";
						var jump_url = "<?php echo U('Login/index');?>";
						$.ajax({
							type:'get',
							url:url,
							data:'',
							dataType:'json',
							success:function(data){
								if(data.code == 1) {
									return dialog.success(data.message, jump_url);
								}else {
									return dialog.error(data.message);
								}
							}
						});
					});
				</script>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<!-- 内容展示 -->
<div class="public-ifame mt20">
	<div class="content">
	<!-- 内容模块头 -->
		<!--<div class="public-ifame-header">-->
			<!--<ul>-->
				<!--<li class="ifame-item logo">-->
					<!--<div class="item-warrp">-->
						<!--<a href="#"><i>LOGO</i>-->
							<!--<h3 class="logo-title">WorldVentures梦幻之旅</h3>-->
							<!--<p class="logo-des">创建于 2016/4/22 22:22:47</p>-->
						<!--</a>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="ifame-item"><div class="item-warrp"><span>注册时间：2015/11/21 21:14:01<br>VIP有效期：</span></div></li>-->
				<!--<li class="ifame-item"><div class="item-warrp" style="border:none"><span>网站浏览量：15451</span></div></li>-->
				<!--<div class="clearfix"></div>-->
			<!--</ul>-->
		<!--</div>-->
		<div class="clearfix"></div>
		<!-- 左侧导航栏 -->
		<div class="public-ifame-leftnav">
			<div class="public-title-warrp">
				<div class="public-ifame-title ">
					<a href="<?php echo U('Index/index');?>">首页</a>
				</div>
			</div>
			<ul class="left-nav-list">
				<li class="public-ifame-item">
					<a href="javascript:;">系统管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('System/index');?>">网站配置</a></li>
							<li><a href="<?php echo U('Admin/index');?>">管理员管理</a></li>
							<!--<li><a href="系统管理/admin_cardTemplate.html" target="content">名片模板管理</a></li>-->
							<!--<li><a href="系统管理/index_tj.html" target="content">首页推荐导航</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">平台管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('Banner/index');?>">Banner管理</a></li>
							<li><a href="<?php echo U('Category/index');?>">分类列表</a></li>
							<li><a href="<?php echo U('ActivityCate/index');?>">活动费用类型</a></li>
							<li><a href="<?php echo U('Robot/index');?>">机器人管理</a></li>
							<!--<li><a href="<?php echo U('UserFeedback/index');?>" target="content">用户反馈</a></li>-->
							<!--<li><a href="信息管理/cate_manage.html" target="content">分类管理</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">积分管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('IntegralRules/index');?>">积分规则</a></li>
							<li><a href="<?php echo U('UserIntegral/index');?>">积分列表</a></li>
							<li><a href="<?php echo U('WxRedEnvelope/index');?>">微信红包发放记录</a></li>
							<!--<li><a href="<?php echo U('Robot/index');?>">机器人管理</a></li>-->
							<!--<li><a href="<?php echo U('UserFeedback/index');?>" target="content">用户反馈</a></li>-->
							<!--<li><a href="信息管理/cate_manage.html" target="content">分类管理</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">用户管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('User/index');?>">用户列表</a></li>
							<li><a href="<?php echo U('UserPhotoAlbum/index');?>">用户相册</a></li>
							<li><a href="<?php echo U('UserFeedback/index');?>">用户反馈</a></li>
							<!--<li><a href="信息管理/cate_manage.html" target="content">分类管理</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">内容管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('Activity/index');?>">活动列表</a></li>
							<li><a href="<?php echo U('UserInvited/index');?>">应邀列表</a></li>
							<li><a href="<?php echo U('UserCollection/index');?>">收藏列表</a></li>
							<li><a href="<?php echo U('UserCancel/index');?>">取消列表</a></li>
							<!--<li><a href="#" target="content">分类管理</a></li>-->
							<!--<li><a href="旅游管理/listbanner.html" target="content">列表页轮播管理</a></li>-->
							<!--<li><a href="旅游管理/listbanner.html" target="content">分类轮播管理</a></li>-->
							<!--<li><a href="旅游管理/listbanner.html" target="content">旅游预订管理</a></li>-->
						</ul>
					</div>
				</li>
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">帮助管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">信息列表</a>|<a href="#" target="content">添加</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">公告管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">信息列表</a>|<a href="#" target="content">添加</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">会员管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">会员管理</a></li>-->
							<!--<li><a href="#" target="content">会员管理（未认证）</a></li>-->
							<!--<li><a href="#" target="content">过滤过期会员</a></li>-->
							<!--<li><a href="#" target="content">会员认证统计</a></li>-->
							<!--<li><a href="#" target="content">会员提现实名认证</a></li>-->
							<!--<li><a href="/user_vbcz.html" target="content">V币充值</a></li>-->
							<!--<li><a href="#" target="content">V币充值统计</a></li>-->
							<!--<li><a href="#" target="content">续费记录</a></li>-->
							<!--<li><a href="#" target="content">续费统计</a></li>-->
							<!--<li><a href="#" target="content">申请提现</a></li>-->
							<!--<li><a href="#" target="content">推广认证管理</a></li>-->
							<!--<li><a href="#" target="content">收支统计</a></li>-->
							<!--<li><a href="#" target="content">V币转送</a></li>-->
							<!--<li><a href="#" target="content">会员在线留言</a></li>-->
							<!--<li><a href="#" target="content">重置会员代理</a></li>-->
							<!--<li><a href="#" target="content">内容推荐</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">会员广告管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" hhref="" target="content">文本广告列表</a></li>-->
							<!--<li><a href="#" hhref="" target="content">图片广告列表</a></li>-->
							<!--<li><a href="#" hhref="" target="content">链接广告列表</a></li>-->
							<!--<li><a href="#" hhref="" target="content">广告开通记录</a></li>-->
							<!--<li><a href="#" hhref="" target="content">广告统计</a></li>-->
							<!--<li><a href="#" hhref="" target="content">过滤过期广告</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">V商城管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">V商城列表</a>|<a href="#" target="content">添加</a></li>-->
							<!--<li><a href="#" target="content">入驻V商城申请</a></li>-->
							<!--<li><a href="#" target="content">V商城订单</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">品牌管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">品牌列表</a></li>-->
							<!--<li><a href="#" target="content">品牌模板</a></li>-->
							<!--<li><a href="#" target="content">品牌开通申请</a></li>-->
							<!--<li><a href="#" target="content">预订管理</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">轮播图管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">首页轮播图管理</a></li>-->
							<!--<li><a href="#" target="content">登录页轮播图管理</a></li>-->
							<!--<li><a href="#" target="content">会员名片轮播图管理</a></li>-->
							<!--<li><a href="#" target="content">商城轮播图管理</a></li>-->
							<!--<li><a href="#" target="content">分享轮播图管理</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">SEO管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">设置SEO</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
				<!--<li class="public-ifame-item">-->
					<!--<a href="javascript:;">管理员管理</a>-->
					<!--<div class="ifame-item-sub">-->
						<!--<ul>-->
							<!--<li><a href="#" target="content">管理员管理</a>|<a href="#" target="content">添加</a></li>-->
						<!--</ul>-->
					<!--</div>-->
				<!--</li>-->
			</ul>
		</div>
		<!-- 右侧内容展示部分 -->
		<div class="public-ifame-content">
			
    <div class="public-nav">
        您当前的位置：
        <a href=""><?php echo ($module); ?></a>>
        <a href=""><?php echo ($menu); ?></a>
    </div>
    <div class="public-content">
        <div class="public-content-header">
            <div class="public-content-right fr">
                <a href="<?php echo U('Activity/add');?>" style="height: 24px; width: 60px;border: 1px solid #ccc;font-size: 12px;text-align:center">添加信息</a>
            </div>
            <div class="public-content-right fr">
                <a href="javaScript:;" id="auditAll" style="height: 24px; width: 60px;border: 1px solid #ccc;font-size: 12px;text-align:center;color: #00C1B3">批量审核</a>
            </div>
            <h3><?php echo ($menu); ?></h3>
        </div>
        <div class="public-content-cont">
            <table class="public-cont-table">
                <tr>
                    <th ><input type="checkbox" id="checkAll">全选</th>
                    <th >ID</th>
                    <th >活动标题</th>
                    <th >活动状态</th>
                    <th >费用类型</th>
                    <th >活动类型</th>
                    <th >参与人数</th>
                    <th >应邀人数</th>
                    <th >活动说明</th>
                    <th >发起时间</th>
                    <th >发起者</th>
                    <th >手机号</th>
                    <th >状态</th>
                    <th >开始时间</th>
                    <th >活动地址</th>
                    <!--<th >活动经度</th>-->
                    <!--<th >活动纬度</th>-->
                    <th >操作</th>
                </tr>
                <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <th>
                            <?php if($v[status] == 0): ?><input type="checkbox" name='id' value="<?php echo ($v["id"]); ?>">
                                <?php else: endif; ?>
                        </th>
                        <td><?php echo ($v["id"]); ?></td>
                        <td><?php echo ($v["title"]); ?></td>
                        <td><?php echo ($v["end_status"]); ?></td>
                        <td><?php echo ($v["activity_cate_name"]); ?></td>
                        <td><?php echo ($v["category_name"]); ?></td>
                        <td><?php echo ($v["join_number"]); ?></td>
                        <td><?php echo ($v["invited_number"]); ?></td>
                        <td title="<?php echo ($v["content"]); ?>" style="color: lightseagreen"><?php echo (substr($v["content"],0,12)); ?>...</td>
                        <td><?php echo ($v["inputtime"]); ?></td>
                        <td><a href="<?php echo U('User/index',['id'=>$v[initiate_user_id]]);?>" style="color: #2e8ded"><?php echo ($v["initiate_user_name"]); ?></a></td>
                        <td><?php echo ($v["user_phone"]); ?></td>
                        <td>
                            <?php if($v[status] == 0): ?><span style="color: red">未审核</span>
                                <?php elseif($v[status] == 1): ?>
                                <span style="color: #45B549">审核通过</span>
                                <?php else: ?>
                                <span style="color: red">删除</span><?php endif; ?>
                        </td>
                        <td><?php echo ($v["start_time"]); ?></td>
                        <td title="<?php echo ($v["activity_address"]); ?>" style="color: lightseagreen"><?php echo (substr($v["activity_address"],0,12)); ?>...</td>
                        <!--<td><?php echo ($v["activity_longitude"]); ?></td>-->
                        <!--<td><?php echo ($v["activity_latitude"]); ?></td>-->
                        <td>
                            <div class="table-fun">
                                <?php if($v[status] == 0): ?><a href="javaScript:;" attr-id="<?php echo ($v["id"]); ?>" attr-status="1" attr-name="通过" class="tp-delete">
                                            <span style="color: deepskyblue">通过</span>
                                        </a>
                                        <a href="javaScript:;" attr-id="<?php echo ($v["id"]); ?>" attr-status="2" attr-name="删除" class="tp-delete">
                                            <span style="color: red">删除</span>
                                        </a>
                                    <?php elseif($v[status] == 1): ?>
                                        <a href="javaScript:;" attr-id="<?php echo ($v["id"]); ?>" attr-status="2" attr-name="删除" class="tp-delete">
                                            <span style="color: red">删除</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="javaScript:;" attr-id="<?php echo ($v["id"]); ?>" attr-status="1" attr-name="恢复" class="tp-delete">
                                            <span style="color: deepskyblue">恢复</span>
                                        </a><?php endif; ?>
                            </div>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <div class="pagelist">
                <?php echo ($page); ?>
            </div>
        </div>
    </div>

		</div>
	</div>
</div>

    <script src="/Public/Run/js/run.js"></script>

    <script>
        var SCOPE = {
            'redirect_url' : "<?php echo U('Activity/delete');?>",
            'jump_url' : "<?php echo U('Activity/index');?>"
        };

        $(function(){
            //全选
            $('#checkAll').click(function(){
                var isCheck=$("#checkAll").is(':checked');  //获得全选复选框是否选中
                $("input[type='checkbox']").each(function() {
                    this.checked = isCheck;       //循环赋值给每个复选框是否选中
                });
            });

            //批量审核
            $('#auditAll').click(function(){
                var obj = document.getElementsByName("id");
                var check_val = [];
                for(var i=0;i<obj.length;i++) {
                    if (obj[i].checked){
                        check_val.push(obj[i].value);
                    }
                }
                layer.open({
                    content: '确认执行该操作?',
                    icon: 3,
                    btn: ['是', '否'],
                    yes: function () {
                        $.ajax({
                            type:'post',
                            url:"<?php echo U('Activity/auditAll');?>",
                            data:{'ids':check_val},
                            dataType:'json',
                            success:function(data){
                                if(data.code == 1){
                                    var url = "<?php echo U('Activity/index');?>";
                                    return dialog.success(data.message,url);
                                }else {
                                    return dialog.error(data.message);
                                }
                            }
                        });
                    },
                });
            })

        })
    </script>


<script>
$().ready(function(){
	var item = $(".public-ifame-item");

	for(var i=0; i < item.length; i++){
		$(item[i]).on('click',function(){
			$(".ifame-item-sub").hide();
			if($(this.lastElementChild).css('display') == 'block'){
				$(this.lastElementChild).hide()
				$(".ifame-item-sub li").removeClass("active");
			}else{
				$(this.lastElementChild).show();
				$(".ifame-item-sub li").on('click',function(){
					$(".ifame-item-sub li").removeClass("active");
					$(this).addClass("active");
				});
			}
		});
	}
});
</script>
</body>
</html>