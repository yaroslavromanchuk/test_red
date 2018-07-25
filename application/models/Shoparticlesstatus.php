<?php
    class Shoparticlesstatus extends wsActiveRecord
    {
        protected $_table = 'ws_articles_status';
        protected $_orderby = array( 'id' => 'ASC');


      /*  protected function _defineRelations()
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
   

        }*/
    }
?>