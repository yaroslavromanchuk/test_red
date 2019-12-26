<?php
	class JustinCities  extends wsActiveRecord {
		protected $_table = 'ws_justin_cities';
		protected $_orderby = array( 'name' => 'ASC');
		protected $_multilang = array('name' => 'name');

		protected function _defineRelations() {
                    $this->_relations = [
                'departments' => array(
                'type' => 'hasMany',
                'class' => 'JustinDepartments',
                'field_foreign' => 'city_uuid',
                'orderby' => array('name_ru' => 'ASC'),
                'onDelete' => 'delete'),
                        ];   
		}

/* $this->_relations = [
                'departments' => array(
                'type' => 'hasMany',
                'class' => 'JustinDepartments',
                'field_foreign' => 'city_uuid',
                'orderby' => array('name_ru' => 'ASC'),
                'onDelete' => 'delete'),
                        ];
                        */


	}