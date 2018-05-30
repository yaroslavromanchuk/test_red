<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<?php
	if($this->errors)
	{
?>
	<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" class="page-img" />
	    <h1>Ошибка:</h1>
	    <ul>
    	<?php
    		foreach($this->errors as $error)
    		{
    	?>
		    <li><?php echo $error;?></li>
		<?php
			}
		?>
	    </ul>
	</div>
<?php
	}

	if($this->saved)
	{
?>
	<div id="pagesaved">
		<img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" class="page-img" />
		<h1>Запись сохранена.</h1>
	</div>
<?php
	}
?>

	<form method="POST" action="<?php echo $this->path;?>subscribers/edit/id/<?php echo (int)$this->sub->getId();?>/">
		<table id="editpage" cellpadding="5" cellspacing="0">
		  <tr>
		    <td class="kolom1">Имя</td>
		    <td><input name="name" type="text" class="formfields" id="paginatitle" value="<?php echo $this->sub->getName();?>" /></td>
		  </tr>

		  <tr>
		    <td class="kolom1">E-mail</td>
		    <td><input name="email" class="formfields" id="email" value="<?php echo $this->sub->getEmail();?>"/></td>
		  </tr>
		  <tr>
			<td class="kolom1">&nbsp;</td>
			<td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить" /></td>
		  </tr>
		</table>
	</form>
	