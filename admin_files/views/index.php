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
   <title><?php echo ($this->getCurMenu()->getTitle()) ? $this->getCurMenu()->getTitle() : 'Система Управления Сайтом '.$this->website->getSite()->getName();?></title>

    <!-- vendor css -->
    <link href="<?=$this->files?>views/template/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?=$this->files?>views/template/lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="<?=$this->files?>views/template/lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
	
	
	 <link href="<?=$this->files?>views/template/lib/highlightjs/github.css" rel="stylesheet">
   <!-- <link href="<?=$this->files?>views/template/lib/rickshaw/rickshaw.min.css" rel="stylesheet">-->
	<link href="<?=$this->files?>views/template/lib/select2/css/select2.min.css" rel="stylesheet">
	<link href="<?=$this->files?>views/template/lib/spectrum/spectrum.css" rel="stylesheet">
	
	<link href="<?=$this->files?>views/template/lib/morris.js/morris.css" rel="stylesheet">
    <!-- Starlight CSS -->
	<link rel="stylesheet" href="<?=$this->files?>views/template/css/starlight.css">
    
		<script src="<?=$this->files?>views/template/lib/jquery/jquery.js"></script>
    
	
	 
  </head>

  <body>
	  <?=$this->message;?>
    <!-- ########## START: LEFT PANEL ########## -->
    <div class="sl-logo"><a href=""><i class="icon ion-android-star-outline"></i> RED.UA</a></div>
    <div class="sl-sideleft">
    <!--  <div class="input-group input-group-search">
        <input type="search" name="search" class="form-control" placeholder="Search">
        <span class="input-group-btn">
          <button class="btn"><i class="fa fa-search"></i></button>
        </span><!-- input-group-btn -->
    <!--  </div><!-- input-group -->
