<style type="text/css">
	table.poll_result {
		border: 1px solid #cef;
		text-align: left;
	}
	table.poll_result th {
		font-weight: bold;
		background-color: #acf;
		border-bottom: 1px solid #cef;
	}
	table.poll_result td,th {
		padding: 4px 5px;
	}
	table.poll_result .odd {
		background-color: #def;
	}
	table.poll_result .odd td {
		border-bottom: 1px solid #cef;
	}
</style><br/>
	<?php
		foreach	($this->results as $title=>$results){?>
			<table border="0">
				<tr>
					<td>
						<strong><?php echo $title?>:</strong>
					</td>
				</tr>
				<tr>
					<td><?php
						
						$sum = 0;
						$chl = array();//labels
						$chd = array();//count
						$chdl = array();//legend values
						foreach ($results['results'] as $result){
							$sum+=$result->getC();
						}
						foreach ($results['results'] as $result){
							if ($result->getC() > 0){
								$chl[]= $result->getName();
								$chd[]= round(($result->getC()*100)/$sum);
								$chdl[]= $result->getC();
							}
						}
						$chl = 'chl='.implode($chl, '|');
						$chd = 'chd=t:'.implode($chd, ',');
						$chdl = 'chdl='.implode($chdl, '|');
						$url = 'https://chart.googleapis.com/chart?cht=p&chs=500x100&'.$chd.'&'.$chl.'&'.$chdl;
						?>
						<img src="<?php echo $url;?>" alt="Poll results" />
					</td>
				</tr>
				<tr>
					<td>Всего проголосовало:&nbsp;<?php echo $sum;?></td>
				</tr>
		</table>
<?php }	?>