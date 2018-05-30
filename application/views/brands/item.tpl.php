	<div class="col-xl-12">
		<div class="b-brand-wrapper text-center bg-white"  >
			<img  style="max-width: 150px;" src="<?=$this->getBrand()->getImage();?>">
			<div class="p-2 text-justify"><?=$this->getBrand()->getText();?></div>
		</div>
	</div>
<?=$this->articlesHtml;?>
<div class="col-xl-12 text-center pt-2">
<a href="<?=$this->getBrand()->getPathFind()?>"><button class="btn btn-danger" ><?=$this->trans->get('Показать все товары этого бренда')?></button></a>
</div>