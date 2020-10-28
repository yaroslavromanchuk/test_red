<input hidden id="c_b" type="text" value="<?php if($_SESSION['count_basket'] > count($_SESSION['basket'])){ echo $_SESSION['count_basket']; }else{ echo count($_SESSION['basket']); }?>">
<div class="container pb-3">
<div class="row mx-auto bg-white p-3 card ">
	<div class="col-md-12 col-lg-10 col-xl-10 mx-auto p-1">
	
	<?php if (!$this->ws->getCustomer()->getIsLoggedIn()) { ?>
	<div class="alert alert-warning col-xs-6" style="float: none;text-align: center;margin: 0 auto 10px;" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<p style="color: black;margin: 5px;">Делали у нас заказ ранее?<br>Для продолжения войдите в свой личный кабинет.</p>
  <a href="#myModalLogin" role="button"  data-placement="left"  data-toggle="modal" style=" text-decoration: none;">
  <button class="btn btn-primary m" data-dismiss="alert"  aria-hidden="true">Войти</button>
	</a>
</div>
<?php } ?>

<?php
	if ($this->errors) {
		echo '
			<div class="col-xs-10 col-xs-offset-1">
				<div class="alert alert-danger">'. $this->trans->get("Вы не заполнили одно из полей").'<br>';
				foreach ($this->errors as $k => $v){
				if($k != 'error') echo '<span class="red">'.$v.'</span><br>'; 
				} 
				echo '</div> </div>';
	}
	if (isset($this->errors['error'])) {
		echo '
			<div class="col-xs-10 col-xs-offset-1">
				<div class="alert alert-danger">';
				foreach ($this->errors['error'] as $k => $v){
					 echo '<span >'.$v.'</span><br>'; 
					 }
					
			echo '</div>
			</div>
		';
	}
	if($this->err_m){
	echo '
			<div class="col-xs-10 col-xs-offset-1">
				<div class="alert alert-danger">';
				foreach ($this->err_m as $k => $v){
					 echo '<span >'.$v.'</span><br>'; 
					 }
					
			echo '</div>
			</div>
		';
	}
?>
            
<form method="post" action="" class="contact-form" name="basket_contacts" id="basket_contacts"  style="text-align: center;">
    
<div id="contact"  class="row">
    <div class="col-xs-12 col-sm-12"><h3  class="card-title text-center"><?=$this->trans->get('Контактные данные')?></h3></div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
		<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getMiddleName()) {
?>
					<input name="middle_name" type="text" placeholder="<?=$this->trans->get('Фамилия');?>"  id="middle_name"  required="" class="form-control <?php if (in_array('middle_name', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this->basket_contacts['middle_name'])) echo htmlspecialchars($this->basket_contacts['middle_name']); ?>"/>
<?php
				}
				else {
?>
					<input name="middle_name" type="text" placeholder="<?=$this->trans->get('Фамилия');?>"  id="middle_name"  required="" class="form-control <?php if (in_array('middle_name', $this -> errors, true)) echo " red";
						?>" value="<?=$this->ws->getCustomer()->getMiddleName(); ?>"/>
<?php
				}
 ?>
		</div> 
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
<?php if (!$this->ws->getCustomer()->getIsLoggedIn()) {?>
	<input name="name" type="text" id="name" required="" placeholder="<?=$this->trans->get('Имя');?>" class="form-control <?php if (in_array('name', $this -> errors, true)) echo " red";?>" value="<?php if (isset($this -> basket_contacts['name'])) echo htmlspecialchars($this -> basket_contacts['name']); ?>"/>
<?php }else{?>
	<input name="name" type="text" id="name" required="" placeholder="<?=$this->trans->get('Имя');?>" class="form-control<?php if (in_array('name', $this -> errors, true)) echo " red";?>" value="<?=$this->ws->getCustomer()->getFirstName(); ?>"/>
<?php } ?>
		</div>
		<div class="col-xl-12 form-group">
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getLastName()) { ?>
	<input name="last_name" type="text" placeholder="<?=$this->trans->get('Отчество');?>" id="last_name" class="form-control <?php if (in_array('last_name', $this -> errors, true)) echo " red";?>" value="<?php if (isset($this -> basket_contacts['last_name'])) echo htmlspecialchars($this -> basket_contacts['last_name']); ?>"/>
<?php }else{?>
	<input name="last_name" type="text" placeholder="<?=$this->trans->get('Отчество');?>" id="last_name" class="form-control <?php if (in_array('last_name', $this -> errors, true)) echo " red";?>" value="<?=$this->ws->getCustomer()->getLastName();?>"/>
<?php } ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="telephone" placeholder="38(000)000-00-00" maxlength="16" required="" id="telephone" type="tel" class="form-control<?php if (in_array('telephone', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['telephone'])) echo htmlspecialchars($this -> basket_contacts['telephone']); ?>"/><label  for="telephone"><span class="red" id="lb"></span></label>
<?php
				}
				else {
?>
					<input name="telephone" placeholder="38(000)000-00-00" maxlength="16" required="" id="telephone" type="tel" class="form-control<?php if (in_array('telephone', $this -> errors, true)) echo " red";
						?>" value="<?=$this->ws->getCustomer()->getPhone1(); ?>"/><label  for="telephone"><span class="red" id="lb"></span></label>
<?php
				}
?>

		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-group">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="email" placeholder="E-mail" type="email" id="email" class="form-control<?php if (in_array('email', $this -> errors, true)) echo " red";
						?>" value="<?php if (isset($this -> basket_contacts['email'])) echo htmlspecialchars($this -> basket_contacts['email']); ?>"/>
<?php
				}
				else {
?>
					<input name="email" placeholder="E-mail" type="email" id="email" class="form-control<?php if (in_array('email', $this -> errors, true)) echo " red";
						?>" value="<?=$this->ws->getCustomer()->getEmail(); ?>"/>
<?php
				}
