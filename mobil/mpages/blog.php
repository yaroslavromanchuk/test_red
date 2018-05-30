<hr>
	<div class="row">
	<hr>
	<ul class="blog_mobi">
	<li>
	<p style="margin-top: -25px;"><a href="/blog/?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog"><span style="color: #5f5e5f;">BLOG</span></a></p>
	</li>
	<li>
	<div style="display: inline-block;padding-bottom: 10px;">
	<a href="<?=$this->blog[0]->getPath()?>?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog">
	<img  src="/storage<?php echo $this->blog[0]->getImage() ?>" alt="<?=$this->blog[0]->getPostName()?>">
	<span><?=$this->blog[0]->getPostName()?></span>
	</a>
	</div>
	</li>
	<li>
	<div style="display: inline-block;padding-bottom: 10px;">
	<a href="<?=$this->blog[1]->getPath()?>?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog">
	<img  src="/storage<?=$this->blog[1]->getImage() ?>" alt="<?=$this->blog[1]->getPostName()?>">
	<span><?=$this->blog[1]->getPostName()?></span>
	</a>
	</div>
	</li>
	<li>
	<div style="display: inline-block;">
	<a href="<?=$this->blog[2]->getPath()?>?utm_source=blog&utm_medium=link&utm_content=Blog&utm_campaign=Blog">
	<img  src="/storage<?=$this->blog[2]->getImage() ?>" alt="<?=$this->blog[2]->getPostName()?>">
	<span><?=$this->blog[2]->getPostName()?></span>
	</a>
	</div>
	</li>
	</ul>
	</div>
	<hr>