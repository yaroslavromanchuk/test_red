<center>
<div id="content">
	<div id="head">
<a href="?subscribe" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #888888;
    border-radius: 5px;
    font-size: 18px;
">Подписаны<?php if($this->count_all) echo " (".$this->count_all.") ";?></a>
<a href="?notified" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #249c1b;
    border-radius: 5px;
    font-size: 18px;
">Уведомлены<?php if($this->count_ok) echo " (".$this->count_ok.") ";?></a>
<a href="?go-notified" style="
    margin: 0 10px 0 10px;
    padding: 3px;
    border: 3px solid #f70000;
    border-radius: 5px;
    font-size: 18px;
" >Ждут уведомления<?php if($this->count_zd) echo " (".$this->count_zd.") ";?></a>
	</div>
	</div>
	</center>
<script type="text/javascript">
    $(document).ready(function () {
		 $('img.img_pre').hover(function () {
		$(this).parent().find('div.simple_overlay').show();
        }, function () {
		$(this).parent().find('div.simple_overlay').hide();
        });
    });
</script>
	<div class="input-group mb-3" style="display:none;">
  <div class="input-group-prepend">
    <label class="input-group-text" for="inputGroupSelect01">Категория</label>
  </div>
  <select class="selectpicker show-tick form-control input" data-live-search="true" id="inputGroupSelect01" name="category">
    <option selected>Выберите категорию...</option>
	<?php foreach (wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>0),array('name'=>'ASC')) as $cat) { ?>
    <option value="<?=$cat->id?>"><?=$cat->getRoutez()?></option>
	<?php } ?>
  </select>
</div>
	<div class="row">

		<div class="col-xs-12">
			<table class="table table-striped table-hover">
				<caption>Уведомления</caption>
				<thead>
					<tr>
						<th>Фото</th>
						<th style="width: 143px;">Артикул</th>
						<th>Подписан</th> 
						<th>Уведомлен</th>
						<th>Email</th>
						<th>Имя</th>
						<th>Отправить по новому артикулу</th>
					</tr>
				</thead>
				<tbody>

<?php
$row = 'row2';
$i=0;
	foreach ($this->notice as $notice) {
	 $row = ($row == 'row2') ? 'row1' : 'row2';
	if($i == 100) break;
	//$notice_art = wsActiveRecord::useStatic('Shoparticles')->findFirst(array('id' => $notice->id_article));
	$notice_art = new Shoparticles((int)$notice->id_article);
	$s_c = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("`code` LIKE  '".$notice->code."'"));
	//echo $s_c->getSize()->getSize();
?>
					<tr class="<?=$row?>" >
					<td>
                <img class="img_pre" rel="#imgiyem<?= $notice_art->getId(); ?>"
                     src="<?php echo $notice_art->getImagePath('small_basket'); ?>"
                     alt="<?php echo htmlspecialchars($notice_art->getTitle()); ?>"/>

                <div class="simple_overlay" id="imgiyem<?=$notice_art->getId();?>" style="position: fixed;top: 20%;left: 25%">
                    <img src="<?=$notice_art->getImagePath('detail')?>" alt="<?=htmlspecialchars($notice_art->getTitle())?>"/>

                </div>
						</td>
						<td scope="row">
						<a target="_blank" href="<?=$notice_art->getPath()?>">
						<?=$notice_art->getCategory()->getRoutez()?></a><br>
					<?=$s_c->getSize()->getSize()?>/<?=$s_c->getColor()->getName()?></td>
						<td><?=$notice->ctime?></td>
						<td><?=$notice->utime?></td>
						<td><?=$notice->email?></td>
						<td><?=$notice->name?></td>
						<td>
						<form action="/admin/notice-edit/" method="get" class="form-inline">
							<div class="form-group mx-sm-3 mb-2">
			<input type="text" id="<?=$notice->id?>" name="code" value="" class="form-control btn-sm w100"/><input hidden type="text"  name="id"  value="<?=$notice->id?>" />
							</div>
							<button type="submit" id='email_go' class="btn  btn-default btn-sm">Отправить</button>
						</form>
						</td>
					</tr> 
<?php
	$i++;}
	
?>
				</tbody>
			</table>
		</div>
	</div>
