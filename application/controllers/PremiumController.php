<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class PremiumController extends controllerAbstract
{
    private $parent_category = 299;
      public function init() {
        parent::init();
        $this->view->critical_css = [
            '/css/catalog/catalog.css', 
        ];
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
         //   '/css/jquery.lightbox-0.5.css',
            '/js/select2/css/select2.min.css',
        ];
        $this->view->scripts = [
            '/js/filter.js',
            '/js/jquery.cycle.all.js',
           // '/js/cloud-zoom.1.0.2.js',
          //  '/js/jquery.lightbox-0.5.js',
            '/lib/select2/js/select2.min.js'
        ];
    }
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    public function allAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    
    
}
