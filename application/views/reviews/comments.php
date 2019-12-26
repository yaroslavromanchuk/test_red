<?php $text_trans_reviews = explode(',', $this->trans->get('Введите Имя,Введите сообщение,Введите Email,Оставить отзыв,Ваш отзыв принят,Смотреть ответы,Ответить,Ваш ответ принят,Отправить')); ?>	
<form name="form_reviews" method="post" action="/reviews/" onsubmit="return validate_form();">		
<div id="div-0"  class="row  m-auto">

<div class=" col-xl-8 py-2 m-auto">
<div class="comment-types" id="comment-type">
<div id="mes" style="font-size: 14px;font-family: sans-serif;font-weight: 600;color: red;"></div>
								<div class="comment-type ">
									<input type="radio" name="comment-type"  id="comment-radio-good" value="3">
									<label for="comment-radio-good"></label>
								</div>
								<div class="comment-type">
									<input type="radio" name="comment-type"  id="comment-radio-normal" value="2">
									<label for="comment-radio-normal"></label>
								</div>
								<div class="comment-type">
									<input type="radio" name="comment-type"  id="comment-radio-bad" value="1">
									<label for="comment-radio-bad"></label>
								</div>
							</div>
</div>
<div class="col-sm-12 col-lg-1 col-xl-1 p-1 m-auto <?php if ($this->ws->getCustomer()->getIsLoggedIn()) echo 'd-none';?>">
<input type="hidden" name="pid" id="pid" value="0">
			<input type="hidden"  name="buf" id="buf" value="0">
			<input type="hidden" name="url_id" value="<?php echo $_SERVER['SERVER_NAME'];?>"/>
			<input type="hidden" name="url" value="http://www.red.ua/reviews/"/>
						<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
				<input type="text" id="sender_name" name="sender_name" onclick="this.value;"  onfocus="this.select()" onblur="this.value=!this.value?'* <?=$this->trans->get('Введите Имя')?>':this.value;" value="* <?=$text_trans_reviews[0]?>" class="in_name" style="display:block;"/>
				<input type="text" id="sender_email" name="sender_email" onclick="this.value;" onfocus="this.select()" onblur="this.value=!this.value?'* <?=$text_trans_reviews[2]?>':this.value;" value="* <?=$text_trans_reviews[2]?>" class="in_name" style="display:block;"/>
				<?php }else{?>
				<input type="text" id="sender_name" name="sender_name"   onfocus="this.select()" onblur="this.value=!this.value?'<?php echo $this->ws->getCustomer()->getFirstName(); ?>':this.value;" value="<?php echo $this->ws->getCustomer()->getFirstName(); ?>" class="in_name"/>
				<input type="text" id="sender_email" name="sender_email"  onfocus="this.select()" onblur="this.value=!this.value?'<?php echo $this->ws->getCustomer()->getEmail(); ?>':this.value;" value="<?php echo $this->ws->getCustomer()->getEmail(); ?>" class="in_name" />
				
				<?php } ?>
</div>
<div class="col-sm-12 col-lg-7  <?php if($this->ws->getCustomer()->getIsLoggedIn()){ echo "col-xl-8";}else{ echo "col-xl-8";} ?> p-1 m-auto">
<div id="mes_t" style="font-size: 14px;font-family: sans-serif;font-weight: 600;color: red;"></div>
<textarea name="message" id="message" class="mess_text form-control" maxlength="1000" placeholder="* <?=$text_trans_reviews[1]?>"  ></textarea>
</div>
<div class="col-xl-8 p-1 m-auto text-center">
<input type="submit" name="send_reviews" value="<?=$text_trans_reviews[3]?>" class="btn btn-danger">
</div>
</div>
</form>		
		<script>	
			$(document).ready( function() {
		$('input:radio').change(function() {$('#mes').html('');});//palec
		$("#message").keypress(function (e) { $("#mes_t").html('');});//vvod message
		$(".mess_text").keypress(function (e) { $(".otv").html('');});//vvod message
		});
function validate_form ( )
{
	valid = true;

	var n=$("#sender_name").val();
      if (!isValidName(n))
        {
		$("#sender_name").val('<?=$text_trans_reviews[0]?>');
		$("#sender_name").focus();
                valid = false;
        }


	var r = $("#sender_email").val();
		if(!isValidEmailAddress(r)){
		$("#sender_email").val('<?=$text_trans_reviews[2]?>');
		$("#sender_email").focus();
			 valid = false;
		}
		if($("#sender_email").val() == 'sene@outlook.com'){ valid = false; }
		
		var text = $("#message").val();
		if(text == ''){
		$('#mes_t').html('<?=$text_trans_reviews[1]?>!');
		 $('#message').focus();
		// alert ( "" );
		valid = false;
		}

		if(!$("div.comment-type input:radio:checked").val()) {
		$('#mes').html('<?=$this->trans->get('Пожалуйста, выберите тип отзыва')?>!');
		 $('#mes').focus();
		 valid = false;
		}
		//var t = $(".mess_text").val();
		//if(!isValidText(t)){
			// alert ( "Вы ввели недопустимые символы." );
			// valid = false;
		//}
		
			if(valid == true) {
			$('.comment-type').hide();
			//$('#comment-type').css({'color':'#2a802c'});
			$('#mes').html('<img style="width: 50%;" src="/storage/images/RED_ua/pays/b40fe94d754814d48a8e93815b42f260.jpg" alt="Отзыв принят"></br> <span style="font-size: 18px;font-family: sans-serif;font-weight: 600;color: #2a802c;"><?=$text_trans_reviews[4]?>!</span>');
			}
        return valid;
		
		
}

