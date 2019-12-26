<?php 
if($this->totalPages){
 $url = explode('?', $_SERVER['REQUEST_URI']);
    if (count($url) == 2) {
        $ur = $url[0];
        $get = '?' . $url[1];
    } else {
        $ur = $_SERVER['REQUEST_URI'];
        $get = '';
    }
	
    $pager = preg_replace('/\/page\/\d*/', '', $ur) . '/page/';
	
$limit = 4;
	$text = '';
$text.='<nav aria-label="..." style="text-align:  center;">
	<ul class="pagination">';
	if ($this->page > 1) {
        $text .= '<li><a href="' . $pager . '1' . $get . '" aria-label="Previous"><span aria-hidden="true"><<</span></a></li>';
		$text.='<li><a href="' . $pager . ($this->page - 1) . $get . '"><span aria-hidden="true"><</span></a></li>';
    } else {
         $text .= '<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true"><<</span></a></li>';
		$text.='<li class="disabled"><a href="#"><span aria-hidden="true"><</span></a></li>';
    }
	 $start = 1;
    $end = $this->totalPages;
	 if ($this->page > $limit) {
        $start = $this->page - $limit;
    }
	
    if (($this->page + $limit) < $this->totalPages) {
        $end = $this->page + $limit;
    }
	
	for ($i = $start; $i <= $end; $i++) {
        if ($i == $this->page) {
            $text .= '<li class="active"><a href="#">'.$i.'<span class="sr-only">(current)</span></a></li>';
        } else {
            $text .= '<li><a href="' . $pager . $i . $get . '">' . $i . '</a></li>';
        }
    }
	 if ($this->page == $this->totalPages) {
	 	$text.='<li class="disabled"><a href="#"><span aria-hidden="true">></span></a></li>';
	   $text .= '<li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">>></span></a></li>';
    } else {
	$text.='<li><a href="' . $pager . ($this->page + 1) . $get . '"><span aria-hidden="true">></span></a></li>';
	$text .= '<li><a href="' . $pager . $this->totalPages . $get . '" aria-label="Previous"><span aria-hidden="true">>></span></a></li>';
    }
	$text .='</ul>
	</nav>';
echo $text;
echo '<p style="font-size:  100%;text-align:  center;margin:  auto;">Страниц: '.$this->totalPages.', записей: '.$this->count.' </p>';
}




