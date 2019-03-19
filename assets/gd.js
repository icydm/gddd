$(window).resize(function () {
	f_masonry();
});
$(function() {
	f_viewadd();
});
function f_viewadd(){
	if(gd_array["viewadd"]==1){
		$.get( gd_array["site_url"] + '/wp-content/themes/gddd/api/ajax-view.php?id='+gd_array["post_id"], function( content ) {});
	}
}
function dabupuwidth(){
  	$('.carousel-image').height($('.carousel-image').width()/4.3);
	if (document.body.clientWidth<=873) {
	   $a=($('.content').width()-10)/2;
	   return $a+'px';
	}else{
	   $a=($('.content').width()-100)/5;
	   return $a+'px';
	}
}
function dabupujiange(){
	if (document.body.clientWidth<=873) {
	   return 10;
	}else{
	   return 25;
	}
}
var ajcontainer = $('.js-masonryy');
function hide_jz_button(){
	if(gd_array["pages_num"]==1 || gd_array["pages_num"]==0){
    	$(".in_load_more").hide();
    }
}
function f_banner(){
	$('.gd-banner').flickity({
      freeScroll: false,
      wrapAround: true,
      autoPlay: true
    });
  	$('.select-dapubua ul').flickity({
      freeScroll: true,
      contain: true,
      prevNextButtons: false,
      pageDots: false
	});
}
function f_masonry() {
  		$(".dapubua").css("width",dabupuwidth());
        ajcontainer.masonry({
          gutter: dabupujiange(),
          itemSelector: '.dapubua',
          isAnimated: true,
      });
}
function f_lazyload() {
	$(".img-lazy").lazyload({
		placeholder:"https://wx2.sinaimg.cn/large/0060lm7Tly1fxty1r6jwdg30m80gogqj.gif",
		failurelimit:40,
		load:f_masonry,
	});
}

function f_ajaxindex(){
	gd_array["ajaxpage"]++;
  	$(".in_load_more").addClass("loading");
	$.get( gd_array["site_url"] + '/wp-content/themes/gddd/api/ajax-page-post.php?page='+gd_array["ajaxpage"]+'&cat='+gd_array["ajaxpost_cat"], function( content ) {
		var $content = $( content );
		ajcontainer.append( $content ).masonry( 'appended', $content );
		f_lazyload();
      	f_masonry();
      	$(".in_load_more").removeClass("loading"); 
      	if(gd_array["ajaxpage"] == gd_array["pages_num"]){
           $(".in_load_more").hide(); 	
        }
    });
}


function f_index_cat(obj){
  	$(".select-dapubua ul li.activated").removeClass("activated");
	obj.addClass("activated");
  	gd_array["ajaxpost_cat"] = obj.attr('cat');
  	gd_array["ajaxpage"] = 1;
  	$(".in_load_more").addClass("loading");
  	$.get( gd_array["site_url"] + '/wp-content/themes/gddd/api/ajax-page-post.php?page='+gd_array["ajaxpage"]+'&cat='+gd_array["ajaxpost_cat"], function( content ) {
		var $content = $( content );
		ajcontainer.html( $content ).masonry('destroy').masonry();
      	f_masonry();
		f_lazyload();
	});
  	$.get( gd_array["site_url"] + '/wp-content/themes/gddd/api/ajax-page-num.php?cat='+gd_array["ajaxpost_cat"], function( content ) {
      	a = JSON.parse(content);
		gd_array["pages_num"] = a['num'];
        if(gd_array["pages_num"] == '1' || gd_array["pages_num"] == '0'){
           $(".in_load_more").hide();
        }else{
        	$(".in_load_more").show();
        }
	});
 	$(".in_load_more").removeClass("loading");
}

/*modal开关*/
function openmodal($id){
	if($("#"+$id).hasClass("active")){
		$("#"+$id).removeClass("active");
	}else{
		$("#"+$id).addClass("active");
    }
}

/*瀑布流点赞 待删除 替换成下面的*/
function gd_postlike(obj){
  	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
  	$a = obj.attr("data-post-id");
  	$a = gd_array["site_url"]+'/wp-content/themes/gddd/api/ajax-like.php?id='+$a;
  	$.get($a, function(result){});
	obj.parent().children('.good-a').show();
  	swal("点赞成功！", "","success");
}
function gd_postnotlike(obj){
  	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	$a = obj.attr("data-post-id");
  	$a = gd_array["site_url"]+'/wp-content/themes/gddd/api/ajax-like.php?id='+$a;
  	$.get($a, function(result){});
  	obj.hide();
  	swal("已取消", "","info");
}

