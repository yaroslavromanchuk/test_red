<?php
if ($this->errors) { ?>
<div id="errormessage"><img src="/img/icons/error.png" alt="" class="page-img"/>
    <h2>Ошибка:</h2>
    <ul>
        <?php
        foreach ($this->errors as $error) {
            ?>
            <li class="alert alert-danger" role="alert"><?=$error?></li>
            <?php

        }
        ?>
    </ul>
</div>
<?php
}
if ($this->saved) { ?>
<div id="pagesaved" class="alert alert-success" role="alert">
    <img src="/img/icons/accept.png" alt="" class="page-img"/>
    <h2>Запись сохранена.</h2>
</div>
<?php } ?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title"><?=$this->getCurMenu()->getTitle().' '.$this->sub->getFullname()?></h3></div>
<div class="panel-body">
<form method="POST" action="<?=$this->path?>user/edit/id/<?=$this->sub->getId()?>/" id="userform" class="form-horizontal" enctype="multipart/form-data">
   <input type="hidden" value="<?=$this->sub->getId()?>" name="id" id="id">
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-warning">
                <div class="panel-heading"><h3 class="panel-title">Личные данные</h3></div>
                <div class="panel-body">
                      <?php if($this->sub->getEmailOk() == 0){ ?>
    <div class="form-group" id="ok">
    <label class="ct-150 control-label">Email не изменялся!</label>
    <div class="col-xs-6">
	 <button class="btn btn-sm btn-success " name="go_mail" id="go_mail" >Отправить подтверждение</button>
    </div>
  </div> <?php  }elseif($this->sub->getEmailOk() == 2){ ?>
      <div class="form-group" id="ok">
    <label class="ct-150 control-label">Email (<?=$this->sub->getTempEmail()?>) не подтвержден!</label>
    <div class="col-xs-6">
        <button class="btn btn-sm btn-success " name="go_mail" id="go_mail" >Отправить подтверждение</button>

    </div>
  </div>
  <?php }?>    
    <div class="form-group">
    <label class="ct-150 control-label">ID/login:</label>
    <div class="col-xs-6">
	<span><?=$this->sub->id.'/'.$this->sub->getUsername()?></span>
         <?php if ($this->user->isDeveloperAdmin() and $this->sub->getCustomerTypeId() < 2) { ?>
        <a href="/admin/adminedit/edit/<?=(int)$this->sub->getId()?>?new_admin=1" class="btn btn-sm btn-danger"
                   onclick="return confirm('Внимание, при переходе по этой ссылке пользователь станет администратором.')">В админы</a>
         <?php } ?>
       <?php if($this->sub->getCart()) { ?>
        <i class="icon ion-ios-cart-outline tx-30 pd-5 view_cart" data-id="<?=$this->sub->id?>" data-tooltip="tooltip" title="Сотреть корзину" ></i>
       <?php } ?>
    </div>
    </div>
    <div class="form-group">
        <label class="ct-150 control-label">Имя:</label>
        <div class="col-xs-6">
	<input name="first_name" type="text" class="form-control"  value="<?=$this->sub->getFirstName()?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="ct-150 control-label">Фамилия:</label>
        <div class="col-xs-6">
	<input name="middle_name" class="form-control" value="<?=$this->sub->getMiddleName()?>"/>
        </div>
    </div>                
    <div class="form-group">
        <label class="ct-150 control-label">Дата рождения:</label>
        <div class="col-xs-6">
	<input name="date_birth" class="form-control" value="<?=$this->sub->getDateBirth()?>"/>гггг-мм-дд
        </div>
    </div>
    <div class="form-group">
        <label class="ct-150 control-label">Аватарка:</label>
        <div class="col-xs-6">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			<input name="logo" type="file" class="form-control" /> Размер 1Х1 не более 1мб.
			<?php if ($this->sub->getLogo()) {
                            echo '<img style="max-width:50px;" src="' . $this->sub->getLogo() . '" />'; 
                            
                        } ?>
        </div>
    </div>               
    <div class="form-group">
        <label class="ct-150 control-label">E-mail:</label>
        <div class="col-xs-6">
            <input name="email" id="email" class="form-control input" value="<?=$this->sub->getEmail();?>"/>
        </div>
    </div>                
    <div class="form-group">
        <label class="ct-150 control-label">Телефон:</label>
        <div class="col-xs-6">
            <input name="phone1" class="form-control" value="<?=$this->sub->getPhone1()?>"/>
          <!--  <a href="tel:<?=$this->sub->getPhone1()?>">Позвонить</a>-->
        </div>
    </div>                
                </div>
            </div>
        </div>
        
        
        
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">Адрес</h3></div>
                <div class="panel-body">
                <div class="form-group">
                    <label class="ct-150 control-label">Индекс:</label>
                    <div class="col-xs-6">
                        <input name="index" class="form-control input" value="<?php echo $this->sub->getIndex();?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="ct-150 control-label">Область:</label>
                    <div class="col-xs-6">
                        <input name="obl" class="form-control input" value="<?php echo $this->sub->getObl();?>"/>
                    </div>
                </div>    
                <div class="form-group">
                    <label class="ct-150 control-label">Район:</label>
                    <div class="col-xs-6">
                        <input name="rayon" class="form-control input" value="<?php echo $this->sub->getRayon();?>"/>
                    </div>
                </div>    
                 <div class="form-group">
                    <label class="ct-150 control-label">Город:</label>
                    <div class="col-xs-6">
                        <input name="city" class="form-control input" value="<?php echo $this->sub->getCity();?>"/>
                    </div>
                </div>   
                 <div class="form-group">
                    <label class="ct-150 control-label">Улица:</label>
                    <div class="col-xs-6">
                        <input name="street" class="form-control input" value="<?php echo $this->sub->getStreet();?>"/>
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Дом:</label>
                    <div class="col-xs-6">
                        <input name="house" class="form-control input" value="<?php echo $this->sub->getHouse();?>"/>
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Квартира:</label>
                    <div class="col-xs-6">
                        <input name="flat" class="form-control input" value="<?php echo $this->sub->getFlat();?>"/>
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Адрес:</label>
                    <div class="col-xs-6">
                        <input name="adress" class="form-control " value="<?php echo $this->sub->getAdress();?>"/>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
   <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title">Данные</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                    <label class="ct-150 control-label">Скидка по покупкам:</label>
                    <div class="col-xs-6">
                        <?php echo $this->sub->getDiscont(false, 0, true);?>%
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Скидка по акциям:</label>
                    <div class="col-xs-6">
                        <?php echo (int)EventCustomer::getEventsDiscont($this->sub->getId());?>%
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Участие в акциях:</label>
                    <div class="col-xs-6">
                       <a href="#" onclick="$('.view_event_history').slideToggle(); return false;">Показать\Скрыть</a>

                <div class="view_event_history" style="display: none;">
                    <?php $user_event = wsActiveRecord::useStatic('EventCustomer')->findAll(array('customer_id' => $this->sub->getId()), array('ctime' => 'DESC'));
                    foreach ($user_event as $uevent) {
                        $event = new Event($uevent->getEventId());
                        ?>
                        <p>Акция <a href="/admin/event/id/<?=$event->getId()?>">"<?=$event->getName()?>"</a> скидка <?=$event->getDiscont()?>%.
                            Активация <?=date('d-m-Y', strtotime($uevent->getCtime()))?>.
                            Статус <?=$event->getPublick() ? '<b>Активна</b>' : 'Остановлена'?></p>
                        <?php } ?>
                </div>
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Сброс пароля:</label>
                    <div class="col-xs-6">
                       <?php if (isset($_GET['rpass'])) {
                if (@$_GET['rpass'] == 'email') echo 'Новый пароль отправлен по Email';
                elseif (@$_GET['rpass'] == 'sms') echo 'Новый пароль отправлен по Sms'; else 'Ошибка';
            } else {
                ?>
                        <a href="&resetpass=email" class="btn btn-sm btn-success mx-1">Email</a><a href="&resetpass=sms" class="btn btn-sm btn-danger">SMS</a>
                <?php } ?><br />
                <?php if(!$this->save_pass_p){ ?>
                Принудительно:<input type="text" value="" class="form-control" name="pass_p" />
                <?php } else { ?>
                    Новый пароль установлен принудительно.
                <?php } ?>
                    </div>
                </div> 
                    <div class="form-group">
                    <label class="ct-150 control-label">Коментарии админа:</label>
                    <div class="col-xs-6">
                         <textarea rows="5" cols="25" name="admin_coments"><?php echo $this->sub->getAdminComents();?></textarea>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="panel panel-danger">
                <div class="panel-heading"><h3 class="panel-title">Настройка</h3></div>
                <div class="panel-body">
                    <div class="form-group">
                    <label class="ct-150 control-label">Скидка VIP:</label>
                    <div class="col-xs-6">
                        <input name="skidka" class="form-control input" value="<?php echo $this->sub->getSkidka();?>"/> %
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Депозит:</label>
                    <div class="col-xs-6">
                        <span><?=$this->sub->getDeposit()?> грн. </span>
                        
                        <a href="#" class="btn btn-sm btn-success"  data-tooltip="tooltip" title="Изменить депозит пользователя" data-toggle="modal" data-target="#edit_deposit1"  >Изменить</a>
                        <div  id="edit_deposit1" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog  modal-dialog-centered">
                        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Редактирование депозита</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body row">
            <div class="col-sm-12 p-0">
                <div class="alert alert-danger" role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
                  <h4 class="alert-heading">Изменения!</h4> 
                 
                  <p>
                      Для зачисления депозита - введите сумму которую нужно прибавить к текущему депозиту и введите номер заказа на основании которого зачисляете депозит.
                  </p>
                  <hr>
                  <p>
                       Для списания депозита - введите сумму (со знаком минус "-5") которую нужно списать от текущего депозита и введите номер заказа на основании которого списуете депозит.
                  </p>
                  <hr>
                  <p>Помним о том, что все действия с депозитом отображаются в личном кабинен пользователя и отправляются уведомления на почту и sms. <br>Если не хотите что бы клиент получил уведомление, поставте галочку "Не уведомлять пользователя."</p>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="d-block">
                    <label class="control-label">Сумма:</label>
                    <div class="">
                      <!--  <input name="deposit"  class="form-control" style="display: none;" value="<?=$this->sub->getDeposit()?>"/>-->
                        <input name="deposit_edit" id="deposit_edit"  class="form-control" value=""/>
                       <label class="ckbox">
                           <input type="checkbox" class="order-item cheker" id="deposit_email" name="deposit_email" value="1"><span>Не уведомлять пользователя</span>
                       </label> 
                    </div>
        </div>
            </div>
            <div class="col-sm-12">
                <div class="d-block">
                    <label class="control-label">Заказ:</label>
                    <div class="">
                        <input name="order_dep" id="order_dep"  class="form-control" placeholder="Заказ" value=""/>
                    </div>
        </div>
            </div>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
                         </div>
                        </div>
                        </div>
                        
                    </div>
                    
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Заказов на магазин:</label>
                    <div class="col-xs-6">
                       <input name="count_order_magaz"  type="text" class="form-control" value="<?=$this->sub->count_order_magaz?>"/>
                    </div>
                </div>
                    <?php if($this->sub->getBonus()){ ?>
                    <div class="form-group">
                    <label class="ct-150 control-label">Бонус:</label>
                    <div class="col-xs-6">
                        <span><?=$this->sub->getBonus()?> грн.</span>
                    </div>
                </div>
                    <?php } ?>
                    <div class="form-group">
                    <label class="ct-150 control-label">Статус:</label>
                    <div class="col-xs-6">
                       <label class="rdiobox">
                            <input name="customer_status_id" id='activ' type="radio"  value="1" <?php if ($this->sub->getCustomerStatusId() != 2) { ?>checked="checked"<?php } ?>>
                            <span>Активный</span>
                        </label>
                        <label class="rdiobox">
                            <input name="customer_status_id" id='ban' type="radio"  value="1" <?php if ($this->sub->getCustomerStatusId() == 2) { ?>checked="checked"<?php } ?>>
                            <span>Бан</span>
                        </label>
                        
                    </div>
                </div>
                     <div class="form-group ban_coment" style="display:none">
                    <label class="ct-150 control-label">За что Бан:</label>
                    <div class="col-xs-6 " >
                             <input type="text" name="ban_comment" value="<?=$this->sub->getBanComment()?>" />
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Блок наложка:</label>
                    <div class="col-xs-6">
                        <label class="ckbox">
                            <input type="checkbox"  name="bloсk_np_n" id='bloсk_np_n'  value="1" <?php if ($this->sub->bloсk_np_n == 1) { ?>checked="checked"<?php } ?>>
                                <span></span>
                        </label>
                    </div>
                </div>
                     <div class="form-group">
                    <label class="ct-150 control-label">Блок Курьером:</label>
                    <div class="col-xs-6">
                        <label class="ckbox">
                            <input type="checkbox" name="block_cur" id='block_cur'
                       value="1" <?php if ($this->sub->block_cur == 1) { ?>checked="checked"<?php } ?>>
                                <span></span>
                        </label>
                    </div>
                    
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Блок Самовывоз:</label>
                    <div class="col-xs-6">
                        <label class="ckbox">
                            <input type="checkbox" name="block_m" id='block_m'
                       value="1" <?php if ($this->sub->block_m == 1) { ?>checked="checked"<?php } ?>>
                                <span></span>
                        </label>
                    </div>
                </div>
                    <div class="form-group">
                    <label class="ct-150 control-label">Блок быстрой заявки:</label>
                    <div class="col-xs-6">
                        <label class="ckbox">
                           <input type="checkbox" name="block_quick" id='block_quick'
                       value="1" <?php if ($this->sub->block_quick == 1) { ?>checked="checked"<?php } ?>>
                                <span></span>
                        </label>
                    </div>
                </div>
                   
                    
                    
                </div>
            </div>
        </div>
       <div class="col-sm-12">
            <input type="submit" class="btn btn-lg btn-primary" name="savepage" id="savepage" value="Сохранить"/>
       </div>
    </div>
  
