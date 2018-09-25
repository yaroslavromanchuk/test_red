<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody(); ?>

<table cellpadding="4" cellspacing="0">
    <tr>
        <th>Слоган</th>
        <th>Данные</th>
        <th>Статистика</th>
        <th>Управление</th>
    </tr>
    <?php foreach ($this->slogans as $slogan) { ?>
    <tr>
        <td style="border-bottom: 1px solid #333;">"<?=$slogan->getSlogan()?>"</td>
        <td style="border-bottom: 1px solid #333;">
            <b>Имя: </b><?=$slogan->getName()?><br/>
            <b>E-mail: </b><?=$slogan->getEmail()?><br/>
            <b>Телефон: </b><?=$slogan->getPhone()?>
            <input type="button" value="Редактировать" class="edit">

            <div class="edit"
                 style="position: absolute; z-index: 100; background: #EDEDEE; border: 1px solid #9C9E9F; padding: 5px; display: none;">
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?=$slogan->getId()?>">
                    <b>Имя: </b><input type="text" name="name" value="<?=$slogan->getName()?>"><br/>
                    <b>E-mail: </b><input type="text" name="email" value="<?=$slogan->getEmail()?>"><br/>
                    <b>Телефон: </b><input type="text" name="phone" value="<?=$slogan->getPhone()?>"><br/>
                    <input type="submit" value="Сохранить">
                </form>
            </div>
        </td>
        <td style="border-bottom: 1px solid #333;">
            <?php
                                 $all = $slogan->score->count();
            $s5 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score' => 5, 'slogan_id' => $slogan->getId()));
            $s4 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score' => 4, 'slogan_id' => $slogan->getId()));
            $s3 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score' => 3, 'slogan_id' => $slogan->getId()));
            $s2 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score' => 2, 'slogan_id' => $slogan->getId()));
            $s1 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score' => 1, 'slogan_id' => $slogan->getId()));
            if ($all != 0) {
                $sred = round((($s5 * 5 + $s4 * 4 + $s3 * 3 + $s2 * 2 + $s1) / $all), 5);
            }
            else $sred = 0;
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
            <?php if ($slogan->getStatus() == 0) { ?>
            <a href="&status=1&id=<?=$slogan->getId()?>"><img src="/img/no_star.png"></a>
            <?php } else { ?>
            <a href="&status=0&id=<?=$slogan->getId()?>"><img src="/img/star.png"></a>
            <?php } ?>
            <a href="&del=1&id=<?=$slogan->getId()?>" onclick="return confirm('Удалить слоган?')"><img
                    src="/img/icons/remove-small.png"></a>
        </td>
    </tr>
    <?php } ?>
</table>
     <script type="text/javascript">
                   $(document).ready(function () {
                    $('input.edit').click(function(){
                        $('div.edit').hide();
                        $(this).parent().find('div.edit').show();
                    });
                   });
         </script>