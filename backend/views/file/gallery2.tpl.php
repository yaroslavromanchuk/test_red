<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

	<p><img src="<?php echo SITE_URL;?>/img/icons/upload-small.png" alt="Pagina toevoegen" width="24" height="24" /><a href="<?php echo $this->path;?>gallery/upload/">Afbeelding uploaden</a></p>


	<table id="imagesgallerylist" cellpadding="3" cellspacing="0">
	<?php 
		$row = 'row1';
		foreach($this->getImages() as $image )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td><a href="<?php echo $this->path;?>gallery/delete/id/<?php echo $image->getId();?>/" onclick="return confirm('Weet u zeker dat deze afbeelding kan worden verwijderd?')"><img src="/img/icons/remove-small.png" alt="Afbeelding verwijderen" width="24" height="24" /></a></td>
			<td><img src="<?php echo $image->getUrl();?>" alt="" /></td>
		</tr>
	<?php
		}
	?>		
	</table>
