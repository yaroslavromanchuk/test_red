<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class AccessoryController extends controllerAbstract
{
    private $parent_category = 54;
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
    
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    public function allAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
          $this->getFilter($category);
    }
    
    
    public function noskiAction(){
        $this->view->category = $category = new Shopcategories(55);
         $this->getFilter($category);  
    }
    public function sumkiAction() { 
                $this->view->category = $category = new Shopcategories(53);
          $this->getFilter($category);  
    }   
    public function remnipojasaAction(){
        $this->view->category = $category = new Shopcategories(65);
         $this->getFilter($category);  
    }
    public function ukrashenijaAction(){
        $this->view->category = $category = new Shopcategories(71);
         $this->getFilter($category);  
    }
    public function sharfyplatkiAction(){
        $this->view->category = $category = new Shopcategories(79);
         $this->getFilter($category);  
    }
    
    public function komplektynaboryAction(){
        $this->view->category = $category = new Shopcategories(134);
         $this->getFilter($category);  
    }
    public function koshelkiAction(){
        $this->view->category = $category = new Shopcategories(117);
         $this->getFilter($category);  
    }
    
    public function perchatkirukavicyAction(){
        $this->view->category = $category = new Shopcategories(114);
         $this->getFilter($category);  
    }
    
    public function golovnyeuboryAction(){
        $this->view->category = $category = new Shopcategories(115);
         $this->getFilter($category);  
    }
    
    public function galstukiAction(){
        $this->view->category = $category = new Shopcategories(152);
         $this->getFilter($category);  
    }
    
    public function zhenskiechasyAction(){
        $this->view->category = $category = new Shopcategories(154);
         $this->getFilter($category);  
    }
    
    public function muzhskiechasyAction(){
        $this->view->category = $category = new Shopcategories(155);
         $this->getFilter($category);  
    }
    
    public function zontyAction(){
        $this->view->category = $category = new Shopcategories(247);
         $this->getFilter($category);  
    }
    
    public function halatyAction(){
        $this->view->category = $category = new Shopcategories(251);
         $this->getFilter($category);  
    }
     
    public function ochkiopravyAction(){
        $this->view->category = $category = new Shopcategories(253);
         $this->getFilter($category);  
    }   
      
    public function chehlydljatelefonovAction(){
        $this->view->category = $category = new Shopcategories(268);
         $this->getFilter($category);  
    }  
    public function drugoyeAction(){
        $this->view->category = $category = new Shopcategories(366);
         $this->getFilter($category);  
    }
    
}
