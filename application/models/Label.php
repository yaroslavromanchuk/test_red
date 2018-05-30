<?php
    class Label extends wsActiveRecord
    {
        protected $_table = 'red_labels';
        protected $_orderby = array('id' => 'DESC');
protected $_multilang = array('name' => 'name');

        protected function _defineRelations()
        {
            $this->_relations = array(
                           'articles' => array(
                                                'type' => 'hasMany',
                                                'class' => 'Shoparticles',
                                                'field_foreign' => 'labelid',
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