<img src="<?=$this->getCurMenu()->getImage()?>" alt="" width="32" class="page-img" height="32" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<?=$this->getCurMenu()->getPageBody()?>

<?php
	if($this->errors)
	{
?>
	<div id="errormessage"><img src="/img/icons/error.png" alt="" class="page-img" />
	    <h1>Ошибка:</h1>
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
		<img src="/img/icons/accept.png" alt="" class="page-img" />
		<h1>Запись сохранена.</h1>
	</div>
<?php
	}
?>

	<form method="POST" action="<?=$this->path?>subscribers/edit/id/<?=$this->sub->id?>/">
            <table  class="table">
                <?php if(false){ foreach ($this->sub as $key => $value) {
                    if($key == 'id'){ continue;}
                    ?>
                <tr>
		    <td class="kolom1"><?=$key?></td>
                    <td><input name="<?=stripslashes($key)?>" type="text" class="form-control"  value="<?=$this->sub[$key]?>"/></td>
		  </tr>    
    
<?php }} ?>
		  <tr>
		    <td class="kolom1">Имя</td>
		    <td><input name="name" type="text" class="form-control"  value="<?=$this->sub->name?>" /></td>
		  </tr>
                  <tr>
		    <td class="kolom1">Сегмент</td>
		    <td>
                        <select name="segment_id" class="form-control select2" data-placeholder="Выберите сегмент">
                            <option label="Выберите сегмент"></option>
                                <?php foreach (Subscriberstype::getListSubscriberType() as $s) { ?>
                            <option value="<?=$s->id?>" <?php if($s->id == $this->sub->segment_id){ echo 'selected'; } ?> ><?=$s->name?> </option>
                               <?php } ?>
                        </select>
                    </td>
		  </tr>
		  <tr>
		    <td class="kolom1">E-mail</td>
		    <td><input name="email" type="email" class="form-control"  value="<?=$this->sub->email?>"/></td>
		  </tr>
		  <tr>
			<td class="kolom1">&nbsp;</td>
			<td><input type="submit" class="buttonps" name="savepage" value="Сохранить" /></td>
		  </tr>
		</table>
	</form>
	