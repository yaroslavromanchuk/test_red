<?php
    class Shoparticlescolor extends wsActiveRecord
    {
        protected $_table = 'ws_articles_colors';
        protected $_orderby = array( 'sequence' => 'ASC', 'name'=>'ASC');
		protected $_multilang = array('name' => 'name');

        protected function _defineRelations()
             {

                 $this->_relations = array(
                     'articles' => array('type' => 'hasMany',
                                     'class' => 'Shoparticlessize',
                                     'field_foreign' => 'id_color',
                                     'onDelete' => null,),

                 );

             }

    }
?>