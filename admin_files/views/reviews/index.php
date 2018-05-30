<img src="<?=SITE_URL.$this->getCurMenu()->getImage()?>" alt="" class="page-img">
<h1><?=$this->getCurMenu()->getTitle()?></h1>
<div class="tabbable">
  <ul class="nav nav-tabs">
    <li><a href="#" onclick="view_reviews(1); return false;" style="font-size: 16px;"  data-toggle="tab">Отзывы<?php if($this->c_rew['otziw'] > 0) {?><span style="    position: absolute;
    top: 0px;
    font-size: 12px;
    background: #333333;
    color: white;
    border-radius: 10px;
    min-width: 15px;
    text-align: center;"><?=$this->c_rew['otziw']?><?php }?></span></a></li>
    <li><a href="#" onclick="view_reviews(3); return false;" style="font-size: 16px;" data-toggle="tab">Ответы<?php if($this->c_rew['otvet'] > 0) {?><span style="    position: absolute;
    top: 0px;
    font-size: 12px;
    background: #333333;
    color: white;
    border-radius: 10px;
    min-width: 15px;
    text-align: center;"><?=$this->c_rew['otvet']?><?php }?></span></a></li>
	<li><a href="#" onclick="view_reviews(2); return false;" style="font-size: 16px;" data-toggle="tab">Скрытые<?php if($this->c_rew['hide'] > 0) {?><span style="    position: absolute;
    top: 0px;
    font-size: 12px;
    background: #333333;
    color: white;
    border-radius: 10px;
    min-width: 15px;
    text-align: center;"><?=$this->c_rew['hide']?><?php }?></span></a></li>
  </ul>
</div>
<div  class="tab-content"></div>
<script type="text/javascript">
function view_reviews(e){
$.get('/admin/reviews-edit/metod/view/id/'+e,function (data) { $('.tab-content').html(data);});
}
</script>