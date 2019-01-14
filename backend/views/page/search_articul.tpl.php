<div class="card pd-20">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-center bg-gray-100 ht-md-80 bd pd-x-20">
		<form action="" method="POST" id="myform" align="center" class="">
                    <div class="d-md-flex pd-y-20 pd-md-y-0">
                        <input type="text" class="form-control" onkeyup="var yratext=/['%']/; if(yratext.test(this.value)) this.value=''" name="articul" id="articul"  value="" autofocus placeholder="Артикул">
                        <button type="submit" class="btn btn-info pd-y-13 pd-x-20 bd-0 mg-md-l-10 mg-t-10 mg-md-t-0" name="go"><?=$this->trans->get('Найти'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    <div class="card-body">
        <div class="row">
            <div id="art" class="col-md-12 col-xl-6 text-center"></div>
            <div class="col-md-12 col-xl-6">
                <div class="col-xl-12" id="add"></div>
                <div class="col-xl-12" id="dell"></div>
                <div class="col-xl-12" id="order"></div>
                <div class="col-xl-12" id="ret"></div>
                <div class="col-xl-12" id="ost"></div>
                <div class="col-xl-12" id="cat"></div>
                <div class="col-xl-12" id="code"></div>
                <div class="col-xl-12" id="sr"></div>
                <div class="col-xl-12">
                    <p>
                        <a  id="a" href="" class="shoping d-inline-block" data-toggle="tooltip"  title="" target="_blank"  ></a> 
                        <a id="ed" href="" class="d-inline-block" title="Редактировать" data-placement="top" data-original-title="Tooltip on top" data-toggle="tooltip"  target="_blank"></a> 
                        <a id="ist" href="" class="history d-inline-block" data-toggle="tooltip"  title="История" target="_blank"></a> 
                        <a id="tov" href="" class="d-inline-block" title="Товары" data-toggle="tooltip"  target="_blank"></a> 
                    </p>
                </div>
            </div>
        </div>
    </div>
    


</div>

	

<script>
$('.history').click(function (e) {
var id = $('.history').data('id');
$.get('/admin/articlehistory/id/'+id+'/m/1',function (data) {fopen('История изменения товара', data);});	
return false;
});
$('.shoping').click(function (e) {
var id = $('.shoping').data('id');
$.get('/admin/ordersbyartycle/id/'+id+'/m/1',function (data) {fopen('История покупок товара', data);});	
return false;
});

$('#myform').submit(function(){
var sr = 'SR';

var z = $('#articul').val();//.replace('%', '');
console.log('sr = '+z);
var s = z[0]+z[1];
console.log('s - '+s);
var l = z.length;
console.log('l - '+l);

//if( z != '' && l == 16){ 
//if(s == 'SR'){
	  var url = '/admin/search_articul/';
		var new_data = '&articul='+ z;
		$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
                    console.log(res);
                    if(res.error){
                        $('#art').html(res.error);
                        return false;
                    }
					//z=z.substring(1);
				
				var img = '<img style="width:100%"  id ="a_' + res.img + '" src="' + res.img + '"  />';
				$('#art').html(z + '<br>' + img);


$('#add').html('<p>Добавлено - '+res.add+'</p>');
$('#dell').html('<p>Удалено - '+res.del+'</p>');
$('#order').html('<p>В заказах - '+res.order+'</p>');
$('#ret').html('<p>На возврате - '+res.return+'</p>');
$('#ost').html('<p>Остаток - '+res.ostatok+'</p>');
$('#cat').html('<p>Категория - '+res.cat+'</p>');
$('#code').html('<p>Текущий артикул - '+res.code+'</p>');
$('#sr').html('<p>Старый артикул - '+res.sr+'</p>');

$('#a').attr("title", 'Куплено '+res.cup+' раз.');
///$('#a').html('Куплено '+res.cup+' раз.');
$('#ed').attr("href", "/admin/shop-articles/edit/id/"+res.id);
$('#ed').html('<i class="menu-item-icon icon ion-ios-create-outline tx-30 pd-5"></i>');
$('#a').html('<i class="menu-item-icon icon ion-ios-basket  tx-30 pd-5"></i>');
//$('#ist').attr("href", "/admin/articlehistory/id/"+res.id);
$('#ist').html('<i class="menu-item-icon icon ion-ios-help-circle-outline  tx-30 pd-5"></i>');
$('.history').data("id", res.id);
$('.shoping').data("id", res.id);



$('#tov').attr("href", "/admin/shop-articles/?search=&search_artikul="+z);
$('#tov').html('<i class="menu-item-icon icon ion-md-filing  tx-30 pd-5"></i>');

                },
                 error: function(res){
                     console.log(res);
                 }
            });
            /*
}else{
alert('Смените язык клавиатуры на английский');
return false;
}
	  }else{
	  var zz = 16-l;
	  alert('Введите артикул. В артикуле нехватает '+zz+' символов');
	   return false;
	  }
          */
	  
$("#articul").val('');
$("#articul").focus();
	  return false;
	});

</script>