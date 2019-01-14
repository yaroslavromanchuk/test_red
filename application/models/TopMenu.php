<?php
    class TopMenu extends wsActiveRecord
    {
        protected $_table = 'red_top_menu';
        protected $_orderby = array( 'id' => 'ASC');

protected $_multilang = array('title' => 'title',
'url' => 'url'
    );
      protected function _defineRelations()
        {


        }

    }
