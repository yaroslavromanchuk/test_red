<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32"/>
<h1><?=$this->getCurMenu()->getTitle(); ?> </h1>
<?=$this->getCurMenu()->getPageBody(); ?>

<?php
if ($this->saved) {
    ?>
    <div id="pagesaved">
        <img src="<?=SITE_URL;?>/img/icons/accept.png" alt="" class="page-img"/>

        <h1>Запись сохранена.</h1>
    </div>
<?php
}
?>
<style>
    .td_cell {
        vertical-align: middle;
        border-bottom: 1px dashed;
        line-height: 20px;
    }

    .td_cell.text {
        font-size: 14px !important;
    }
</style>
<form method="POST" action="<?=$this->path; ?>adminedit/edit/<?=(int)$this->admin->getId(); ?>/">
    <table id="editpage" cellpadding="5" cellspacing="0">
        <tr>
            <td class="kolom1"></td>
            <td><a href="/admin/user/edit/id/<?=$this->admin->getId(); ?>">Редактировать как пользователя</a>
            </td>
        </tr>
        <tr>
            <td class="kolom1">Номер</td>
            <td><?=$this->admin->getId(); ?></td>
        </tr>
        <tr>
            <td class="kolom1">Логин</td>
            <td><?=$this->admin->getUsername(); ?></td>
        </tr>
        <tr>
            <td class="kolom1">Имя</td>
            <td><?=$this->admin->getFirstName(); ?></td>
        </tr>
        <tr>
            <td class="kolom1">Фамилия</td>
            <td><?=$this->admin->getMiddleName(); ?></td>
        </tr>
        <tr>
            <td class="kolom1">E-mail</td>
            <td><?=$this->admin->getEmail(); ?></td>
        </tr>
        <tr>
            <td class="kolom1">Телефон</td>
            <td><?=$this->admin->getPhone1(); ?></td>
        </tr>
        <?php if ($this->website->getCustomer()->getCustomerTypeId() == 4) { ?>
            <tr>
                <td class="kolom1">Последний вход</td>
                <td><?=$this->admin->getVisitTime(); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td class="kolom1">Статус</td>
            <td>
			<select name="customer_type_id" class="input" onChange="Chek(this.value); return false;">
			<?php foreach (wsActiveRecord::useStatic('Customer')->findByQuery('SELECT id, name_ru FROM ws_customer_types') as $sub){ ?>
			<option value="<?=$sub->id?>" <?php if($this->admin->getCustomerTypeId() == $sub->id) echo 'selected';  ?>><?=$sub->name_ru?></option>
			<?php } ?>
			</select>
            </td>
        </tr>
        <tr>
            <td>Делать заказ</td>
            <td><input type="checkbox" name="all_right[do_pay]" value="1"
                       <?php if ($this->admin->hasRight('do_pay')){ ?>checked="checked" <?php } ?> /></td>
        </tr>
        <tr>
            <td>Редактировать админский заказ</td>
            <td><input type="checkbox" name="all_right[edit_my_order]" value="1"
                       <?php if ($this->admin->hasRight('edit_my_order')){ ?>checked="checked" <?php } ?> /></td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <p style="font-size: 14px; font-weight: bold;">Меню прав:</br> <a href=""
                                                                                   onclick=" $('.rv_yes').click(); return false;">Все
                                    "Да"</a>  <a href="" onclick=" $('.rv_no').click(); return false;">Все "Нет"</a></br>
									</p>
                            <div style="height: 500px; overflow-y: scroll;">
                                <table cellpadding="0" cellspacing="0" >
                                    <?php foreach (AdminRights::getViewPages() as $pages) { ?>
                                        <tr>
                                            <td class="td_cell text">
                                                <img width="16" src="<?php echo $pages->getImage(); ?>" alt=""/>
                                                <?php echo $pages->getName(); ?>
												<?php //echo $pages->getId(); ?>
                                            </td>
                                            <td class="td_cell" style="width: 100px;">
                                                <label><input class="rv_no"
                                                              type="radio"
                                                              <?php if (@$this->rights[$pages->getId()]['view'] == 0) { ?>checked="checked"<?php } ?>
                                                              name="view[<?php echo $pages->getId(); ?>]" value="0"> Нет</label>
                                                <label><input class="rv_yes" id="v<?php echo $pages->getId(); ?>"
                                                              type="radio"
                                                              <?php if (@$this->rights[$pages->getId()]['view'] == 1) { ?>checked="checked"<?php } ?>
                                                              name="view[<?php echo $pages->getId(); ?>]" value="1"> Да</label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </td>
                        <td style="width: 50%;">
                            <p style="font-size: 14px; font-weight: bold;">Права:</br> <a href=""
                                                                                    onclick=" $('.rr_yes').click(); return false;">Все
                                    "Да"</a></br> <a href="" onclick=" $('.rr_no').click(); return false;">Все "Нет"</a></br>
									</p>
                            <div style="height: 500px; overflow-y: scroll;">
                                <table cellpadding="0" cellspacing="0" >
                                    <?php foreach (AdminRights::getPages() as $pages) {
                                        ?>
                                        <tr>
                                            <td class="td_cell text">
                                                <?php echo $pages->getName(); ?>
												<?php //echo $pages->getId(); ?>
                                            </td>
                                            <td class="td_cell" style="width: 100px;">
                                                <label><input class="rr_no"
                                                              type="radio"
                                                              <?php if (@$this->rights[$pages->getId()]['right'] == 0) { ?>checked="checked"<?php } ?>
                                                              name="right[<?php echo $pages->getId(); ?>]" value="0">
                                                    Нет</label>
                                                <label><input class="rr_yes" id="r<?php echo $pages->getId(); ?>"
                                                              type="radio"
                                                              <?php if (@$this->rights[$pages->getId()]['right'] == 1) { ?>checked="checked"<?php } ?>
                                                              name="right[<?php echo $pages->getId(); ?>]" value="1"> Да</label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>

                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="kolom1">&nbsp;</td>
            <td><input type="submit" class="buttonps" name="savepage" id="savepage" value="Сохранить"/></td>
        </tr>
    </table>
</form>

<script>
function Chek(c){
$('.rr_no').click();
$('.rv_no').click();
if(c == 5){
var v = [402, 11, 322, 193, 10, 355, 353, 447, 451, 415, 12, 323, 425, 5, 188, 428, 488, 489, 475, 479, 495 ];
var r = [321, 332, 333, 338, 380, 327, 4, 402, 11, 322, 196, 194, 346, 360, 416, 193, 345, 366, 335, 418, 379, 363, 307, 344, 412, 411, 10, 355, 382, 334, 353, 441, 415, 12, 324, 325, 425, 5, 430, 429, 188, 428, 339, 488, 489, 476, 475, 479, 481, 495];
}else if(c == 6){
var v = [ 402, 11, 322, 193, 10, 415, 12, 323, 425, 5, 188, 428, 495 ];
var r = [321, 332, 333, 338, 380, 331, 393, 347, 327, 4, 350, 403, 402, 11, 322, 20, 390, 196, 364, 394, 194, 346, 195, 360, 395, 416, 193, 345, 366, 335, 418, 379, 363, 307, 412, 411, 10, 382, 334, 441, 46, 415, 12, 323, 324, 325, 425, 5, 189, 190, 188, 339, 428, 491, 493, 495];
}else if(c == 7){
var v = [402, 11, 322, 193, 12, 10, 474, 425, 5, 188, 495];
var r = [412, 321, 332, 333, 327, 4, 403, 402, 11, 322, 196, 194, 346, 195, 360, 395, 416, 193, 366, 335, 418, 363, 307, 12, 411, 10, 382, 334, 474, 5, 25, 192, 191, 384, 189, 190, 188, 339, 428, 491, 492, 495];
}else if(c == 8){
var v = [423, 402, 11, 322, 193, 10, 474,  451, 12, 323, 425, 5, 188, 428, 495];
var r = [321, 332, 333, 4, 423, 403, 402, 11, 322, 196, 364, 394, 194, 346, 195, 360, 395, 416, 193, 345, 335, 363, 307, 344, 412, 10, 474, 382, 12, 323, 324, 325, 425, 25, 188, 339, 428, 476, 492, 495];
}else if(c == 3 || c == 4){
$('.rr_yes').click();
$('.rv_yes').click();
return false;
}else{
return false;
}

v.forEach(function(item, i, v) {
$('#v'+item+'.rv_yes').click();
});
r.forEach(function(item, i, r) {
$('#r'+item+'.rr_yes').click();
});
}
</script>
