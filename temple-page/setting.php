<?php
get_header();

global $user_ID;
if (!$user_ID) {
  echo '<div style="max-width:1100px;margin:0 auto;background: white;padding: 20px;"><p>请先登录</p></div>';
}else{
$user_id = $user_ID;

?>
<style>
#setting .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
#setting .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
#setting .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
#setting .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
  .marb10{
  margin-bottom:10px;
  } 
[v-cloak] {
  display: none !important;
}
.bg{
width:100%;
border: 1px dashed #d9d9d9;
border-radius: 6px;
cursor: pointer;
position: relative;
overflow: hidden;
padding: 1px;
}
.mxw{
max-width: 300px;
}
  .dashang{
    max-width: 200px;  
  
  }
</style>
<div class="container" style="max-width: 1025px;background: white;">
  <div id="setting" style="padding: 20px;" v-cloak >


      
          <el-form label-position="left" label-width="80px" :model="formLabelAlign">
              <el-form-item label="头像">
              <el-upload
                class="avatar-uploader"
                action="<?php echo home_url('/wp-content/themes/gddd/api/ajax-upload.php'); ?>"
                :data="upLoadData"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :before-upload="beforeAvatarUpload">
                <img v-if="imageUrl" :src="imageUrl" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
              </el-upload>
              </el-form-item>

              <el-form-item label="背景">
              <el-upload
                class="bg-uploader"
                action="<?php echo home_url('/wp-content/themes/gddd/api/ajax-upload.php'); ?>"
                :data="upLoadbgData"
                :show-file-list="false"
                :on-success="handlebgSuccess"
                :before-upload="beforebgUpload">
                <img v-if="imagebgUrl" :src="imagebgUrl" class="bg">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
              </el-upload>
              </el-form-item>

              <el-form-item label="打赏">
              <el-upload
                class="dashang-uploader"
                action="<?php echo home_url('/wp-content/themes/gddd/api/ajax-upload.php'); ?>"
                :data="upLoadDatadashang"
                :show-file-list="false"
                :on-success="handledashangSuccess"
                :before-upload="beforedashangUpload">
                <img v-if="imageUrldashang" :src="imageUrldashang" class="dashang">
              </el-upload>
              </el-form-item>
            
              <el-form-item label="昵称">
                <el-input v-model="formLabelAlign.name.text" class="mxw" @focus="formLabelAlign.name.fo=true" maxlength="10"></el-input>
                <el-button type="primary" v-if="formLabelAlign.name.fo" @click="changename">修改</el-button>
              </el-form-item>

              <el-form-item label="签名">
                <el-input v-model="formLabelAlign.qianming.text" class="mxw" maxlength="20" type="textarea" :autosize="{ minRows: 2, maxRows: 4}"  @focus="formLabelAlign.qianming.fo=true"></el-input>
                <el-button type="primary" v-if="formLabelAlign.qianming.fo" @click="changeqianming">修改</el-button>
              </el-form-item>
            
          </el-form>
      

    
    
    
    
  
  </div>
</div>

<script>
    new Vue({
      el: '#setting',
      data:{
            formLabelAlign: {
              name: {
              	text:'<?php echo gd_get_user_dname();?>',
                fo:false
              },
              qianming: {
              	text:'<?php echo gd_get_user_meta_message($user_id,'qianming');?>',
                fo:false
              },
              secret:''
            },
        	imageUrl: '<?php echo gd_get_user_meta_message($user_id,'avatar');?>',
        	upLoadData:{
            	type:'ava',
            },
        	imagebgUrl:'<?php echo gd_get_user_meta_message($user_id,'back');?>',
        	upLoadbgData:{
            	type:'back',
            },
        	imageUrldashang:'<?php echo gd_get_user_meta_message($user_id,'dashang');?>',
            upLoadDatadashang:{
            	type:'dashang',
            },
        	user_id:<?php echo $user_id; ?>
       },
       methods: {
               	handleAvatarSuccess(res, file) {
                  	if(res.status==200){
                    	this.imageUrl = res.msg;
                      	this.$message.success('上传成功');
                    }else{
                    	this.$message.error('上传失败，请联系管理员');
                    }
      			},
                beforeAvatarUpload(file) {
                  const arrType = ['image/jpeg','image/jpg','image/png','image/gif']
                  var isJPG = false
                  for(var i = 0, len = arrType.length; i < len; i++) {
                    if ((file.type === arrType[i])) {
                      isJPG = true;
                    }
                  };　
				  if(!isJPG){
                  	this.$message.error('上传图片只能是 JPG、JPEG、PNG、GIF 格式!');
                  }
                  const isLt5M = file.size / 1024 / 1024 < 5;


                  if (!isLt5M) {
                    this.$message.error('上传图片大小不能超过 5MB!');
                  }
                  return isJPG && isLt5M;
                },
               	handlebgSuccess(res, file) {
                  	if(res.status==200){
                    	this.imagebgUrl = res.msg;
                      	this.$message.success('上传成功');
                    }else{
                    	this.$message.error('上传失败，请联系管理员');
                    }
      			},
                beforebgUpload(file) {
                  const arrType = ['image/jpeg','image/jpg','image/png','image/gif']
                  var isJPG = false
                  for(var i = 0, len = arrType.length; i < len; i++) {
                    if ((file.type === arrType[i])) {
                      isJPG = true;
                    }
                  };　
				  if(!isJPG){
                  	this.$message.error('上传图片只能是 JPG、JPEG、PNG、GIF 格式!');
                  }
                  const isLt5M = file.size / 1024 / 1024 < 5;


                  if (!isLt5M) {
                    this.$message.error('上传图片大小不能超过 5MB!');
                  }
                  return isJPG && isLt5M;
                },
               	handledashangSuccess(res, file) {
                  	if(res.status==200){
                    	this.imageUrldashang = res.msg;
                      	this.$message.success('上传成功');
                    }else{
                    	this.$message.error('上传失败，请联系管理员');
                    }
      			},
                beforedashangUpload(file) {
                  const arrType = ['image/jpeg','image/jpg','image/png','image/gif']
                  var isJPG = false
                  for(var i = 0, len = arrType.length; i < len; i++) {
                    if ((file.type === arrType[i])) {
                      isJPG = true;
                    }
                  };　
				  if(!isJPG){
                  	this.$message.error('上传图片只能是 JPG、JPEG、PNG、GIF 格式!');
                  }
                  const isLt5M = file.size / 1024 / 1024 < 5;


                  if (!isLt5M) {
                    this.$message.error('上传图片大小不能超过 5MB!');
                  }
                  return isJPG && isLt5M;
                },
         		changename(){
                  var url = gd_array["site_url"]+"/wp-content/themes/gddd/api/ajax-info.php";
                  this.$http.post(
                        url,
                        {
                          type:'disname',
                          to:this.user_id,
                          value:this.formLabelAlign.name.text,
                        },
                        {emulateJSON:true}
                  ).then(function(res){
                          ress =res.data;
                          if(ress.status == '200'){
                              this.$message.success('修改成功');
                          }
                          else if(ress.status == '500'){
                              this.$message.error('修改失败：'+ress.msg);
                          }
                      });
                  	this.formLabelAlign.name.fo=false;
                },
         		changeqianming(){
                    var url = gd_array["site_url"]+"/wp-content/themes/gddd/api/ajax-qianming.php";
                    this.$http.post(
                          url,
                          {
                            to:this.user_id,
                            qianming:this.formLabelAlign.qianming.text,
                          },
                          {emulateJSON:true}
                    ).then(function(res){
                            ress =res.data;
                            if(ress.status == '200'){
                                this.$message.success('修改成功');
                            }
                            else if(ress.status == '500'){
                                this.$message.error('修改失败：'+ress.msg);
                            }
                        });
       				this.formLabelAlign.qianming.fo=false;
       			}
      }
})
</script>


<?php 
}
  get_footer();
?>