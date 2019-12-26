<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<?php
	if($this->saved)
	{
?>	
	<div id="pagesaved">
		<img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" width="32" height="32" class="page-img" />
		<h1>Настройки были успешно сохранены.</h1>
	</div>
<?php
	}

	if($this->getUser()->isSuperAdmin())
	{
		if($this->getConfigs())
		{
			echo '<h2>Установки</h2>
			<form name="configs" method="POST" action="' . $this->path . 'config/" class="form-horizontal">';
			foreach($this->getConfigs() as $config){
				echo '<div class="form-group">
				<label for="' . $config->getId(). '" class="col-xs-2 control-label">'. $config->getCode(). '</label>
				<div class="col-xs-8">
	<input type="text" class="form-control input" style="max-width:500px;" name="data[' . $config->getId(). ']" value="' . $config->getValue(). '"  id="' . $config->getId(). '" ><small>' . $config->getDescription(). '</small>
    </div>
			      </div>';
			}
			echo '<p><input type="submit" class="buttonpw" name="config" value="Сохранить"/></p></form>';
		}


		if($this->getFields() and false)
		{
			echo '<hr /><h2>Ввод контакта</h2><form name="contacts" method="POST" action="' . $this->path . 'config/">';
			echo '
    <p>Чтобы удалить поле, оставьте "Имя поля" пустое. Чтобы добавит текстовое поле, создайте поле с названием "Комментарий".<br />
    </p><table id="contactformtable" cellpadding="5" cellspacing="0">
      <tr>
        <th>Использовать</th>
        <th>Имя поля</th>
        <th>Текст поля</th>
        <th>Ошибка</th>
        <th>Порядок</th>
      </tr>';
			foreach($this->getFields() as $field)
			{
				echo '<tr>';
				echo '<td><input type="checkbox" name="fields[' . $field->getId() .'][required]" value="1" ' . ($field->getRequired()? 'checked' : '') . '/></td>';
				echo '<td><input type="input" name="fields[' . $field->getId() .'][code]" value="' . $field->getCode() .'" /></td>';
				echo '<td><input type="input" name="fields[' . $field->getId() .'][name]" value="' . $field->getName() .'" /></td>';
				echo '<td><input type="input" name="fields[' . $field->getId() .'][error_text]" value="' . $field->getErrorText() .'" /></td>';
				echo '<td><input type="input" name="fields[' . $field->getId() .'][sequence]" value="' . $field->getSequence() .'" /></td>';

				echo '</tr>';		
			}
			//new empty row
			$field = new Field();
			echo '<tr>';
			echo '<td><input type="checkbox" name="fields[' . (int)$field->getId() .'][required]" value="1" /></td>';			
			echo '<td><input type="input" name="fields[' . (int)$field->getId() .'][code]" value="' . $field->getCode() .'" /></td>';
			echo '<td><input type="input" name="fields[' . (int)$field->getId() .'][name]" value="' . $field->getName() .'" /></td>';
			echo '<td><input type="input" name="fields[' . (int)$field->getId() .'][error_text]" value="' . $field->getErrorText() .'" /></td>';
			echo '<td><input type="input" name="fields[' . (int)$field->getId() .'][sequence]" value="' . $field->getSequence() .'" /></td>';
		
			echo '</tr>';
			echo '</table>';
				
			echo '<p><input type="submit" class="buttonpw" name="field" value="Сохранить"/></p></form>';
		}
		
		
		
		if($this->getTranslations() and false)
		{
			echo '<hr /><h2>Тексты</h2><form name="dictionary" method="POST" action="' . $this->path . 'config/">';
			echo '<table id="dictonarytable" cellpadding="5" cellspacing="0">
      <tr>
        <th>Текст</th>
        <th>Перевод Рус.</th>
		<th>Перевод Укр.</th>
      </tr>
      <tr>';
			foreach($this->getTranslations() as $trans)
			{
				echo '<tr>';
				echo '<td><input type="input" name="translations[' . $trans->getId() .'][name]" value="' . $trans->getName() .'" readonly="readonly"/></td>';
				echo '<td><input type="input" name="translations[' . $trans->getId() .'][translation]" value="' . $trans->getTranslation() .'" /></td>';
				echo '<td><input type="input" name="translations[' . $trans->getId() .'][translation_uk]" value="' . $trans->getTranslationUk() .'" /></td>';
				echo '</tr>';		
			}

			echo '</table>';
				
			echo '<p><input type="submit" class="buttonpw" name="translation" value="Сохранить"/></p></form>';
		}		
	}
?>