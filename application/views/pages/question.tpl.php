<div id="accordion" class="row m-auto">
<?php if(@$this->faq){
    foreach($this->faq as $f){ ?>
    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 m-auto">
  <div class="card">
    <div class="card-header" id="h<?=$f->id?>">
        <h5 class="mb-0" data-toggle="collapse" data-target="#<?=$f->id?>" aria-expanded="false" aria-controls="<?=$f->id?>"><a href="#" onchange="return false;"><?=$f->getQuestion()?></a>
      </h5>
    </div>
    <div id="<?=$f->id?>" class="collapse" aria-labelledby="h<?=$f->id?>" data-parent="#accordion">
      <div class="card-body"><?=$f->getAnswer()?></div>
    </div>
  </div>
    </div>
  <?php }
  }?>
</div>


