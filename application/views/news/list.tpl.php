<div class="row mx-auto">
<?php
if($this->news){
foreach($this->news as $new) { ?>
<div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-2">
<div class="bg-white p-3 h-100 text-center">
	<h2><?=$new->option_text?></h2>
	<p class="text-center"><?=$new->intro?></p>
        <?php if($new->timer){ ?>
        <div class="text-center mb-2">
                        <p class="p-0 m-0 d_end text-uppercase">До завершения:</p>
                        <div class="timer d-inline-block p-2 btn-group" id="<?=$new->id?>"></div>
                    </div>
        <script>initializeClock('<?=$new->id?>', new Date("<?=$new->end?> 23:59:59"));</script>
<?php } ?>
	<a class="btn btn-danger" href="<?=$new->getPath()?>"><?=$this->trans->get('Смотреть детали')?></a><br/>
	</div>
	</div>
<?php
}

}else{ ?>
<div class="col-xl-12 p-1">
<div class="bg-white p-2 h-100 text-center">
<h5 class="text-danger d-inline-block p-3 font-weight-bold"><?=$this->trans->get('В данный момент нет действующих акций')?></h5><br>
<p><?=$this->trans->get('Подпишитесь на нашу новостную рассылку, чтобы всегда располагать последней информацией и узнавать о наших особых предложениях')?>.</p>
</div>
</div>
<?php } ?>
    </div>


    <?php
if($this->ws->getCustomer()->getIsLoggedIn() and $this->ws->getCustomer()->isAdmin()){ 
    $all_news = Shoparticlesoption::findAllActiveOption();
    if($all_news){ ?> 
<br>
    <div class="row mx-auto">
        <div class="col-sm-12">
        <div class="card">
            <h5 class="card-header bg-danger text-white">
    Скрытые или не активные акции
  </h5>
       <div class="card-body">
           <h5 class="card-title">Отображаются только администрации</h5>
           <div class="row">

    <?php 
        foreach ($all_news as $n){ ?>
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 p-1">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title text-danger"><?=$n->option_text?></h5>
                    <p class="card-text"><?=$n->intro?></p>
                      <?php if($n->timer){ ?>
                    <div class="text-center m-auto">
                        <p class="p-0 m-0 d_end text-uppercase"></p>
                        <div class="timer d-inline-block p-2" id="<?=$n->id?>"></div>
                    </div>
                    <a class="btn btn-danger" href="<?=$n->getPath()?>"><?=$this->trans->get('Смотреть детали')?></a>
<script>initializeClock('<?=$n->id?>', new Date("<?=$n->end?> 23:59:59"));</script>
                      <?php } ?>
                </div>

            </div>
        </div>
      <?php  } ?>
       
             
       </div>  
       </div>
    </div>
        </div>  
    </div>
   <?php  }
} 



