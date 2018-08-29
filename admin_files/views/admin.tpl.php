<!DOCTYPE html">
<html lang="en">
<head>
	<meta name="robot" content="no-index,no-follow"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($this->getCurMenu()->getTitle()) ? $this->getCurMenu()->getTitle() : 'Система Управления Сайтом '.$this->website->getSite()->getName();?></title>

	<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/bs/css/bootstrap.css?v=1.1">
	<!--<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/bs/css/bootstrap-select.css?v=1.0">-->
	<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/layout.css?v=2">
	<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/Ionicons/css/ionicons.css">
	<!--
	<link href="<?=$this->files?>css/font-awesome/css/font-awesome.css" rel="stylesheet">-->

<script src="<?=$this->files?>scripts/jquery.js"></script> 
</head>
<body id="body" >
<?php
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );
 if($this->user->getId() == 8005){
//echo print_r($this->get);
} ?>
<?php //if(in_array($this->user->getId(), array(27391,6741,25129,22609,34608,28767,26187,8985,7668,73412993,1,8005))) echo $this->render('poll/poll_box.tpl.php');
//array(33929,22832,31748,29397,24148,22699,8005,24150)
?>
<?php
$d1 = date('d-m'); 
$d2 = date('d-m', strtotime($this->user->getDateBirth()));
//echo $d1.'-'.$d2;
 if($d1 == $d2){?>
 <div>
<p style="
left:5px;
    top: 5px;
    width: 200px;
    height: 280px;
    position: fixed;
    background-size: contain;
    background-repeat: no-repeat;
    background-image: url(/img/happy.jpg);">
	<span style="font-size: 16px;    color: black;max-width: 200px;top: 300px;position: fixed;background: rgba(228, 228, 228, 0.79);">
	<?=$this->user->getMiddleName().' '.$this->user->getFirstName().'.</br>'.$this->user->getHappy(); ?>
	</span>
	</p>
	</div>
<?php } ?>

<div id="container">
        <div id="center-content">
            <div id="menu">
			<?php
			$menus =  wsActiveRecord::useStatic('Menu')->findAll(array('active'=> 1, 'type_id'=>2, 'parent_id' => 4,'section in (1,2,3,4,5)')); ?>
            <div> 
			<div class="admin_cms">
			<span style="margin-left: 15px;"><span style="color:red;">RED</span> CMS</span><br>
			<span style="font-size:12px;"><?=date($days[date( 'N' )] . ' d.m.Y')?> </span>
			</div>
<div class="wrapper_menu" onClick="show()">
						<p class="top_name" ><?=$this->trans->get('Страницы');?><i class="glyphicon glyphicon-menu-down"></i></p>
						<ul class="new_menu">
<?php foreach($menus as $menu){
if($menu->getSection() != 1) continue;
 if(!@$this->admin_rights[$menu->getId()]['view']) continue; ?>
	<li <?php echo ($menu->getStyle() ? ' class="' . $menu->getStyle() . '"' : ''); ?> >
		<a href="<?=$this->path.$menu->getUrl(); ?>" <?php echo ($menu->getTarget() ? ' target="' . $menu->getTarget() . '"' : ''); ?> title="<?=$menu->getPageIntro(); ?>" >
			<img src="<?=SITE_URL.$menu->getImage(); ?>" alt="<?php echo $menu->getMetaCustom(); ?>" style="width: 16px;" />
			<?=$menu->getName(); ?>
		</a>
	</li>
<?php } ?>
						</ul>
					</div>
					<div class="wrapper_menu" onClick="show()">
						<p class="top_name"><?=$this->trans->get('Магазин'); ?><i class="glyphicon glyphicon-menu-down"></i></p>
						<ul class="new_menu">
<?php foreach($menus as $menu){
if($menu->getSection() != 2) continue;
 if(!@$this->admin_rights[$menu->getId()]['view']) continue; ?>
	<li <?php echo ($menu->getStyle() ? ' class="' . $menu->getStyle() . '"' : ''); ?>>
		<a href="<?=$this->path.$menu->getUrl(); ?>" <?php echo ($menu->getTarget() ? ' target="' . $menu->getTarget() . '"' : ''); ?> title="<?=$menu->getPageIntro();?>" >
			<img src="<?=SITE_URL.$menu->getImage(); ?>" alt="<?=$menu->getMetaCustom(); ?>" style="width: 16px;"/>
		<?=$menu->getName(); ?>
		</a>
	</li>
<?php } ?>
						</ul>
					</div>
					<div class="wrapper_menu" onClick="show()">
						<p class="top_name"> <?=$this->trans->get('Пользователи'); ?><i class="glyphicon glyphicon-menu-down"></i></p>
						<ul class="new_menu">
