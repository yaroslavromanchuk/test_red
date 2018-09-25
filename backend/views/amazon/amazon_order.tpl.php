<img src="<?=SITE_URL.$this->getCurMenu()->getImage(); ?>" alt=""  class="page-img" />
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<br>
<a href="/admin/amazonorders">К заказам</a>
<?php
 if ($this->errors) { ?>
    <div id="errormessage" style="margin: auto;">
	<img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt=""  class="page-img"/>
        <span style="font-size: 14px;font-weight: bold;">Найдены ошибки:</span><br>
        <ul>
            <?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
        </ul>
    </div>
<?php
}
 if($this->order->count() >0){ ?>
 <table id="products1" cellpadding="4" cellspacing="0" class="table table-hover">
    <tr>
		<th>Фото</th>
        <th>Название</th>
		<th>Размер</th>
		<th>Цвет</th>
        <th>Количество</th>
        <th>Цена</th>
		 <th>Cумма</th>
		 <th>Действие</th>
    </tr>
	<?php foreach ($this->order as $a){ $row = ($row == 'row2') ? 'row1' : 'row2';	?>
<tr  class="<?php echo $row;?>" id="<?=$a->id?>">
	<td><img src="<?=$a->img_sm?>" class="prev"  rel="#m<?=$a->id?>">
	<div class="simple_overlay" id="m<?=$a->id?>" style="position: fixed;top: 10%; margin-left: 10%;"><img src="<?=$a->img?>" style="max-width:500px;"/></div></td>
	<td><?=$a->title?></td>
	<td><?=$a->size?></td>
	<td><?=$a->color?></td>
	<td name="count">
	<?php if($a->count > 1){ ?><select name="count_article" id="sel_<?=$a->id?>" class="form-control  input count_articles">
	<?php for($i=1;$i<=$a->count;$i++){ ?>
	<option value="<?=$i?>" <?php if($a->count == $i) echo 'selected'; ?>><?=$i?></option>
	<?php }?>
	</select>
	<?php }else{ echo $a->count; } ?></td>
	<td name="price_<?=$a->id?>"><?=$a->price?></td>
	<td name="summ_<?=$a->id?>"><?=$a->price*$a->count?></td>
	<td>
	<img src="/img/icons/cantremove-small.png" style="cursor: pointer;"  data-placement="right"  data-tooltip="tooltip"  title="Удалить" title="Удалить товар" name="<?=$a->asin?>" onclick="dell_article(<?=$a->id?>);">
	</td>
</tr>
	<?php } ?>
	</table>
	<?php }?>
 <script>
  function view_img(e) {// просмотр товара
		  var id = e.id;
		  var price = $('#'+id).data('price');
		  var cnt = $('#'+id).data('cnt');
		  var flag = $('#'+id).data('flag');
            var url = '/admin/amazon/';
		   var new_data = '&method=view&flag='+flag+'&asin='+id+'&cnt='+cnt+'&price='+price;
            $.ajax({
			beforeSend: function( data ) {
		$('#popup').html('<img  id="loading" src="/img/loader-article.gif">');
		fopen();
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				if(res) {
				$('#popup').html(res);
				}
				}
				}).done(fopen);

        }
		
		 function dell_article(e){
		  var url = '/admin/amazonorders/';
		   var new_data = '&method=dell&id='+e;
			$.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				$('#'+res).hide();
				},
				error: function(res){
				console.log(res);
				}
				});
 }
 	$('.count_articles').change(function () {
	var url = '/admin/amazonorders/';
		   var new_data = '&method=edit&id='+this.parentNode.parentNode.id+'&count='+this.value;
		   $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				console.log(res);
				//console.log(this.parentNode("td[name=count]").html());
				//$('#'+e).hide();
				}
				});
	});
     $(document).ready(function () {
 $(".prev").hover(function () {
     $(this).parent().find("div.simple_overlay").show();
     }, function () {
     $(this).parent().find("div.simple_overlay").hide();
     });
		});
 </script>