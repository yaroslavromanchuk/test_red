<hr>
	<div class="row">
	<hr>
        <div class="col-xs-12 col-sm-12">
            <a href="/blog/?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog"><span style="color: #5f5e5f;">BLOG</span></a>
        </div>
        <?php
        foreach ($this->blog as $b){ ?>
        <div class="col-6 p-1">
        <a href="<?=$b->getPath()?>?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog">
	<img  data-src="/storage<?=$b->getImage()?>" alt="<?=$b->getPostName()?>" style="max-width: 100%">
	<span><?=$b->getPostName()?></span>
	</a>
        </div>
       <?php }
        ?>
	</div>
<hr>