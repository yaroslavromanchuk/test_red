<nav class="navbar navbar-expand-lg navbar-light bg-light " style="z-index: 2;">
  <a class="navbar-brand" href="#">
    Категории
  </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#cat" aria-controls="cat" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <button class="navbar-toggler  float-right"  type="button" data-toggle="modal" data-target="#mobi_filter" >
    <span><i class="icon ion-ios-funnel" style="font-size: 29px;"></i></span>
  </button>
    <div class="collapse navbar-collapse" id="cat">
    <ul class="navbar-nav" style="max-height: calc(100vh - 11.2rem);overflow-y: auto;" >
      <?php if(count($this->filters['newcat'])){
            foreach ($this->filters['newcat'] as $cat) { 
                if($cat['kids']){ ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=$cat['title']?>
                            </a>
                             <div class="dropdown-menu" >
                                 <?php  foreach ($cat['kids'] as $value) {
                                     if($value['count'] > 0){?>
                <a class='dropdown-item' href='<?=$value['url']?>'>
                        <?=$value['title']?><span class='float-right badge    badge-pill round'><?=$value['count']?></span>
                </a>  
                                 <?php }} ?>
        </div>
        </li>
               <?php }else{ ?>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?=$cat['url']?>">
                       <?=$cat['title']?><span class="float-right badge    badge-pill round"><?=$cat['count']?></span>
                        </a>
                            </li>
                            <?php }
    } 
                        } ?>
    </ul>
  </div>
</nav>