<?php $menus =  wsActiveRecord::useStatic('Menu')->findAll(array('active'=> 1, 'type_id'=>2, 'parent_id' => 4,'section in (1,2,3,4,5)')); ?>
      <label class="sidebar-label">Меню навигации</label>
      <div class="sl-sideleft-menu">
	  <a href="/admin/index/" class="sl-menu-link <?php echo '/admin/index/' == $_SERVER['SCRIPT_NAME'] ? 'active' : ''; ?>">
          <div class="sl-menu-item">
            <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
            <span class="menu-item-label"><?=$this->trans->get('Главная');?></span>
          </div><!-- menu-item active-->
        </a><!-- sl-menu-link -->
        <a href="#" class="sl-menu-link <?php if($this->getCurMenu()->getSection() == 1) echo 'active show-sub' ?>">
          <div class="sl-menu-item">
            <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
            <span class="menu-item-label"><?=$this->trans->get('Страницы');?></span>
			 <i class="menu-item-arrow fa fa-angle-down"></i>
          </div><!-- menu-item active-->
        </a><!-- sl-menu-link -->
		<ul class="sl-menu-sub nav flex-column" >
		<?php foreach($menus as $menu){ if($menu->getSection() == 1 and $this->admin_rights[$menu->getId()]['view']) { ?>
<li class="nav-item"><a href="<?=$this->path.$menu->getUrl()?>" class="nav-link <?php echo $this->path.$menu->getUrl() == $_SERVER['SCRIPT_NAME'] ? 'active' : ''; ?>" title="<?=$menu->getPageIntro()?>"><?=$menu->getName(); ?></a></li>
<?php } } ?>
        </ul>
        <a href="#" class="sl-menu-link <?php if($this->getCurMenu()->getSection() == 2) echo 'active show-sub' ?> ">
          <div class="sl-menu-item">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label"><?=$this->trans->get('Магазин')?></span>
			<i class="menu-item-arrow fa fa-angle-down"></i>
          </div><!-- menu-item -->
        </a><!-- sl-menu-link -->
				<ul class="sl-menu-sub nav flex-column" >
		<?php foreach($menus as $menu){
		if($menu->getSection() == 2 and $this->admin_rights[$menu->getId()]['view']){?>
<li class="nav-item"><a href="<?=$this->path.$menu->getUrl()?>" class="nav-link <?php echo $this->path.$menu->getUrl() == $_SERVER['SCRIPT_NAME'] ? 'active' : ''; ?>" title="<?=$menu->getPageIntro()?>"><?=$menu->getName(); ?></a></li>
<?php }} ?>
        </ul>
        <a href="#" class="sl-menu-link <?php if($this->getCurMenu()->getSection() == 3) echo 'active show-sub' ?>">
          <div class="sl-menu-item">
            <i class="menu-item-icon ion-ios-pie-outline tx-20"></i>
            <span class="menu-item-label"><?=$this->trans->get('Пользователи')?></span>
            <i class="menu-item-arrow fa fa-angle-down"></i>
          </div><!-- menu-item -->
        </a><!-- sl-menu-link -->
        		<ul class="sl-menu-sub nav flex-column" >
		<?php foreach($menus as $menu){ if($menu->getSection() == 3 and $this->admin_rights[$menu->getId()]['view']) { ?>
<li class="nav-item"><a href="<?=$this->path.$menu->getUrl()?>" class="nav-link <?php echo $this->path.$menu->getUrl() == $_SERVER['SCRIPT_NAME'] ? 'active' : ''; ?>" title="<?=$menu->getPageIntro()?>"><?=$menu->getName(); ?></a></li>
<?php } } ?>
        </ul>
        <a href="#" class="sl-menu-link <?php if($this->getCurMenu()->getSection() == 4) echo 'active show-sub' ?>">
          <div class="sl-menu-item">
            <i class="menu-item-icon icon ion-ios-gear-outline tx-24"></i>
            <span class="menu-item-label"><?=$this->trans->get('Администирование')?></span>
            <i class="menu-item-arrow fa fa-angle-down"></i>
          </div><!-- menu-item -->
        </a><!-- sl-menu-link -->
        		<ul class="sl-menu-sub nav flex-column" >
		<?php foreach($menus as $menu){ if($menu->getSection() == 4 and $this->admin_rights[$menu->getId()]['view']){ ?>
<li class="nav-item"><a href="<?=$this->path.$menu->getUrl()?>" class="nav-link <?php echo $this->path.$menu->getUrl() == $_SERVER['SCRIPT_NAME'] ? 'active' : ''; ?>" title="<?=$menu->getPageIntro()?>"><?=$menu->getName(); ?></a></li>
<?php }  }?>
        </ul>
        <a href="#" class="sl-menu-link <?php if($this->getCurMenu()->getSection() == 5) echo 'active show-sub' ?>">
          <div class="sl-menu-item">
            <i class="menu-item-icon icon ion-ios-filing-outline tx-24"></i>
            <span class="menu-item-label"><?=$this->trans->get('Служебное')?></span>
            <i class="menu-item-arrow fa fa-angle-down"></i>
          </div><!-- menu-item -->
        </a><!-- sl-menu-link -->
        		<ul class="sl-menu-sub nav flex-column" >
		<?php foreach($menus as $menu){ if($menu->getSection() == 5 and $this->admin_rights[$menu->getId()]['view']) { ?>
<li class="nav-item"><a href="<?=$this->path.$menu->getUrl()?>" class="nav-link <?php echo $this->path.$menu->getUrl() == $_SERVER['SCRIPT_NAME'] ? 'active' : ''; ?>" title="<?=$menu->getPageIntro()?>"><?=$menu->getName(); ?></a></li>
<?php } } ?>
        </ul>
      </div><!-- sl-sideleft-menu -->

      <br>
    </div><!-- sl-sideleft -->
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <div class="sl-header">
      <div class="sl-header-left">
        <div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href=""><i class="icon ion-navicon-round"></i></a></div>
        <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href=""><i class="icon ion-navicon-round"></i></a></div>
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
                <li><a href="/admin/user/edit/id/<?=$this->user->id?>"><i class="icon ion-ios-gear-outline"></i> Редактировать</a></li>
              <!--  <li><a href=""><i class="icon ion-ios-person-outline"></i> Settings</a></li>
                <li><a href=""><i class="icon ion-ios-download-outline"></i> Downloads</a></li>
                <li><a href=""><i class="icon ion-ios-folder-outline"></i> Favorites</a></li>-->
                <li><a href="/admin/password"><i class="icon ion-ios-star-outline"></i> Изменение пароля</a></li>
                <li><a href="/admin/logout"><i class="icon ion-power"></i> Выход</a></li>
              </ul>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </nav>
        <div class="navicon-right">
          <a id="btnRightMenu" href="" class="pos-relative">
            <i class="icon ion-ios-bell-outline"></i>
            <!-- start: if statement -->
            <span class="square-8 bg-danger"></span>
            <!-- end: if statement -->
          </a>
        </div><!-- navicon-right -->
      </div><!-- sl-header-right -->
    </div><!-- sl-header -->
    <!-- ########## END: HEAD PANEL ########## -->

    <!-- ########## START: RIGHT PANEL ########## -->
    <div class="sl-sideright">
      <ul class="nav nav-tabs nav-fill sidebar-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" role="tab" href="#messages">Messages (2)</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" role="tab" href="#notifications">Notifications (8)</a>
        </li>
      </ul><!-- sidebar-tabs -->

      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane pos-absolute a-0 mg-t-60 active" id="messages" role="tabpanel">
          <div class="media-list">
            <!-- loop starts here -->
            <a href="" class="media-list-link">
              <div class="media">
                <img src="<?=$this->files?>views/template/img/img3.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="mg-b-0 tx-medium tx-gray-800 tx-13">Donna Seay</p>
                  <span class="d-block tx-11 tx-gray-500">2 minutes ago</span>
                  <p class="tx-13 mg-t-10 mg-b-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring.</p>
                </div>
              </div><!-- media -->
            </a>
            <!-- loop ends here -->
            <a href="" class="media-list-link">
              <div class="media">
                <img src="<?=$this->files?>views/template/img/img4.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="mg-b-0 tx-medium tx-gray-800 tx-13">Samantha Francis</p>
                  <span class="d-block tx-11 tx-gray-500">3 hours ago</span>
                  <p class="tx-13 mg-t-10 mg-b-0">My entire soul, like these sweet mornings of spring.</p>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link">
              <div class="media">
                <img src="<?=$this->files?>views/template/img/img7.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="mg-b-0 tx-medium tx-gray-800 tx-13">Robert Walker</p>
                  <span class="d-block tx-11 tx-gray-500">5 hours ago</span>
                  <p class="tx-13 mg-t-10 mg-b-0">I should be incapable of drawing a single stroke at the present moment...</p>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link">
              <div class="media">
                <img src="<?=$this->files?>views/template/img/img5.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="mg-b-0 tx-medium tx-gray-800 tx-13">Larry Smith</p>
                  <span class="d-block tx-11 tx-gray-500">Yesterday, 8:34pm</span>

                  <p class="tx-13 mg-t-10 mg-b-0">When, while the lovely valley teems with vapour around me, and the meridian sun strikes...</p>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link">
              <div class="media">
                <img src="<?=$this->files?>views/template/img/img3.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="mg-b-0 tx-medium tx-gray-800 tx-13">Donna Seay</p>
                  <span class="d-block tx-11 tx-gray-500">Jan 23, 2:32am</span>
                  <p class="tx-13 mg-t-10 mg-b-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring.</p>
                </div>
              </div><!-- media -->
            </a>
          </div><!-- media-list -->
          <div class="pd-15">
            <a href="" class="btn btn-secondary btn-block bd-0 rounded-0 tx-10 tx-uppercase tx-mont tx-medium tx-spacing-2">View More Messages</a>
          </div>
        </div><!-- #messages -->

        <div class="tab-pane pos-absolute a-0 mg-t-60 overflow-y-auto" id="notifications" role="tabpanel">
          <div class="media-list">
            <!-- loop starts here -->
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img8.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Suzzeth Bungaos</strong> tagged you and 18 others in a post.</p>
                  <span class="tx-12">October 03, 2017 8:45am</span>
                </div>
              </div><!-- media -->
            </a>
            <!-- loop ends here -->
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img9.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Mellisa Brown</strong> appreciated your work <strong class="tx-medium tx-gray-800">The Social Network</strong></p>
                  <span class="tx-12">October 02, 2017 12:44am</span>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img10.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700">20+ new items added are for sale in your <strong class="tx-medium tx-gray-800">Sale Group</strong></p>
                  <span class="tx-12">October 01, 2017 10:20pm</span>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img5.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Julius Erving</strong> wants to connect with you on your conversation with <strong class="tx-medium tx-gray-800">Ronnie Mara</strong></p>
                  <span class="tx-12">October 01, 2017 6:08pm</span>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img8.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Suzzeth Bungaos</strong> tagged you and 12 others in a post.</p>
                  <span class="tx-12">September 27, 2017 6:45am</span>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img10.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700">10+ new items added are for sale in your <strong class="tx-medium tx-gray-800">Sale Group</strong></p>
                  <span class="tx-12">September 28, 2017 11:30pm</span>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img9.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Mellisa Brown</strong> appreciated your work <strong class="tx-medium tx-gray-800">The Great Pyramid</strong></p>
                  <span class="tx-12">September 26, 2017 11:01am</span>
                </div>
              </div><!-- media -->
            </a>
            <a href="" class="media-list-link read">
              <div class="media pd-x-20 pd-y-15">
                <img src="<?=$this->files?>views/template/img/img5.jpg" class="wd-40 rounded-circle" alt="">
                <div class="media-body">
                  <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Julius Erving</strong> wants to connect with you on your conversation with <strong class="tx-medium tx-gray-800">Ronnie Mara</strong></p>
                  <span class="tx-12">September 23, 2017 9:19pm</span>
                </div>
              </div><!-- media -->
            </a>
          </div><!-- media-list -->
        </div><!-- #notifications -->

      </div><!-- tab-content -->
    </div><!-- sl-sideright -->
    <!-- ########## END: RIGHT PANEL ########## --->

    <!-- ########## START: MAIN PANEL ########## -->
    <div class="sl-mainpanel">
      <nav class="breadcrumb sl-breadcrumb">
        <a class="breadcrumb-item" href="/admin/index/"><?=$this->trans->get('Главная');?></a>
		<?php if($this->getCurMenu()->getSection()){?>
		 <span class="breadcrumb-item active"><?=$this->getCurMenu()->getTitle()?></span>
	<?php	} ?>
       
      </nav>
	   <?php if($this->user->getId() == 8005) //echo print_r($this->getCurMenu()->getSection());?>

	  <?=$this->render($this->middle_template);?><!-- sl-pagebody -->
    <!--  <footer class="sl-footer">
        <div class="footer-left">
          <div class="mg-b-2">Copyright &copy; 2017. Starlight. All Rights Reserved.</div>
          <div>Made by ThemePixels.</div>
        </div>
        <div class="footer-right d-flex align-items-center">
          <span class="tx-uppercase mg-r-10">Share:</span>
          <a target="_blank" class="pd-x-5" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//themepixels.me/starlight"><i class="fa fa-facebook tx-20"></i></a>
          <a target="_blank" class="pd-x-5" href="https://twitter.com/home?status=Starlight,%20your%20best%20choice%20for%20premium%20quality%20admin%20template%20from%20Bootstrap.%20Get%20it%20now%20at%20http%3A//themepixels.me/starlight"><i class="fa fa-twitter tx-20"></i></a>
        </div>
      </footer>-->
    </div><!-- sl-mainpanel -->
    <!-- ########## END: MAIN PANEL ########## -->
	
