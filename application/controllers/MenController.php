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
     public function init() {
        parent::init();
        $this->view->critical_css = [
            '/css/catalog/catalog.css', 
        ];
        $this->view->css = [
         //   '/css/cloudzoom/cloudzoom.css',
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
    public function odezhdaAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
         $this->getFilter($category);
    }
    
    public function brjukiAction() {
        //var_dump($this->get);
                $this->view->category = $category = new Shopcategories(73);
             //echo   $category->id;
         $this->getFilter($category);  
    }
    public function tenniskirubashkiAction() {
        $this->_redirect('/men/tenniski/');
    }
    public function rubashkiAction(){
        $this->view->category = $category = new Shopcategories(39);
        $this->getFilter($category);  
    }
    public function tenniskiAction(){
        $this->view->category = $category = new Shopcategories(344);
        $this->getFilter($category);  
    }
    public function futbolkimaikiAction(){
        $this->_redirect('/men/futbolki/');
       // $this->view->category = $category = new Shopcategories(32);
       // $this->getFilter($category);  
    }
    public function futbolkiAction(){
        $this->view->category = $category = new Shopcategories(32);
        $this->getFilter($category);  
    }
    public function maikiAction(){
        $this->view->category = $category = new Shopcategories(343);
        $this->getFilter($category);  
    }
    public function pidzhakikostjumyAction(){
        $this->view->category = $category = new Shopcategories(40);
        $this->getFilter($category);  
    }
    public function pidzhakiAction(){
        $this->view->category = $category = new Shopcategories(40);
        $this->getFilter($category);  
    }
    public function kostjumyAction(){
        $this->view->category = $category = new Shopcategories(351);
        $this->getFilter($category);  
    }
    public function dzhemperyreglanyAction(){//
        $this->_redirect('/men/dzhempery/');
        //$this->view->category = $category = new Shopcategories(78);
       // $this->getFilter($category);  
    }
    public function dzhemperyAction(){//
        $this->view->category = $category = new Shopcategories(78);
        $this->getFilter($category);  
    }
    public function reglanyAction(){//
        $this->view->category = $category = new Shopcategories(353);
        $this->getFilter($category);  
    }
    public function dzhinsyAction(){
        $this->view->category = $category = new Shopcategories(107);
        $this->getFilter($category);  
    }
    public function belyeAction(){
        $this->view->category = $category = new Shopcategories(84);
        $this->getFilter($category);  
    }
    public function shortyAction(){
        $this->view->category = $category = new Shopcategories(141);
        $this->getFilter($category);  
    }
    public function verhnjajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(149);
        $this->getFilter($category);  
    }
    
    public function pukhovikiAction(){
        $this->view->category = $category = new Shopcategories(352);
        $this->getFilter($category);  
    }
    public function paltoAction(){
        $this->view->category = $category = new Shopcategories(324);
        $this->getFilter($category);  
    }
    public function kurtkiAction(){
        $this->view->category = $category = new Shopcategories(325);
        $this->getFilter($category);  
    }
    public function vetrovkiAction(){
        $this->view->category = $category = new Shopcategories(326);
        $this->getFilter($category);  
    }
    public function plashhiAction(){
        $this->view->category = $category = new Shopcategories(327);
        $this->getFilter($category);  
    }
    public function zhiletyAction(){
        $this->view->category = $category = new Shopcategories(328);
        $this->getFilter($category);  
    }
    public function pizhamyAction(){
        $this->view->category = $category = new Shopcategories(158);
        $this->getFilter($category);  
    }
    public function trusyAction(){
        $this->view->category = $category = new Shopcategories(314);
        $this->getFilter($category);  
    }
    public function plavkiAction(){
        $this->view->category = $category = new Shopcategories(315);
        $this->getFilter($category);  
    }
    public function belyemaikiAction(){
        $this->view->category = $category = new Shopcategories(316);
        $this->getFilter($category);  
    }
    public function intimnoyebeljoAction(){
        $this->view->category = $category = new Shopcategories(359);
        $this->getFilter($category);  
        }
    public function sportivnajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(263);
        $this->getFilter($category);
    }
    public function halatyAction(){
        $this->view->category = $category = new Shopcategories(362);
        $this->getFilter($category);  
    }
    public function noskiAction(){
        $this->view->category = $category = new Shopcategories(363);
        $this->getFilter($category);  
    }
    
}
