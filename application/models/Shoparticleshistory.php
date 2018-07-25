<?php
    class Shoparticleshistory extends wsActiveRecord
    {
        protected $_table = 'ws_articles_history';
        protected $_orderby = array( 'id' => 'DESC');


        protected function _defineRelations()
        {

            $this->_relations = array(    
            
            );

        }
    }
?>