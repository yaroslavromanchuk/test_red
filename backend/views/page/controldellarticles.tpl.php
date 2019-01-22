<div class="row row-sm mg-x-0">
    <div class="col-sm-12">
        <div class="card pd-30">
            <h6 class="card-body-title"><?=$this->trans->get('Выберите интервал дат')?></h6>
<div class="input-group">
    <div class="input-group-prepend">
    <span class="input-group-text" >От</span>
  </div>
 <input class="form-control" name="from" type="date" id="from" >
 <input class="form-control" name="to" type="date" id="to">
   <div class="input-group-prepend">
    <span class="input-group-text" >До</span>
  </div>
</div>
<br>
 <div class="input-group">
    <span>Исключить</span>
<label class="ckbox mg-b-0" >
    <input type="checkbox"  class="checking" value="22832">
<span>Зисс</span>
</label>
<label class="ckbox mg-b-0" >
     <input type="checkbox"  class="checking" value="7668">
 <span>Олеся</span>
 </label>
<label class="ckbox mg-b-0" >
     <input type="checkbox"  class="checking" value="24148">
  <span>Сергей</span>
 </label>
<label class="ckbox mg-b-0" >
     <input type="checkbox" checked  class="checking" value="8005">
       <span>Ярик</span>
 </label>
<br>
    <button class="btn btn-info mg-r-5" name="send_ttn" id="send_ttn" onclick="go($('#from').val(), $('#to').val());"><?=$this->trans->get('Отобразить')?></button></br>
</div>
</div>
        </div>
  <div class="col-sm-12">
    <table   class="table text-center m-auto table-hover" >
        <thead>
            <tr>
                <th>№</th>
                <th>Товар</th>
                <th>Модель</th>
                <th>Артикул</th>
                <th>Админ</th>
                <th>Процес</th>
                <th>Колл.</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody id="popup_res">
            
        </tbody>
    </table>
</div>
	 <script>

	 function go(x,y) {
	 
	  if(x!='' && y!=''){ 
	  
	 //alert(chek());
	  var url = '/admin/controldellarticles/';
		var new_data = '&from='+x+'&to='+y+'&not='+chek()+'&metod=go';
		$.ajax({
		beforeSend: function() {
                    $('#popup_res').html('');
                            //$('#popup').html('<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    console.log(res);
		//$('#popup_res').html('');
				for (i=0; i<res.send.length; i++) { 
				$('#popup_res').append('<tr><td>'+(i+1)+'</td><td>'+res.send[i]['tovar']+'</td><td>'+res.send[i]['model']+'</td><td>'+res.send[i]['articul']+'</td><td>'+res.send[i]['admin']+'</td><td>'+res.send[i]['proces']+'</td><td style="text-align: center;">'+res.send[i]['dell']+'</td><td>'+res.send[i]['ctime']+'</td></tr>');
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