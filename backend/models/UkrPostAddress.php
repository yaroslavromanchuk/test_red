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
class UkrPostAddress extends wsActiveRecord
{
    protected $_table = 'ws_ukr_post_address';
    protected $_orderby = array( 'ids' => 'ASC');
    protected function _defineRelations()
        {

            $this->_relations = array(
              /*  'client' => array(
				'type' => 'hasOne',
            'class' => 'UkrPostClients',
            'field' => 'addressId'
                    )        */  
            );

        }
    
    public function getAddress($id){
              return wsActiveRecord::useStatic('UkrPostAddress')->findFirst(['ids' => $id]);
        }
        public function listAddress($id){
            return wsActiveRecord::useStatic('UkrPostAddress')->findAll(['clients_id' => $id]);
        }
    
}
