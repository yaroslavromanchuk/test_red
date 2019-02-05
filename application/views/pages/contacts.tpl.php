<style>
/* any random name here - same for inout field */
.login_box{
	display:none;
}
</style>			
<?php
	if($this->errors)
	{
?>
	<div id="errormessage">
	    <h4 class="violet">Ошибка ...</h4>
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
?>
    <?php
$bool = false;
 if($this->errors){ $bool = true;}
 ?>
    <form method="post" action="" class="contact-form w-100 was-validated ">
<div class="row m-auto">
    <div class="col-xl-12 m-auto p-2  text-center">
<div class="comment-types">
<div class="comment-type">
<span class="red">*</span> - Поля, обязательные для заполнения
</div>
</div>
</div>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-3 m-auto">
    <?php
        

		foreach(Field::poblagodarit_pojaluvatsa() as $field)
		{
	?>
<div class="form-group form-group-sm">
<label for="<?=$field->getCode()?>"><?=$this->trans->get($field->name)?>
    <?php if($field->getRequired()){ echo '<span class="red">*</span>'; } ?>
 <?php if($bool and $this->errors[$field->getCode()]) echo '<br><span class="red" style="font-size: 10px;">'.$this->errors[$field->getCode()].'</span>'; ?></label>

        <?php
			if($field->getCode() == 'comments')
			{
		?>
        <textarea name="<?=$field->getCode()?>" id="<?=$field->getCode()?>" cols="45" rows="10" <?php if($field->getRequired()){ echo 'required=""';} ?> class="form-control"><?=$this->post[$field->getCode()]?></textarea>		
		<?php
			}
			else
			{
		?>
    <input class="form-control" name="<?=$field->getCode()?>" type="<?=$field->type?>" id="<?=$field->getCode()?>"  <?php if($field->getRequired()){ echo 'required=""';} ?> value="<?=$this->post[$field->getCode()]?>">
		<?php
			}
		?>
        
      
</div>
    
                <?php } ?>
    </div>
    <div class="col-xl-12 text-center">
			<button class="btn btn-danger" type="submit" id="send"><?=$this->trans->get('Отправить')?></button>
		</div>
    </div>

</form>