<style>.thumb{height:200px;}</style>

<?php if($this->errors){ ?>
<div class="alert alert-danger <?php if($this->errors) echo 'show';?>"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1">Возникли ошибки:</span>
    <ul>
		<?php foreach ($this->errors as $error) { ?><li><?=$error?></li><?php } ?>
	</ul>
</div>
<?php }elseif($this->post_list){ ?>
<div class="alert alert-danger show"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<span class="sr-only1">Содержимое массива:</span>
		<?=$this->post_list?>
</div>
<?php } ?>

    <?php if($this->article){ ?>

<form action="<?=$this->path?>articles-add/edit/<?=$this->article->id?>" method="POST" class="was-validated" id="editform"  enctype="multipart/form-data">

<div id="wizard">
    <div class="card mt-3">
        <div class="card-body">
            <h6 class="card-body-title">Категория & Пол & Сезон</h6>
            <section>
			  <div class="row mg-b-25">
              <div class=" col-sm-12 col-md-6 col-lg-4 col-xl-4">
                  <div class="form-group ">
                  <label class="form-control-label">Категория: <span class="tx-danger">*</span></label>
                  <select class="form-control select2-show-search" name="category" id="category" data-placeholder="Выберите категорию"  required>
                    <option label="Выберите категорию"></option>
					<?php if($this->categories){
					$mas = array();
					foreach ($this->categories as $cat){
                                            $mas[$cat->getId()] = $cat->getRoutez();  
                                        }
					asort($mas);
				foreach($mas as $key => $value){
				if(strripos($value, 'SALE') === FALSE){  ?>
             <option 
 <?php /*if($this->article->category_id == $key){
     echo 'selected';
 }elseif(!empty($_COOKIE['category']) && $_COOKIE['category'] == $key){
     echo 'selected';
 } */?>
                 value="<?=$key?>"
                     ><?=$value?></option>
			 <?php 
                         
 }
					}
					} ?>
                  </select>
                  </div>
                  <div class="form-group ">
                      <label class="form-control-label">Дополнительная Категория: </label>
                  <select class="form-control select2-show-search" name="dop_cat_id" id="dop_cat_id" data-placeholder="Выберите доп. категорию"  >
                    <option label="Выберите категорию"></option>
					<?php if($this->categories){
					$mas = array();
					foreach($this->categories as $cat){ $mas[$cat->getId()] = $cat->getRoutez();}
					asort($mas);
				foreach($mas as $kay => $value){ ?>
             <option value="<?=$kay?>" <?php if($this->article->dop_cat_id == $key){ echo 'selected'; } ?> ><?=$value?></option>
			 <?php }
                                        }?>
                  </select>
                  </div>
                </div><!-- form-group -->
					<script>
                                            
                                   /*       $(function(){
                                                if($( "#category" ).val()){
                                                    $.ajax({
            url: '/admin/articles-add/',
            dataType: 'json',
            type: 'POST',
            data: "&method=opisanie&category=" + $( "#category" ).val(),
            success:function(data){
			if(data.status == 'ok'){
			$('.text_opis').html(data.result);
			console.log(data);
			}
            },
			error:function(data){
			console.log(data);
			}
        });
                                                }
                                            });
	$( "#category" ).change(function() {
$.ajax({
            url: '/admin/articles-add/',
            dataType: 'json',
            type: 'POST',
            data: "&method=opisanie&category=" + $(this).val(),
            success:function(data){
			if(data.status == 'ok'){
			$('.text_opis').html(data.result);
			console.log(data);
			}
            },
			error:function(data){
			console.log(data);
			}
        });
});*/
</script>
<div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
    <div class="form-group  ">
                  <label class="form-control-label">Продавец: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="shop_id" id="shop_id" data-placeholder="Выберите продавца"  required>
                    <option label="Выберите продавца"></option>
					<?php foreach(wsActiveRecord::useStatic('Shop')->findAll(['active'=>1]) as $p){ ?>
					<option value="<?=$p->getId()?>" <?php if(@$this->article->getShopId() and $p->getId() == $this->article->getShopId()){ echo 'selected'; }?> ><?=$p->getName()?></option>
					<?php } ?>
                  </select>
                </div>
<div class="form-group">
                  <label class="form-control-label">Бренд: <span class="tx-danger">*</span></label>
                  <select class="form-control select2-show-search" name="brand" id="brand" data-placeholder="Выберите бренд"  required>
                    <option label="Выберите бренд"></option>
					<?php if($this->brands){
				foreach($this->brands as $b){
				 ?>
             <option value="<?=$b->id?>" <?php if($b->id == $this->article->brand_id){ echo'selected';}?>><?=$b->name?></option>
			 <?php 
					}
					}?>
                  </select>
                </div><!-- form-group -->
		
</div>
<div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
<div class="form-group  ">
                  <label class="form-control-label">Пол: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="size_type" id="size_type" data-placeholder="Выберите пол"  required>
                    <option label="Выберите пол"></option>
					<?php foreach($this->sex as $s){ ?>
					<option value="<?=$s->getId()?>" <?php if(@$this->article->getSizeType() and $s->getId() == $this->article->getSizeType()){ echo 'selected'; }?> ><?=$s->getName()?></option>
					<?php } ?>
                  </select>
                </div>

                          </div>
		<div class="form-group  col-sm-12 col-md-6 col-lg-2 col-xl-2">
                  <label class="form-control-label">Сезон: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="sezon" id="sezon" data-placeholder="Выберите сезон"  required>
                    <option label="Выберите сезон"></option>
					<?php foreach($this->sezon as $s){ ?><option value="<?=$s->getId()?>" <?php if(@$this->article->getSezon() and $s->getId() == $this->article->getSezon()){ echo 'selected'; }?> ><?=$s->getName()?></option><?php } ?>
                  </select>
                </div>
<div class="form-group  col-sm-12 col-md-6 col-lg-2 col-xl-2">
                  <label class="form-control-label">Модель:</label>
                  <select class="form-control select2" name="model_id" id="model_id" data-placeholder="Выберите модель">
                     <option value="0">Без модели</option>
					<?php foreach(wsActiveRecord::useStatic('Shoparticlesmodel')->findAll(['active'=>1]) as $s){ ?>
                     <option value="<?=$s->getId()?>" <?php if($this->article->model_id and $s->getId() == $this->article->model_id){ echo 'selected'; }elseif(!empty($_COOKIE['model_id']) && $_COOKIE['model_id'] == $s->getId()){ echo 'selected';}?> ><?=$s->name?></option>
					<?php } ?>
                  </select>
                </div>
		 		
		</div>
            </section>
          </div>
        </div>
   <?php  if(!$this->article->image){ ?>
    <div class="card mt-3">
    <div class="card-body">
         <h6 class="card-body-title">Фото</h6>
         <div class="row">
             <div class="col-sm-12 col-md-2">
                 <label class="custom-file">
                <input type="file" id="image_file"  name="image_file[]" onchange="previewFile(this)" multiple class="custom-file-input" required>
                <span class="custom-file-control custom-file-control-primary"></span>
              </label>
        
             </div>
             
                        <div class="col-sm-12 col-md-10"><p class="image_file"></p></div>
         </div>
    </div>
    </div>
    <?php } ?>
    <div class="card mt-3">
    <div class="card-body">
         <h6 class="card-body-title">Состав & Описание</h6>
            <section>
                
                <div class="row m-auto">
                    
                    <div class="col-sm-12 col-md-12 col-lg-6 border border-info">
                        
                         <div class="card">
	<div class="card-body">
             <h5 class="card-body-title">Состав</h5>
             <div class="row">
                <?php $sostav_array = $this->article->sostav?unserialize($this->article->sostav):[];
                if(count($sostav_array) > 0){
                 
                    $i = 1;
                    foreach ($sostav_array as $name_sostav => $procent_sostava){ ?>
                 <div class="col-sm-12 s_sost" data-id="<?=$i?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control sostav ui-autocomplete-input" name="sostav[<?=$i?>][name]" required="" value="<?=$name_sostav?>" autocomplete="off">
                                    <input type="text" class="form-control" name="sostav[<?=$i?>][value]" value="<?=$procent_sostava?>">
                                    <span class="input-group-btn">
                                        <a class="btn btn-danger" data-toggle="reroute" data-tag="dell">Удалить</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                 <?php $i++;   }
                 
                    
                } ?>
                    <a class="btn btn-success" data-toggle="reroute" data-tag="add">+</a>
             </div>
	</div>
	
</div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 border border-primary">
                         <div class="card">
                         <div class="card-body">
                             <h5 class="card-body-title">Описание</h5>
                             <?php $opis = $this->article->long_text?unserialize($this->article->long_text):[];
                           
                             ?>
                             <div class="form-group  ">
                  <label class="form-control-label">Страна производителя: </label>
                  <input type="text"  class="form-control country" name="opis[Страна производителя]" <?php
                  if(count($opis)){
      foreach ($opis as $o => $p){
      if($o == 'Страна производителя') { echo 'value="'.$p.'"';}
      }
                  }elseif($this->article->brand_id){
          echo 'value="'.$this->article->brands->country_brand.'"';
      } ?>  > 
                </div>
                             <div class="form-group  ">
                  <label class="form-control-label">Соответствие размеров: </label>
                  <select class="form-control select2" name="soot_rozmer" data-placeholder="Выберите соответствие">
                   <option label="Выберите соответствие"></option>
                   <option value="Европейские размеры." <?=$this->article->soot_rozmer == "Европейские размеры."?'selected':''?> >Европейские размеры.</option>
	<option value="Наши размеры." <?=$this->article->soot_rozmer == "Наши размеры."?'selected':''?>>Наши размеры.</option>
	<option value="Французские размеры." <?=$this->article->soot_rozmer == "Французские размеры."?'selected':''?>>Французские размеры.</option>
	<option value="Размеры США." <?=$this->article->soot_rozmer == "Размеры США."?'selected':''?>>Размеры США.</option>
	<option value="Итальянские размеры." <?=$this->article->soot_rozmer == "Итальянские размеры."?'selected':''?>>Итальянские размеры.</option>
                  </select>
                </div>
                     
                         </div>
                            </div>
                    </div>
                </div>

            </section>
    </div>
        <div class="card-footer">
             <!-- <p>Проверте правильность введенных данных для сохранения!</p>
			  
			   <label class="ckbox">
					<input type="checkbox" name="ontop" value="1" ><span>Отобразить на главной.</span>
				</label>-->
            <div class="form-group  text-center">
                    <input type="submit" name="save" id="save" value="Сохранить" class="btn btn-success btn-lg">
              </div>
        </div>
            
    </div>
          <!--  <section>
              <p>Внесити необходимые данные и нажмите кнопку "Создать".</p>

	<div class="row mg-b-25 border border-dark">
	
	<div class="form-group  col-sm-12 col-md-12 col-lg-12 col-xl-12">
             <button type="button" onclick="Add_Opis();return false;" class="btn btn-info btn-lg m-2 pd-x-20" >Создать</button>
	<div class="align-text-bottom">
			 <label class="form-control-label">Описание: <span class="tx-danger">*</span></label>
                  <textarea rows="8" cols="100" class="form-control" id="long_text" name="long_text" required></textarea>
				  </div>
	</div>
	
                 
	</div>
	
		<script>

function Add_Opis(){
var arr_op = [];
$('input:checked.long_checkbox').each(function(){
var inp = $(this).parent().parent()[0];
console.log(inp);
var span = $(this).parent().parent()[0].nextElementSibling;
var input = $(this).parent().parent()[0].nextElementSibling.nextElementSibling;
var val = '';
if(input.type == 'select-multiple'){
console.log(input.name);
$('[name="'+input.name+'"] option:selected').each(function() {
      val += $(this).text() + " ";
    });
}else{
val = input.value;
}
//console.log(input);
//console.log(input.type);
arr_op.push(span.innerHTML+': '+val);
});
  long_text.innerHTML = arr_op.join(';<br>\n')+'.';
}
</script>

            </section>-->
                        
          </div>
<!--<input type="submit" name="save" value="Сохранить" style="display:none;">	-->	  
  </form>


<?php } ?>
</div>
    <script>
       
              $(function() {
        });
        
         $(document).on('click', '[data-toggle=reroute]', function(e) {
        console.log(this);
        //add
    if($(this).data().tag == 'add'){
        var block = $(this).prev(".s_sost");
        console.log(block);
        if(block.length){
          //  var id = block.data('id');
            var id_new =  block.data('id')+1;
        }else{
            var id_new =  1;
        }
        if(!id_new){
            id_new = 0;
        }
        console.log(id_new);
        var el = '<div class="col-sm-12 s_sost" data-id="'+id_new+'"><div class="form-group"><div class="input-group"><input type="text"  class="form-control sostav" name="sostav['+id_new+'][name]" required  ><input type="text"  class="form-control" name="sostav['+id_new+'][value]" ><span class="input-group-btn"><a class="btn btn-danger" data-toggle="reroute" data-tag="dell">Удалить</a></span></div></div></div>';
        
      //  var block = $(this).prev(".col-sm-12");
       // var cln = block.clone();
//cln.find("input:first").val('');
//$(this).before(cln);
        $(this).before(el);
        
            $('.sostav').autocomplete({
                maxHeight: 400, // Максимальная высота списка подсказок, в пикселях
    width: 300, // Ширина списка
    zIndex: 9999, // z-index списка
                source: <?=$this->sost?>
                
            });
            return false;
            }else{
         var block = $(this).parents(".col-sm-12:first");
        console.log(block);
     block.detach();
     return false;
            }
    });

      $(function(){

        'use strict';

		/*$('#wizard').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          autoFocus: true,
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanging: function (event, currentIndex, newIndex) {
            if(currentIndex < newIndex) {
              // Step 1 form validation
              if(currentIndex === 0) {
                var category = $('#category').parsley();
                var brand = $('#brand').parsley();
                var shop = $('#shop_id').parsley();
                var img = $('#image_file').parsley();
				var size_type = $('#size_type').parsley();
				var sezon = $('#sezon').parsley();
                              //  var model = $('#model_id').parsley();
                if(category.isValid()  && size_type.isValid() && sezon.isValid() && brand.isValid() && shop.isValid() && img.isValid()) {
				return true;
               } else {
                 category.validate();
                 brand.validate();
				 size_type.validate();
				 sezon.validate();
                                 shop.validate();
                                img.validate();
               }
              }
              // Step 2 form validation
              if(currentIndex === 1) {
			// var sost = $('#sostav').parsley();
			// if(sost.isValid()) {
                 return true;
              //  } else {
				//sost.validate();
				//}

              }
			  if(currentIndex === 2){
               var long_t = $('#long_text').parsley();

                if(long_t.isValid()) {
				$(".list_save").html('');
				$(".list_save").append('<li>Категория: '+$("#category option:selected").text()+'</li>');
                                if($("#dop_cat_id option:selected").text()){ $(".list_save").append('<li>Доп. Категория: '+$("#dop_cat_id option:selected").text()+'</li>'); }
					$(".list_save").append('<li>Пол: '+$("#size_type option:selected").text()+'</li>');
					$(".list_save").append('<li>Сезон: '+$("#sezon option:selected").text()+'</li>');
				$(".list_save").append('<li>Соответствие: '+$("#soot_rozmer option:selected").text()+'</li>');
				$('.image_file img.thumb').clone().appendTo('.list_save');
				//$('.image_file_2 img.thumb').clone().appendTo('.list_save');
				$(".list_save").append('<li>Состав: '+$("#sostav").text()+'</li>');
				$(".list_save").append('<li>Описание: '+$("#long_text").text()+'</li>');
				$("#save").prop('checked', true);
                 return true;
                } else {
				long_t.validate();
				}
			  }
            // Always allow step back to the previous step even if the current step is not valid.
            } else {
			return true; 
			}
          }
        });//end steps*/
      });//end ready
	  function previewFile(etv){
	console.log(etv.name);
	console.log(etv.files);
         var str = etv.name.substring(0, etv.name.length - 2);
         $('.'+str).html('');
  //var preview = $('#'+etv.id+'_view');
$(etv.files).each(function () {
    var reader = new FileReader();
            reader.readAsDataURL(this);
            reader.onload = function (e) {
               
	//$('.'+str).append(['<img class="thumb" " src="', reader.result, '" />'].join(''));
               $('.'+str).append("<img class='thumb' src='" + e.target.result + "'>");
            }
});
/*
  var file    = etv.files[0];
  var reader  = new FileReader();

   reader.onloadend = function () {
       var str = etv.name.substring(0, etv.name.length - 2);
	$('.'+str).html(['<img class="thumb" " src="', reader.result, '" />'].join(''));
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
  }
  */
 
}
	function add_img() {
		console.log('fghdftg');
		
		var url = '/admin/shop-articles/edit/id/<?=$this->article->getId();?>';
                        $.post(
                            url,
                            "addimage=on",
                            function (data) {
var c = '<div class="form-group  col-sm-12 col-md-12 col-lg-6 col-xl-6"><label class="form-control-label">Доп.Рисунок:</label><div class="col-lg-12 mg-t-40 mg-lg-t-0 wd-xs-300"><label class=" custom-file"><input  class="custom-file-input image" name="images_file'+ data.id +'" type="file" onchange="previewFile(this)"   required><span class="custom-file-control custom-file-control-primary"></span></label></div><div class="col-lg-12 mg-t-40 mg-lg-t-0"><p class="images_file'+ data.id +'"></p></div></div>';
                                $('#addimage').before(c);
                                console.log(data);
                                console.log(data.id);

                            },
                            "json"
                        ).fail(function () {
                                console.log("error");
                            });
		};
    </script>	