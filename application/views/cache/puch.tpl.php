<?php
if(isset($_COOKIE['s']) and $_COOKIE['s'] !=''){
	$date = array(); 
	$d = date('Y-m-d', strtotime(Shoparticles::decode($_COOKIE['s'], 'coderedua')));
	$date[] = '  data_new >= "' .$d. '" and `active` =  "y" and `stock` not like "0" and status = 3 and ws_articles.category_id != 16 ';
	$find_count = wsActiveRecord::useStatic('Shoparticles')->count($date);
	//var_dump($find_count);
	}
	if($find_count > 0){
	?>
<div class="puch p_c puch_mobil" id="puch_ok" style="display:none;">
	<p style="    font-size: 14px;
    color: black;
    padding: 10px;"><?=$this->trans->get('С Вашего последнего посещения появилось');?>
	</br> <span style="font-weight: bold;"><?php echo $find_count; ?></span> новинок</p>
	<!--<img src="https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid=<?php echo $_COOKIE['PHPSESSID']; ?>&t=event&ec=push_view&ea=view&el=<?php echo $_COOKIE['PHPSESSID'];?>&cs=push_view&cm=view&cn=Push" width="1" height="1" alt="" />-->
	<div>
	<input type="button" class="btn btn-default" onclick="PuchGo();" value="<?=$this->trans->get('СМОТРЕТЬ ПОДБОРКУ');?>">
	<!-- puch_button_go <input type="button" class="puch_button_close" onclick="PuchClose();" value="Закрыть">--></div>
	<div >
	<?php if($this->ws->getCustomer()->getIsLoggedIn()){ echo '<p style="font-size: 10px;
    font-weight: 100;"><input type="checkbox" style="cursor: pointer;
    height: 10px;
    width: 10px;" name="puch_close" id="ch_cl" value=""> '.$this->trans->get('Больше не показывать').'!</p>'; }else{
	echo '<p><input hidden type="checkbox"  id="ch_cl"></p>';
	}?>
	</div>
	<a class="close" style="background-image: url(http://www.red.ua/img/close.png);    background-size: cover;
    position: absolute; right: 0px;
    top: 0px;
    cursor: pointer;
    height: 25px;
    width: 25px;" onclick="PuchClose();" ></a>
	</div>
	<script>
	$(function(){
	$('#puch_ok').delay(3000).fadeIn(500);
	});
	function PuchGo(){
	Clos('puch_close', 1);
	//$("#puch_ok").append('<img src="https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid=<?php echo $_COOKIE['PHPSESSID']; ?>&t=event&ec=push_open&ea=open&el=<?php echo $_COOKIE['PHPSESSID'];?>&cs=push_open&cm=open&cn=Push" width="1" height="1" alt="" />');
	document.location.href='/category/id/1/';
	}

	function PuchClose(){
	
	$("#puch_ok").fadeOut(500);
	//$("#puch_ok").append('<img src="https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1&cid=<?php echo $_COOKIE['PHPSESSID']; ?>&t=event&ec=push_close&ea=close&el=<?php echo $_COOKIE['PHPSESSID'];?>&cs=push_close&cm=close&cn=Push" width="1" height="1" alt="" />'); 
	if($("#ch_cl").prop("checked")){
	 Clos('puch_close', 1);
	var new_data = '&method=add_flag';
		$.ajax({
                url: '/page/flagCloseCustomer/',
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
                }
            });
	}else{
	 Clos('puch_close', 1);
	}
	}
	 function Clos(f, g) {
	
        var e = new Date(new Date().getTime() + 1000 * 3600 * 12);
        var domain = window.location.hostname;
        document.cookie = f + "=" + g + "; domain="+domain+"; path=/; expires=" + e.toUTCString();
    }
	</script>
	<?php } ?>