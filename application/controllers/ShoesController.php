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
     public function init() {
        parent::init();
        $this->view->critical_css = [
            '/css/catalog/catalog.css', 
        ];
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
           // '/css/jquery.lightbox-0.5.css',
            '/js/select2/css/select2.min.css',
        ];
        $this->view->scripts = [
            '/js/filter.js',
            '/js/jquery.cycle.all.js',
           // '/js/cloud-zoom.1.0.2.js',
           // '/js/jquery.lightbox-0.5.js',
            '/lib/select2/js/select2.min.js'
        ];
    }
    public function indexAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    public function allAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    
    public function womenAction() {
        $this->view->category = $category = new Shopcategories(35);
         $this->getFilter($category);  
    }
    public function womentufliAction() {
        $this->view->category = $category = new Shopcategories(36);
         $this->getFilter($category);  
    }
    
        public function womenbosonozhkiAction(){
        $this->view->category = $category = new Shopcategories(37);
         $this->getFilter($category);  
        }
        public function womenbotinkibotilonyAction(){
        $this->view->category = $category = new Shopcategories(57);
         $this->getFilter($category);  
        }
        public function womensapogiAction(){
        $this->view->category = $category = new Shopcategories(58);
         $this->getFilter($category);  
        }
        public function womenkrossovkiAction(){
        $this->view->category = $category = new Shopcategories(62);
         $this->getFilter($category);  
        }
        public function womenkedyAction(){
        $this->view->category = $category = new Shopcategories(345);
         $this->getFilter($category);  
        }
        public function womendlyadomaAction(){
        $this->view->category = $category = new Shopcategories(355);
         $this->getFilter($category);  
        }
    

    public function menAction(){
        $this->view->category = $category = new Shopcategories(56);
         $this->getFilter($category);  
    }
        public function menkrossovkiAction(){
        $this->view->category = $category = new Shopcategories(60);
         $this->getFilter($category);  
        }
        public function menkedyAction(){
        $this->view->category = $category = new Shopcategories(347);
         $this->getFilter($category);  
        }
        public function menvetnamkiAction(){
        $this->view->category = $category = new Shopcategories(61);
         $this->getFilter($category);  
        }
        public function mensandaliiAction(){
        $this->view->category = $category = new Shopcategories(348);
         $this->getFilter($category);  
        }
        public function menmokasinyAction(){
        $this->view->category = $category = new Shopcategories(349);
         $this->getFilter($category);  
        }
        public function mentufliAction(){
        $this->view->category = $category = new Shopcategories(68);
         $this->getFilter($category);  
        }
        public function menbotinkiAction(){
        $this->view->category = $category = new Shopcategories(346);
         $this->getFilter($category);  
        }
        public function mendlyadomaAction(){
        $this->view->category = $category = new Shopcategories(356);
         $this->getFilter($category);  
        }
        
    public function babyAction(){
        $this->view->category = $category = new Shopcategories(67);
         $this->getFilter($category);  
    }
    
    public function unisexAction(){
        $this->view->category = $category = new Shopcategories(244);
         $this->getFilter($category);  
    }

    
}
