<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<input style="display:none;" type="button" id='massprintttn' value="Создать заявку на курьера"/>
<table id="products" cellpadding="4" cellspacing="0" style="width:100%;">
    <tr>
		<th>Cоздан</th>
		<th>Вместимые ТТН</th>
        <th>№ Реестра</th>
		<th>№ Заявки</th>
        <th>Печать</th>
    </tr>
    <?php
	
	if($this->register){
                      $row = 'row1';
    foreach ($this->register as $or)
    {
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
             <tr class="<?php echo $row;?>">
                 <td style="width:10%;"><?php  echo $or->getCtime();?></td>
				 <td style="font-size: 11px;width:50%;"><?php  echo $or->getList();?></td>
				  <td style="width:10%;"><?php  echo $or->getRegister();?></td>
				  <td style="width:10%;"><?php  echo $or->getKuryer();?></td>
                 <td style="width:10%;"><input class="button" type="button" id="<?php  echo $or->getRegister();?>" name="<?php  echo $or->getRegister();?>" value="Печать"  onClick="Print('<?php  echo $or->getRegister();?>')" /></td>
            </tr>
        <?php } 
		}
		?>
</table>
<script type="text/javascript">
	function Print(id){
window.open ( 'https://apii.meest-group.com/services/print/register.php?number=' + id , '_blank');
	}
</script>
