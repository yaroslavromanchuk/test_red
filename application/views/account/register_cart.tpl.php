<h1><?php echo mb_strtoupper($this->getCurMenu()->getName());?></h1>
<br/>
<br/>
<?php if ($this->errors) { ?><p><strong><?php echo $this->trans->get('Поля отмеченные <span class="red">красным</span> являються обязательными для заполнения.');?></strong></p><?php
}

    if (isset($this->errors['error'])) {
        echo '<span style="color: red;">' . $this->errors['error'] . '</span><br />';
    }
?>
<?php if (isset($this->error_email)) echo '<span style="color: red;">' . $this->error_email . '</span><br /><br />'; ?>
<form method="post" action="" class="contact-form" name="register" id="register">
    <table class="basket-cont">
        <tr>
            <td>
                <label for="telephone" class="label-contact"><?php echo $this->trans->get('№ карты');?>
                    <span>*</span></label>
            </td>
            <td>
                <input name="cart" type="text"
                       class="formfields<?php if (in_array('cart', $this->errors, true)) echo " red"; ?>"
                       id="cart"
                       value="<?=$this->post->cart?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="email" class="label-contact">E-mail <span>*</span></label>
            </td>
            <td>
                <input name="email" type="text"
                       class="formfields<?php if (in_array('email', $this->errors, true)) echo " red"; ?>" id="email"
                       value="<?=$this->post->email?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="name" class="label-contact"><?php echo $this->trans->get('Имя');?> <span>*</span></label>
            </td>
            <td>
                <input name="name" type="text"
                       class="formfields<?php if (in_array('name', $this->errors, true)) echo " red"; ?>" id="name"
                       value="<?=$this->post->name?>"/>

            </td>
        </tr>
        <tr>
            <td>
                <label for="telephone" class="label-contact"><?php echo $this->trans->get('Телефон');?>
                    <span>*</span></label>
            </td>
            <td>
                <input name="telephone" type="text"
                       class="formfields<?php if (in_array('telephone', $this->errors, true)) echo " red"; ?>"
                       id="telephone"
                       value="<?=$this->post->telephone?>"/>
            </td>
        </tr>
        <tr>
        <tr>
            <td>
                <label for="password" class="label-contact">Пароль
                    <span>*</span></label>
            </td>
            <td>
                <input name="password" type="password"
                       class="formfields<?php if (in_array('password', $this->errors, true)) echo " red"; ?>"
                       id="password"
                       value=""/>
            </td>
        </tr>
        <tr>
        <tr>
            <td>
                <label for="password2" class="label-contact">Повторите пароль
                    <span>*</span></label>
            </td>
            <td>
                <input name="password2" type="password"
                       class="formfields<?php if (in_array('password', $this->errors, true)) echo " red"; ?>"
                       id="password2"
                       value=""/>
            </td>
        </tr>
        <tr>
            <td>
                <br/>
                <a class="next-step" href="#" onclick="document.forms.register.submit(); return false;">Регистрация</a>
            </td>
            <td></td>
        </tr>
    </table>
</form>