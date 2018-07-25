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
<script type="text/javascript">
function toggle(el) {
el.style.display = (el.style.display == 'none') ? '' : 'none'
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
//$("#navbar-main").show("slide", { direction: "down" }, 1000);
}else{
$("#navbar-main").slideUp();
$("#none").hide();

}
//$("#none").style.display = $("#none").style.display == 'none'? 'block' : 'none';
$("#none").toggleClass("show");

$(".logo-red").toggleClass("opacity_logo_red");
});
});
</script>
<nav class="navbar fixed-top navbar-light bg-light p-1">
  <button class="navbar-toggler collapsed open"  type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="logo_m" style="vertical-align: top;display: inline-block;">
	<a class="navbar-brand1" href="/"><div class="logo-grey"></div><div class="logo-red"></div></a>
  </div>
		<div class="menu_m_a px-0" >
			<div id="m_desires">
		<?php if(@$_SESSION['desires']){ ?>
					<a href="/desires/" title="<?=$result[0]?>" class="p-0"><div class="m_desires_ok"></div></a>
					<?php }else{ ?>
					<a href="#" title="<?=$result[1]?>!" class="p-0"><div class="m_desires"></div></a>
					<?php } ?>
			</div>
		<a onclick="toggle(searsh)" class="p-0"><div class="searsh"></div></a>
		<a href="/basket/" class=""  title="<?php if($articles_count > 0){echo $result[6].' '.$articles_count.' '.$word;}else{echo $result[7];}?>">
			<div class="m_basket img_bag" ><span id="span_ok1" <?php if($articles_count > 0) echo 'class="span_ok rounded-circle"'?> style="right: 0px;"><?php if($articles_count > 0)  echo $articles_count; ?></span></div>
		</a>	
   </div>
		</nav>
		<?php if(Config::findByCode('new_grafik')->getValue()){ echo '<p style="
    padding: 1px 5px;
    text-align:  center;
    color:  red;
    margin-bottom: 0px;
">'.Config::findByCode('new_grafik')->getValue().'</p>'; ?>
		
		
		<?php } ?>
<div class="back" style="
    z-index: 53;
	height: 42px;
    width: 100%;
    position: fixed;
    top: 48px;
    padding: 5px 0;
    background: #f1f1f1;">
	<div style="position: fixed;left: 10px;">
		<a href="javascript:history.back();" style="font-size: 16px;color: #707070;">
     <img src='/mobil/mimages/back.png' alt="Обратно" style="width: 30px;">  <?=$this->trans->get('Назад');?>  
</a>
	</div>
		<div  style="position: fixed;right: 10px; <?php if(trim($this->getCurMenu()->getUrl()) != 'category') echo 'display:none;'; ?>">
	<a  onclick="hideShowDiv();" style="font-size: 16px;color: #707070;">  <?=$this->trans->get('Фильтры');?>   <img src='/mobil/mimages/filter.png' alt="Фильтры" style="
    width: 25px;
"></a>
	</div>
</div>
<div id="none" class="modal fade open s_h left" style="background: #0000006b;top:48px;">
<div class="collapse kat_menu_m navbar-collapse s_h left p-2" id="navbar-main" style="position: relative;">
<ul class="navbar-nav">
<li class="nav-item">
			<div class="nav-link" style="padding-top: 0.2rem;padding-bottom: 0.2rem;">
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="button" class="btn btn-secondary btn-sm" <?php echo @$_SESSION['lang'] == 'uk' ? disabled : '';?> name="uk" onclick="setUk(this);" >UA</button>
					<button type="button" class="btn btn-secondary btn-sm" <?php echo @$_SESSION['lang'] == 'ru' ? disabled : '';?>  name="ru" onclick="setUk(this);" >RU</button>
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
		<input class="input_sersh" placeholder="<?=$this->trans->get('Поиск');?>" type="text" name="s" default="<?=$this->trans->get('Поиск');?>" value=""/>
		<input type="submit" value="Искать" style="display: none;" />
	</div>
</form>
</div>