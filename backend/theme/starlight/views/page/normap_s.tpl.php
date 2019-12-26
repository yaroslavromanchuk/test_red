<?php $text = explode(',', $this->trans->get('Просмотр,Товар,Всего,Куплено,Осталось,Норма продажи,Дней,Скидка,Продается,'));?>
<table cellpadding="4" cellspacing="0" style="width:900px;">
    <tr>
        <th><?=$text[0]?></th>
        <th><?=$text[1]?></th>
        <th><?=$text[2]?></th>
        <th><?=$text[3]?></th>
        <th><?=$text[4]?></th>
        <th><?=$text[5]?></th>
        <th><?=$text[6]?></th>
        <th><?=$text[7]?></th>
    </tr>
    <?php 
	if(@$this->norma){
	foreach ($this->norma as $kay => $val) { ?>
    <tr><td colspan="6"><?=$text[8].' '.$kay.' '.$text[6]?> </td></tr>
    <?php
    $row = 'row1';
    foreach ($val as $item) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        $style = '';
        $pr =  round($item['proc'], 1);
        if ($item['day'] > 14) {
            if ($pr < 75) {
                $style = 'background: #00ff00;';
            }
            if ($pr < 50) {
                $style = 'background: #ffff00;';
            }
            if ($pr < 25) {
                $style = 'background: #ffa500;';
            }
            if ($pr < 10) {
                $style = 'background: #ff0000;';
            }
        }
        if ($item['day'] >= 125) {
            $style = 'background:red;';
        }

        ?>
        <tr class="<?=$row?>" style="<?=$style?>">
<td><a href="<?=$item['patch']?>" target="_blank"><img src="<?=SITE_URL?>/img/icons/view-small.png" alt="Просмотр" width="24" height="24"/></a></td>
            <td><?=$item['title']?></td>
            <td><?=$item['all']?></td>
            <td><?=$item['by']?></td>
            <td><b><?=$item['all']-$item['by']?></b></td>
            <td><?=$pr?>%</td>
            <td><?=$item['day']?></td>
            <td><?=$item['diskont']?>%</td>
        </tr>
        <?php }  ?>

    <?php } } ?>
</table>