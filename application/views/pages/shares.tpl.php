	<!--<h1><?php // echo $this->getCurMenu()->getName();?></h1>-->
	<?php echo $this->getCurMenu()->getPageBody();?>
	
<?php 
if($this->promocod){ echo '<div style="width: 100%;text-align: center;margin-top: 20px;margin-bottom: 20px;">
<div style="color: red;background: #eeeeee;width: 300px;display: inline-block;height: 75px;border-radius: 7px;">
<p style="
    font-size: 45px;
">'.$this->promocod.'</p>
</div>
</div>';}
echo '<div style="margin-bottom: 20px;
    font-size: 18px;
    width: 100%;
    text-align: center;" ><span style="color:red; font-size:14px;">Cкидка не действует на новые товары.</span></br>
	<span>Промокод действителен до 30.09.2017</span>
	</br>Удачных покупок с Red.ua!</div>';
?>	
