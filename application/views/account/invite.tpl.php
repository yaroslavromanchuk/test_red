<h1><?php echo mb_strtoupper($this->getCurMenu()->getName());?></h1>
<?php echo $this->getCurMenu()->getPageBody();?>
<br />
<br />
<?php if($this->ws->getCustomer()->getIsLoggedIn()) { ?>
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
    <form method="post" action="?" name="invite" id="invite">
        <table align="center" style="font-size:14px;">
            <tr>
                <td><b>Имя друга: </b></td>
                <td><input type="text" name="name" class="input_reg"></input></td>
            </tr>

            <tr>
                <td><b>E-mail друга: </b></td>
                <td><input type="text" name="email" class="input_reg" ></input></td>
            </tr>
        </table>
        <br />
        <a onclick="document.forms.invite.submit(); return false;" href="#" class="btn btn-default">Пригласить</a>
    </form>
</div>
<?php } ?>