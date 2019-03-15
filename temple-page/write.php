<?php   
global $wpdb,$user_ID;
get_header();//载入头部文件   
if ($user_ID) {?>

<link href="https://cdn.bootcss.com/quill/2.0.0-dev.3/quill.snow.css" rel="stylesheet">
<script src="<?php echo home_url('/wp-content/themes/gddd/assets/write/quill.js');?>"></script>
<script src="<?php echo home_url('/wp-content/themes/gddd/assets/write/vue-quill-editor.js');?>"></script>
<style>
  .content-article {
      margin: 0 auto;
  }
  .ql-editor {
      min-height: 200px;
        font-size: .8rem;
  }
  .quill-editor{
      background: white;
  }
  .writetitle .el-input-group__prepend,.writetitle input{
    border-radius: 0;
    border-color: #ccc;
  }
  .writetitle  .el-input-group__prepend {
    background-color: #ffffff;
  }
  .ql-syntax{
    background: black;
    color: white;
    padding: 15px!important;
  }
  .ql-editor.ql-blank::before {
    line-height: 40px;
  }
  .ql-editor ol li {
    list-style: decimal;
    padding-left: 0;
  }
  .el-input-group {  
    margin: 10px 0;
  }
  .el-input-group--append .el-select .el-input.is-focus .el-input__inner, .el-input-group--prepend .el-select .el-input.is-focus .el-input__inner {
      border-color: #409EFF
  }
@media screen and (max-width:873px){
  .content-article{
      padding: 10px;
  }
}
</style>

<?php
  $cats = of_get_option('write_category','none');
?>
<div class="content-article" id="gdwrite">
	<h2><i class="iconfont icon-feather fa-lg" style="color:#fb7299; padding-right: 10px;"></i>投稿</h2>


<div class="writetitle">
  <el-input placeholder="..." v-model="form.title">
    <template slot="prepend">文章标题</template>
  </el-input>
  
  <div class="el-input el-input-group el-input-group--prepend">
    <div class="el-input-group__prepend">投稿分类</div>
      <el-select v-model="form.cat" placeholder="选择分类" slot="prepend">
      <?php 
          foreach ($cats as $cat => $sta) {
            if($sta=='1'){
              echo '<el-option key="'.$cat.'" label="'.get_the_category($cat)[0]->name.'" value="'.$cat.'"></el-option>';
            }
          }
      ?>
      </el-select>
  </div>
  
  <el-input v-model="form.label" placeholder="用、隔开">
  	<template slot="prepend">文章标签</template>
  </el-input>

</div>  

  <quill-editor v-model="form.content"
                ref="quillEditor"
                :options="editorOption"
                @blur="onEditorBlur($event)"
                @focus="onEditorFocus($event)"
                @ready="onEditorReady($event)">
       
    <div id="toolbar" slot="toolbar">
          <span class="ql-formats"><button type="button" class="ql-bold"></button></span>
          <span class="ql-formats"><button type="button" class="ql-italic"></button></span>
          <span class="ql-formats"><button type="button" class="ql-blockquote"></button></span>
          <span class="ql-formats"><button type="button" class="ql-code-block"></button></span>
          <span class="ql-formats"><button type="button" class="ql-header" value="2"></button></span>
          <span class="ql-formats"><button type="button" class="ql-list" value="ordered"></button></span>
          <span class="ql-formats"><button type="button" class="ql-list" value="bullet"></button></span>
          <span class="ql-formats">
            <select class="ql-align">
              <option selected="selected"></option>
              <option value="center"></option>
              <option value="right"></option>
              <option value="justify"></option>
            </select>
          </span>
          <span class="ql-formats"><button type="button" class="ql-clean"></button></span>
          <span class="ql-formats"><button type="button" class="ql-link"></button></span>
          <span class="ql-formats"><button type="button" @click="gdvideo"><i class="fa fa-file-movie-o "></i></button></span>
      	  <span class="ql-formats"><button type="button" @click="gdfile"><i class="fa fa-file-archive-o "></i></button></span>
      </div>
  
  </quill-editor>
  <div><pre><code>
  {{form.content}}
  </code></pre></div>
<script>

Vue.use(VueQuillEditor)
new Vue({
	 el: '#gdwrite',
	 data: {
           editorOption: {
              theme: 'snow',
              modules:{
                toolbar:'#toolbar',
              }
            },
           form: {
             	title:'',
             	content: '',
                cat: '',
                label: '',
            },
	 	},
    components: {
    	LocalQuillEditor: VueQuillEditor.quillEditor
    },
    methods: {
      gdvideo(){
		let quill = this.$refs.quillEditor.quill;
        if(!quill.hasFocus()){
        	quill.focus();
        }
        let length = quill.getSelection().index;
        quill.insertText(length, '[gd_video link="链接" credit="购买积分或留空" rmb="购买价格或留空" ][/gd_video]', );
        quill.setSelection(length + 63)
      },
      gdfile(){
        let quill = this.$refs.quillEditor.quill;
        if(!quill.hasFocus()){
        	quill.focus();
        }
        let length = quill.getSelection().index;
        quill.insertText(length, '[gd_file link="链接" name="这是一部让人惊喜的视频" pass="提取码" code="解压码"]', );
        quill.setSelection(length + 60)
      },
      onEditorBlur(quill) {
        console.log('editor blur!', quill)
      },
      onEditorFocus(quill) {
        console.log('editor focus!', quill)
      },
      onEditorReady(quill) {
        console.log('editor ready!', quill)
      }
    },
    computed: {
      editorA() {
        return this.$refs.quillEditor.quill
      }
    },
    mounted() {
      console.log('this is quill instance object', this.editor)
    }
	})
</script>

  
</div>

  
<?php }else {
   echo '<div class="container content-article" style="background: white;">请先登录</div>';
}   
get_footer();
?>  