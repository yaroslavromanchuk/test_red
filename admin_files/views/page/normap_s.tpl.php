<?php $text = explode(',', $this->trans->get('Просмотр,Товар,Всего,Куплено,Осталось,Норма продажи,Дней,Скидка,Продается,'));?>
<table cellpadding="4" cellspacing="0" style="width:900px;">
    <tr>
        <th><?php echo $text[0]; ?></th>
        <th><?php echo $text[1]; ?></th>
        <th><?php echo $text[2]; ?></th>
        <th><?php echo $text[3]; ?></th>
        <th><?php echo $text[4]; ?></th>
        <th><?php echo $text[5]; ?></th>
        <th><?php echo $text[6]; ?></th>
        <th><?php echo $text[7]; ?></th>
    </tr>
    <?php foreach ($this->norma as $kay => $val) { ?>
    <tr>
        <td colspan="6">
            <?php echo $text[8].' '.$kay.' '.$text[6]; ?> 
        </td>
    </tr>
    <?php
    $row = 'row1';
    foreach ($val as $item) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        $style = '';
        $pr = round($item['proc'], 1);
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
        <tr class="<?php echo $row;?>" style="<?php echo $style;?>">
            <td style="<?php echo $style;?>">
                <a href="<?php echo $item['art']->getPath();?>" target="_blank"><img
                        src="<?php echo SITE_URL;?>/img/icons/view-small.png" alt="Просмотр" width="24"
                        height="24"/></a>
            </td>
            <td style="<?php echo $style;?>">
                <?php echo $item['art']->getTitle();?>
            </td>
            <td style="<?php echo $style;?>">
                <?php echo $item['all'];?>
            </td>
            <td style="<?php echo $style;?>">
                <?php echo $item['by'];?>
            </td>
            <td style="<?php echo $style;?>">
                <b><?php echo $item['all'] - $item['by'];?></b>
            </td>
            <td style="<?php echo $style;?>">
                <?php echo $pr;?>%
            </td>
            <td style="<?php echo $style;?>">
                <?php echo $item['day'];?>
            </td>
            <td style="<?php echo $style;?>">
                <?php echo $item['art']->getDiscount();?>%
            </td>

        </tr>
        <?php } ?>

    <?php } ?>
</table>