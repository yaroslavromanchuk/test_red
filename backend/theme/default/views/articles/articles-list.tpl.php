<?php if(isset($_SESSION['astrafit']) and !empty($_SESSION['astrafit'])){
    $art = wsActiveRecord::useStatic('Shoparticles')->findById($_SESSION['astrafit']);
    //unset($_SESSION['astrafit']);
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
    } ?>
<div class="card pd-20">
     <h6 class="card-body-title"><?=$this->getCurMenu()->getTitle()?></h6>
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
     <form action="<?=$this->path?>articles-add/listarticles/search/" method="GET" id="editForm">
<div class="row mg-b-25">
<div class="col-lg-4 mg-t-20 mg-lg-t-0">
                <div class="form-group  mg-b-0">
                  <input type="text" name="artikul" onkeyup="this.value = this.value.replace (/[^\d-,]/g, '')" class="form-control" placeholder="Штриихкод...">
                </div><!-- form-group -->
    </div><!-- col-4 -->
<div class="col-lg-4 mg-t-20 mg-lg-t-0">
              <div class="input-group">
                  <input type="text" name="code" class="form-control" value="<?=isset($_GET['code'])?$_GET['code']:''?>" placeholder="Накладная...">
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
<?php if($this->articles){ ?>

          <div class="table-wrapper">
		  <table class="table display responsive nowrap datatable1" >
		  <thead class="bg-info">
									<tr>
                                                                            <td>#</td>
									<td>Действие</td>
									<td>Модель</td>
									<td>Цена</td>
									<td>Накладна</td>
									<td>Размеры</td>
									</tr>
		</thead>
		<tbody>
		<?php
                $i = 1;
                foreach($this->articles as $a){ ?>
		  
		  <tr>
                      <td><?=$i?></td>
		  <td>
				  <a href="<?=$a->getPath()?>" target="_blank" data-tooltip="tooltip" data-placement="right" title="Смотреть">
				  <i class="menu-item-icon icon ion-md-eye tx-22 mg-10"></i>
				  </a>
				  <a href="<?=$this->path?>articles-add/edit/<?=$a->id?>/"  data-tooltip="tooltip"  title="Редактировать">
				  <i class="menu-item-icon icon ion-md-paper tx-22  mg-10" ></i>
				  </a>
				  </td>
									<td><?=$a->getTitle()?></td>
									<td><?=$a->getPrice()?></td>
									<td><?=$a->getCode()?></td>
									<td>
									<?php if($a->getSizes()){ ?>
								<!--	<table>
									<thead class="bg-primary">
									<tr><td>Код</td><td>Цвет</td><td>Размер</td><td>Колл.</td>
									</tr>
									</thead>-->
									<ol>
<?php foreach($a->getSizes() as $s){ ?>
<li><?=$s->code.', '.$s->color->name.', '.$s->size->size.':'.$s->count?></li>

								<!--	<tr>
									<td><?=$s->code;?></td>
									<td><?=$s->color->name;?></td>
									<td><?=$s->size->size;?></td>
									<td><?=$s->count;?></td>
									</tr>-->
<?php } ?>
</ol>
								<!--	</table>-->
									<?php } ?>
									</td>
									</tr>
								<?php $i++;	}?>
									</tbody>
</table> 

          </div><!-- table-wrapper -->

<?php } ?>

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
