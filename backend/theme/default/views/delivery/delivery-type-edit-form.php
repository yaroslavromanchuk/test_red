<?php
if(isset($this->payment)){
   
    ?>
<form method="POST" class="payment-form" action="/admin/delivery_type/" name="load-form-payment">
   
   <?php
   
   foreach ($this->payment as $qq => $pay) {
       

//print_r($pay);//  echo $pay->delivery->name . ' - ' . $pay->payment->name . '<br>';
       ?>
         <div class="form-row">

      <input type="text" class="form-control" hidden="true"  name="payment[<?=$pay->id?>]['id']" placeholder="<?=$pay->id?>" value="<?=$pay->id?>">

      <input type="text" class="form-control" hidden="true"   name="payment[<?=$pay->id?>]['delivery_id']" placeholder="" value="<?=$pay->delivery->name?>">

    <div class="form-group col-md-3">
      <label for="name">Оплата</label>
      <input type="text" class="form-control"  name="payment[<?=$pay->id?>]['payment_id']" placeholder="" value="<?=$pay->payment->name?>">
    </div>
             <div class="form-group col-md-3">
      <label for="name">ФОП</label>
      <input type="text" class="form-control"  name="payment[<?=$pay->id?>]['fop']" placeholder="" value='<?=$pay->fopname->name?>'>
    </div>
               <div class="form-group col-md-3">
      <label for="name">Макс. сумма платежа</label>
      <input type="text" class="form-control"  name="payment[<?=$pay->id?>]['max_sum']" placeholder="" value="<?=$pay->max_sum?>">
    </div>
              <div class="form-group col-md-3">
      <label for="name">Стоимость доставки</label>
      <input type="text" class="form-control"  name="payment[<?=$pay->id?>]['price']" placeholder="" value="<?=$pay->price?>">
    </div>
              <div class="form-group col-md-3">
      <label for="active"></label>
             <label class="ckbox">
                <input type="checkbox" name="payment[<?=$pay->id?>]['active']" value="1"   <?=$pay->active?'checked="checked"':''?>><span>Доступен для выбора</span>
              </label>
    </div>
       </div>
   <?php } ?>
     <hr>
          <div class="col-md-12 text-center">
              <input type="submit" class="btn btn-primary btn-lg" name="save_page"  value="Сохранить"/>
          </div>
     
</form>
<?php } ?>

<script>
    
    $(".payment-form").submit(function (e) {
        e.preventDefault();
        var form = $(this);
      //  console.log(form.serializeArray());
         $.ajax({
			url: '/admin/delivery_type/',
			type: 'POST',
			dataType: 'json',
			data: {id: e, method:'save_form_payment', data: form.serialize()},
			success: function (res) {
				console.log(res);
			//fopen('Способы оплаты', res, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
				error: function(e){
				console.log(e);
				}
		});
//какие то действия
return false;
       // $(this).submit();
    });
</script>