/*分享小组件点赞*/
function gd_post_like(obj){
  	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	$a = obj.attr("data-post-id");
  	$num = parseInt(obj.parent().children('span').html());
  	$a = gd_array["site_url"]+'/wp-content/themes/gddd/api/ajax-like.php?id='+$a;
  	$.get($a, function(result){
  		if(obj.hasClass("gd-color")){
			obj.removeClass("gd-color");
          	obj.parent().children('span').html($num-1);
          	obj.parent().parent().children('dd').children('.text').html('点赞');
          	$('#post-like').html($num-1);
			swal("已取消", "","info");
		}else{
			obj.addClass("gd-color");
          	obj.parent().children('span').html($num+1);
          	obj.parent().parent().children('dd').children('.text').html('已点赞');
          	$('#post-like').html($num+1);
			swal("点赞成功！", "","success");
		}
  	});	
}
/*收藏文章*/
function gd_post_collect(obj){
  	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	$a = obj.attr("data-post-id");
  	$num = parseInt($('#post-collect').html());
  	$a = gd_array["site_url"]+'/wp-content/themes/gddd/api/ajax-collect.php?id='+$a;
  	$.get($a, function(result){
  		if(obj.hasClass("gd-color")){
			obj.removeClass("gd-color");
          	$('#post-collect').html($num-1);
          	obj.parent().parent().children('dd').children('.text').html('收藏');
			swal("已取消", "","info");
		}else{
			obj.addClass("gd-color");
          	$('#post-collect').html($num+1);
          	obj.parent().parent().children('dd').children('.text').html('已收藏');
			swal("收藏成功！", "","success");
		}
  	});	
}

function opendashang(){
	$.fancybox.open('<div style="width:280px;height:auto;padding:0;border-radius:4px"><div style="padding:20px"><div style="text-align:center;height:80px;display:block"><img style="width:auto;height:100%;border-radius:50%" src="'+gd_array['site_url']+'/wp-content/themes/gddd/inc/sdk/thumb/index.php?src='+gd_array['auther_ava']+'&w=200&h=200&zc=1"></div><div style="font-size:12px;color:#777;text-align:center;margin:10px 0"><span>我欣赏你的好品味～</span></div><div style="margin:20px auto;font-size:20px;line-height:30px;color:#777;text-align:center"><img style="width: 100%;" src="'+gd_array['dashang']+'"></div><div style="font-size:13px;color:#777;text-align:center"><i class="iconfont icon-saoma" style="font-size: 25px;vertical-align:sub;margin-right:5px;"></i>打开手机支付宝扫一扫<br>我赏我光荣～</div></div></div>');
}
function openfx(){
	$.fancybox.open('<div class="widget-share"><div class="widget-share-title">分享给你的小伙伴</div><div class="widget-share-content"><div class="widget-share-content-wqq"><span id="weibo" onclick="gd_sharetoxl()"><i class="fa fa-weibo fa-lg"></i></span><span id="kongjian" onclick="gd_sharetoqzone()"><i class="iconfont icon-gd_qzone fa-lg"></i></span><span id="qq" onclick="gd_sharetoqq()"><i class="fa fa-qq fa-lg"></i></span></div><div class="widget-share-content-qrcode"><div id="share-qrcode"></div><br><span>扫码分享</span></div></div></div>');
	$('#share-qrcode').html('<img src="'+gd_array['site_url']+'/wp-content/themes/gddd/inc/sdk/qrcode/index.php?c='+window.location.href+'" width="150px"/>');
}
/*分享代码*/
function gd_sharetoxl(){
	title = document.title;
	url = window.location.href;
	picurl = gd_array['thumb'];
	var sharesinastring='http://v.t.sina.com.cn/share/share.php?title='+title+'&url='+url+'&content=utf-8&sourceUrl='+url+'&pic='+picurl;
	window.open(sharesinastring,'newwindow','height=400,width=400,top=100,left=100');
}
function gd_sharetoqzone(){
	title = document.title;
	url = window.location.href;
	picurl = gd_array['thumb'];
	var shareqqzonestring='http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?summary='+title+'&url='+url+'&pics='+picurl;
	window.open(shareqqzonestring,'newwindow','height=400,width=400,top=100,left=100');
}
function gd_sharetoqq(){
	title = document.title;
	url = window.location.href;
	picurl = gd_array['thumb'];
    var _shareUrl = 'https://connect.qq.com/widget/shareqq/iframe_index.html?';
        _shareUrl += 'url=' + encodeURIComponent(url);
        _shareUrl += '&title=' + encodeURIComponent(title);
        _shareUrl += '&pics=' + encodeURIComponent(picurl);
    window.open(_shareUrl,'newwindow','height=500,width=800,top=100,left=100');
}


