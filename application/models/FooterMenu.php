<?php
    class FooterMenu extends wsActiveRecord
    {
        protected $_table = 'red_footer_menu';
        protected $_orderby = array( 'id' => 'ASC');

	protected $_multilang = array('title' => 'title');

      protected function _defineRelations()
        {


        }

    }
