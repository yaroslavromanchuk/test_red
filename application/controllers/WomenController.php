<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WomenController
 *
 * @author PHP
 */
class WomenController extends controllerAbstract
{
    private $parent_category = 14;
    
    public function indexAction(){
        $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }

    public function odezhdaAction() {
      // var_dump($this->get);
         
         $this->view->category = $category = new Shopcategories($this->parent_category);
         FilterController::getFilter($category);
    }
    
    public function beljoAction() { 
        //var_dump($this->get);
                $this->view->category = $category = new Shopcategories(249);
             //echo   $category->id;
         FilterController::getFilter($category);  
    }   
        public function beljomaikiAction(){
        $this->view->category = $category = new Shopcategories(317);
        FilterController::getFilter($category);  
        }
        public function bjustgalteryAction(){
        $this->view->category = $category = new Shopcategories(297);
        FilterController::getFilter($category);  
        }
        public function bodikombinaciiAction(){
        $this->view->category = $category = new Shopcategories(306);
        FilterController::getFilter($category);  
        }
        public function komplektybeljaAction(){
        $this->view->category = $category = new Shopcategories(140);
        FilterController::getFilter($category);  
        }
        public function korrektirujuweebeljoAction(){
        $this->view->category = $category = new Shopcategories(307);
        FilterController::getFilter($category);  
        }
        public function pizhamyAction(){
        $this->view->category = $category = new Shopcategories(137);
        FilterController::getFilter($category);  
        }
        public function trusikiAction(){
        $this->view->category = $category = new Shopcategories(296);
        FilterController::getFilter($category);  
        }
        
    public function bluzyrubashkiAction(){
        $this->view->category = $category = new Shopcategories(148);
        FilterController::getFilter($category);  
    }
    public function brjukiAction(){
        $this->view->category = $category = new Shopcategories(80);
        FilterController::getFilter($category);  
    }
    public function dzhemperyAction(){
        $this->view->category = $category = new Shopcategories(69);
        FilterController::getFilter($category);  
    }
    public function futbolkitenniskiAction(){
        $this->view->category = $category = new Shopcategories(30);
        FilterController::getFilter($category);  
    }
    public function dzhinsyAction(){
        $this->view->category = $category = new Shopcategories(113);
        FilterController::getFilter($category);  
    }
    public function jubkiAction(){
        $this->view->category = $category = new Shopcategories(144);
        FilterController::getFilter($category);  
    }
    public function koftyAction(){
        $this->view->category = $category = new Shopcategories(309);
        FilterController::getFilter($category);  
    }
    public function kostjumykomplektyAction(){
        $this->view->category = $category = new Shopcategories(147);
        FilterController::getFilter($category);  
    }
     public function kupalnikiAction(){
        $this->view->category = $category = new Shopcategories(74);
        FilterController::getFilter($category);  
    }
     public function maikiAction(){
        $this->view->category = $category = new Shopcategories(313);
        FilterController::getFilter($category);  
    }
     public function pidzhakizhiletyAction(){
        $this->view->category = $category = new Shopcategories(77);
        FilterController::getFilter($category);  
    }
     public function platjaAction(){
        $this->view->category = $category = new Shopcategories(70);
        FilterController::getFilter($category);  
    }
        public function platjadelovyeAction(){
        $this->view->category = $category = new Shopcategories(330);
        FilterController::getFilter($category);  
        }
        public function platjapovsednevnyeAction(){
        $this->view->category = $category = new Shopcategories(334);
        FilterController::getFilter($category);  
        }
        public function platjarubashkiAction(){
        $this->view->category = $category = new Shopcategories(329);
        FilterController::getFilter($category);  
        }
        public function platjasarafanyAction(){
        $this->view->category = $category = new Shopcategories(333);
        FilterController::getFilter($category);  
        }
        public function platjasportivnyeAction(){
        $this->view->category = $category = new Shopcategories(332);
        FilterController::getFilter($category);  
        }
        public function platjavechernieAction(){
        $this->view->category = $category = new Shopcategories(331);
        FilterController::getFilter($category);  
        }
        
     public function reglanyAction(){
        $this->view->category = $category = new Shopcategories(335);
        FilterController::getFilter($category);  
    }
     public function shortykapriAction(){
        $this->view->category = $category = new Shopcategories(143);
        FilterController::getFilter($category);  
    }
     public function sportivnajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(75);
        FilterController::getFilter($category);  
    }
     public function topyAction(){
        $this->view->category = $category = new Shopcategories(318);
        FilterController::getFilter($category);  
    }
     public function verhnjajaodezhdaAction(){
        $this->view->category = $category = new Shopcategories(142);
        FilterController::getFilter($category);  
    }
    
    
        public function kurtkiAction(){
        $this->view->category = $category = new Shopcategories(319);
        FilterController::getFilter($category);  
        }
        public function paltoAction(){
        $this->view->category = $category = new Shopcategories(322);
        FilterController::getFilter($category);  
        }
        public function vetrovkiAction(){
        $this->view->category = $category = new Shopcategories(321);
        FilterController::getFilter($category);  
        }
        public function plashhiAction(){
        $this->view->category = $category = new Shopcategories(320);
        FilterController::getFilter($category);  
        }
        public function zhiletyAction(){
        $this->view->category = $category = new Shopcategories(323);
        FilterController::getFilter($category);  
        }
        

}
