<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Акции на товар, бренд, категорию 
 *
 * @author PHP
 */
class NewsController extends controllerAbstract
{
   // public function init()
   // {
    //    parent::init();

  //  }
    /**
     * Страница с акциями или детально про отдельную акцию
     * 
     * если в get присутствует id -> детали отдельной акции
     * если в get нет id -> все активные акции
     * return view page
     */
        public function indexAction()
    {
        if (isset($this->get->id) && $news = wsActiveRecord::useStatic('Shoparticlesoption')->findById($this->get->getId())) {
            $this->view->news = $news;
           //$this->cur_menu->setImage($news->intro);
            $this->cur_menu->setName($news->option_text);
            $this->cur_menu->setPageTitle($news->option_text.' - '.Translator::get('в интернет магазине RED'));
            $this->cur_menu->setMetatagDescription(strip_tags($news->content));
            echo $this->render('news/one.tpl.php');
        } else{
            $this->view->news = wsActiveRecord::useStatic('Shoparticlesoption')->findActiveOption();
            echo $this->render('news/list.tpl.php');
        }
    }
    
   /* public function index1Action()
    {
        if ($news = wsActiveRecord::useStatic('News')->findById($this->get->getId())) {
            $this->cur_menu->page_image = $news->image;
            $this->cur_menu->page_title = $news->title;
            $this->cur_menu->name = $news->title;
            $this->cur_menu->page_body = $news->content;
            echo $this->render('news/one.tpl.php');
        } else{
            echo $this->render('news/list.tpl.php');
        }
    }*/
    
    
  
}
