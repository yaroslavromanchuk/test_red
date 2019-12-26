<input type="hidden" name="current_page" id="current_page" value="1"/>
<input type="hidden" name="total_pages" id="total_pages" value="<?=$this->total_pages?>"/>

<!--result -->
<div class="row m-0">
        <?php
            if(Registry::get('device') == 'computer'){ ?>

<div class="col col-sm-12 col-md-12 col-lg-2 col-xl-2 p-0 px-2">
     <?php
     if($this->getCurMenu()->getUrl() == 'desires' and $this->result_count > 0){ ?>
<div class="card mb-1">
    <button class="btn btn btn-outline-danger btn-sm p-1 " onclick="clearDes(); return false;">Очистить избранное</button>
</div>
    <script>
        function clearDes(){
    $.ajax({
            url:'/ajax/clear/',
            type:'POST',
            dataType:'json',
            success:function(){
                location.reload();
            },
        error: function(e){
            console.log(e);
            
        }
        });
}
        </script>
   <?php  
 }?>
        <?php if($this->filters){ ?>
        <div class="card">
    <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center"><?=$this->trans->get('filters')?></h6>
  </div>
    <div class="card-body p-1">
        <div class="row m-0">
        <div class="col-lg-12  filter_fixed px-2" style="z-index: 3">
            <?=$this->render('finder/filter.tpl.php')?>
        </div>
    </div>
    </div>
</div>
            <?php } ?>
 <?php if($top = Shoparticlestop::activeTopArticle() and $top->count() > 0){?>
<div class="card"></div>
 <?php }elseif($act = Shoparticlesoption::findActiveOption(5) and $act->count() > 0){ ?>
<div class="card">
  <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center"><?=$this->trans->get('spec_predlogeniye')?></h6>
  </div>  
<div id="activ_akciya" class="carousel1 slide" >
  <div class="carousel-inner" id="circle_car">
      <?php $i = 0; foreach ($act as $new) { ?>
     
      <div class="carousel-item <?=$i==0?'active':''?>">
           <a href="<?=$new->getPath()?>">
        <div class="bg-white p-3 d-block w-100 text-center ">
	<p class="w-100"><?=$new->option_text?></p>
	<?=$new->intro?>
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
</div>
</div>
<script>
$(document).ready(function() {
$('#circle_car').cycle({ 
    fx:      'blindX',
    speed:    1000, 
    timeout:  2000,
    pause:  1
});
});
</script>
 <?php } ?>
 <?php if(count($this->filters['newcat']) > 0){ ?>
    
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
                        ".$value['title']."<span class='float-right badge    badge-pill round'>".$value['count']."</span></a>";
                        
                    }
                    echo '</div>';
                }else{ ?>
                        <a class="list-group-item list-group-item-action"  href="<?=$cat['url']?>">
                      <i class="icon ion-ios-arrow-forward-outline mr-3"></i><?=$cat['title']?><span class="float-right badge    badge-pill round"><?=$cat['count']?></span></a>

                            <?php }
    } ?>
                    </div>
                </div>
     </div>

<?php } ?>
</div>
    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10 p-0">
    <div class="row m-0 m-auto">
        <div class="col-sm-12 p-0"><?=$this->result?></div>
    </div>
                  
</div>
     <?php   
    }else{ ?>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-1">
   <?=$this->render('finder/menu_cat_mobi.php')?> 
<?=$this->render('finder/mobi_filter.php')?>
    </div>
<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-0">
    <div class="row m-0 m-auto">
        <div class="col-lg-12 p-0"><?=$this->result?></div>
    </div>
                  
</div>
        
  <?php  } ?>

</div>
<!--exit result -->

