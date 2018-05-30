<?php $user = $this->ws->getCustomer()?>
<h1><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<br/>
<br/>
<?php if ($this->errors) { ?><p><strong><?php echo $this->trans->get('Поля отмеченные <span class="red">красным</span> являються обязательными для заполнения.');?></strong></p><?php
    foreach ($this->errors as $error) {
        echo '<span style="color: red;">' . $error . '</span><br />';
    }
} ?>
<?php if ($this->ok) { ?><p><strong><?php echo $this->trans->get('Спасибо за участвие в конкурсе.');?></strong></p><?php } ?>
<form method="post" action="" class="contact-form" name="slogan" id="slogan">
<table class="basket-cont">
    <tr>
        <td>
            <label for="name" class="label-contact">Имя <span>*</span></label>
        </td>
        <td>
            <input name="name" type="text"
                   class="formfields<?php if (in_array('name', $this->errors, true)) echo " red"; ?>" id="name"
                   value="<?php if (isset($this->post->name)) echo $this->post->name; elseif(isset($user->id)) echo $user->getFirstName();?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <label for="email" class="label-contact">E-mail <span>*</span></label>
        </td>
        <td>
            <input name="email" type="text"
                   class="formfields<?php if (in_array('email', $this->errors, true)) echo " red"; ?>" id="email"
                   value="<?php if (isset($this->post->email)) echo $this->post->email; elseif(isset($user->id)) echo $user->getEmail();?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <label for="phone" class="label-contact">Телефон <span>*</span></label>
        </td>
        <td>
            <input name="phone" type="text"
                   class="formfields<?php if (in_array('phone', $this->errors, true)) echo " red"; ?>" id="phone"
                   value="<?php if (isset($this->post->phone)) echo $this->post->phone; elseif(isset($user->id)) echo $user->getPhone1();?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <label for="text" class="label-contact">Слоган <span>*</span></label>
        </td>
        <td><textarea name="text" class="formfields<?php if (in_array('text', $this->errors, true)) echo " red"; ?>" id="text"><?=$this->post->text?></textarea>
        </td>
    </tr>
    <tr>
        <td>
            <br />
            <a class="next-step" href="#" onclick="document.forms.slogan.submit(); return false;">Отправить</a>
        </td>
        <td></td>
    </tr>
 </table>
    </form>