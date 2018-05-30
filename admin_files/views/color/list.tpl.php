<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" class="page-img"/>
<h1><?php echo $this->getCurMenu()->getTitle();?></h1>
<hr/>
<?php echo $this->getCurMenu()->getPageBody();?>
<p><img height="24" width="24" alt="new" src="/img/icons/edit-small.png"><a href="/admin/color/">Добавить цвет</a></p>
<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th  colspan="2">Действия</th>
			<th class="c-projecttitle">Цвет</th>
			<th class="c-projecttitle">Название</th>

		</tr>
	<?php
		$row = 'row1';
		foreach($this->getColors() as $sub )
		{
			$getId = $sub->getId();
			$getName = $sub->getName();
			$getColor = $sub->getColor();
			$sql = '
				SELECT
					count(ART.id) as cnt
				FROM
					ws_articles_sizes ART
				WHERE
					ART.id_color = '.$getId.'
			';
			$count = wsActiveRecord::findByQueryArray($sql);
			$count = $count[0]->cnt;

			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon">
				<a href="<?php echo $this->path;?>color/id/<?php echo $getId;?>/">
					<img src="<?php echo SITE_URL;?>/img/icons/edit-small.png" alt="Редактирование" />
				</a>
			</td>
			<td class="kolomicon">
<?php
				if(!$count) {
?>
                    <a href="<?php echo $this->path;?>color/del/<?php echo $getId;?>/" onclick="return confirm('Вы действиельно хотите удалить цвет?')">
						<img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить" width="24" height="24" />
					</a>
<?php
				}
				else {
					echo $count.' тов.';
				}
?>

            </td>
			<td class="c-projecttitle">
                <div style="width: 30px; height: 30px; background: <?php echo $getColor?>">
				</div>
            </td>
			<td class="c-projecttitle"><?php echo $getName;?></td>
		</tr>
	<?php
		}
	?>
    </table>