</form>
</div>
</div>


<?php  //$orders = wsActiveRecord::useStatic('Shoporders')->findAll(array('customer_id' => $this->sub->getId()));
$orders = wsActiveRecord::useStatic('Shoporders')->findByQuery('SELECT * FROM ws_orders WHERE customer_id ='.$this->sub->getId().' ORDER BY `ws_orders`.`id` DESC LIMIT 10');
if ($orders->count() > 0) {
    ?>
<!--
<p> Оплатить заказы: </p>
<input type="input" class="pay_orders_sum" name="pay_orders_sum" value="0"/> грн.
<input type="button" disabled="disabled" class="pay_pay" value="Оплатить"/> <br/>
<p>
    Заказы на сумму: <span class="all_orders_sum">0</span> грн.<br/>
    На депозит: <span class="to_deposit_sum">0</span> грн.<br/>
</p>
<p>Заказы:</p>-->


<table cellspacing="0" cellpadding="4" id="orders" class="table">
    <tr>
        <th></th>
        <th>Действие</th>
        <th>Статус</th>
        <th>Номер</th>
        <th>Дата</th>
        <th>Товаров</th>
        <th>Стоимость</th>
        <th>Счет</th>
        <th>Магазин</th>
    </tr>
    <?php $row = 'row2'; foreach ($orders as $order) {
    $row = ($row == 'row2') ? 'row1' : 'row2';
    ?>
    <tr class="<?php echo $row; ?>">
        <td>
            <?php if ($order->getStatus() != 2) { ?>
            <input type="checkbox" name="pay_order_<?=$order->getId();?>"  class="user_orders <?php if ($order->getStatus() == 8) echo 'oplach'; ?>"/>
            <?php } ?>
<input type="hidden" class="real_sum" value="<?php echo (float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost() - $order->getDeposit(); ?>" />
        </td>
        <td>
            <a href="/admin/shop-orders/edit/id/<?=$order->getId();?>"><img width="24" height="24"
                                                                                     alt="Редактировать"
                                                                                     src="/img/icons/edit-small.png"></a>
        </td>
        <td><?=$order->getStat()->getName()?>
            <?php if ($order->getAdminPayId()) { ?>
                <br/><span style="color: #6666ff">Проведен</span>
                <?php } ?>
        </td>
        <td><?=$order->getId();?></td>
        <td><?php $d = new wsDate($order->getDateCreate()); echo $d->getFormattedDateTime(); ?></td>
        <td><?php $count_o = $order->countArticles(); echo $count_o; ?></td>
        <td><?php if ($count_o != 0) {
            echo number_format((double)$order->getTotal('a'), 2, ',', '') . ' грн <br/>' . Number::formatFloat(((float)$order->getPriceWithSkidka() + (float)$order->getDeliveryCost() - (float)$order->getDeposit()), 2) . ' грн';
        } ?></td>
        <td>
            <a target="_blank" href="/admin/generateorder/id/<?php echo $order->getId();?>/type/1">Магазин</a><br/>
            <a target="_blank" href="/admin/generateorder/id/<?php echo $order->getId();?>/type/2">Укрпочта</a><br/>
        </td>
        <td>
            <?php echo $order->getDeliveryTypeId() ? $order->getDeliveryType()->getName() : "&nbsp;"; ?><br/>
            <?php if ($order->getNakladna()) { ?>
            Накладная №: <?php echo $order->getNakladna(); ?>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>

<?php } ?>
<script>
   
    $('.view_cart').click(function (e) {
var id = e.target.attributes.getNamedItem("data-id").value;
$.ajax({
        type: 'POST',
        url: '/admin/carts/',
        data: { method: 'view_cart', id: id},
        success: function (data) { 
           // console.log(data);
            fopen('Содержимое корзины', data);
        }
    });	
});

    $('#edit_deposit1').on('show.bs.modal', function (e) {
        $('#deposit_edit').attr('required','required');
        $('#order_dep').attr('required','required');
  // do something...
});
$('#edit_deposit1').on('hide.bs.modal', function (e) {
    //console.log(e);
  // do something...
  $('#deposit_edit').attr('required',false);
        $('#order_dep').attr('required',false);
});
$(":radio").click(function(){
    
                    $('.ban_coment').slideToggle(); //return false;
                });

    
 /**   $(document).ready(function () {

        $('.user_orders').click(function () {
            $('.pay_pay').attr('disabled', true);


            if ($(this).attr('checked')) {
                if ($(this).hasClass('oplach')) {
                    if (!confirm('Заказ уже оплачкен. Продолжить ?')) return false;
                }
            }
            sum = 0;
            jQuery.each($('.user_orders:checked'), function () {
                sum = parseFloat(sum) + parseFloat($(this).parent().find('.real_sum').val());
            });
            $('.all_orders_sum').html(sum.toFixed(2));
            depoz = parseFloat($('.pay_orders_sum').val()) - parseFloat(sum.toFixed(2));
            $('.to_deposit_sum').html(depoz.toFixed(2));
            if ($('.to_deposit_sum').html() >= 0) {
                $('.pay_pay').attr('disabled', false);
            }

        });
        $('.pay_orders_sum').keyup(function () {
            $('.pay_pay').attr('disabled', true);
            depoz = parseFloat($('.pay_orders_sum').val()) - parseFloat($('.all_orders_sum').html());
            $('.to_deposit_sum').html(depoz.toFixed(2));
            if ($('.user_orders:checked').val()) {
                if ($('.to_deposit_sum').html() >= 0) {
                    $('.pay_pay').attr('disabled', false);
                }
            }
        });
        $('.pay_pay').click(function () {
            if ($('.user_orders:checked').val()) {
                if (parseFloat($('.pay_orders_sum').val()) > 0) {
                    ids = '';
                    i = 0;
                    jQuery.each($('.user_orders:checked'), function () {
                        if (i != 0) {
                            ids += ',' + $(this).attr('name').substr(10);
                        } else {
                            ids += $(this).attr('name').substr(10);
                        }
                        i++;
                    });
                    window.location = '/admin/payorder/?ids=' + ids + '&pay=' + parseFloat($('.pay_orders_sum').val()) + '&customer=' +<?php echo $this->sub->getId();?>;
                } else {
                    alert('Введите сумму');
                }
            } else {
                alert('Выберите заказы');
            }
        });

    });
    */

    $(document).ready(function () {
	$('#go_mail').on( "click", function () {
	//alert($('#email').val());
	var id = $('#id').val();
		$.ajax({
			beforeSend: function( data ) {
                            console.log(data);
				$('#go_mail').attr('value', 'Email отправляется...');
			},
			type: "POST",
			url: '/admin/user/',
			data: {id: id, method: 'emailgo'},
			success: function( data ) {
				console.log(data);
			},
			dataType: 'json',
			complete: function( data ) {
			
				$('#go_mail').attr('value', 'Email отправлен!');
				$('#ok').slideToggle();//fadeOut(1000);
			},
			error: function( e ) {
                            console.log(e);
			alert('Что-то пошло не так. Попробуйте снова!');
			}
		});
	
	
	});
	
	});
	</script>