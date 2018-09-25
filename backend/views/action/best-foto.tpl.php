<?php
    $res = array();
?>

<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<form action="" method="post">
    Конкурс
    <select name="konkurs">
        <option value="999">Выберите конкурс</option>
        <option value="0" <?php if ($this->konkurs == 0) { ?>selected="selected"<?php } ?>>Мое чудо</option>
        <option value="6" <?php if ($this->konkurs == 6) { ?>selected="selected"<?php } ?>>Новогодний</option>
        <option value="7" <?php if ($this->konkurs == 7) { ?>selected="selected"<?php } ?>>Halloween</option>
    </select>
    <input type="submit" value="Выбрать"/> <br><br>
    <a href="/admin/best-foto/add_new/1/">Добавить фото</a>
    <br><br>
</form>

    <a href="" class="show_res">Показать результаты</a>
     <div class="s_res" style="display: none; border: 1px solid"></div>

<table cellpadding="4" cellspacing="0">
    <tr>
        <th>Фото</th>
        <th>Данные</th>
        <th>Статистика</th>
        <th>Управление</th>
    </tr>
    <?php foreach ($this->fotos as $foto) { ?>
    <tr>
        <td style="border-bottom: 1px solid #333;"><img src="<?php echo $foto->getImagePath('listing');?>"
                                                        style="width: 150px;"
                                                        alt="<?php echo htmlspecialchars($foto->getName());?>"/></td>
        <td style="border-bottom: 1px solid #333;">
            <b>Имя: </b><?=$foto->getName()?><br/>
            <b>E-mail: </b><?=$foto->getEmail()?><br/>
            <b>Телефон: </b><?=$foto->getPhone()?><br/>
            <b>Наименование вещи: </b><?=$foto->getItem()?><br/>
            <b>Бренд: </b><?=$foto->getBrend()?><br/>
            <b>Цена: </b><?=$foto->getPrice()?><br/>
            <b>Возраст: </b><?=$foto->getAge()?><br/>
            <b>Хобби: </b><?=$foto->getHoby()?><br/>
            <b>Текст: </b><?=$foto->getText()?><br/>
            <b>Имя ребенка: </b><?=$foto->getNextName()?><br/>
            <b>Тип: </b><?php
            switch ($foto->getType()) {
                case '1':
                    echo 'Чудесный мальчик';
                    break;
                case '2':
                    echo 'Чудесная девочка';
                    break;
                case '3':
                    echo 'Самое оригинальное чудо';
                    break;
            }
            ?><br/>
            <input type="button" value="Редактировать" class="edit">

            <div class="edit"
                 style="position: absolute; z-index: 100; background: #EDEDEE; border: 1px solid #9C9E9F; padding: 5px; display: none;">
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?=$foto->getId()?>">
                    <b>Имя: </b><input type="text" name="name" value="<?=$foto->getName()?>"><br/>
                    <b>E-mail: </b><input type="text" name="email" value="<?=$foto->getEmail()?>"><br/>
                    <b>Телефон: </b><input type="text" name="phone" value="<?=$foto->getPhone()?>"><br/>
                    <b>Наименование вещи: </b><input type="text" name="item" value="<?=$foto->getItem()?>"><br/>
                    <b>Бренд: </b><input type="text" name="brend" value="<?=$foto->getBrend()?>"><br/>
                    <b>Цена: </b><input type="text" name="price" value="<?=$foto->getPrice()?>"><br/><br/>
                    <b>Возраст: </b><input type="text" name="age" value="<?=$foto->getAge()?>"><br/><br/>
                    <b>Хобби: </b><input type="text" name="hoby" value="<?=$foto->getHoby()?>"><br/><br/>
                    <b>Текст: </b><input type="text" name="text" value="<?=$foto->getText()?>"><br/><br/>
                    <b>Имя ребенка: </b><input type="text" name="next_name" value="<?=$foto->getNextName()?>"><br/><br/>
                    <b>Тип: </b><input type="text" name="type" value="<?=$foto->getType()?>"><br/><br/>
                    <input type="submit" value="Сохранить">
                </form>
            </div>
        </td>
        <td style="border-bottom: 1px solid #333;">
            <?php
            $all = $foto->score->count();
            $s5 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 5, 'image_id' => $foto->getId()));
            $s4 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 4, 'image_id' => $foto->getId()));
            $s3 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 3, 'image_id' => $foto->getId()));
            $s2 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 2, 'image_id' => $foto->getId()));
            $s1 = wsActiveRecord::useStatic('ActionFotoscore')->count(array('score' => 1, 'image_id' => $foto->getId()));
            if ($all != 0) {
                $sred = round((($s5 * 5 + $s4 * 4 + $s3 * 3 + $s2 * 2 + $s1) / $all), 5);
            } else $sred = 0;
            ?>
            <b>Проголосовало: </b><?=$all?><br/>
            <b>Оценка 5: </b><?=$s5?><br/>
            <b>Оценка 4: </b><?=$s4?><br/>
            <b>Оценка 3: </b><?=$s3?><br/>
            <b>Оценка 2: </b><?=$s2?><br/>
            <b>Оценка 1: </b><?=$s1?><br/>
            <b>Средний бал: </b><?=$sred?>
        </td>
        <td style="border-bottom: 1px solid #333;">
            <?php if ($foto->getStatus() == 0) { ?>
            <a href="&status=1&id=<?=$foto->getId()?>"><img src="/img/no_star.png"></a>
            <?php } else { ?>
            <a href="&status=0&id=<?=$foto->getId()?>"><img src="/img/star.png"></a>
            <?php } ?>
            <a href="&del=1&id=<?=$foto->getId()?>" onclick="return confirm('Удалить фото?')"><img
                    src="/img/icons/remove-small.png"></a>
        </td>
    </tr>
    <?php
    if ($foto->getStatus() == 1) {
        if (@$res[(int)$foto->getType()][0]['sred']) {
            if ($res[(int)$foto->getType()][0]['sred'] < $sred) {
                $res[(int)$foto->getType()] = array();
                $res[(int)$foto->getType()][0]['sred'] = $sred;
                $res[(int)$foto->getType()][0]['img'] = '<img src="' . $foto->getImagePath('listing') . '" style="width: 150px;"/>';
                $res[(int)$foto->getType()][0]['text'] = '  <b>Имя: </b>' . $foto->getName() . '<br/>
                               <b>E-mail: </b>' . $foto->getEmail() . '<br/>
                               <b>Телефон: </b>' . $foto->getPhone() . '><br/>
                               <b>Наименование вещи: </b>' . $foto->getItem() . '<br/>
                               <b>Бренд: </b>' . $foto->getBrend() . '<br/>
                               <b>Цена: </b>' . $foto->getPrice() . '<br/>
                               <b>Возраст: </b>' . $foto->getAge() . '<br/>
                               <b>Хобби: </b>' . $foto->getHoby() . '<br/>
                               <b>Текст: </b>' . $foto->getText() . '<br/>
                               <b>Имя ребенка: </b>' . $foto->getNextName() . '<br/>';
                $res[(int)$foto->getType()][0]['sred_info'] = '<b>Проголосовало: </b>'.$all.'<br/>
                                      <b>Оценка 5: </b>'.$s5.'<br/>
                                      <b>Оценка 4: </b>'.$s4.'<br/>
                                      <b>Оценка 3: </b>'.$s3.'<br/>
                                      <b>Оценка 2: </b>'.$s2.'<br/>
                                      <b>Оценка 1: </b>'.$s1.'<br/>';
            }
            elseif ($res[(int)$foto->getType()][0]['sred'] == $sred) {
                $res[(int)$foto->getType()][]['sred'] = $sred;
                $res[(int)$foto->getType()][count($res[(int)$foto->getType()]) - 1]['img'] = '<img src="' . $foto->getImagePath('listing') . '" style="width: 150px;"/>';
                $res[(int)$foto->getType()][count($res[(int)$foto->getType()]) - 1]['text'] = '  <b>Имя: </b>' . $foto->getName() . '<br/>
                               <b>E-mail: </b>' . $foto->getEmail() . '<br/>
                               <b>Телефон: </b>' . $foto->getPhone() . '><br/>
                               <b>Наименование вещи: </b>' . $foto->getItem() . '<br/>
                               <b>Бренд: </b>' . $foto->getBrend() . '<br/>
                               <b>Цена: </b>' . $foto->getPrice() . '<br/>
                               <b>Возраст: </b>' . $foto->getAge() . '<br/>
                               <b>Хобби: </b>' . $foto->getHoby() . '<br/>
                               <b>Текст: </b>' . $foto->getText() . '<br/>
                               <b>Имя ребенка: </b>' . $foto->getNextName() . '<br/>';
                $res[(int)$foto->getType()][count($res[(int)$foto->getType()]) - 1]['sred_info'] = '<b>Проголосовало: </b>'.$all.'<br/>
                                      <b>Оценка 5: </b>'.$s5.'<br/>
                                      <b>Оценка 4: </b>'.$s4.'<br/>
                                      <b>Оценка 3: </b>'.$s3.'<br/>
                                      <b>Оценка 2: </b>'.$s2.'<br/>
                                      <b>Оценка 1: </b>'.$s1.'<br/>';
            }
        } else {
            $res[(int)$foto->getType()][0]['sred'] = $sred;
            $res[(int)$foto->getType()][0]['img'] = '<img src="' . $foto->getImagePath('listing') . '" style="width: 150px;"/>';
            $res[(int)$foto->getType()][0]['text'] = '  <b>Имя: </b>' . $foto->getName() . '<br/>
                    <b>E-mail: </b>' . $foto->getEmail() . '<br/>
                    <b>Телефон: </b>' . $foto->getPhone() . '><br/>
                    <b>Наименование вещи: </b>' . $foto->getItem() . '<br/>
                    <b>Бренд: </b>' . $foto->getBrend() . '<br/>
                    <b>Цена: </b>' . $foto->getPrice() . '<br/>
                    <b>Возраст: </b>' . $foto->getAge() . '<br/>
                    <b>Хобби: </b>' . $foto->getHoby() . '<br/>
                    <b>Текст: </b>' . $foto->getText() . '<br/>
                    <b>Имя ребенка: </b>' . $foto->getNextName() . '<br/>';
            $res[(int)$foto->getType()][0]['sred_info'] = '<b>Проголосовало: </b>'.$all.'<br/>
                       <b>Оценка 5: </b>'.$s5.'<br/>
                       <b>Оценка 4: </b>'.$s4.'<br/>
                       <b>Оценка 3: </b>'.$s3.'<br/>
                       <b>Оценка 2: </b>'.$s2.'<br/>
                       <b>Оценка 1: </b>'.$s1.'<br/>';
        }
    }

}

    ?>
</table>
    <div class="rezults" style="display: none">
        <?php foreach ($res as $kay => $val) {
        $type = '';
        if ((int)$kay == 1) $type = 'Чудесный мальчик';
        if ((int)$kay == 2) $type = 'Чудесная девочка';
        if ((int)$kay == 3) $type = 'Самое оригинальное чудо';
        ?>
        <h2><?php echo $type;?></h2>
        <p>Количество: <?php echo count($val);?></p>
        <table>
            <?php foreach ($val as $res) { ?>
            <tr>
                <td>Средний бал: <?php echo $res['sred']?> <br />
                    <?php echo $res['sred_info']?></td>
                <td><?php echo $res['img']?></td>
                <td><?php echo $res['text']?></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </div>

<script type="text/javascript">
    $(document).ready(function () {
        $('input.edit').click(function () {
            $('div.edit').hide();
            $(this).parent().find('div.edit').show();
        });
        $('.show_res').click(function(){
            $('.s_res').html($('.rezults').html()).show();
            return false;
        })
    });
</script>