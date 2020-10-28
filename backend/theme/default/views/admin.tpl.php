<!DOCTYPE html">
<html lang="ru">
<head>
	<meta name="robot" content="no-index,no-follow"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content=" initial-scale=1.0, maximum-scale=1.0,  user-scalable=no"/>
<title><?php echo ($this->getCurMenu()->getTitle()) ? $this->getCurMenu()->getTitle() : 'Система Управления Сайтом '.$this->website->getSite()->getName();?></title>

	<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/bs/css/bootstrap.css?v=1.4.7">
        
        <link href="<?=$this->files?>views/template/lib/select2/css/select2.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/layout.css?v=3.36.4">
        <link rel="stylesheet" type="text/css" href="<?=$this->files?>views/template/lib/spin_kit/spinkit.css">
	<link rel="stylesheet" type="text/css" href="<?=$this->files?>css/ionicons/css/ionicons.css">
	
	<link href="<?=$this->files?>css/font-awesome/css/font-awesome.css" rel="stylesheet">

<script src="<?=$this->files?>scripts/jquery.js"></script> 
</head>
<body>
   
<?php

if($this->user->id == 8005){
    
    echo $this->render('poll/poll_box.tpl.php');?>
    <!-- <div id="video-bg">
    <video width="100%" height="auto" preload="auto" autoplay="autoplay"
    loop="loop" >
        <source src="/img/admin/video.mp4" type="video/mp4"></source>
    </video>
</div>-->
    
<?php }
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );

//echo print_r($this->get);

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

            <!--Название сайта и кнопка раскрытия меню для мобильных-->
   <!-- Классы navbar и navbar-default (базовые классы меню) -->
   <nav class="navbar navbar-default navbar-fixed-top" >
  <!-- Контейнер (определяет ширину Navbar) -->
  <div class="container">
    <!-- Заголовок -->
    <div class="navbar-header">
      <!-- Кнопка «Гамбургер» отображается только в мобильном виде (предназначена для открытия основного содержимого Navbar) -->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- Бренд или название сайта (отображается в левой части меню) -->
      <a class="navbar-brand" href="/">
          <img src="<?=$this->files?>img/logo/logo.png"  style="height: 36px;padding-bottom: 7px;margin-top: -8px;"alt="RED">
      </a>
    </div>
    <!-- Основная часть меню (может содержать ссылки, формы и другие элементы) -->
    <div class="collapse navbar-collapse" id="navbar-main">
      <!-- Содержимое основной части -->
      <ul class="nav navbar-nav">
        <?php              
        $section = AdminSection::find('AdminSection');
         foreach ($section as $s) { ?>
        <!-- Выпадающий список -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$s->name?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
              <?php 
              foreach (Menu::find('Menu',['active'=>1, 'type_id'=>2, 'section'=>$s->id], ['sequence'=>'ASC']) as $m) {
                                if($this->admin_rights[$m->id]['view']){ ?>
              <li><a href="<?=$this->path.$m->getUrl().'/'?>" <?=$m->getTarget()?' target="'.$m->getTarget().'"':''?> title="<?=$m->getPageIntro()?>" ><img src="<?=$m->getImage()?>" alt="<?=$m->getMetaCustom()?>" style="width: 16px;" />
			<?=$m->getName(); ?></a></li>
              
              <?php } 
              
                                }?>
          </ul>
        </li>
                        
     <?php   }                ?>
        
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li style="padding-top: 15px;padding-right: 15px;"><?=$this->user->getMiddleName()?></li>
        <li style="padding-top: 10px;"><img src="<?=$this->user->getLogo() ? $this->user->getLogo() : Config::findByCode('admin_logo_folder')->getValue().'error.png'?>" class="wd-32 rounded-circle" style="width: 36px; border-radius: 50%;" alt=""></li>
      </ul>
    </div>
     </div>

</nav>
  <!-- <div style="display: none; position: fixed; top: 15px; left: 20px;z-index: 1031;">
       На сайте: <span class="badge badge-primary badge-pill" id="user_site"></span>
   </div>-->
<div id="container" class="container">
    
<?=$this->message;?>
<div id="content">
<?=$this->render($this->middle_template)?>
<?=$this->render('page/footer.tpl.php')?>
</div>
        <!--end center-content-->
</div>
<!--end container -->
<p id="back-top"><a href="#top"><span></span></a></p>

<script  src="<?=$this->files?>css/bs/js/bootstrap.min.js"></script>
<script src="<?=$this->files?>views/template/lib/select2/js/select2.min.js"></script>
<!--<script  src="<?=$this->files?>css/bs/js/bootstrap-select.js"></script>-->
<!--<script   src="<?=$this->files?>css/bs/js/jquery-ui.js"></script>-->
  <script src="<?=$this->files?>views/template/js/download_excel.js?v=1.0"></script>
<script>
     $(function(){

        'use strict';
        // F1();
        $('.select2').select2({
          minimumResultsForSearch: Infinity
        });
        $('.select2-show-search').select2({
          minimumResultsForSearch: ''
        });
   
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
 function F1(){
        $.get('/ajax/usersite/', function(data){$('#user_site').html(data);});
       // setTimeout(F1, 2000); // Запускаем эту же функцию 2-й раз, потом 3-й, 4-й и так до бесконечности
      //  return false;
    }
 
//открытие всплывающего окна нова почта
function fopen(header = '', body = '', footer = ''){
if(body) $('#popup').html(body);
if(header) $('#myModalLabel').html(header);
if(footer){ $('#myModalFooter').html(footer);}else{ $('#myModalFooter').html('<button class="btn btn-secondary pd-x-20" data-dismiss="modal" aria-hidden="true">Закрыть</button>');}
$('#myModalMessage').modal('show');
}
//закрытие всплывающего окна нова почта
function FormClose(){$('#myModalMessage').modal('hide');}	

function LoadGetForm(file){
        $.ajax({
			url: '/admin/nowamail/',
			type: 'POST',
			dataType: 'json',
			data: {file: file, metod:'gel_email_load_form'},
                        beforeSend: function(){
                            $("#result_load_form").html('<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>');
                    //$('<div/>', { id: 'foo', class: 'modal-backdrop fade show', html: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>' }).html('body');
                },
			success: function (res) {
                            $("#result_load_form").html(res);
				//console.log(res);
				//fopen('История отправлленых писем пользователю ID:'+id, res.message, '<button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>');
                },
				error: function(e){
				console.log(e);
				}
		});
       return false;
    
}
function show(){return false;}

  
        
        function download(table){
           // var table = String(t_id);
            return  tableToExcel(table,  String(table),  String(table)+'.xls');
        }
function setLang(lang, url = null) {
    if(!url){ url = '<?=$_REQUEST['route']?>'; }
if(lang !== '<?=Registry::get('lang')?>'){
$.post('/ajax/setlang/', { lang: lang, ur: url});
		}
}

</script>
</body>
</html>