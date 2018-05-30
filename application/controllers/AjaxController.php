<?php

class AjaxController extends controllerAbstract
{

    public function init()
    {
        parent::init();

    }
    public function getbasketcountAction(){
        if (!isset($_SESSION['basket']))
            $_SESSION['basket'] = array();
        $this->basket  = $_SESSION['basket'];
        $articles_count = 0;
        $articles_price = 0.0;
        if ( $this->basket)
            foreach ( $this->basket as $item) {
                $articles_count += $item['count'];
                $articles_price += $item['price'] * $item['count'];
            }
        die(json_encode(array('cnt'=>$articles_count,'price'=>$articles_price)));
    }
	
	public function setlangAction(){
	if(@$this->post->lang){$_SESSION['lang'] = $this->post->lang;}
	 die();
	return false;
	}



}
