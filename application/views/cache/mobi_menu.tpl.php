<?php
$result = explode(',',$this->trans->get('Избранное,В избранное ничего нет,Пароль,Войти в личный аккаунт,Регистрация,Забыли пароль,В корзине,Корзина пуста,Выход'));
	$articles_count = 0;
	$articles_price = 0.0;
	if ($this->getBasket()) {
		foreach ($this->getBasket() as $item) {
			$articles_count += $item['count'];
			$articles_price += $item['price'] * $item['count'];
		}
	}
?>
<script>
function toggle(el) {
el.style.display = (el.style.display == 'none') ? '' : 'none';
}
$(document).ready(function(){
$(".openFilter").click(function() {
hideShowDiv();
});
$(".open").click(function() {
$("body").toggleClass("modal-open");
$(".s_h").toggleClass("left");
if ($("#none").is(':hidden')){
$("#none").show();
$("#navbar-main").slideDown();
}else{
$("#navbar-main").slideUp();
$("#none").hide();

}
$("#none").toggleClass("show");

$(".logo-grey").toggleClass("red");
//$(".logo-red").toggleClass("opacity_logo_red");

});
});
</script>
<nav class="navbar fixed-top  navbar-dark bg-dark p-1">
  <button class="navbar-toggler collapsed open">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="logo_m" style="vertical-align: top;display: inline-block;">
	<a class="navbar-brand1" href="/"><div class="logo-grey"></div><div class="logo-red"></div></a>
  </div>
<div class="menu_m_a1 px-0 float-right position-relative text-white" style="top:-5px" >
    <div id="m_desires" class="d-inline-block text-white" style="width: 30px;margin: 5px;height: 30px;">
		<?php if($_SESSION['desires']){ ?>
		<a href="/desires/" title="<?=$result[0]?>" class="p-0"><i class="icon ion-ios-heart text-white" style="font-size:30px"></i></a>
		<?php }else{ ?>
		<a href="#" title="<?=$result[1]?>!" class="p-0"><i class="icon ion-ios-heart-outline text-white" style="font-size:30px"></i></a>
		<?php } ?>
    </div>
    <a onclick="toggle(searsh)" >
        <span class="d-inline-block" style="width: 30px;margin: 5px;height: 30px;">
            <i class="icon ion-ios-search" style="font-size:30px"></i>
        </span>
    </a>
    
    <a href="/basket/"  title="<?php if($articles_count > 0){echo $result[6].' '.$articles_count.' '.$word;}else{echo $result[7];}?>">
        <span class="m_basket1 img_bag d-inline-block text-white" style="width: 30px;margin: 5px;height: 30px;" >
            <i class="icon ion-ios-basket" style="font-size:30px"></i>
            <span id="span_ok1" <?php if($articles_count > 0) {echo 'class="span_ok rounded-circle"';}?> style="right: 5px;top:25px">
                <?php if($articles_count > 0){  echo $articles_count;} ?>
            </span>
        </span>
    </a>	
</div>
		</nav>
<?php if(Config::findByCode('new_grafik')->getValue()){
    echo '<p style="padding: 1px 5px;text-align:  center;color:  red;margin-bottom: 0px;">'.Config::findByCode('new_grafik')->getValue().'</p>';
 } ?>
<div class="back" style="z-index: 53;height: 42px;width: 100%;position: fixed;top: 52px;padding: 5px 0;background: #f1f1f1;">
    <div style="position: fixed;left: 10px;">
		<a href="javascript:history.back();" style="font-size: 16px;color: #707070;">
     <img src='/mobil/mimages/back.png' alt="Обратно" style="width: 30px;">  <?=$this->trans->get('Назад');?>  
</a>
	</div>
  <?php if(true){ ?>  
    <button class="navbar-toggler  float-right mx-1" style="    color: #6c757d;" type="button" data-toggle="modal" data-target="#mobi_filter" onclick="hideShowDiv();">
    <span><i class="icon ion-ios-funnel" style="font-size: 25px;"></i></span>
  </button>
    <button class="navbar-toggler float-right mx-1" style="    color: #6c757d;" type="button" data-toggle="modal"  data-target="#mobi_filter_cat">
    <span><i class="icon ion-md-list" style="font-size: 25px;"></i></span>
  </button>
    <?php } ?>
