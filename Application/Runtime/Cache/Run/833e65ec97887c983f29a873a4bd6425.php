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
							<li><a href="<?php echo U('System/index');?>" target="content">网站配置</a></li>
							<li><a href="<?php echo U('Admin/index');?>" target="content">管理员管理</a></li>
							<!--<li><a href="系统管理/admin_cardTemplate.html" target="content">名片模板管理</a></li>-->
							<!--<li><a href="系统管理/index_tj.html" target="content">首页推荐导航</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">平台管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('Category/index');?>" target="content">分类列表</a></li>
							<li><a href="<?php echo U('ActivityCate/index');?>" target="content">活动费用类型</a></li>
							<li><a href="<?php echo U('Robot/index');?>" target="content">机器人管理</a></li>
							<!--<li><a href="<?php echo U('UserFeedback/index');?>" target="content">用户反馈</a></li>-->
							<!--<li><a href="信息管理/cate_manage.html" target="content">分类管理</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">用户管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('User/index');?>" target="content">用户列表</a></li>
							<li><a href="<?php echo U('UserFeedback/index');?>" target="content">用户反馈</a></li>
							<!--<li><a href="信息管理/cate_manage.html" target="content">分类管理</a></li>-->
						</ul>
					</div>
				</li>
				<li class="public-ifame-item">
					<a href="javascript:;">内容管理</a>
					<div class="ifame-item-sub">
						<ul>
							<li><a href="<?php echo U('Activity/index');?>" target="content">活动列表</a></li>
							<li><a href="<?php echo U('UserInvited/index');?>" target="content">应邀列表</a></li>
							<li><a href="<?php echo U('UserCollection/index');?>" target="content">收藏列表</a></li>
							<li><a href="<?php echo U('UserCancel/index');?>" target="content">取消列表</a></li>
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

            <h3><?php echo ($menu); ?></h3>

        </div>
        <div class="public-content-cont">
            <table class="public-cont-table">
                <tr>
                    <th >ID</th>
                    <th >用户名</th>
                    <th >手机号</th>
                    <th >反馈内容</th>
                    <th >反馈时间</th>
                    <!--<th >操作</th>-->
                </tr>
                <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo ($v["id"]); ?></td>
                        <td><?php echo ($v["user_name"]); ?></td>
                        <td><?php echo ($v["phone"]); ?></td>
                        <td title="<?php echo ($v["feedback_content"]); ?>" style="color: #6eb6de"><?php echo (substr($v["feedback_content"],0,60)); ?>...</td>
                        <td><?php echo ($v["inputtime"]); ?></td>
                        <!--<td>-->
                            <!--<div class="table-fun">-->
                                <!--&lt;!&ndash;<a href="<?php echo U('Admin/edit',['id'=>$v[id]]);?>">修改</a>&ndash;&gt;-->
                                <!--<?php if($v[status] == 1): ?>-->
                                    <!--<a href="javaScript:;" attr-id="<?php echo ($v["id"]); ?>" attr-name="封停" class="tp-delete">-->
                                        <!--<span style="color: red">封停</span>-->
                                    <!--</a>-->
                                    <!--<?php else: ?>-->
                                    <!--<a href="javaScript:;" attr-id="<?php echo ($v["id"]); ?>" attr-name="恢复" class="tp-delete">-->
                                        <!--<span style="color: deepskyblue">恢复</span>-->
                                    <!--</a>-->
                                <!--<?php endif; ?>-->
                            <!--</div>-->
                        <!--</td>-->
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
            'redirect_url' : "<?php echo U('UserFeedback/delete');?>",
            'jump_url' : "<?php echo U('UserFeedback/index');?>"
        };
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