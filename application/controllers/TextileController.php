<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class TextileController extends controllerAbstract
{
    private $parent_category = 146;
    
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function allAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    
    
}
