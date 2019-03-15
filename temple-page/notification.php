<?php
get_header();

global $wpdb,$user_ID;
if (!$user_ID) {
  echo '<div style="max-width:1100px;margin:0 auto;background: white;padding: 20px;"><p>请先登录</p></div>';
  die();
}
$type = isset($_GET['type']) ? $_GET['type'] : 'tongzhi';
$id = isset($_GET['id']) ? intval($_GET['id']) : '';
$message_unread = gd_check_uread_message($user_ID);
$noti_unread = gd_check_uread_noti($user_ID);
?>
<style>
  .tile-title{
    margin-bottom: 5px;
  }
  @media screen and (max-width: 1376px){}
    .gd-header {
        margin-bottom: 4px;
    }
  }
</style>
<div class="container" style="max-width: 1025px;">
	<div class="columns notitype">
      <ul class="column col-auto">
          <li>
              <a href="<?php echo home_url('/notification?type=tongzhi');?>"<?php if($noti_unread >0){echo 'class="badge" data-badge="'.$noti_unread.'"';} ?>>通知</a>
          </li>
          <li>
              <a href="<?php echo home_url('/notification?type=sixin');?>" <?php if($message_unread >0){echo 'class="badge" data-badge="'.$message_unread.'"';} ?>>私信</a>
          </li>
      </ul>
      <div class="column col col-ml-auto noticontent">
        <ul>
<?php
          
if($type=='sixin'){
  	if($id!==''){
      	$message = gd_get_contact_message($user_ID,$id);
      	foreach($message as $val){
          if((int)$val["user_id"]!==$user_ID){
?>
          <div class="tile">
            <div class="tile-icon">
              <figure class="avatar avatar-lg"><img src="<?php echo gd_get_user_meta_message($val["user_id"],'avatar');?>" alt="Avatar"></figure>
            </div>
            <div class="tile-content">
              <p class="tile-title"><?php echo gd_get_user_message($val["user_id"],'display_name');?><span class="dot"></span><small><?php echo $val["msg_date"]; ?></small></p>
              <p class="tile-subtitle noti-sixin-content"><?php echo $val["msg_value"]; ?></p>
            </div>
          </div>
          <i class="cl"></i>
<?php       
        }else{
?>
          <div class="tile float-right">
            <div class="tile-content">
              <p class="tile-title"><?php echo gd_get_user_message($val["user_id"],'display_name');?><span class="dot"></span><small><?php echo $val["msg_date"]; ?></small></p>
              <p class="tile-subtitle float-right noti-sixin-content"><?php echo $val["msg_value"]; ?></p>
            </div>
             <div class="tile-icon">
              <figure class="avatar avatar-lg"><img src="<?php echo gd_get_user_meta_message($val["user_id"],'avatar');?>" alt="Avatar"></figure>
            </div>
          </div>
          <i class="cl"></i>
<?php     
          }
        }
?>
	<textarea id="textarea" placeholder="私信内容" class="ipt-txt" style="overflow-x: hidden; overflow-wrap: break-word; height: 100px;width:100%;padding: 5px 10px;resize: vertical;"></textarea>
    <?php gd_comment_emoji(); ?>
    <button data-id="<?php echo $id;?>" class="btn float-right" onclick="send_sixin($(this))">发送</button>
<script type='text/javascript' src='//cdn.bootcss.com/autosize.js/4.0.0/autosize.min.js'></script>
<script>
autosize($('textarea'));
function send_sixin(obj){
 	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	$id = obj.attr('data-id');
  	msg = $('.ipt-txt').val();
    var input_data = 'to='+$id+'&value='+msg;
    $.ajax({
        type: "POST",   
        url:  gd_array["site_url"]+"/wp-content/themes/gddd/api/ajax-sixin.php",   
        data: input_data,   
        success: function(msg){   
            $a =JSON.parse(msg);
            if($a['status']==500){
                swal($a['msg'], "","warning");
            }else if($a['status']==200){
                swal($a['msg'], "","success");
              	setTimeout(function(){location.reload();},1500);
            }
        }   
    });
}   
    </script>
<?php
    }else{
    $message = gd_get_user_all_message($user_ID);
	foreach($message as $key => $val){
    $message_unread = gd_check_uread_message($user_ID,$key);
?>
    <div class="tile">
      <div class="tile-icon">
        <figure <?php if($message_unread >0){echo 'class="avatar avatar-lg badge" data-badge="'.$message_unread.'"';}else{ echo 'class="avatar avatar-lg"';} ?>><img src="<?php echo gd_get_user_meta_message($key,'avatar');?>" alt="Avatar"></figure>
      </div>
      <div class="tile-content">
        <p class="tile-title"><?php echo gd_get_user_message($key,'display_name');?><span class="dot"></span><small><?php echo $val[0]["msg_date"]; ?></small></p>
        <p class="tile-subtitle"><?php echo $val[0]["msg_value"]; ?></p>
      </div>
      <div class="tile-action">
        <a class="btn gd-color" href="<?php echo home_url('/notification?type=sixin&id='.$key);?>">查看</a>
      </div>
    </div>
<?php
    }
    }
}
?>

<?php
if($type=='tongzhi'){
  
$message = gd_get_user_all_noti($user_ID);
foreach($message as $val){
?>
          
    <div class="tile noti">
      <div class="tile-icon">
        <figure class="avatar avatar-lg <?php if($val["msg_read"]==0){echo 'badge';} ?>" data-badge=" "><a href="<?php echo gd_get_user_page_url($val["msg_who"]); ?>" target="_blank"><img src="<?php echo gd_get_user_meta_message($val["msg_who"],'avatar');?>" alt="Avatar"></a></figure>
      </div>
      <div class="tile-content">
        <p class="tile-title"><small><?php echo $val["msg_date"]; ?></small></p>
        <p class="tile-subtitle"><?php echo htmlspecialchars_decode($val["msg_text"]); ?></p>
      </div>
    </div>
                
<?php
}

}
?>
        </ul>
      </div>

  </div>
</div>


<?php 
  get_footer();
?>