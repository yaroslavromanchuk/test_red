<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<img src="<?=$this->getCurMenu()->getImage()?>" alt=""  class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle();?> </h1>
<?=$this->getCurMenu()->getPageBody();?>
<?php $today=date("Y-m-d H:i:s"); ?>
<?php
//header('Access-Control-Allow-Origin: *');
if($this->errors){
?>
	<div id="errormessage"><img src="<?=SITE_URL?>/img/icons/error.png" alt="" class="page-img" />
	    <h1>Найдены ошибки:</h1>
	    <ul>
    	<?php
    		foreach($this->errors as $error)
    		{
    	?>
		    <li><?php echo $error;?></li>
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
        <td><input name="post_name" type="text" class="form-control input" id="paginaid" value="" /></td>
      </tr>
	  <tr>
        <td class="kolom2">Публиковать</td>
        <td><label>
          <input type="checkbox" name="public" value="1" id="checkbox" />
        </label></td>
      </tr>
	  <tr >
        <td class="kolom2">Дата публикации</td>
        <td>
		<input  name="ctime" type="date" class="form-control input" id="ctime" value=""/> 
		</td>
      </tr>
      <tr>
        <td class="kolom2">Автор</td>
        <td><label>
          <input type="text" name="autor" class="form-control input"  id="autor" value=""/>
        </label></td>
      </tr>
	  <tr>
        <td class="kolom2">Путь к картинке для главной страницы</td>
        <td><label>
		https://www.red.ua/storage
          <input type="text" name="image" class="form-control" style="width: 350px;display:inline-block;"  id="image" value=""/>
        </label></td>
      </tr>
	  <tr>
	  <td class="kolom2">Описание</td>
	  <td>
	   <input type="text" name="description"  id="description" class="form-control" style="width: 800px;display:inline-block;" value=""/>
	  </td>
	  </tr>
	   <tr>
	  <td class="kolom2">Теги</td>
	  <td>
	   <input type="text" name="keyword"  id="keyword" class="form-control " style="width: 800px;display:inline-block;" value=""/>
	  </td>
	  </tr>
	  	  <tr>
        <td class="kolom2">Категории</td>
        <td><label>
          <input type="checkbox" name="c1" value="1" id="с1" />
        ТРЕНДЫ
		</label>
		<label>
          <input type="checkbox" name="c2" value="2" id="с2"/>
        LOOKBOOK
		</label>
		<label>
          <input type="checkbox" name="c3" value="2" id="с3" />
        ВИДЕО
		</label>
		<label>SHOPPING
          <input type="checkbox" name="c4" value="4" id="с4"/>
        SHOPPING
		</label>
		<label>
          <input type="checkbox" name="c5" value="5" id="с5" />
        ЛЮДИ
		</label>
		<label>WELLNESS
          <input type="checkbox" name="c6" value="6" id="с6"/>
        WELLNESS
		</label>
		<label>
          <input type="checkbox" name="c7" value="7" id="с7" />
        INSPIRATION
		</label>
		</td>
      </tr>
       <tr>
        <td class="kolom2">Вступительная часть<br>Картинка 50%</td>
        <td><textarea name="preview_post" cols="1000" rows="25" class="message-h" id="preview_post"></textarea></td>
      </tr> 
      <tr>
        <td class="kolom2">Содержимое<br>
		Картинки до 99%<br>
		Товар мах 320рх</td>
        <td><textarea name="content_post" cols="2000" rows="50" class="message-h" id="content_post"></textarea></td>
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
		external_filemanager_path:"<?=$this->files?>scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
	   external_plugins: { "filemanager" : "<?=$this->files?>scripts/filemanager/plugin.min.js"},
	   convert_urls: false
 });
 
  });
</script> 