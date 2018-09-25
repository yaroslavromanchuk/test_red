<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<form action="<?php echo $this->getCurMenu()->getPath();?>" method="post" enctype="multipart/form-data" id="form-demo">
	
	
	<?php if ($this->errors): ?>
		<?php foreach ($this->errors as $error): ?>
			<p><?php echo $error; ?></p>
		<?php endforeach; ?>
	<?php endif; ?>
	
      <select name="category_id">
        <option value="0">-- Выбор категории --</option>
        <?php foreach( wsActiveRecord::useStatic('Menu')->findAll(array('type_id' => 3)) as $cat )
        	echo '<option value="' . $cat->getId() . '">' . $cat->getName() . '</option>';
        ?>
      </select>
    
		<input type="hidden" name="file_type_id" value="<?php echo $this->file_type;?>"/>
	
	<fieldset id="demo-fallback">
		<legend><?php echo $this->getCurMenu()->getTitle();?></legend>
		<label style="float: left; width: 320px;">Название:</label><input type="text" name="descr" value="" /><br/>
		<label style="float: left; width: 320px;">Загружаемый файл (большой, 184x84):</label><input type="file" name="file_big" /><br/>
		<label style="float: left; width: 320px;">Загружаемый файл (маленький, 31x31):</label><input type="file" name="file_small" /><br/>
		<br/>
		<input type="submit" value="Загрузить"/>
	</fieldset>
 
</form>