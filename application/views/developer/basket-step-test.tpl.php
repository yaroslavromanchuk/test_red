<?php
$middle_name = '';
$name = '';
$last_name = '';
$phone = '';
$email = '';
$comment = '';
$street = '';
$hous = '';
$flat = '';
$city = '';
$sklad = '';
$index = '';
$obl = '';
$rayon = '';
if($this->ws->getCustomer()->getIsLoggedIn()){
$middle_name = $this->ws->getCustomer()->getMiddleName();
$name = $this->ws->getCustomer()->getFirstName();
$last_name = $this->ws->getCustomer()->getLastName();
$phone = $this->ws->getCustomer()->getPhone1();
$email = $this->ws->getCustomer()->getEmail();
}elseif(@$this->basket_contacts){
$middle_name = @$this->basket_contacts['middle_name'];
$name = @$this->basket_contacts['name'];
$last_name = @$this->basket_contacts['last_name'];
$phone = @$this->basket_contacts['telephone'];
$email = @$this->basket_contacts['email'];
}
?>
<link href="/lib/highlightjs/github.css" rel="stylesheet">
<link href="/lib/jquery.steps/jquery.steps.css" rel="stylesheet">
<link href="/css/starling.css" rel="stylesheet">
<link rel="stylesheet" href="/lib/jquery-ui/jquery-ui.css" type="text/css" media="screen">
<style>
    .g{
        color: #a5a5a5;
    }
    </style>
    <div class="card">
<div class="row m-auto p-5 ">
    <div class="col-xl-12">
        <h6 class="card-body-title"><?=$this->getCurMenu()->getName()?></h6>
    </div>
    <div class="col-xl-12">
        <?php if($this->info){
echo '<div class="col-xs-10 col-xs-offset-1">';
foreach ($this->info as $k => $v){
if($k == 'login') {
    echo '<div class="alert alert-warning col-xs-6" style="float: none;text-align: center;margin: 0 auto 10px;" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<p style="color: black;margin: 5px;">Делали у нас заказ ранее?<br>Для продолжения войдите в свой личный кабинет.</p>
  <a href="#myModalLogin" role="button"  data-placement="left"  data-toggle="modal" style=" text-decoration: none;">
  <button class="btn btn-primary m" data-dismiss="alert"  aria-hidden="true">Войти</button>
	</a>
</div>';
}else{
    echo '<div class="alert alert-info">'.$v.'</div>'; 
    
}
} 
echo '</div>';
}
 ?>
<div class="info"></div>
<div class="alert alert-warning col-xs-6 open_form_avtor" style="float: none;text-align: center;margin: 0 auto 10px;display:none;" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <a href="#myModalLogin" role="button"  data-placement="left"  data-toggle="modal" style=" text-decoration: none;">
  <button class="btn btn-primary m" data-dismiss="alert"  aria-hidden="true">Войти в аккаунт</button>
	</a>
</div>
    </div>
</div>
   </div> 
<form method="post" action="" class="contact-form m-auto mb-sm-5 mb-md-5 mb-xl-2 mb-5 " name="basket_contacts"  data-parsley-validate >
<div class="row m-auto bg-white p-0 " >
<div  class="col-md-12 col-lg-10 col-xl-10 m-auto p-lg-5 p-md-2 " >
    <div id="wizard"  >
 <h3>Контактные данные</h3>
            <section>
	 <div class="row m-auto p-0">
              <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <label class="form-control-label"><?=$this->trans->get('Фамилия')?>: <span class="text-danger">*</span></label>
				  <input name="middle_name" type="text" ng-model="middle_name" placeholder="Фамилия" id="middle_name"   required class="form-control " value="<?=$middle_name?>">
                </div><!-- form-group -->

		<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <label class="form-control-label"><?=$this->trans->get('Имя')?>: <span class="text-danger">*</span></label>
                  <input name="name" type="text" required placeholder="Имя" ng-model="name"  id="name" class="form-control" value="<?=$name?>">
                </div>
		<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <label class="form-control-label"><?=$this->trans->get('Отчество')?>: </label>
                  <input name="last_name" type="text" id="last_name" placeholder="Отчество" class="form-control" value="<?=$last_name?>">
                </div>
		<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <label class="form-control-label"><?=$this->trans->get('Телефон')?>: <span class="text-danger">*</span></label>
                  <input name="phone" placeholder="38(000)000-00-00" required maxlength="16" id="phone"  type="tel"  class="form-control " value="<?=@$phone?>">
                </div>
		<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <label class="form-control-label">Email: <span class="text-danger">*</span></label>
                  <input  name="email" type="email" placeholder="email" required data-parsley-type="email" data-parsley-trigger="change" id="email" class="form-control " value="<?=@$email?>">
                </div>
		<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6">
                  <label class="form-control-label"><?=$this->trans->get('Коментарий к заказу')?>:</label>
                 <textarea name="comments" id="comments" rows="1" class="form-control" placeholder="<?=$this->trans->get('Коментарий к заказу')?>"></textarea>
                </div>
				</div>
            </section>
