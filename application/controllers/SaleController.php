<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class SaleController extends controllerAbstract
{
    private $parent_category = 85;
    
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function allAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    public function menAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(231);
         FilterController::getFilter($category);
    }
    public function womenAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(218);
         FilterController::getFilter($category);
    }
    public function shoesAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(240);
         FilterController::getFilter($category);
    }
    public function textileAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(243);
         FilterController::getFilter($category);
    }
    public function accessoryAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(198);
         FilterController::getFilter($category);
    }
    public function babyAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(214);
         FilterController::getFilter($category);
    }
    public function sportAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(258);
         FilterController::getFilter($category);
    }
    
}
