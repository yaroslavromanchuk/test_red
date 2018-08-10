<link type="text/css" href="/css/findex.css?v=1.1" rel="stylesheet"/>
<input type="hidden" name="current_page" id="current_page" value="1"/>
<input type="hidden" name="total_pages" id="total_pages" value="<?=$this->total_pages?>"/>
<div id="filter"  class="filter mt-1 col-xs-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 p-3" ><?=$this->render('finder/filter.tpl.php');?></div>
<!--result -->
<div id="result" style="display: table;" class="col-sm-12 col-md-12 col-lg-10 col-xl-10"><?=$this->result;?></div>
<!--exit result -->