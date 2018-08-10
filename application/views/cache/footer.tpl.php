<?php // echo $this->socsety; ?>
<footer>
<div class="row footer">
<div class="col-xs-12 col-sm-6 col-md-3">

<p><?=$this->trans->get('График работы Call-центра')?></p>
<div>
<span><i class="glyphicon glyphicon-time" aria-hidden="true"></i> Пн-Пт:	09:00 - 18:00<br>
<?=$this->trans->get('Сб-Вс:	Выходные')?></span><br>
<span><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i> (044) 224-40-00</span><br>
<span><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i> (063) 809-35-29</span><br>
<span><i class="glyphicon glyphicon-earphone" aria-hidden="true"></i> (067) 406-90-80</span><br>
<span><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i> market@red.ua</span><br>
</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-3">
<p><?=$this->trans->get('Страницы')?></p>
<?php foreach (wsActiveRecord::useStatic('FooterMenu')->findAll() as $menu) {
 echo '<div class="list-page"><a href="'.$menu->getUrl().'"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> '.$menu->getTitle().'</a> </div>';
 }?>
</div>
<div class="col-xs-12 col-sm-6 col-md-3">
<p><?=$this->trans->get('Новости')?></p>
<span><?=$this->trans->get('Подпишитесь на нашу новостную рассылку, чтобы всегда располагать последней информацией и узнавать о наших особых предложениях')?>!</span><br><br>
<div>
<form action="/subscribe/" method="POST" id="formulier" name="formulier">
<input type="hidden" name="active" value="1">
		<input type="text" class="form-input"  placeholder="E-mail..." value="" name="email">
	<input type="submit" class="form-submit" value="Подписаться">
               </form>
</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-3">
<p><?=$this->trans->get('Соц.Сети')?></p>
<div class="footer-soc">
<span><?=$this->trans->get('Присоединяйтесь к нашим группам в социальных сетях. Еще больше общения, еще ярче эмоции, еще интереснее посты')?>!
</span><br><br>
<a href="https://www.facebook.com/lifestyle.red.ua/"><img alt="lifestyle" src="/img/footer/facebuk.png"></a>
<a href="http://instagram.com/red_ua"><img alt="red_ua" src="/img/footer/instagram.png"></a>
<a href="https://www.youtube.com/user/SmartRedShopping"><img alt="SmartRedShopping" src="/img/footer/youtube.png"></a>
</div>
</div>
</div>
<div  style="text-align:center;">
	<span class="copirite">&copy; Интернет-магазин RED.UA, <?=date('Y');?></span>
	<img style="-webkit-filter: grayscale(100%);margin-left: -15%;" src="/img/icons/payment_logo.png" alt="payment">
	<?php
//d($this->get, false);
	if($this->get->controller == 'Home'){ ?><span class="copirite" style="left: 20px;">Раскрутка сайта — <a href="https://aweb.ua/">Aweb.ua</a></span><?php } ?>
</div>
</footer>
<p id="back-top"><a href="#top"><span></span></a></p>
<style>#liracrm-callme{    bottom: -1%;
    right: -1%;}
</style>
<script async src='//uaadcodedsp.rontar.com/rontar_aud_async.js'></script>
<script>
window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
    rnt('add_event', {advId: 20676});
    //<!-- EVENTS START -->
rnt('add_event', {advId: '20676', pageType:'home'});
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
    //<!-- EVENTS FINISH -->
$(document).ready(function(){

$("#scrolled-socials").hide();
	// hide #back-top first
	$("#back-top").hide();
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
			$("#scrolled-socials").fadeIn();
				$('#back-top').fadeIn();
			} else {
			$("#scrolled-socials").fadeOut();
				$('#back-top').fadeOut();
			}
		});
		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		}); 
	});
});

/*widget*/
(function() {
var widgetId = 'a977b0023f4594ba63190bf5ca00d6ba';
var s = document.createElement('script');
s.type = 'text/javascript';
s.charset = 'utf-8';
s.async = true;
s.src = '//callme.voip.com.ua/script/widget/'+widgetId;
var ss = document.getElementsByTagName('script')[0];
ss.parentNode.insertBefore(s, ss);}
)();

function setUk(l) {
var s = '<?=$_SESSION['lang']?>';
if(l.name != s){
      $.ajax({
         type: "POST",
         url: "/ajax/setlang/",
         data: "&lang="+l.name,
         success: function(res){
		 hr = location.href;
		 if(res == 'ru' && hr.indexOf('uk') != -1){
		 location.replace(hr.replace('uk','ru'));
		 }else if(res == 'uk' && hr.indexOf('ru') != -1) {
		 location.replace(hr.replace('ru', 'uk'));
		 }else{
		 location.replace(location.origin+'/'+res+location.pathname);
		 }
		 }
          });
		  }
          return false;
}
function setCooki(e) {
document.cookie = "mobil =" + e;
location.reload();
          return false;
}
</script>
