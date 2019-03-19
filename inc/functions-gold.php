<?php

function gdkm_menu_page() {
	$title = esc_html__('卡密设置', '卡密设置');
	add_menu_page($title, $title, 'manage_options', 'gdkm_settings', 'gdkm_display_settings');
}
add_action('admin_menu', 'gdkm_menu_page');


function gdkm_custom_submenu_page(){
add_submenu_page( 'gdkm_settings', '卡密列表', '卡密列表', 'manage_options', 'gdkmm_settings','gdkmm_display_settings' );
}
add_action('admin_menu', 'gdkm_custom_submenu_page');


//生成卡密 邀请码
function gd_create_guid($namespace = null,$inv = false) {
    static $guid = '';
    $uid = uniqid ( "", true );

    $data = $namespace;
    $data .= $_SERVER ['REQUEST_TIME'];     // 请求那一刻的时间戳
    $data .= $_SERVER ['HTTP_USER_AGENT'];  // 获取访问者在用什么操作系统
    $data .= $_SERVER ['SERVER_ADDR'];      // 服务器IP
    $data .= $_SERVER ['SERVER_PORT'];      // 端口号
    $data .= $_SERVER ['REMOTE_ADDR'];      // 远程IP
    $data .= $_SERVER ['REMOTE_PORT'];      // 端口信息

    $hash = strtoupper ( hash ( 'ripemd128', $uid . $guid . md5 ( $data ) ) );

    if($inv){
        $guid = substr ( $hash, 0, 4 ). substr ( $hash, 8, 4 ). substr ( $hash, 12, 4 );
    }else{
        $guid = substr ( $hash, 0, 4 ) . '-' . substr ( $hash, 8, 4 ) . '-' . substr ( $hash, 12, 4 ) . '-' . substr ( $hash, 16, 4 ) . '-' . substr ( $hash, 20, 4 );
    }
    
    return $guid;
}


