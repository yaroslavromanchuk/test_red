<?php

class AdminController extends AdminController
{
     private $_files_folder = 'backend';
    private $_controller = 'admin';
    /**
     * Название темы
     * @var type 
     */
    private $_theme = 'default';
    /**
     * Файл шаблона
     * @var type 
     */
    private $_template = 'admin.tpl.php';

    public function init()
    {
    parent::init();
    
    }
    public function ordersAction(){
        echo 'tut';
    }
}
