<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminSection
 *
 * @author PHP
 */
class AdminSection extends wsActiveRecord
{
    protected $_table = 'ws_admin_menu_section';
    protected $_orderby = array( 'sort' => 'ASC');

protected $_multilang = array('name' => 'name'
    );
}
