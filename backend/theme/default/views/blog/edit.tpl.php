<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<img src="<?=$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle()?> </h1>
<?=$this->getCurMenu()->getPageBody()?>
<?php $today=date("Y-m-d H:i:s"); ?>
<?php
if($this->errors){
?>
	<div id="errormessage"><img src="/img/icons/error.png" alt="" class="page-img" />
	    <h1>Найдены ошибки:</h1>
	    <ul>
    	<?php
    		foreach($this->errors as $error)
    		{
    	?>
		    <li><?=$error?></li>
		<?php
			}
		?>
	    </ul>
	</div>  
<?php
	}

	if($this->saved)
	{
?>	
	<div id="pagesaved">
		<img src="/img/icons/accept.png" alt=""  class="page-img" />
		<h1>Данные успешно сохранены.</h1>
	</div>
<?php
	}
	
?>

	<form method="post" action=""  enctype="multipart/form-data">
    <table id="editpage" cellpadding="5" cellspacing="0" class="table">
      <tr>
        <td class="kolom2">Заголовок</td>
        <td><input name="post_name" type="text" class="form-control" id="paginaid" value="<?=$this->blogedit->getPostName()?>" /></td>
      </tr>
	  <tr>
        <td class="kolom2">Публиковать</td>
        <td><label>
          <input type="checkbox" name="public" value="1" id="checkbox" <?=($this->blogedit->getPublic()==1)?'checked':''?>/>
        </label></td>
      </tr>
	  <tr >
        <td class="kolom2">Дата публикации</td>
        <td>
		<input  name="ctime" type="date" class="form-control" id="ctime" value="<?=$this->blogedit->getCtime()?date('Y-m-d', strtotime($this->blogedit->getCtime())):date('Y-m-d')?>"/> 
		</td>
      </tr>
      <tr>
        <td class="kolom2">Автор</td>
        <td><label>
          <input type="text" name="autor" class="form-control"  id="autor" value="<?=$this->blogedit->getAutor()?>"/>
        </label></td>
      </tr>
	  <tr>
        <td class="kolom2">Путь к картинке для главной страницы</td>
        <td><label>
		https://www.red.ua/storage
          <input type="text" name="image" class="form-control" style="width: 350px;display:inline-block;"  id="image" value="<?=$this->blogedit->getImage()?>"/>
        </label></td>
      </tr>
	  <tr>
	  <td class="kolom2">Описание</td>
	  <td>
	   <input type="text" name="description"  id="description" class="form-control" style="width: 800px;display:inline-block;" value="<?=$this->blogedit->getDescription()?>"/>
	  </td>
	  </tr>
	   <tr>
	  <td class="kolom2">Теги</td>
	  <td>
	   <input type="text" name="keyword"  id="keyword" class="form-control" style="width: 800px;display:inline-block;" value="<?=$this->blogedit->getKeyword()?>"/>
	  </td>
	  </tr>
	  	  <tr>
        <td class="kolom2">Категории</td>
        <td><label>
          <input type="checkbox" name="c1" value="1" id="с1" <?php echo (stristr($this->blogedit->getCategories(), '1') == true) ? 'checked' : '';?>/>
        ТРЕНДЫ
		</label>
		<label>
          <input type="checkbox" name="c2" value="2" id="с2" <?php echo (stristr($this->blogedit->getCategories(), '2') == true) ? 'checked' : '';?>/>
        LOOKBOOK
		</label>
		<label>
          <input type="checkbox" name="c3" value="2" id="с3" <?php echo (stristr($this->blogedit->getCategories(), '3') == true) ? 'checked' : '';?>/>
        ВИДЕО
		</label>
		<label>SHOPPING
          <input type="checkbox" name="c4" value="4" id="с4" <?php echo (stristr($this->blogedit->getCategories(), '4') == true) ? 'checked' : '';?>/>
        SHOPPING
		</label>
		<label>
          <input type="checkbox" name="c5" value="5" id="с5" <?php echo (stristr($this->blogedit->getCategories(), '5') == true) ? 'checked' : '';?>/>
        ЛЮДИ
		</label>
		<label>WELLNESS
          <input type="checkbox" name="c6" value="6" id="с6" <?php echo (stristr($this->blogedit->getCategories(), '6') == true) ? 'checked' : '';?>/>
        WELLNESS
		</label>
		<label>
          <input type="checkbox" name="c7" value="7" id="с7" <?php echo (stristr($this->blogedit->getCategories(), '7') == true) ? 'checked' : '';?>/>
        INSPIRATION
		</label>
		</td>
      </tr>
       <tr>
        <td class="kolom2">Вступительная часть<br>Картинка 50%</td>
        <td><textarea name="preview_post" cols="1000" rows="25" class="message-h" id="preview_post"><?=$this->blogedit->getPreviewPost()?></textarea></td>
      </tr> 
      <tr>
        <td class="kolom2">Содержимое<br>
		Картинки до 99%<br>
		Товар мах 320рх</td>
        <td><textarea name="content_post" cols="2000" rows="50" class="message-h" id="content_post"><?=$this->blogedit->getContentPost()?></textarea></td>
      </tr>

    </table>
	<p><input type="submit" class="btn btn-small btn-default" name="savepage" id="savepage" value="Сохранить" />
	</p>
    </form>
<script>
$(document).ready(function(){
 tinymce.init({ 
mode : "exact",
elements : "content_post, preview_post",
language : 'ru',
width: 800,
height: 500,
plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| responsivefilemanager | link unlink anchor | image  media | forecolor backcolor  | fontsizeselect | preview | code",
	    image_advtab: true,
            
            external_filemanager_path:"/backend/scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
	   external_plugins: { "filemanager" : "/backend/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
 });
 
  });
</script> 