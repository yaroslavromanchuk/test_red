<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?= $this->getCurMenu()->getPageBody()?>

<?php
    if ($this->errors) {
    ?>
<div id="errormessage"><img src="/img/icons/error.png" alt="" width="32" height="32"
                            class="page-img"/>

    <h1>Найдены ошибки:</h1>
    <ul>
        <?php
                        foreach ($this->errors as $error)
    {
        ?>
        <li><?=$error?></li>
        <?php
    }
        ?>
    </ul>
</div>
        <?php
}
if ($this->saved){ ?>
<div id="pagesaved">
    <img src="/img/icons/accept.png" alt="" width="32" height="32" class="page-img"/>
    <h1>Данные успешно сохранены</h1>
</div>
<?php } ?>

<form method="post" action="<?=$this->path?>homeblock/edit/<?=$this->block->getId()?$this->block->getId().'/':''?>" enctype="multipart/form-data">
    <table class="table table-light" style="background: #ccc;">

        <tr>
            <td class="kolom1">Тип</td>
            <td>
                <select name="block" class="select2">
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
            <td><input name="name" type="text"  class="form-control"
                       id="paginaid" value="<?php echo $this->block->getName();?>"/></td>
        </tr>
		<tr>
            <td class="kolom1">Имя uk</td>
            <td><input name="name_uk" type="text"  class="form-control"
                       id="paginaid" value="<?php echo $this->block->getNameUk();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Название</td>
            <td><input name="title" type="text" class="form-control" id="paginatitle"
                       value="<?php echo $this->block->getTitle();?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Ссылка</td>
            <td><input name="url" type="text" class="form-control" id="paginatitleuk"
                       value="<?php echo $this->block->getUrl();?>"/></td>
        </tr>
 <tr>
            <td class="kolom1">Дата публикации</td>
            <td><input name="date" type="text" class="form-control" id="paginatitle"
                       value="<?php echo @$this->block->getDate() ?  $this->block->getDate():  date("Y-m-d H:i:s");?>" /> Формат:2016-11-15 08:00:00</td>
        </tr>
		<?php if($this->block->getBlock() == 6){?>
		 <tr>
            <td class="kolom1">Дата исчезновения</td>
            <td><input name="exitdate" type="text" class="form-control" id="paginatitle"
                       value="<?php echo $this->block->getExitdate();?>" placeholder="0000-00-00 00:00:00"/> Формат:2016-11-15 08:00:00</td>
        </tr> 
		
		<tr>
			<td class="kolom1">Украинская версия <br>Картинка <br> Блок на главной: 600px X 800px <br />Большой баннер: 1500px X 380px</td>
			<td>
                <label>
                    <input name="image_uk" type="file" class="form-control-1" id="fileField"/>
                </label>
                <?php if ($this->block->getImageUk()) {
                echo '<br/><br/><img style="max-width:300px;" src="' . $this->block->getImageUk() . '" />';
            } ?>
            </td>
        </tr>
		<?php }?>
        <tr>
            <td class="kolom1">Русская версия<br>Картинка <br /> Блок на главной: 600px X 800px <br />Большой баннер: 1500px X 380px</td>
            <td>
                <label>
                    <input name="image" type="file" class="form-control-1" id="fileField"/>
                </label>
                <?php if ($this->block->getImage()) {
                echo '<br/><br/><img style="max-width:300px;" src="' . $this->block->getImage() . '" />';
            } ?>
            </td>
			</tr>
			

    </table>


    <p>
        <input type="submit" class="btn" name="savepage" id="savepage" value="Сохранить блок"/>
    </p>
</form>
