<?php
    class Shoparticleslabel extends wsActiveRecord
    {
        protected $_table = 'ws_articles_labels';
        protected $_orderby = array('name' => 'ASC');
		protected $_multilang = array('name' => 'name');
        protected function _defineRelations()
             {
                 $this->_relations = array(
                                'articles' => array(
                                                     'type' => 'hasMany',
                                                     'class' => 'Shoparticles',
                                                     'field_foreign' => 'label_id',
                                                     'orderby' => array('ctime' => 'ASC')
                                                 ),

                           );
             }

             public function _beforeDelete()
             {
                 $folder = $_SERVER['DOCUMENT_ROOT'];
                 @unlink($folder . $this->getImage());
                 return true;
             }
    }
?>