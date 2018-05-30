<?php
	$getImage = $this->getCurMenu()->getImage();
	$getTitle = $this->getCurMenu()->getTitle();
	$getPageBody = $this->getCurMenu()->getPageBody();
	$getBrands = $this->getBrands();
	$path = $this->path;
?>
	<img src="<?php echo SITE_URL;?><?php echo $getImage;?>" alt="" class="page-img"/>
	<h1><?php echo $getTitle;?></h1>
	<hr/>
<?php
	echo $getPageBody;
?>
	<p>
		<img height="24" width="24" alt="new" src="/img/icons/edit-small.png">
		<a href="/admin/brand/edit/">Добавить бренд</a>
	</p>

	<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th  colspan="2">Действия</th>
			<th class="c-projecttitle">Изображение</th>
			<th class="c-projecttitle">Название</th>
			<th class="c-projecttitle">Количество товаров</th>
			<th class="c-projecttitle">На главной</th>
		</tr>
<?php
	$row = 'row1';

	$data_cond = array();
	foreach($getBrands as $sub ) {
		$sql = '
			SELECT
				count(ART.id) as cnt
			FROM
				ws_articles ART
			WHERE
				ART.brand_id = '.$getId.'
			GROUP BY
				brand_id
		';
		$count = wsActiveRecord::findByQueryArray($sql);
		$count = $count[0]->cnt;
		$getId = $sub->getId();
		$getImage = $sub->getImage();
		$getName = $sub->getName();
		$getTop = $sub->getTop();

		$row = ($row == 'row2') ? 'row1' : 'row2';
?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon">
				<a href="<?php echo $path;?>brand/edit/id/<?php echo $getId;?>/">
					<img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" />
				</a>
			</td>
			<td class="kolomicon">
<?php
				if (!$count) {
?>
					<a href="<?php echo $path;?>brand/delete/id/<?php echo $getId;?>/">
						<img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" />
					</a>
<?php
				}
?>
        </td>
			<td class="c-projecttitle">
				<img style="max-width: 30px; max-height: 30px;" src="<?php echo $getImage;?>" alt="<?php echo $getName;?>">
			</td>
			<td class="c-projecttitle">
				<?php echo $getName;?>
			</td>
			<td class="c-projecttitle">
				<a href="/admin/shop-articles/?search=&brand=<?php echo $getName;?>&from=&to=&id=&sort=dateminus">
					<?php echo $count;?>
				</a>
			</td>
			<td class="c-projecttitle">
				<?php echo $getTop?'Да':'Нет'?>
			</td>
		</tr>
<?php
		}
?>
    </table>