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
class Ukrpostkontragent extends wsActiveRecord
{
    protected $_table = 'ws_ukr_post_clients';
    protected $_orderby = array( 'id' => 'ASC');
    
    
    public function getSender($id = 0){
              return wsActiveRecord::useStatic('Ukrpostkontragent')->findFirst(['external_id' => $id]);
        }
       public function getRecipient($id = 0){
              return wsActiveRecord::useStatic('Ukrpostkontragent')->findFirst(['external_id' => $id]);
        } 
    
}
