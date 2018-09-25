<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<?php
	if($this->errors)
	{
?>
	<div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
	    <h1>Че-то произошло:</h1>
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

        <script type='text/javascript'>
function textToPassword( elem )
{
 var eData={v:elem.value, t:elem.type, s:elem.size, n:elem.name},
     newElem, newType=(eData.t=='password'?'text':'password');
 try
 {
  newElem=document.createElement("<input type='"+newType+"' name='"+eData.n+"' value='"+eData.v+"' size='"+eData.s+"' >");
 }
 catch(e)
 {
  newElem=document.createElement('input');
  newElem.name=eData.n;
  newElem.name=eData.n;
  newElem.type=newType;
  newElem.size=eData.s;
  newElem.value=eData.v;
 }
 elem.parentNode.replaceChild(newElem, elem);
 return newElem.type;
}
</script>
    <form method="post" action="<?php echo $this->path;?>password/">
    <p><input name="" type="checkbox" onclick='this.value=(textToPassword(this.form["oldpassword"]));textToPassword(this.form["newpassword"]);textToPassword(this.form["newpassword2"])'/> Показывать вводимый пароль</p>
    <table id="changepw" cellpadding="6" cellspacing="0">
      <tr>
        <td class="kolom1">Текущий пароль</td>
        <td><input name="oldpassword" class="formfields" type="password"/></td>
      </tr>
      <tr>
        <td class="kolom1">Новый пароль</td>
        <td><input name="newpassword" class="formfields" type="password" /></td>
      </tr>
      <tr>
        <td class="kolom1">Повтор нового пароля</td>
        <td><input name="newpassword2" class="formfields" type="password" /></td>
      </tr>
    </table>
    <p>
      <label>
      <input type="submit" class="buttonpw" name="resetpassword" id="resetpassword" value="Изменить" />
      </label>
    </p>
    </form>
    