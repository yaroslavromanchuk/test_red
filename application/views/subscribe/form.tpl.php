<?php echo $this->getCurMenu()->getPageBody();?>
<p width="100%" align="center" >
<img src="/storage/menu/98cd82022249b41effe9764d9578940c.gif" class="topleftimage" align="center" style="width:150px; text-align:center;">
</p>
<?php if($this->errors) {foreach($this->errors as $error){ ?>
	<p class="error"><?=$error?></p>
<?php }} ?>

<form method="POST" action="/subscribe/"  name="formulier" class="was-validated" align="center">
<div align="center" >
<table cellpadding="5" cellspacing="0">
<tr>

<td style="padding-right: 1%;">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="name" type="text" id="name" required placeholder="Имя" class="form-control" value="<?=$this->post->name?>"/>
<?php
				}
				else {
?>
					<input name="name" type="text" id="name" required placeholder="Имя" class="form-control" value="<?=$this->ws->getCustomer()->getFirstName()?>"/>
<?php
				}
?>
</td>
<td><?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="email" placeholder="E-mail" required type="email" id="email" class="form-control" value="<?=$this->post->email?$this->post->email:$_GET['email']?>" />
<?php
				}else{
?>
					<input name="email" placeholder="E-mail" required type="email" id="email" class="form-control " value="<?=$this->ws->getCustomer()->getEmail()?>"/>
<?php
				}
?></td>
</tr>
<tr>
<td class="pr-1">
<input type="radio" name="active" id="radio"  value="1" <?php echo (!isset($this->post->active) || $this->post->active == 1)? 'checked="checked"':'';?>/>Подписаться</td>
<td>
<input type="radio" name="active" id="radio"  value="0"  <?php echo (isset($this->post->active) && !$this->post->active)? 'checked="checked"':'';?>/>Отписаться 
</td>
</tr>
</table> 

</div>

<input type="submit" class="btn btn-danger" name="save"  align="center"  value="Сохранить"/>


</form>