/**提交评论**/
function postcomment(obj){
	$comment = obj.parent().children('.ipt-txt').val();
  	if($comment==''){
    	swal('评论为空', "","warning");
      	return;
    }
    var input_data = 'comment_post_ID='+gd_array['post_id']+'&comment='+$comment+'&_wp_unfiltered_html_comment='+gd_array['wp_unfiltered_html_comment'];
	if(obj.parent().hasClass('childreplyform')){
		input_data = input_data + '&comment_parent='+	obj.attr('comment-id');
	}
	$.ajax({
	    type: "POST",   
	    url:  gd_array["site_url"]+"/wp-content/themes/gddd/api/ajax-comment.php",   
	    data: input_data,   
	    success: function(msg){   
			$a =JSON.parse(msg);
			if($a['status']==500){
				swal($a['msg'], "","warning");
			}else if($a['status']==200){
				swal('提交成功', "","success");
				if(obj.parent().hasClass('childreplyform')){
					$('#comment'+obj.attr('comment-id')).append($a['msg']);
				}else{
					$('.post_comments').append($a['msg']);
				}
				obj.parent().children('.ipt-txt').val('');
			}
	    }   
	});   
}
function commentformadd(obj){
	$('.tianchong .reply').html('');
	_parentcomment = obj.attr('comment-id');
	text = '<div class="textarea-container childreplyform"><textarea cols="80" name="msg" rows="5" placeholder="请自觉遵守互联网相关的政策法规，严禁发布色情、暴力、反动的言论。" class="ipt-txt"></textarea><button class="comment-submit" comment-id='+_parentcomment+' onclick="postcomment($(this))">发表评论</button></div>';
	obj.parent().children('.tianchong').children('.reply').html(text);
}
/*关注*/
function gd_follow(obj){
	$id = obj.attr('data-id');
    $link = gd_array["site_url"]+'/wp-content/themes/gddd/api/ajax-follow.php?id='+$id;
  	$.get($link, function(result){
		$a =JSON.parse(result);
      	if($a['status']==500){
        	swal($a['msg'], "","warning");
        }else if($a['status']==200){
			swal($a['msg'], "","success");
          	if(obj.hasClass('yiguanzhu')){
            	obj.removeClass("yiguanzhu");
              	obj.html('关注');
            }else{
            	obj.addClass("yiguanzhu");
             	 obj.html('已关注');
            }
        }
  	});
}

/*海报s*/
function canvasTextAutoLine(str,canvas,initX,initY,lineHeight,width){
    var ctx = canvas.getContext("2d"); 
    var lineWidth = 0;
    var canvasWidth = width; 
    var lastSubStrIndex= 0; 
    for(let i=0;i<str.length;i++){ 
        lineWidth+=ctx.measureText(str[i]).width; 
        if(lineWidth>canvasWidth-initX){//减去initX,防止边界出现的问题
            ctx.fillText(str.substring(lastSubStrIndex,i),initX,initY);
            initY+=lineHeight;
            lineWidth=0;
            lastSubStrIndex=i;
        } 
        if(i==str.length-1){
            ctx.fillText(str.substring(lastSubStrIndex,i+1),initX,initY);
        }
    }
}

function openhaibao(){
	$.fancybox.open('<div id="haibao"><canvas id="hbCanvas" width="860" height="1316" style="max-width:100%;width:430px;height:auto;"></canvas><button class="btn gd-color" style="margin: 0 auto;display: block;margin-bottom: 8px;width: 86px;"   onclick="convertCanvasToImage()" title="点击下载">下载</button></div>');
	var url1 = gd_array['thumb'];
    var url2 = gd_array['auther_ava'];//头像
    var code = gd_array['site_url']+'/wp-content/themes/gddd/inc/sdk/qrcode/index.php?c='+window.location.href;
    var title = document.title;
    var auther = gd_array['auther_name'];//作者名称
    var des = gd_array['postdes'];
    
    imageToCanvas('hbCanvas',url1,url2,code,title,auther,des,'1');
}

