<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>"  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?php
//echo print_r($this->categories);
foreach ($this->categories as $cat) {
                    $mas[$cat->getRoutez()]['id'] = $cat->getId();
                    //$mas[$cat->getRoutez()]['count'] = (int)$cat->getArticles()->count();
                }

                ksort($mas);
$mascat = array();
foreach ($this->categories as $cat) {
                    $mascat[$cat->getRoutez()] = $cat;
                }

                ksort($mascat);
      
?>



	<form method="post" action="#">
    <table  cellpadding="4" cellspacing="0" class="table table-hover">
		
        <thead>
            <tr>
			<th colspan="4">Действия</th>
			<th>Название</th>
			<th>Путь</th>
		</tr>
        </thead>
                
	<?php

		$row = 'row1';
		$cur = -1;
		$count = $this->getCategories()->count();
$i=0;
		foreach($mascat as $category){
                    $i++;
			//if ($this->category_edit && $this->category_edit->getId() != $category->getId()) continue;
			$row = ($row == 'row2') ? 'row1' : 'row2';
			$cur++;
			$is_first = (0 == $cur);
			$is_last = ($count == $cur + 1);
	?>
		<tr class="<?=$row?>">
			<td class="kolomicon"><a href="<?=$this->path?>shop-categories/edit/id/<?=$category->getId()?>/"><img src="<?=SITE_URL?>/img/icons/edit-small.png" alt="Редактировать" width="24" height="24" /></a></td>
		<?php
        	if (/*$category->getArticles()->count() or */$category->kids->count()>0)
			{
		?>
			<td class="kolomicon"><img src="<?php echo SITE_URL;?>/img/icons/cantremove-small.png" alt="Verwijderen" width="24" height="24" /></td>
		<?php
			}
			else
			{
		?>
			<td class="kolomicon"><a href="<?php echo $this->path;?>shop-categories/delete/id/<?php echo $category->getId();?>/" onclick="return confirm('Удалить категорию?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24" /></a></td>
		<?php
			}
			if ($count > 1) {
				if (!$is_first) {
					?><td class="kolomicon"><a href="<?php echo $this->path;?>shop-categories/moveup/id/<?php echo $category->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/up.png" alt="Вверх" width="24" height="24" /></a></td><?php
				} else {
					?><td class="kolomicon">&nbsp;</td><?php
				}
				if (!$is_last) {
					?><td class="kolomicon"><a href="<?php echo $this->path;?>shop-categories/movedown/id/<?php echo $category->getId();?>/"><img src="<?php echo SITE_URL;?>/img/icons/down.png" alt="Вниз" width="24" height="24" /></a></td><?php
				} else {
					?><td class="kolomicon">&nbsp;</td><?php
				}
			} else {
				?><td class="kolomicon">&nbsp;</td>
				<td class="kolomicon">&nbsp;</td><?php
			}
		?>
			<td class="kolomtitle">
			<?php if ($this->category_edit && $this->category_edit->getId() == $category->getId()) {
			?>
			<label>Рус: <input name="category_edit_name" type="text" id="category_edit_name" value="<?php echo htmlspecialchars($this->category_edit->getName());?>" /></label>
			 <label>Укр: <input name="category_edit_name_uk" type="text" id="category_edit_name_uk" value="<?php echo htmlspecialchars($this->category_edit->getNameUk());?>" /></label> 
                <br />
                <input name="active" type="checkbox" id="active" <?php if (strcasecmp($this->category_edit->active,'1') == 0) echo 'checked="checked"';?> />
                <label for="active">Активна</label>
            <p>Описание</p>
            <label>Рус: <textarea name="category_edit_description" class="formfields" id="category_edit_description" ><?php echo $this->category_edit->getDescription();?></textarea></label>
			 <label>Укр: <textarea name="category_edit_description_uk" class="formfields" id="category_edit_description_uk" ><?php echo $this->category_edit->getDescriptionUk();?></textarea></label> 
			<?php } else {
					echo $category->getName();
				}
				//echo '('.$category->getArticles()->count().')';?>
			</td>

			<td class="kolomtitle">
			<?php if ($this->category_edit && $this->category_edit->getId() == $category->getId()) {
			?><label><select name="category_edit_id" class="formfields" id="category_edit_id">
            <option value="" selected>Выберите категорию...</option>
            <?php

                  foreach ($mas as $kay => $value) { ?>
            <option value="<?=$value['id']?>" <?php if ($this->category_edit and $value['id'] == $this->category_edit->getId()){ echo "selected";}?>><?=$kay?></option>
                      <?php } ?>
          </select>	</label>
                    <br />
                Категория уценки
                    <br />
                <select name="ucenka_edit_id" class="formfields" id="ucenka_edit_id">
            <option value="" selected>Выберите категорию...</option>
            <?php

                  foreach ($mas as $kay => $value) {
                    ?><option
                        value="<?php echo $value['id'];?>" <?php if ($this->category_edit and $value['id'] == @$this->category_edit->getUsencaCategory()) echo "selected";?>><?php echo $kay;?></option><?php

                }

			?>
          </select>

			<?php } else {
 				echo $category->getRoutez(); } ?>
			</td>
		</tr>
	<?php
		}
	?>

		<tr class="<?php $row = ($row == 'row2') ? 'row1' : 'row2';echo $row;?>">
			<td colspan="4">Новая категория</td>
			<td><label>
          Рус: <input type="text" name="category_name" id="category_name" /><br/>
          <!-- Укр: <input type="text" name="category_name_uk" id="category_name_uk" /></label></td> -->
			<td><label>
			<select name="category_id" class="formfields" id="category_id">
            <option value="" selected>Выберите категорию...</option>
                <?php   foreach ($mas as $kay => $value) {
                    ?><option
                        value="<?php echo $value['id'];?>" ><?php echo $kay;?></option><?php

                }
      
			?>
          </select>			
          </label></td>          
		</tr>
    </table>
	
	<p>
        <label>
          <input type="submit" name="button" id="button" value="Сохранить" />
          </label>
      </p>
    </form>


	
<script  src="<?=$this->files?>scripts/tinymce/tinymce.min.js"></script>
<script>
	tinymce.init({
		selector: "textarea",
		//width: 750,
		height: 100,
		language : 'ru',
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
	   ],
	   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
	   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | fontsizeselect | preview | code ",
	   image_advtab: true ,
	   
	   external_filemanager_path:"<?=$this->files?>/scripts/filemanager/",
	   filemanager_title:"Responsive Filemanager" ,
	   external_plugins: { "filemanager" : "<?=$this->files?>/scripts/filemanager/plugin.min.js"},
	   convert_urls: false
	 });
</script>