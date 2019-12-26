<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle(); ?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
if ($this->errors) {
    ?>
    <div id="errormessage"><img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt="" width="32" height="32"
                                class="page-img"/>

        <h1>Найдены ошибки:</h1>
        <ul>
            <?php
            foreach ($this->errors as $error) {
                ?>
                <li><?php echo $error; ?></li>
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
        <img src="<?php echo SITE_URL; ?>/img/icons/accept.png" alt="" width="32" height="32" class="page-img"/>

        <h1>Данные успешно сохранены</h1>
    </div>
<?php
}
?>


<form method="post" action="/admin/best-foto/add_new/1/" enctype="multipart/form-data">
    <table id="editpage" cellpadding="5" cellspacing="0">

        <tr>
            <td class="kolom1">Конкурс</td>
            <td>
                <select name="action_id">
                    <option value="7" <?php if ($this->foto->getActionId() == 7) { ?>selected="selected"<?php } ?>>
                        Halloween
                    </option>

                </select>
            </td>
        </tr>

        <tr>
            <td class="kolom1">Имя</td>
            <td><input name="name" type="text" class="formfields" value="<?php echo $this->foto->getName(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Email</td>
            <td><input name="email" type="text" class="formfields" value="<?php echo $this->foto->getEmail(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Телефон</td>
            <td><input name="phone" type="text" class="formfields" value="<?php echo $this->foto->getPhone(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Текст</td>
            <td><input name="text" type="text" class="formfields" value="<?php echo $this->foto->getText(); ?>"/></td>
        </tr>

        <tr>
            <td class="kolom1">Изображение</td>
            <td><input name="image" type="file" class="formfields" id="image"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <img src="<?php echo $this->foto->getImagePath('listing'); ?>"/>

            </td>
        </tr>


    </table>

    <p>
        <input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/>
    </p>
</form>