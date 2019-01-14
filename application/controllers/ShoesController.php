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
class ShoesController extends controllerAbstract
{
    private $parent_category = 33;
    
    public function indexAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function allAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    
    public function womenAction() {
        $this->view->category = $category = new Shopcategories(35);
        FilterController::getFilter($category);  
    }
    public function womentufliAction() {
        $this->view->category = $category = new Shopcategories(36);
        FilterController::getFilter($category);  
    }
    
        public function womenbosonozhkiAction(){
        $this->view->category = $category = new Shopcategories(37);
        FilterController::getFilter($category);  
        }
        public function womenbotinkibotilonyAction(){
        $this->view->category = $category = new Shopcategories(57);
        FilterController::getFilter($category);  
        }
        public function womensapogiAction(){
        $this->view->category = $category = new Shopcategories(58);
        FilterController::getFilter($category);  
        }
        public function womenkrossovkikedyAction(){
        $this->view->category = $category = new Shopcategories(62);
        FilterController::getFilter($category);  
        }
    

    public function menAction(){
        $this->view->category = $category = new Shopcategories(56);
        FilterController::getFilter($category);  
    }
        public function menkrossovkikedyAction(){
        $this->view->category = $category = new Shopcategories(60);
        FilterController::getFilter($category);  
        }
        public function menvetnamkisandaliiAction(){
        $this->view->category = $category = new Shopcategories(61);
        FilterController::getFilter($category);  
        }
        public function mentuflibotinkiAction(){
        $this->view->category = $category = new Shopcategories(68);
        FilterController::getFilter($category);  
        }
        
    public function babyAction(){
        $this->view->category = $category = new Shopcategories(67);
        FilterController::getFilter($category);  
    }
    
    public function unisexAction(){
        $this->view->category = $category = new Shopcategories(244);
        FilterController::getFilter($category);  
    }

    
}