//卡密生成页面
function gdkm_display_settings(){
    $card = '';
    $rmb = '';
    if( isset($_POST['action']) && sanitize_text_field($_POST['action'])=='update' && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
        $count = $_POST['nub'];
        $rmb = $_POST['rmb'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'gd_card';

        for ($i=0; $i < $count; $i++) {
            $key = gd_create_guid();
            $value = wp_create_nonce(uniqid ( "", true ));

            $wpdb->insert($table_name, array(
                'card_key'=> $key,
                'card_value'=> $value,
                'card_rmb'=> $rmb,
                'card_status'=> 0,
                'card_user'=> 0
            ) );

            $card .= $key.' '.$value.'<br>';
        }
    }

?>
<div class="wrap">
	<h1>主题卡密设置</h1>
    <h2 class="title">卡密生成</h2>
	<form method="post">
		<input type="hidden" name="action" value="update">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
        <p>请填写生成的数量，及面值。在售卡平台注意设置出售价格和在此设置的价格一致，建议不要一次生成太多，以免服务器开销过大。建议一次生成50组以内。</p>
		<?php
            if($card){
                echo '<div style="background-color:#ddd;padding:10px">
                <p>卡密已经生成，当前面值'.$rmb.'元，请直接复制到卡密销售平台进行销售。</p>
                '.$card.'
                </div>';
            }
		?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="nub">生成的数量</label>
                    </th>
                    <td>
                        <input name="nub" type="text" id="nub" value="20" class="regular-text ltr" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false">
                        <p>请填写生成卡密的数量，默认20组</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="rmb">生成卡密的面值</label>
                    </th>
                    <td>
                        <input name="rmb" type="text" id="rmb" value="100" class="regular-text ltr" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false">
                        <p>请填写生成卡密的面值，默认100元</p>
                    </td>
                </tr>
            </tbody>
        </table>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="立刻生成"></p>
	</form>
</div>
<?php
}

//卡密管理
function gdkmm_display_settings(){
    global $wpdb;
    $order = 'ORDER BY id ASC';
    $table_name = $wpdb->prefix . 'gd_card';
    $limit = 10;
    $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
    $offset = ($paged-1)*$limit;
    //删除订单
    if(isset($_GET['del'])){
        $wpdb->delete( $table_name, array( 'id' => $_GET['del'] ) );
    }
    $pages = $wpdb->get_var( "SELECT count(*) FROM $table_name");
    $cards = $wpdb->get_results( "SELECT * FROM $table_name $order LIMIT $offset,$limit" ,ARRAY_A );
    $data = array(
        'pages' => ceil($pages/$limit),
        'paged'=>$paged,
    );
    $request = http_build_query($_REQUEST);
    $request = $request ? '?'.$request : '';
    global $wp;
    $current_url = admin_url( '/admin.php'.$wp->request );

?>
<div class="wrap">
    <style>
        .clearfix:after,.nav-links:after {
          content: ".";
          display: block;
          height: 0;
          clear: both;
          visibility: hidden;
        }
        .clearfix,.nav-links {
          display: inline-block;
        }
        * html .clearfix,* html .nav-links {
          height: 1%;
        }
        .clearfix,.nav-links {
          display: block;
        }
        .pagenav{
            margin-top:20px
        }
        .btn-group{
            float:left
        }
        .btn-pager{
            float:right
        }
        .wp-core-ui .btn-group a{
            margin-right:10px
        }
        .wp-core-ui .btn-pager button{
            margin-left:10px
        }
        .bordernone{
            background: none;
            border:0;
            margin-right:10px
        }
    </style>
    <h2 class="title"><?php _e('卡密管理','ziranzhi2');?></h2>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
        <?php
            if(count($cards) > 0){
        ?>
        <p>&nbsp;</p>
        <table class="wp-list-table widefat fixed striped shop_page_order_option">
            <thead>
                <tr><td>编号</td><td>卡号</td><td>密码</td><td>面值</td><td>是否使用</td><td>使用者</td><td>操作</td></tr>
            </thead>
            <tbody>
                <?php
                    foreach ($cards as $val) {
                        if($val['card_user']!=='0'){
                            $user = gd_get_user_page_link($val['card_user']);
                        }else{
                            $user = '无';
                        }
                        echo '<tr>
                        <td>'.$val['id'].'</td>
                        <td>'.$val['card_key'].'</td>
                        <td>'.$val['card_value'].'</td>
                        <td>'.$val['card_rmb'].'</td>
                        <td>'.($val['card_status'] ? '<span style="color:green">已使用</span>' : '<span style="color:red">未使用</span>').'</td>
                        <td>'.$user.'</td>
                        <td><a href="'.add_query_arg('del',$val['id'],$current_url.$request).'">删除</a></td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
        <?php
            }else{
                echo '<p>暂无卡密</p>';
            }
        ?>
    </form>
    <?php echo '<div class="pagenav clearfix">'.gd_pagenavi(5,$data).'</div>' ?>
</div>
<?php
}

/*卡密充值*/
add_action( 'wp_ajax_gd_km_pay', 'gd_km_pay' );
function gd_km_pay(){
    $key = isset($_POST['key']) ? esc_sql($_POST['key']) : '';
    $value = isset($_POST['value']) ? esc_sql($_POST['value']) : '';

    //核对卡密
    global $wpdb;
    $table_name = $wpdb->prefix . 'gd_card';
    $cards = $wpdb->get_results( "SELECT * FROM $table_name WHERE card_key='$key' AND card_value='$value' AND card_status=0" ,ARRAY_A );
    if(count($cards) == 0){
        print json_encode(array('status'=>500,'msg' =>__('该卡密错误或者已经失效','gd')));
        exit;
    }

    $cards = $cards[0];
    $id = $cards['id'];
    $rmb = $cards['card_rmb'];
    $user_id = get_current_user_id();


    
    $resout = $wpdb->update(
        $table_name,
        array(
            'card_status'=>1,
            'card_user'=>$user_id,
        ),
        array('card_key'=>$key)
    );

    if($resout){
      	$rmb = gd_get_user_meta_message($user_id,'rmb')+$rmb;
        update_user_meta($user_id,'rmb',$rmb);
      	print json_encode(array('status'=>200,'msg'=>$rmb));
        exit;
    }else{
        print json_encode(array('status'=>500,'msg'=>__('充值失败','gd')));
        exit;
    }
}

/*积分购买*/
add_action( 'wp_ajax_gd_credit_pay', 'gd_credit_pay' );
function gd_credit_pay(){
    $value = isset($_POST['value']) ? intval(esc_sql($_POST['value'])) : '';
  	$user_id = get_current_user_id();
  	$rmb = gd_get_user_meta_message($user_id,'rmb');
  	if($value - $rmb > 0){
        print json_encode(array('status'=>500,'msg' =>__('余额不足','gd')));
        exit;
    }
  	$credit = gd_get_user_meta_message($user_id,'credit');
  	$bugcredit = $value * of_get_option('creditscale','none');
  	$credit = $credit + $bugcredit;
  	$rmb = $rmb-$value;
  	update_user_meta($user_id,'credit',$credit);
  	update_user_meta($user_id,'rmb',$rmb);
  
  	updatelv($user_id);
  
    print json_encode(array('status'=>200,'msg'=>array('credit'=>$credit,'rmb'=>$rmb)));
    exit; 	
}

/*vip购买*/
add_action( 'wp_ajax_gd_vip_pay', 'gd_vip_pay' );
function gd_vip_pay(){
    $value = isset($_POST['value']) ? intval(esc_sql($_POST['value'])) : '';
  	$user_id = get_current_user_id();
  	$rmb = gd_get_user_meta_message($user_id,'rmb');

	switch ($value) {
		case '1':
			$money = of_get_option('vipday','none');
			$end = 24 * 60 * 60; 
			break;
		case '2':
			$money = of_get_option('vipweek','none');
			$end = 7 * 24 * 60 * 60;
			break;
		case '3':
			$money = of_get_option('vipmonth','none');
			$end = 30 * 24 * 60 * 60;
			break;
		case '4':
			$money = of_get_option('vipyear','none');
			$end = 365 * 24 * 60 * 60;
			break;
		case '5':
			$money = of_get_option('vipforever','none');
			$end = 0;
			break;
		default:
	        print json_encode(array('status'=>500,'msg' =>__('发生错误','gd')));
	        exit;
	}

  	if($money - $rmb > 0){
        print json_encode(array('status'=>500,'msg' =>__('余额不足','gd')));
        exit;
    }

  	$rmb = $rmb-$money;
  	update_user_meta($user_id,'rmb',$rmb);
  	
  	$now =time();

  	$pretime = get_user_meta($user_id,'vip',true);
  	if($pretime == ''){
      	update_user_meta($user_id,'vip',$now-1);
    	$pretime = 0;
    }
  	
  	if($end !== 0){
  		if($pretime > $now){
    		$end = $pretime + $end;
		}else{
			$end = $now + $end;
		}
  	}
  	update_user_meta($user_id,'vip',$end);
  	//updatelv($user_id);
  	$last = get_vip_time($user_id,true);
  	
    print json_encode(array('status'=>200,'msg'=>array('rmb'=>$rmb,'time'=>$last)));
    exit; 	
}
/*获取vip剩余时间*/
function get_vip_time($user_id,$type = false){
	$vip = get_user_meta($user_id,'vip',true);
  	$now =time();
  	if($vip == ''){
      	update_user_meta($user_id,'vip',$now-1);
    }
	if($vip==0){
		if($type){
			return '永久';
		}
		return 0;
	}else{
		$last = $vip - $now;
		if($last<0){
			if($type){
				return '0';
			}
			return '-1';
		}else{
          	if($type){
				return stamptoday($last);
			}
			return $last;
		}
	}
}

function gd_is_vip($user_id){
	$time = get_vip_time($user_id,false);
  	if($time == '0' || $time !=='-1' ){
    	return true;
    }else{
    	return false;
    }
}



function gd_get_order_des($key,$val){

    if($key == 'order_type'){
        $arr = array(
			'd'=>__('兑换','gd'),
			'w'=>__('文章内购','gd'),
			'ds'=>__('打赏','gd'), 
			'cz'=>__('充值','gd'),	
			'vip'=>__('vip购买','gd'),
			'cg'=>__('积分购买','gd'),
			'v'=>__('视频购买','gd'),
        );
    }

    if($key == 'order_state'){
        $arr = array(
            'w'=>'<span style="color:#333">'.__('等待付款','gd').'</span>',
            'f'=>'<span style="color:red">'.__('已付款未发货','gd').'</span>',
            'c'=>'<span style="color:blue">'.__('已发货','gd').'</span>',
            's'=>'<span style="color:#999">'.__('已删除','gd').'</span>',
            'q'=>'<span style="color:green">'.__('已签收','gd').'</span>',
            't'=>'<span style="color:#333">'.__('已退款','gd').'</span>',
        );
    }

    return isset($arr[$val]) ? $arr[$val] : '';
}

//订单处理
class Gd_order_Message{

	
	private $order_id;//  订单id
    private $user_id;//用户ID
    private $post_id;//用户ID

    private $order_type;//订单类型    d : 兑换 , w : 文章内购 ，ds : 打赏 ，cz : 充值 ，vip : VIP购买 ,cg : 积分购买 v :文章视频
    private $order_state;//订单状态   w : 等待付款 ，f : 已付款未发货 ，c : 已发货 ，s : 已删除 ，q : 已签收 ，t : 已退款
    private $order_count;//订单数量
    private $order_value;//订自定义的 value

    private $rmb_use;
    private $credit_use;
    private $rmb_get;
    private $credit_get;

    private $wpdb;//数据库全局变量
    private $table_name;//表名


    public function __construct(
      	$order_id= '',
    	$user_id = 0,
    	$post_id = 0,
    	$order_type = '',
    	$order_state = '',
    	$rmb_use = 0.00,
    	$credit_use =0,
    	$rmb_get = 0.00,
    	$credit_get = 0,
    	$order_count = 1,
    	$order_value = ''
    ){
		$this->order_id = esc_sql(esc_attr($order_id));
      
        $this->user_id = esc_sql((int)$user_id);
        $this->post_id = esc_sql((int)$post_id);

        $this->order_type = esc_sql(esc_attr($order_type));
        $this->order_state = esc_sql(esc_attr($order_state));
        $this->order_count = esc_sql((int)$order_count);
        $this->order_value = esc_sql(esc_attr($order_value));

        $this->rmb_use = esc_sql((float)$rmb_use);
    	$this->credit_use =esc_sql((int)$credit_use);
    	$this->rmb_get = esc_sql((float)$rmb_get);
    	$this->credit_get = esc_sql((int)$credit_get);

        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'gd_order';

    }

    //添加数据
    public function add_data(){

    	if( $this->wpdb->insert( $this->table_name, array(
			'order_id' => $this->order_id,
	        'user_id' => $this->user_id,
	    	'post_id' => $this->post_id,
	    	'order_type' => $this->order_type,
	    	'order_state' => $this->order_state,
	    	'rmb_use' => $this->rmb_use,
	    	'credit_use' => $this->credit_use,
	    	'rmb_get' => $this->rmb_get,
	    	'credit_get' => $this->credit_get,
	    	'order_count' => $this->order_count,
	    	'order_value' => $this->order_value

        ) ) )
    	return $this->wpdb->insert_id;

    	return false;

    }

    //订单更新
    public function update_orders_data($id,$data){
        if(empty($id) || empty($data)) return false;

		$arg = array( 'order_id' => $id );
            
        $this->wpdb->update(
            $this->table_name,
            $data,
            $arg
        );
        

        return true;
    }

}


/*文章视频购买*/
add_action( 'wp_ajax_videobuy', 'videobuy' );
function videobuy(){
    $post_id = isset($_GET['id']) ? intval(esc_sql($_GET['id'])) : '';
  	$user_id = get_current_user_id();
  	$rmb = gd_get_user_meta_message($user_id,'rmb');
	$credit = gd_get_user_meta_message($user_id,'credit');
	$content = get_post($post_id)->post_content;

	$shortcode_tags = array(
		'gd_video' => 'gd_video_play'
	);
	if ( false === strpos( $content, '[' ) ) {
		return;
	}
	if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
		return;
	}
	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
	$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
	if ( empty( $tagnames ) ) {
		return;
	}
	$ignore_html ='';
	$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );
	$pattern = get_shortcode_regex( $tagnames );
	preg_match( "/$pattern/", $content, $m);
	if ( $m[1] == '[' && $m[6] == ']' ) {
		return;
	}

	$attr = shortcode_parse_atts( $m[3] );
	
  	$credit_need = ($attr['credit'] !== '' ) ? $attr['credit'] : 0;
  	$rmb_need = ($attr['rmb'] !== '') ? $attr['rmb'] : 0;
	
  	if($credit_need !== 0 ){
      if($credit_need - $credit > 0){
          print json_encode(array('status'=>500,'msg' =>__('积分不足','gd')));
          exit;
      }
    }
  
  	if( $rmb_need !== 0 ){
      if($rmb_need - $rmb > 0){
          print json_encode(array('status'=>500,'msg' =>__('余额不足','gd')));
          exit;
      }
    }
  

  	$rmb = $rmb-$rmb_need;
  	update_user_meta($user_id,'rmb',$rmb);

    $credit = $credit-$credit_need;
  	update_user_meta($user_id,'credit',$credit);
  
  	$order_id = gd_create_guid(null,true);
	$order = new Gd_order_Message($order_id,$user_id,$post_id,'v','q',$rmb_need,$credit_need,0,0,1,'购买了视频文章'.get_permalink($post_id));
	$resout = $order->add_data();

  	//gd_send_noti($msg_user,$msg_type,$msg_who,$msg_value,$msg_text)
  	gd_send_noti($user_id,1,of_get_option('guanfangid','none'),$post_id,'您购买了视频文章：<a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a>');
  	
  	if($resout){
        print json_encode(array('status'=>200,'msg' =>__('购买成功','gd')));
        exit; 
    }

    print json_encode(array('status'=>500,'msg' =>__('未知错误，请联系管理员','gd')));
    exit;

}

