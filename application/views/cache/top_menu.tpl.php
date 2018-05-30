<?php
$result = explode(',',$this->trans->get('Избранное,В избранном ничего нет,Пароль,Войти в личный кабинет,Регистрация,Забыли пароль,В корзине,Корзина пуста,Выход'));
	$articles_count = 0;
	//$articles_price = 0.0;
	if ($this->getBasket()) {
		foreach ($this->getBasket() as $item) {
			$articles_count += $item['count'];
			//$articles_price += $item['price'] * $item['count'];
		}
	}
	$_SESSION['count_basket'] = $articles_count;
	if ($articles_count == 1) {
		$word = "товар";
	}
	elseif ($articles_count >= 2 && $articles_count <= 4) {
		$word = "товара";
	}
	else {
		$word = $this->trans->get("товаров");
	}
?>
<div class="modal fade" id="myBasket" tabindex="-1" role="dialog" aria-labelledby="myBasketLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title">СОДЕРЖИМОЕ КОРЗИНЫ</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		<div class="modal-body mess_backet" >
		<table  class=""> 
					<tr>
						<td>
						<label>Эл. почта</label>
						<div class="input-group mx-2">
							<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
							</div>
							<input type="text"  placeholder="email" autocomplete="off" class="form-control" name="login" aria-label="email" aria-describedby="basic-addon1">
							</div>
						</td>
						<td>
							<label>Пароль</label>
							<div class="input-group mx-2">
							<div class="input-group-prepend">
							<span class="input-group-text pencil" id="basic-addon2"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i></span>
							</div>
							<input type="password"  class="form-control" autocomplete="off" placeholder="Пароль"  name="password" aria-label="Пароль" aria-describedby="basic-addon2" >
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<a class="input-group mx-2" href="/account/register/" ><?=$result[4]?></a>
						</td>
						<td>
							<a class="input-group mx-2" href="/account/resetpassword/"><?=$result[5]?>?</a>
						</td>
					</tr>
               </table>
		</div>
		<div class="modal-footer">
			<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Продолжить покупки</button>
			<button class="btn btn-danger" data-dismiss="modal" onclick="location.href='/basket/'" aria-hidden="true">Перейти в корзину</button>
		</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title">Вход в личный кабинет</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		<div class="modal-body">
			<form id="authenticateByLogin" name="authenticateByLogin" class="form-inline" action="/account/login/?redirect=<?=$_SERVER['REQUEST_URI'];?>" method="post">
				<table  class=""> 
					<tr>
						<td>
						<label>Эл. почта</label>
						<div class="input-group mx-2">
							<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
							</div>
							<input type="text"  placeholder="email" class="form-control" name="login" aria-label="email" aria-describedby="basic-addon1">
							</div>
						</td>
						<td>
							<label>Пароль</label>
							<div class="input-group mx-2">
							<div class="input-group-prepend">
							<span class="input-group-text pencil" id="basic-addon2"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i></span>
							</div>
							<input type="password"  class="form-control" placeholder="Пароль"  name="password" aria-label="Пароль" aria-describedby="basic-addon2" >
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<a class="input-group mx-2" href="/account/register/" ><?=$result[4]?></a>
						</td>
						<td>
							<a class="input-group mx-2" href="/account/resetpassword/"><?=$result[5]?>?</a>
						</td>
					</tr>
               </table>
			   <input type="submit" style="display: none"/>
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
			<button class="btn btn-primary" onclick="$('#authenticateByLogin').submit(); return false;">Войти</button>
		</div>
		</div>
	</div>
</div>
<nav class="navbar navbar-expand-md  fixed-top navbar-dark bg-dark top_menu_new">
  <button class="navbar-toggler"  type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
		<li class="nav-item">
			<div class="nav-link" style="padding-top: 0.2rem;padding-bottom: 0.2rem;">
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="button" class="btn btn-secondary btn-sm" <?php echo @$_SESSION['lang'] == 'uk' ? disabled : '';?> name="uk" onclick="setUk(this);" >UA</button>
					<button type="button" class="btn btn-secondary btn-sm" <?php echo @$_SESSION['lang'] == 'ru' ? disabled : '';?>  name="ru" onclick="setUk(this);" >RU</button>
				</div>
			</div>
		</li>
	<?php foreach (wsActiveRecord::useStatic('TopMenu')->findAll() as $menu) { ?>
		<li class="nav-item">
			<a class="nav-link" href="<?=$menu->getUrl()?>"><?=$menu->getTitle()?></a>
		</li>
	<?php } ?>
    </ul>
</div>
<div class="menu_login">
	 <?php $des = 0; if (!$this->ws->getCustomer()->getIsLoggedIn()) { ?>
				<div>
				<a href="#" role="button" class="nav-link" data-placement="left"  data-tooltip="tooltip"     title="<?=$result[3]?>">
				<img src="/img/top_menu/sign-input.png" data-toggle="modal" data-target="#myModalLogin" >
				</a>
					</div>
                <?php } else {
				$des = wsActiveRecord::useStatic('Desires')->count(array('id_customer'=>$this->ws->getCustomer()->getId()));
				?>
				 <div >
<a href="/account/" class="nav-link new_name_ok" style="color: #ffffff;padding-right: 10px;"><?=$this->ws->getCustomer()->getFirstName().' '.$this->ws->getCustomer()->getMiddleName()?></a></div>
<div  >
<a href="/account/logout/" class="nav-link" data-placement="left"  data-tooltip="tooltip"  title="<?=$result[8]?>"><img  src="/img/top_menu/sign-output.png">
</a>
</div>
                <?php } ?>
	<div >
	<?php if($_SESSION['desires'] or $des > 0 ){ ?>
					<a href="/desires/" class="new_login_ok nav-link" data-placement="left"  data-tooltip="tooltip"  title="<?=$result[0]?>">
					<img class="img_des" src="/img/top_menu/des_wite_all.png" alt="Избранное"/>
					</a>
					<?php
					}else{
					?>
					<a href="#" class="new_login_ok nav-link" data-placement="left"  data-tooltip="tooltip"  title="<?=$result[1]?>!">
					<img class="img_des" src="/img/top_menu/des_wite.png" alt="Избранное" > 
					</a>
					<?php 
					}
					?>
	</div>
<div>
	<a href="#" class="nav-link" data-placement="left" onclick="basket_view()"; data-tooltip="tooltip" title="<?php if($articles_count > 0){echo $result[6].' '.$articles_count.' '.$word;}else{echo $result[7];}?>" >					
					<img class="img_bag"      src="/img/top_menu/backet.png" alt="Корзина"/>
					<span id="span_ok" class="rounded-circle <?php if($articles_count > 0) echo 'span_ok'; ?>"><?php if($articles_count > 0) echo $articles_count; ?></span>
	</a>
	</div>	
	</div>
</nav>
<script>
function basket_view(){
$.ajax({
			type: "POST",
			url: '/shop/basket/',
			data: '&metod=view_basket',
			dataType: 'json',
			success: function (data) {
			console.log(data);
			$(".mess_backet").html(data);
			$('#myBasket').modal('show');
			}
		});
}
</script>
