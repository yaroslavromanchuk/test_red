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
<ul class="row articles-row">
<?php foreach($this->articles as $article){
    $article->getSpecNakl();
    echo $article->getSmallBlockCachedHtml();
} ?>
</ul>
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center" style="padding-top: 10px;">
<input type="hidden" class="items_on_page" name="items_on_page" value="<?=$_SESSION['items_on_page']?$_SESSION['items_on_page']:32?>" />

<div class="btn-group mb-3"  data-toggle="buttons-checkbox">
                    <button onclick="return but_val($(this))" type="button" class="btn btn-secondary <?php if(@$_SESSION['items_on_page']==32 or !@$_SESSION['items_on_page'])  echo 'active' ?>" <?php if(@$_SESSION['items_on_page']==32 or !@$_SESSION['items_on_page'])  echo 'disabled' ?> value="32">30</button>
                    <button onclick="return but_val($(this))" type="button" class="btn btn-secondary <?php if(@$_SESSION['items_on_page']==60 or !@$_SESSION['items_on_page'])  echo 'active' ?>" <?php if(@$_SESSION['items_on_page']==60)  echo 'disabled' ?> value="60">60</button>
                    <button onclick="return but_val($(this))" type="button" class="btn btn-secondary <?php if(@$_SESSION['items_on_page']==90 or !@$_SESSION['items_on_page'])  echo 'active' ?>" <?php if(@$_SESSION['items_on_page']==90)  echo 'disabled' ?> value="90">90</button>
                    <button onclick="return but_val($(this))" type="button" class="btn btn-secondary <?php if(@$_SESSION['items_on_page']==120 or !@$_SESSION['items_on_page'])  echo 'active' ?>" <?php if(@$_SESSION['items_on_page']==120) echo 'disabled' ?> value="120">120</button>
</div>
<?php if($this->ws->getCustomer()->getId() == 8005){
//echo $this->cur_page;
//echo '<br>'.$this->total_pages;
if(@$this->total_pages){
$limit = 4;
	$text = '';
	$page = ($this->cur_page+1);
$text.='<nav aria-label="Page navigation example">
	<ul class="pagination pagination-dark mb-0" style="display: inline-flex;">';
	if ($page > 1) {
        $text .= '<li class="page-item"><a class="page-link" href=""  onclick="return ToPage(1)" aria-label="Previous"><span aria-hidden="true"><<</span></a></li>';
		$text.='<li class="page-item"><a class="page-link"  href=""  onclick="return ToPage('.($page - 1).')"><span aria-hidden="true"><</span></a></li>';
    } else {
         $text .= '<li class="page-item disabled"><a class="page-link" aria-label="Previous"><span aria-hidden="true"><<</span></a></li>';
		$text.='<li class="page-item disabled"><a class="page-link" ><span aria-hidden="true"><</span></a></li>';
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
            $text .= '<li class="page-item active"><a class="page-link" disabled >'.$i.'<span class="sr-only">(current)</span></a></li>';
        } else {
            $text .= '<li class="page-item"><a class="page-link" href="" onclick="return ToPage('.$i.')">' . $i . '</a></li>';
        }
    }
	 if ($page == $this->total_pages) {
	 	$text.='<li class="page-item disabled"><a class="page-link" ><span aria-hidden="true">></span></a></li>';
	   $text .= '<li class="page-item disabled"><a class="page-link"  aria-label="Next"><span aria-hidden="true">>></span></a></li>';
    } else {
	$text.='<li class="page-item"><a class="page-link" href="" onclick="return ToPage('.($page+1).')"><span aria-hidden="true">></span></a></li>';
	$text .= '<li class="page-item"><a class="page-link" href="" onclick="return ToPage('.$this->total_pages.')" aria-label="Previous"><span aria-hidden="true">>></span></a></li>';
    }
	$text .='</ul>
	</nav>';
echo $text;
}

 ?> 
 <!--
<nav aria-label="Page navigation example">
  <ul class="pagination pagination-dark mg-b-0">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav>-->


