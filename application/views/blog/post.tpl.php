<?php
$text_trans_blog = explode(',', $this->trans->get('Недавнее,Ваше Имя,Подписаться,Смотреть'));
if(Registry::get('device') == 'computer' or ($_COOKIE['mobil'] and $_COOKIE['mobil'] == 10)){ 
$desctop = true;
}else{
$desctop = false;
}
 ?>
<!--
<script>
$(function(){
$(".zxz_post p img").each(function() {
var img = this;
$(this).replaceWith('<a href="'+img.src+'" class="im"><img src="'+img.src+'" width="100%"></a>');
});	
$(".zxz_post p a.im").lightBox({
  //  overlayBgColor: '#FFF',
  fixedNavigation: true,
    overlayOpacity: 0.6,
    //imageLoading: 'img/loading.gif',
   // imageBtnClose: 'img/close.gif',
   // imageBtnPrev: 'img/prev.gif',
    //imageBtnNext: 'img/next.gif',
    containerResizeSpeed: 350,
    txtImage: 'Изображение',
    txtOf: 'из',
	keyToClose: 'Закрыть',
	keyToPrev: 'Назад',
	keyToNext: 'Далее'
   });
});

</script>-->
<div class="container">
<div class="row mx-0">
    <?php 
		$d = date("Y-m-d H:i:s");
		$post = Blog::find('Blog',["public"=>1, "ctime < '$d'"], [], [10]);
		?>

    <div class="col-xl-2 col-lg-2  d-none d-lg-block d-xl-block">
    <div class="card">
        <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center "><?=$text_trans_blog[0]?></h6>
  </div>
        <div class="card-body">
	<ul class="list-unstyled">
		<?php foreach ($post as $item) { ?>
		<li style="list-style-type: none;" class="media my-1">
		<img src="/storage<?=$item->getImage(); ?>" class="align-self-center mr-3" style="width:35px;">
		<div class="media-body">
		<a href="<?=$item->getPath()?>">
		<?=$item->getPostName();?>
		</a>
		</div>
		</li>
		<?php } ?>
				</ul>
        </div>
                                </div>
</div>
    
<div class="col-md-12 col-lg-10 col-xl-10 ">
   <div class="card">
         <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center mb-0">
        <div class="btn-group" role="group" aria-label="Basic example">
<?php
		foreach (BlogCategory::getAllCategory() as $value) { ?>
		<a href="<?=$value->getPath()?>" class="btn btn-secondary"><?=$value->getName()?></a>
		<?php } ?>
</div>
    </h6>
  </div>
         <div class="card-body">
	<div class="col-md-12">
		
			<div class="col-md-12 m-2">
				<span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$this->onepostblog->getUtime();?></span>|
				<span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$this->onepostblog->getAutor();?></span>
			</div> 
			<div class="col-md-12 m-2 zxz_post">
				<?=$this->onepostblog->getContentPost()?>
			</div>
            <br>
            <div class="fb-like" data-href="https://www.red.ua'/blog/id/' .<?=$this->onepostblog->getId()?>/" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
			
	</div>
    </div>
       </div>
    
    
</div>	
    <!--
<div class="col-lg-2 col-xl-2 d-none d-lg-block d-xl-block">
    <div class="card">
         <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center ">FASHION RADIO</h6>
  </div>
        <div class="card-body">
            	<div>
	<img src="/images/ofr_btn2.png" style="cursor: pointer; width: 100px;" onclick="window.open('http://ofr.fm/air/','','toolbar=no, location=no, scrollbars=no, resizable=no, top=100, left=100, width=360, height=593'); return false;" >
	</div>
	<div style="margin-top:10px;" class="fb-like-box" data-href="https://www.facebook.com/pages/RED-UA/148503625241218" data-width="198"
             data-height="400" data-show-faces="true" data-stream="false" data-header="true">    
        </div>
	<iframe id="fr" style="overflow: hidden; height: 100px; width: 198px; border: 0pt none;" src="https://www.youtube.com/subscribe_widget?p=SmartRedShopping"  scrolling="no" frameborder="0"></iframe>

        </div>
        </div></div>	-->		
</div>
</div>
<script>
(function(d, s, id) {
//console.log(d);
//console.log(s);
///console.log(id);
                var js, fjs = d.getElementsByTagName(s)[0];
				//console.log(fjs);
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
</script>
