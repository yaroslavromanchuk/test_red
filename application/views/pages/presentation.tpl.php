<!--<h1><?php //echo $this->getCurMenu()->getName();?></h1>-->
<?php echo $this->getCurMenu()->getPageBody();?>
<?php $link = "utm_source=presentation&utm_medium=link&utm_content=Presentation&utm_campaign=Presentation";?>
<p style="text-align: center;">
<iframe  src="https://www.youtube.com/embed/mK8UBAVLsYU" frameborder="0"  class="video_frame" id="frame"></iframe>
</p>
<!--меню картинок для телефона-->
<ul style="text-align: center;" class="m_block_presertation">
<?php if($this->block_p1->count()> 0){ ?>
<li>
		<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p1[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
										<img class="img_vertical_high big m_present"  src="<?php echo $this->block_p1[0]->getImage() ?>" >
										</div>
										</a>
										
</li>
<?php }?>
<?php if($this->block_p2->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p2[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
										<img class="img_horizontal_high m_present" alt="<?php echo $this->block_p2[0]->getName() ?>" src="<?php echo $this->block_p2[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p3->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p3[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img <img class="img_vertical_high m_present" alt="<?php echo $this->block_p3[0]->getName() ?>" src="<?php echo $this->block_p3[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p4->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p4[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small m_present" alt="<?php echo $this->block_p4[0]->getName() ?>" src="<?php echo $this->block_p4[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p5->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p5[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small m_present" alt="<?php echo $this->block_p5[0]->getName() ?>" src="<?php echo $this->block_p5[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p6->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p6[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small m_present" alt="<?php echo $this->block_p6[0]->getName() ?>" src="<?php echo $this->block_p6[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p7->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p7[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high m_present" alt="<?php echo $this->block_p7[0]->getName() ?>" src="<?php echo $this->block_p7[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p8->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p8[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small m_present" alt="<?php echo $this->block_p8[0]->getName() ?>" src="<?php echo $this->block_p8[0]->getImage() ?>">
                                    </a>
									
</li>
<?php }?>
<?php if($this->block_p9->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p9[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_high m_present"  alt="<?php echo $this->block_p9[0]->getName() ?>" src="<?php echo $this->block_p9[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p10->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p10[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small m_present" alt="<?php echo $this->block_p10[0]->getName() ?>" src="<?php echo $this->block_p10[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p11->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p11[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small m_present" alt="<?php echo $this->block_p11[0]->getName() ?>" src="<?php echo $this->block_p11[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p12->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p12[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img  class="img_vertical_high m_present"  alt="<?php echo $this->block_p12[0]->getName() ?>" src="<?php echo $this->block_p12[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
<?php if($this->block_p13->count()> 0){ ?>
<li>
<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p13[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high m_present" alt="<?php echo $this->block_p13[0]->getName() ?>" src="<?php echo $this->block_p13[0]->getImage() ?>">
                                    </div>
									</a>
									
</li>
<?php }?>
</ul>
<!--выход меню картинок для телефона-->
<!--меню картинок-->
<div id="menu_6" class="block_p-box">
	<table style="text-align: center;"   >
    <tr>
        <td rowspan="2">
		<?php if($this->block_p1->count()> 0){ ?>
                                    <a href="#comment-modal" data-toggle="modal"  onClick="myFunc(<?php echo $this->block_p1[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
										<img class="img_vertical_high big"  src="<?php echo $this->block_p1[0]->getImage() ?>" >
										</div>

                                    </a>
									<?php }?>
        
		</td>
        <td colspan="2"> 
		<?php if($this->block_p2->count()> 0){ ?>
                                     <a href="#comment-modal" data-toggle="modal"  onClick="myFunc(<?php echo $this->block_p2[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
										<img class="img_horizontal_high" alt="<?php echo $this->block_p2[0]->getName() ?>" src="<?php echo $this->block_p2[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
                                </td>
        <td rowspan="2">
		<?php if($this->block_p3->count()> 0){ ?>
                                  <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p3[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img <img class="img_vertical_high" alt="<?php echo $this->block_p3[0]->getName() ?>" src="<?php echo $this->block_p3[0]->getImage() ?>">
                                    </div>
									</a>
                                <?php }?>
								</td>
    </tr>
    <tr>
        <td>
		<?php if($this->block_p4->count()> 0){ ?>
                                     <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p4[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
									<img class="img_vertical_small" alt="<?php echo $this->block_p4[0]->getName() ?>" src="<?php echo $this->block_p4[0]->getImage() ?>">
                                    </div>
									</a>
                                <?php }?>
								</td>
        <td>
		<?php if($this->block_p5->count()> 0){ ?>
                                   <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p5[0]->getBlock()?>);">
										<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_small" alt="<?php echo $this->block_p5[0]->getName() ?>" src="<?php echo $this->block_p5[0]->getImage() ?>">
                                    </div>
									</a>
                               <?php }?>
								</td>
    </tr>
    <tr>
        <td colspan="2">
<?php if($this->block_p6->count()> 0){ ?>             
			 <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p6[0]->getBlock()?>);">
						<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high try" alt="<?php echo $this->block_p6[0]->getName() ?>" src="<?php echo $this->block_p6[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
                               </td>
        <td colspan="2">
		<?php if($this->block_p7->count()> 0){ ?>
                                     <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p7[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high try" alt="<?php echo $this->block_p7[0]->getName() ?>" src="<?php echo $this->block_p7[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
        </td>
		</tr>
		<tr>
        <td colspan="2">
<?php if($this->block_p8->count()> 0){ ?>                
				<a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p8[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high try" alt="<?php echo $this->block_p8[0]->getName() ?>" src="<?php echo $this->block_p8[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
                                </td>
	<td colspan="2">
		<?php if($this->block_p9->count()> 0){ ?>
                                    <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p9[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high try"  alt="<?php echo $this->block_p9[0]->getName() ?>" src="<?php echo $this->block_p9[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
        </td>
    </tr>
    <tr>
        
        <td rowspan="2">
		<?php if($this->block_p10->count()> 0){ ?>
                                     <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p10[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_vertical_high" alt="<?php echo $this->block_p10[0]->getName() ?>" src="<?php echo $this->block_p10[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
                               </td>
        <td colspan="2">
		<?php if($this->block_p11->count()> 0){ ?>
                                     <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p11[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high" alt="<?php echo $this->block_p11[0]->getName() ?>" src="<?php echo $this->block_p11[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
                                </td>
        <td rowspan="2">
		<?php if($this->block_p12->count()> 0){ ?>
                                    <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p12[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img  class="img_vertical_high"  alt="<?php echo $this->block_p12[0]->getName() ?>" src="<?php echo $this->block_p12[0]->getImage() ?>">
                                    </div>
									</a>
									<?php }?>
       </td>
    </tr>
    <tr>
        <td colspan="2">
		<?php if($this->block_p13->count()> 0){ ?>
                                     <a href="#comment-modal" data-toggle="modal" onClick="myFunc(<?php echo $this->block_p13[0]->getBlock()?>);">
									<div class="text_top_prod_pr" data-title="Подробнее ...">
                                        <img class="img_horizontal_high" alt="<?php echo $this->block_p13[0]->getName() ?>" src="<?php echo $this->block_p13[0]->getImage() ?>">
                                    </div>
									</a> 
									<?php }?>
                                </td>
    </tr>
</table>
	
	
	
	
	
  <div class="clear"></div>
	</div>
<script type="text/javascript">
if(html=="tablet"){
$('#frame').addClass('m_video_frame');
obj_present.Style();
}
</script>

<!-- !Comment modal2 -->  

	<div class="modal fade comment-form" id="comment-modal"  role="dialog" aria-labelledby="comment-modal">
		<div class="modal-dialog" role="document" id="zzz">
		<a class=" but_clos close" aria-hidden="true" data-dismiss="modal" style="opacity: .7;"></a>
	    	<div class="modal-content"  style="text-align: center;">
	    	</div>
	  	</div>
	</div>	

	<!-- End Comment modal2 -->

<script type="text/javascript">
$(function () {
		//$('a.img_horizontal_high').lightBox({fixedNavigation: true});
	});

function myFunc (x) {
$.get('/presentation/id/' + x + '/metod/getframe/',
		function (data) {
		
		 $('#zzz .modal-content').html(data);
			$('.ca-ul-box').scrollable({speed: 1000,  prev: ".left", next: ".right"}).navigator("#main_navik");
			 
			
		});

		return false;
	
}

	
</script>

			