function imageToCanvas(canvasid,url1,url2,code,title,auther,des,state){
    url1 = gd_array["site_url"] + '/wp-content/themes/gddd/inc/sdk/thumb/index.php?src='+url1+'&w=780&h=780&zc=1';
    url2 = gd_array["site_url"] + '/wp-content/themes/gddd/inc/sdk/thumb/index.php?src='+url2+'&w=120&h=120&zc=1';
    var canvas = document.getElementById(canvasid);
  	var ctx = canvas.getContext("2d"); 
	ctx.fillStyle="#ffffff";
  	ctx.fillRect(0,0,860,1316);
	$('#haibao').append('<img id="url1" src="'+url1+'" />');
	var img1 = document.getElementById('url1');	
  	img1.onload = function() {
    var w = img1.width
    var h = img1.height
    var dw = 780/w 
    var dh = 780/h
    var ratio       
    if(w > 780 && h > 780 || w < 780 && h < 780){
        if (dw > dh) {
            ctx.drawImage(img1, 0, (h - 780/dw)/2, w, 780/dw, 40, 160, 780, 780)
        } else {
            ctx.drawImage(img1, (w - 780/dh)/2, 0, 780/dh, h, 40, 160, 780, 780)
        }
    }
    else{
        if(w < 780){
            ctx.drawImage(img1, 0, (h - 780/dw)/2, w, 780/dw, 40, 160, 780, 780)
        }else {
            ctx.drawImage(img1, (w - 780/dh)/2, 0, 780/dh, h, 40, 160, 780, 780)
        }
    }
    
  }
    $('#haibao').append('<img id="url2" src="'+url2+'" />');
    var img2 = document.getElementById('url2');
  	img2.onload = function() {
	ctx.save(); 
	ctx.arc(100, 80, 60, 0, 2 * Math.PI);
	ctx.clip();
	ctx.drawImage(img2, 40, 20, 120, 120);
	ctx.restore(); 
	
    }
	ctx.save();
	ctx.font="36px Arial";
	ctx.textAlign="left";
	ctx.fillStyle ='black';
  	ctx.font="30px Arial";
	ctx.fillText(auther.substring(0,20),180,120,600);
	ctx.save();
  	ctx.font="36px Arial";
	ctx.fillText(title.substring(0,20),180,70,600);
	canvasTextAutoLine(des.substring(0,30),canvas,40,1036,36,520);
	ctx.fillText('长按识别二维码继续阅读',40,1200);
	var img3 = new Image();
	
	img3.setAttribute('crossorigin', 'anonymous');
	img3.onload = function() {
	  var w = img3.width;
	  var h = img3.height;
	  ctx.drawImage(img3,620,1000,200,200);
	  ctx.save();
	}
    img3.src = code;
   	$('#url1').remove();
 	$('#url2').remove();
  	return;
}


function convertCanvasToImage(){
	var canvas = document.getElementById('hbCanvas');
	var image = new Image();
	image.src = canvas.toDataURL("image/png");
	$('#haibao').html('<a href="'+image.src+'" download="haibao.png"><img style="max-width:100%;width:430px;height:auto;background:#ffffff;" src="'+image.src+'" /></a>');
	const imgUrl = image.src;
	if (window.navigator.msSaveOrOpenBlob) {
		var bstr = atob(imgUrl.split(',')[1])
		var n = bstr.length
		var u8arr = new Uint8Array(n)
		while (n--) {
		u8arr[n] = bstr.charCodeAt(n)
		}
		var blob = new Blob([u8arr])
		window.navigator.msSaveOrOpenBlob(blob, 'chart-download' + '.' + 'png')
	} else {
		var $a = $("<a></a>").attr("href", imgUrl).attr("download", "haibao.png");
		$a[0].click();
	}
  	$.fancybox.close();
  	return;
}
/*海报e*/


function zhankai(obj){
	$('.user-center').animate({ height : $('.user-center').height()+300 } , 600 );
}


function gd_sendsixin(obj){
	$id = obj.attr('data-id');
 	if(gd_array["login_state"]!=="1"){
    	swal("请先登录！", "","warning");
      	return;
    }
	swal({
	  text: '在下方输入你想说的话',
	  content: "input",
	  button: {
	    text: "发送!",
	    closeModal: false,
	  },
	})
	.then(msg => {
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

				}
		    }   
		}); 

	  return;
	swal.stopLoading();
	swal.close();
	})
}



