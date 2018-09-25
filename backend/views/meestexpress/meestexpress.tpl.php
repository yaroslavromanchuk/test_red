<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>
<?php
//echo $this->view->render('email/template_new.tpl.php');
//echo 'dgsdfgdfg'.EventCustomer::getEventsCustomerCount(8005);

//echo SendMail::getInstance()->getMailList();
 if($this->getCity()){
//echo print_r($this->city);
/*
foreach ($this->city as $city) {
echo $city->DescriptionRU.' ( '.$city->RegionDescriptionRU.' обл., '.$city->DistrictDescriptionRU.' р-н )<br>';
}*/

} ?>

<div class="calc_block_s">
		<select name="s_service" id="s_service" class="s_service">
			<option value="0">из отделения</option>
			<option value="1">с адреса</option>
		</select>
	</div>
<div class="calc_block_s">
		<div>Откуда</div>
		<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
		<input type="text" id="s_city" class="calc_city ui-autocomplete-input" placeholder="Город (выберите из выпадающего списка)" autocomplete="off">
		<input type="hidden" id="s_city_id">
		<div class="clear"></div>
		
		<div id="s_branch_block">
			<select class="s_branch" id="s_branch">
				<option value="">Отделение:</option>
			</select>
			<div class="clear"></div>
		</div>
		<div>
		</br>
		<input type="text" id="cost" value="" placeholder="Сумма">
		<input type="text" id="strach" value="" placeholder="Страховка">
	 <p><input type="button" value="Проверить" onclick="getCalc()"></p>
		<div id="cal"></div>
		</div>
	</div>

<p><select id="warehouses" style="display:none;"></select></p>
<link rel="stylesheet" href="/admin_files/scripts/jquery-ui.css" type="text/css" media="screen">


<script type="text/javascript" src="/admin_files/scripts/jquery-ui.js"></script>
<script type="text/javascript">
	 $(document).ready(function () {
	$('#s_service').change(function(){
			if (this.value == 0){
				$('#s_branch_block').show();
				//checkAPT();
			} 
			if (this.value == 1){
				jQuery('#s_branch_block').hide();
				//loadFormats(formats_all);
				//checkAPT();
			} 
		})
	 

jQuery( '#s_city' ).autocomplete({
			source: '/admin/getmistcity/?what=city',
			minLength: 3,
			maxHeight: 200,
			deferRequestBy: 300,
			search: function( event, ui ) {
				jQuery('#s_city_id').val('');
			},
			select: function (event, ui) {
			//console.log(ui.item);
				if (ui.item == null) {
					jQuery('#s_city_id').val('');
				} else {
					jQuery('#s_city_id').val(ui.item.id);
					//if (jQuery('#s_service').val() == '1'){
						//calcTerm();
					//}
					//jQuery( '#r_service' ).focus();
					if($('#s_service').val() == 0) getBranch('s');
					
				}
			}
		});
		
	});
	
	
//получение отделений
	function getBranch(name) {
	
	var uid = $( '#' + name + '_city_id').val();
	
		$.get('/admin/getmistcity/?what=branch&term=' + uid,
		
			function (data) { $('#' + name + '_branch').html(data);});
			
				}
				
				function getCalc() {
					$('#cal').html('');
	var uid_type = 1;
	var uid_c = '5CB61671-749B-11DF-B112-00215AEE3EBE';
	var uid_b = '';
	var strach = $('#strach').val();
	var cost = $('#cost').val();
	if(strach != '' && cost != '' ){
		$.get('/admin/getmistcity/?what=calc&uid_type='+uid_type+'&uid_b='+uid_b+'&uid_c='+uid_c+'&cost='+cost+'&strach='+strach,
		
			function (data) {
			$('#cal').html(data);
			//$('#k_b_g').fadeOut(300);
			});
			}else{
			$('#cal').html('Введите сумму и страховку!');
			}
			return false;
				}


</script>

