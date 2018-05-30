<?php

class PresentationController extends controllerAbstract
{


    public function indexAction()
    {
$link = "?utm_source=presentation&utm_medium=link&utm_content=Presentation&utm_campaign=Presentation";
		$this->view->block_p1 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>1, 'sport is null', 'sequence'=>1));
        $this->view->block_p2 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>2, 'sport is null', 'sequence'=>1));
        $this->view->block_p3 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>3, 'sport is null', 'sequence'=>1));
        $this->view->block_p4 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>4, 'sport is null', 'sequence'=>1));
        $this->view->block_p5 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>5, 'sport is null', 'sequence'=>1));
        $this->view->block_p6 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>6, 'sport is null', 'sequence'=>1));
		$this->view->block_p7 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>7, 'sport is null', 'sequence'=>1));
        $this->view->block_p8 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>8, 'sport is null', 'sequence'=>1));
        $this->view->block_p9 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>9, 'sport is null', 'sequence'=>1));
        $this->view->block_p10 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>10, 'sport is null', 'sequence'=>1));
		$this->view->block_p11 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>11, 'sport is null', 'sequence'=>1));
		$this->view->block_p12 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>12, 'sport is null', 'sequence'=>1));
		$this->view->block_p13 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>13, 'sport is null', 'sequence'=>1));
		
		if ($this->get->metod == 'getframe') {
		 $id = $this->get->getId();
		$articles_query = '
					SELECT ws_articles. * FROM ws_articles WHERE ws_articles.id IN('.wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>$id, 'sport is null', 'sequence'=>1))[0]->getArticles().')
					AND ws_articles.active =  "y"
					ORDER BY RAND( )';
					
					$finish_articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);

		
	$text = ' 
	<div class="ca-box" style="left:0px; width: 100%; height: 600px; margin-top: -17px;">
	<div class="ca-ul-box" id="mobil_present" style=" width: 100%;">
				<div class="items">';
                   
					
						
							foreach (wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>$id, 'sport is null', 'sequence'=>0)) as $block) { 
							//if($block->getSequence() == 1){continue;}
							 $text .= '                           
                            <div class="item">
                                <a class="img"  href="'.$block->getUrl().$link.'" target="_blank">
								<img class="img_pres"  src="'. $block->getImage() .'" alt="'.$block->getName() .'"/></a>
                            							</div>';
                         
                        }
              $text .= '
					</div>
					</div>
					<div  class="navi" id="main_navik" style="left: 10px;position: absolute;top: 20px;"></div>
					<div class="slider-nav">
					<span class="right"></span>
					<span class="left"></span>
					</div>
					<div style="width: 100%; height: auto;  ">
					<div style="font-size: 16px; background: white;"><a href="http://www.red.ua/category/id/266/mega-sale-20/odezhda/'.$link.'" target="_blank">Похожие товары</a></div>
					';
					 foreach ($finish_articles as $block) {
            if ($block->getId()) { 
			$text .='<div style="float:left; background: white;">
                                <a name="'. $block->getId() .'" href="'.$block->getPath().$link.'"  target="_blank">
                                    <img style="width: 149.5px;" src="'.$block->getImagePath('small_preview') .'">
                                    <div  style="float: none;">
                                         <p style="font-size: 13px;">
 
                                            '.$block->getBrand().'<br>
  
                                            <span style="color: red;">
  
                                               '. $block->getPrice().' грн
  
                                            </span>
                                        </p>
                                        <div class="sh_clear">
										</div>
                                    </div>
                                </a>
								</div>
			
			';
			}
			}
			$text .='
			
			</div>
			</div>';
		die($text);
	}
        echo $this->render('pages/presentation.tpl.php'); 

    }
	public function indexsportAction(){
		
		$link = "?utm_source=presentation&utm_medium=link&utm_content=Presentation&utm_campaign=Presentation";
		$this->view->block_p1 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>1, 'sport is not null', 'sequence'=>1));
        $this->view->block_p2 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>2, 'sport is not null', 'sequence'=>1));
        $this->view->block_p3 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>3, 'sport is not null', 'sequence'=>1));
        $this->view->block_p4 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>4, 'sport is not null', 'sequence'=>1));
        $this->view->block_p5 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>5, 'sport is not null', 'sequence'=>1));
        $this->view->block_p6 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>6, 'sport is not null', 'sequence'=>1));
		$this->view->block_p7 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>7, 'sport is not null', 'sequence'=>1));
        $this->view->block_p8 = wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>8, 'sport is not null', 'sequence'=>1));
		
		
		if ($this->get->metod == 'getframe_sport') {
		 $id = $this->get->getId();
		$articles_query = '
					SELECT ws_articles. * FROM ws_articles WHERE ws_articles.id IN('.wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>$id, 'sport is not null', 'sequence'=>1))[0]->getArticles().')
					AND ws_articles.active =  "y"
					ORDER BY RAND( )';
					
					$finish_articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);

		
	$text = ' 
	<div class="ca-box" style="left:0px; width: 100%; height: 600px; margin-top: -17px;">
	<div class="ca-ul-box" id="mobil_present" style=" width: 100%;">
				<div class="items">';
                   
					
						
							foreach (wsActiveRecord::useStatic('PresentationBlock')->findAll(array('block'=>$id, 'sport is not null', 'sequence'=>0)) as $block) { 
							//if($block->getSequence() == 1){continue;}
							 $text .= '                           
                            <div class="item">
                                <a class="img" href="'.$block->getUrl().$link.'" target="_blank">
								<img class="img_pres" src="'. $block->getImage() .'" alt="'.$block->getName() .'"/></a>
                            							</div>';
                        
                        }
              $text .= '
					</div>
					</div>
					<div  class="navi" id="main_navik" style="left: 10px;position: absolute;top: 20px;"></div>
					<div class="slider-nav">
					<span class="right"></span>
					<span class="left"></span>
					</div>
					<div style="width: 100%; height: auto; ">
					<div style="font-size: 16px; background: white;"><a href="http://www.red.ua/category/id/265/mega-sale-20/sport/'.$link.'" target="_blank">Похожие товары</a></div>
					';
					 foreach ($finish_articles as $block) {
            if ($block->getId()) { 
			$text .='<div style="float:left; background: white;">
                                <a name="'. $block->getId() .'" href="'.$block->getPath().$link.'"  target="_blank">
                                    <img style="width: 149.5px;" src="'.$block->getImagePath('small_preview') .'">
                                    <div  style="float: none;">
                                        <p style="font-size: 13px;">
 
                                            '.$block->getBrand().'<br>
  
                                            <span style="color: red;">
  
                                               '. $block->getPrice().' грн
  
                                            </span>
                                        </p>
                                        <div class="sh_clear">
										</div>
                                    </div>
                                </a>
								</div>
			
			';
			}
			}
			$text .='
			
			</div>
			</div>';
		die($text);
	}
		
		echo $this->render('pages/presentation.sport.tpl.php'); 
	}


}

