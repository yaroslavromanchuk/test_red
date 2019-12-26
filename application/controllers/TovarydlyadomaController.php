<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class TovarydlyadomaController extends controllerAbstract
{
    private $parent_category = 254;
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
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function allAction() {
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function textileAction(){
         $this->view->category = $category = new Shopcategories(146);
         FilterController::getFilter($category);  
    }
    public function posudAction(){
         $this->view->category = $category = new Shopcategories(339);
         FilterController::getFilter($category);  
    }
    public function uyutAction(){
         $this->view->category = $category = new Shopcategories(340);
         FilterController::getFilter($category);  
    }
    
    
}
