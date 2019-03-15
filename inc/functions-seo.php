<?php
function gd_is_page($all = true,$type = ''){
    global $wp_query;
    $page = $wp_query->get('gd_page');
    if($all){
        return $page;
    }else{
        return $page === $type;
    }
    return false;
}
function gd_is_custom_tax($type){
  if($type == 'collection'){
	$res = is_tax('collection') || gd_is_page(false,'gd-collections');
  }
  return $res;
}
function gd_seo_quota_encode($value) {
    $value = str_replace('"','&#34;',$value);
    $value = str_replace("'",'&#39;',$value);
    return $value;
}

function gd_seo_get_post_meta($post_id,$key) {
    $value = get_post_meta($post_id,$key,true);
    $value = stripslashes($value);
    return $value;
}
function gd_seo_get_term_meta($term_id,$key) {
    $value = get_term_meta($term_id,$key,true);
    $value = stripslashes($value);
    return $value;
}
function gd_get_html_code($str){
    return str_replace('\\','',$str);
}
// 将关键词和描述输出在wp_head区域
add_action('wp_head','gd_seo_head_meta',10);
function gd_seo_head_meta(){
    echo '<meta property="og:locale" content="zh_CN" />'."\n";
    echo gd_seo_head_meta_keywords()."\n";
    echo gd_get_html_code(of_get_option('head_user_diy',''));//head 自定义代码 
}

add_action('wp_footer','gd_seo_footer_meta',10);
function gd_seo_footer_meta(){
    echo gd_get_html_code(of_get_option('foot_user_diy',''));//foot 自定义代码 
}

//seo 标题
add_filter("document_title_parts", "gd_seo_document_title");
function gd_seo_document_title($title){
    global $post;
    if(is_singular()){
        $custom_title = gd_seo_get_post_meta($post->ID, 'gd_seo_title');
        if($custom_title){ $title["title"] = $custom_title; }
    }
    if(is_tax() || is_category() || is_tag()){
        $cat = get_queried_object();
        $cat = $cat->term_id;
        $custom_title = gd_seo_get_term_meta($cat,'seo_title');
        if($custom_title){ $title["title"] = $custom_title; }
    }
    return $title;
}

