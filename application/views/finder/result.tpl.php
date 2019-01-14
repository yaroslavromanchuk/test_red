<link type="text/css" href="/css/findex.css?v=1.4" rel="stylesheet"/>
<input type="hidden" name="current_page" id="current_page" value="1"/>

<script src="/lib/select2/js/select2.min.js"></script>

<input type="hidden" name="total_pages" id="total_pages" value="<?=$this->total_pages?>"/>
  
<?php if(true){ ?>
<!--result -->
<div class="row m-0">
        <?php
            if(Registry::get('device') == 'computer'){ ?>

<div class="col col-sm-12 col-md-12 col-lg-2 col-xl-2 p-1">
     <?php
    
 if($top = Shoparticlestop::activeTopArticle() and $top->count() > 0){
    // echo print_r($top);
     ?>
<div class="card"></div>
 <?php }elseif($act = Shoparticlesoption::findActiveOption(5) and $act->count() > 0){ ?>
<div class="card">
  <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center"><?=$this->trans->get('spec_predlogeniye')?></h6>
  </div>  
<div id="activ_akciya" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
      <?php $i = 0; foreach ($act as $new) {?>
     
      <div class="carousel-item <?=$i==0?'active':''?>">
           <a href="<?=$new->getPath()?>">
        <div class="bg-white p-3 d-block w-100 text-center ">
	<p><?=$new->option_text?></p>
	<p class="text-center"><?=$new->intro?></p>
          <?php if($new->timer){ ?>
        <div class="text-center mb-2">
                        <p class="p-0 m-0 d_end text-uppercase">До завершения:</p>
                        <div class="timer d-inline-block p-2 btn-group" id="<?=$new->id?>"></div>
                    </div>
        <script>initializeClock('<?=$new->id?>', new Date("<?=$new->end?> 23:59:59"));</script>
          <?php } ?>
	</div>   
          </a>
      </div> 
          
      <?php $i++; } ?>
  </div>
  <a class="carousel-control-prev" href="#activ_akciya" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#activ_akciya" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>
 <?php }
 if(count($this->filters['newcat']) > 0){ 
 ?>
    
    <div class="card">
    <div class="card-header w-100">
    <h6 class="title">
            <?php 
    if($this->getCategory()->getId()){
   echo $this->getCategory()->getRoutez().'<span class="float-right badge  badge-light  badge-pill round">'.$this->result_count.'</span>';
    }else{
        echo $this->trans->get('Категория');
    } ?>
        </h6>
  </div>
  
<div class="filter-content">
                    
<div class="list-group list-group-flush list-group-item-action">
                        <?php
    foreach ($this->filters['newcat'] as $cat) { 
      if($cat['kids']){
        if($cat['title']){
             echo "<a class='list-group-item list-group-item-action' href='".$cat['url']."'><i class='icon ion-ios-arrow-down mr-3'></i>".$cat['title']."</a>";
        }
   
 echo ' <div class="list-group list-group-flush p-1 pl-3">';
        foreach ($cat['kids'] as $value) {
             echo "<a class='list-group-item list-group-item-action' href='".$value['url']."'>
                 <i class='icon ion-ios-arrow-forward-outline mr-3'></i>
                        ".$value['title']."<span class='float-right badge  badge-secondary  badge-pill round'>".$value['count']."</span></a>";
                        
                    }
                    echo '</div>';
                }else{ ?>
                        <a class="list-group-item list-group-item-action"  href="<?=$cat['url']?>">
                      <i class="icon ion-ios-arrow-forward-outline mr-3"></i><?=$cat['title']?><span class="float-right badge  badge-secondary  badge-pill round"><?=$cat['count']?></span></a>

                            <?php }
    } ?>
                    </div>
                </div>
     </div>

<?php } ?>
</div>
    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 p-1">
    <div class="row m-0">
        <div class="col-lg-12  filter_fixed px-2" style="z-index: 3">
            <?=$this->render('finder/filter.tpl.php')?>
        </div>
        
    </div>
    <div class="row m-0">
        <div class="col-lg-12"><?=$this->result?></div>
    </div>
                  
</div>
     <?php   
    }else{ ?>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-1">
    <div class="modal fade" id="mobi_filter_cat" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md" style="margin: 0.5rem 0.5rem 2.5rem 0.5rem; ">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title"><?=$this->trans->get('Категория')?></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		<div class="modal-body p-1" >
                    
<div class="list-group list-group-flush list-group-item-action" style="z-index:999">
                        <?php
                        if(count($this->filters['newcat'])){
    foreach ($this->filters['newcat'] as $cat) { 
      if($cat['kids']){
        if($cat['title']){
             echo "<a class='nav-item list-group-item' href='".$cat['url']."'><i class='icon ion-ios-arrow-down mr-3'></i>".$cat['title']."</a>";
        }
   
 echo ' <div class="list-group list-group-flush p-1 pl-3">';
        foreach ($cat['kids'] as $value) {
             echo "<a class='nav-item list-group-item' href='".$value['url']."'>
                 <i class='icon ion-ios-arrow-forward-outline mr-3'></i>
                        ".$value['title']."<span class='float-right badge  badge-secondary  badge-pill round'>".$value['count']."</span></a>";
                        
                    }
                    echo '</div>';
                }else{ ?>
                        <a class="nav-item list-group-item"  href="<?=$cat['url']?>">
                      <i class="icon ion-ios-arrow-forward-outline mr-3"></i><?=$cat['title']?><span class="float-right badge  badge-secondary  badge-pill round"><?=$cat['count']?></span></a>

                            <?php }
    } 
                        } ?>
                    </div>
                    </div>
                    </div>
            </div>
        </div>
<?=$this->render('finder/mobi_filter.php')?>
    </div>
<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-1">
    <div class="row m-0">
        <div class="col-lg-12 p-1"><?=$this->result?></div>
    </div>
                  
</div>
        
  <?php  } ?>

</div>
<!--exit result -->
    <?php 
}else{ ?>
<div id="filter"  class="filter col-xs-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 p-0"  style="float:left">
    <div class="card">
    
            <?=$this->render('finder/filter_new.tpl.php')?>
      
    </div>

</div>
<!--result -->
<div id="result" style="display: table;" class="col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0"><?=$this->result;?></div>
<!--exit result -->
<?php } ?>
