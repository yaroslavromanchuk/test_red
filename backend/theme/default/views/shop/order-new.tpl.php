<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img"
     height="32"/>
<h1>Новый заказ</h1>
<form action="" method="post">
<p><strong>Информация о покупателе</strong></p>

  <?php if ($this->errors) { ?>
    <div id="errormessage"><img src="<?php echo SITE_URL;?>/img/icons/error.png" alt="" width="32" height="32" class="page-img" />
        <h1>Найдены ошибки:</h1>
        <ul>
            <?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
        </ul>
    </div>
    <?php } ?>

<table cellpadding="4" cellspacing="0" id="order-client">

      <tr>
        <td class="column-data">Способ доставки *</td>
        <td> <select name="delivery_type_id" id="delivery_type">
                    <option value=""></option>
                    <?php foreach(wsActiveRecord::useStatic('DeliveryType')->findAll() as $method) {
                                   echo '<option value="' . $method->getId() . '">' . $method->getName(). '</option>';
                        }
                    ?>
                </select></td>
    </tr>
    <tr>
        <td class="column-data">Способ оплаты *</td>
        <td><select name="payment_method_id"  id="payment_method" >
                    <option value=""></option>
                        </select>
                <script type="text/javascript">
                   $(document).ready(function () {
                        $('#delivery_type').change(function () {

                            var delivery = $(this).val();
                            var payment = $('#payment_method').val();
                             if(delivery==4){
                                $('.ukr>span').html('*');
                            }
                            else{
                                $('.ukr>span').html('');
                            }
                            if (delivery == '0') {
                                window.location.reload(true);
                                return(false);
                            }
                            $('#payment_method').attr('disabled', true);
                            $('#payment_method').html('<option>загрузка...</option>');
                            var url = '/page/getpayment/';
                            $.get(
                            url,
                            "delivery=" + delivery,
                            function (result) {
                                if (result.type == 'error') {
                                    alert('error');
                                    return(false);
                                }
                                else {
                                    var options = '';
                                    var option = '';
                                    $(result.payment).each(function() {
                                        option ='';
                                        if (payment == $(this).attr('id')) option = 'selected="selected"';
                                        options += '<option value="' + $(this).attr('id') + '" '+option+'>' + $(this).attr('title') + '</option>';
                                    });
                                    $('#payment_method').html(options);
                                    $('#payment_method').attr('disabled', false);
                                }
                            },
                            "json"
                            );
                        });
                    });

                </script></td>
    </tr>
    <tr>
        <td class="column-data">Клиент</td>
        <td><select name="id" class="formfields" onchange="if ($(this).val() != '') document.location='/admin/shop-orders/edit/id/?user='+this.value; return false;">
            <option value="0">Новый клиент</option>
            <?php foreach(wsActiveRecord::useStatic('Customer')->findAll() as $user){?>
                <option value="<?=$user->getId()?>" <?php if($user->getId() == @$_GET['user']) echo 'selected="selected"';?>><?php if ($user->getUsername()!=null) echo $user->getUsername(); else echo $user->getPhone1();?> (<?=$user->getFirstName()?>)</option>
            <?php } ?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="column-data">Компания</td>
        <td><input type="text" value="<?=$this->customer->getCompanyName()?>" name="company" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">Имя *</td>
        <td><input type="text" value="<?=$this->customer->getFirstName()?>" name="name" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">Адрес *</td>
        <td><input type="text" value="<?=$this->customer->getAdress()?>" name="address" class="formfields"></td>
    </tr>
     <tr>
        <td class="column-data ukr">Индекс <span></span></td>
        <td><input type="text" value="<?=$this->customer->getIndex()?>" name="index" class="formfields"></td>
    </tr>
     <tr>
        <td class="column-data ukr">Улица <span></span></td>
        <td><input type="text" value="<?=$this->customer->getStreet()?>" name="street" class="formfields"></td>
    </tr>
     <tr>
        <td class="column-data ukr">Дом <span></span></td>
        <td><input type="text" value="<?=$this->customer->getHouse()?>" name="house" class="formfields"></td>
    </tr>
     <tr>
        <td class="column-data ukr">Квартира <span></span></td>
        <td><input type="text" value="<?=$this->customer->getFlat()?>" name="flat" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">Почтовый код</td>
        <td><input type="text" value="" name="pc" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">Город</td>
        <td><input type="text" value="<?=$this->customer->getCity()?>" name="city" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">Телефон</td>
        <td><input type="text" value="<?=$this->customer->getPhone1()?>" name="phone" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">E-mail</td>
        <td><input type="text" value="<?=$this->customer->getEmail()?>" name="email" class="formfields"></td>
    </tr>
    <tr>
        <td class="column-data">Комментарий</td>
        <td><textarea cols="45" name="coments"></textarea></td>
    </tr>



</table>
    <input type="submit" value="Сохранить" />
    </form>

