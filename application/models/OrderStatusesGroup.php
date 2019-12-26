<?php
    class OrderStatusesGroup extends wsActiveRecord
    {
        protected $_table = 'ws_order_statuses_group';
        protected $_orderby = array('id' => 'ASC');
         protected $_multilang = ['name' => 'name'];

        protected function _defineRelations()
        {
            $this->_relations = [
                'status' => array(
                'type' => 'hasMany',
                'class' => 'Shoporderstatuses',
                'field_foreign' => 'group',
                'orderby' => array('id' => 'ASC'),
                'onDelete' => 'delete'),
            ];
        }

    }