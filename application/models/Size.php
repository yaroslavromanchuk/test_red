<?php
    class Size extends wsActiveRecord
    {
        protected $_table = 'ws_sizes';
        protected $_orderby = array( 'category_id'=>'ASC', 'sequence' => 'ASC',  'size'=>'ASC');


        protected function _defineRelations()
        {

            $this->_relations = array(
                'articles' => array(
				'type'            => 'hasMany', 
				'class'            => 'Shoparticlessize',
				'field_foreign'    => 'id_size',
				'orderby'        => array('id' => 'ASC'),
				'onDelete'        => 'delete'
                    ), 
		'category' => array(
				'type'=>'hasOne',
				'class'=>'SizeCategory',
				'field'=>'category_id'
                    ),            
            );

        }

        public function getArticlesCount()
        {
            return wsActiveRecord::useStatic('Shoparticlessize')->count(array('id_size'=>$this->getId()));
        }
    }