// 网页关键字与og标签
function gd_seo_head_meta_keywords(){
    if(is_paged())
    {
        return;
    }

    $keywords = '';
    $og = '';
    $title = esc_html(get_bloginfo('name').' &#8211; '.get_bloginfo( 'description', 'display' ));
    $logo = of_get_option('web_logo','');
    if(is_home() || is_front_page()){
        $keywords = of_get_option('site_keywords',''); //
        $og = '
<meta property="og:site_name" content="'.$title.'" />
<meta property="og:type" content="website" />
<meta property="og:title" content="'.$title.'" />
<meta property="og:url" content="'.home_url().'" />
<meta property="og:image" content="'.$logo.'" />';
    }elseif(is_category() || gd_is_custom_tax('collection') || is_tax()){
        $cat = get_queried_object()->term_id;
        $cat_name = single_cat_title('',false);
        $keywords = gd_seo_get_term_meta($cat,'seo_keywords');
        $keywords = $keywords ? $keywords : $cat_name;
        $og = '
<meta property="og:type" content="website" />
<meta property="og:site_name" content="'.$title.'" />
<meta property="og:title" content="'.$cat_name.'" />
<meta property="og:url" content="'.get_category_link( $cat ).'" />
<meta property="og:image" content="'.$logo.'" />';
    }elseif(is_tag()){
        global $wp_query;
        $tag_name = single_cat_title('',false);
        $tag_id = $wp_query->queried_object->term_id;
        $keywords = gd_seo_get_term_meta($tag_id,'seo_keywords');
        $keywords = $keywords ? $keywords : $tag_name;
        $og = '
<meta property="og:type" content="website" />
<meta property="og:site_name" content="'.$title.'" />
<meta property="og:title" content="'.$tag_name.'" />
<meta property="og:url" content="'.get_tag_link( $tag_id ).'" />
<meta property="og:image" content="'.$logo.'" />';
    }elseif(is_single()){
        global $post;
        $post_id = $post->ID;
        $post_cats = strip_tags(get_the_category_list( ',', 'multiple', $post_id ));
        $post_tags = strip_tags(get_the_tag_list('',',',''));
        $post_meta = gd_seo_get_post_meta($post_id, 'gd_seo_keywords');
        $keywords = $post_meta ? $post_meta : $post_tags.($post_tags ? ',' : '').$post_cats;
        $author_id = get_post_field( 'post_author', $post_id );
        $thumb = get_the_post_thumbnail_url($post_id) ? get_the_post_thumbnail_url($post_id) : gd_get_post_thumb($post_id);
        $og = '
<meta property="og:site_name" content="'.$title.'" />
<meta property="og:type" content="article" />
<meta property="og:url" content="'.get_permalink($post_id).'" />
<meta property="og:title" content="'.get_the_title($post_id).'" />
<meta property="og:updated_time" content="'.get_the_modified_date('c',$post_id).'" />
<meta property="og:image" content="'.$thumb.'" />
<meta property="article:published_time" content="'.get_the_time('c',$post_id).'" />
<meta property="article:modified_time" content="'.get_the_modified_date('c',$post_id).'" />
<meta property="article:author" content="'.gd_get_user_page_url($author_id).'" />
<meta property="article:publisher" content="'.home_url().'" />';
    }elseif(is_singular()){
        global $post;
        $post_id = $post->ID;
        $keywords = gd_seo_get_post_meta($post->ID, 'gd_seo_keywords');
        $author_id = get_post_field( 'post_author', $post_id );
        $og = '
<meta property="og:site_name" content="'.$title.'" />
<meta property="og:type" content="article" />
<meta property="og:url" content="'.get_permalink($post_id).'" />
<meta property="og:title" content="'.get_the_title($post_id).'" />
<meta property="og:updated_time" content="'.get_the_modified_date('c',$post_id).'" />
<meta property="og:image" content="'.(get_the_post_thumbnail_url($post_id) ? get_the_post_thumbnail_url($post_id) : gd_get_post_thumb($post_id)).'" />
<meta property="article:published_time" content="'.get_the_time('c',$post_id).'" />
<meta property="article:modified_time" content="'.get_the_modified_date('c',$post_id).'" />
<meta property="article:author" content="'.gd_get_user_page_url($author_id).'" />
<meta property="article:publisher" content="'.home_url().'" />';
    }

    $keywords = trim(strip_tags($keywords));
    $og = trim($og);
    if($keywords)
    {
        $keywords = '<meta name="keywords" content="'.$keywords.'" />'."\n";
    }
    return $keywords.gd_seo_head_meta_description().$og;
}
// 网页描述
function gd_seo_head_meta_description($weixin = false){
    if(is_paged())
    {
        return;
    }
    $description = '';
    $og = '';
    if(is_home() || is_front_page()){
        $description =  of_get_option('site_des','');
    }elseif(is_category() || gd_is_custom_tax('collection') || is_tax()){
        $description = category_description();
    }elseif(is_tag()){
        $description = tag_description();
    }elseif(is_single() || is_singular()){
        global $post;
        $description = gd_get_post_des($post->ID);
    }

    $description = strip_tags($description);
    $description = trim($description);

    if($weixin) return $description;
    
    if($description)
    {
        return '<meta name="description" content="'.htmlspecialchars($description).'" />'."\n".'<meta property="og:description" content="'.htmlspecialchars($description).'" />'."\n";
    }
    return '';
}

// 添加后台界面meta_box
add_action('add_meta_boxes','gd_seo_post_metas_box_init');
function gd_seo_post_metas_box_init(){
    add_meta_box('seo-metas','SEO','gd_seo_post_metas_box',array('post','page','collection'),'side','high');
}
function gd_seo_post_metas_box($post){
    if($post->ID) {
        $post_id = $post->ID;
        $seo_title = gd_seo_get_post_meta($post_id,'gd_seo_title');
        $seo_keywords = gd_seo_get_post_meta($post_id,'gd_seo_keywords');
        $seo_description = gd_seo_get_post_meta($post_id,'gd_seo_description');
    }
    else {
        $seo_title = '';
        $seo_keywords = '';
        $seo_description = '';
    }
    ?>
    <div class="seo-metas">
        <p>SEO标题：<input type="text" class="regular-text" name="seo_title" value="<?php echo gd_seo_quota_encode($seo_title); ?>" style="max-width: 98%;"></p>
        <p>SEO关键词：<input type="text" class="regular-text" name="seo_keywords" value="<?php echo gd_seo_quota_encode($seo_keywords); ?>" style="max-width: 98%;"></p>
        <p>SEO描述：<br><textarea class="large-text" name="seo_description"><?php echo $seo_description; ?></textarea></p>
        <p>若不指定，则自动使用文章标签作为关键词，文章前20个字符作为描述，若要取消，请直接设置成空格然后保存。</p>
    </div>
<?php
}

