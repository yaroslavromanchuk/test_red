<?php
    class Shoparticlessize extends wsActiveRecord
    {
        protected $_table = 'ws_articles_sizes';
        protected $_orderby = array( 'count' => 'DESC');


        protected function _defineRelations()
        {
                    $this->_relations = array(
					'size' => array(
                                    'type'=>'hasOne',
                                    'class'=>'Size',
                                    'field'=>'id_size'), 
                    'color' => array(
                                     'type'=>'hasOne',
                                     'class'=>'Shoparticlescolor',
                                     'field'=>'id_color'), 
                                        
                                        );
   

        }
    }
?>