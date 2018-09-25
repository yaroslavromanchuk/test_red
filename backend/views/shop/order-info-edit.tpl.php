<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<?php
$order_status = explode(',', $this->trans->get('new,processing,canceled,ready_shop,ready_post'));
if ($this->errors) {
    ?>
    <div id="errormessage"><img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt="" class="page-img"/>

        <h1>Ошибка:</h1>
        <ul>
            <?php
            foreach ($this->errors as $error) {
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
        <img src="<?php echo SITE_URL; ?>/img/icons/accept.png" alt="" class="page-img"/>

        <h1>Запись сохранена.</h1>
    </div>
<?php

}
?>

<form method="POST" action="<?php echo $this->path; ?>orderinfo/id/<?php echo (int)$this->order->getId(); ?>/">

    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1">Имя</td>
            <td><input name="name" type="text" class="formfields"
                       value="<?php echo $this->order->getName(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Фамилия</td>
            <td><input name="middle_name" type="text" class="formfields"
                       value="<?php echo $this->order->getMiddleName(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Адрес</td>
            <td><input name="address" type="text" class="formfields"
                       value="<?php echo $this->order->getAddress(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Город</td>
            <td><input name="city" type="text" class="formfields"
                       value="<?php echo $this->order->getCity(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Почтовый код</td>
            <td><input name="pc" type="text" class="formfields"
                       value="<?php echo $this->order->getPc(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Индекс</td>
            <td><input name="index" type="text" class="formfields"
                       value="<?php echo $this->order->getIndex(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Область</td>
            <td><input name="obl" type="text" class="formfields"
                       value="<?php echo $this->order->getObl(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Район</td>
            <td><input name="rayon" type="text" class="formfields"
                       value="<?php echo $this->order->getRayon(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Улица</td>
            <td><input name="street" type="text" class="formfields"
                       value="<?php echo $this->order->getStreet(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Дом</td>
            <td><input name="house" type="text" class="formfields"
                       value="<?php echo $this->order->getHouse(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Квартира</td>
            <td><input name="flat" type="text" class="formfields"
                       value="<?php echo $this->order->getFlat(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Склад</td>
            <td><input name="sklad" type="text" class="formfields"
                       value="<?php echo $this->order->getSklad(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">Телефон</td>
            <td><input name="telephone" type="text" class="formfields"
                       value="<?php echo $this->order->getTelephone(); ?>"/></td>
        </tr>
        <tr>
            <td class="kolom1">E-mail</td>
            <td><input name="email" type="text" class="formfields"
                       value="<?php echo $this->order->getEmail(); ?>"/></td>
        </tr>

        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>
