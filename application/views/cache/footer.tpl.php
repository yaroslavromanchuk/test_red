<link rel="stylesheet" type="text/css" href="/css/footer.css?v=1.3" />
<footer>
<div class="row footer m-auto">
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
<div style="column-count: 2;">
<?php foreach (wsActiveRecord::useStatic('FooterMenu')->findAll() as $menu) {
 echo '<div class="list-page"><a href="'.$menu->getUrl().'"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> '.$menu->getTitle().'</a> </div>';
 }?>
    </div>
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
<p><?=$this->trans->get('Соц.Сети')?> <a href="/#">RED.UA</a></p>
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
	<span class="copirite">&copy; <a href="/">Интернет-магазин RED.UA</a>, <?=date('Y');?></span>
	<img style="-webkit-filter: grayscale(100%);margin-left: -15%;" src="/img/icons/payment_logo.png" alt="payment">
	<?php
//d($this->get, false);
	if($this->get->controller == 'Home'){ ?><span class="copirite" style="left: 20px;">Раскрутка сайта — <a href="https://aweb.ua/">Aweb.ua</a></span><?php } ?>
</div>
</footer>
<p id="back-top"><a href="#top"><span></span></a></p>
<script src="//uaadcodedsp.rontar.com/rontar_aud_async.js"></script>
<script>
window.rnt=window.rnt||function(){(rnt.q=rnt.q||[]).push(arguments)};
rnt('add_event', {advId: 20676});
rnt('add_event', {advId: '20676', pageType:'home'});
rnt('add_audience', {audienceId: '20676_254951d7-6d13-4ea2-a507-747c9e6fe802'});
</script>
