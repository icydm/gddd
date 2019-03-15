<?php
get_header();

if(wp_is_mobile()){
    $row = '24';
  	$w = '95%';
  	$rowa = '6';
  	$rowb = '18';
}else{
    $row = '6';
  	$w = '500px';
  	$rowa = '2';
  	$rowb = '22';
}
?>
<style>
.blacklistpage{
  max-width:1100px;
  margin:0 auto;
  padding: 20px;
}
.blacklistbg{
	width: 100%;
    height: 200px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
  	margin-top: -40px;
    background: white;
}
  .list{
  	position: relative;
  }
  .poa{
  	position: absolute;
  }
  .ava{
    width: 100%;
    border-radius: 4px;
  }
  .el-row{
      padding: 20px 20px 0 20px;
    border-bottom: 1px solid rgba(80,80,80,.11);
  }
@media screen and (max-width:1376px){
  .blacklistbg{
      height: 100px;
      margin-top: -12px;
  }
}
[v-cloak] {
  display: none !important;
}
</style>
<div class="contain">
  <div class="blacklistbg" style="background-image: url('https://i.loli.net/2019/03/03/5c7b60665fb87.jpg');"></div>
  <div class="blacklistpage" id="blacklist" v-cloak> 
    <h2><i class="fa fa-user-times" style="color: #FF5722; padding-right: 10px;"></i>小黑屋/总览 </h2>  

<?php if(is_super_admin()){ ?>
    <el-button type="primary" class="mr10" @click="cardFormVisible = true">添加</el-button>
    
      <el-dialog title="加入小黑屋" :visible.sync="cardFormVisible" width="<?php echo $w; ?>">
        <p>再次添加相同id用户即为删除</p>
        <el-form :model="form" label-position="left">
          <el-form-item label="用户ID：" label-width="100px">
              <el-input v-model="form.id" autocomplete="off" placeholder=""></el-input>
          </el-form-item>
          <el-form-item label="禁闭时间：" label-width="100px">
            <el-input-number v-model="form.time" :min="1"></el-input-number><span style="margin-left: 10px;">天</span>
          </el-form-item>
          <el-form-item label="禁闭原因：" label-width="100px">
              <el-input type="textarea" v-model="form.rea" autocomplete="off" placeholder=""></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cardFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="add">添 加</el-button>
        </div>
      </el-dialog>
<?php } ?>
    
    <div class="blacklisthum">

    <?php $list = ((array)get_option('blacklist'));
    	foreach($list as $a){
          $time = (int)get_user_meta($a,'heiwu',true)['time']-time();
          $disnone = '';
          if($time<=0){
          	checkheiwu($a);
            $disnone = 'style="display:none;"';
          }
    ?>
              <el-row :gutter="20" <?php echo $disnone;?> >
                <el-col :span="<?php echo $rowa; ?>">
                  <img class="ava" src="<?php echo gd_get_user_meta_message($a,'avatar');?>" id="h-avatar" />
                </el-col>
                <el-col :span="<?php echo $rowb; ?>">
                  <span class="name">昵称：<a style="color: black;" href="<?php echo gd_get_user_page_url($a) ;?>" target="_blank"><?php echo gd_get_user_message($a,'display_name');?></a></span><br>
                  <span class="time">剩余：<i  style="color: red;"><?php echo stamptoday($time);?></i></span><br>
                  <span class="reason">原因：<?php echo get_user_meta($a,'heiwu',true)['reason'];?></span>
                </el-col>
              </el-row>

     <?php
        }
    ?>

    </div>
      
      
</div>
</div>

<script>
    new Vue({
      el: '#blacklist',
      data:{
        cardFormVisible:false,
        form: {
            id: '',
            time: '1',
            rea:'',
        },
      },
      methods:{
		add(){
            var url = gd_array.ajaxurl+"blacklisttoggle";
            this.$http.post(
              url,
              {
                  id:this.form.id,
                  day:this.form.time,
                  reason:this.form.rea
              },
              {emulateJSON:true}
              ).then(function(res){
                ress =res.data;

                if(ress.status == '200'){
                    this.$notify({
                      title: '添加成功',
                      message: ress.msg,
                      type: 'success'
                    });
                    this.cardFormVisible = false;
                }
                else if(ress.status == '500'){
                    this.$notify.error({
                      title: '添加失败',
                      message: ress.msg
                    });
                }
            });
        }
      }
    })
</script>
<?php
get_footer();