?>
		</div>
		<div class="col-xl-12 form-group">
		<textarea name="comments" id="order-comment" rows="4" class="form-control" placeholder="<?php echo $this->trans->get('Коментарий к заказу');?>"></textarea>
		</div>
	<div class="col-xl-12 text-right my-3">
	<input type="button" class="btn btn-danger btn-lg" value="<?=$this->trans->get('Продолжить');?>" onclick="validate_form1();">
	</div>
	</div>
	<script>
	function validate_form1(){
	$('#delivery').hide();
	var valid = true;
	var name = $("#name");
	var middle_name = $("#middle_name");
	var email = $("#email");
	var phone = $("#telephone").val();
	
	   if(name.val() == "")
        {
				$('#ms').html("<?=$this->trans->get('Пожалуйста, заполните поле Ваше имя')?>").fadeIn(100);
				name.addClass("red");
				name.focus();
                valid = false;
				
        }else{ name.removeClass("red"); }

	 if(middle_name.val() == "")
        {
		$('#ms').html("<?=$this->trans->get('Пожалуйста, заполните поле Ваша фамилия')?>").fadeIn(100);
				middle_name.addClass("red");
				middle_name.focus();
                valid = false;
				
        }else{ middle_name.removeClass("red");  }
		
		if(!isValidEmailAddress(email.val())){
		$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните коректно поле Email')?>').fadeIn(100);
			 email.addClass("red");
			email.focus();
			 valid = false;
		
		}else{ email.removeClass("red"); }
		

	phone = phone.replace(/[^0123456789]/g,'');
	if(phone.length!=12){
					var x = 12-phone.length;
					var t ='<?=$this->trans->get('В номере телефона не хватает')?> '+x+' <?=$this->trans->get('цифр')?>.'; 
					$("#lb").text(t);
					 $('#telephone').addClass("red");
					 $('#telephone').removeAttr('style');
					valid = false;
					}else{ $('#telephone').removeClass("red"); }
					
					if(!isValidPhone(phone)){
		var t =' <?=$this->trans->get('В номере телефона присутствуют недопустимые символы.')?>'; 
			$("#lb").text(t);
			 $('#telephone').focus();
			 valid = false;
		}else{ $('#telephone').removeClass("red"); }
		
			if(valid == true){
			$('#ms').hide();
			$('#contact').hide();
			$('#delivery').show();
			$('.delivery_type').removeAttr('checked');
			$('.label_delivery').removeClass("active");
			}
        return valid;
}
 function isValidEmailAddress(emailAddress) {
    var pattern_email = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern_email.test(emailAddress);
    }

	 function isValidPhone(valid_phone) {
    var pattern_phone = new RegExp(/^[0-9]+$/);
    return pattern_phone.test(valid_phone);
    }

function delivery_form(){
		var val = true;
			
		if (!$('.delivery_type').is(':checked')){
                $('#ms').html("<?=$this->trans->get('Пожалуйста, выберите способ доставки')?>").fadeIn(100);
                val = false;
        }

		if($('#delivery_type_8').prop("checked")){ //нова почта 
			var city_np = $("#city_np");
			var sklad_np = $("#sklad");
		if(city_np.val() == "")
        {
				$('#ms').html("<?=$this->trans->get('Пожалуйста, заполните поле Город')?>").fadeIn(100);
				city_np.addClass("red");
				city_np.focus();
                val = false;
        }else{ city_np.removeClass("red"); }
		if(sklad_np.val() == "")
        {
				sklad_np.addClass("red");
				sklad_np.focus();
                val = false;
        }else{
		$('#sklad_np').val($('#sklad option:selected').data('id'));
		sklad_np.removeClass("red");
		}	
		}else if($('#delivery_type_4').prop("checked")){ //укр почта
			var obl1 =$("#obl");
			var city1 =$("#city");
			var rayon = $("#rayon");
			var street1 =$("#street")
			var house1 =$("#house");
			var flat1 =$("#flat");
				
				if(street1.val() == "")
        {
				$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните поле Улица')?>').fadeIn(100);
				street1.addClass("red");
				street1.focus();
                val = false;
        }else{  street1.removeClass("red");}
				if(house1.val() == "")
        {
				$('#ms').html('"<?=$this->trans->get('Пожалуйста, заполните поле Дом')?>"').fadeIn(100);
				house1.addClass("red");
				house1.focus();
                val = false;
        }else{  house1.removeClass("red");}
		if(flat1.val() == "")
        {
				$('#ms').html('"<?=$this->trans->get('Пожалуйста, заполните поле Квартира')?>"').fadeIn(100);
				flat1.addClass("red");
				flat1.focus();
                val = false;
        }else{ flat1.removeClass("red");}
			
			if(obl1.val() == "")
        {
				$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните поле Область')?>').fadeIn(100);
				obl1.addClass("red");
				obl1.focus();
                val = false;
        }else{ obl1.removeClass("red");}
			
		if(city1.val() == "")
        {
				$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните поле Город')?>').fadeIn(100);
				city1.addClass("red");
				city1.focus();
                val = false;
        }else{ city1.removeClass("red");}
		if(rayon.val() == "")
        {
                alert ( "<?=$this->trans->get('Пожалуйста, заполните поле Район')?>" ).fadeIn(100);
				$('#ms').html('');
				rayon.addClass("red");
				rayon.focus();
                val = false;
        }else{ rayon.removeClass("red");}
			
		}else if($('#delivery_type_9').prop("checked")){ //курьер
			var street = $("#s_street");
			var house = $("#house");
			var flat = $("#flat");

		if(street.val() == "")
        {
				$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните коректно поле улица. В появившемся списке выберите нужную Вам улицу.')?>').fadeIn(100);
				street.addClass("red");
				street.focus();
                val = false;
        }else{ street.removeClass("red");}
		
		if(house.val() == "")
        {
				$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните поле Дом')?>').fadeIn(100);
				house.addClass("red");
				house.focus();
                val = false;
        }else{ house.removeClass("red");}
		if(flat.val() == "")
        {
				$('#ms').html('<?=$this->trans->get('Пожалуйста, заполните поле Квартира')?>').fadeIn(100);
				flat.addClass("red");
				flat.focus();
                val = false;
        }else{ flat.removeClass("red");}

		}else if($('#delivery_type_3').prop("checked")){
			var count = $('#c_b').val();
			//console.log(count);
		 if(count > 5) { val = false;
$('#ms').html("<?=$this->trans->get('На пункт выдачи заказов можно заказать не более 5 единиц товара в одном заказе, у Вас в заказе')?> "+count+" <?=$this->trans->get('единиц. Удалите пожалуйста')?> "+(count-5)+"<?=$this->trans->get('единиц из заказа')?>!").fadeIn(100);
		//location.href = '/basket/';
		}
		}else if($('#delivery_type_18').prop("checked")){
                console.log('br');
                var city_jast = $("#city_justin");
			var sklad_jast = $("#branch");
		if(city_jast.val() == "")
        {
				$('#ms').html("<?=$this->trans->get('Пожалуйста, заполните поле Город')?>").fadeIn(100);
				city_jast.addClass("red");
				city_jast.focus();
                val = false;
        }else{ city_jast.removeClass("red"); }
		if(sklad_jast.val() == "")
        {
            $('#ms').html("<?=$this->trans->get('Пожалуйста, выберите отделение для доставки')?>").fadeIn(100);
				sklad_jast.addClass("red");
				sklad_jast.focus();
                val = false;
        }else{
		//sklad_jast.val($('#sklad option:selected').data('id'));
		sklad_jast.removeClass("red");
		}
                }
                if($('#delivery_type_5').prop("checked")){
               // alert('error');
                val = false;
                }
		if(val === true){
		$('#go_f').show();
		$('#ms').hide();
		$('#contact').hide();
		$('#delivery').hide();
		$('#dop_mat').hide();
		$('#pay').show();
		$('#sog').show();
		$('.label_payment').removeClass("active"); 
		$('.payment_method').removeAttr('checked');
		}
		return val;
	}
	function obratno1(){
			$('#delivery').hide();
			$('#contact').show();
			$('.dop_fields').hide();
			$('#dop_mat').hide();
			$('#pobedy').hide();
			$('#stroitely').hide();
			//$('#mishugi').hide();
			return true;
	}
