<?php

class Ukrpostclients extends wsActiveRecord
{
    protected $_table = 'ws_ukr_post_clients';
    //protected $_orderby = array( 'id' => 'ASC');
     protected function _defineRelations()
        {

            $this->_relations = array(
                'address' => array(
				'type'            => 'hasMany', 
				'class'            => 'UkrPostAddress',
				'field_foreign'    => 'id',
				'onDelete'        => 'delete'
                    ), 
		'shipments' => array(
				'type'=>'hasMany',
				'class'=>'UkrPostShipments',
				'field_foreign'    => 'recipient',
                    'onDelete'        => 'delete'
                    ),            
            );

        }
    
    public function getSender($id = 0){
              return wsActiveRecord::useStatic('UkrPostClients')->findFirst(['externalId' => $id]);
        }
       public function getRecipient($id = 0){
              return wsActiveRecord::useStatic('UkrPostClients')->findFirst(['externalId' => $id]);
        } 
    
}
