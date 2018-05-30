<?php
class Menu extends wsMenu 
{
	static protected $_top_position = 3;
	static protected $_admin_menu = 2;

	protected $_multilang = array('name' => 'name',
									'page_title' => 'page_title',
									'page_intro' => 'page_intro',
									'page_body' => 'page_body',
									'metatag_keywords' => 'metatag_keywords',
									'metatag_description' => 'metatag_description');

	
	static public function findTopMenu()
	{
		return self::findMenu(self::$_top_position);
	}	

	static public function findAdminMenu()
	{
		return self::findMenu(self::$_admin_menu, self::findRootByUrl('admin')->getId());
	}

	static public function findUserPages()
	{
		return wsActiveRecord::useStatic('Menu')->findBySiteId(1);

		$m = self::findMenu(self::$_top_position);
		return $m->merge(self::findMenu(null));
	}
}
?>