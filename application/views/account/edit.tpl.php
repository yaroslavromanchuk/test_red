<script src="/js/call/jquery.mask.js"></script> 
<script>
$(function($){
 //$("#phone").mask("38(999)999-99-99");
 $("#date_birth").mask("99-99-9999");
 });
</script> 
<p style="font-size:16px;font-weight: bold;" align="center">Редактирование</p>
  <?php
$bool = false;
 if($this->errors) $bool = true;
 
  if (count($this->errors)){
    foreach($this->errors as $error){
    echo '<p><span style="font-size: 16px;color: red; font-weight: bold;">'.$error.'</span></p>';
    }
} ?>
<form method="post" action="" class="contact-form w-100 was-validated " name="account_edit" id="account_edit">  
<div class="row mx-auto bg-white p-3 ">
<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-3">
<div class="form-group form-group-sm">
<label for="company"><?=$this->trans->get('Компания')?></label>
<input class="form-control" name="company" id="company" type="text"  value="<?=$this->user->company_name?>" >
</div>
<div class="form-group form-group-sm">
<label for="name"><?=$this->trans->get('Имя')?> <span class="red">*</span> <?php if($bool and @$this->errors['name']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['name'].'</span>'; ?></label>
<input class="form-control" name="name" type="text" id="name" required="" value="<?=$this->user->getFirstName()?>">
</div>
<div class="form-group form-group-sm">
<label for="middle_name"><?=$this->trans->get('Фамилия')?> <span class="red">*</span> <?php if($bool and @$this->errors['middle_name']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['middle_name'].'</span>'; ?></label>
<input class="form-control" name="middle_name" type="text" id="middle_name" required="" value="<?=$this->user->middle_name?>">
</div>
<div class="form-group form-group-sm">
<label for="gender"><?=$this->trans->get('Sex')?> <span class="red">*</span> <?php if($bool and @$this->errors['gender']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['gender'].'</span>'; ?></label>
<select name="gender" id="gender" class="form-control" required>
<option></option>
<option value="w" <?=(@$this->usuer->gender == 'w')?'selected':''?> ><?=$this->trans->get('Женский')?></option>
<option value="m" <?=(@$this->user->gender == 'm')?'selected':''?> ><?=$this->trans->get('Мужской')?></option>
</select>
</div>

<?php if($this->user->isNoActive()){ ?>
		<div class="form-group form-group-sm">
<label for="temp_email">E-mail <span class="red">*</span>  <?php if($bool and @$this->errors['email']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['email'].'</span>'; ?></label>
<input class="form-control" name="temp_email" id="temp_email" type="email" required="" value="<?=$this->user->email?>" >
</div>
		<?php } ?>
		<?php if($this->user->getDateBirth() == '0000-00-00'){ ?>
		<div class="form-group form-group-sm">
<label for="date_birth"><?=$this->trans->get('Дата рождения')?> <span class="red">*</span> <?php if($bool and @$this->errors['date_birth']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['date_birth'].'</span>'; ?></label>
<input class="form-control" name="date_birth" type="text" id="date_birth" required="" placeholder="99-99-9999" value="<?=$this->user->date_birth?>" onkeyup="this.value.replace(/[^\d-]*/g, '')">
</div>
			<?php } ?>
			
<?php if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->getId() == 8005 and false){ ?>
			<div class="form-group form-group-sm">
<label for="phone"><?=$this->trans->get('Телефон')?> <span class="red">*</span> <?php if($bool and @$this->errors['phone']) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors['phone'].'</span>'; ?></label>
<input class="form-control" name="phone" type="tel" id="phone" required="" placeholder="(xxx)xxx-xx-xx" value="<?=$this->user->phone1?>" >
</div>
		<?php } ?>

</div>
<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 p-3">
<div class="form-group form-group-sm">
<label for="index"><?=$this->trans->get('Индекс')?></label>
<input type="text" value="<?=$this->user->getIndex()?>" maxlength="5" pattern="[0-9]{5}" class="form-control" name="index" id="index">
</div>
<div class="form-group form-group-sm">
<label for="obl"><?=$this->trans->get('Область')?></label>
<input type="text" value="<?=$this->user->obl?>" class="form-control" name="obl" id="obl">
</div>
<div class="form-group form-group-sm">
<label for="rayon"><?=$this->trans->get('Район')?></label>
<input type="text" value="<?=$this->user->getRayon()?>" class="form-control" name="rayon" id="rayon">
</div>
<div class="form-group form-group-sm">
<label for="city"><?=$this->trans->get('Город')?></label>
<input type="text" value="<?=$this->user->getCity()?>" class="form-control" name="city" id="city">
</div>
<div class="form-group form-group-sm">
<label for="street"><?=$this->trans->get('Улица')?></label>
<input type="text" value="<?=$this->user->getStreet()?>" class="form-control" name="street" id="street">
</div>
<div class="form-group form-group-sm">
<label for="house"><?=$this->trans->get('Дом')?></label>
<input type="text" value="<?=$this->user->getHouse()?>" class="form-control" name="house" id="house">
</div>
<div class="form-group form-group-sm">
<label for="flat"><?=$this->trans->get('Квартира')?></label>
<input type="text" value="<?=$this->user->getFlat()?>" class="form-control" name="flat" id="flat">
</div>
</div>
		  <div class="col-xl-12 text-center">
			<button class="btn btn-danger" type="submit" id="send">Сохранить</button>
		</div>
	</div>
    </form>