<h3>Доставка и оплата</h3>
            <section>
<div class="row m-auto p-0">
			  <div class="card pd-x-30 pd-y-10 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="delivery1" >
			<div class="form-group">
                            <label for="delivery" class="font-weight-bold"><?=$this->trans->get('Выберите способ доставки')?>:</label>
      <select class="form-control select21" id="delivery" class="delivery" name="delivery" data-placeholder="Выберите способ доставки"  required onchange="loadDopFile(this)">
          <option label="Способ доставки..."></option>
        <?php foreach(wsActiveRecord::useStatic('DeliveryType')->findAll(array('active_user'=> 1, 'id != 16'), array('name'=>'ASC')) as $dely){ ?>
          <option value="<?=$dely->getId();?>"><span><?=$dely->getName()?></span></option>>
			   <?php } ?>>
      </select>
      </div>
			</div>
    <div class="card pd-x-30 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 d-none" id="payment_none"  >
                            <div class="form-group">
                                <label for="payment" class="font-weight-bold"><?=$this->trans->get('Выберите способ оплаты')?>:</label>
                                 <select class="form-control" id="payment" class="payment" name="payment" data-placeholder="Выберите способ оплаты"  required>
                                     
                                 </select>
                            </div>
</div>
                            
<div class="card pd-30 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="DopDelivery">
    <div class="card "> 
    <div class="row m-auto p-0">
        
        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 index hide_dop d-none">
            <label class="col-xs-6 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-control-label"><?=$this->trans->get('Индекс')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="index" required id="index" placeholder="<?=$this->trans->get('Индекс')?>">
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 obl hide_dop d-none">
            <label class="form-control-label"><?=$this->trans->get('Область')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="obl" required id="obl" placeholder="<?=$this->trans->get('Область')?>">
        </div>
	<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 rayon hide_dop d-none">
            <label class="form-control-label"><?=$this->trans->get('Район')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="rayon" required id="rayon" placeholder="<?=$this->trans->get('Район')?>">
        </div>      
	<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 city hide_dop d-none">
            <label class="form-control-label"><?=$this->trans->get('Город')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="city" required id="city" placeholder="<?=$this->trans->get('Город')?>">
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 city_np hide_dop d-none">
            <label class="form-control-label"><?=$this->trans->get('Город')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="city_np" required id="city_np" placeholder="<?=$this->trans->get('Город')?>">
            <input type="text" class="d-none" name="cityx"  id="cityx"  >
        </div>
	<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 sklad hide_dop d-none">
            <label class="form-control-label"><?=$this->trans->get('Склад')?>: <span class="text-danger">*</span></label>
            <!--<input type="text" class="form-control" name="sklad"  required  id="sklad" placeholder="<?=$this->trans->get('Склад')?>">-->
            <select class="form-control" name="sklad"  required  id="sklad" onchange="$('#sklad_np').val($('#sklad option:selected').data('id')); return false;">
                
            </select>
            <input type="text" id="sklad_np" name="sklad_np"  class="d-none" />
        </div>
	<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 street hide_dop d-none">
            <label class="form-control-label"><?=$this->trans->get('Улица')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="street" required id="street" placeholder="<?=$this->trans->get('Улица')?>">
            <img class="img_gif" id="s_k"  src="/img/delivery/loading.gif" />
        </div>
	<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 hous hide_dop d-none">
            <label class="col-xs-6 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-control-label"><?=$this->trans->get('Дом')?>: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="hous" required id="hous" placeholder="<?=$this->trans->get('Дом')?>">
        </div>
	<div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-6 flat hide_dop d-none">
        <label class="col-xs-6 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-control-label"><?=$this->trans->get('Квартира')?>: <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="flat" required id="flat" placeholder="<?=$this->trans->get('Квартира')?>">
        </div>
	<div class="form-group col-sm-12 col-md-12 col-lg-4 col-xl-4 pobeda hide_dop d-none">
			  <p>
			  <?=$this->trans->get('<p>г. Киев</p>
							<p>проспект Победы, 98/2</p>
							<p>между метро "Нивки" и "Святошино"</p>
							<p>пн-вс: 11:00-21:00</p>
							<p>(093) 854 23 53</p>
							<p>(067) 406 90 80</p>')?>
			  </p>
              </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-8 pobeda hide_dop d-none">
           <iframe src="https://maps.google.com.ua/maps?f=q&source=s_q&hl=ru&geocode=&q=%D0%B3.+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%BF%D1%80%D0%BE%D1%81%D0%BF%D0%B5%D0%BA%D1%82+%D0%9F%D0%BE%D0%B1%D0%B5%D0%B4%D1%8B,+98%2F2&aq=&sll=50.49355,30.460997&sspn=0.006935,0.021136&gl=ua&ie=UTF8&hq=&hnear=%D0%BF%D1%80%D0%BE%D1%81%D0%BF.+%D0%9F%D0%BE%D0%B1%D0%B5%D0%B4%D1%8B,+98%2F2,+%D0%9A%D0%B8%D0%B5%D0%B2,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9A%D0%B8%D0%B5%D0%B2&t=m&ll=50.465263,30.396852&spn=0.016391,0.04283&z=14&iwloc=A&output=embed" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen ></iframe>
        </div>
        <div class="form-group col-sm-12 col-md-12 col-lg-4 col-xl-4 stroyka hide_dop d-none">
			  <p>
			  <?=$this->trans->get('<p>г. Киев</p>
							<p>ул.Строителей, 40</p>
							<p>ТЦ "DOMA", 2 этаж</p>
							<p>пн-вс: 10:00-22:00</p>
							<p>(063) 010 34 53</p>
							<p>(098) 634 26 82</p>')?>
			  </p>
              </div>
       <div class="form-group col-sm-12 col-md-12 col-lg-8 col-xl-8 stroyka hide_dop d-none">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d500.4270632597404!2d30.611137329965956!3d50.454703823112624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6f6224f101f87811!2sRed!5e0!3m2!1sru!2sua!4v1517309778043" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
				</div>	
    </div>
				</div>
				</div>			
            </section>
 <h3>Подтверждение заказа</h3>
            <section>
            <div class="row m-auto p-0">
               
                <div class="col-xl-4">
                    <div class="card">
  <div class="card-body">
    <h5 class="card-title">Контактные данные</h5>
    <p class="card-text"><span class="g">Ф.И.О.</span> <span class="k_fio"></span></p>
    <p class="card-text"><span class="g">Телефон: </span> <span class="k_phone"></span></p>
    <p class="card-text"><span class="g">Email: </span> <span class="k_email"></span></p>
    <p class="card-text"><span class="g">Коментарий: </span> <span class="k_koment"></span></p>
  </div>
</div>
                </div>
                <div class="col-xl-4">
                             <div class="card">
  <div class="card-body">
    <h5 class="card-title">Доставка</h5>
    <p class="card-text"><span class="g">Способ доставки:</span> <span class="k_dely"></span></p>
    <p class="card-text"><span class="g">Адрес: </span> <span class="k_adress"></span></p>
    <p class="card-text"><span class="g">Способ оплаты: </span> <span class="k_pay"></span></p>
  </div>
</div>
                </div>
                 <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                         <h5 class="card-title">Товары</h5>
                    <?php 
                 foreach ($_SESSION['basket'] as $item) {
                     if (($article = wsActiveRecord::useStatic('Shoparticles')->findById($item['article_id'])) && $article->getId() && $item['count'] > 0) { ?>
                <p class="card-text">
                    <img src="<?=$article->getImagePath('listing')?>" style="max-width:75px;"  alt="<?=htmlspecialchars($article->getTitle());?>" />
                    <span><?=$item['option_price']?$item['option_price']:$item['price']?> грн.</span>
                </p>
                  <?php   } 
                 }
                 echo '<p><span>К оплате: </span><span>'.$_SESSION['total_price'].' грн.</span></p>'
                ?>
                </div>
                        </div>
                    </div>
		</div>
            </section>
 </div>
</div>
            
 
</div>
 </form>
<!--     
            -->
 <script src="/lib/popper.js/popper.js?v=1.0"></script>
	<script src="/lib/bootstrap/bootstrap.js"></script>
		<script src="/lib/jquery-ui/jquery-ui.js"></script>
			<script src="/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
				<script src="/lib/highlightjs/highlight.pack.js"></script>
                                <script src="/lib/parsleyjs/parsley.js?v=1.7"></script>
				<script src="/lib/jquery.steps/jquery.steps.js"></script>
						
<script>
window.Parsley.setLocale('ru');
function loadDopFile(e){
console.log(e.value);
$.ajax({
                url: '/developer/basketcontacts/',
                type: 'POST',
		async: false,
                dataType: 'json',
                data: '&delivery_type=true&id='+e.value,
                success: function (res) {
				$('.hide_dop').addClass('d-none');
				if(res.pay){
                                $('#payment_none').removeClass('d-none');
				var pay = '<option label="Способ оплаты..."></option>';
				for (var key in res.pay){
				pay += '<option value="'+key+'">'+res.pay[key]+'</option>';
                                }
				$('#payment').html('');
				$('#payment').html(pay);
				}
				if(res.dop){
				for (var key in res.dop){
				$('.'+res.dop[key]).removeClass('d-none');
				console.log(res.dop[key]);
				}
				
				
				}
				console.log(res);
                },
				error: function(e){
				console.log(e);
				}
            });

return false;
}
$(function(){
'use strict';
		$('#wizard').steps({
          headerTag: 'h3',
          bodyTag: 'section',
          autoFocus: true,
		  labels: {
		  cancel: "Отмена",
		  current: "текущий шаг:",
		  pagination: "Pagination",
		  next: "Далее",
		  previous: "Назад",
		  loading: "Загрузка ...",
		  finish: "Оформить заказ",
		  },
          titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
          onStepChanging: function (event, currentIndex, newIndex) {
            if(currentIndex < newIndex) {
              // Step 1 form validation
              if(currentIndex === 0) {
                  var m = $('#middle_name');
			var middle_name = m.parsley();
                        var n = $('#name');
			var name = n.parsley();
                        var las = $('#last_name');
                        var p = $('#phone');
			var phone = p.parsley();
                        var em = $('#email');
			var email = em.parsley();
                        var kom = $('#comments');
                        
			if(middle_name.isValid() && name.isValid() && phone.isValid() && email.isValid()) {
			var valid = false;
			 var ret;
			var url = '/developer/basketcontacts/';
				var new_data = '&contact_valid=true&email='+$('#email').val()+'&phone='+$('#phone').val();
		var response = $.ajax({
		beforeSend: function( data ) {
		//$('#pageslist').html('<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
		async: false,
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				if(res.open_form_avtor == 'open'){
				$('.info').html('');
				if(res.email){
				$('#email').toggleClass("border-danger");
				$('.info').append('<div class="alert alert-dark" role="alert">Email '+res.email+' уже зарегистрирован в системе.<a href="#myModalLogin" role="button"  data-placement="left"  data-toggle="modal" style="text-decoration: none;margin-left: 10px;"><button class="btn btn-danger btn-sm m" data-dismiss="alert"  aria-hidden="true">Войти в аккаунт</button></a></div>');
				}else{
				$('#email').removeClass("border-danger");
				}
				if(res.phone){ 
				$('#phone').toggleClass("border-danger");
				$('.info').append('<div class="alert alert-dark" role="alert">В системе уже зарегистрирован пользователь с номером телефона: '+res.phone+'.<a href="#myModalLogin" role="button"  data-placement="left"  data-toggle="modal" style="text-decoration: none;margin-left: 10px;"><button class="btn btn-danger btn-sm m" data-dismiss="alert"  aria-hidden="true">Войти в аккаунт</button>	</a></div>');
				}else{
				$('#phone').removeClass("border-danger");
				}
				return false;
				}else{
				return true;
				}
				
                },
				error: function(e){
				console.log(e);
				}
            });
			
			console.log(response);
			//var data = JSON.parse(response);
			console.log(response.responseJSON.open_form_avtor);
			
			var ret = response.responseJSON.open_form_avtor;
			if(ret === 'off'){
                        valid = true;
                        $('.k_fio').html(m.val()+' '+n.val()+' '+las.val());
                        $('.k_phone').html(p.val());
                        $('.k_email').html(em.val());
                        $('.k_koment').html(kom.val());
                        }
				return valid;
              } else {
			  middle_name.validate();
			  name.validate();
			  phone.validate();
			  email.validate();
            }
              }
              // Step 2 form validation
              if(currentIndex === 1) {
			 // Parsley._remoteCache = {};
                
                var del = $('#delivery');
                var del_text = $('#delivery option:selected').text();
                var pay = $('#payment');
                var pay_text = $('#payment option:selected').text();
                
			
		var delivery = del.parsley();
                var payment = pay.parsley();
                if(del.val() == 9) {
                var st = $('#street');
                var h = $('#hous');
                var f = $('#flat');
                var street = st.parsley();
                var hous = h.parsley();
                var flat = f.parsley();
               
               if(delivery.isValid() && payment.isValid() && street.isValid() && hous.isValid() && flat.isValid()){
               $('.k_dely').html(del_text);
               $('.k_adress').html(st.val()+', дом. '+h.val()+', кв. '+f.val());
               $('.k_pay').html(pay_text);
               
               return true;
               }else{
               delivery.validate();
               payment.validate();
               street.validate();
               hous.validate();
               flat.validate();
               }
               
               }else if(del.val() == 8){
               var c = $('#city_np');
               var s = $('#sklad');
               var s_text = $('#sklad option:selected').text();
               var city = c.parsley();
               var sklad = s.parsley();
               
               if(delivery.isValid() && payment.isValid() && city.isValid() && sklad.isValid()){
               
               $('.k_dely').html(del_text);
               $('.k_adress').html('город '+c.val()+', отд. '+s_text);
               $('.k_pay').html(pay_text);
               return true;
               }else{
               delivery.validate();
               payment.validate();
               city.validate();
               sklad.validate();
               }
               }else if(del.val() == 4){
               var i = $('#index');
               var o = $('#obl');
               var r = $('#rayon');
               var c = $('#city');
               var st = $('#street');
               var h = $('#hous');
               var f = $('#flat');
               var index = i.parsley();
               var obl = o.parsley();
               var rayon = r.parsley();
               var city = c.parsley();
               if(delivery.isValid() && payment.isValid() && city.isValid() && index.isValid() && obl.isValid() && rayon.isValid()){
               $('.k_dely').html(del_text);
               var adr = i.val()+', обл. '+o.val()+', район '+r.val()+', город '+c.val();
            if(st.val()){ adr+= ', ул. '+st.val();}
               if(h.val()){ adr+= ', дом. '+h.val();}
               if(f.val()){ adr+= ', кв. '+f.val();}
               $('.k_adress').html(adr);
               $('.k_pay').html(pay_text);
               
               return true;
               }else{
               delivery.validate();
               payment.validate();
               city.validate();
               index.validate();
               obl.validate(); 
               rayon.validate();
               }
               }else if(del.val() == 3 || del.val() == 5){
              // console.log(del);
               if(delivery.isValid() && payment.isValid()){
               $('.k_dely').html(del_text);
                $('.k_adress').html('');
               $('.k_pay').html(pay_text);
               return true;
               }else{
               delivery.validate();
               payment.validate();
               }
               
               }else{
               if(delivery.isValid() && payment.isValid()){
               return true;
               }else{
               delivery.validate();
               payment.validate();
               }
               }
				
              }
			   // Step 2 form validation
              if(currentIndex === 2) {
			/// var sost = $('#sostav').parsley();
			// if(sost.isValid()) {
             //    return true;
             //   } else {
			//	sost.validate();
			//	}

              }
		
            // Always allow step back to the previous step even if the current step is not valid.
            } else {
			return true; 
			}
          }
        });//end steps
        
        $('#street').autocomplete({
	source: '/shop/getmistcity/?what=street',
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
			$('#s_k').fadeIn(500);
			},
			select: function (event, ui) {
			$('#s_k').fadeOut(300);
			}
				});
                                
                                var uidnp = $('#city_np').val();
	$('#city_np').autocomplete({
			source: '/shop/novapochta/?what=citynpochta&term=' + uidnp,
			minLength: 2,
			maxHeight: 200,
			deferRequestBy: 100,
			search: function( event, ui ) {
			$('#cityx').val('');
			},
			select: function (event, ui) {
				if (ui.item == null) {
					$('#cityx').val('');
				} else {
					$('#cityx').val(ui.item.id);
					myNP(ui.item.id);
	
				}
			}
		});
});//exit ready

			
		function myNP (x) {
        $.ajax({
                url: '/shop/novapochta/',
                type: 'POST',
                dataType: 'json',
                data: 'warehouses='+x+'&metod=getframe_np',
                success: function (data) {
                    console.log(data);
                    $('#sklad').html('<option label="Способ оплаты..."></option>'+data);
                   // $('#k_np_g').fadeOut(1);
                  //  $('#sklad_np_leb').fadeOut(1);
                    $('#sklad').fadeIn(10);
                },
                error: function (e){
                    console.log(e);
                }
        });
		return false;
}		
</script>