</script>
<div class="row form-group" id="delivery" style="display:none;" >
		<div class="col-xl-12" >
		<h3  class="card-title text-center"><?=$this->trans->get('Выберите способ доставки')?></h3>
					<div class="btn-group" data-toggle="buttons"> 
					<ul class="backet_ul" align="center">
<?php foreach(wsActiveRecord::useStatic('DeliveryType')->findAll(array('active_user'=> 1), array('sort'=>'ASC')) as $dely){ if($dely->getId() == 16){ continue;} ?>
					<li>
					<label  class="btn btn-default label_delivery" for="delivery_type_<?=$dely->getId()?>">
					<div class="media">
					<input hidden class="delivery_type" name="delivery_type_id" id="delivery_type_<?=$dely->getId()?>" value="<?=$dely->getId();?>" type="radio" >
					<img class="align-self-center mr-2"  src="<?=$dely->getImg()?>"/>
					<div class="media-body"><p class="my-auto"><?=$dely->getName()?></p></div>
					</div>
					</label>
					</li>
					<?php }?>
					</ul>
					</div>
		</div>
	</div>
<p style="display:none;text-align: center;width: 100%;" class="alert alert-danger" id="ms"></p>
	<div class="row form-group" id="dop_mat"  style="display:none;">

		<div class="col-xs-10 col-md-12 col-lg-12  dop_fields" 
		style="<?php if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4, 9, 8))) echo 'display: none;'; ?>">
			<div class="panel panel-default">
				<div class="row panel-body">
	<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group up" style="<?php if ($this->basket_contacts['delivery_type_id'] == 4) echo 'display: none;'; ?>" >
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getIndex()) { ?>
							<input name="index" id="index" type="text" placeholder="<?=$this->trans->get('Индекс')?>" class="form-control<?php if (in_array('index', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['index'])) echo htmlspecialchars($this -> basket_contacts['index']); ?>"/>
<?php }else { ?>
							<input name="index" id="index" type="text" placeholder="<?=$this->trans->get('Индекс')?>" class="form-control<?php if (in_array('index', $this -> errors, true)) echo " red";
								?>" value="<?=$this->ws->getCustomer()->getIndex(); ?>"/>
<?php } ?>
					</div>
					<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group up " style="<?php//np
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4,8))) echo 'display: none;'; ?>" id="oblast">
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getObl()) { ?>
							<input name="obl" id="obl" type="text" placeholder="<?=$this->trans->get('Область')?>" class="form-control<?php if (in_array('obl', $this -> errors, true)) echo " red"; ?>" value="<?php if (isset($this -> basket_contacts['obl'])) echo htmlspecialchars($this -> basket_contacts['obl']); ?>"/>
<?php }else { ?>
							<input name="obl" id="obl" type="text" placeholder="<?=$this->trans->get('Область')?>" class="form-control<?php if (in_array('obl', $this -> errors, true)) echo " red"; ?>" value="<?=$this->ws->getCustomer()->getObl(); ?>"/>
<?php } ?>
					</div>
		<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group up" style="<?php if ($this->basket_contacts['delivery_type_id'] == 4) echo 'display: none;'; ?>" >			
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getRayon()) { ?>
							<input name="rayon" id="rayon" type="text" placeholder="<?=$this->trans->get('Район')?>" class="form-control<?php if (in_array('rayon', $this -> errors, true)) echo " red"; ?>" value="<?php if (isset($this -> basket_contacts['rayon'])) echo htmlspecialchars($this -> basket_contacts['rayon']); ?>"/>
<?php }else{?>
							<input name="rayon" id="rayon" type="text" placeholder="<?=$this->trans->get('Район')?>" class="form-control<?php if (in_array('rayon', $this -> errors, true)) echo " red";
								?>" value="<?=$this->ws->getCustomer()->getRayon(); ?>"/>
<?php }?>
					</div>
