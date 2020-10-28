<?php
/**
 * Description of BabyController
 *
 * @author PHP
 */
class SaleController extends controllerAbstract
{
    private $parent_category = 85;
      public function init() {
        parent::init();
        $this->view->critical_css = [
            '/css/catalog/catalog.css', 
        ];
        $this->view->css = [
            '/css/cloudzoom/cloudzoom.css',
          //  '/css/jquery.lightbox-0.5.css',
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
    }//------------
    public function menAction() {
         $this->view->category = $category = new Shopcategories(231);
          $this->getFilter($category);
    }
    public function menbrjukiAction() {
         $this->view->category = $category = new Shopcategories(232);
          $this->getFilter($category);
    }
    public function menverhnjajaodezhdaAction() {
         $this->view->category = $category = new Shopcategories(233);
          $this->getFilter($category);
    }
    public function mendzhemperyAction() {
         $this->view->category = $category = new Shopcategories(234);
          $this->getFilter($category);
    }
    public function mendzhinsyAction() {
         $this->view->category = $category = new Shopcategories(235);
          $this->getFilter($category);
    }
    public function menkostjumyAction() {
         $this->view->category = $category = new Shopcategories(369);
          $this->getFilter($category);
    }
    public function menmaikiAction() {
         $this->view->category = $category = new Shopcategories(370);
          $this->getFilter($category);
    }
    public function menbelyeAction() {
         $this->view->category = $category = new Shopcategories(213);
          $this->getFilter($category);
    }
    public function menpidzhakiAction() {
         $this->view->category = $category = new Shopcategories(236);
          $this->getFilter($category);
    }
    public function menreglanyAction() {
         $this->view->category = $category = new Shopcategories(371);
          $this->getFilter($category);
    }
    public function menrubashkiAction() {
         $this->view->category = $category = new Shopcategories(372);
          $this->getFilter($category);
    }
    public function mensportivnajaodezhdaAction() {
         $this->view->category = $category = new Shopcategories(264);
          $this->getFilter($category);
    }
    public function mentenniskiAction() {
         $this->view->category = $category = new Shopcategories(237);
          $this->getFilter($category);
    }
    public function menfutbolkiAction() {
         $this->view->category = $category = new Shopcategories(238);
          $this->getFilter($category);
    }
    public function menshortyAction() {
         $this->view->category = $category = new Shopcategories(239);
          $this->getFilter($category);
    }
    //------------------------
    public function womenAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(218);
          $this->getFilter($category);
    }
    //-------
    public function shoesAction() {
         $this->view->category = $category = new Shopcategories(240);
          $this->getFilter($category);
    }
    public function shoeswomenAction() {
         $this->view->category = $category = new Shopcategories(241);
          $this->getFilter($category);
    }
    public function shoesmenAction() {
         $this->view->category = $category = new Shopcategories(242);
          $this->getFilter($category);
    }
    public function shoesunisexAction() {
         $this->view->category = $category = new Shopcategories(245);
          $this->getFilter($category);
    }
    public function shoesbabyAction() {
         $this->view->category = $category = new Shopcategories(368);
          $this->getFilter($category);
    }
    //--------------
    public function textileAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(243);
          $this->getFilter($category);
    }
    //----------------
    public function accessoryAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(198);
          $this->getFilter($category);
    }
        public function accessorygalstukiAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(199);
          $this->getFilter($category);
    }
        public function accessorygolovnyeuboryAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(200);
          $this->getFilter($category);
    }
    public function accessorydrugoyeAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(367);
          $this->getFilter($category);
    }
     public function accessoryzhenskiechasyAction() {
         $this->view->category = $category = new Shopcategories(201);
          $this->getFilter($category);
    }
    public function accessoryzontyAction() {
         $this->view->category = $category = new Shopcategories(261);
          $this->getFilter($category);
    }
     public function accessorykomplektynaboryAction() {
         $this->view->category = $category = new Shopcategories(203);
          $this->getFilter($category);
    }
    public function accessorykoshelkiAction() {
         $this->view->category = $category = new Shopcategories(204);
          $this->getFilter($category);
    }
    public function accessorymuzhskiechasyAction() {
         $this->view->category = $category = new Shopcategories(202);
          $this->getFilter($category);
    }
    public function accessorynoskiAction() {
         $this->view->category = $category = new Shopcategories(205);
          $this->getFilter($category);
    }
    public function accessoryochkiopravyAction() {
         $this->view->category = $category = new Shopcategories(262);
          $this->getFilter($category);
    }
    public function accessoryperchatkirukavicyAction() {
         $this->view->category = $category = new Shopcategories(206);
          $this->getFilter($category);
    }
    public function accessoryremnipojasaAction() {
         $this->view->category = $category = new Shopcategories(207);
          $this->getFilter($category);
    }
    public function accessorysumkiAction() {
         $this->view->category = $category = new Shopcategories(208);
          $this->getFilter($category);
    }
    public function accessoryukrashenijaAction() {
         $this->view->category = $category = new Shopcategories(209);
          $this->getFilter($category);
    }
    public function accessoryhalatyAction() {
         $this->view->category = $category = new Shopcategories(252);
          $this->getFilter($category);
    }
    public function accessorychehlydljatelefonovAction() {
         $this->view->category = $category = new Shopcategories(269);
          $this->getFilter($category);
    }
    public function accessorysharfyplatkiAction() {
         $this->view->category = $category = new Shopcategories(210);
          $this->getFilter($category);
    }
    //----------------------------
        
    public function babyAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(214);
          $this->getFilter($category);
    }
    //---------------------
    public function sportAction() {
      // var_dump($this->get);
         $this->view->category = $category = new Shopcategories(258);
          $this->getFilter($category);
    }
    
}
