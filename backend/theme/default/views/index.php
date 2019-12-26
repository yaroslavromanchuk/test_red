<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robot" content="no-index,no-follow"/>
	<meta name="author" content="Romanchuk Yaroslav"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

   <title><?php echo ($this->getCurMenu()->getTitle()) ? $this->getCurMenu()->getTitle() : 'Система Управления Сайтом '.$this->website->getSite()->getName();?></title>


    <!-- vendor css -->
    <link href="<?=$this->files?>lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?=$this->files?>lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?=$this->files?>lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="<?=$this->files?>lib/highlightjs/github.css" rel="stylesheet">
    
   <!-- <link href="<?=$this->files?>lib/rickshaw/rickshaw.min.css" rel="stylesheet">-->
   <link href="<?=$this->files?>lib/datatables/jquery.dataTables.css" rel="stylesheet">
	<link href="<?=$this->files?>lib/select2/css/select2.min.css" rel="stylesheet">
	<link href="<?=$this->files?>lib/spectrum/spectrum.css" rel="stylesheet">
	
	<link href="<?=$this->files?>lib/morris.js/morris.css" rel="stylesheet">
	
	 <link href="<?=$this->files?>lib/jquery.steps/jquery.steps.css" rel="stylesheet">
         <link href="<?=$this->files?>lib/summernote/summernote-bs4.css" rel="stylesheet">
          <link href="<?=$this->files?>lib/spin_kit/spinkit.css?v=1.0" rel="stylesheet">
    <!-- Starlight CSS -->
	<link rel="stylesheet" href="<?=$this->files?>css/starlight.css?v=2.5">
    
	<script src="<?=$this->files?>lib/jquery/jquery.js"></script>
    
	
	 
  </head>

  <body >
      <?php
      if($this->user->id == 8005){
          
         // echo '<pre>';
         // print_r($this->getCurMenu());
         // echo '</pre>';
      }
      ?>
	  <?=$this->message;?>
    <!-- ########## START: LEFT PANEL ########## -->
    <div class="sl-logo">
        <a href="">
            <i class="icon ion-ios-star-outline"></i>
            <img src="<?=$this->files?>img/logo/logo.png"  style="height: 30px;margin-top: -10px;margin-left: 5px;"alt="RED">
        </a>
    </div>
    <div class="sl-sideleft">
    <!--  <div class="input-group input-group-search">
        <input type="search" name="search" class="form-control" placeholder="Search">
        <span class="input-group-btn">
          <button class="btn"><i class="fa fa-search"></i></button>
        </span><!-- input-group-btn -->
    <!--  </div><!-- input-group -->

      <label class="sidebar-label">Меню навигации</label>
      <div class="sl-sideleft-menu">
          <?php foreach (AdminSection::find('AdminSection') as $s) { ?>
            <a href="#" class="sl-menu-link <?php if($this->getCurMenu()->getSection() == $s->id){ echo 'active show-sub';} ?> ">
          <div class="sl-menu-item">
            <?=$s->logo?>
            <span class="menu-item-label"><?=$s->getName()?></span>
			<i class="menu-item-arrow fa fa-angle-down"></i>
          </div><!-- menu-item -->
        </a><!-- sl-menu-link -->
        <ul class="sl-menu-sub nav flex-column">
        <?php    foreach (Menu::find('Menu',['active'=>1, 'type_id'=>2, 'section'=>$s->id]) as $m) {
                                if($this->admin_rights[$m->id]['view']){ ?>
        <li class="nav-item">
            <a href="<?=$this->path.$m->getUrl()?>/" class="nav-link <?=($m->getUrl() == $this->getCurMenu()->getUrl()) ? 'active' : ''?>" title="<?=$m->getPageIntro()?>">
                    <?=$m->getName()?>
            </a>
        </li>
        
        <?php } 
        
                                } ?>
        </ul>
     <?php  } ?>
	
      </div><!-- sl-sideleft-menu -->

      <br>
    </div><!-- sl-sideleft -->
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <div class="sl-header">
      <div class="sl-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-md-menu"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-md-menu"></i></a></div>
      </div><!-- sl-header-left -->
      <div class="sl-header-right">
        <nav class="nav">
          <div class="dropdown">
            <a href="" class="nav-link nav-link-profile" data-toggle="dropdown">
              <span class="logged-name"><?=$this->user->getMiddleName()?><span class="hidden-md-down"> <?=$this->user->getFirstName()?></span></span>
              <img src="<?php echo $this->user->getLogo() ? $this->user->getLogo() : Config::findByCode('admin_logo_folder')->getValue().'error.png' ?>" class="wd-32 rounded-circle" alt="">
            </a>
            <div class="dropdown-menu dropdown-menu-header wd-200">
              <ul class="list-unstyled user-profile-nav">
                <li><a href="/admin/user/edit/id/<?=$this->user->id?>"><i class="icon ion-ios-create-outline"></i> Редактировать</a></li>
              <!--  <li><a href=""><i class="icon ion-ios-person-outline"></i> Settings</a></li>
                <li><a href=""><i class="icon ion-ios-download-outline"></i> Downloads</a></li>
                <li><a href=""><i class="icon ion-ios-folder-outline"></i> Favorites</a></li>-->
                <li><a href="/admin/password"><i class="icon ion-ios-star-outline"></i> Изменение пароля</a></li>
                <li><a href="/admin/logout"><i class="icon ion-ios-exit-outline"></i> Выход</a></li>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
       
      </div><!-- sl-header-right -->
    </div><!-- sl-header -->
    <!-- ########## END: HEAD PANEL ########## -->
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="sl-mainpanel">
      <nav class="breadcrumb sl-breadcrumb">
        <a class="breadcrumb-item" href="/admin/index/"><?=$this->trans->get('Главная');?></a>
		<?php if(/*$this->getCurMenu()->getSection()*/true){ ?>
		 <span class="breadcrumb-item active"><?=$this->getCurMenu()->getTitle()?></span>
	<?php	} ?>
       
      </nav>
    <div class="sl-pagebody">
       <!-- <div class="sl-page-title">
          <h5><?=$this->getCurMenu()->getTitle()?></h5>
          <p><?=$this->getCurMenu()->getPageIntro()?></p>
        </div>-->
            <?=$this->render($this->middle_template)?>
    </div>

    </div><!-- sl-mainpanel -->
    <!-- ########## END: MAIN PANEL ########## -->
	<script src="<?=$this->files?>lib/jquery-ui/jquery-ui.js"></script>
