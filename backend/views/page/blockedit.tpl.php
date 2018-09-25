<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
    if ($this->errors) {
    ?>
<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32"
                            class="page-img"/>

    <h1>Найдены ошибки:</h1>
    <ul>
        <?php
                        foreach ($this->errors as $error)
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

if ($this->saved) {
    ?>
<div id="pagesaved">
    <img src="<?php echo SITE_URL;?>/img/icons/accept.png" alt="" width="32" height="32" class="page-img"/>

    <h1>Данные успешно сохранены</h1>
</div>
<?php

}
?>

<form method="post" action="<?php echo $this->path;?>homeblock/edit/<?php echo $this->block->getId();?>/"
      enctype="multipart/form-data">
    <table id="editpage" cellpadding="5" cellspacing="0">

        <tr>
            <td class="kolom1">Тип</td>
            <td>
                <select name="block">
				<option value="6" <?php if ($this->block->getBlock() == 6) { ?>selected="selected" <?php } ?>>Большой баннер</option>
				<option value="2" <?php if ($this->block->getBlock() == 2) { ?>selected="selected" <?php } ?>>Женская одежда</option>
                <option value="1" <?php if ($this->block->getBlock() == 1) { ?>selected="selected" <?php } ?>>Аксессуары</option>
                <option value="5" <?php if ($this->block->getBlock() == 5) { ?>selected="selected" <?php } ?>>Текстиль</option>
                <option value="4" <?php if ($this->block->getBlock() == 4) { ?>selected="selected" <?php } ?>>Мужская одежда</option>
                  <!--  <option value="3" <?php //if ($this->block->getBlock() == 3) { ?>selected="selected" <?php //} ?>>Нижний 2</option>-->  
                </select>

            </td>
        </tr>
        <tr>
            <td class="kolom1">Имя</td>
            <td><input name="name" type="text"  class="formfields"
                       id="paginaid" value="<?php echo $this->block->getName();?>"/></td>
        </tr>
		<tr>
            <td class="kolom1">Имя uk</td>
            <td><input name="name_uk" type="text"  class="formfields"
                       id="paginaid" value="<?php echo $this->block->getNameUk();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="title" type="text" class="formfields" id="paginatitle"
                       value="<?php echo $this->block->getTitle();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Ссылка</td>
            <td><input name="url" type="text" class="formfields" id="paginatitleuk"
                       value="<?php echo $this->block->getUrl();?>"/></td>
        </tr>
 <tr>
            <td class="kolom1">Дата публикации</td>
            <td><input name="date" type="text" class="formfields" id="paginatitle"
                       value="<?php echo @$this->block->getDate() ?  $this->block->getDate():  date("Y-m-d H:i:s");?>" /> Формат:2016-11-15 08:00:00</td>
        </tr>
		<?php if($this->block->getBlock() == 6){?>
		 <tr>
            <td class="kolom1">Дата исчезновения</td>
            <td><input name="exitdate" type="text" class="formfields" id="paginatitle"
                       value="<?php echo $this->block->getExitdate();?>" placeholder="0000-00-00 00:00:00"/> Формат:2016-11-15 08:00:00</td>
        </tr> 
		
		<tr>
			<td class="kolom1">Украинская версия <br>Картинка <br> Центральные: 270px X 180px <br /> Боковые: 320px X 390px <br />Большой баннер: 1003px X 370px</td>
			<td>
                <label>
                    <input name="image_uk" type="file" class="formfields-1" id="fileField"/>
                </label>
                <?php if ($this->block->getImageUk()) {
                echo '<br/><br/><img style="max-width:300px;" src="' . $this->block->getImageUk() . '" />';
            } ?>
            </td>
        </tr>
		<?php }?>
        <tr>
            <td class="kolom1">Русская версия<br>Картинка <br /> Центральные: 270px X 180px <br /> Боковые: 320px X 390px <br />Большой баннер: 1003px X 370px</td>
            <td>
                <label>
                    <input name="image" type="file" class="formfields-1" id="fileField"/>
                </label>
                <?php if ($this->block->getImage()) {
                echo '<br/><br/><img style="max-width:300px;" src="' . $this->block->getImage() . '" />';
            } ?>
            </td>
			</tr>
			

    </table>


    <p>
        <input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить блок"/>
    </p>
</form>
