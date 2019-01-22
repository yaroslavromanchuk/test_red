<link type="text/css" href="/css/findex.css?v=1" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="/css/stores/fm.revealator.jquery.min.css"/>
<?php
$text_trans_blog = explode(',', $this->trans->get('Недавнее,Ваше Имя,Подписаться,Смотреть')); 
if(Registry::get('device') == 'computer' or ($_COOKIE['mobil'] and $_COOKIE['mobil'] == 10)){ 
$desctop = true;
}else{
$desctop = false;
}
 //echo $this->getCurMenu()->getPageBody();
 ?>
<div class="row mx-auto">
<div class="col-xl-2 col-lg-2  d-none d-lg-block d-xl-block p-2">
    <div class="card">
        <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center "><?=$text_trans_blog[0]?></h6>
  </div>
        <div class="card-body">
            <?php 
		$d = date("Y-m-d H:i:s");
		$post = Blog::find('Blog',["public"=>1, "ctime < '$d'"], [], [10]);
		?>
	<ul class="list-unstyled">
		<?php foreach ($post as $item) { ?>
		<li style="list-style-type: none;" class="media my-1">
		<img src="/storage<?=$item->getImage(); ?>" class="align-self-center mr-3" style="width:35px;">
		<div class="media-body">
		<a href="<?=$item->getPath()?>">
		<?=$item->getPostName();?>
		</a>
		</div>
		</li>
		<?php } ?>
				</ul>
        </div>
                                </div>
</div>
<div class="col-md-12 col-xl-8 col-lg-8 p-2">
     <div class="card">
         <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center mb-0">
        <div class="btn-group" role="group" aria-label="Basic example">
<?php
		foreach (BlogCategory::getAllCategory() as $value) { ?>
		<a href="<?=$value->getPath()?>" class="btn btn-secondary"><?=$value->getName()?></a>
		<?php } ?>
</div>
    </h6>
  </div>
         <div class="card-body">
    <div class="row m-auto">
		<style>
		.media p{width:100%;}
		p img{display:none;}
		.media img{
                max-width:500px;
		width:100%;
                }
		</style> 
<?php
$i = 0;
foreach ($this->blog as $value) { ?>
                <div class="col-md-12 <?php if($i != 0){ echo ' revealator-zoomin revealator-duration5  revealator-once';}?> ">
		<div class="row my-4">
                    <?php if($i % 2 === 0){ ?> 
                        <div class="col-md-6 media text-right">
			<a href="<?=$value->getPath()?>">
			<img src="/storage<?=$value->getImage()?>">
			</a>
			</div>
			<div class="col-md-6">
                            <div>
                        <span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$value->getUtime();?></span>|
			<span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$value->getAutor();?></span>
                        </div>
                            <h3 style="font-size: 2rem;"><a href="<?=$value->getPath(); ?>"><?=$value->getPostName();?></a></h3>
                            <div>
			<span><?=$value->getPreviewPost()?></span>
			<a class="btn btn btn-outline-dark btn-lg" href="<?=$value->getPath()?>">
				<?=$text_trans_blog[3]?>
			</a>
                        </div>
			</div>
                        <?php }else{ ?>
                    <div class="col-md-6">
                        <div>
                        <span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$value->getUtime();?></span>|
			<span style="color: darkgrey;font-size: 12px;margin-left: 1%;margin-right: 1%;"><?=$value->getAutor();?></span>
                        </div>
                        <h3 style="font-size: 2rem;"><a href="<?=$value->getPath(); ?>"><?=$value->getPostName();?></a></h3>
                         
			 <div>
			<span><?=$value->getPreviewPost()?></span>
			<a class="btn btn btn-outline-dark btn-lg" href="<?=$value->getPath()?>">
				<?=$text_trans_blog[3]?>
			</a>
                        </div>
			</div>
                        <div class="col-md-6 media text-right">
			<a href="<?=$value->getPath()?>">
			<img src="/storage<?=$value->getImage()?>">
			</a>
			</div>
			
                   <?php } ?>
			
			</div>
</div>					
			<?php $i++;	} ?>
</div>
         </div>
    </div>
</div>
<div class="col-lg-2 col-xl-2 d-none d-lg-block d-xl-block  text-center">
    <div class="card">
         <div class="card-header w-100">
    <h6 class="title text-uppercase font-weight-bold text-center ">FASHION RADIO</h6>
  </div>
        <div class="card-body">
            	<div>
	<img src="/images/ofr_btn2.png" style="cursor: pointer; width: 100px;" onclick="window.open('http://ofr.fm/air/','','toolbar=no, location=no, scrollbars=no, resizable=no, top=100, left=100, width=360, height=593'); return false;" >
	</div>
	<div style="margin-top:10px;" class="fb-like-box" data-href="https://www.facebook.com/pages/RED-UA/148503625241218" data-width="198"
             data-height="400" data-show-faces="true" data-stream="false" data-header="true">    
        </div>
	<iframe id="fr" style="overflow: hidden; height: 100px; width: 198px; border: 0pt none;" src="https://www.youtube.com/subscribe_widget?p=SmartRedShopping"  scrolling="no" frameborder="0"></iframe>

        </div>
        </div>
    
	</div>
</div>

						<?php 

if ($this->allcount > $this->onpage) {
?>
<div class="row m-auto">
<div class="col-lg-12 text-center">
	<ul  class="finder_pages">
		<?php
	if ($this->page > 1) {
?>
		<li class="page-skip"><a href="&page=<?=$this->page-1;?>"><span style="padding:5px;"><< </span></a></li>
<?php
	} ?>
	<?php
	$b = '';
	$st = ceil($this->allcount/10);
	$q = 1;
	$f1 = 0;
	$f2 = 0;
for($i = 1;$i<=$st; $i++) {
if($i == $this->page)  {$b = 'class="selected"';}else{ $b = '';}
if($st > 10){
if($i < $this->page - 4 and $i < 4 ){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i < ($this->page - 3) and $f1 == 0){
$f1 = 1;
echo '<li><span style="padding:5px;">...</span></li>';
}elseif($this->page == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 1) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 2) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page - 3) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 1) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 2) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if(($this->page + 3) == $i){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i > ($this->page + 3) and $f2 == 0 ){
echo '<li class="page-skip"><span style="padding:5px;">...</span></li>';
$f2 = 1;
}else if($i == $st){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}else if($i == ($st - 1)){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
else if($i == ($st - 2)){
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}else{
echo '<li class="page-skip"><a href="&page='.$i.'" style="text-decoration: none;" '.$b.'>'.$i.'</a></li>';
}
}
	?>
		<?php
	if ($this->page < ceil($this->allcount / $this->onpage)) {
?>
			<li class="page-skip"><a href="&page=<?=$this->page + 1;?>"><span style="padding:5px;"> >></span></a></li>
<?php
	}
?>
	</ul>
	</div>
</div>
<?php
}
?>
<script  src="/js/stores/fm.revealator.jquery.min.js?v=1.0"></script>
<script>
(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
</script>