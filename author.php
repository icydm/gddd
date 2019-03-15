<?php
get_header();
$can = current_user_can('delete_users');
$user_id =  get_query_var('author');
$current_user_id = get_current_user_id();
$self = ($user_id == $current_user_id || $can) ? true : false;
$type = get_query_var('gd_user_page');
$user_data = get_userdata($user_id);

function type_active($type,$type_t){
	if($type==$type_t){
    	echo ' active';
      	return;
    }elseif($type==''&&$type_t=='posts'){
    	echo ' active';
      	return;    	
    }
}

?>
<div class="container content-article">
 
  
  <div class="usercenter">
    <div class="h-inner-back" style="background-image: url('<?php echo gd_get_user_meta_message($user_id,'back');?>');"></div>
    <div class="h-inner">
      <div class="h-user">
          <div class="info-a">
              <div class="h-avatar">
              <img src="<?php echo gd_get_user_meta_message($user_id,'avatar');?>" id="h-avatar" />
              </div>
          </div>
          <div class="info-b">
          	  <div class="info-nalv">
                <span id="h-name"><?php echo gd_get_user_message($user_id,'display_name');?></span><i style="color: #fb7299;">lv<?php echo gd_get_user_meta_message($user_id,'lv');?></i>
              </div>
			  <input style="pointer-events: none;" id="h-sign" type="text" value="<?php echo gd_get_user_meta_message($user_id,'qianming');?>">
          </div>
          <div class="info-c">
              <div id="guanzhu"><?php echo gd_get_user_followbutton($user_id); ?></div>
              <div id="faxinxi"><?php echo gd_get_user_msgbutton($user_id); ?></div>
          </div>
      </div>
    </div>
  </div>
  
  <div class="userchoice columns">
    <div class="column col-9">
      <ul class="tab tab-block">
        <li class="tab-item<?php type_active($type,'posts') ?>">
          <a href="<?php echo gd_get_user_page_url($user_id,'posts'); ?>">投稿</a>
        </li>
        <li class="tab-item<?php type_active($type,'collect') ?>">
          <a href="<?php echo gd_get_user_page_url($user_id,'collect'); ?>">收藏</a>
        </li>
        <li class="tab-item<?php type_active($type,'like') ?>">
          <a href="<?php echo gd_get_user_page_url($user_id,'like'); ?>">喜欢</a>
        </li>
        <li class="tab-item<?php type_active($type,'follow') ?>">
          <a href="<?php echo gd_get_user_page_url($user_id,'follow'); ?>">关注<span class="mx-1"><?php echo count(gd_get_user_meta_message($user_id,'follow'));?></span></a>
        </li>
        <li class="tab-item<?php type_active($type,'fans') ?>">
          <a href="<?php echo gd_get_user_page_url($user_id,'fans'); ?>">粉丝<span class="mx-1"><?php echo count(gd_get_user_meta_message($user_id,'fans'));?></span></a>
        </li>
      </ul>
      <div class="user-center">
<?php 
      if($type){
      	get_template_part( 'formats/user',sanitize_user($type));
      }else{
      	get_template_part( 'formats/user','posts');
      }
?>
		  
        </div>
    </div>
    
    <div class="column col-3">
      <div class="gd_aside" style="margin-top: 10px;">
          <div class="section userinfo">
              <div class="columns row">
                  <div class="item uid d-inline-block column col-4">
                      <span class="UID">UID</span>
                      <span class="text"><?php echo $user_id; ?></span>
                  </div>
                  <div class="item regtime d-inline-block column col-8">
                      <span class="TIME">TIME</span>
                      <span class="text">注册于 <?php echo date("Y/m/d",strtotime(gd_get_user_message($user_id,'user_registered')));?></span>
                  </div>
                
              </div>
              <span class="clearfix"></span>
              <div class="row invi">
                  <div class="item invite">
                    <p>邀请链接</p>
                    <span>https://gd.moyuf.cn/?i=FQQzSXiQk</span>
                  </div>
              </div>
          </div>
		<?php get_sidebar(); ?>
      </div>
    </div>
  </div>


</div>
<?php
get_footer();
?>