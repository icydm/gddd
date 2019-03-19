<?php

function optionsframework_option_name() {
	return 'options-framework-theme';
}

function optionsframework_options() {

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name ;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}


	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/options/images/';

	$options = array();

	$options[] = array(
		'name' => __( '基本设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
  	$options[] = array(
		'name' => __( 'Logo', 'theme-textdomain' ),
		'desc' => __( '网站logo', 'theme-textdomain' ),
		'id' => 'web_logo',
		'std' => 'https://cdn.moyuf.cn/wp-content/uploads/2018/08/logo.png',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '默认的填充图', 'theme-textdomain' ),
		'desc' => __( '无特色图时要占位显示的一张图片', 'theme-textdomain' ),
		'id' => 'default_thumb',
		'std' => 'https://i.loli.net/2018/11/27/5bfd3c18574a2.png',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '接受举报的邮箱', 'theme-textdomain' ),
		'desc' => __( '接受举报的邮箱账号', 'theme-textdomain' ),
		'id' => 'jubaoemail',
		'std' => '1@qq.com',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '官方管理员id', 'theme-textdomain' ),
		'desc' => __( '发送通知等消息时会显示这个', 'theme-textdomain' ),
		'id' => 'guanfangid',
		'std' => '1',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '默认的头像', 'theme-textdomain' ),
		'desc' => __( '无头像时要占位显示的一张图片', 'theme-textdomain' ),
		'id' => 'default_ava',
		'std' => 'https://wx3.sinaimg.cn/mw690/0060lm7Tly1fz0anjw303j30e80e8n5d.jpg',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '默认的用户中心背景图', 'theme-textdomain' ),
		'desc' => __( '用户中心无背景图时要占位显示的一张图片', 'theme-textdomain' ),
		'id' => 'default_user_back',
		'std' => 'https://wx2.sinaimg.cn/large/0060lm7Tly1fykaxzqba3j30zk05k44l.jpg',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '默认的打赏收款码', 'theme-textdomain' ),
		'desc' => __( '无收款码要占位显示的一张图片', 'theme-textdomain' ),
		'id' => 'default_dashang',
		'std' => 'https://i.loli.net/2019/01/07/5c32ff0c645a4.jpg',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '网站关键词', 'theme-textdomain' ),
		'desc' => __( '网站首页的网页关键词，建议使用英文的逗号隔开', 'theme-textdomain' ),
		'id' => 'site_keywords',
		'std' => '123,321',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '网站描述', 'theme-textdomain' ),
		'desc' => __( '网站首页的网页描述，推荐200字以内', 'theme-textdomain' ),
		'id' => 'site_des',
		'std' => '这是一个超级棒的主题',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'Banner', 'theme-textdomain' ),
		'desc' => __( '首页轮播图，每行一个，|隔开。例子：（链接|图片链接） 宽/高=4.3。', 'theme-textdomain' ),
		'id' => 'banner_textarea',
		'std' => 'https://wwww.baidu.com|https://i.loli.net/2018/11/26/5bfbb5a93dce5.jpg',
		'type' => 'textarea'
	);
   $options[] = array(
		'name' => __( '首页专题', 'theme-textdomain' ),
		'desc' => __( '首页专题，每行一个，|隔开。例子：（专题id|图片链接|提示词）。', 'theme-textdomain' ),
		'id' => 'zhuanti_textarea',
		'std' => '1|https://i.loli.net/2018/11/26/5bfbb5d3993b6.png|更新中',
		'type' => 'textarea'
	);
	$options[] = array(
		'name' => __( 'timthumb.php外链白名单设置', 'theme-textdomain' ),
		'desc' => __( 'timthumb.php域名白名单设置，英文 逗号,隔开', 'theme-textdomain' ),
		'id' => 'timthumbwhite',
		'std' => 'ww1.sinaimg.cn,ww2.sinaimg.cn,ww3.sinaimg.cn,ww4.sinaimg.cn,wx1.sinaimg.cn,wx2.sinaimg.cn,wx3.sinaimg.cn,wx4.sinaimg.cn,n.sinaimg.cn,i.loli.net,ws1.sinaimg.cn,ws2.sinaimg.cn,ws3.sinaimg.cn,ws4.sinaimg.cn,thirdqq.qlogo.cn',
      	'type' => 'textarea'
	);
	$options[] = array(
		'name' => __( '首页显示分类', 'theme-textdomain' ),
		'desc' => __( '首页ajax显示的分类', 'theme-textdomain' ),
		'id' => 'index_ajax_category',
		'std' => array(), 
		'type' => 'multicheck',
		'options' =>$options_categories
	);
	$options[] = array(
		'name' => __( '首页不想显示分类', 'theme-textdomain' ),
		'desc' => __( '首页综合内不想分类', 'theme-textdomain' ),
		'id' => 'index_no_category',
		'std' => array(), 
		'type' => 'multicheck',
		'options' =>$options_categories
	);
  	$options[] = array(
		'name' => __( '滚动加载', 'theme-textdomain' ),
		'desc' => __( 'AJAX滚动无限加载开关', 'theme-textdomain' ),
		'id' => 'index_ajax_gd',
		'std' => '',
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => __( '滚动加载临时停止', 'theme-textdomain' ),
		'desc' => __( '无限加载，加载到这一页时暂停一次', 'theme-textdomain' ),
		'id' => 'index_ajax_gd_num',
		'std' => '3',
		'class' => 'mini',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '文章高度', 'theme-textdomain' ),
		'desc' => __( '是否设置文章列表等高', 'theme-textdomain' ),
		'id' => 'index_ajax_height',
		'std' => '',
		'type' => 'checkbox'
	);
  	$options[] = array(
		'name' => __( '默认编辑器', 'theme-textdomain' ),
		'desc' => __( '禁用最新版古腾堡编辑器', 'theme-textdomain' ),
		'id' => 'gtb_jy',
		'std' => '',
		'type' => 'checkbox'
	);
  	$options[] = array(
		'name' => __( '文章页画廊', 'theme-textdomain' ),
		'desc' => __( '重新修改文章页内图片结构，优化seo，并开启画廊', 'theme-textdomain' ),
		'id' => 'single_hl',
		'std' => '',
		'type' => 'checkbox'
	);
		$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress,wplink' )
	);
	$options[] = array(
		'name' => __( '页脚友链', 'theme-textdomain' ),
		'desc' => __( '要显示的页脚友链分类ID', 'theme-textdomain' ),
		'id' => 'foot_link_id',
		'std' => '',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( '页脚信息', 'theme-textdomain' ),
		'desc' => __( '页脚综合信息', 'theme-textdomain' ),
		'id' => 'foot_msg',
		'std' => 'Copyright © 2010-2018 摸鱼否 ICP备123456789号-1',
		'type' => 'textarea'
	);
  	$options[] = array(
		'name' => __( '头部自定义代码', 'theme-textdomain' ),
		'desc' => __( '通常情况下是 meta 标签、link 标签等等，当然，你使用 style 标签也是没问的。通常情况下，这里是用来放置百度站长平台验证代码的', 'theme-textdomain' ),
		'id' => 'head_user_diy',
		'std' => '',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);
  	$options[] = array(
		'name' => __( '页脚自定义代码', 'theme-textdomain' ),
		'desc' => __( '这里的东西会在网站的页脚位置输出，一般是用来放百度统计等代码的', 'theme-textdomain' ),
		'id' => 'foot_user_diy',
		'std' => '',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);
  	$options[] = array(
		'name' => __( '注册设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
  	$options[] = array(
		'name' => __( '允许的注册方式', 'theme-textdomain' ),
		'desc' => __( '允许用户使用什么方式注册，如果允许注册，至少开启一个', 'theme-textdomain' ),
		'id' => 'sign_way',
		'std' => array(), 
		'type' => 'multicheck',
		'options' =>array(
        	'email' => '邮件',
          	'phone' => '手机',
        )
	);
    $options[] = array(
		'name' => __( '用户协议', 'theme-textdomain' ),
		'desc' => __( '注册需要同意的用户协议页面', 'theme-textdomain' ),
		'id' => 'sign_xieyi',
		'std' => 'https://passport.baidu.com/static/passpc-account/html/protocal.html',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '短信验证设置', 'theme-textdomain' ),
		'desc' => __( '阿里短信验证设置 短信服务申请地址：<a target="_blank" href="https://www.aliyun.com/product/sms?userCode=n2akdzbm">短信服务-阿里云</a>', 'theme-textdomain' ),
		'type' => 'info'
	);
  	$options[] = array(
		'name' => __( 'accessKeyId', 'theme-textdomain' ),
		'desc' => __( '阿里云的accessKeyId', 'theme-textdomain' ),
		'id' => 'sms_key',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'accessKeySecret', 'theme-textdomain' ),
		'desc' => __( '阿里云的accessKeySecret', 'theme-textdomain' ),
		'id' => 'sms_sec',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '签名名称', 'theme-textdomain' ),
		'desc' => __( '请填写短信服务->签名管理->签名名称（必须是已通过的签名）', 'theme-textdomain' ),
		'id' => 'sms_qm',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '模版Code', 'theme-textdomain' ),
		'desc' => __( '请填写短信服务->模版管理->模版Code（必须是已通过的模版Code）', 'theme-textdomain' ),
		'id' => 'sms_code',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'QQ登录是否开启', 'theme-textdomain' ),
		'desc' => __( '是否开启QQ登录', 'theme-textdomain' ),
		'id' => 'open_qq_state',
		'std' => '',
		'type' => 'checkbox'
	);
  	$options[] = array(
		'name' => __( 'QQ ID', 'theme-textdomain' ),
		'desc' => __( 'QQ登录申请地址：https://connect.qq.com/', 'theme-textdomain' ),
		'id' => 'open_qq_id',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'QQ KEY', 'theme-textdomain' ),
		'desc' => __( 'QQ的回调地址请填写：https://www.moyuf.cn/open', 'theme-textdomain' ),
		'id' => 'open_qq_key',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '投稿设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	$options[] = array(
		'name' => __( '投稿允许分类', 'theme-textdomain' ),
		'desc' => __( '允许投稿的分类', 'theme-textdomain' ),
		'id' => 'write_category',
		'std' => array(), 
		'type' => 'multicheck',
		'options' =>$options_categories
	);
  
  	$options[] = array(
		'name' => __( '等级设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
    $options[] = array(
        'name' => __( 'lv2', 'theme-textdomain' ),
        'desc' => __( '升级到lv2需要积分', 'theme-textdomain' ),
        'id' => 'gdlva',
        'std' => '200',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( 'lv3', 'theme-textdomain' ),
        'desc' => __( '升级到lv3需要积分', 'theme-textdomain' ),
        'id' => 'gdlvb',
        'std' => '300',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( 'lv4', 'theme-textdomain' ),
        'desc' => __( '升级到lv4需要积分', 'theme-textdomain' ),
        'id' => 'gdlvc',
        'std' => '400',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( 'lv5', 'theme-textdomain' ),
        'desc' => __( '升级到lv5需要积分', 'theme-textdomain' ),
        'id' => 'gdlvd',
        'std' => '500',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( 'lv6', 'theme-textdomain' ),
        'desc' => __( '升级到lv6需要积分', 'theme-textdomain' ),
        'id' => 'gdlve',
        'std' => '600',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( 'lv7', 'theme-textdomain' ),
        'desc' => __( '升级到lv7需要积分', 'theme-textdomain' ),
        'id' => 'gdlvf',
        'std' => '700',
        'type' => 'text'
    );
  	$options[] = array(
		'name' => __( 'VIP权限', 'theme-textdomain' ),
		'desc' => __( 'VIP是否可以查看所有隐藏内容？', 'theme-textdomain' ),
		'id' => 'isvipshow',
		'std' => '',
		'type' => 'checkbox'
	);
     $options[] = array(
        'name' => __( 'vipday', 'theme-textdomain' ),
        'desc' => __( '购买24h会员需要rmb', 'theme-textdomain' ),
        'id' => 'vipday',
        'std' => '1',
        'type' => 'text'
    );
     $options[] = array(
        'name' => __( 'vipweek', 'theme-textdomain' ),
        'desc' => __( '购买一周会员需要rmb', 'theme-textdomain' ),
        'id' => 'vipweek',
        'std' => '7',
        'type' => 'text'
    );
     $options[] = array(
        'name' => __( 'vipmonth', 'theme-textdomain' ),
        'desc' => __( '购买一月会员需要rmb', 'theme-textdomain' ),
        'id' => 'vipmonth',
        'std' => '30',
        'type' => 'text'
    );
     $options[] = array(
        'name' => __( 'vipyear', 'theme-textdomain' ),
        'desc' => __( '购买一年会员需要rmb', 'theme-textdomain' ),
        'id' => 'vipyear',
        'std' => '360',
        'type' => 'text'
    );
     $options[] = array(
        'name' => __( 'vipforever', 'theme-textdomain' ),
        'desc' => __( '购买永久会员需要rmb', 'theme-textdomain' ),
        'id' => 'vipforever',
        'std' => '600',
        'type' => 'text'
    );
  
  	$options[] = array(
		'name' => __( '财富设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
  	$options[] = array(
		'name' => __( '是否启动卡密功能', 'theme-textdomain' ),
		'desc' => __( '是否启动卡密功能', 'theme-textdomain' ),
		'id' => 'gdkm_ok',
		'std' => '0',
		'type' => 'select',
		'class' => 'mini',
		'options' => array(
              '1' => __( '启动', 'theme-textdomain' ),
              '0' => __( '关闭', 'theme-textdomain' )
		)
	);
     $options[] = array(
        'name' => __( '卡密购买页', 'theme-textdomain' ),
        'desc' => __( '购买卡密的链接', 'theme-textdomain' ),
        'id' => 'gdkmlink',
        'std' => 'https://www.baidu.com/',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '积分现金比例', 'theme-textdomain' ),
        'desc' => __( '1rmb等于多少积分', 'theme-textdomain' ),
        'id' => 'creditscale',
        'std' => '10',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '今日最多可以被奖励多少积分', 'theme-textdomain' ),
        'desc' => __( '达到这个数量之后积分就不再奖励了', 'theme-textdomain' ),
        'id' => 'maxjifenliangji',
        'std' => '10',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '签到奖励', 'theme-textdomain' ),
        'desc' => __( '最小值,最大值', 'theme-textdomain' ),
        'id' => 'gdqiandao',
        'std' => '0,10',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '点赞奖励', 'theme-textdomain' ),
        'desc' => __( '文章点赞的奖励', 'theme-textdomain' ),
        'id' => 'postdianzan',
        'std' => '0',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '文章收藏奖励', 'theme-textdomain' ),
        'desc' => __( '文章收藏的奖励', 'theme-textdomain' ),
        'id' => 'postshoucang',
        'std' => '0',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '关注用户奖励', 'theme-textdomain' ),
        'desc' => __( '关注用户的奖励', 'theme-textdomain' ),
        'id' => 'guanzhujiangli',
        'std' => '0',
        'type' => 'text'
    );
    $options[] = array(
        'name' => __( '被关注用户奖励', 'theme-textdomain' ),
        'desc' => __( '被关注用户的奖励', 'theme-textdomain' ),
        'id' => 'beiguanzhujiangli',
        'std' => '0',
        'type' => 'text'
    );
  
  	$options[] = array(
		'name' => __( 'SMTP设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
  	$options[] = array(
		'name' => __( '是否启动smtp', 'theme-textdomain' ),
		'desc' => __( '如果您使用了其他插件实现邮件发送，或者自己的服务器支持，可以关闭此项', 'theme-textdomain' ),
		'id' => 'smtp_ok',
		'std' => '0',
		'type' => 'select',
		'class' => 'mini',
		'options' => array(
              '1' => __( '启动', 'theme-textdomain' ),
              '0' => __( '关闭', 'theme-textdomain' )
		)
	);
  	$options[] = array(
		'name' => __( '发信人昵称', 'theme-textdomain' ),
		'desc' => __( '邮件中显示的发件人姓名，比如：张三', 'theme-textdomain' ),
		'id' => 'smtp_name',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'SMTP服务器地址', 'theme-textdomain' ),
		'desc' => __( '请查询自己邮箱提供商的 SMTP 地址，比如：smtp.163.com', 'theme-textdomain' ),
		'id' => 'smtp_ad',
		'std' => '',
		'type' => 'text'
	);
   	$options[] = array(
		'name' => __( 'SMTP端口', 'theme-textdomain' ),
		'desc' => __( '请查询自己邮箱提供商的端口地址，默认：25', 'theme-textdomain' ),
		'id' => 'smtp_port',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '邮箱账户', 'theme-textdomain' ),
		'desc' => __( '邮件中显示的发件人邮箱地址，比如：xxx@xxx.com', 'theme-textdomain' ),
		'id' => 'smtp_count',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( '邮箱密码', 'theme-textdomain' ),
		'desc' => __( '您的邮箱密码或授权码（根据服务商的不同需求设置）', 'theme-textdomain' ),
		'id' => 'smtp_pass',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'SMTP加密方式', 'theme-textdomain' ),
		'desc' => __( 'tls ssl 或 留空', 'theme-textdomain' ),
		'id' => 'smtp_jiami',
		'std' => '',
		'type' => 'text'
	);
  	$options[] = array(
		'name' => __( 'smtp设置参考', 'theme-textdomain' ),
		'desc' => __( '<a target="_blank" href="https://ww1.sinaimg.cn/large/005LKbBcly1fy3o93gkqwj30lx07374c.jpg">常见邮箱地址、端口参数参考</a>', 'theme-textdomain' ),
		'type' => 'info'
	);
  	$options[] = array(
		'name' => __( 'CMS设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
  	$options[] = array(
		'name' => __( 'cms开启', 'theme-textdomain' ),
		'desc' => __( '是否开启CMS(不建议使用，未完善，相当丑)', 'theme-textdomain' ),
		'id' => 'index_cms_open',
		'std' => '',
		'type' => 'checkbox'
	);
  	$options[] = array(
		'name' => __( 'CMS设置参考', 'theme-textdomain' ),
		'desc' => __( '<p>一行一个<br>分类填1|分类id|标题链接|标题名称<br>1|123|https://xxx.com|哈哈<br>文章填2|文章id,隔开(小于等于6)|标题链接|标题名称<br>2|1,2,3,4|https://xxx.com|嘿嘿<br></p>', 'theme-textdomain' ),
		'type' => 'info'
	);
   $options[] = array(
		'name' => __( 'CMS设置', 'theme-textdomain' ),
		'desc' => __( '', 'theme-textdomain' ),
		'id' => 'index_cms_option',
		'std' => '',
		'type' => 'textarea'
	);
  	$options[] = array(
		'name' => __( '筛选页设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
	$options[] = array(
		'name' => __( '筛选设置', 'theme-textdomain' ),
		'desc' => __( '先去新建个页面，然后选择 ‘筛选页面’设置好链接，发布 ', 'theme-textdomain' ),
		'type' => 'info'
	);
   $options[] = array(
		'name' => __( '筛选条件', 'theme-textdomain' ),
		'desc' => __( '每行一个筛选大类，id之间用,隔开。a加标签id，b加分类id，c加专题id。例子：（地区|a1,b2,c3）。', 'theme-textdomain' ),
		'id' => 'shaixuan_textarea',
		'std' => '地区|a1,b2,b3,a4,c5,a6,c7',
		'type' => 'textarea'
	);
  	$options[] = array(
		'name' => __( '广告设置', 'theme-textdomain' ),
		'type' => 'heading'
	);
  	$options[] = array(
		'name' => __( '文末广告是否开启', 'theme-textdomain' ),
		'desc' => __( '文末广告是否开启', 'theme-textdomain' ),
		'id' => 'postadopen',
		'std' => '',
		'type' => 'checkbox'
	);
  	$options[] = array(
		'name' => __( '文末广告自定义代码', 'theme-textdomain' ),
		'desc' => __( '', 'theme-textdomain' ),
		'id' => 'postad',
		'std' => '',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);
	return $options;
}