</div>
<div id="none" class="modal fade open s_h left" style="background: #0000006b;top:52px;">
<div class="collapse kat_menu_m navbar-collapse s_h left p-2" id="navbar-main" style="position: relative;">
<ul class="navbar-nav">
<li class="nav-item">
			<div class="nav-link" style="padding-top: 0.2rem;padding-bottom: 0.2rem;">
				<div class="btn-group" role="group" aria-label="Basic example">
			
				
					<button type="button" class="btn btn-secondary btn-sm" <?php echo $_SESSION['lang'] == 'uk' ? disabled : '';?> name="uk" onclick="setUk('uk','<?=Registry::get('lang')?>','<?=$_SERVER['REQUEST_URI']?>');" >UA</button>
					<button type="button" class="btn btn-secondary btn-sm" <?php echo $_SESSION['lang'] == 'ru' ? disabled : '';?>  name="ru" onclick="setUk('ru','<?=Registry::get('lang')?>','<?=$_SERVER['REQUEST_URI']?>');" >RU</button>
					
				</div>
				<?php if($this->ws->getCustomer()->getIsLoggedIn()){
	echo '  '.$this->ws->getCustomer()->getFirstName() . ' ' . $this->ws->getCustomer()->getMiddleName();
	}?>
			</div>
						</li>
						<li class="nav-item">
		<a href="tel:+380442244000" class="d-block btn btn-light py-1 my-1 text-left"><span>+38(044) 224-40-00</span></a>
		</li>
						<li class="nav-item">
						<a href="/" class="d-block btn btn-light py-1 my-1 text-left"><img src="/mobil/mimages/menu/home.png" alt="Главная"/><span><?=$this->trans->get('Главная');?></span></a>
						</li>
		
                <?php  if (!$this->ws->getCustomer()->getIsLoggedIn()) { 
			?>
			<li class="nav-item">
			<a href = "/account/login/" class="d-block btn btn-light py-1 my-1 text-left" ><img src="/mobil/mimages/menu/vhod.png" alt="Вход"/><?=$this->trans->get('Войти');?></a>
			</li>
			<li class="nav-item">
			<a href = "/account/register/" class="d-block btn btn-light py-1 my-1 text-left" ><img src="/mobil/mimages/menu/reg.png" alt="<?=$result[4]?>"/><?=$result[4]?></a>
			</li>
                <?php }else { 
				?>
					<li class="nav-item">	<a href = "/account/" class="d-block btn btn-light py-1 my-1 text-left" ><img src="/mobil/mimages/menu/login.png" alt="Личный кабинет"/><?=$this->trans->get('Личный кабинет');?></a></li>
                <?php } ?>
						
						<li class="nav-item">
						<a href="/brands/" class="d-block btn btn-light py-1 my-1 text-left"><img src="/mobil/mimages/menu/brand.png" alt="Бренды"/><?=$this->trans->get('Бренды');?></a> 
						</li>
						<?php		
                    foreach (wsActiveRecord::useStatic('TopMenu')->findAll() as $menu) {
					echo '<li class="nav-item" ><a href="' . $menu->getUrl() . '" class="d-block btn btn-light py-1 my-1 text-left">
					<img  src="/mobil/mimages/menu/'.$menu->getImg().'.png" />' . $menu->getTitle() . '</a></li>';
                    }
					?>
					<li class="nav-item">
						<a href="/returns/" class="d-block btn btn-light py-1 my-1 text-left"><img src="/mobil/mimages/menu/return.png" alt="Условия Возврата"/><?=$this->trans->get('Условия Возврата');?></a>
						</li>
						<?php  if ($this->ws->getCustomer()->getIsLoggedIn()) { 
			?><li class="nav-item">
			<a href="/account/logout/" class="d-block btn btn-light py-1 my-1 text-left"><img src="/mobil/mimages/menu/exit.png" alt="Выход"/><?=$result[8]?></a>
			</li>
			<?php } ?>
					</ul>

</div>



</div>
<div class="" id="searsh" style="display: none;">
<form method="get" id="new_search_box" action="/search/">
<div>
<input class="input_sersh" placeholder="<?=$this->trans->get('Поиск')?>" type="text" name="s" default="<?=$this->trans->get('Поиск')?>" value=""/>
<input type="submit" value="Искать" style="display: none;" />
</div>
</form>
</div>