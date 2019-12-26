<div class="sl-page-title">
          <h5><?=$this->getCurMenu()->getTitle()?></h5>
          <p>...</p>
        </div>
<input hidden type="text" id="em" name="em"  value="">
<div class="card pd-20 pd-sm-40">
    <div class="card-body">
        <table id="productss" class="table table-hover table-bordered"  >
            <thead>
    <tr>
        <th><label class="ckbox" data-tooltip="tooltip" title="Выделить все заказы">
                <input onchange="chekAll();" class="chekAll" type="checkbox"/><span></span></label>
        </th>
	<th>Действие</th>
		<th>Заказ</th>
		<th>Статус</th>
        <th>Заказ Создан</th>
		<th>Дата доставки</th>
        <th>Время доставки</th>
		<th>Оплата</th>
    </tr>
            </thead>
            <tbody>
    <?php
	$count = 0;
	if($this->order_trekko){
            $row = 'row1';
    foreach ($this->order_trekko as $or)
    {
	$count ++;
        $row = ($row == 'row2') ? 'row1' : 'row2';
        ?>
             <tr class="<?=$row?>">
		<td>
                     <label class="ckbox">
                         <input type="checkbox" class="order-item cheker" id="<?=$or->id?>" name="item_<?=$or->id?>"/><span></span>
                     </label>
                </td>
                <td>
                    <a href="/admin/trekko/edit/<?=$or->id;?>/" target="_blank" >
                        <img src="/img/icons/edit-small.png" alt="Редактировать" width="24" height="24">
                    </a>
                </td> 
		<td><?=$or->id?></td>
		<td><?=$or->getStat()->getName()?></td>
                <td><?=$or->date_create?></td>
		<td><?=$or->delivery_date?></td>
		<td><?=$or->delivery_interval?></td>
		<td><?=$or->getPaymentMethod()->getName()?></td>
            </tr>
        <?php } 
		}
		?>
            </tbody>
</table>
    </div>
<div class="card-footer">
    
    <p style="margin-left:20px;">
        <span><?='Всего записей: '.$count?></span>
        <div class="btn-group" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-secondary" id="print" name="print">Печать реестра</button>
  <button type="button" class="btn btn-secondary" id="save" name="save"  >Создать заявку</button>
</div>

</p>
</div>

    
    
</div><!--card-->
<table  style="display:none;" id="resultat" class="table table-hover table-bordered" >
<tr><th>Создано</th><th>Ошибки</th></tr>
<tr><td id="ok" style="color:green;font-weight: bold;"></td><td id="off" style="color:red;font-weight: bold;"></td></tr>
</table>

<script >
function chekAll() {
		if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
            return false;
        }
        $('#save').click(function () {
		$('#resultat').fadeOut(500);
		var ord = '';
            if ($('.order-item:checked').val()) {
				
                i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						ord+= ',' + $(this).attr('id');
                    } else {
						ord += $(this).attr('id');
                    }
                    i++;
                });
				}
			if(ord != ''){	
			$.ajax({
			beforeSend: function(data) {
				//$('#save').html('Создается...');//attr('value', );
                                    $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).appendTo('body');
         
			},
			type: "POST",
			url: '/admin/trekko/',
			dataType: 'json',
			data: {method: 'add_ttn_all', id: ord },
			success: function(data) {
			console.log(data);

			if(data.ok.length != 0){
			//console.log('ok = '+data.ok);
			var oke = '';
                        for(var k in data.ok){
                            oke += 'Заказ '+data.ok[k].order_id +' добавлен!<br>';
                        }      
			$('#ok').html(oke);
				//console.log('oke = '+oke);
			}
			if(data.off.length != 0){
			//console.log('off'+data.off[0].error);
			var offe ='';
                        for(var z in data.off){
                            offe +=data.off[z].error+'!<br>';
                        }

				$('#off').html(offe);
			}
			if(data.cur !== ''){
			console.log('cur'+data.cur);
			}
			$('#resultat').fadeIn(500);
			},
			complete: function(data){
                            //$('#save').html('Создать');
                            $('#foo').detach();
			///$('#save').attr('value', 'Создать');
			},
			error: function(e, b) {
			console.log(e);
                        console.log(b);
			alert('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
                        return false;
			}
		});
		
		}
        });
		
			$('#print').click(function () {
			 if ($('.order-item:checked').val()) {
		var ord = '';
              var   i = 0;
                jQuery.each($('.order-item:checked'), function () {
                    if (i != 0) {
						ord+= ',' + $(this).attr('id');
                    } else {
						ord += $(this).attr('id');
                    }
                    i++;
                });
				}
				mailGo(ord);
	
			});
			
	function mailGo(id){
				
                    $.ajax({
			type: "GET",
			url: '/admin/exelkurer/',
			dataType: 'json',
			data: '&id='+id+'&flag=1',
			success: function( data ){
                             console.log(data);
			if(data){
                            window.open("/admin/trekko/prints/"+id, '_blank');
                        }
			//console.log(data);
			},
			error: function(e) {
                            console.log(e);
			$('#popup').html('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
				
                            fopen();
			}
		});
	return false;
		
		}
</script>
