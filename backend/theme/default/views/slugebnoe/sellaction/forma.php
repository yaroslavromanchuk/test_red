
<?php if($this->excel){
    
   // l($this->excel);
    ?>
    <div class="row row-sm mg-x-0 mt-2">
     <div class="col-sm-12">
<div class="card pd-30">
<h6 class="card-body-title">Содержимое файта</h6>
<p></p>
<form  method="POST" action="" class="form-horizontal">
                            
			<?php foreach($this->excel as $kc=>$config){ ?>
                            <div class="row mg-b-25">
                                <?php 
                                        foreach ($config as $k=>$v) {?>
                                        <div class="col">
				<div class="form-group">
                                    <label class="form-control-label"><?=$kc.':'.$k?></label>
                                    <input type="text" class="form-control" <?=($kc==0)?'readonly':''?>  name="data[<?=$kc?>][<?=$k?>]" value="<?=$v?>"  >
			      </div>
                            </div>     
                                            <?php    }
                                ?>
                                </div>
			<?php } ?>
    <input type="submit"  class="btn btn-info mg-r-5" name="save" value="Сохранить">
                          <!--   <button class="btn btn-info mg-r-5" name="save" type="submit">Сохранить</button>    -->
</form>
</div>
</div>  
</div>
<?php }else{ ?>
<div class="row row-sm mg-x-0">
     <div class="col-sm-12">
<div class="card pd-30">
<h6 class="card-body-title">Форма чтения файла для сверки</h6>
<p></p>
<form action="" method="POST"  enctype="multipart/form-data">
<div class="form-layout">
            <div class="row mg-b-25">
		<div class="col-lg-3 mg-t-40 mg-lg-t-0">
                <input type="file" name="excel_file" class="custom-file-input">
                <span class="custom-file-control custom-file-control-primary"></span>
            </div>
                <div class="col-lg-2 mg-t-40 mg-lg-t-0">
                <input type="text" name="order" class="form-control" placeholder="Заказ" value="<?=$_GET['order']?$_GET['order']:1?>">
            </div>
                <div class="col-lg-2 mg-t-40 mg-lg-t-0">
                    <input type="text" name="status" class="form-control" placeholder="Статус" value="<?=$_GET['status']?$_GET['status']:2?>">
            </div>
                <div class="col-lg-2 mg-t-40 mg-lg-t-0">
             <input type="text" name="summa" class="form-control" placeholder="Сумма" value="<?=$_GET['summa']?$_GET['summa']:3?>">
            </div>
                    <button class="btn btn-info mg-r-5" name="open_file" type="submit">Прочитать файл</button>
            </div>
</div>
</form>
</div>
</div>
    <div class="col-sm-12">
        <?php  if($this->save){ ?>
        <div class="alert alert-success" role="alert"><strong>Успех!</strong> <?=$this->save?></div>
       <?php } ?>
         <?php  if($this->warning){ ?>
        <div class="alert alert-warning" role="alert"><strong>Предупреждение!</strong> <?=$this->warning?></div>
       <?php } ?>
         <?php  if($this->error){ ?>
        <div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> <?=$this->error?></div>
       <?php } ?>
    </div>  
</div>
<?php } ?>
