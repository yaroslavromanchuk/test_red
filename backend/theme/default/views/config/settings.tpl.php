<?php if($this->saved){ ?>	
<div class="alert alert-success " role="alert">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only1">Настройки успешно сохранены!</span>
</div>
<?php
	}

	if($this->getUser()->isSuperAdmin())
	{ ?>
            <div class="accordion" id="accordionExample">
<?php if($this->getConfigs()){ ?>
<div class="card pd-20 mb-3 ">
    <div class="card-header" id="config">
      <h5 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseConfig" aria-expanded="false" aria-controls="collapseConfig">
         Установки сайта
        </button>
      </h5>
    </div>
    <div id="collapseConfig" class="collapse" aria-labelledby="config" data-parent="#accordionExample">
      <div class="card-body">

			<form name="configs" method="POST" action="<?=$this->path?>config/" class="form-horizontal">
                            
			<?php foreach($this->getConfigs() as $config){ ?>
                            <div class="row mg-b-25">
                                <div class="col-lg-6">
				<div class="form-group">
				<label class="form-control-label"><?=$config->getCode()?>_RU</label>
                            <input type="text" class="form-control"  name="data[<?=$config->getId()?>][value]" value="<?=$config->getValue()?>"  ><small><?=$config->getDescription()?></small>

			      </div>
                            </div>
                                <div class="col-lg-6">
				<div class="form-group">
				<label class="form-control-label"><?=$config->getCode()?>_UA</label>
                            <input type="text" class="form-control"  name="data[<?=$config->getId()?>][value_uk]" value="<?=$config->getValueUk()?>"  ><small><?=$config->getDescription()?></small>

			      </div>
                            </div>
                                </div>
			<?php } ?>
                                
	<input type="submit" class="btn btn-default" name="config" value="Сохранить"/>
                        </form>
        </div>
    </div>
                        </div>
	<?php	} ?>


	
		
		
		
	<?php	if($this->getTranslations())
		{ ?>
		<div class="card pd-20 mb-3">
    <h5 class="card-body-title"></h5>
    <div class="card-header" id="text">
      <h5 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseText" aria-expanded="false" aria-controls="collapseText">
         Переводы тескстов
        </button>
      </h5>
    </div>
    <div id="collapseText" class="collapse" aria-labelledby="text" data-parent="#accordionExample">
      <div class="card-body">

                       <form name="dictionary" method="POST" action="<?=$this->path?>config/" class="form-horizontal">
			<table class="table">
                            <thead>
      <tr>
        <th>Текст</th>
        <th>Перевод Рус.</th>
		<th>Перевод Укр.</th>
      </tr>
                            </thead>
                            <tbody>
      
		<?php	foreach($this->getTranslations() as $trans)
			{ ?>
				<tr>
				<td>
                                    <input type="text" class="form-control" name="translations[<?=$trans->getId()?>][name]" value="<?=$trans->getName()?>" readonly="readonly"/></td>
				<td>
                                    <input type="text" class="form-control" name="translations[<?=$trans->getId()?>][translation]" value="<?=$trans->getTranslation()?>" /></td>
				<td>
                                    <input type="text" class="form-control" name="translations[<?=$trans->getId()?>][translation_uk]" value="<?=$trans->getTranslationUk()?>" /></td>
				</tr>		
		<?php	} ?>
                            </tbody>
			</table>
				
		<input type="submit" class="btn btn-default" name="save_translation" value="Сохранить"/>
</form>
    </div>
        </div>
    </div>
		<?php }	 ?>
                
        <?php if($this->getRedirect()){ ?>
<div class="card pd-20 mb-3 ">
    <div class="card-header" id="redirect">
      <h5 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseredirect" aria-expanded="false" aria-controls="collapseredirect">
         Редиректы url
        </button>
      </h5>
    </div>
    <div id="collapseredirect" class="collapse" aria-labelledby="redirect" data-parent="#accordionExample">
      <div class="card-body">

			<form name="redirects" method="POST" action="<?=$this->path?>config/" class="form-horizontal">
                            
			<?php foreach($this->getRedirect() as $config){ ?>
                            <div class="row mg-b-25">
                                <div class="col-lg-5">
				<div class="form-group">
			
                            <input type="text" class="form-control"  name="data[<?=$config->getId()?>][url]" value="<?=$config->getUrl()?>"  >

			      </div>
                            </div>
                                <div class="col-lg-1 text-center">
                                    <div class="form-group">
				<i class="icon ion-md-arrow-round-forward"></i>
                                   </div>
                                    </div>
                                <div class="col-lg-5">
				<div class="form-group">
				
                            <input type="text" class="form-control"  name="data[<?=$config->getId()?>][to_url]" value="<?=$config->getToUrl()?>"  >

			      </div>
                            </div>
                                </div>
			<?php } ?>
                                
	<input type="submit" class="btn btn-default" name="redirect" value="Сохранить"/>
                        </form>
          <div class="mt-3">
              <button type="button" class="btn btn-primary btn-sm" onclick="$('.add_new_redirect').show(); $(this).hide();"> <i class="icon ion-md-add-circle" ></i>Добавить новый редирект</button>
             
              <div class="add_new_redirect " style="display: none;">
                  <form name="redirects_add" method="POST" action="<?=$this->path?>config/" class="form-horizontal" >
                  <div class="row mg-b-25">
                                <div class="col-lg-5">
				<div class="form-group">
			
                            <input type="text" class="form-control"  name="url" value=""  >

			      </div>
                            </div>
                                <div class="col-lg-1 text-center">
                                    <div class="form-group">
				<i class="icon ion-md-arrow-round-forward"></i>
                                   </div>
                                    </div>
                                <div class="col-lg-5">
				<div class="form-group">
				
                            <input type="text" class="form-control"  name="to_url" value=""  >

			      </div>
                            </div>
                                </div>
                      <input type="submit" class="btn btn-default" name="redirect_save" value="Сохранить"/>
                  </form>
              </div>
          </div>
        </div>
    </div>
                        </div>
	<?php	} ?>        
                
         </div>       
             <?php  	if($this->getFields() and false)
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
	}
?>