<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1><?php echo $this->getCurMenu()->getPageBody();?>
<div style="text-align: center;" id="d" >
<h2><?php echo $this->trans->get('Выберите интервал дат'); ?>!</h2>
<p>от:<input class="form-control input" name="from" type="date" id="from" > | до:<input class="form-control input" name="to" type="date" id="to"></p>
</div>
 <div style="border: 1px dotted #aaa;padding: 10px; text-align:center;">
<h2>Исключить</h2> <label style="cursor: pointer;" >Тишкова<input type="checkbox"  class="checking" value="17192"></label> | <label style="cursor: pointer;" >Сахно<input type="checkbox"  class="checking" value="8985"></label> | <label style="cursor: pointer;" >Зисс<input type="checkbox"  class="checking" value="22832"></label> | <label style="cursor: pointer;" >Руденко<input type="checkbox"  class="checking" value="6862"></label> | <label style="cursor: pointer;" >Олеся<input type="checkbox"  class="checking" value="7668"></label> | <label style="cursor: pointer;" >Максим<input type="checkbox"  class="checking" value="22699"></label> | <label style="cursor: pointer;" >Сергей<input type="checkbox"  class="checking" value="24148"></label> | <label style="cursor: pointer;" >Ярик<input type="checkbox" checked  class="checking" value="8005"></label></br></br>
	<button class="button" name="send_ttn" id="send_ttn" onclick="go($('#from').val(), $('#to').val());"><?php echo $this->trans->get('Отобразить'); ?></button></br>
	</div></br>
	<div>
	<table id="popup" align="center" >

	</table>
	</div>
	 <script>

	 function go(x,y) {
	 
	  if(x!='' && y!=''){ 
	  $('#popup').html('');
	 //alert(chek());
	  var url = '/admin/controldellarticles/';
		var new_data = '&from='+x+'&to='+y+'&not='+chek()+'&metod=go';
		$.ajax({
		beforeSend: function( data ) {
		$('#popup').html('<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				$('#popup').html('<tr><th>№</th><th>Товар</th><th>Модель</th><th>Артикул</th><th>Админ</th><th>Процес</th><th>Колл.</th><th>Дата</th></tr>');
				for (i=0; i<res.send.length; i++) { 
				$('#popup').append('<tr><td>'+(i+1)+'</td><td>'+res.send[i]['tovar']+'</td><td>'+res.send[i]['model']+'</td><td>'+res.send[i]['articul']+'</td><td>'+res.send[i]['admin']+'</td><td>'+res.send[i]['proces']+'</td><td style="text-align: center;">'+res.send[i]['dell']+'</td><td>'+res.send[i]['ctime']+'</td></tr>');
				}
                }
            });
	  
	  }else{
	  alert('Укажите две даты для построения графика.');
if(x=='') {$('#from').focus();}else{$('#to').focus();}
	  
	  }
	 
	 }
	 function chek(){ 
	 var ch = '';
	 $( ':checkbox:checked' ).each(function(){
    ch += ","+this.value;// alert(this.value);
});
return ch;
}
	
	</script>