<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

	<p><img src="<?php echo SITE_URL;?>/img/icons/upload-small.png" alt="Pagina toevoegen" width="24" height="24" /><a href="<?php echo $this->path;?>images/upload/">Afbeelding uploaden</a></p>

	<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2">Handeling</th>
			<th>URL van de afbeelding</th>
		</tr>
	<?php 
		$row = 'row1';
		foreach($this->getImages() as $image )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $image->getUrl();?>" target="_blank"><img src="<?php echo SITE_URL;?>/img/icons/view-small.png" alt="Afbeelding bekijken" width="24" height="24" /></a></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>images/delete/id/<?php echo $image->getId();?>/" onclick="return confirm('Weet u zeker dat deze afbeelding kan worden verwijderd?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="Afbeelding verwijderen" width="24" height="24" /></a></td>
			<td class="kolomtitle"><?php echo $image->getUrl();?></td>
		</tr>
	<?php
		}
	?>
    </table>