function validate_form_otv (id)
{
var d = $(id).attr('name');
	valid = true;

	var n=$('form[name="'+d+'"] .sender_name_2').val();
      if (!isValidName(n))
        {
		$('form[name="'+d+'"] .sender_name_2').val('<?=$text_trans_reviews[0]?>!');
		$('form[name="'+d+'"] .sender_name_2').focus();
                //alert ( "Пожалуйста, заполните корректно поле Ваше имя" );
                valid = false;
        }

	var r=$('form[name="'+d+'"] .sender_email_2').val();
		if(!isValidEmailAddress(r)){
		$('form[name="'+d+'"] .sender_email_2').val('<?=$text_trans_reviews[2]?>!');
		$('form[name="'+d+'"] .sender_email_2').focus();
		
			// alert ( "Пожалуйста, заполните корректно поле Email" );
			 valid = false;
		}
		if($(".sender_email").val() == 'sene@outlook.com'){ valid = false; }
		
		
		var t = $('form[name="'+d+'"] .mess_text').val();
		//alert(t);
		if(t == ''){
		$('form[name="'+d+'"] .otv').html('<?=$text_trans_reviews[1]?>!');
		 $('form[name="'+d+'"] .otv').focus();
			 valid = false;
		}
		
			if(valid == true){
			$('.comment-type').hide();
			$('#mes').html('<img style="width: 50%;" src="/storage/images/RED_ua/pays/b40fe94d754814d48a8e93815b42f260.jpg" alt="Отзыв принят"></br> <span style="font-size: 18px;font-family: sans-serif;font-weight: 600;color: #2a802c;"><?=$text_trans_reviews[7]?>!</span>');
			}
        return valid;
		
		
}

 function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
    }
	
	 function isValidName(validname) {
    var pattern1 = new RegExp(/^[а-яіА-ЯІєЄёЁa-zA-Z0-9. -]+$/);
    return pattern1.test(validname);
    }
	 function isValidText(validtext) {
    var pattern1 = new RegExp(/^[а-яіА-ЯІЇїєЄёЁa-zA-Z0-9. -&,!=)(№#:;]+$/);
    return pattern1.test(validtext);
    }
</script>

	<section class="ac-container">
            <div class="row m-auto">
            <?php
            foreach ($this->coments as $coment) {
			$id=$coment["id"];
$subcomments = 'SELECT `id`,`name`, `text`, `date_add` FROM ws_comment_system where parent_id = '.$id.' and public = 1 ORDER BY `date_add` DESC';
$subcomments = wsActiveRecord::useStatic('Reviews')->findByQuery($subcomments);
$res = mysql_query('SELECT count(id) FROM ws_comment_system WHERE parent_id = '.$id.' AND public = 1'); 
$row = mysql_fetch_row($res);
?>
                <div class="col-xl-8 m-auto p-3">
	<div id="comment-<?=$coment["id"]?>"  class=" row bg-white p-2 m-auto my-2" >		
		
	<input id="ac-<?=$coment["id"]?>" name="accordion-1" type="checkbox" class="inp"/>
	<img style="width: 30px;height:30px;" class="mr-2" src="/img/reviews/<?=$coment["flag"];?>.png" />
	<span class="leb"><b><?=$coment["name"]?></b></span>
	<span class="rd_r"><?=$coment["date_add"]?></span>
	
	<div id="comment-<?=$coment["id"]?>-x" class="col-xl-12 px-5"><?=$coment["text"]?></div>

<div class="col-lg-5 col-xl-6 my-2 px-2 text-left">

<span class="btn btn-secondary" onclick="hide_show('div-<?=$coment["id"]?>', this); return false; " ><?=$text_trans_reviews[6]?></span>
</div>
<?php if($row[0] > 0) { ?>
<div class="col-lg-5 col-xl-6 my-2 px-2 text-right">
<label for="ac-<?=$coment["id"]?>"><span class="btn btn-secondary" ><?=$text_trans_reviews[5]." (".$row[0].")"; ?></span>
	</label>	
	</div>
	<?php } ?>

<script>
function hide_show(id, i)
{
//$(i).hide();
	display = document.getElementById(id).style.display;

    if(display=='none'){
       document.getElementById(id).style.display='block';
    }else{
       document.getElementById(id).style.display='none';
    }

 
}
</script>
<article class="ac-large">
<?php

		foreach ($subcomments as $v) {

			?>
			<div id="comment-<?php echo $v["id"]?>" class="ss">
	<div class="c">
		<span class="l"><b><?php echo $v["name"]?></b></span><span class="rd_r_a"><?php echo $v["date_add"]?></span>
		<div><?php echo $v["text"]?></div>
	</div>
</div>
<?php
}
?>
<div class="clear"></div>
</article>
<div id="div-<?php echo $coment["id"]?>"  class=" col-xl-12 m-auto add_f1 bg-white" style="display:none;">
<form name="form_reviews_<?=$coment["id"]?>" method="post" action="/reviews/" onsubmit="return validate_form_otv(this);">
<table style="width: 100%;" class="add_forma">
<tr>
<td>
<input type="hidden" name="pid" id="pid<?=$coment["id"]?>" value="0">
			<input type="hidden"  name="buf" id="buf<?=$coment["id"]?>" value="<?=$coment["id"]?>">
			<input type="hidden" name="url_id" value="<?php echo $_SERVER['SERVER_NAME'];?>"/>
			<input type="hidden" name="url" value="<?php echo $_SERVER['HTTP_REFERER'];?>"/>
						<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
				<input type="text"  name="sender_name" onclick="this.value;"  onfocus="this.select()" onblur="this.value=!this.value?'* <?=$text_trans_reviews[0]?>':this.value;" value="* <?=$text_trans_reviews[0]?>" class="in_name sender_name_2" style="display:block;"/>
				<?php }else{?>
				<input type="text"  name="sender_name"    onfocus="this.select()" onblur="this.value=!this.value?'<?php echo $this->ws->getCustomer()->getFirstName(); ?>':this.value;" value="<?php if($this->ws->getCustomer()->isAdmin()){ echo 'Администрация';}else{ echo $this->ws->getCustomer()->getFirstName();} ?>" class="in_name sender_name_2"/>
				
				<?php } ?> 
</td>
<td rowspan="2" style="width: 100%;">
<div class="otv" style="font-size: 14px;font-family: sans-serif;font-weight: 600;color: red;"></div>
<textarea name="message" class="form-control mess_text"  maxlength="1000" placeholder="* <?=$text_trans_reviews[1]?>" ></textarea>
</td>
</tr>
<tr>
<td>

							<?php
				if (!$this->ws->getCustomer()->getIsLoggedIn()) {
?>
				<input type="text"  name="sender_email" onclick="this.value;" onfocus="this.select()" onblur="this.value=!this.value?'* Введите Email':this.value;" value="* Ваш Email" class="in_name sender_email_2" style="display:block;"/>
				<?php }else{?>
				<input type="text"  name="sender_email"  onfocus="this.select()" onblur="this.value=!this.value?'<?php echo $this->ws->getCustomer()->getEmail(); ?>':this.value;" value="<?php  echo $this->ws->getCustomer()->getEmail(); ?>" class="in_name sender_email_2" />
				<?php }?>
</td>
</tr>
</table>

				
				<input type="submit" name="send_onswer" value="<?=$text_trans_reviews[8]?>" class="btn btn-danger" style="display:block;">
</form>
</div>



<div class="clear">&nbsp;</div>

</div>
<!--<hr style="margin-top: 10px;
    margin-bottom: 10px;"> -->
</div>
            <?php 
			} ?>
</div>

</section>

			<?php 

if ($this->allcount > $this->onpage) {
?>
	<div class="clear"></div>
	<div style="text-align: center;padding:10px;">
	<ul style="font-size: 16px;" class="finder_pages">
		<?php
	if ($this->page > 1) {
?>
		<li class="page-skip"><a href="?page=<?=$this->page-1;?>"><span style="padding:5px;"><< </span></a></li>
<?php
	} ?>
	<?php
	$b = '';
	$st = ceil($this->allcount/20);
	$q = 1;
	$f1 = 0;
	$f2 = 0;
for($i = 1;$i<=$st; $i++) {
if($i == $this->page)  {$b = 'class="selected"';}else{ $b = '';}
if($st > 10){
if($i < $this->page - 4 and $i < 4 ){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i < ($this->page - 3) and $f1 == 0){
$f1 = 1;
echo '<li><span style="padding:5px;">...</span></li>';
}elseif($this->page == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 1) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 2) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 3) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 1) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 2) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 3) == $i){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i > ($this->page + 3) and $f2 == 0 ){
echo '<li class="page-skip"><span style="padding:5px;">...</span></li>';
$f2 = 1;
}else if($i == $st){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i == ($st - 1)){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
else if($i == ($st - 2)){
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}else{
echo '<li class="page-skip"><a href="?page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}
	?>
		<?php
	if ($this->page < ceil($this->allcount / $this->onpage)) {
?>
			<li class="page-skip"><a href="?page=<?=$this->page + 1;?>"><span style="padding:5px;"> >></span></a></li>
<?php
	}
?>
	</ul>
	</div>
    <div class="clear"></div>
<?php
}
?>