<style type="text/css">
    table.poll_result {
        border: 1px solid #cef;
        text-align: left;
    }

    table.poll_result th {
        font-weight: bold;
        background-color: #acf;
        border-bottom: 1px solid #cef;
    }

    table.poll_result td, th {
        padding: 4px 5px;
    }

    table.poll_result .odd {
        background-color: #def;
    }

    table.poll_result .odd td {
        border-bottom: 1px solid #cef;
    }
</style><br/>
<?php if($this->user->getId() == 8005) { echo '<a href="/admin/pollresults/new_poll/new/">Создать новое голосование</a>';} ?>
<br>
<?php
        foreach ($this->results as $title => $results) {
    ?>
<?php if ($results['active'] == 0) { ?>
    <p style="color: red;">Опрос завершон.</p>
    <?php } ?>
<table border="0" style="width: 100%">
    <tr>
        <td>
            <strong><?php echo $title?>:</strong>

         <!--   <a href="/admin/pollresults/excel/<?php //echo $results['id']; ?>">скачать результаты Excel</a>-->
        </td>
    </tr>
    <tr>
        <td style="    text-align: center;"><?php

            $sum = 0;
            $chl = array();//labels
            $chd = array();//count
            $chdl = array();//legend values
            foreach ($results['results'] as $result) {
                $sum += $result->getC();
            }
            foreach ($results['results'] as $result) {
                if ($result->getC() > 0) {
                    $chl[] = mb_substr(str_replace("'",'',str_replace('"','',$result->getName())),0,60);
                    $chd[] = round(($result->getC() * 100) / $sum);
                    $chdl[] = $result->getC();
                }
            }
            $chl = 'chl=' . implode($chl, '|');
            $chd = 'chd=t:' . implode($chd, ',');
            $chdl = 'chdl=' . implode($chdl, '|');
            $url = 'https://chart.googleapis.com/chart?cht=p&chs=500x200&' . $chd . '&' . $chl ;
            ?>
            <img src="<?=$url;?>" alt="Poll results"/>
        </td>
    </tr>
    <tr>
        <td>Всего проголосовало: <?=$sum;?></td>
    </tr>

    <?php if ($results['results']->count()) { ?>
        <tr>
            <td>
                <p>Ответы:</p>
                <table>
                    <?php foreach ($results['results'] as $item) { ?>
                        <tr><td><?=$item->getName()?></td><td><?php echo ' - '.$item->getC()?></td></tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
        <?php } ?>

    <?php if ($results['answers']->count()) { ?>
    <tr>
        <td>
            <p>Ответы пользователей:</p>
            <table>
                <?php foreach ($results['answers'] as $item) { ?>
                    <tr>
                        <td><?php echo $item->getRes()?></td>
                        <td><?php echo '  - '.$item->getCnt()?></td>
                    </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
    <?php } ?>
</table>
<br/>
<br/>

<?php } ?>