<?php
/**
 *Template Name: vip购买页面
 *
 *
**/
get_header();
global $user_ID;
if (!$user_ID) {
	echo '<div style="max-width:1100px;margin:0 auto;background: white;padding: 20px;"><p>请先登录</p></div>';
}else{
$user_id = $user_ID;
function get_row(){
  if(wp_is_mobile()){
      return '24';
  }else{
      return '6';
  }
}
$row = get_row();

?>
<style>
  .pagevip{
  	max-width:1100px;
    margin:0 auto;
    padding: 20px;
  }
  .marb20{
  	margin-bottom: 20px
  }
  .marl10{
  	margin-left: 10px;
  }
    .el-row {
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
  }
  .el-col {
    border-radius: 4px;
  }
  .bg-purple {
    background: white;
  }
    .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .inbl{
    display: inline-block;
    vertical-align: middle;
  }
  .pd15{
  	padding:15px;
  }
@media screen and (max-width:873px){
  .marb10phone{
  	margin-bottom: 10px;
  }
}
  [v-cloak] {
    display: none !important;
  }
</style>
<div class="container">
  <div class="pagevip" id="vippay" v-cloak> 
    
	<h2 class="marb20"><i class="iconfont icon-VIP fa-lg" style="color: #ffba00;padding-right: 10px;"></i>特权购买</h2>  

    <div class="marb20">
    
<el-row :gutter="20">
  <el-col :span="<?php echo $row; ?>"><div class="grid-content bg-purple pd15 marb10phone">
    <i class="fa fa-cloud-download fa-lg inbl" style="color: #409eff;padding-right: 15px;font-size: 2rem;"></i>
    <span class="inbl">专属下载特权<br>全站任意下载</span>
  </div></el-col>
  <el-col :span="<?php echo $row; ?>"><div class="grid-content bg-purple pd15 marb10phone">
    <i class="iconfont icon-VIP fa-lg inbl" style="color: red;padding-right: 15px;font-size: 2rem;"></i>
    <span class="inbl">专属红名标识<br>彰显尊贵身份</span>
  </div></el-col>
  <el-col :span="<?php echo $row; ?>"><div class="grid-content bg-purple pd15 marb10phone">
    <i class="iconfont icon-kefu fa-lg inbl" style="color: #fb7299;padding-right: 15px;font-size: 2rem;"></i>
    <span class="inbl">专属客服热线<br>全天为您服务</span>
  </div></el-col>
  <el-col :span="<?php echo $row; ?>"><div class="grid-content bg-purple pd15 marb10phone">
	更多特权准备中
  </div></el-col>
  <el-col :span="24"><div class="grid-content pd15">
	余额：{{ rmb }} RMB
  </div></el-col>
  <el-col :span="24"><div class="grid-content pd15">
	VIP剩余：{{ viptime }} 
  </div></el-col>
</el-row>
      
      
    </div>
    
    <div>
      <el-select v-model="value2" placeholder="请选择" class="marb10phone">
        <el-option
          v-for="item in options2"
          :key="item.value"
          :label="item.label"
          :value="item.value"
          :disabled="item.disabled">
        </el-option>
      </el-select>
      
      <el-button type="primary" @click="bugvip">立刻购买</el-button>
    </div>
	


    
  </div>

<script>
    new Vue({
      el: '#vippay',
      data:{
            options2: [{
                value: '1',
                label: '1天 / <?php echo of_get_option('vipday','none'); ?> RMB'
              }, {
                value: '2',
                label: '1周 / <?php echo of_get_option('vipweek','none'); ?> RMB',
              }, {
                value: '3',
                label: '1月 / <?php echo of_get_option('vipmonth','none'); ?> RMB'
              }, {
                value: '4',
                label: '1年 / <?php echo of_get_option('vipyear','none'); ?> RMB'
              }, {
                value: '5',
                label: '永久 / <?php echo of_get_option('vipforever','none'); ?> RMB'
              }],
			value2: '1',
        	viptime:'<?php echo get_vip_time($user_id,true);?>',
        	rmb:<?php echo gd_get_user_meta_message($user_id,'rmb'); ?>
       },
       methods: {
       	bugvip(){
                var url = gd_array.ajaxurl+"gd_vip_pay";
                this.$http.post(
                  url,
                  {
                     value:this.value2
                  },
                  {emulateJSON:true}
                  ).then(function(res){
                  	ress =res.data;
                  	
                    if(ress.status == '200'){
                        this.$notify({
                          title: '购买成功',
                          message: 'VIP已成功加时',
                          type: 'success'
                        });
                      	this.rmb = ress.msg.rmb;
                      	this.viptime = ress.msg.time;
                    }
                  	else if(ress.status == '500'){
                        this.$notify.error({
                          title: '购买失败',
                          message: ress.msg
                        });
                    }
                });
        }
      }
})
</script>
</div>
<?php
}
get_footer();
