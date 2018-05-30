	<?php
	$articles_count = 0;
	$articles_price = 0.0;
	if ($this->getBasket()) {
		foreach ($this->getBasket() as $item) {
			$articles_count += $item['count'];
			$articles_price += $item['price'] * $item['count'];
		}
	}
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
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" id="navbar-main-toggle" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false">
          <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
          <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href=".">
					<div class="logo">
						<div class="logo-grey"></div>
						<div class="logo-red"></div>
					</div>
					
      	</a>
							<div id="f_s"style="float:right; width: 70px; display: block">
					<table cellpadding="0" cellspacing="0">
					<tr>
							 <td style="padding-left: 0px;padding-top: 9px;">
                                <span style=""><?php echo "(".$articles_count.")"; ?></span>
							</td>
							<td style="padding-top:10px; padding-left:5px">
								<a href="/basket/"  title="Корзина"><img src="/img/bag.png" alt="enter"/></a>
                            </td>
					</tr>					
						</table>		
					</div>
    	</div>
		            
		
		<div class="collapse navbar-collapse" id="navbar-main">

						<ul class="nav navbar-nav">
						<li>
						RED.UA
						</li>
						<li>
						<a href="http://dev.red.org.ua/" >Головна</a>
						</li>
						<li>
						Личный кабинет
						</li>
						<li>
                <?php  if (!$this->ws->getCustomer()->getIsLoggedIn()) { 
			?>
			<a href = "/account/login/" >Войти</a>
                <?php }
				else { 
				?>
                 <a href="/account/logout/">Выход</a>
                <?php } ?>
						</li>
						<li>
						<a href="/basket/">Корзина</a>
						</li>
						<li>
						<?php  if ($this->ws->getCustomer()->getIsLoggedIn()) echo '<a href="/account/orderhistory/">Мои заказы</a>';?>
						</li>
						</ul>
						
						<ul class="nav navbar-nav">
						<li>
						О магазине
						</li>
						<?php
								
					$menus = wsActiveRecord::useStatic('TopMenu')->findAll();
					
                    foreach ($menus as $menu) {
                        $class = '';
						echo '<li>';
						
						
					echo '<a href="' . $menu->getUrl() . '"' . $class . '>' . $menu->getTitle() . '<span></span></a>';
                        echo '<span class="sep"></span>';
						echo '</li>';
                    }
					?>
					</ul>

</div>
		</div>
	</nav>