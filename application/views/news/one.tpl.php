<div class="row mx-auto bg-white">
<div class="col-xl-12 p-3">
	<h1 style="color:#e40413;"><?=$this->news->option_text?></h1>
        <div class="text-center p-3" >
        <?=$this->news->content?>
        </div>
</div>
    <?php if($this->news->action != 'all'){?>
    <div class="col-xl-12 text-center p-3 ">
        <a class="btn btn-danger" href="<?=$this->news->getPathFind()?>"><?=$this->trans->get('Товары в акции')?></a>
    </div>
    <?php  } ?>
</div>

