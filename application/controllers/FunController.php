<?php

class FunController extends controllerAbstract
{

    public function init()
    {
        parent::init();

    }
    public function clearlastarticlesAction(){
        $_SESSION['history'] =array();
        $this->_redirect(@$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:'');
    }


}
