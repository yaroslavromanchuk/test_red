
    <?php
    $info = Stores::getInfo();
    if($info) {
        $i = 0;
        foreach ($info as $v) { ?> 
    <div class="row m-auto  <?php if($i != 0){ echo 'revealator-zoomin revealator-duration10  revealator-once'; } ?>">
         <?php   if($i % 2 === 0){?>
      <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2 ">
       <div class="card bd-0">
            <img class="card-img img-fluid" src="<?=$v->src?>" alt="Card image cap">
       </div>
    </div> 
    <div class="col-sm-12 col-md-6 col-lg-8 col-xl-8 p-2  ">
        <div class=" m-auto"><?=$v->text?></div>
    </div>
        <?php  }else{?>
     <div class="col-sm-12 col-md-6 col-lg-8 col-xl-8 p-2  text-right ">
        <div class="media-body m-auto"><?=$v->text?></div>
    </div>
      <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2 ">
       <div class="card bd-0">
            <img class="card-img img-fluid" src="<?=$v->src?>" alt="Card image cap">
       </div>
    </div>   
    <?php   } 
         $i++; ?>
         </div>
        <?php    } ?>
        <?php } ?>


