<style>
    img {
        vertical-align: middle;
    }

    img.active {
        border: 1px solid #fff;
        padding: 1px;
    }

    #ban_table, #ban_table table{border-collapse: collapse;}
    #ban_table tr:nth-child(even){background: #DBDCDD;}
    #ban_table tr td input[type="text"]{background: #EDEEEF; border: none;}
    #ban_table tr:nth-child(even) td input[type="text"]{background: #DBDCDD;}
    #ban_table thead th{height: 25px; width: 210px;}
    #ban_table thead th:first-child{width: 55px;}
    #ban_table tr td{width: 210px; height: 25px; padding: 2px; border-bottom: 1px solid #9c9e9f;}
    #ban_table tr td:first-child{width: 55px;}
    #ban_table tr td input{margin: 0 45%;}
    #ban_table tr td input[type="text"]{margin: 0; width: 100%;}
    button{margin: 15px 0;}
</style>

<img src="<?=SITE_URL; ?><?=$this->getCurMenu()->getImage(); ?>" alt="" class="page-img"/>
<h1><?=$this->getCurMenu()->getTitle(); ?></h1>
<hr/>
<?=$this->getCurMenu()->getPageBody(); ?>
<?php if (isset($this->text)) { ?> <textarea rows="20" cols="60"><?=$this->text;?></textarea> <?php } ?>
<form action="" method="get">
    <h4>Поиск</h4>
	<span> Id </span><input type="text" class="form-control input" name="id" value="<?=$this->get->id?>" >
	<span> Email </span><input type="text" class="form-control input" name="email" value="<?=$this->get->email?>" >
	<span> Имя </span><input type="text" class="form-control input" name="name" value="<?=$this->get->name?>"/>
	<span> Тел. </span><input type="text" class="form-control input" name="phone" value="<?=$this->get->phone?>"/>
   <!-- <span> № карты </span><input type="text" class="input" name="cart" value="<?=$this->get->cart?>"/>-->
    <br/>Заказы (номера через ",")<input type="text" class="form-control input" name="orders" value="<?=$this->get->orders?>">
    <p>  
<label><input type="radio" name="ban" value="1" <?php if (@$_GET['ban'] == 1) { ?> checked="checked"<?php } ?>/>Общий Бан</label>
<label><input type="radio" name="ban" value="2" <?php if (@$_GET['ban'] == 2) { ?> checked="checked"<?php } ?>/>Бан курьер</label>
<label><input type="radio" name="ban" value="3" <?php if (@$_GET['ban'] == 3) { ?> checked="checked"<?php } ?>/>Бан НП</label>
<label><input type="radio" name="ban" value="4" <?php if (@$_GET['ban'] == 4) { ?> checked="checked"<?php } ?>/>Бан СМ</label><br>
<label><input type="radio" name="ban" value="5" <?php if (@$_GET['ban'] == 5) { ?> checked="checked"<?php } ?>/>Бан Быстрой заявки</label><br>
<label><input type="radio" name="ban" value="6" <?php if (@$_GET['ban'] == 6) { ?> checked="checked"<?php } ?>/>Бан Justin</label><br>
<button   id="cler" class="button" style="display:none;" type="button" title="Очистить фильтр">Очистить фильтры</button>
	</p>
	<input type="submit" class="btn btn-default" value="Найти"/>
