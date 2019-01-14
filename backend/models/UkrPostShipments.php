<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Storess
 *
 * @author PHP
 */
class UkrPostShipments extends wsActiveRecord
{
    protected $_table = 'ws_ukr_post_shipments';
    protected $_orderby = array( 'id' => 'ASC');
    /*
    public function getAkcii(){
              return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'new']);
        }*/
    
}
