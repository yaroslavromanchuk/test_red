<?php
    class Shoparticlesicon extends wsActiveRecord
    {
        protected $_table = 'ws_articles_icons';
        protected $_orderby = array( 'name' => 'ASC');


        protected function _defineRelations()
        {

            $this->_relations = array(    
            
            );

        }
    }
?>