<img src="<?php echo SITE_URL;?><?php echo $this->getCurMenu()->getImage();?>" alt="" width="32" class="page-img" height="32" />
<h1><?php echo $this->getCurMenu()->getTitle();?> </h1>
<?php echo $this->getCurMenu()->getPageBody();?>

	<p><img src="<?php echo SITE_URL;?>/img/icons/upload-small.png" alt="PDF-bestand uploaden" width="24" height="24" /><a href="<?php echo $this->path;?>pdfs/upload/">PDF-bestand uploaden</a></p>

	<table id="pageslist" cellpadding="2" cellspacing="0">
		<tr>
			<th colspan="2">Handeling</th>
			<th>URL van het PDF-bestand</th>
		</tr>
	<?php 
		$row = 'row1';
		foreach($this->getPdfs() as $pdf )
		{
			$row = ($row == 'row2') ? 'row1' : 'row2';
	?>
		<tr class="<?php echo $row;?>">
			<td class="kolomicon"><a href="<?php echo $pdf->getUrl();?>" target="_blank"><img src="<?php echo SITE_URL;?>/img/icons/view-small.png" alt="PDF-bestand bekijken" width="24" height="24" /></td>
			<td class="kolomicon"><a href="<?php echo $this->path;?>pdfs/delete/id/<?php echo $pdf->getId();?>/" onclick="return confirm('Weet u zeker dat dit PDF-bestand kan worden verwijderd?')"><img src="<?php echo SITE_URL;?>/img/icons/remove-small.png" alt="PDF-bestand verwijderen" width="24" height="24" /></a></td>
			<td class="kolomtitle"><?php echo $pdf->getUrl();?></td>	
		</tr>
	<?php
		}
	?>
    </table>