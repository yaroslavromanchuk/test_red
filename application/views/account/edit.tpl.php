<script type="text/javascript" src="/js/call/jquery.mask.js"></script> 
<script>
$(function($){ $("#phone").mask("38(999)999-99-99");});
$(function($){ $("#date_birth").mask("99-99-9999");});

</script> 
<p style="font-size:16px;font-weight: bold;" align="center">Редактирование</p>
  <?php  if (count($this->errors)){
    foreach($this->errors as $error){
    echo '<p><span style="font-size: 16px;color: red; font-weight: bold;">'.$error.'</span></p>';
    }
} ?>
<form method="post" action="" class="contact-form" name="account_edit" id="account_edit">  
<table cellspacing="0" cellpadding="4" class="basket-cont view" align="center" style="font-size: 14px;">
    <tbody><tr>
            <td class="info">Компания</td>
            <td><input type="text" value="<?=$this->user->getCompanyName()?>"  class="form-control" name="company"></td>
        </tr>
        <tr>
            <td class="info">Имя</td>
            <td><input type="text" value="<?=$this->user->getFirstName()?>" class="form-control" name="name"></td>
        </tr>
        <tr>
            <td class="info">Фамилия</td>
            <td><input type="text" value="<?=$this->user->getMiddleName()?>" class="form-control" name="middle_name"></td>
        </tr>
        <tr>
            <td class="info">Отчество</td>
            <td><input type="text" value="<?=$this->user->getLastName()?>" class="form-control" name="last_name"></td>
        </tr>
        <tr>
            <td class="info">Индекс</td>
            <td><input type="text" value="<?=$this->user->getIndex()?>" class="form-control" name="index"></td>
        </tr>
        <tr>
            <td class="info">Область</td>
            <td><input type="text" value="<?=$this->user->getObl()?>" class="form-control" name="obl">
            	<input type="hidden" value="<?=$this->user->getAdress()?>" class="form-control" name="address">
            </td>
        </tr>
        <tr>
            <td class="info">Район</td>
            <td><input type="text" value="<?=$this->user->getRayon()?>" class="form-control" name="rayon"></td>
        </tr>
        <tr>
            <td class="info">Город</td>
            <td><input type="text" value="<?=$this->user->getCity()?>" class="form-control" name="city"></td>
        </tr>
        <tr>
            <td class="info">Улица</td>
            <td><input type="text" value="<?=$this->user->getStreet()?>" class="form-control" name="street"></td>
        </tr>
        <tr>
            <td class="info">Дом</td>
            <td><input type="text" value="<?=$this->user->getHouse()?>" class="form-control" name="house"></td>
        </tr>
        <tr>
            <td class="info">Квартира</td>
            <td><input type="text" value="<?=$this->user->getFlat()?>" class="form-control" name="flat"></td>
        </tr>
		<?php if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->isNoActive()){ ?>
		<tr>
            <td class="info">Email</td>
            <td><input type="text" value="<?=$this->user->getEmail()?>" class="form-control" name="temp_email"></td>
        </tr>
		<?php } ?>
		<?php if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getDateBirth() == '0000-00-00'){ ?>
		
		<tr>
            <td class="info">Дата рождения</td>
            <td><input type="text" value="" class="form-control" name="date_birth" id="date_birth" placeholder="99-99-9999" maxlength="10" onkeyup="validate(this)"></td>
        </tr>
			<?php } ?>
			<?php if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getId() == 8005){ ?>
        <tr>
            <td class="info">Телефон</td>
            <td><input type="text" value="<?=$this->user->getPhone1()?>" class="form-control" name="phone" id="phone"></td>
        </tr>
		<?php } ?>
        <!--tr>
            <td class="info">Акционный код розыгрыша</td>
            <td><input type="text" value="<?=$this->user->getDrawing()?>" class="form-control" name="drawing" id="drawing"></td>
        </tr--> 
		<tr><td colspan="2" style="text-align:center;"><a class="btn btn-default" href="#" onclick="document.forms.account_edit.submit(); return false;">Сохранить</a></td></tr>
        <script type="text/javascript">
		
			function validate(inp) {
    inp.value = inp.value.replace(/[^\d-]*/g, '');
                        // .replace(/([,.])[,.]+/g, '$1')
                        // .replace(/^[^\d]*(\d+([.,]\d{0,5})?).*$/g, '$1');
}
	/*$(document).ready(function() {
		if ($("#phone").attr('value') == '') {
			$("#phone").mskd("0999999999");
		}
	});
	*/
	
</script>
    </tbody></table>
    </form>
	<?php if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->isNoActive()) {?>
	<p style="font-size: 13px;
    padding: 5px;">
В связи с подписанием Президентом Украины указа №133/2017 "Про решение Рады национальной безопасности и обороны Украины "Про применение персональних специальных экономических и других ограничивающих мер (санкций)" хотим предупредить, что вы, вероятно, не сможете получить доступ к своему почтовому ящику <?php echo $this->user->getEmail(); ?>.</br>
Рекомендуем заменить свой адрес электронной почты. Это позволит не потерять аккаунт и историю заказов, а также оставаться с нами на связи.</br>
Из нашего опыта, надежными и удобными сервисами почты являются: Gmail, Ukr.Net, YahooMail</p>
	<?php  } ?>
	
	    <?php  if (count($this->errors)){
    foreach($this->errors as $error){
    echo '<p><span style="font-size: 16px;
    color: red;
    font-weight: bold;">'.$error.'</span></p>';
    }
} ?>
<script type="text/javascript">
	/*function Validate(){
		var draw = $("#drawing").val();		
		if (draw!='red2014' || ' '){
			alert('Вы ввели неверный акционный код');
		}
	}*/
</script>