</form>
<a href="/admin/getusertoexcels/">Пользователи в Excel</a>
<br/><p>По умолчанию: последние <?php if(@$this->getSubscribers()) echo $this->getSubscribers()->count(); ?> зарегистрированых</p>
<table id="pageslist" cellpadding="2" cellspacing="0" class="table">
    <tr>
        <th></th>
        <th class="c-projecttitle"><span>Имя</span>
				<span>
				<a href="&sorting=c.first_name&direction=ASC"><img
                        src="<?php echo SITE_URL; ?>/img/icons/down-small.png" alt="&darr;" <?php
                    if ($_GET['sorting'] == 'c.first_name' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
				<a href="&sorting=c.first_name&direction=DESC"><img src="<?php echo SITE_URL; ?>/img/icons/up-small.png"
                                                                    alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'c.first_name' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
				</span><span>Фамилия</span>
				<span>
				<a href="&sorting=c.middle_name&direction=ASC"><img
                        src="<?php echo SITE_URL; ?>/img/icons/down-small.png" alt="&darr;" <?php
                    if ($_GET['sorting'] == 'c.middle_name' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
				<a href="&sorting=c.middle_name&direction=DESC"><img
                        src="<?php echo SITE_URL; ?>/img/icons/up-small.png" alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'c.middle_name' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
				</span>
        </th>
        <th class="c-projecttitle">Е-мейл/Логин
				<span>
				<a href="&sorting=c.email&direction=ASC"><img src="<?php echo SITE_URL; ?>/img/icons/down-small.png"
                                                              alt="&darr;" <?php
                    if ($_GET['sorting'] == 'c.email' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
				<a href="&sorting=c.email&direction=DESC"><img src="<?php echo SITE_URL; ?>/img/icons/up-small.png"
                                                               alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'c.email' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
				</span>
        </th>
        <th class="c-projecttitle" width="102">Телефон
				<span>
				<a href="&sorting=c.phone1&direction=ASC"><img src="<?php echo SITE_URL; ?>/img/icons/down-small.png"
                                                               alt="&darr;" <?php
                    if ($_GET['sorting'] == 'c.phone1' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
				<a href="&sorting=c.phone1&direction=DESC"><img src="<?php echo SITE_URL; ?>/img/icons/up-small.png"
                                                                alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'c.phone1' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
				</span>
        </th>
        <th class="c-clientname" width="60">Cкидка по акции</th>
        <th class="c-clientname">Скидка<br>

            <div>
                <a href="&sorting=c.skidka&direction=ASC"><img src="<?php echo SITE_URL; ?>/img/icons/down-small.png"
                                                               alt="&darr;" <?php
                    if ($_GET['sorting'] == 'c.skidka' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
                <a href="&sorting=c.skidka&direction=DESC"><img src="<?php echo SITE_URL; ?>/img/icons/up-small.png"
                                                                alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'c.skidka' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
            </div>
        </th>
        <th class="c-clientname">Заказов<br>
             <div>
                <a href="&sorting=order_count&direction=ASC"><img src="/img/icons/down-small.png"
                                                               alt="&darr;" <?php
                    if ($_GET['sorting'] == 'order_count' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
                <a href="&sorting=order_count&direction=DESC"><img src="<?php echo SITE_URL; ?>/img/icons/up-small.png"
                                                                alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'order_count' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
            </div>
           <!-- <a href="&sorting=order_count&direction=DESC" style="color:#F22">ТОП 100</a>-->
        </th>
        <th class="c-clientname">Соглашение<br>

            <div>
                <!-- <a href="&sorting=o.date_create&direction=ASC"><img src="<?php echo SITE_URL; ?>/img/icons/down-small.png" alt="&darr;" <?php
                if ($_GET['sorting'] == 'o.date_create' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
                -->
                <a href="&sorting=o.date_create&direction=DESC"><img
                        src="/img/icons/up-small.png" alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'o.date_create' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
            </div>
        </th>
        <th class="c-clientname">Депозит<br>

            <div>
                <a href="&sorting=c.deposit&direction=ASC"><img src="/img/icons/down-small.png" alt="&darr;" <?php
                    if ($_GET['sorting'] == 'c.deposit' && $_GET['direction'] == 'ASC') echo 'class="active"';?>/></a>
                <a href="&sorting=c.deposit&direction=DESC"><img src="/img/icons/up-small.png" alt="&uarr;" <?php
                    if ($_GET['sorting'] == 'c.deposit' && $_GET['direction'] == 'DESC') echo 'class="active"';?>/></a>
            </div>
        </th>
        <th>История<br>писем</th>
    </tr>
<?php
    $row = 'row1';
    foreach ($this->getSubscribers() as $sub) {
        $row = ($row == 'row2') ? 'row1' : 'row2';
?>
        <tr class="<?php echo $row; ?>">
            <td class="kolomicon"><a href="<?php echo $this->path; ?>user/edit/id/<?php echo $sub->getId(); ?>/"><img
                        src="/img/icons/edit-small.png" alt="Редактирование"/></a>
                <a href="https://www.red.ua/account/login/j25k17l2517/1/username/<?=$sub->username?>/"  target="_blank">Login</a>
			</td>
            <td class="c-projecttitle"><?php echo $sub->getFullname(); ?> <?php if ($sub->getCustomerStatusId() == 2 or $sub->bloсk_np_n == 1 or $sub->block_cur == 1 or $sub->block_m == 1) {
                    echo '<span style="color: red; font-weight: bold; cursor: pointer" onclick="$(this).parent().find(\'.ban_info\').slideToggle();">Бан</span>';
                } ?>
                <p class="ban_info" style="display: none;">
                    За что Бан: <?php echo $sub->getBanComment(); ?><br/>
                    Последний бан <?php echo date('d-m-Y H:i:s', strtotime($sub->getBanDate())); ?> установил
<?php
                    $customer_ban = new Customer($sub->getBanAdmin());
                    if ($customer_ban) {
                        echo $customer_ban->getFullname();
                    }
?>
                </p>
            </td>
            <td class="c-projecttitle"><?php echo $sub->getEmail();
                if ($sub->getEmail() != $sub->getUsername()) echo ' /<br>' . $sub->getUsername(); ?></td>
            <td class="c-projecttitle"><?php echo $sub->getPhone1(); ?></td>
            <td class="c-projecttitle"><?php echo (int)EventCustomer::getEventsDiscont($sub->getId()); ?>%</td>
            <td class="c-projecttitle"><?php echo $sub->getDiscont(false, 0, true); ?>%</td>
            <td class="c-projecttitle"><?php echo $sub->getOrderCount(); ?></td>
            <td class="c-projecttitle"><?php echo $sub->isUserTerms() ? date('d-m-Y', strtotime($sub->isUserTerms())) : 'Нет'; ?></td>
            <td class="c-projecttitle"><?php echo $sub->getDeposit(); ?></td>
            <td><i class="icon ion-email tx-30 pd-5  " alt="письмо" onclick="return SearchMail1(<?=$sub->id?>);" data-id="<?=$sub->id?>" data-placement="left" title="" data-tooltip="tooltip" data-original-title="История отправленных писем пользователю. По умолчанию 30 последних!"></i></td>
        </tr>
    <?php
    }
    ?>
</table>


<script>
   $(document).ready(function(){
   $('input:radio').change(function(){
   $('#cler').show();}
   );
   if($('input:radio:checked').val())  $('#cler').show();
	  $('#cler').click(function(){
	  $('input:radio').attr('checked', false);
	  $('#cler').hide();
	  });
   });
   function SearchMail1(id){
       $.ajax({
			url: '/admin/nowamail/',
			type: 'POST',
			dataType: 'json',
			data: {id: id, metod:'gel_email_customer'},
			success: function (res) {
				console.log(res);
				fopen('История отправлленых писем пользователю ID:'+id, res.message, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
				error: function(e){
				console.log(e);
				}
		});
       
     //  var form ='<div class="form-group"><label for="recipient-name" class="col-form-label">Тема:</label><input type="text" class="form-control" id="email_subject"></div><div class="form-group"><label for="message-text" class="col-form-label">Сообщение:</label><textarea class="form-control" id="mesageemail"></textarea></div>';
//var footer = '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button><button class="btn btn-primary" onclick="return go_email('+id+');" >Отправить</button>';

//fopen('История отправлленых писем пользователю ID:'+id, form);
		return false;
   }
  
   function LoadGetForm(file){
        $.ajax({
			url: '/admin/nowamail/',
			type: 'POST',
			dataType: 'json',
			data: {file: file, metod:'gel_email_customer_load_form'},
                        beforeSend: function(){
                            $("#result_load_form").html('<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><br><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');
                    //$('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).html('body');
                },
			success: function (res) {
                            $("#result_load_form").html(res);
				//console.log(res);
				//fopen('История отправлленых писем пользователю ID:'+id, res.message, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
				error: function(e){
				console.log(e);
				}
		});
       return false;
    
}
 
</script>