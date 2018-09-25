<?php $all = ($this->cur_menu->parameter=='all') ? 1 : 0; ?>
<style>
.check{
padding:0;
border:0;
width:50px;
height:65px;
background-color: transparent;
}
.check.checked{
background: url('/img/icons/check.png') no-repeat top center;
}
.check span{
padding: 0;
display: block;
margin-top: 28px;
font-size: 13px;
}
#fiveown tr:nth-child(4) .check:active{
margin-top:1px;
height:64px;
}
#fiveown tr:nth-child(4) .check:hover{
background: url('/img/icons/check.png') no-repeat top center;
}
table td {vertical-align: middle;}
table#fiveown tr {opacity: 0.5;}
table#fiveown tr:nth-child(-n+4) {opacity: 1;}
</style>

<script>
$( document ).ready(function() {
	$( "#fiveown tr:nth-child(4) td .check" ).live( "click", function() {
		if ( !($(this).hasClass('checked'))){/**/
			if (confirm("Вы уверены?")){
				var date = new Date();
				var time = date.getHours()+':'+('0'+date.getMinutes()).slice(-2)+':'+ ('0'+ date.getSeconds()).slice(-2);
				var th = $(this);

				$.ajax({
					type: 'POST',
					url: '/admin/5min/',
					data: {
						time: time,
						n: $(this).attr('rel')
					}
				}).done(function() {
					th.addClass('checked');
					th.html("<span>"+time+"</span>");
				})
				.fail(function() {
					th.html("<span>Ошибка</span>");
				});
			}
		}
	});
});
</script>

<div style="width: <?php  echo $all ? 650 : 500 ;?>px; margin: auto;">
<img src="<?php echo SITE_URL; ?><?php echo $this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img" height="32"/>
<h1>
	<?php echo $this->getCurMenu()->getTitle();?>
	<span style="font-weight:normal">
		<?php  if ($all){?> - Общий список<?php } else {?> - Отмечайте уход с рабочего места!<?php } ?>
	</span>
</h1>
<br/>

<table border="1" bordercolor="000" style="text-align:center;" width="100%" cellpadding="0" cellspacing="0"
	<?php if ($all){?>id="fiveall"<?php } else {?>id="fiveown"<?php } ?> >

	<?php if (!$all) echo '<tr><td colspan="7" style="font-size:14px;padding:3px;">'.$this->website->getCustomer()->getFirstName().' '.$this->website->getCustomer()->getSecondName().'</td></tr>'; ?>
	<tr>
		<?php if ($all){?><td>ФИО</td><?php } ?>
		<td>1</td>
		<td>2</td>
		<td>3</td>
		<td>4</td>
		<td>5</td>
		<td>6</td>
		<td>7</td>
	</tr>
<?php foreach($this->fivem as $fivem){
	$ns = wsActiveRecord::findByQueryFirstArray('SELECT first_name,middle_name FROM ws_customers WHERE `id` ='.$fivem->getCustopmerId());
?>
<?php if (@$tmpdate != $fivem->getDate()){?>
	<tr>
		<td colspan="<?php  echo $all ? 8 : 7 ;?>" style="font-size:14px;padding:3px;"><?php echo $fivem->getDate(); ?></td>
	</tr>
<?php } ?>
	<tr>
		<?php if ($all) echo '<td style="font-size:14px;">'.$ns['first_name'].' '.$ns['middle_name'].'</td>'; ?>

		<?php for ($i = 1; $i <= 7; $i++) { ?>
		<td>
		<button class="check<?php echo ($fivem->{'getCheck'.$i}() > 0) ? ' checked' : ''; ?>" rel="<?php echo $i; ?>"> <?php
			echo ($fivem->{'getCheck'.$i}() > 0) ? '<span>'.$fivem->{'getCheck'.$i}().'</span>' : '';
		?></button>
		</td>
		<?php } ?>
	</tr>

<?php $tmpdate = $fivem->getDate(); } ?>
</table>



</div>