// 保存填写的meta信息
add_action('save_post','gd_seo_post_metas_box_save');
function gd_seo_post_metas_box_save($post_id){
    if(!isset($_POST['seo_title']) || !isset($_POST['seo_keywords']) || !isset($_POST['seo_description'])) return $post_id;
    $seo_title = strip_tags($_POST['seo_title']);
    $seo_keywords = strip_tags($_POST['seo_keywords']);
    $seo_description = stripslashes(strip_tags($_POST['seo_description']));
    if($seo_title == ' '){
        delete_post_meta($post_id,'gd_seo_title');
    }elseif($seo_title){
        update_post_meta($post_id,'gd_seo_title',$seo_title);
    }
    if($seo_keywords == ' '){
        delete_post_meta($post_id,'gd_seo_keywords');
    }elseif($seo_keywords){
        update_post_meta($post_id,'gd_seo_keywords',$seo_keywords);
    }

    if($seo_description == ' '){
        delete_post_meta($post_id,'gd_seo_description');
    }elseif($seo_description){
        update_post_meta($post_id,'gd_seo_description',$seo_description);
    }
}

function gd_seo_extra_term_fields($term){
    $metas = array(
        array('meta_name' => 'SEO关键词','meta_key' => 'seo_keywords'),
        array('meta_name' => 'SEO标题','meta_key' => 'seo_title'),
        array('meta_name' => '背景图片','meta_key' => 'back')
    );
    if(isset($term->term_id))
        $term_id = $term->term_id;
    foreach($metas as $meta) {
        $meta_name = $meta['meta_name'];
        $meta_key = $meta['meta_key'];
        if(isset($term_id)) $meta_value = gd_seo_get_term_meta($term_id,$meta_key);
        else $meta_value = '';
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_<?php echo $meta_key; ?>"><?php echo $meta_name; ?></label></th>
            <td><input type="text" name="term_meta_<?php echo $meta_key; ?>" id="term_<?php echo $meta_key; ?>" class="regular-text" value="<?php echo $meta_value; ?>"></td>
        </tr>
    <?php
    }
}

function gd_seo_extra_term_fileds_save($term_id){
    if(!empty($_POST)) foreach($_POST as $key => $value){
        if(strpos($key,'term_meta_') === 0 && trim($value) != '') {
            $meta_key = str_replace('term_meta_','',$key);
            $meta_value = trim($value);
            update_term_meta($term_id,$meta_key,$meta_value) OR add_term_meta($term_id,$meta_key,$meta_value,true);
        }
    }
}


//分类
add_action( 'category_add_form_fields','gd_seo_extra_term_fields',10, 2 );
add_action( 'category_edit_form_fields','gd_seo_extra_term_fields',10);
add_action( 'edited_category','gd_seo_extra_term_fileds_save' );  
add_action( 'create_category','gd_seo_extra_term_fileds_save' );

/*
add_action( 'shoptype_add_form_fields','gd_seo_extra_term_fields',10, 2 );
add_action( 'shoptype_edit_form_fields','gd_seo_extra_term_fields',10);
add_action( 'edited_shoptype','gd_seo_extra_term_fileds_save' );  
add_action( 'create_shoptype','gd_seo_extra_term_fileds_save' );*/

//专题
add_action( 'collection_add_form_fields','gd_seo_extra_term_fields',10, 2 );
add_action( 'collection_edit_form_fields','gd_seo_extra_term_fields',10);
add_action( 'edited_collection','gd_seo_extra_term_fileds_save' );  
add_action( 'create_collection','gd_seo_extra_term_fileds_save' );

//标签
add_action( 'post_tag_add_form_fields','gd_seo_extra_term_fields',10, 2 );
add_action( 'post_tag_edit_form_fields','gd_seo_extra_term_fields',10);
add_action( 'edited_post_tag','gd_seo_extra_term_fileds_save' );  
add_action( 'create_post_tag','gd_seo_extra_term_fileds_save' );


?>