<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group np" id="sity1" style="<?php if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4,8))) echo 'display: none;';?>" >
	<img class="img_gif" id="k_np_g"  src="/img/delivery/loading.gif" />
	<input name="city_np" type="text" class="form-control <?php if (in_array('city_np', $this -> errors, true)) echo " red";?>"  placeholder="<?=$this->trans->get('Город')?>"  id="city_np" value="" onclick="$('#sklad').fadeOut(50); return false;" />
	<input type="text" style="display:none;" name="cityx"  id="cityx"  >
	<div class="help" id="h_np"><?=$this->trans->get('Начните ввод Города и выберите из списка')?></div>
</div>
<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group k" id="s_street_block" > 
			<img class="img_gif" id="k_s_g"  src="/img/delivery/loading.gif" />
			<input name="s_street" id="s_street" type="text"  placeholder="<?=$this->trans->get('Улица')?>" class="form-control<?php if (in_array('s_street', $this -> errors, true)) echo " red"; ?>" value=""/>
			<div class="help" id="h_s"><?=$this->trans->get('Начните ввод улицы и выберите со списка')?></div>
			<input type="hidden" id="s_street_id" name="s_street_id">
		</div>
<div class="col-xs-12 col-sm-12  col-md-12 col-md-12 col-lg-12 col-xl-12 justin"  > 
			<div class="form-group text-left">
                    <label class="form-control-label">Город: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="city_justin" id="city_justin" data-placeholder="Выберите город" required="false" tabindex="-1" >
                         <option label="Выберите город"></option>
                    </select>
                </div>
		</div>
                    <div class="col-xs-12 col-sm-12  col-md-12 col-md-12 col-lg-12 col-xl-12  justin"  > 
			<div class="form-group text-left">
                    <label class="form-control-label">Отделение: <span class="tx-danger">*</span></label>
                    <select class="form-control select2" name="branch" id="branch" data-placeholder="Выберите Отделение" required="false" tabindex="-1" >
                        <option label="Выберите Отделение"></option>
                        
                    </select>
                </div>
		</div>
<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group up" id="sity" style="<?php//np
						if (!in_array($this->basket_contacts['delivery_type_id'], array(4,8)))
							echo 'display: none;';
					?>" >
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getCity()) { ?>
							<input name="city" id="city" type="text" placeholder="<?=$this->trans->get('Город')?>" class="form-control<?php if (in_array('city', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['city'])) echo htmlspecialchars($this -> basket_contacts['city']); ?>"/>
<?php } else { ?>
							<input name="city" id="city" type="text" placeholder="<?=$this->trans->get('Город')?>" class="form-control<?php if (in_array('city', $this -> errors, true)) echo " red"; ?>" value="<?=$this->ws->getCustomer()->getCity(); ?>"/>
<?php } ?>
					</div>
<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group  up" id="strit" style="<?php//np
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4,8)))
							echo 'display: none;';
					?>">
<?php if (!$this->ws->getCustomer()->getIsLoggedIn()|| !$this->ws->getCustomer()->getStreet()) { ?>
							<input name="street" id="street" type="text" placeholder="<?=$this->trans->get('Улица')?>" class="form-control<?php if (in_array('street', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['street'])) echo htmlspecialchars($this -> basket_contacts['street']); ?>"/>
<?php } else { ?>
							<input name="street" id="street" type="text" placeholder="<?=$this->trans->get('Улица')?>" class="form-control<?php if (in_array('street', $this -> errors, true)) echo " red";
								?>" value="<?php echo $this->ws->getCustomer()->getStreet(); ?>"/>
<?php } ?>
					</div>
<div class="col-xs-12 col-sm-12  col-md-6 col-md-6 col-lg-6 col-xl-6 form-group up k" id="dom" style="<?php
						if (!in_array(@$this->basket_contacts['delivery_type_id'], array(4,9)))
							echo 'display: none;';
					?>">
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getHouse()) { ?>
							<input name="house" id="house" type="text" placeholder="<?=$this->trans->get('Дом')?>" class="form-control<?php if (in_array('house', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['house'])) echo htmlspecialchars($this -> basket_contacts['house']); ?>"/>
<?php } else { ?>
							<input name="house" id="house" type="text" placeholder="<?=$this->trans->get('Дом')?>" class="form-control<?php if (in_array('house', $this -> errors, true)) echo " red"; ?>" value="<?php echo $this->ws->getCustomer()->getHouse(); ?>"/>
<?php } ?>
					</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-lg-6 col-xl-6 form-group up k" id="kvartira" style="<?php
						if (!in_array($this->basket_contacts['delivery_type_id'], array(4,9)))
							echo 'display: none;'; ?>">
<?php if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getFlat()) { ?>
							<input name="flat" id="flat" type="text" placeholder="<?=$this->trans->get('Квартира')?>" class="form-control<?php if (in_array('flat', $this -> errors, true)) echo " red";
								?>" value="<?php if (isset($this -> basket_contacts['flat'])) echo htmlspecialchars($this -> basket_contacts['flat']); ?>"/>
<?php } else { ?>
							<input name="flat" id="flat" type="text" placeholder="<?=$this->trans->get('Квартира')?>" class="form-control<?php if (in_array('flat', $this -> errors, true)) echo " red"; ?>" value="<?php echo $this->ws->getCustomer()->getFlat(); ?>"/>
<?php } ?>
					</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-md-6 col-lg-6 col-xl-6 form-group np" style="<?php
						if (!in_array($this->basket_contacts['delivery_type_id'], array(8, 16))) echo 'display: none;'; ?>">