function gd_post_accusation(obj){
    if(gd_array["login_state"]!=="1"){
      swal("请先登录！", "","warning");
      return;
    }
	$.fancybox.open('<div class="album-report" style="width:500px;padding:20px"><h4 class="report-title" style="margin:0">我要举报</h4><p class="tip" style="color:#999;font-size:12px;margin:0">请选择举报的理由:</p><div class="form-group" style="border-bottom:1px solid #3535350f"><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="1" checked><i class="form-icon"></i>侵犯版权</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="2"><i class="form-icon"></i>话题不相关</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="3"><i class="form-icon"></i>垃圾广告</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="4"><i class="form-icon"></i>色情</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="5"><i class="form-icon"></i>引战</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="6"><i class="form-icon"></i>违法信息</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="7"><i class="form-icon"></i>人身攻击</label><label class="form-radio jubaoli"><input type="radio" name="jubaoradio" value="8"><i class="form-icon"></i>不顺眼</label></div><button class="btn" onclick="gd_post_accusationn($(this))">举报</button></div>');
}


function gd_post_accusationn(obj){
	$value = $("input[name=jubaoradio]:checked").attr('value');
	$posturl = window.location.href;
	var input_data = 'id='+$value+'&value='+$posturl;
    $.ajax({
        type: "POST",   
        url:  gd_array["site_url"]+"/wp-content/themes/gddd/api/ajax-jubao.php",   
        data: input_data,   
        success: function(msg){
        $.fancybox.close();
        $a =JSON.parse(msg);
        if($a['status']==500){
          swal($a['msg'], "","warning");
        }else if($a['status']==200){
          swal($a['msg'], "","success");
        }
        
        }   
    }); 
}

function commentlikehate(obj){
	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	input_data = 'coid='+obj.attr('coid');
	$.ajax({
	    type: "POST",   
	    url:  gd_array["site_url"]+"/wp-content/themes/gddd/api/ajax-comment-likehate.php",   
	    data: input_data,   
	    success: function(msg){   
			$a =JSON.parse(msg);
			if($a['status']==500){
				swal($a['msg'], "","warning");
			}else if($a['status']==200){
				swal('成功', "","success");
				commi = obj.children('i');
				if(commi.hasClass("gd-color")){
					commi.removeClass("gd-color");
				}else{
					commi.addClass("gd-color");
			    }
				obj.children('span').html($a['msg']);
			}
	    }   
	});  
}

function gd_video_buy(id){
  	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	$link = gd_array['ajaxurl']+'videobuy&id='+id;
  	$.get($link, function(result){
		$a =JSON.parse(result);
      	if($a['status']==500){
        	swal($a['msg'], "","warning");
        }else if($a['status']==200){
			swal($a['msg'], "","success");
          	setTimeout(function(){location.reload();},1500);
        }
  	});
}


const scrollToTop = () => {
  const y = document.documentElement.scrollTop || document.body.scrollTop || window.pageYOffset
  if (y > 0) {
    window.requestAnimationFrame(scrollToTop);
    window.scrollTo(0, y - y / 8);
  }
};
var sidebtn = new Vue({
      el: '#asidebutton',
      data:{},
      methods:{
        qiandan(){
            var url = gd_array.ajaxurl+"gd_signin";
            this.$http.post(
              url,
              {
				credit:1
              },
              {emulateJSON:true}
              ).then(function(res){
                ress =res.data;

                if(ress.status == '200'){
                    this.$notify({
                      title: '签到成功',
                      message: '获得'+ress.msg+'积分',
                      type: 'success'
                    });
                }
                else if(ress.status == '500'){
                    this.$notify.error({
                      title: '签到失败',
                      message: ress.msg
                    });
                }
            });
        },
        backtop(){
			scrollToTop();
        }
      }
})

function postpay(id){
  	if(gd_array["login_state"]!=="1"){
    	swal("请先登录", "","warning");
      	return;
    }
	$link = gd_array['ajaxurl']+'postcontentbuy&id='+id;
  	$.get($link, function(result){
		$a =JSON.parse(result);
      	if($a['status']==500){
        	swal($a['msg'], "","warning");
        }else if($a['status']==200){
			swal($a['msg'], "","success");
          	setTimeout(function(){location.reload();},1500);
        }
  	});
}




