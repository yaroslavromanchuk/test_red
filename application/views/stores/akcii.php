
    <div class="row m-auto section1">
<?php
$new = Stores::getAkcii();

foreach ($new as $value) { ?>

     <div class="modal fade" id="d-<?=$value->id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" ><?=$value->name?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$value->text?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Закрити</button>
      </div>
    </div>
  </div>
</div>
    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2 revealator-zoomin revealator-duration10 revealator-once">
        <div class="card bd-0">
            <img class="card-img img-fluid" src="<?=$value->src?>" alt="Card image cap">
<div class="card-img-overlay p-4 d-flex align-items-start flex-column">
    <p class="mb-0" style="margin-top: 75%;">
       <button class="btn btn-outline-dark btn-hover-white btn-lg view-modal" data-toggle="modal" data-target="#d-<?=$value->id?>">Деталі</button>
    </p>
  </div><!-- card-img-overlay -->
       <!-- <div class="text-block blue">
            <button class="btn btn-outline-light btn-hover-white btn-lg view-modal" data-toggle="modal" data-target="#d-<?=$value->id?>">Деталі</button>
        </div>-->
               
    </div>
        </div>
        
    <?php
    $i++;
}
?>
</div>
