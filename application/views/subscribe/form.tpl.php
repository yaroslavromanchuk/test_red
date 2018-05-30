<h1><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->getCurMenu()->getPageBody();?>
<p width="100%" align="center" >
<img src="/storage/menu/98cd82022249b41effe9764d9578940c.gif" class="topleftimage" align="center" style="width:150px; text-align:center;">
</p>
<?php if($this->errors) foreach($this->errors as $error){ ?>
	<p class="error"><?php echo $error;?></p>
<?php } ?>

<form method="POST" action="/subscribe/" id="formulier" name="formulier" align="center">
<div align="center" >
<table cellpadding="5" cellspacing="0">
<tr>

<td style="padding-right: 1%;">
<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="name" type="text" id="name" required="" placeholder="Имя" class="form-control<?php if (in_array('name', $this -> errors, true)) echo " red";
						?>" value="<?php echo @$this->post->name;?>"/>
<?php
				}
				else {
?>
					<input name="name" type="text" id="name" required="" placeholder="Имя" class="form-control<?php if (in_array('name', $this -> errors, true)) echo " red";
						?>" value="<?php echo $this->ws->getCustomer()->getFirstName(); ?>"/>
<?php
				}
?>
</td>
<td><?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
					<input name="email" placeholder="E-mail" type="email" id="email" class="form-control<?php if (in_array('email', $this -> errors, true)) echo " red";
						?>" value="<?php echo @$this->post->email?@$this->post->email:@$_GET['email'];?>" />
<?php
				}
				else {
?>
					<input name="email" placeholder="E-mail" type="email" id="email" class="form-control<?php if (in_array('email', $this -> errors, true)) echo " red";
						?>" value="<?php echo $this->ws->getCustomer()->getEmail(); ?>"/>
<?php
				}
?></td>
</tr>
<tr>
<td style="padding-right: 1%;">
<input type="radio" name="active" id="radio" onchange = 'showOrShow("radio", "vubor");' value="1" <?php echo (!isset($this->post->active) || @$this->post->active)? 'checked="checked"':'';?>/>Подписаться</td>
<td>
<input type="radio" name="active" id="radio"  value="0" onchange = 'showOrHide("radio", "vubor");' <?php echo (isset($this->post->active) && !@$this->post->active || @$_GET['email'])? 'checked="checked"':'';?>/>Отписаться 
</td>
</tr>
</table> 

</div>

<div id="vubor" class="vubor_div">
<h3>Выберите товар, который Вы хотите получать в рассылке:</h3>
<table class="table_sub">
<tr>
<td style="color: black;font-size: 18px;text-align: center;"><br/><br/><input type="checkbox" name="m_new" value="1">Мужское<br/></td>
<td style="color: black;font-size: 18px;text-align: center;"><br/><br/><input type="checkbox" name="g_new" value="1">Женское<br/></td>
<td style="color: black;font-size: 18px;text-align: center;"><br/><br/><input type="checkbox" name="d_new" value="1">Детcкое<br/></td><!--
<td lass="table_sub_td">Мужское</td>
<td lass="table_sub_td">Женское</td>
<td lass="table_sub_td">Детям</td>-->
</tr>
</table>
</br>
<input type="submit" class="btn btn-default" name="save"  align="center"  value="Сохранить"/>
</div>

</form>
<script type="text/javascript">
  function showOrShow(cb, cat) {
    cb = document.getElementById(cb);
    cat = document.getElementById(cat);
    if (cb.checked) cat.style.display = "block";
    else cat.style.display = "none";
  }
</script>
<script type="text/javascript">
  function showOrHide(cb, cat) {
    cb = document.getElementById(cb);
    cat = document.getElementById(cat);
    if (cb.checked) cat.style.display = "block";
    else cat.style.display = "none";
  }
</script>
