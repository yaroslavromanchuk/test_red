<h1><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->getCurMenu()->getPageBody(); ?>
<br/>
<!--<a class="next-step zak" href="add/">Добавить слоган</a>-->
<br/>
<?php if($this->slogans->count()==0) echo '<p>Нету слоганов</p>';?>
<div class="articles-list">
	<?php $cnt = 0;
	foreach ($this->slogans as $slogan)
	{
		if (!($cnt % 3)) echo '<div class="articles-row">';
		$all = $slogan->score->count();
		$s5 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score'=>5,'slogan_id'=>$slogan->getId()));
		$s4 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score'=>4,'slogan_id'=>$slogan->getId()));
		$s3 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score'=>3,'slogan_id'=>$slogan->getId()));
		$s2 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score'=>2,'slogan_id'=>$slogan->getId()));
		$s1 = wsActiveRecord::useStatic('ActionSloganscore')->count(array('score'=>1,'slogan_id'=>$slogan->getId()));
		if($all != 0){
			$sred = round((($s5*5+$s4*4+$s3*3+$s2*2+$s1)/$all),5);
		}
		else $sred =0;
		?>
		<div class="article-item">
			<b>Слоган:</b>
			<div class="slogan_text"><?=$slogan->getSlogan()?>"</div>
			<p class="brand"><?=$slogan->getName()?></p>

			<p class="name"></p>
			<p style="font-size: 11px;">Проголосовало: <?=$all?></p>

			<p style="font-size: 11px;">Средний бал: <?=round($sred,2)?></p>

			<p class="star"><?php if($slogan->isScore()){?>
			<!--	<img class="clicable" src="/img/no_star.png" alt="1" name="<?/*=$slogan->getId()*/?>">
				<img class="clicable" src="/img/no_star.png" alt="2" name="<?/*=$slogan->getId()*/?>">
				<img class="clicable" src="/img/no_star.png" alt="3" name="<?/*=$slogan->getId()*/?>">
				<img class="clicable" src="/img/no_star.png" alt="4" name="<?/*=$slogan->getId()*/?>">
				<img class="clicable" src="/img/no_star.png" alt="5" name="<?/*=$slogan->getId()*/?>">-->
				<?php }else echo 'Cпасибо за голос';?>
			</p>
		</div>
		<?php
                                      $cnt++;
		if (!($cnt % 3)) echo '</div>';
	}
	if ($cnt % 3)
		echo '</div>';
	?>
</div>
<div id="big_slogan"></div>
<script type="text/javascript">
	var showed = false;
	$('document').ready(function(){

		$('.slogan_text').mouseover(function(){
			var div = $(this);
			var startHeight = div.height();
			div.css( "height", "auto" );
			var endHeight = div.height();
			if (endHeight > startHeight && !showed){
				console.log(showed);
				$('#big_slogan').show();
				$('#big_slogan').html(div.html());

				$('#big_slogan').css('top',(div.offset().top + 5)+'px').css('left',(div.offset().left - 170)+'px');
				showed = true;
				console.log(showed);
			}
			//big_slogan
			div.height(startHeight);

		});

		$('#big_slogan').mouseleave(function(){
			$('#big_slogan').html('').hide();
			showed = false;
		});


	});

	$(function() {
		$('p.star img').bind("mouseenter",function() {
			$(this).parent().find('img:lt('+$(this).attr('alt')+')').attr('src','/img/star.png');

		}).bind("mouseleave", function() {
			        $('p.star img').attr('src','/img/no_star.png');
		        });
		$('p.star img').click(function(){
			var p =$(this).parent();
			var url = '/page/clickslogan/';
			$.get(
					url,
					"id=" + $(this).attr('name')+'&score='+$(this).attr('alt'),
					function (result) {
						if (result.type == 'error') {
							return(false);
						}
						else {
							p.html('Cпасибо за голос');
						}
					},
					"json"
			);
		});


	});
</script>
