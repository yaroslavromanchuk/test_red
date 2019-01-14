<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BabyController
 *
 * @author PHP
 */
class BabyController extends controllerAbstract
{
    private $parent_category = 59;
    
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function odezhdaAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function beljoAction() { 
                $this->view->category = $category = new Shopcategories(163);
         FilterController::getFilter($category);  
    }   
    public function bluzyrubashkiAction(){
        $this->view->category = $category = new Shopcategories(273);
        FilterController::getFilter($category);  
    }
    public function dzhemperyreglanyAction(){
        $this->view->category = $category = new Shopcategories(274);
        FilterController::getFilter($category);  
    }
    public function futbolkimaikiAction(){
        $this->view->category = $category = new Shopcategories(275);
        FilterController::getFilter($category);  
    }
    public function platjaAction(){
        $this->view->category = $category = new Shopcategories(276);
        FilterController::getFilter($category);  
    }
    public function verhnjajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(277);
        FilterController::getFilter($category);  
    }
    public function pidzhakiAction(){
        $this->view->category = $category = new Shopcategories(278);
        FilterController::getFilter($category);  
    }
    public function brjukilosinyAction(){
        $this->view->category = $category = new Shopcategories(279);
        FilterController::getFilter($category);  
    }
    public function shortykapriAction(){
        $this->view->category = $category = new Shopcategories(280);
        FilterController::getFilter($category);  
    }
    public function jubkiAction(){
        $this->view->category = $category = new Shopcategories(281);
        FilterController::getFilter($category);  
    }
    public function dzhinsyAction(){
        $this->view->category = $category = new Shopcategories(282);
        FilterController::getFilter($category);  
    }
    public function kostjumykomplektyAction(){
        $this->view->category = $category = new Shopcategories(283);
        FilterController::getFilter($category);  
    }
    
        
    
}
