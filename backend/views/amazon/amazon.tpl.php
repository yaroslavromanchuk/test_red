<img src="<?=SITE_URL.$this->getCurMenu()->getImage(); ?>" alt="" width="32" class="page-img" />
<script src="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?=$this->files?>scripts/ui/jquery-ui-1.9.1.custom.css" type="text/css" media="screen"/>
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<br>
<p style="float:  left;"><span id="c_b"></span><a href="" onclick="view_basket();return false;" >Корзина</a></p>
<p style="text-align:right;"><a href="" id="elem">Очитсть таблицу</a></p> 
<?php
//echo '<pre>';
//echo print_r($_SESSION['am_basket']);
//echo '</pre>';
 if ($this->errors) { ?>
    <div id="errormessage" style="margin: auto;">
	<img src="<?php echo SITE_URL; ?>/img/icons/error.png" alt="" width="32" height="32" class="page-img"/>
        <span style="font-size: 14px;font-weight: bold;">Найдены ошибки:</span><br>
        <ul>
            <?php foreach ($this->errors as $error) echo "<li>{$error}</li>"; ?>
        </ul>
    </div>
<?php
}
if($this->add_count) echo 'Загружено : '.$this->add_count;
 if($this->article->count() > 0){ 
 ?>

 <table id="products1" cellpadding="4" cellspacing="0" class="table  table-hover">
    <tr>
        <th>Група</th>
        <th>Ссылка</th>
        <th>Название</th>
		<th>Категория</th>
        <th>Головна<br>категория</th>
        <th>Сосотояние</th>
		<th>Наличие</th> 
		<th>Опт. цена</th>
		<th>Цена RED</th>
		<th>Смотреть</th>
    </tr>
	
	<?php foreach ($this->article as $a){ ?>
<tr <?php if($_SESSION['am_basket']) { if(array_key_exists($a->asin, $_SESSION['am_basket'])) echo 'style="background:#4CAF50;" '; }?> id="tr-<?=$a->asin?>" >
	<td><?=$a->group?></td>
	<td><a href="<?=$a->link?>" target="_blank">link</a></td>
	<td><?=$a->name?></td>
	<td><?=$a->category?></td>
	<td><?=$a->sub_category?></td>
	<td><?=$a->condition?></td>
	<td><?=$a->quantity?></td>
	<td><?=$a->wholesale_price?></td>
	<td><?=$a->price?></td>
	<td><input type="button" class="btn btn-small btn-default" data-flag="1" data-link="<?=$a->link?>"   data-price="<?=$a->price?>" id="<?=$a->asin?>" data-cnt="<?=$a->quantity?>"  onclick="view_img(this);"  value="Смотреть"></td>
	</tr>
	<?php } ?>
	</table>
	    <?php
    $limitLeft = 2;
    $limitRight = 2;
    $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    } else {
        $ur = $_SERVER['REQUEST_URI'];
        $get = '';
    }
    $pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
    $paginator = '&nbsp;&nbsp;';
    if ($this->page > 1) {
        $paginator .= '<a href="' . $pager . '1' . $get . '"><<</a>&nbsp;<a href="' . $pager . ($this->page - 1) . $get . '"><</a>&nbsp;';
    } else {
        $paginator .= '<span class="grey"><</span>&nbsp;<span class="grey"><<</span>&nbsp;';
    }
    $start = 1;
    $end = $this->totalPages;
    if ($this->page > $limitLeft) {
        $paginator .= '...&nbsp;';
        $start = $this->page - $limitLeft;
    }
    if (($this->page + $limitRight) < $this->totalPages) {
        $end = $this->page + $limitRight;
    }
    //for ($i = 1; $i <= $this->totalPages; $i++){
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $paginator .= '<span>' . $i . '</span>';
        } else {
            $paginator .= '<span><a href="' . $pager . $i . $get . '">' . $i . '</a></span>';
        }
        if ($i <= $end - 1) {
            $paginator .= '<span class="delimiter">&nbsp;|&nbsp;</span>';
        }

    }
    if ($this->page == $this->totalPages) {
        $paginator .= '&nbsp;<span class="grey">>></span>&nbsp;<span class="grey">></span>';
    } else {
        $paginator .= '&nbsp;<a href="' . $pager . ($this->page + 1) . $get . '">></a>&nbsp;<a href="' . $pager . $this->totalPages . $get . '">>></a>';
    }
    echo $paginator;

    ?><br/>
    Всего страниц: <?=$this->totalPages?>, записей: <?=$this->count?> 
	<?php }else{ ?>
	<div align="center" style="margin-top: 5px;" id="form_pa">
<form method="POST" action="" enctype="multipart/form-data" style="width: 400px;padding: 5px;background: #9E9E9E;border-radius: 5px;">
<input type="file" name="exel" class="input" >
<input type="text" name="limit" value="1000" style="width: 50px;display:none;" class="input" >
<input type="submit" name="save" id="save" value="Открыть" class="button">
</form>
</div>
<?php } ?>
 <script>
 
  elem.onclick = function(){// очистка корзины
  $.ajax({
                url:  '/admin/amazon/',
                type: 'POST',
                dataType: 'json',
                data: '&method=dell_base',
                success: function (res) {
				if(res) {
				fopen('',res);
				//setTimeout(FormClose,1500);  
				}
				//location.href=location.href;
				location.reload();
				}
				});
 }