/*签到*/
add_action( 'wp_ajax_gd_signin', 'gd_signin' );
function gd_signin(){
	$user_id = get_current_user_id();

	global $wpdb;
	$table = $wpdb->prefix . 'gd_notification';
	$qiandao = $wpdb->get_var("SELECT COUNT(*) FROM $table where msg_type=3 and to_days(msg_date) = to_days(now())");
 	if($qiandao!=='0'){
      print json_encode(array('status'=>500,'msg'=>'您似乎今天已经签到过了'));
      exit; 
    }


  	$option = explode(',',of_get_option('gdqiandao','none'));
	$signcredit = rand($option[0], $option[1]);

  	addcredit($user_id,'qiandao',$signcredit);

	print json_encode(array('status'=>200,'msg'=>$signcredit));
	exit; 	
}

/*文章内购*/
add_action( 'wp_ajax_postcontentbuy', 'postcontentbuy' );
function postcontentbuy(){
    $post_id = isset($_GET['id']) ? intval(esc_sql($_GET['id'])) : '';
  	$user_id = get_current_user_id();
  	$rmb = gd_get_user_meta_message($user_id,'rmb');
	$credit = gd_get_user_meta_message($user_id,'credit');
	$content = get_post($post_id)->post_content;

	$shortcode_tags = array(
		'gd_content' => 'gd_content_fun'
	);
	if ( false === strpos( $content, '[' ) ) {
		return;
	}
	if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
		return;
	}
	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
	$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
	if ( empty( $tagnames ) ) {
		return;
	}
	$ignore_html ='';
	$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );
	$pattern = get_shortcode_regex( $tagnames );
	preg_match( "/$pattern/", $content, $m);
	if ( $m[1] == '[' && $m[6] == ']' ) {
		return;
	}

	$attr = shortcode_parse_atts( $m[3] );
	
	$type = ($attr['access'] !== '' ) ? $attr['access'] : 0;
  	$need = ($attr['key'] !== '' ) ? $attr['key'] : 0;
	
	if($type=='2'){
		if($need !== 0 ){
		  if($need - $credit > 0){
			  print json_encode(array('status'=>500,'msg' =>__('积分不足','gd')));
			  exit;
		  }
		}
		$credit = $credit-$need;
		update_user_meta($user_id,'credit',$credit);
		$credit_need = $need;
	}elseif($type=='3'){
		if( $need !== 0 ){
		  if($need - $rmb > 0){
			  print json_encode(array('status'=>500,'msg' =>__('余额不足','gd')));
			  exit;
		  }
		}	
		
		$rmb = $rmb-$need;
		update_user_meta($user_id,'rmb',$rmb);
		$rmb_need = $need;
	}else{
		print json_encode(array('status'=>500,'msg' =>__('未知错误','gd')));
		exit;	
	}
	
  	$order_id = gd_create_guid(null,true);
	$order = new Gd_order_Message($order_id,$user_id,$post_id,'w','q',$rmb_need,$credit_need,0,0,1,'购买了付费内容'.get_permalink($post_id));
	$resout = $order->add_data();

  	//gd_send_noti($msg_user,$msg_type,$msg_who,$msg_value,$msg_text)
  	gd_send_noti($user_id,1,of_get_option('guanfangid','none'),$post_id,'您购买了付费内容：<a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a>');
  	
  	if($resout){
        print json_encode(array('status'=>200,'msg' =>__('购买成功','gd')));
        exit; 
    }

    print json_encode(array('status'=>500,'msg' =>__('未知错误，请联系管理员','gd')));
    exit;

}
?>