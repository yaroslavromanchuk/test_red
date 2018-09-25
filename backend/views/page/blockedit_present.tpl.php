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

<form method="post" action="<?php echo $this->path;?>presentblock/edit/<?php echo $this->block->getId();?>/"
      enctype="multipart/form-data">
    <table id="editpage" cellpadding="5" cellspacing="0">

        <tr>
            <td class="kolom1">Тип</td>
            <td>
                <select name="block">
    <option value="1" <?php if ($this->block->getBlock() == 1) { ?>selected="selected" <?php } ?>>1</option>
    <option value="2" <?php if ($this->block->getBlock() == 2) { ?>selected="selected" <?php } ?>>2</option>
    <option value="3" <?php if ($this->block->getBlock() == 3) { ?>selected="selected" <?php } ?>>3</option>
    <option value="4" <?php if ($this->block->getBlock() == 4) { ?>selected="selected" <?php } ?>>4</option>
    <option value="5" <?php if ($this->block->getBlock() == 5) { ?>selected="selected" <?php } ?>>5</option>
    <option value="6" <?php if ($this->block->getBlock() == 6) { ?>selected="selected" <?php } ?>>6</option>
	<option value="7" <?php if ($this->block->getBlock() == 7) { ?>selected="selected" <?php } ?>>7</option>
	<option value="8" <?php if ($this->block->getBlock() == 8) { ?>selected="selected" <?php } ?>>8</option>
	<option value="9" <?php if ($this->block->getBlock() == 9) { ?>selected="selected" <?php } ?>>9</option>
	<option value="10" <?php if ($this->block->getBlock() == 10) { ?>selected="selected" <?php } ?>>10</option>
	<option value="11" <?php if ($this->block->getBlock() == 11) { ?>selected="selected" <?php } ?>>11</option>
	<option value="12" <?php if ($this->block->getBlock() == 12) { ?>selected="selected" <?php } ?>>12</option>
	<option value="13" <?php if ($this->block->getBlock() == 13) { ?>selected="selected" <?php } ?>>13</option>
                </select>

            </td>
        </tr>
        <tr>
            <td class="kolom1">Имя</td>
            <td><input name="name" type="text"  class="formfields"
                       id="paginaid" value="<?php echo $this->block->getName();?>"/></td>
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
            <td class="kolom1">Титулка</td> 
            <td><input name="sequence" type="text" class="formfields" id="paginatitleseq"
                       value="<?php echo $this->block->getSequence();?>"/></td>
        </tr>
		<tr>
            <td class="kolom1">ID товаров</td>
            <td><input name="articles" type="text" class="formfields" id="paginatitleatr"
                       value="<?php echo $this->block->getArticles();?>"/></td>
        </tr>


        <tr>
            <td class="kolom1">Картинка <br /> Презентация: 600px X 600px</td>
            <td>
                <label>
                    <input name="image" type="file" class="formfields-1" id="fileField"/>
                </label>
                <?php if ($this->block->getImage()) {
                echo '<br/><img src="' . $this->block->getImage() . '" />';
            } ?>
            </td>
        </tr>

    </table>


    <p>
        <input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить блок"/>
    </p>
</form>
