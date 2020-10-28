<?php if(isset($_SESSION['astrafit']) and !empty($_SESSION['astrafit'])){
    $art = wsActiveRecord::useStatic('Shoparticles')->findById($_SESSION['astrafit']);
    unset($_SESSION['astrafit']);
    if($art->id and $art->category->astrafit){
        $s = [];
 foreach ($art->sizes as $k => $size) {
     $s[] = $size->size->size;
 }
    ?>
<!-- AstraFit.Loader -->
<script>
(function(d, s, id, host, ver, shopID, locale){ 
    var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;
    d.astrafitSettings={host:host,ver:ver,shopID:shopID,locale:locale};
    js=d.createElement(s);js.id=id;js.async=true;js.src=host+"/js/loader."+ver+".min.js";
    fjs.parentNode.insertBefore(js,fjs);
}(document, "script", "astrafit-loader", "https://widget.astrafit.com", "latest", 464, "ru"));
</script>
<!-- /AstraFit.Loader -->
    <div class="alert alert-info" role="alert"><strong><?=$art->id?></strong><br>
    <div class="astrafit-wdgt" 
 data-id="<?=$art->id?>"  
 data-sizes-list="<?=implode($s, ',')?>" 
 data-canonical="https://www.red.ua<?=$art->getPath()?>"
 data-img="https://www.red.ua<?=$art->getImagePath('card_product')?>"
></div>
    </div>
    
    
    <?php }
    }?>
<?php
if($this->save){ ?>
<div class="alert alert-success <?php if($this->save){ echo 'show';}?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1"><?=$this->save?></span>
</div>
<?php } ?>
<?php if($this->errors){ ?>
<div class="alert alert-danger <?php if($this->errors) {echo 'show';}?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1">Возникли ошибки:</span>
    <ul>
		<?php foreach ($this->errors as $error) { ?><li><?=$error?></li><?php } ?>
	</ul>
</div>
<?php } ?>
<div class="card pd-20 pd-sm-40">
<h6 class="card-body-title">Форма загрузки товара</h6>
<p class="mg-b-20 mg-sm-b-30">Здесь Вы можете загрузить новый товар, посмотреть уже загруженый и добавить содержимое.</p>

<form action="<?=$this->path?>articles-add/listarticles/search/" method="POST" id="editForm">
<div class="row mg-b-25">
<div class="col-lg-4 mg-t-20 mg-lg-t-0">
                <div class="form-group  mg-b-0">
                  <input type="text" name="artikul" onkeyup="this.value = this.value.replace (/[^\d-,]/g, '')" class="form-control" placeholder="Штриихкод...">
                </div><!-- form-group -->
    </div><!-- col-4 -->
<div class="col-lg-4 mg-t-20 mg-lg-t-0">
              <div class="input-group">
                <input type="text" name="code" class="form-control" placeholder="Накладная...">
                <span class="input-group-btn">
                  <button class="btn bd bg-white tx-gray-600" type="submit"><i class="fa fa-search" data-tooltip="tooltip" title="Искать товары"></i></button>
                </span>
              </div><!-- input-group -->
			
            </div>
    <div class="col-lg-4 mg-t-20 mg-lg-t-0" ng-app="" id="f_go_tegram">
         <div class="input-group" >
               
                    <input type="text"  ng-model="nakladna" id="input_telegram" pattern="^[ 0-9]+$" onkeyup="this.value = this.value.replace (/\D/, '')" class="form-control" placeholder="№ накладной...">
                <span class="input-group-btn">
                    <button class="btn bd bg-white tx-gray-600" onclick="otpravka($('#sendTelegram').html());  return false;" type="button"><i class="ion-ios-send-outline" data-tooltip="tooltip" title="Отправить уведомление"></i></button>
                </span>
              </div><!-- input-group -->
               <p id="sendTelegram">Накладная № {{ nakladna }} готова!</p>
    </div>
        
	  </div>		
  </form>

</div>
<div class="card pd-20 pd-sm-40 mg-t-50">
          <h6 class="card-body-title">Загрузить новую накладную</h6>
          <p class="mg-b-20 mg-sm-b-30">Выберите файл наскладной и нажмите "Загрузить".</p>
		  <form action="<?=$this->path?>articles-add/loadexcel/load/" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
			 <div class="col-lg-3 mg-t-40 mg-lg-t-0">
              <label class="custom-file">
                <input type="file" name="excel_file" class="custom-file-input" data-tooltip="tooltip" title="Выберите Excel файл для загрузки товара">
                <span class="custom-file-control custom-file-control-primary" ></span>
              </label>
            </div><!-- col -->
			<div class="col-lg-3 d-none">
              <label class="rdiobox" >
                <input name="version" type="radio" value="1">
                <span data-tooltip="tooltip" title="Наклыдные - коректировки">Старая версия</span>
              </label>
			   <label class="rdiobox" >
                               <input name="version" type="radio" checked="true" value="2">
                <span data-tooltip="tooltip" title="Наклыдные с расценки">Новая версия</span>
              </label>
            </div><!-- col-3 -->
            </div><!-- row -->

            <div class="form-layout-footer">
              <button class="btn btn-info mg-r-5" type="submit" data-tooltip="tooltip" title="Загрузить содержимое Excel файла">Загрузить</button>
              <button class="btn btn-secondary" data-tooltip="tooltip" title="Очистить данные формы">Очистить</button>
            </div><!-- form-layout-footer -->
          </div>
		  </form>
		 
        </div>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
    
    
    function otpravka(text){
     if($('#input_telegram').val()!= ''){
         $('#f_go_tegram').hide();
  $.ajax({  
  type: "GET",  
  url: "https://api.telegram.org/bot539849731:AAH9t4G2hWBv5tFpACwfFg3RqsPhK4NrvKI/sendMessage?",
  data: "chat_id=530803601&parse_mode=HTML&text="+encodeURIComponent(text), 
  }); 
     }else{
         alert('Введите номер накладной для отправки сообщения!');
     }
  return false;
 };
    </script>