<?php  header('Access-Control-Allow-Origin: *'); 
header('X-XSS-Protection: 0'); ?>
<style>
.modal-dialog{
width: 730px;
}

</style>
<center>
<div id="content">
	<div id="head">
	<input name="all" type="button" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #888888;
    border-radius: 5px;
    font-size: 18px;
" class="buttonps11" id="all" onclick="All('all')" value="Общая">
<input name="shop" type="button" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #249c1b;
    border-radius: 5px;
    font-size: 18px;
" class="buttonps11" id="shop" onclick="All('shop')" value="Магазины">
	<input name="men" type="button" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #888888;
    border-radius: 5px;
    font-size: 18px;
" class="buttonps11" id="men" onclick="All('men')" value="Мужская">
<input name="women" type="button" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #249c1b;
    border-radius: 5px;
    font-size: 18px;
" class="buttonps11" id="women" onclick="All('women')" value="Женская">
	<input name="baby" type="button" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #888888;
    border-radius: 5px;
    font-size: 18px;
" class="buttonps11" id="baby" onclick="All('baby')" value="Детская">
	</div>
	<hr/>
	</div>
	</center>
	
<div id="viewe"></div>
	<div class="row" id="view"></div>

<script type="text/javascript">
 function All(x) {
   var url = '/admin/subscribersemail/';
            var data = 'parametr='+x;
            sendPost(url, data);
}

 function Dell(x) {
            var url = '/admin/subscribersemail/';
            var data = 'preview=dell&id='+x;
            sendSave(url, data);
        }

 function Preview(x) {
            var url = '/admin/subscribersemail/';
            var data = 'preview=view&id='+x;
            sendSave(url, data);
        }
	function sendPost(url, data) {
			surl = url+"?"+data;
		console.log(surl);
		//console.log(data);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function (data) {
				//console.log(data);
				//alert(data.send);
				if(data.send){
				$('#view').html(data.result);
				}else{
				alert("error");
				}	
                }
            });

        }	
		
		
				function sendSave(url, data) {
			surl = url+"?"+data;
		console.log(surl);
		console.log(data);
            $.ajax({
                url: surl,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function (data) {
			//	console.log(data);
				if(data.send == 'dell'){
				$('#view').html(data.result);
				}else{ 
				//$('#popup').html(data.result);
				fopen(data.subject, data.result);
				}	
                },
				error: function(data){
				console.log(data);
				}
            });

        }
/*		
function fopen(){
$('#popup').css({"left":"10%", "position":"absolute", "top":"-70px", "width":"700px"})
$('#popup').fadeIn();
$('#popup').append('<a id="popup_close" onclick="FormClose()"></a>');
$('body').append('<div id="fade" onclick="FormClose()"></div>');
$('#fade').css({'filter':'alpha(opacity=40)'}).fadeIn();
return false;
}
function FormClose(){
$('#popup').fadeOut();
$('#fade').fadeOut();
$('#fade').remove();
$('#popup_close').remove();
}		*/
		
   /*$(document).ready(function () {
        //var count_mail = $('#all_subject').val();
        //var send_mail = 0;
        //var count = 10;
        //var mails =''; 


		
        $('#preview11').click(function () {
            var url = '/admin/subscribersemail/';
            var data = $('#mail_form').serialize();
           // $('.mailing_start').show();
            sendMailTest(url, data, 1);
			//$('#show_emails').show();
			//$('#send_emails').show();
			alert('Сообщение отправлено на тестовый email.');

        });
		

        $('#send_all').click(function () {
            var url = '/admin/menmailing/';
            var data = $('#mail_form').serialize();

            $(this).attr('disabled', 'true');
            $('.mailing_start').show();
            sendMail(url, data, 0);
			$('#show_emails').show();
            $('#send_emails').show();

            alert('Рассылка стартовала. Дождитесь окончания!');
        });



    });*/
</script>