<?php foreach($menus as $menu){
if($menu->getSection() != 3) continue;
 if(@$this->admin_rights[$menu->getId()]['view']){ ?>
			<li <?php echo ($menu->getStyle() ? ' class="' . $menu->getStyle() . '"' : ''); ?>>
	<a href="<?=$this->path.$menu->getUrl(); ?>" <?php echo ($menu->getTarget() ? ' target="' . $menu->getTarget() . '"' : ''); ?> title="<?=$menu->getPageIntro(); ?>" >
			<img src="<?=SITE_URL.$menu->getImage();?>" alt="<?=$menu->getMetaCustom(); ?>" style="width: 16px;" />
											<?=$menu->getName(); ?>
										</a>
									</li>
<?php } }?>
						</ul>
					</div>
					<div class="wrapper_menu" onClick="show()">
						<p class="top_name"><?=$this->trans->get('Администирование'); ?><i class="glyphicon glyphicon-menu-down"></i></p>
						<ul class="new_menu">
<?php foreach($menus as $menu){
if($menu->getSection() != 4) continue;
 if(@$this->admin_rights[$menu->getId()]['view']){ ?>
	<li <?php echo ($menu->getStyle() ? ' class="' . $menu->getStyle() . '"' : ''); ?>>
		<a href="<?php echo $menu->getUrl() == 'site' ? '/' : $this->path.$menu->getUrl(); ?>" <?php echo ($menu->getTarget() ? ' target="' . $menu->getTarget() . '"' : ''); ?> title="<?=$menu->getPageIntro(); ?>" >
			<img src="<?=SITE_URL.$menu->getImage(); ?>" alt="<?=$menu->getMetaCustom(); ?>" style="width: 16px;" >
			<?=$menu->getName(); ?>
		</a>
	</li>
<?php }} ?>
						</ul>
					</div>
	<div class="wrapper_menu" onClick="show()">
		<p class="top_name"><?=$this->trans->get('Служебное'); ?><i class="glyphicon glyphicon-menu-down"></i></p>
		<ul class="new_menu">
<?php foreach($menus as $menu){
if($menu->getSection() != 5) continue;
 if(!$this->admin_rights[$menu->getId()]['view']) continue; ?>
	<li <?php echo ($menu->getStyle() ? ' class="' . $menu->getStyle() . '"' : ''); ?> >
		<a href="<?=$this->path.$menu->getUrl(); ?>" <?php  echo ($menu->getTarget() ? ' target="' . $menu->getTarget() . '"' : ''); ?> title="<?=$menu->getPageIntro(); ?>">
			<img src="<?=SITE_URL.$menu->getImage(); ?>" alt="<?=$menu->getMetaCustom(); ?>" style="width: 16px;">
			<?=$menu->getName(); ?>
		</a>
	</li>
<?php } ?>
		</ul>
	</div>
	<div style="float: right;
    position: relative;
    padding: 5px;
    margin-right: 5px;">
	 <span style="font-size: 12px;font-weight: bold;"><?=$this->user->getMiddleName()?><span class="hidden-md-down"> <?=$this->user->getFirstName()?></span></span>
	<img src="<?=$this->user->getLogo() ? $this->user->getLogo() : Config::findByCode('admin_logo_folder')->getValue().'error.png'?>" class="wd-32 rounded-circle" style="width: 30px;vertical-align: middle;border-style: none; border-radius: 50%;" alt=""></div>
<div style="clear: both;"></div>
</div>
</div>
<?=$this->message;?>
<div id="content">
<?=$this->render($this->middle_template)?>
<?=$this->render('page/footer.tpl.php')?>
</div>
</div>
        <!--end center-content-->
</div>
<!--end container -->
<p id="back-top"><a href="#top"><span></span></a></p>

<script  src="<?=$this->files?>css/bs/js/bootstrap.min.js"></script>

<!--<script  type="text/javascript"  src="<?=$this->files?>css/bs/js/bootstrap-select.js"></script>-->
<!--<script  type="text/javascript"  src="<?=$this->files?>css/bs/js/jquery-ui.js"></script>-->
<script>
$(document).ready(function(){
$("[data-tooltip='tooltip']").tooltip();
	$("#back-top").hide();
		$(window).scroll(function () {
			if ($(this).scrollTop() > 300) {
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
//открытие всплывающего окна нова почта
function fopen(header = '', body = '', footer = ''){
if(body) $('#popup').html(body);
if(header) $('#myModalLabel').html(header);
if(footer){ $('#myModalFooter').html(footer);}else{ $('#myModalFooter').html('<button class="btn btn-secondary pd-x-20" data-dismiss="modal" aria-hidden="true">Закрыть</button>');}
$('#myModalMessage').modal('show');
}
//закрытие всплывающего окна нова почта
function FormClose(){$('#myModalMessage').modal('hide');}	

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