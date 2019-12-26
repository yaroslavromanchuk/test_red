<?php if($this->result_count > 0){ ?>
<div class="modal fade comment-form"  tabindex="-1" role="dialog" id="comment-modal_article" aria-labelledby="id_comment_modal_article">
<div  class="modal-dialog modal-lg">
	<div  class="modal-content">
	<div class="modal-header">
	<h4 class="modal-title" id="id_comment_modal_article"><?=$this->trans->get('Быстрый просмотр товара');?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</div>
	<div class="modal-body" id="view_article"></div>
	</div>
</div>
</div>
<div class="row articles-row m-auto w-100">
<?php
$i = 1;
foreach($this->articles as $article){
    $article->getSpecNakl();
    echo $article->getSmallBlockCachedHtml(true);
    $i++;
} ?>
</div>
<div class="row m-auto">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center pt-3" >
<input type="hidden" class="items_on_page" name="items_on_page" value="<?=$_COOKIE['items_on_page']?$_COOKIE['items_on_page']:32?>" />
<div class="btn-group mb-3"  data-toggle="buttons-checkbox">
                    <button onclick="return but_val_new($(this))" type="button" class="btn btn-secondary <?php if($_COOKIE['items_on_page']==32 or !$_COOKIE['items_on_page'])  echo 'active' ?>" <?php if($_COOKIE['items_on_page']==32 or !$_COOKIE['items_on_page'])  echo 'disabled' ?> value="32">30</button>
                    <button onclick="return but_val_new($(this))" type="button" class="btn btn-secondary <?php if($_COOKIE['items_on_page']==60)  echo 'active' ?>" <?php if($_COOKIE['items_on_page']==60)  echo 'disabled' ?> value="60">60</button>
                    <button onclick="return but_val_new($(this))" type="button" class="btn btn-secondary <?php if($_COOKIE['items_on_page']==90)  echo 'active' ?>" <?php if($_COOKIE['items_on_page']==90)  echo 'disabled' ?> value="90">90</button>
                    <button onclick="return but_val_new($(this))" type="button" class="btn btn-secondary <?php if($_COOKIE['items_on_page']==120)  echo 'active' ?>" <?php if($_COOKIE['items_on_page']==120) echo 'disabled' ?> value="120">120</button>
</div>
 <?php 
if($this->total_pages){
$limit = 4;
	$text = '';
	$page = ($this->cur_page+1);
$text.='<nav aria-label="Page navigation example">
	<ul class="pagination pagination-sm pagination-dark mb-0   justify-content-center" >';
	if ($page > 1) {
        $text .= '<li class="page-item"><a class="page-link" href=""  onclick="return ToPage(1)" aria-label="Previous"><span class="previous_all_page_link" aria-hidden="true"></span></a></li>';
		$text.='<li class="page-item"><a class="page-link"  href=""  onclick="return ToPage('.($page - 1).')"><span class="previous_page_link" aria-hidden="true"></span></a></li>';
    } else {
         $text .= '<li class="page-item disabled"><a class="page-link" aria-label="Previous"><span class="previous_all_page_link" aria-hidden="true"></span></a></li>';
		$text.='<li class="page-item disabled"><a class="page-link" ><span class="previous_page_link" aria-hidden="true"></span></a></li>';
    }
	 $start = 1;
    $end = $this->total_pages;
	 if ($page > $limit) {
        $start = $page - $limit;
    }
	
    if (($page + $limit) < $this->total_pages) {
        $end = $page + $limit;
    }
	
	for ($i = $start; $i <= $end; $i++) {
        if ($i == $page) {
            $text .= '<li class="page-item active"><a class="page-link" style="pointer-events: none; 
    cursor: default;" >'.$i.'<span class="sr-only">(current)</span></a></li>';
        } else {
            $text .= '<li class="page-item"><a class="page-link" href="" onclick="return ToPage('.$i.')">' . $i . '</a></li>';
        }
    }
	 if ($page == $this->total_pages) {
	 	$text.='<li class="page-item disabled"><a class="page-link" ><span class="next_page_link" aria-hidden="true"></span></a></li>';
	   $text .= '<li class="page-item disabled"><a class="page-link"  aria-label="Next"><span class="next_all_page_link" aria-hidden="true"></span></a></li>';
    } else {
	$text.='<li class="page-item"><a class="page-link" href="" onclick="return ToPage('.($page+1).')"><span class="next_page_link" aria-hidden="true"></span></a></li>';
	$text .= '<li class="page-item"><a class="page-link" href="" onclick="return ToPage('.$this->total_pages.')" aria-label="Previous"><span class="next_all_page_link" aria-hidden="true"></span></a></li>';
    }
	$text .='</ul>
	</nav>';
echo $text;
}
?>
</div>
</div>
<?php }else{ ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center p-3">
        <div class="alert alert-danger" role="alert"><?=$this->trans->get('message_no_articles');?></div>
    </div>
</div>
 <?php   } ?>