<script src="<?=$this->files?>views/template/lib/popper.js/popper.js"></script>
    <script src="<?=$this->files?>views/template/lib/bootstrap/bootstrap.js"></script>
	
	<script src="<?=$this->files?>views/template/lib/jquery-ui/jquery-ui.js"></script>
	<script src="<?=$this->files?>views/template/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
	

	<script src="<?=$this->files?>views/template/lib/highlightjs/highlight.pack.js"></script>
	<script src="<?=$this->files?>views/template/lib/select2/js/select2.min.js"></script>
    <script src="<?=$this->files?>views/template/lib/spectrum/spectrum.js"></script>
	
	<script src="<?=$this->files?>views/template/js/starlight.js"></script>

	<script>
	  $(function(){
	  
	
	  'use strict';
	

        $('.select2').select2({
          minimumResultsForSearch: Infinity
        });
		// Select2 by showing the search
        $('.select2-show-search').select2({
          minimumResultsForSearch: ''
        });
	  });
//открытие всплывающего окна нова почта	
function fopen(header = '', body = ''){
if(body) $('#popup').html(body);
if(header) $('#myModalLabel').html(header);
$('#myModalMessage').modal('show');
}
//закрытие всплывающего окна нова почта
function FormClose(){$('#myModalMessage').modal('hide');}
$(document).ready(function(){



$("[data-tooltip='tooltip']").tooltip();
	$("#back-top").hide();
		$(window).scroll(function () {
			if ($(this).scrollTop() > 200) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
		$('#back-top a').click(function () {$('body,html').animate({scrollTop: 0}, 1);return false;}); 	
/*
$('#myModalMessage').on('hidden.bs.modal', function (event) { 
// функции 
});	*/
});

	
function show(){return false;}
function setUk(l) {
var s = '<?=$_SESSION['lang']?>';
if(l.name != s){
      $.ajax({
         type: "POST",
         url: "/ajax/setlang/",
         data: "&lang="+l.name,
		// dataType: 'json',
         success: function(res){document.location.href = "/admin/index/";}
          });
		  }
          return false;
}
</script>
  </body>
</html>
