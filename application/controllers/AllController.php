<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class AllController extends controllerAbstract
{
    private $parent_category = 267;
    
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function articlesAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    
    
}
