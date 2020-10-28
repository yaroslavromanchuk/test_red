<style>.thumb{height:200px;}</style>
<div class="card pd-30">
<h6 class="card-body-title">Форма редактирования товара</h6>
<p class="mg-b-20 mg-sm-b-30">Здесь Вы можете добавить недостающею информацию о товаре и отправить его на размещение.</p>
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

<form action="<?=$this->path?>articles-add/edit/<?=$this->article->id?>" method="POST" id="editform" enctype="multipart/form-data">

<div id="wizard">
            <h3>Категория & Пол & Сезон</h3>
            <section >
              <p>Укажите параметры товара!</p>
			  <div class="row mg-b-25">
              <div class="form-group  col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <label class="form-control-label">Категория: <span class="tx-danger">*</span></label>
                  <select class="form-control select2-show-search" name="category" id="category" data-placeholder="Выберите категорию"  required>
                    <option label="Выберите категорию"></option>
					<?php if($this->categories){
					$mas = array();
					foreach($this->categories as $cat){ $mas[$cat->getId()] = $cat->getRoutez();}
					asort($mas);
				foreach($mas as $kay => $value){
				if(strripos($value, 'SALE') === FALSE){ ?>
             <option value="<?=$kay?>"><?=$value?></option>
			 <?php }
					}
					}?>
                  </select>
                </div><!-- form-group -->
					<script>
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
});
</script>
		<div class="form-group  col-sm-12 col-md-6 col-lg-2 col-xl-2">
                  <label class="form-control-label">Пол: <span class="tx-danger">*</span></label>
                  <select class="form-control select2" name="size_type" id="size_type" data-placeholder="Выберите пол"  required>
                    <option label="Выберите пол"></option>
					<?php foreach($this->sex as $s){ ?>
					<option value="<?=$s->getId()?>" <?php if(@$this->article->getSizeType() and $s->getId() == $this->article->getSizeType()){ echo 'selected'; }?> ><?=$s->getName()?></option>
					<?php } ?>
                  </select>
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
                    <option label="Выберите модель"></option>
                     <option>Без модели</option>
					<?php foreach(Shoparticlesmodel::find('Shoparticlesmodel') as $s){ ?>
					<option value="<?=$s->getId()?>" <?php if($this->article->model_id and $s->getId() == $this->article->model_id){ echo 'selected'; }?> ><?=$s->name?></option>
					<?php } ?>
                  </select>
                </div>
				
				</div>
            </section>
            <h3>Рисунки</h3>
            <section>
              <p>Выберите минимум один рисунок.</p>
	<div class="form-layout">
            <div class="row mg-b-15">
			<div class="form-group  col-sm-12 col-md-12 col-lg-6 col-xl-6">
			 <label class="form-control-label">Рисунок-1: <span class="tx-danger">*</span></label>
			 <div class="col-lg-12 mg-t-40 mg-lg-t-0 wd-xs-300">
                <label class=" custom-file">
                <input  class="custom-file-input image" id="image_file" name="image_file" onchange="previewFile(this)" type="file"   required >
				
				<span class="custom-file-control custom-file-control-primary"></span>
				</label>
              </div>
			  <div class="col-lg-12 mg-t-40 mg-lg-t-0"><p class="image_file"></p></div>
			  </div>
			  <input type="button" name="addimage" id="addimage" onClick="add_img();"  style="max-height: 50px;" class="btn btn-small btn-default" value="Добавить изображение">
			</div>
			
			</div>
			
            </section>
            <h3>Состав</h3>
            <section>
              <p>Внесити необходимые данные и нажмите кнопку "Создать".</p>
