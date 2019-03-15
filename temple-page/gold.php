<?php
/**
 *
 *
 *用户财富页面
**/
get_header();
global $user_ID;
if (!$user_ID) {
	echo '<div style="max-width:1100px;margin:0 auto;background: white;padding: 20px;"><p>请先登录</p></div>'; 
}else{
$user_id = $user_ID;
function get_row(){
  if(wp_is_mobile()){
      return '12';
  }else{
      return '6';
  }
}
$row = get_row();
global $wpdb;
global $table_prefix;
$table_name = $table_prefix.'gd_order';
$historydata = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id=$user_id and order_state = 'q'",ARRAY_A);
$historydataa=array();
foreach($historydata as $a){
	$historydataa[] = array(
      'date' => $a['order_date'],
      'name' => gd_get_order_des('order_type',$a['order_type']),
      'credit' => $a['credit_get']-$a['credit_use'],
      'rmb' => $a['rmb_get']-$a['rmb_use'],
      'description' => htmlspecialchars_decode($a['order_value'])
    );
}
?>
<style>
  .pagegold{
  	max-width:1100px;
    margin:0 auto;
    padding: 20px;
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
    line-height: 36px;
    padding: 5px 6px;
  }
  .marb20{
  	margin-bottom: 20px
  }
  .marl10{
  	margin-left: 10px;
  }
  .goldbtn .el-button{
  	margin-bottom: 20px
  }
  [v-cloak] {
    display: none !important;
  }
</style>
<div class="container">
<div class="pagegold" id="gold" v-cloak>


    <h2><i class="fa fa-dashboard" style="color: rgb(64, 158, 255);padding-right: 10px;"></i>财富页面/总览</h2>  
  
      <div class="gold-dh">
        <el-row :gutter="20">
          <el-col :span="<?php echo $row; ?>"><div class="grid-content bg-purple">余额：{{ rmb }}</div></el-col>
          <el-col :span="<?php echo $row; ?>"><div class="grid-content bg-purple">积分：{{ credit }}</div></el-col>
        </el-row>
      </div>
  
  	  <?php if(of_get_option('gdkm_ok','none')=='1'){?>
  	  	<p>卡密购买链接：<a href="<?php echo of_get_option('gdkmlink','none'); ?>" target="_blank">点我</a></p>
  	  <?php } ?>
  
      <div class="marb20 goldbtn">
        <el-button type="primary" disabled>充值</el-button>
        <el-button type="primary" @click="cardFormVisible = true" <?php if(of_get_option('gdkm_ok','none')=='0'){?>disabled<?php } ?>>卡密</el-button>
        <el-button type="info" @click="creditFormVisible = true">购买积分</el-button>
        <el-button type="info" plain disabled>提现</el-button>
      </div>

      <el-dialog title="卡密充值" :visible.sync="cardFormVisible" width="320px">
        <el-form :model="form">
          <el-form-item label="卡号" :label-width="formLabelWidth">
              <el-input v-model="form.name" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="密码" :label-width="formLabelWidth">
              <el-input v-model="form.sec" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cardFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="postcard">确 定</el-button>
        </div>
      </el-dialog>
  
  	  <el-dialog title="购买积分" :visible.sync="creditFormVisible" width="350px">
        <el-form :model="form">
          <el-form-item label="花费金额" label-width="80px">
              <el-input-number v-model="buycredit" :min="1" :max="rmb"></el-input-number>
          </el-form-item>
          <p class="marl10">购买比例：1：{{ creditscale }}</p>
          <p class="marl10">积分增加：{{ creditneedrmb }}</p>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="creditFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="postcredit">确 定</el-button>
        </div>
      </el-dialog>

      <el-table
        :data="tableData3"
        height="450"
        border
        style="width: 100%">
        <el-table-column
          prop="date"
          label="时间">
        </el-table-column>
        <el-table-column
          prop="name"
          label="类型">
        </el-table-column>
        <el-table-column
          prop="credit"
          label="积分">
        </el-table-column>
        <el-table-column
          prop="rmb"
          label="rmb">
        </el-table-column>
        <el-table-column
          prop="description"
          label="描述">
        </el-table-column>
      </el-table>



  <script>
    new Vue({
      el: '#gold',
      data:{
        'rmb':<?php echo gd_get_user_meta_message($user_id,'rmb');?>,
        'credit':<?php echo gd_get_user_meta_message($user_id,'credit');?>,
        'tableData3': <?php print json_encode($historydataa); ?>,
          'form': {
            name: '',
            sec: ''
          },
          'formLabelWidth': '50px',
          'cardFormVisible': false,
          'creditFormVisible':false,
          'buycredit':'',
          'creditscale':'<?php echo of_get_option('creditscale','none');?>',

       },
       methods: {
      	postcard(){
                var url = gd_array.ajaxurl+"gd_km_pay";
                this.$http.post(
                  url,
                  {
                     key:this.form.name,
                     value:this.form.sec
                  },
                  {emulateJSON:true}
                  ).then(function(res){
                  	ress =res.data;
                  	
                    if(ress.status == '200'){
                        this.$notify({
                          title: '充值成功',
                          message: '您的卡密已经充值成功',
                          type: 'success'
                        });
                      	this.rmb = ress.msg;
                      	this.cardFormVisible = false;
                    }
                  	else if(ress.status == '500'){
                        this.$notify.error({
                          title: '充值失败',
                          message: ress.msg
                        });
                    }
                });
    	},
         postcredit(){
         	var url = gd_array.ajaxurl+"gd_credit_pay";
           	this.$http.post(
                  url,
                  {
                     value:this.buycredit
                  },
                  {emulateJSON:true}
            ).then(function(res){
                  	ress =res.data;
                    if(ress.status == '200'){
                        this.$notify({
                          title: '购买成功',
                          message: '您的积分已充值成功',
                          type: 'success'
                        });
                      	this.credit = ress.msg.credit;
                      	this.rmb = ress.msg.rmb;
                      	this.creditFormVisible = false;
                    }
                  	else if(ress.status == '500'){
                        this.$notify.error({
                          title: '充值失败',
                          message: ress.msg
                        });
                    }
                });
         }
    
      },
     computed: {
        creditneedrmb: function () {
          return parseInt(this.buycredit * this.creditscale);
        }
      }
    
    
    })
  </script>


</div>
</div>

<?php
}
get_footer();
