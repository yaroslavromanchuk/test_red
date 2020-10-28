<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title>Чек</title>
	<meta name="robot" content="no-index,no-follow"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<style type="text/css">
    body {font-size: 8px; font-family: Verdana;}
	.tt_border_bottom {border-bottom: solid 1px #000;}
    .tt_border_top {border-top: solid 1px #000;}
    .fnt_size_1 {font-size: 12px;}
	.fnt_size_10{font-size: 10px;} 
    .fnt_size_2 {font-size: 11px;}
    .fnt_size_3 {font-size: 15px;}
    .border_all {border-top: 1px solid #000;border-left: 1px solid #000;}
    .border_left {border-left: 1px solid #000;}
    .border_right {border-right: 1px solid #000;}
    .border_top {border-top: 1px solid #000;}
    .border_none {border: none;}
</style>
<body onload="window.print()">
    <table border="0" cellpadding="3" cellspacing="0" width="250">
        <tr>
            <td colspan="3"  align="center" class="fnt_size_3">
                <b><?=$this->delivery?></b>
            </td>
        </tr>
        <tr>
            <td colspan="3"  align="center" class="fnt_size_3" style="padding-bottom: 15px;padding-top: 15px;">
                №_______________________
            </td>
        </tr>
        <?php
        if(!empty($this->articles)){
        foreach ($this->articles as $article){
            foreach($article as $a){
            ?>
            <tr>
            <td class="border_all" style="font-size: 1.2em;">
                <?=$a->name?>
            </td>
                <td colspan="2" class="border_all border_right" style="padding: 5px">
                   <img src="/images/barcodeimage.php?text=<?=$a->cod?>" alt="<?=$a->cod?>" style="margin-top: 5px;" />
            </td >
            <!--<td class="border_all  border_right" style="width: 15px;">
                 <?=$a->count?>
            </td>-->
        </tr>
        <?php   } }
        }
        ?>
        <tr>
            <td class="tt_border_top" colspan="3">
                
            </td>
        </tr>
    </table>
</body>
</html>