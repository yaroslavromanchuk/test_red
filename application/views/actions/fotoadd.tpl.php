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
<?php if ($this->ok) { ?><p><strong><?php echo $this->trans->get('Спасибо за участие в конкурсе.');?></strong></p><?php } ?>

<form method="post" action="" class="contact-form" name="fotoadd" id="fotoadd" enctype="multipart/form-data">
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
            <label for="image" class="label-contact">Фото <span>*</span></label>
        </td>
        <td><input name="image" type="file" class="formfields<?php if (in_array('image', $this->errors, true)) echo " red"; ?>" id="image" />
        </td>
    </tr>
     <!--<tr>
        <td>
            <label for="item" class="label-contact">Наименование вещи <span>*</span></label>
        </td>
        <td>
            <input name="item" type="text"
                   class="formfields<?php /*if (in_array('item', $this->errors, true)) echo " red"; */?>" id="item"
                   value="<?php /*if (isset($this->post->item)) echo $this->post->item; elseif(isset($user->id)) echo $user->getItem();*/?>"/>
        </td>
    </tr>
     <tr>
        <td>
            <label for="brend" class="label-contact">Бренд <span>*</span></label>
        </td>
        <td>
            <input name="brend" type="text"
                   class="formfields<?php /*if (in_array('brend', $this->errors, true)) echo " red"; */?>" id="brend"
                   value="<?php /*if (isset($this->post->brend)) echo $this->post->brend; elseif(isset($user->id)) echo $user->getBrend();*/?>"/>
        </td>
    </tr>
     <tr>
        <td>
            <label for="price" class="label-contact">Цена </label>
        </td>
        <td>
            <input name="price" type="text"
                   class="formfields" id="price"
                   value="<?php /*if (isset($this->post->price)) echo $this->post->price; elseif(isset($user->id)) echo $user->getPrice();*/?>"/> .грн
        </td>
    </tr>-->
    <tr>
       <td>
           <label for="age" class="label-contact">Возраст <span>*</span></label>
       </td>
       <td>
           <input name="age" type="text"
                  class="formfields<?php if (in_array('age', $this->errors, true)) echo " red"; ?>" id="age"
                  value="<?php if (isset($this->post->age)) echo $this->post->age; elseif(isset($user->id)) echo $user->getAge();?>"/>
       </td>
   </tr>
    <tr>
       <td>
           <label for="next_name" class="label-contact">Имя ребенка <span>*</span></label>
       </td>
       <td>
           <input name="next_name" type="text"
                  class="formfields<?php if (in_array('next_name', $this->errors, true)) echo " red"; ?>" id="next_name"
                  value="<?php if (isset($this->post->next_name)) echo $this->post->next_name; elseif(isset($user->id)) echo $user->getNextName();?>"/>
       </td>
   </tr>
    <tr>
           <td>
               <label  class="label-contact">Номинация </label>
           </td>
           <td>
               <input type="radio" value="1" name="type"> «Чудесный мальчик» <br/>
               <input type="radio" value="2" name="type"> «Чудесная девочка» <br/>
               <input type="radio" value="3" checked="checked" name="type"> «Самое оригинальное чудо» <br/>
           </td>
       </tr>

    <tr>
       <td>
           <label for="hoby" class="label-contact">Хобби </label>
       </td>
       <td>
           <input name="hoby" type="text"
                  class="formfields" id="hoby"
                  value="<?php if (isset($this->post->hoby)) echo $this->post->hoby; elseif(isset($user->id)) echo $user->getHoby();?>"/>
       </td>
   </tr>
    <tr>
       <td>
           <label for="text" class="label-contact">Подпись к фото </label>
       </td>
       <td>
           <input name="text" type="text"
                  class="formfields" id="text"
                  value="<?php if (isset($this->post->text)) echo $this->post->text; elseif(isset($user->id)) echo $user->getText();?>"/>
       </td>
   </tr>
    <tr>
        <td>
            <br />
            <a class="next-step" href="#" onclick="document.forms.fotoadd.submit(); return false;">Отправить</a>
        </td>
        <td></td>
    </tr>
 </table>
    </form>