<h1><?php echo mb_strtoupper($this->getCurMenu()->getName());?></h1>
<br />
<br />
<div class="login" align="center">
<?php
if(isset($this->errors))
{
    foreach($this->errors as $error){
        echo '<span style="color: red;">'.$error.'</span><br />'; 
    }
}
if(isset($this->ok)) {
     echo '<span style="color: green;">'.$this->ok.'</span><br />';
}
?>
   <div class="col-xl-12 text-center">
    <form method="post" action="/account/epass/" name="epass" id="epass">
        <table cellspacing="0" cellpadding="4" class="basket-cont view" style="font-size:14px;" align="center">
            <tbody>
                <tr>
                    <td>Старый пароль</td>
                    <td><input type="password" name="oldpass" class="form-control" value=""></td>
                </tr> 
                <tr>
                    <td>Новый пароль</td>
                    <td><input type="password" name="password" class="form-control" value=""></td>
                </tr> 
                <tr>
                    <td>Новый пароль</td>
                    <td><input type="password" name="password2" class="form-control" value=""></td>
                </tr> 
            </tbody>
        </table>
        <br />
        <br />
        <a onclick="document.forms.epass.submit(); return false;" href="#" class="btn btn-danger">Сменить</a>
    </form>
	</div>
</div>
<script type="text/javascript">
    validateForm('#epass', buildURL('account', 'validatePassword'), false);
</script>