<?php
if(true){
 ?>
 <label id="sklad_np_leb" style="display:none;color: #999;"><img class="img_gif" id="k_np_w" style="top: 0px;left: -10px;"  src="/img/delivery/loading.gif" /><?=$this->trans->get('Идет поиск отделений')?>...</label>
<select class="form-control<?php if (in_array('sklad', $this -> errors, true)) echo " red";?>" id="sklad" name="sklad" style="display:none;"></select>
<input type="text" id="sklad_np" name="sklad_np"  style="display:none;" />
<?php }else { if (!$this->ws->getCustomer()->getIsLoggedIn() || !$this->ws->getCustomer()->getSklad()) { ?>
				<input name="sklad" id="sklad" type="text" placeholder="<?=$this->trans->get('Склад')?>" class="form-control<?php if (in_array('sklad', $this -> errors, true)) echo " red"; ?>" value="<?php if (isset($this -> basket_contacts['sklad'])) echo htmlspecialchars($this -> basket_contacts['sklad']); ?>"/>
					<?php } else { ?>
					<input name="sklad" id="sklad" type="text" placeholder="<?=$this->trans->get('Склад')?>" class="form-control<?php if (in_array('sklad', $this -> errors, true)) echo " red"; ?>" value="<?php echo $this->ws->getCustomer()->getSklad(); ?>"/>
<?php } } ?>
					</div>
				</div>
			</div>
		</div>
<p  class="alert alert-success k_type1 d-none w-100" >
    <span style="font-size:  16px;font-weight:  bold;">Мы рады что Вы с нами, и доставим Ваш заказ совершенно БЕСПЛАТНО</span>
    <!--<br>Изменения стоимости курьерской доставка. С 03.09.2019 стоимость доставки составляет 65 грн.<br>Бесплатная доставка при заказе от 900 грн.<br> За дополнительной информацией обращайтесь в Колл.центр (044) 224-40-00-->
</p>
<div class="alert alert-danger m_type col-sm-12" >
    <h4 class="alert-heading">Обратите внимание!</h4>
     <p>По техническим причинам, доставка в пункт самовывоза ул.Строителей 40, <b>ВРЕМЕННО НЕДОСТУПНА</b>.<br>В связи с этим, мы предлагаем Вам оформить доставку курьером по Киеву. Мы доставим Ваши заказы <b>БЕСПЛАТНО</b>.</p> 
     <hr>
     <p>За дополнительной информацией обращайтесь в Колл.центр <a href="tel:+380442244000">+38(044) 224-40-00</a></p>
</div>

<div class="col-sm-12 sv only_sv" id="pobedy" style="<?php if ($this->basket_contacts['delivery_type_id'] != 3){ echo 'display: none;';} ?> " >
			<div class="panel panel-default sv only_sv">
				<div class="row panel-body" style="background: white;">
                                    <div class=" col-sm-12 alert alert-primary" role="alert">
                                        <h4 class="alert-heading">Обратите внимание!</h4>
