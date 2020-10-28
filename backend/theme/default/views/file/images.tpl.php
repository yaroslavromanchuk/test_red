<img src="<?=$this->getCurMenu()->getImage()?>" alt="" width="32" class="page-img" height="32" />
<h1><?=$this->getCurMenu()->getTitle()?> </h1>
<?=$this->getCurMenu()->getPageBody()?>

	<p>
		<img src="/img/icons/upload-small.png" alt="Pagina toevoegen" width="24" height="24" />
		<a href="<?=$this->path?>images/upload/">Загрузить изображение</a>
		<!--<img src="/img/icons/upload-small.png" alt="Pagina toevoegen" width="24" height="24" />
		<a href="<?=$this->path?>images/upload-gallery/">Загрузить изображения для галереи</a>-->
	</p>
<?php if($this->imades){ ?>
	<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2">Действие</th>
			<th>URL изображения</th>
			<th>Страница</th>
		</tr>
	<?php 
		$row = 'row1';
		foreach($this->getImages() as $image )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $image->getOpenUrl();?>" target="_blank"><img src="<?php echo SITE_URL;?>/img/icons/view-small.png" alt="Просмотр изображения" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>images/delete/id/<?php echo $image->getId();?>/" onclick="return confirm('Вы уверены, что хотите удалить изображение?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Удалить изображение" width="24" height="24" /></a></td>
			<td class="kolomtitle"><?php echo $image->getOpenUrl();?></td>
			<td><?php echo ($image->getCategoryId() ? $image->getCategory()->getName() : '');?></td>			
		</tr>
	<?php
		}
	?>
    </table>
        <?php  } ?>
        <div class="panel panel-primary">
           <div class="panel-heading"><h3 class="panel-title">Фото товаров</h3></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                         <form method="get" class="form-horizontal" name="form_id_tovar">
                       <label class="control-label" for="id">Просмотр фото товара</label>
                    <div class="input-group">
  <span class="input-group-addon">ID</span>
  <input type="text" class="form-control" class="form-control" name="id" id="id" value="<?=$_GET['id']?$_GET['id']:''?>">
  <span class="input-group-btn">
      <button class="btn btn-default" type="submit">Go!</button>
  </span>
</div>
                </form>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <form method="get" class="form-horizontal" name="form_filter_tovar">
                       <label class="control-label" for="date">Подбор товара</label>
                    <div class="input-group">
  <span class="input-group-addon">ID</span>
  <input  class="form-control" type="date" class="form-control" id="date" name="date" value="<?=$_GET['date']?$_GET['date']:''?>">
  <span class="input-group-btn">
      <button class="btn btn-default" type="submit">Go!</button>
  </span>
</div>
                </form>
                    </div>
                </div>
               
                 
            </div>
        </div>
     <?php if($this->article){ ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><h3 class="panel-title"><?=$this->article->id?></h3></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <img src="<?=$this->article->getImagePath('card_product')?>" style="max-width: 600px;" alt="<?=htmlspecialchars($this->article->getTitle())?>">
                    </div>
                    <?php
                    
                    if($this->article->getImages()->count() > 0){
     foreach($this->article->getImages() as $image){
            if($image->image){  ?>
            <div class="col-xs-12 col-md-3 text-center">
                <img alt="<?=$image->title?>" class="catalog_img" title="<?=$image->title?>" src="<?=$image->getImagePath('detail')?>">
            </div><?php } }
                    } ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php if($this->articles and $this->all_count > 0){ ?>
        <div class="panel panel-primary">
             <div class="panel-heading"><h3 class="panel-title">Форма удаления фото</h3></div>
             <div class="panel-body">
                
                 
                 <div class="row">
                     <div class="col-xs-12">
                          <div class="alert alert-success d-none" role="alert">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only1">Удалено: </span><span id="ok">0</span>
</div>
<div class="alert alert-danger d-none"  role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	<span class="sr-only1">Ошибки удаления: </span><span id="all_p_er"></span>
</div>
                     </div>
                     <form id="go_dell_foto">
                     <div class="col-xs-12">
                         <input id="all_count" name="all_count" type="text" style="display:none" value="<?=$this->all_count?>">
                         <input id="date" type="text" name="date" style="display:none" value="<?=$this->date?>">
                         <input type="text" name="method" style="display:none" value="dell_img">
                     </div>
                         <div class="col-xs-12">
                              <button type="submit"  name="send_full" id="send_all" class="btn btn-outline-danger">Запустить</button>
                         </div>
                      </form>
                 </div>
                
             </div>
        </div>
        
        <div class="panel panel-primary">
            <div class="panel-heading"><h3 class="panel-title">Товары (<?=$this->all_count?>)</h3></div>
            <div class="panel-body">
                <?php
              //  $folder = $_SERVER['DOCUMENT_ROOT'].'/files/org/';
                    $filename_dest = pathinfo('3df8afb0c645d3573b48634d953fdde1.jpg');
                 //    l($filename_dest);
       // echo unlink($folder.$filename_dest['filename'].'.'.strtolower($filename_dest['extension']));
                foreach($this->articles as $a){ ?>
                <div class="row">
                    <p><?=$a->id?></p>
                    <div class="col-xs-12 text-center">
                        <img src="<?=$a->getImagePath()?>" style="max-width: 600px;" alt="<?=htmlspecialchars($a->getTitle())?>">
                    </div>
                    <?php if($a->getImages()->count() > 0){
     foreach($a->getImages() as $image){
            if($image->image){  ?>
            <div class="col-xs-4 col-md-2 text-center">
                <img alt="<?=$image->title?>" class="catalog_img" title="<?=$image->title?>" style="max-width: 100%;" src="<?=$image->getImagePath()?>">
            </div>
                <?php } }
                    } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
<script>
 $(function(){
        'use strict';
        //f1f411c36255c15e38e98eca66f6a469.jpg
    var l1 = 0;
        var l2 = 50;
        
        var ok = 0;
	var error = 0;
       // var all_count = $("#all_count").val();

 $('#go_dell_foto').submit(function(e){
        $('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><br><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>'}).appendTo('body');
            send($('#go_dell_foto').serialize(), l1, l2);
          //  $('.alert-success').toggleClass('d-none');
          //  $('.alert-danger').toggleClass('d-none');
		
       return false;
    });
    
    function send(data, l1, l2){
        var all_count = $("#all_count").val();
      var new_dat = data + '&l1='+l1+'&l2='+l2;
        console.log(data);
        $.ajax({
                url: '/admin/images/',
                type: 'POST',
                dataType: 'json',
                data: new_dat,
                success: function (res) {
                   // l1+=10;
                    console.log(res);
                    ok+=res.ok;
                    error+=res.error;
                    $('#ok').html(ok);
                    $('#all_p_er').html(error);
                    if (res.l2 < all_count) {
                            send(data, res.l1, res.l2);
                        }else{
                            console.log('exit');
                        }
                },
                error: function(e){
                    console.log(e);
                }
            });
        
         $('#foo').detach();
        return false;
    }
    
    });
</script>
        