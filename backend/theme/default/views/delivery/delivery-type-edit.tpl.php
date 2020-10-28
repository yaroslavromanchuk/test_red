<div class="card">
        <div class="card-body">
<?php if ($this->errors) { ?>
	<div class="alert alert-success" role="alert">
		<h4 class="alert-heading">Ошибка!</h4> 
			<?php	foreach ($this->errors as $error) { ?><p><?=$error?></p><hr><?php } ?>
	</div>
<?php } ?>
<?php if ($this->saved) { ?>
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Запись сохранена!</h4> 
</div>
<?php } ?>
<form method="POST" action="<?=$this->path?>delivery_type/edit/id/<?=(int)$this->delivery->getId()?>/">
    <div class="form-row">
        <div class="form-group col-md-3">
      <label for="name">Название</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Название" value="<?=$this->delivery->getName()?>">
    </div>
       
        <div class="form-group col-md-3">
      <label for="price">Стоимость</label>
      <input type="text" name="price" class="form-control" id="price" placeholder="Стоимость" value="<?=$this->delivery->price?>">
    </div>
         <div class="form-group col-md-2">
             <label for="active"></label>
             <label class="ckbox">
                <input type="checkbox" name="active" value="1"  id="active" <?=$this->delivery->active?'checked="checked"':''?>><span>Доступен для выбора</span>
              </label>
    </div>
    <div class=" form-group col-md-4">
        <label for="img">Картинка</label>
        <div class="input-group">
            <?php if($this->delivery->img){ ?>
            <div class="input-group-prepend">
                <span class="input-group-text"><img style="max-width: 40px;" src="<?=$this->delivery->img?>"></span>
  </div>
            <?php } ?>
  <input type="text" class="form-control" placeholder="url" id="img" name="img" aria-label="url"  value="<?=$this->delivery->img?$this->delivery->img:''?>" aria-describedby="basic-addon2">
  <div class="input-group-append">
    <a data-toggle="modal" data-target="#myModalMessage" href="/js/filemanager/dialog.php?type=1&field_id=img&relative_url=0&fldr=/delivery/&akey=dsflFWR9u2xQa" class="btn btn-outline-secondary" type="button">Open</a>
  </div>
  </div>
</div>
        </div>
     <!--<hr>
     <div class="col-md-12 text-center">
         <input type="button" class="btn btn-primary " onclick="loadFormPayment(<?=$this->delivery->id?>);"  value="Оплата"/>
          </div>-->
     <hr>
    <div class="form-row">
       
        <div class="col-md-12"><h4>Описание для клиентов</h4></div>
        <div class="form-group col-md-6">
      <label for="prices">Стоимость (текст)</label>
      <input type="text" name="prices" class="form-control" id="prices" placeholder="Стоимость" value="<?=$this->delivery->prices?>">
    </div>
        <div class="form-group col-md-6">
      <label for="time">Сроки доставки (текст)</label>
      <input type="text" name="time" class="form-control" id="time" placeholder="Сроки доставки" value="<?=$this->delivery->time?>">
    </div>
        <div class="form-group col-md-6">
      <label for="notice">Уведомления (текст)</label>
      <input type="text" name="notice" class="form-control" id="notice" placeholder="Уведомления" value="<?=$this->delivery->notice?>">
    </div>
        <div class="form-group col-md-6">
      <label for="note">Примечание (текст)</label>
      <input type="text" name="note" class="form-control" id="note" placeholder="Примечание" value="<?=$this->delivery->note?>">
    </div>
        <div class="form-group col-md-12">
      <label for="adress">Адресс доставки (текст)</label>
      <input type="text" name="adress" class="form-control" id="adress" placeholder="Адресс доставки" value="<?=$this->delivery->adress?>">
    </div>
</div>
      <hr>
      <div class="form-row">
          <div class="col-md-12"><h4>Доставка - Оплата</h4></div>
<?php
	foreach ($this->payments as $k => $payment) {
?>
         <div class="form-group col-md-6">
             <label for="<?=$payment->getId()?>" class="col-sm-5 col-form-label"><?=$payment->payment->getName()?></label>
    <div class="col-sm-7">
      <input type="text"  class="form-control" name="<?=$payment->getId()?>" id="<?=$payment->getId()?>" value="<?=$payment->price?>">
    </div>
            
  </div>
<?php
	}
?>
          </div> 
          <hr>
          <div class="col-md-12 text-center">
              <input type="submit" class="btn btn-primary btn-lg" name="savepage" id="savepage" value="Сохранить"/>
          </div>
            

        
</form>
            </div>
    </div>
<script>
    
    function loadFormPayment(e){
        $.ajax({
			url: '/admin/delivery_type/',
			type: 'POST',
			dataType: 'json',
			data: {id: e, method:'load_form_payment'},
			success: function (res) {
				//console.log(res);
			fopen('Способы оплаты', res, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
				error: function(e){
				console.log(e);
				}
		});
        
    }
$('#myModalMessage').on('show.bs.modal', function (e) {
   // console.log(e.relatedTarget.href);
  //  $('#myModalMessage .modal-body').html('<iframe width="770" height="600" src="'+e.relatedTarget.href+'" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>');
    
  // do something...
});
</script>