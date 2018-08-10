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
<?php
foreach($this->desires as $article) 
{
    $article->getSpecNakl();
    echo $article->getSmallBlockCachedHtml();?>
<?php } ?>
</ul>
