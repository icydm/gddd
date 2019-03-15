<?php
/**
 *Template Name: 测试页面
 *
**/
get_header();
?>

<div class="container" style="max-width: 1025px;background:white;">
  


                  
<div id="xxxminer" style="padding-bottom: 5px;">

  
  

<div id="waveform"></div>  
<script src="https://unpkg.com/wavesurfer.js"></script>
<script>
var wavesurfer = WaveSurfer.create({
    container: '#waveform',
    waveColor: 'violet',
    progressColor: 'purple'
});
wavesurfer.load('https://www.moyuf.cn/wp-content/uploads/2019/02/song_cjrg_teasdale_64kb.mp3');
</script>

  
  
  
  
</div>



<script type='text/javascript' src='//cdn.bootcss.com/vue/2.4.4/vue.min.js'></script>




</div>

<?php
get_footer();
