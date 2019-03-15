<?php
get_header();

if(wp_is_mobile()){
    $row = '24';
  	$w = '95%';
}else{
    $row = '8';
  	$w = '500px';
}
$cats = get_terms( 'link_category', array(
        'hierarchical' => true,
        'hide_empty' => false,
        'orderby' =>  'meta_value_num',
        'order' =>  'ASC',
    ) );
?>
<style>
.linkspage{
  max-width:1100px;
  margin:0 auto;
  padding: 20px;
}
.bottom {
  margin-top: 13px;
  line-height: 12px;
}

.button {
  padding: 0;
  float: right;
}
.linkspage .mr10{
	margin: 10px;
}
.linkspage .pd10{
	padding: 10px;
}
.clearfix:after {
  clear: both
}
.cardbgimg{
	height: 160px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  position: relative;
}
.cardbgimg:before{
  display: block;
  content: "";
  position: absolute;
  left: 0;
  height: 100%;
  width: 100%;
  background: rgba(0, 0, 0, 0.5);  
}
.linkdes{
  white-space: nowrap; 
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
  color: #9e9e9e;
}
.linkname{
	font-size: 1rem;
}
.mart5{
    margin-top: 5px!important;
}
.linkright{
	text-align: right;
}
  .avaimg{
    height: 80px;
    width: 80px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 50%;
    border: 5px solid white;
    margin-top: -40px;
    z-index: 7;
    position: relative;
    margin-left: 10px;
  }
  .qianwang{
	color: white!important;
    float: right;
    top: -35px;
    position: relative;
    right: 15px;
  }
@media screen and (max-width:873px){
  .linkspage .mr10{
      margin: 0px;
      margin-bottom:10px;
  }
}
[v-cloak] {
  display: none !important;
}
</style>
<div class="container">
  
  <div class="linkspage" id="links" v-cloak> 
    <h2 class="mr10"><i class="fa fa-handshake-o" style="color: black; padding-right: 10px;"></i>友情链接 </h2>
	<el-button type="primary" class="mr10" @click="shenqing">申请链接</el-button>
    
      <el-dialog title="申请链接" :visible.sync="cardFormVisible" width="<?php echo $w; ?>">
        <el-form :model="form" label-position="left">
          <el-form-item label="选择分类：" label-width="100px">
            <el-select v-model="cat" placeholder="选择分类">
              <?php 
                  foreach ($cats as $cat) {
                    echo '<el-option key="'.$cat->term_id.'" label="'.$cat->name.'" value="'.$cat->term_id.'"></el-option>';
                  }
              ?>
            </el-select>
          </el-form-item>
          <el-form-item label="网站名称：" label-width="100px">
              <el-input v-model="form.name" autocomplete="off" placeholder="网站的名字"></el-input>
          </el-form-item>
          <el-form-item label="网站网址：" label-width="100px">
              <el-input v-model="form.link" autocomplete="off" placeholder="请带上 http 或 https"></el-input>
          </el-form-item>
          <el-form-item label="描述图片：" label-width="100px">
              <el-input v-model="form.img" autocomplete="off" placeholder="请输入图片链接"></el-input>
          </el-form-item>
          <el-form-item label="网站描述：" label-width="100px">
              <el-input type="textarea" v-model="form.des" autocomplete="off" placeholder="网站的描述性文字"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cardFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="addlink">提 交</el-button>
        </div>
      </el-dialog>
    
<el-row>   
<?php
if(!empty($cats)){
    $i = 0;
    foreach ( $cats as $cat ) {
        $i++;
      	echo '<h4 class="mr10" style="clear: both;">'.$cat->name.'</h4>';
        $bookmarks = get_bookmarks( array('category'=>$cat->term_id,'orderby'=>'link_rating','order'=>'DESC') );
        foreach ($bookmarks as $bookmark) {
            $img = $bookmark->link_image ? $bookmark->link_image : 'https://wx3.sinaimg.cn/large/006yt1Omgy1g0hv73g78ej31380konp0.jpg';
            $description = $bookmark->link_description ? $bookmark->link_description : '这个网站没有任何描述信息';
          	$name = $bookmark->link_name ? $bookmark->link_name : '没名字';
          	$link = $bookmark->link_url ? $bookmark->link_url : '';
          	$owner = $bookmark->link_owner;
          ?>
  
              <el-col :span="<?php echo $row; ?>" class="pd10">
                <el-card :body-style="{ padding: '0px' }" shadow="hover">
                  
<div style="background-image: url('<?php echo $img; ?>');" class="cardbgimg"></div>
<div style="background-image: url('<?php echo gd_get_user_meta_message($owner,'avatar');?>');" class="avaimg"></div>
<a href="<?php echo $link; ?>" target="_blank" class="el-button mart5 el-button--primary qianwang">点击前往</a>
<div style="padding: 5px 0 20px 15px;">
<span class="linkname"><?php echo $name; ?></span><br>
<span class="linkdes"><?php echo $description; ?></span>
</div>
                  
                </el-card>
              </el-col>
        <?php
        }
    }
}
?>

</el-row>

    
</div>
</div>
<link href="https://cdn.bootcss.com/element-ui/2.5.4/theme-chalk/index.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/element-ui/2.5.4/index.js"></script>

<script>
    new Vue({
      el: '#links',
      data:{
      	cardFormVisible:false,
        form: {
            name: '',
            link: '',
            img:'',
            des:''
        },
        cat: ''
      },
      methods:{
        shenqing(){
          	if(gd_array["login_state"]!=="1"){
                swal("请先登录", "","warning");
                return;
            }
      		this.cardFormVisible = true;
     	 },
        addlink(){
            var url = gd_array.ajaxurl+"gd_insert_link";
            this.$http.post(
              url,
              {
                  link_url:this.form.link,
                  link_name:this.form.name,
                  link_image:this.form.img,
                  link_category:this.cat,
                  link_description:this.form.des
              },
              {emulateJSON:true}
              ).then(function(res){
                ress =res.data;

                if(ress.status == '200'){
                    this.$notify({
                      title: '申请成功',
                      message: '您的链接已提交，请耐心等待审核',
                      type: 'success'
                    });
                    this.cardFormVisible = false;
                }
                else if(ress.status == '500'){
                    this.$notify.error({
                      title: '申请失败',
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