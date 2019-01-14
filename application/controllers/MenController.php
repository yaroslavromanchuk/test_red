<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenController
 *
 * @author PHP
 */
class MenController extends controllerAbstract
{
    private $parent_category = 15;
    
    public function indexAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function odezhdaAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    
    public function brjukiAction() {
        //var_dump($this->get);
                $this->view->category = $category = new Shopcategories(73);
             //echo   $category->id;
         FilterController::getFilter($category);  
    }
    public function tenniskirubashkiAction(){
        $this->view->category = $category = new Shopcategories(39);
        FilterController::getFilter($category);  
    }
    public function futbolkimaikiAction(){
        $this->view->category = $category = new Shopcategories(32);
        FilterController::getFilter($category);  
    }
    public function pidzhakikostjumyAction(){
        $this->view->category = $category = new Shopcategories(40);
        FilterController::getFilter($category);  
    }
    public function dzhemperyreglanyAction(){
        $this->view->category = $category = new Shopcategories(78);
        FilterController::getFilter($category);  
    }
    public function dzhinsyAction(){
        $this->view->category = $category = new Shopcategories(107);
        FilterController::getFilter($category);  
    }
    public function belyeAction(){
        $this->view->category = $category = new Shopcategories(84);
        FilterController::getFilter($category);  
    }
    public function shortyAction(){
        $this->view->category = $category = new Shopcategories(141);
        FilterController::getFilter($category);  
    }
    public function verhnjajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(149);
        FilterController::getFilter($category);  
    }
    
    
    public function paltoAction(){
        $this->view->category = $category = new Shopcategories(324);
        FilterController::getFilter($category);  
    }
    public function kurtkiAction(){
        $this->view->category = $category = new Shopcategories(325);
        FilterController::getFilter($category);  
    }
    public function vetrovkiAction(){
        $this->view->category = $category = new Shopcategories(326);
        FilterController::getFilter($category);  
    }
    public function plashhiAction(){
        $this->view->category = $category = new Shopcategories(327);
        FilterController::getFilter($category);  
    }
    public function zhiletyAction(){
        $this->view->category = $category = new Shopcategories(328);
        FilterController::getFilter($category);  
    }
    public function pizhamyAction(){
        $this->view->category = $category = new Shopcategories(158);
        FilterController::getFilter($category);  
    }
    public function trusyAction(){
        $this->view->category = $category = new Shopcategories(314);
        FilterController::getFilter($category);  
    }
    public function plavkiAction(){
        $this->view->category = $category = new Shopcategories(315);
        FilterController::getFilter($category);  
    }
    public function belyemaikiAction(){
        $this->view->category = $category = new Shopcategories(316);
        FilterController::getFilter($category);  
    }
    public function sportivnajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(263);
        FilterController::getFilter($category);
    }
    
}
