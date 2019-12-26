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
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
            '/css/jquery.lightbox-0.5.css',
                        '/js/select2/css/select2.min.css',
            '/css/catalog/catalog.css', 
        ];
        $this->view->scripts = [
            '/js/filter.js',
            '/js/jquery.cycle.all.js',
            '/js/cloud-zoom.1.0.2.js',
            '/js/jquery.lightbox-0.5.js',
            '/lib/select2/js/select2.min.js'
        ];
    }
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
    public function tenniskirubashkiAction() {
        $this->_redirect('/men/tenniski/');
    }
    public function rubashkiAction(){
        $this->view->category = $category = new Shopcategories(39);
        FilterController::getFilter($category);  
    }
    public function tenniskiAction(){
        $this->view->category = $category = new Shopcategories(344);
        FilterController::getFilter($category);  
    }
    public function futbolkimaikiAction(){
        $this->_redirect('/men/futbolki/');
       // $this->view->category = $category = new Shopcategories(32);
       // FilterController::getFilter($category);  
    }
    public function futbolkiAction(){
        $this->view->category = $category = new Shopcategories(32);
        FilterController::getFilter($category);  
    }
    public function maikiAction(){
        $this->view->category = $category = new Shopcategories(343);
        FilterController::getFilter($category);  
    }
    public function pidzhakikostjumyAction(){
        $this->view->category = $category = new Shopcategories(40);
        FilterController::getFilter($category);  
    }
    public function pidzhakiAction(){
        $this->view->category = $category = new Shopcategories(40);
        FilterController::getFilter($category);  
    }
    public function kostjumyAction(){
        $this->view->category = $category = new Shopcategories(351);
        FilterController::getFilter($category);  
    }
    public function dzhemperyreglanyAction(){//
        $this->_redirect('/men/dzhempery/');
        //$this->view->category = $category = new Shopcategories(78);
       // FilterController::getFilter($category);  
    }
    public function dzhemperyAction(){//
        $this->view->category = $category = new Shopcategories(78);
        FilterController::getFilter($category);  
    }
    public function reglanyAction(){//
        $this->view->category = $category = new Shopcategories(353);
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
    
    public function pukhovikiAction(){
        $this->view->category = $category = new Shopcategories(352);
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