<p>Для получения заказа в пункте самовывоза, его нужно оплатить в полном размере, после чего Вы сможете посмотреть и примерить товар. <br>Если товар не подошел, Вы сможете оформить возврат не выходя с пункта самовывоза или в течении 14 дней, и получить деньги обратно.</p>
                                    </div>
					<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 form-group sv only_sv">
						<?=$this->trans->get('<p>г. Киев</p>
							<p>проспект Победы, 98/2</p>
							<p>между метро "Нивки" и "Святошино"</p>
							<p>пн-вс: 10:00-22:00</p>
							<p>(093) 854 23 53</p>
							<p>(067) 406 90 80</p>')?>
					</div>
					<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 sv only_sv">
						<iframe src="https://maps.google.com.ua/maps?f=q&source=s_q&hl=ru&geocode=&q=%D0%B3.+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%BF%D1%80%D0%BE%D1%81%D0%BF%D0%B5%D0%BA%D1%82+%D0%9F%D0%BE%D0%B1%D0%B5%D0%B4%D1%8B,+98%2F2&aq=&sll=50.49355,30.460997&sspn=0.006935,0.021136&gl=ua&ie=UTF8&hq=&hnear=%D0%BF%D1%80%D0%BE%D1%81%D0%BF.+%D0%9F%D0%BE%D0%B1%D0%B5%D0%B4%D1%8B,+98%2F2,+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9A%D0%B8%D0%B5%D0%B2&t=m&ll=50.465263,30.396852&spn=0.016391,0.04283&z=14&iwloc=A&output=embed" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen ></iframe>
					</div>
				</div>
			</div>
</div>
<div class="col-xl-12 sv only_sv" id="stroitely" style="<?php if ($this->basket_contacts['delivery_type_id'] != 5) {echo 'display: none;';} ?> " >
			<div class="panel panel-default sv only_sv">
				<div class="row panel-body" style="background: white;">
					<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 form-group sv only_sv">
						<?=$this->trans->get('<p>г. Киев</p>
							<p>ул.Строителей, 40</p>
							<p>ТЦ "DOMA", 2 этаж</p>
							<p>пн-вс: 10:00-22:00</p>
							<p>(063) 010 34 53</p>
							<p>(098) 634 26 82</p>')?>
					</div>
					<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 sv only_sv">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d500.4270632597404!2d30.611137329965956!3d50.454703823112624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6f6224f101f87811!2sRed!5e0!3m2!1sru!2sua!4v1517309778043" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	<div class="col-xl-12" style="margin-top: 10px;">
	<input type="button" class="btn btn-danger btn-lg" value="<?=$this->trans->get('Назад');?>" onclick="obratno1();" style="float: left;border-color: #969696;background: #969696;">
	<input type="button" class="btn btn-danger btn-lg"  id="go_to_pay" value="<?=$this->trans->get('Продолжить');?>" onclick="delivery_form();" style="float: right;">
	<br>
		</div>
	</div>

<div class="row form-group payment_panel" id="pay" style="display:none;">
		<div class="col-xs-12 col-md-12 col-lg-12" >
		<h3 class="card-title text-center"><?=$this->trans->get('Выберите способ оплаты')?></h3>
		<!--<p  class="alert alert-danger" ><?php //echo $this->trans->get('По техническим причинам, онлайн оплата временно не доступна. Приносим свои извинения за временные неудобства'); ?>.</p>-->
<?php if($this->ws->getCustomer()->isBlockNpN()) { ?>
<p id="text_np" class="text_np alert alert-danger" style="display: none;">
    <?=$this->trans->get('В связи с нарушением условий оплаты заказов, наложенный платеж для Вас временно не доступен.<br>Вы можете оплачивать заказы мгновенно онлайн с помощью Visa MasterCard и Приват24.
					<br>За детальной информацией обращайтесь в Call центр.<br>')?>
					</p>
<?php } if($this->ws->getCustomer()->isBlockCur()){ ?>
<p id="text_kur" class="text_kur alert alert-danger" style="display: none;"><?=$this->trans->get('В связи с нарушением оплаты курьерских заказов, оплата наличными для Вас временно не доступна.
					<br>Вы можете оплачивать заказы мгновенно онлайн с помощью Visa MasterCard и Приват24.
					<br>За детальной информацией обращайтесь в Call центр.<br>')?></p>
<?php } if($this->ws->getCustomer()->isBlockM()){ ?>
<p  id="text_mag" class="text_mag alert alert-danger" style="display: none;">
    <?=$this->trans->get('В связи с нарушением условий выкупа заказов с пункта выдачи, оплата наличными для Вас временно не доступна.')?></p>
<?php } if($this->ws->getCustomer()->isBlockOnline()){ ?>
<p  class="alert alert-danger" ><?=$this->trans->get('По техническим причинам, онлайн оплаты, временно недоступны. Приносим свои извинения за временные неудобства.')?></p>
    <?php }
    if($this->ws->getCustomer()->isBlockJustin()){ ?>
        <p  id="text_justin" class="text_justin alert alert-danger" style="display: none;">
        <?=$this->trans->get('В связи с нарушением условий выкупа заказов, оплата наличными для Вас временно не доступна.
					<br>Вы можете оплачивать заказы мгновенно онлайн с помощью Visa MasterCard и Приват24.
					<br>За детальной информацией обращайтесь в Call центр.<br>')?></p>
        <?php } ?>
<div class="btn-group payment_method_container"  data-toggle="buttons"  >
<ul class="backet_ul" align="center">
<li>
<label id="l_nl" class="btn btn-default k_p s_m_p s_p_p jastin_p label_payment 
<?php 
if($this->ws->getCustomer()->isBlockCur()) { echo " hide_kur";}
if($this->ws->getCustomer()->isBlockM()){ echo " hide_mag";}
if($this->ws->getCustomer()->isBlockJustin()){ echo " hide_justin";} ?>" 
style="<?php if (!in_array($this->basket_contacts['delivery_type_id'], array(3,9,18))){ echo ' display: none;';} ?>">
							<div class="media">
							<input class="payment_method" hidden name="payment_method_id" id="payment_method_1" value="1" type="radio" autocomplete="on">
							<img class="align-self-center mr-2" src="/img/delivery/uah.png"/>
							<div class="media-body"><span class="align-self-center"><?=$this->trans->get('При получении')?></span></div>
							</div>
						</label>
</li>
<?php if (!$this->ws->getCustomer()->isBlockNpN()){ ?>
<li>
<label id="l_np" class="btn btn-default np_p up_p label_payment " style="<?php
		if (!in_array($this->basket_contacts['delivery_type_id'], array(4,8))){echo ' display: none;';}?>">
		<div class="media">
			<input class="payment_method" hidden name="payment_method_id" id="payment_method_3" value="3" type="radio" autocomplete="on">
							<img class="align-self-center mr-2" src="/img/delivery/npuah.png">
							<div class="media-body"><?=$this->trans->get('Наложенный платеж')?></div>
		</div>
						</label> 
</li>
<?php }
//if($this->ws->getCustomer()->getId() == 8005){
if(false){
 ?>
<li>
<label id="l_o" class="btn btn-default k_p s_m_p s_p_p np_p up_p label_payment"  style="<?php
						if (!in_array($this->basket_contacts['delivery_type_id'], array(4, 8, 9, 3)))
                                                {echo 'display: none;';} ?>">
							<div class="media">
							<input class="payment_method" hidden name="payment_method_id" id="payment_method_7" value="7" type="radio" autocomplete="on">
							<img class="align-self-center mr-2" src="/img/delivery/vm.png"/>
							<div class="media-body" >Онлайн оплата<br>(тестовый режим)</div>
							</div>
						</label>
</li>
<?php } ?>
<?php if(!$this->ws->getCustomer()->isBlockOnline() and Config::findByCode('pay_online')->getValue()){ ?>
<li>
<label id="l_vs" class="btn btn-default k_p s_m_p s_p_p np_p up_p jastin_p label_payment "  style="<?php
	if (!in_array($this->basket_contacts['delivery_type_id'], array(4, 8, 9, 3,18))){echo 'display: none;';} ?>">
							<div class="media">
							<input class="payment_method" hidden name="payment_method_id" id="payment_method_4" value="4" type="radio" autocomplete="on">
							<img class="align-self-center mr-2" src="/img/delivery/vm.png"/>
							<div class="media-body" >Онлайн<br>Visa/MasterCard</div>
							</div>
						</label>
</li>

<li>
<label id="l_privat" class="btn btn-default k_p s_m_p s_p_p np_p up_p jastin_p label_payment" style="<?php
						if (!in_array($this->basket_contacts['delivery_type_id'], array(4, 8, 9, 3,18))) {echo 'display: none;';} ?>">
					<div class="media">
					<input class="payment_method" hidden name="payment_method_id" id="payment_method_6" value="6" type="radio" autocomplete="on">
					<img class="align-self-center mr-2"  src="/img/delivery/p24.png"/>
							<div class="media-body" >Онлайн<br>Приват24</div>
							</div>
						</label>
</li>  
<!--
<li>
<label id="l_wb" class="btn btn-default k_p s_m_p s_p_p np_p up_p label_payment"  style="<?php
						if (!in_array($this->basket_contacts['delivery_type_id'], array(4, 8, 9, 5, 3)))
							echo 'display: none;'; ?>">
							<div class="media">
							<input class="payment_method" hidden name="payment_method_id" id="payment_method_5" value="5" type="radio" autocomplete="on">
							<img class="align-self-center mr-2"  src="/img/delivery/wm.png"/>
							<div class="media-body" >WebMoney</div>
							</div>
						</label>
</li>-->
<?php } ?>
</ul>
</div>
	<!-- дополнительный текст о доставке-->
	<div class="row" id="dop_t" style="display:none;" >
		<div class="col-xs-12 col-md-12 col-lg-12">
			<div class="alert alert-info" role="alert" id="d_text" style="margin: 10px 0px 0px 0px;padding: 10px;"></div>
		</div>
	</div>
<!-- /дополнительный текст о доставке-->
		</div>
	</div>

<!-- соглашение + заказать-->
	<div class="row" id="sog" style="display:none;" >
	<div class="col-xs-12 col-md-12 col-lg-12 np_np" style="display: none; margin-bottom: 15px; 
	<?php if (!in_array($this->basket_contacts['delivery_type_id'], array(8,16))){ echo 'display: none;'; }?>">
	<?=$this->trans->get('<span style="color: red;">Посылку Вы оплачиваете в отделении Новой Почты. Для оформления возврата у Вас есть 14 дней. <a href="/returns/" target="_blank">Условия возврата.</a></span>')?>
	</div>
	<div class="col-xs-12 col-md-12 col-lg-12">
		<div class="panel panel-default">
			<div class="panel-body">
			<div class="checkbox">
	<label><input type="checkbox" name="soglas" id="soglas" checked> <?=$this->trans->get('Я выражаю согласие на обработку моих персональных данных')?></label>
				</div>
		<div class="checkbox">
		<label><input type="checkbox" name="oznak" id="oznak"> <?=$this->trans->get('Я ознакомлен с <a href="/terms/" target="_blank">условиями предоставления услуг</a> интернет-магазина RED.ua"')?></label>
		   </div>
		 </div>
		</div>
	</div>
<div class="col-xs-12 col-md-12 col-lg-12 text-right z_m">
		<table style="width:100%;margin-bottom: 10px;">
		<tr>
		<td style="width:50%;">
		<br>
	<input type="button" class="btn btn-danger btn-lg" value="<?=$this->trans->get('Назад');?>" onclick="obratno2();" style="float: left;border-color: #969696;background: #969696;">
	</td>
	<td style="width:50%;text-align: right;">
	<br>
	<input  style=" float: right;"  id="go_f" type="button" class="btn btn-danger btn-lg"   onclick="validate_form_uslug();" value="<?=$this->trans->get('Заказать');?>" >
	<input style="display:none; float: right;" id="go" type="submit" class="btn btn-danger btn-lg" onclick="document.forms.basket_contacts.submit(); return false;" <?=$this->trans->get('Заказать');?>>
	<br>
	</td>
	</tr>
	</table>
		</div>
	</div>
	<!-- /соглашение + заказать-->
</form>
</div>
</div>
</div>
<script>
    
function validate_form_uslug (){
var valid_uslug = true;
	if(document.getElementById('oznak').checked == false){
	valid_uslug = false;
	$('#ms').html('<?=$this->trans->get('Вы не отметили что ознакомлены с условиями предоставления услуг ФОП "Цыбуля".')?>').fadeIn(100);
	$('#oznak').focus();
	}else if(document.getElementById('soglas').checked == false){
	valid_uslug = false;
	$('#ms').html('<?=$this->trans->get('Вы не отметили что выражаете согласие на обработку Ваших персональных данных.')?>').fadeIn(100);
	$('#soglas').focus();
	}else if( $('.payment_method').is(":checked") && $('.payment_method:checked').val() > 0){
	$('#ms').css({'background-color' : '#fff' , 'border-color' : '#ededed'});
	$('#ms').html('<img style="width:100px;" src="/img/loading_trac.png">').fadeIn(100);
	document.forms.basket_contacts.submit();
	}else{
	valid_uslug = false;
	$('#ms').html('<?=$this->trans->get('Выберите способ оплаты.')?>').fadeIn(100);
	}
	return valid_uslug;
}
	function obratno2(){
	$('#dop_t').hide();
			$('#ms').hide();
			$('#delivery').show();
			$('#contact').hide();
			$('#pay').hide();
			$('#go_f').hide();
			$('#sog').hide();
			$('.delivery_type').removeAttr('checked');
			$('.label_delivery').removeClass("active");
	}


			$(function() {
                           // $('.select2').parsley();
                            'use strict'
			//np
			$("#city_np").focus(function (){ $('#h_np').fadeIn(300);}); 
				$("#city_np").blur(function (){$('#h_np').fadeOut(300); });
			
			$("#s_street").focus(function (){ $(this).next('div #h_s').fadeIn(300);}); 
				$("#s_street").blur(function (){$(this).next('div #h_s').fadeOut(300); });

			//var f = 'c';
				$('.delivery_type').change(function() {
				$('#ms').hide();
	   var delivery = $('.delivery_type:checked').val();
					
					$('.mish_type').hide();
					$('.m_type').hide();
                                        $('.k_type').hide();
					$('.dop_fields').hide();
					$('.s_m_p').hide();
					$('.s_p_p').hide();
					$('.np').hide();
					$('.np_p').hide();
					$('.np_np').hide();
					$('.up').hide();
					$('.up_p').hide();
                                        $('.jastin_p').hide();
					$('.k').hide();
                                        $('.justin').hide();
					$('.k_p').hide();
					$('#pobedy').hide();
					$('#stroitely').hide();
					//$('#mishugi').hide();
				$('#dop_mat').show();
                                $('#go_to_pay').show();
                                
                                switch(delivery){
                                    case '4': 
                                                $('.np_np').hide();
						$('#pobedy').hide();
						$('#stroitely').hide();
						$('#dostav').show();
						$('.dop_fields').show();
						$('.up').show(); 
						$('.up_p').show();
						$('.hide_np').hide();
						$('.text_np').show();
						$('.text_kur').hide();
						$('.text_mag').hide();
                                                $('.text_justin').hide();
                                    break;
                                    case '8': 
                                                $('#pobedy').hide();
						$('#stroitely').hide();
						$('#dostav').show();
						$('.dop_fields').show();
						$('.np').show();
						$('.np_p').show();
						$('.hide_np').hide();
						$('.text_np').show();
						$('.text_kur').hide();
						$('.text_mag').hide();
                                                $('.text_justin').hide();
                                    break;
                                    case '9': 
                                                $('.k_type').show();
						$('.np_np').hide();
						$('#pobedy').hide();
						$('#stroitely').hide();
						$('#dostav').show();
						$('.dop_fields').show();
						$('.k_p').show();
						$('.k').show();
						$('.hide_kur').hide();
						$('.text_kur').show();// console.log(delivery);
						$('.text_mag').hide();
                                                $('.text_justin').hide();
						$('.text_np').hide();
                                    break;
                                    case '18':                                            
       $.ajax({
         type: 'POST',
         url: '/shop/justin/',
         dataType: 'json',
         data: {metod: 'city'},
         success: function(data) {
             $('#branch').empty();
             $('#city_justin').empty().select2({data : data});
         }
     });
                                            $('.justin').show();
                                            $('#dostav').show();
                                            $('.dop_fields').show();
                                            $('.jastin_p').show();
                                            $('.hide_justin').hide();
                                            $('.text_justin').show();
                                            $('.text_mag').hide();
                                    break;
                                    case '3': 
                                            $('.np_np').hide();
                                            $('#pobedy').show();
                                            $('.s_p_p').show();
                                            $('.hide_mag').hide();
                                            $('.text_mag').show();
                                            $('.text_kur').hide();
                                            $('.text_justin').hide();
                                            $('.text_np').hide();
                                    break;
                                    default: break;
                                }
		});
        $('#city_justin').on("select2:select", function(e){ console.log('sel'); refresh(e);});
        $('#city_justin').on("select2:unselect", function(){  $('#branch').empty(); });
              
$('#s_street').autocomplete({
	source: '/shop/getmistcity/?what=street',
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
			$('#k_s_g').fadeIn(500);
			},
			select: function (event, ui) {
			$('#k_s_g').fadeOut(300);
			$('.k').show();
			}
				});	
	//npva pochta	city
   $( "#city_np" ).autocomplete({
     minLength: 2,
    maxHeight: 100,
    deferRequestBy: 100,
      source: function(request, response){
           var lang = "<?=Registry::get('lang')?>";
         if(lang == 'uk'){ lang  = 'ua';}
        // организуем кроссдоменный запрос 
                let np = new NP('1e594a002b9860276775916cdc07c9a6', lang);
                    var respons = np.getCities(0, request.term);
                    
                    if(respons.success){
                        response($.map(respons.data, function(item){
              return{
                label: item.DescriptionRu,
                value: item.DescriptionRu,
                id: item.Ref
              }
            }));
                    }else{
                        console.log(respons);
                    }
      },
    select: function (event, ui) {
            if (ui.item == null) {
		$('#cityx').val('');
            } else {
		$('#cityx').val(ui.item.id);
		myNP(ui.item.id);
	
	}
            }
    });	
	//получение отделений
			$('.payment_method').change(function() {
				var delivery = $('.delivery_type:checked').val();
				var payment = $('.payment_method:checked').val();
					
				if((delivery == 8 || delivery == 16) && payment == 3){$('.np_np').show(); }else{$('.np_np').hide();}	
				if(delivery == 4) { $('#d_text').html('<?=$this->trans->get('Отправка заказов Укрпочтой - вторник и четверг.');?>'); $('#dop_t').show();	}
				if(delivery == 8 || delivery == 16) { $('#d_text').html("<?=$this->trans->get("Отправка заказов Новой Почтой - понедельник, среда и пятница. Срок хранения заказов на Новой Почте 5 дней, с момента получения смс о доставке в отделение.");?>"); $('#dop_t').show();}
				if(delivery == 3){ $('#d_text').html('<?=$this->trans->get('Доставка в пункты самовывоза с понедельника по пятницу.');?>'); $('#dop_t').show(); }	 //
				if(delivery == 9) { $('#d_text').html("<?=$this->trans->get("После проверки заказа менеджер свяжется с Вами");?>"); $('#dop_t').show(); }
								});	
                                                                $('.select2').select2();
				
}); //exit ready
	
         function refresh(evt){
        if(!evt){
            var args = {};
        }else{
            //console.log(evt.params.data);
            var args = evt.params.data;
        }
        $.ajax({
         type: 'POST',
         url: '/shop/justin/',
         dataType: 'json',
         data: {id:args.id, metod: 'search_depart'},
         success: function(data) {
             $('#branch').empty().select2({data : data});
             //console.log('Yay! '+data);
         }
     });
        }
 function myNP (x) {
     var lang = "<?=Registry::get('lang')?>";
         if(lang == 'uk'){ lang  = 'ua';}
         let np = new NP('920af0b399119755cbca360907f4fa60', lang);
        var response = np.getWarehouses(x, 0);
                   
                    if(response.success){
    var text = '';
    for(var k in response.data){
        if(lang == "ru"){
        text+='<option data-id ="'+response.data[k].Ref+'" value="'+response.data[k].DescriptionRu+'">'+response.data[k].DescriptionRu+'</option>';
    }else{
        text+='<option data-id ="'+response.data[k].Ref+'" value="'+response.data[k].Description+'">'+response.data[k].Description+'</option>';
    }
    }
    }else{
        text += '<option data-id ="'+response.data[k].Ref+'" value="'+response.data[k].Description+'">'+response.data[k].Description+'</option>';
        return response.errors;
    }
    // console.log(response);
     $('#sklad').html(text);
                    $('#k_np_g').fadeOut(1);
                    $('#sklad_np_leb').fadeOut(1);
                    $('#sklad').fadeIn(10);
		return false;
}			
</script>