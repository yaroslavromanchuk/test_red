<link rel="stylesheet" href="/css/norma_slider/jslider.css" type="text/css">

<script type="text/javascript" src="/js/norma_slider/jshashtable-2.1_src.js"></script>
	<script src="/js/norma_slider/jquery.numberformatter-1.2.3.js"></script>
	<script src="/js/norma_slider/tmpl.js"></script>
	<script src="/js/norma_slider/jquery.dependClass-0.1.js"></script>
	<script src="/js/norma_slider/draggable-0.1.js"></script>
	<script src="/js/norma_slider/jquery.slider.js"></script>
	
	  <div class="layout">
    <div class="layout-slider" style="width: 100%"><p style="pading: 0 0 0 10;">
      от <span style="display: inline-block; width: 900px; padding: 0px 10px 0 5px;"><input id="Slider" type="slider" name="price" value="0;999" /></span>  до</br>
	  </br><button style="cursor:pointer;"  onclick="return request();"><?=$this->trans->get('Отобразить')?></button></p>
    </div>
  </div>
<script>
$("#Slider").slider({ from: 1, to: 999, step: 1, smooth: true, round: 0, dimension: "&nbsp;дней", skin: "plastic" });
function request(){

var arr = $("#Slider").val().split(';',2);
console.log(arr);
$.ajax({
beforeSend: function( data ) {
		$('#result').html('<img  id="loading" src="/img/loader-article.gif">');
			},
			//async: false,
			url: '/admin/normaP/',
			type: 'POST',
			dataType: 'json',
			data : '&metod=slider&x='+arr[0]+'&y='+arr[1],
			//cache: false,
				success:function(data){
				$('#result').html(data.result);
				},
				error: function(html){
				console.log(html.responseText);
				}
			});
return false;
}
</script>
  