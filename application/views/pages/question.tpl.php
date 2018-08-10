<h1><?=$this->getCurMenu()->getName()?></h1>
	<?php //echo $this->getCurMenu()->getPageBody();?>
<div id="accordion">
<?php if(@$this->faq){ foreach($this->faq as $f){ ?>
  <div class="card">
    <div class="card-header" id="h<?=$f->id?>">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#<?=$f->id?>" aria-expanded="false" aria-controls="<?=$f->id?>"><?=$f->question?></button>
      </h5>
    </div>
    <div id="<?=$f->id?>" class="collapse" aria-labelledby="h<?=$f->id?>" data-parent="#accordion">
      <div class="card-body"><?=$f->answer?></div>
    </div>
  </div>
  <?php } }?>
</div>