function  view_basket() {// просмотр содержимого корзины
  var url = '/admin/amazon/';
		   var new_data = '&method=view_basket';
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				if(res) {
				fopen('Корзина',res);
				}
				}
				});
			return false;	
  };
 function order_from_basket(q){// оформление заказа
 //  alert(q.name);
           $.ajax({
                url: '/admin/amazon/',
                type: 'POST',
                dataType: 'json',
                data: '&method=add_order',
                success: function (res) {
				if(res) {
				fopen('Корзина',res);
				}
				},
				error: function(res){
				console.log(res);
				}
				}).done(count_basket);
   
   };
   
 function add_basket(q){//добавление товара в корзину
 //var img = [];
 var img_sm = $( "#img > .img_sm").attr( "src");
  var img = $( "#img > .img_a").attr( "src");
  var size = $("#description_amazon .size > b").html();
  var color = $("#description_amazon .color > b").html();
  var title = $("#description_amazon .title > b").html();
 /*$( "#img > img").each( function( index, element) {
 img.push($( element).attr( "src"));
   // console.log( "id:", $( element).attr( "src"));
});*/
console.log(img+'-'+img_sm+'-'+size+'-'+color);
  var c = $('#count_art').val();
  var price = $('#price').val();
  var link = $('#link').val();
 var asin = q.name;
  var url = '/admin/amazon/';
		   var new_data = '&method=add_basket&asin='+asin+'&cnt='+c+'&price='+price+'&img='+img+'&link='+link+'&img_sm='+img_sm+'&size='+size+'&color='+color+'&title='+title;
           $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				if(res) {
				fopen('Корзина',res);
				count_basket();
				setTimeout(FormClose,1500);  
				}
				},
				complete: function( res ) {
			$('#tr-'+asin).css('background', '#4CAF50');
			}
				});
 }
 function count_basket(){//подщет количества товаров в корзине
            $.ajax({
                url:'/admin/amazon/',
                type: 'POST',
                dataType: 'json',
                data: '&method=count_basket',
                success: function (res) {$('#c_b').html(res);}
				});
 }
  function dell_basket(q){//удаление товара с корзины
 var asin = q.name;
  var url = '/admin/amazon/';
		   var new_data = '&method=dell_basket&asin='+asin;
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				if(res) {
				view_basket();
				}
				}
				}).done(count_basket);
 }
  		  function view_img(e) {// просмотр товара
		  var id = e.id;
		  var price = $('#'+id).data('price');
		  var cnt = $('#'+id).data('cnt');
		  var flag = $('#'+id).data('flag');
		  var link = $('#'+id).data('link');
            var url = '/admin/amazon/';
		   var new_data = '&method=view&flag='+flag+'&asin='+id+'&cnt='+cnt+'&price='+price+'&link='+link;
            $.ajax({
			beforeSend: function( data ) {
		fopen('Карточка товара','<img  id="loading" src="/img/loader-article.gif">');
			},
                url: url,
                type: 'POST',
                dataType: 'json',
                data: new_data,
                success: function (res) {
				//console.log(res);
				if(res) {
				fopen('Карточка товара',res);
				}
				},
			complete: function( res ) {
			//var tr = e.parentNode.parentNode.id;
			$('#tr-'+id).css('background', '#FFEB3B');
			},
			error: function( z ) {
			console.log(z);
			//alert('Что-то пошло нетак! Заказ не добавлен, внесите изменения и попробуйте снова!');
			}
				
				});//.done(fopen);

        }
$( "#count_art" ).change(function() {
alert('ok');
$( "select option:selected" ).each(function() {

    $('#show_price').html(($('#price').val()*$(this).text()));

    });


});
     $(document).ready(function () {
		count_basket();
     $('.prev').hover(function () {
     $(this).parent().find('div.simple_overlay').show();
     }, function () {
     $(this).parent().find('div.simple_overlay').hide();
     });

		
		});
 
 </script>