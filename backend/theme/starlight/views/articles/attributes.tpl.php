<div class="sl-pagebody">
<div class="sl-page-title">
          <h5><?=$this->getCurMenu()->getTitle()?></h5>
          <p><?=$this->getCurMenu()->getPageBody()?></p>
</div>
<div class="card pd-20 pd-sm-40">
			  <h6 class="card-body-title">Просмотр атрибутов</h6>
<p class="mg-b-20 mg-sm-b-30">Здесь можно только посмотреть атрибуты!</p>
<div class="row">
              <div class="col-lg-4">
			  <h6 class="card-body-title">Категория товара</h6>
<p class="mg-b-20 mg-sm-b-30">Для просмотра атрибутов, выберите категорию!</p>
			  <?php
		$mas = array();
		foreach ($this->categories as $cat) {$mas[$cat->getId()] = $cat->getRoutez();}
			asort($mas);
			?>
			  
                <select name="cat_list" class="form-control select2-show-search select-search" data-placeholder="Выберите категорию">
                  <option label="Choose one"></option>
				  <?php
			foreach ($mas as $kay => $value) {
if(strripos($value, 'SALE') === FALSE){
			?>
			<option value="<?=$kay?>"<?php if ($this->cur_category and $kay == $this->cur_category->getId()) echo "selected";?>><?=$value?></option>
			<?php } } ?>
                </select>
              </div><!-- col-4 -->
			  <div class="col-lg-4">
			  <h6 class="card-body-title">Атребуты по категориям</h6>
					<p class="mg-b-20 mg-sm-b-30">Выберите атребут!</p>
				<select name="atrib_list" class="select-search atrib_ad d-none" data-placeholder="Выберите атрибут">
				
				</select>
			  </div><!-- col-4 -->
			  <div class="col-lg-4">
			  <h6 class="card-body-title">Параметры</h6>
					<p class="mg-b-20 mg-sm-b-30">Список доступных параметров</p>
				<ul  class="atrib_param-list">
				
				</ul>
			  </div><!-- col-4 -->
			   
			  

		
</div>
</div>

<div class="card pd-20 pd-sm-40 mg-t-25">
	<h6 class="card-body-title">Добавление и редактирование артибутов</h6>
<p class="mg-b-20 mg-sm-b-30">Здесь можно управлять атрибутами!</p>
<div class="row">
              <div class="col-lg-4">
			  <h6 class="card-body-title">Категория товара</h6>
<p class="mg-b-20 mg-sm-b-30">Для просмотра атрибутов, выберите категорию!</p>
			  <?php
		$mas = array();
		foreach ($this->categories as $cat) {$mas[$cat->getId()] = $cat->getRoutez();}
			asort($mas);
			?>
			  
                <select name="cat_edit" class="form-control select2-show-search select-search cat_edit" data-placeholder="Выберите категорию">
                  <option label="Choose one"></option>
				  <?php
			foreach ($mas as $kay => $value) {
if(strripos($value, 'SALE') === FALSE){
			?>
			<option value="<?=$kay?>"<?php if ($this->cur_category and $kay == $this->cur_category->getId()) echo "selected";?>><?=$value?></option>
			<?php } } ?>
                </select>
              </div><!-- col-4 -->
			  <div class="col-lg-4">
			  <h6 class="card-body-title">Атребуты по категориям</h6>
				<p class="mg-b-20 mg-sm-b-30">Выберите атребут!</p>
				<div class="add_atrib input-group в-тщту">
				<input type="text" class="form-control" placeholder="Атрибут" name="atrib_name"><span class="input-group-btn"><button class="btn bd bg-white tx-gray-600" type="button"><i class="fa fa-search"></i></button></span>
				</div>
				<select name="atrib_edit" class="select-search atrib_edit d-none" data-placeholder="Выберите атрибут">
				
				</select>
			  </div><!-- col-4 -->
</div>
</div>
</div>
<script>
	  $(function(){
	  $('.select-search').change(function(e) {
				if(this.name == 'cat_list'){
				$.ajax({
            url: '/admin/attributes/atrib/'+this.value,
            dataType: 'json',
            type: 'POST',// or GET
           // data: vls,
            success:function(data){
			//console.log(data);
			if(data.atribures.length > 0){
			var list = '<option label="Выберите атрибут"></option>';
			for (var z in data.atribures) {
			list += '<option value="'+data.atribures[z].id+'">'+data.atribures[z].name+'</option>';
			//console.log(data.atribures[z].id);
			//if(arr[z].id == o.val()) c.html(arr[z].count);
			}
			//list+='</select>';
			$('.atrib_ad').html(list);
			// Select2 by showing the search
        $('.atrib_ad').select2({
          minimumResultsForSearch: ''
        });
			}
			
			
			//alert(data);
                //$('#table_size tbody').html(data.txt);
            }
        });
		}else if(this.name == 'atrib_list'){
		$.ajax({
            url: '/admin/attributes/param/'+this.value,
            dataType: 'json',
            type: 'POST',// or GET
           // data: vls,
            success:function(data){
			console.log(data);
			if(data.parametr.length > 0){
			var list = '';
			for (var z in data.parametr) {
			list += '<li>'+data.parametr[z].text+'</li>';
			//console.log(data.atribures[z].id);
			//if(arr[z].id == o.val()) c.html(arr[z].count);
			}
			//list+='</select>';
			$('.atrib_param-list').html(list);

			}
            }
        });
		}else if(this.name == 'cat_edit'){
		
		$.ajax({
            url: '/admin/attributes/atrib/'+this.value,
            dataType: 'json',
            type: 'POST',// or GET
           // data: vls,
            success:function(data){
			//console.log(data);
			if(data.atribures.length > 0){
			$('.add_atrib').show();
			var list = '<option label="Выберите атрибут"></option>';
			for (var z in data.atribures) {
			list += '<option value="'+data.atribures[z].id+'">'+data.atribures[z].name+'</option>';
			//console.log(data.atribures[z].id);
			//if(arr[z].id == o.val()) c.html(arr[z].count);
			}
			//list+='</select>';
			$('.atrib_edit').html(list);
			// Select2 by showing the search
			
        $('.atrib_edit').select2({ minimumResultsForSearch: '' });
			}
			
			
			//alert(data);
                //$('#table_size tbody').html(data.txt);
            }
        });
		
		}
			});
	  });
	  </script>