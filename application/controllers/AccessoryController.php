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
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    
    
    public function noskiAction(){
        $this->view->category = $category = new Shopcategories(55);
        FilterController::getFilter($category);  
    }
    public function sumkiAction() { 
                $this->view->category = $category = new Shopcategories(53);
         FilterController::getFilter($category);  
    }   
    public function remnipojasaAction(){
        $this->view->category = $category = new Shopcategories(65);
        FilterController::getFilter($category);  
    }
    public function ukrashenijaAction(){
        $this->view->category = $category = new Shopcategories(71);
        FilterController::getFilter($category);  
    }
    public function sharfyplatkiAction(){
        $this->view->category = $category = new Shopcategories(79);
        FilterController::getFilter($category);  
    }
    
    public function komplektynaboryAction(){
        $this->view->category = $category = new Shopcategories(134);
        FilterController::getFilter($category);  
    }
    public function koshelkiAction(){
        $this->view->category = $category = new Shopcategories(117);
        FilterController::getFilter($category);  
    }
    
    public function perchatkirukavicyAction(){
        $this->view->category = $category = new Shopcategories(114);
        FilterController::getFilter($category);  
    }
    
    public function golovnyeuboryAction(){
        $this->view->category = $category = new Shopcategories(115);
        FilterController::getFilter($category);  
    }
    
    public function galstukiAction(){
        $this->view->category = $category = new Shopcategories(152);
        FilterController::getFilter($category);  
    }
    
    public function zhenskiechasyAction(){
        $this->view->category = $category = new Shopcategories(154);
        FilterController::getFilter($category);  
    }
    
    public function muzhskiechasyAction(){
        $this->view->category = $category = new Shopcategories(155);
        FilterController::getFilter($category);  
    }
    
    public function zontyAction(){
        $this->view->category = $category = new Shopcategories(247);
        FilterController::getFilter($category);  
    }
    
    public function halatyAction(){
        $this->view->category = $category = new Shopcategories(251);
        FilterController::getFilter($category);  
    }
     
    public function ochkiopravyAction(){
        $this->view->category = $category = new Shopcategories(253);
        FilterController::getFilter($category);  
    }   
      
    public function chehlydljatelefonovAction(){
        $this->view->category = $category = new Shopcategories(268);
        FilterController::getFilter($category);  
    }  
}
