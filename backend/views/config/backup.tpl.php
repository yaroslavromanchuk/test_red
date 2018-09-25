<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<?php
	if($this->saved)
	{
?>	
	<div id="pagesaved">
		<img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" width="32" height="32" class="page-img" />
		<h1>Резервное копирование выполнено успешно.</h1>
	</div>
<?php
	}
?>

	<form id="backup" method="POST" action="<?php echo $this->path;?>backup/">
		<input name="backup" type="submit" class="buttonpw" value="Выполнить"/>
	</form>