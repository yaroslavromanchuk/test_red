<?php
if(Registry::get('device') == 'computer' or (@$_COOKIE['mobil'] and $_COOKIE['mobil'] == 10)){ 
$desctop = true;
}else{
$desctop = false;
}
 ?>
<script type="text/javascript">
$(function(){
$(".zxz_post p img").each(function() {
var img = this;
$(this).replaceWith('<a href="'+img.src+'"><img src="'+img.src+'" width="50%"></a>');
});
$(".zxz_post p a").lightBox({
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
</script>
<div class="row mx-0">
<div class="col-lg-2 col-xl-2 d-none d-lg-block d-xl-block">
<?php 
		$d = date("Y-m-d H:i:s");
		$post = wsActiveRecord::useStatic('Blog')->findAll(array("public"=>1, "ctime < '$d'"), array(), array(10));
		?>
				<p style="font-weight: bold;text-align: center;margin-top: 5px;"><?=$text_trans_blog[0]?></p>
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
<div class="col-md-12 col-lg-8 col-xl-8">
	<div class="col-xs-12 col-md-12 col-xl-12 text-center m">
		<div class="btn-group" role="group" aria-label="Basic example">
		<?php foreach ($this->blog_cat as $value) { ?>
			<a href="/blog/?category=<?=$value->getId();?>" class="btn btn-secondary"><?=$value->getName();?></a>
		<?php } ?>
		</div>
	</div>
	<div class="col-md-12 px-1">
		<?php foreach ($this->onepostblog as $value) { ?>
			<h2 style="font-size: 2rem;"><a href="<?=$value->getPath(); ?>"><?=$value->getPostName();?></a></h2>
			<div class="col-md-12 m-2">
				<span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$value->getUtime();?></span>|
				<span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$value->getAutor();?></span>
			</div> 
			<div class="col-md-12 m-2 zxz_post">
				<?php echo $value->getContentPost(); ?>
			</div>
<?php if(@$this->onepostblog){?></br><div class="fb-like" data-href="https://<?=$_SERVER['HTTP_HOST'].'/blog/id/' .$value->getId();?>" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div><?php } ?>

			<?php } ?>
	</div>
</div>			
<div class="col-lg-2 col-xl-2 d-none d-lg-block d-xl-block">
<p style="font-weight: bold;text-align: center;margin-top: 5px;">FASHION RADIO</p>
	<div style="text-align: center;">
	<img src="/images/ofr_btn2.png" style="cursor: pointer; width: 100px;" onclick="window.open('http://ofr.fm/air/','','toolbar=no, location=no, scrollbars=no, resizable=no, top=100, left=100, width=360, height=593'); return false;" >
	</div>
	<div style="margin-top:10px;" class="fb-like-box" data-href="https://www.facebook.com/pages/RED-UA/148503625241218" data-width="198"
                 data-height="400" data-show-faces="true" data-stream="false" data-header="true"></div>
	<iframe id="fr" style="overflow: hidden; height: 100px; width: 198px; border: 0pt none;" src="https://www.youtube.com/subscribe_widget?p=SmartRedShopping"  scrolling="no" frameborder="0"></iframe>
</div>			
</div>
<script>

(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
			</script>
