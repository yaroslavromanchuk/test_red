<div class="row m-auto">
    <?php
    $new = Stores::getAllSrores();
    if($new){ ?>
    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
        <div class="list-group" id="list-tab" role="tablist" style="max-height:500px; height: 100%; overflow-y: overlay">
        <?php
        $i = 0;
        foreach ($new as $v) { ?>
             <a class="list-group-item list-group-item-action <?php if($i == 0){ echo 'active';} ?>" id="list-<?=$v->id?>-list" data-toggle="list" href="#list-<?=$v->id?>" role="tab" aria-controls="<?=$v->id?>">
            <div class="d-flex w-100 justify-content-between">
                 <h5 class="mb-1"><?=$v->name?></h5>
                 </div>
                 <p class="mb-1"><?=$v->adress?></p>
        </a> 
        <?php $i++; } ?>

    </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
    <div class="tab-content" id="nav-tabContent">
        <?php $i=0; foreach ($new as $a) { ?>
 <div class="tab-pane fade <?php if($i == 0){ echo 'show active';} ?>" id="list-<?=$a->id?>" role="tabpanel" aria-labelledby="list-<?=$a->id?>"><?=$a->google_map?></div>
<?php $i++; } ?>
    </div>
  </div>
    <?php } ?> 
    
    <!--<div class="col-sm-12 col-md-6 col-lg-8 col-xl-8 p-2">
        <div class="card">
       
           <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m12!1m8!1m3!1d68315.31722965499!2d30.464325334738444!3d50.48238029653888!3m2!1i1024!2i768!4f13.1!2m1!1zUkVEINC80LDQs9Cw0LfQuNC9!5e0!3m2!1sru!2sua!4v1543309029280"  height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
    </div>-->
</div>