<script src="<?=$this->files?>lib/popperjs/popper.js?v=1.0"></script>
    <script src="<?=$this->files?>lib/bootstrap/bootstrap.js"></script>
	
	
	<script src="<?=$this->files?>lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
	

	<!--<script src="<?=$this->files?>lib/highlightjs/highlight.pack.js"></script>-->
        
			<script src="<?=$this->files?>lib/datatables/jquery.data_tables.js"></script>
			<script src="<?=$this->files?>lib/datatables-responsive/data_tables.responsive.js"></script>
                                <script src="<?=$this->files?>lib/parsley.js/parsley.min.js?v=1.1"></script>
	<script src="<?=$this->files?>lib/select2/js/select2.min.js"></script> 

    <script src="<?=$this->files?>lib/spectrum/spectrum.js"></script>
		
		<script src="<?=$this->files?>lib/jquery.steps/jquery.steps.js"></script>
		
                <script src="<?=$this->files?>lib/summernote/summernote-bs4.min.js"></script>
                
<script src="<?=$this->files?>lib/highlightjs/highcharts.js"></script>
<script src="<?=$this->files?>lib/highlightjs/exporting.js"></script>
<script src="<?=$this->files?>lib/highlightjs/export-data.js?v=1.4"></script>
	
	<script src="<?=$this->files?>scripts/starlight.js?v=1"></script>

	<script>
	  $(function(){

	  'use strict';
	 $('.select2').parsley();

        $('.select2').select2({
          minimumResultsForSearch: Infinity
        });
		// Select2 by showing the search
        $('.select2-show-search').select2({
          minimumResultsForSearch: ''
        });
        
	  });
          
       $('.chekAll').change(function () {
          if($('.chekAll').is(":checked")){
		$('.cheker').prop('checked', true);
		}else{
		$('.cheker').prop('checked', false);
		}
            return false;
       });
       
//открытие всплывающего окна нова почта	
function fopen(header = '', body = '', footer = ''){
if(body) $('#popup').html(body);
if(header) $('#myModalLabel').html(header);
if(footer){ $('#myModalFooter').html(footer);}else{ $('#myModalFooter').html('<button class="btn btn-secondary pd-x-20" data-dismiss="modal" aria-hidden="true">Закрыть</button>');}
$('#myModalMessage').modal('show');
}
//закрытие всплывающего окна нова почта
function FormClose(){$('#myModalMessage').modal('hide');}
$(document).ready(function(){

	$("#back-top").hide();
		$(window).scroll(function () {
			if ($(this).scrollTop() > 200) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
                
		$('#back-top a').click(function () {$('body,html').animate({scrollTop: 0}, 1);return false;}); 	

});

	
function show(){return false;}

</script>
  </body>
</html>
