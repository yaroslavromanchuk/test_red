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
	    <h1 class="violet">Ошибка ...</h1>
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
<div class="row m-auto">
    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 m-auto">
        <form method="post" action="?" class="contact-form w-100 was-validated">
<input type="text" value="" name="login" class="login_box"/>
<table class="table">
	<?php

		foreach(Field::poblagodarit_pojaluvatsa() as $field)
		{
	?>
        <tr>
        <td>
		<label for="<?php echo $field->getCode();?>" class="label-contact">
			<?php
				echo $field->getName();
				echo $field->getRequired() ? ' *' : '';
			?>
		</label>
        </td>
        <td>
		<?php
			if($field->getCode() == 'comments')
			{
		?>
        <textarea name="<?php echo $field->getCode();?>" id="<?php echo $field->getCode();?>" cols="21" rows="10" class="formfields"><?php echo @$this->post[$field->getCode()];?></textarea>		
		<?php
			}
			else
			{
		?>
    <input type="text" name="<?php echo $field->getCode();?>" id="<?php echo $field->getCode();?>" value="<?php echo $this->post[$field->getCode()];?>" class="formfields"/>
		<?php
			}
		?>
        </td>
        </tr>
	<?php
		}
	?>
    <tr>
    <td>
    <div class="border-l"></div>
    <button type="submit" class="search-button"><?=$this->trans->get('Отправить')?></button>
    <div class="border-r"></div>
    </td>
    <td></td>
    </tr>
    </table>
</form>
    </div>
</div>
