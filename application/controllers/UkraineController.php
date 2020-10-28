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
class UkraineController extends controllerAbstract
{
    private $parent_category = 12;
      public function init() {
        parent::init();
         $this->view->critical_css = [
           '/css/catalog/catalog.css', 
        ];
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
                        '/js/select2/css/select2.min.css',
        ];
        $this->view->scripts = [
            '/js/filter.js',
            '/js/jquery.cycle.all.js',
            '/js/cloud-zoom.1.0.2.js',
            '/js/jquery.lightbox-0.5.js',
            '/lib/select2/js/select2.min.js'
        ];
    }
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    public function odezhdaAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    public function menAction() { 
                $this->view->category = $category = new Shopcategories(265);
          $this->getFilter($category);  
    } 
    public function womenAction() { 
                $this->view->category = $category = new Shopcategories(266);
          $this->getFilter($category);  
    } 
    public function babyAction() { 
                $this->view->category = $category = new Shopcategories(270);
          $this->getFilter($category);  
    } 
    public function accessoryAction() { 
                $this->view->category = $category = new Shopcategories(271);
          $this->getFilter($category);  
    } 
    public function shoesAction() { 
                $this->view->category = $category = new Shopcategories(272);
          $this->getFilter($category);  
    }
    /*
    public function beljoAction() { 
                $this->view->category = $category = new Shopcategories(163);
          $this->getFilter($category);  
    }   
    public function bluzyrubashkiAction(){
        $this->view->category = $category = new Shopcategories(273);
         $this->getFilter($category);  
    }
    public function dzhemperyreglanyAction(){
        $this->view->category = $category = new Shopcategories(274);
         $this->getFilter($category);  
    }
    public function futbolkimaikiAction(){
        $this->view->category = $category = new Shopcategories(275);
         $this->getFilter($category);  
    }
    public function platjaAction(){
        $this->view->category = $category = new Shopcategories(276);
         $this->getFilter($category);  
    }
    public function verhnjajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(277);
         $this->getFilter($category);  
    }
    public function pidzhakiAction(){
        $this->view->category = $category = new Shopcategories(278);
         $this->getFilter($category);  
    }
    public function brjukilosinyAction(){
        $this->view->category = $category = new Shopcategories(279);
         $this->getFilter($category);  
    }
    public function shortykapriAction(){
        $this->view->category = $category = new Shopcategories(280);
         $this->getFilter($category);  
    }
    public function jubkiAction(){
        $this->view->category = $category = new Shopcategories(281);
         $this->getFilter($category);  
    }
    public function dzhinsyAction(){
        $this->view->category = $category = new Shopcategories(282);
         $this->getFilter($category);  
    }
    public function kostjumykomplektyAction(){
        $this->view->category = $category = new Shopcategories(283);
         $this->getFilter($category);  
    }
    public function pizhamyAction(){
        $this->view->category = $category = new Shopcategories(336);
         $this->getFilter($category);  
    }*/
    
        
    
}