<?php }else{ ?>
            <ul class="finder_pages">
                <?php
                $limit = 1; 								//how many items to show per page
                $page = $this->cur_page + 1;
                if($page){
                    $start = ($page - 1) * $limit; 			//first item to display on this page
                }else{
                    $start = 0;								//if no page var is given, set start to 0
                }
                /* Setup page vars for display. */
                //if ($page == 0) $page = 1;					//if no page var is given, default to 1.
                $prev = $page - 1;							//previous page is page - 1
                $next = $page + 1;							//next page is page + 1
                $lastpage = ceil(($this->total_pages)/$limit);		//lastpage is = total pages / items per page, rounded up.
                $lpm1 = $lastpage - 1;						//last page minus 1
                $adjacents = 2;
                $pagination = '';
                if($lastpage > 1){
                    //previous button
                    if ($page > 1){
                        $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$prev.', 1)"><img src="/img/icons/left.png" alt="Обратно" style="top:-2px;left: -2px;"></a></li>';
                    }else{
                        $pagination.= '';
                    }
                    $counter = 0;
                    //pages
                    if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
                    {
                        for ($counter = 1; $counter <= $lastpage; $counter++){
                            if ($counter == $page)
                                $pagination.= '<li class="page-skip"><a href="#" class="selected" onclick="return prepareSearchToPage('.$counter.', 1)">  '.$counter.'  </a></li>';
                            else
                                $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$counter.',1)"> '.$counter.' </a></li>';

                        }
                    }
                    elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
                    {
                        //close to beginning; only hide later pages
                        if($page < 1 + ($adjacents * 2))
                        {
                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                            {
                                if ($counter == $page)
                                    $pagination.= '<li class="page-skip"><a href="#" class="selected" onclick="return prepareSearchToPage('.$counter.',1)"> '.$counter.' </a></li>';
                                else
                                    $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$counter.',1)"> '.$counter.' </a></li>';
                            }
                            $pagination.= '<li class="page-skip">...</li>';
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.@$lpm1.')"> '.@$lpm1.' </a></li>';
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.@$lastpage.')"> '.@$lastpage.' </a></li>';
                        }
                        //in middle; hide some front and some back
                        elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                        {
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage(1 , 1)"> 1 </a></li>';
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage(2 , 1)"> 2 </a></li>';
                            $pagination.= '<li class="page-skip">...</li>';
                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                            {
                                if ($counter == $page)
                                    $pagination.= '<li class="page-skip"><a href="#" class="selected" onclick="return prepareSearchToPage('.$counter.' , 1)"> '.$counter.' </a></li>';
                                else
                                    $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$counter.' , 1)"> '.$counter.' </a></li>';
                            }
                            $pagination.= '<li class="page-skip">...</li>';
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$lpm1.')"> '.$lpm1.' </a></li>';
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$lastpage.')"> '.$lastpage.' </a></li>';
                        }
                        //close to end; only hide early pages
                        else
                        {
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage(1 , 1)"> 1 </a></li>';
                            $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage(2 , 1)"> 2 </a></li>';
                            $pagination.= '<li>...</li>';
                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                            {
                                if ($counter == $page)
                                    $pagination.= '<li class="page-skip"><a href="#" class="selected" onclick="return prepareSearchToPage('.$counter.' , 1)"> '.$counter.' </a></li>';
                                else
                                    $pagination.= '<li class="page-skip"><a href="#" onclick="return prepareSearchToPage('.$counter.' , 1)"> '.$counter.' </a></li>';
                            }
                        }
                    }
                    //next button
                    if ($page < $counter - 1) 
                        $pagination.= '<li class="page-skip"><a href="#"  onclick="return prepareSearchToPage('.$next.', 1)"><img src="/img/icons/right.png" alt="далее" style="top: -2px;left: 2px;"></a></li>';
                    else
                        $pagination.= '';

                }
                echo $pagination;
                ?>
            </ul>
			<?php } ?>
</div>
</div>