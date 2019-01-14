<div class="row mx-auto bg-white">
<div class="col-xl-12 p-3">
        <div class="text-center p-3" >
        <?=$this->news->content?>
        </div>
</div>
    <?php if($this->news->type != 'all'){ ?>
    <div class="col-xl-12 text-center p-3 ">
        <a class="btn btn-danger" href="<?=$this->news->getPathFind()?>"><?=$this->trans->get('Товары в акции')?></a>
    </div>
    <?php  } ?>
</div>

