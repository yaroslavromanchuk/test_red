<img src="<?=SITE_URL.$this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php //echo $this->getCurMenu()->getPageBody(); ?>

<form action="" method="POST" id="myform" align="center">
<input type="text" name="ttn" id="ttn" value="" autofocus maxlength="14" class="input">
<input type="submit" style="display:none;" name="go" value="<?=$this->trans->get('Найти'); ?>" class="input">
</form>
<p id="text" style="text-align:center;"></p>
<script>
var k = 0;
	  $('#ttn').keypress(function(e){
	 if(e.key == 'Enter')  $('#myform').submit();
      e = e || event;
      if (e.ctrlKey || e.altKey || e.metaKey) return;
	  if(e.which > 47 && e.which < 58 ){
	 // console.log(e.which);
	  return true;
	  }else{
	   return false;
	  }
    });
$(document).ready(function(){
});
$('#myform').submit(function(){
var z = $('#ttn').val();
console.log(z+' - '+z.length);
if( z != '' && z.length == 14){ 
	  var url = '/admin/addttnorder/';
		var new_data = '&ttn='+ z;
		$.ajax({
		beforeSend: function( data ) {
		//$('#pageslist').html('<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				if(res == 'error'){
				$('#text').html('<span style="font-size: 16px;color: red;font-weight: 600;">ERROR<span>');
				//fopen();//.delay(50).FormClose();
				//setTimeout(function(){$('#text').html('');}, 1000);

				}else{
				$('#text').html('<span style="font-size: 16px;color: #1a961f;font-weight: 600;">OK<span>');
				setTimeout(function(){$('#text').html(' ');}, 300);
				}
                }
            });
	  }
$("#ttn").val('');
$("#ttn").focus();
	  return false;
	});

</script>