<div class="form-layout">
	<div class="row mg-b-25 border border-dark">
	<div class="form-group  col-sm-12 col-md-12 col-lg-12 col-xl-12">
			 <label class="form-control-label">Состав: <span class="tx-danger">*</span></label>
      <div class="text_sostav row">
            <?php
			if($this->sostav){
			foreach($this->sostav as $s){ ?>
			<div class="input-group col-sm-12 col-md-6 col-lg-4 col-xl-4 mg-b-5" >
			<span class="input-group-addon bg-transparent">
			<label class="ckbox wd-16"><input type="checkbox" id="n_<?=$s->id?>" class="sostav_checkbox"><span></span></label>
			</span>
			<?=$s->text?>
			<span class="input-group-addon tx-size-sm lh-2"><?=$s->name?></span>
			</div>
			
			<?php } 
			
			}?>
			
          </div>
		 
	</div>
	<div class="form-group  col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button type="button" onclick="Add_Sostav();return false;" class="btn btn-info btn-lg m-2 pd-x-20">Создать</button>
	<div class="align-text-bottom">
			 <label class="form-control-label">Состав: <span class="tx-danger">*</span></label>
                  <textarea rows="8" cols="100" class="form-control" id="sostav" name="sostav" required></textarea>
				  </div>
            
	</div>
	</div>
	<script>
	$( ".s_s" ).click(function() {
var c = $(this).parent("div").find("input.sostav_checkbox")[0];
console.log(this);
$("#"+c.id).prop('checked', true);
});
function Add_Sostav(){
var arr_s = [];
var j = 0;
$('input:checked.sostav_checkbox').each(function(){
var input = $(this).parent().parent()[0].nextElementSibling;
if(input.tagName == 'SELECT'){
arr_s.push(input.name+' '+input.value);
}else{
arr_s.push(input.value+input.name);
}
j++;
});
console.log(arr_s.sort().reverse());
  sostav.innerHTML = arr_s.join('; \n')+'.';
}
</script>
</div>
            </section>
			<h3>Описание</h3>
            <section>
              <p>Внесити необходимые данные и нажмите кнопку "Создать".</p>

	<div class="row mg-b-25 border border-dark">
	<div class="form-group  col-sm-12 col-md-12 col-lg-12 col-xl-12">
			 <label class="form-control-label">Описание: <span class="tx-danger">*</span></label>
             <div class="text_opis row">

          </div>
		  <div class="input-group col-sm-12 col-md-12 col-lg-12 col-xl-12 mg-b-5">
		  <span class="input-group-addon tx-size-sm lh-2">Соответствие размеров:</span>
                  <select class="form-control" name="soot_rozmer" id="soot_rozmer" data-placeholder="Выберите соответствие">
                    <option label="Выберите соответствие"></option>
	<option value="Европейские размеры.">Европейские размеры.</option>
	<option value="Наши размеры.">Наши размеры.</option>
	<option value="Французские размеры.">Французские размеры.</option>
	<option value="Размеры США.">Размеры США.</option>
	<option value="Итальянские размеры.">Итальянские размеры.</option>
                  </select>
				  </div>
		
		
	</div>
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

            </section>
			<h3>Сохранение</h3>
            <section>
              <p>Проверте правильность введенных данных для сохранения!</p>
			   <div class="row mg-b-25 ">
			   <ul class="list_save">
			   </ul>
			   </div>
			   <label class="ckbox">
					<input type="checkbox" name="ontop" value="1" ><span>Отобразить на главной.</span>
				</label>
				<input type="checkbox" name="save" id="save" value="1" style="display:none;">
            </section>
          </div>
<!--<input type="submit" name="save" value="Сохранить" style="display:none;">	-->	  
  </form>


<?php } ?>
</div>
    <script>
      $(function(){

        'use strict';

		$('#wizard').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          autoFocus: true,
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanging: function (event, currentIndex, newIndex) {
            if(currentIndex < newIndex) {
              // Step 1 form validation
              if(currentIndex === 0) {
                var category = $('#category').parsley();
				var size_type = $('#size_type').parsley();
				var sezon = $('#sezon').parsley();
                              //  var model = $('#model_id').parsley();
                if(category.isValid()  && size_type.isValid() && sezon.isValid()) {
				return true;
               } else {
                 category.validate();
				 size_type.validate();
				 sezon.validate();
                                
               }
              }
              // Step 2 form validation
              if(currentIndex === 1) {
                var img = $('#image_file').parsley();
		
                if(img.isValid()) { 
				return true;
                } else {
				img.validate();
				
				}
              }
			   // Step 2 form validation
              if(currentIndex === 2) {
			 var sost = $('#sostav').parsley();
			 if(sost.isValid()) {
                 return true;
                } else {
				sost.validate();
				}

              }
			  if(currentIndex === 3){
               var long_t = $('#long_text').parsley();

                if(long_t.isValid()) {
				$(".list_save").html('');
				$(".list_save").append('<li>Категория: '+$("#category option:selected").text()+'</li>');
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
        });//end steps
      });//end ready
	  function previewFile(etv) {
	console.log(etv.name);
	console.log(etv.files);
  //var preview = $('#'+etv.id+'_view');

  var file    = etv.files[0];
  var reader  = new FileReader();

   reader.onloadend = function () {
	$('.'+etv.name).html(['<img class="thumb" " src="', reader.result, '" />'].join(''));
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "";
  }
 
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