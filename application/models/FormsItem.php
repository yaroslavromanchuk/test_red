<?php
    class FormsItem extends wsActiveRecord
    {
        protected $_table = 'ws_forms_item';
        protected $_orderby = array( 'id' => 'DESC');


      protected function _defineRelations()
        {
            	$this->_relations = array(
                    'forms' => [
					'type'=>'hasOne',
					'class'=>'Forms',
					'field' => 'forms_id'
                        ],
                    'types' => [
					'type'=>'hasOne',
					'class'=>'FormsItemType',
					'field' => 'type'
                        ]
                );

        }
    }
