<?php
class wsMenu extends wsActiveRecord
{
    protected $_table = 'ws_menus';
	protected $_orderby = array('sequence' => 'ASC', 'id'=>'ASC');
	
	protected $_default_catalog = '';
    protected $_current_catalog;

    protected $_parents;

    //protected $action;
	
	protected $request_filters = array();
	//protected $_multilang = array('name' => 'name', 'page_title'=>'page_title', 'page_intro'=>'page_intro', 'page_body'=>'page_body' );

	protected function _defineRelations()
	{	
		$this->_relations = array('type' => array('type'=>'hasOne', //belongs to
													'class'=>self::$_menu_type_class,
													'field'=>'type_id',
													'autoload'=>true),		

								'parent' => array('type'=>'hasOne',
													'class'=>self::$_menu_class,
													'field'=>'parent_id'),

								'kids' => array('type' => 'hasMany',
													'class' => self::$_menu_class,
													'field_foreign' => 'parent_id',
													'orderby' => array('sequence' => 'ASC')),

													);
	}

	
	public function findLastSequenceRecord() {
		return $this->findFirst(array(), array('sequence'=>'DESC'));
	}

    public function getActiveKids()
    {
        return $this->getKids(array('active'=>1));
    }
	
	public function isChild($parent_id)
	{
		if($this->getId() == $parent_id)
			return true;
		
		if ($this->getParents())
		{
			foreach($this->getParents() as $parent)
			{
				if($parent->getId() == $parent_id)
					return true;
			}
		}
		
		return false;
	}

    public function getAction()
    {
        if (!$this->action)
            return 'index';

        return $this->action;
    }
	public function getPathSitemap()
    {
          return '/'.$this->getUrl().'/';  
    }
    public function getPath()
    {
		if($this->getRedirectUrl()){return $this->getRedirectUrl();}

        $items = array();
        $result = '';

        if (!$items = $this->getParents()){ return Registry::get('Website')->getSite()->getPath() . '/' . $this->getUrl() . '/';}

        foreach ($items as $item){$result .= '/' . $item->getUrl();}

        $result .= '/' . $this->getUrl() . '/';
		
		
		/*
		$tmp = array();
		foreach($this->getFilters() as $filter)
		{
			if($filter->getValue() && !$filter->getVisible())
			{
				$tmp[ucfirst($filter->getProperty()->getName())] = strtolower($filter->getValue());
			}
		}
		*/
		//$result .= $this->getFilterUrl(array(), $tmp);
		
		return Registry::get('Website')->getSite()->getPath() . $result;
    }

    public function getParents()
    {
    	if ($this->_parents){
    		return $this->_parents;
                
        }
        $parent = null;
        if ( ($this->getParentId() == -1) || !$this->getParentId() ){
            return;
            
        }
        if (!$parent = $this->getParent()){
        	return;
                
        }

        $parents = array();

        do
        {
           $parents[] = $parent;
        }
        while ($parent->getParentId() > 0 and $parent = $parent->getParent());

        $this->_parents = array_reverse($parents);

        return $this->_parents;
    }

	public function getTitle()
	{
		return ($this->getPageTitle() ? $this->getPageTitle() : $this->getName());
	}
        public function getFooter()
	{
		return $this->getPageFooter();
	}

	public function setUrl($url) 
	{
		$this->url = preg_replace('/[^a-zA-Z0-9_-]+/','-', trim(strtolower($url)));
	}

    public static function findMenu($type, $parent_id = null)
    {
        return wsActiveRecord::useStatic(self::$_menu_class)->findAll(array('active'=> 1, 'type_id'=>$type, 'parent_id' => $parent_id));
    }

    public static function findByName($name)
    {
		return wsActiveRecord::useStatic(self::$_menu_class)->findFirst(array('name'=>strtolower($name)));
    }

	//!!! ADD ESCAPE
    public static function findRootByUrl($uri)
    {
    	return wsActiveRecord::useStatic(self::$_menu_class)->findFirst('LOWER(ws_menus.url) = "' . strtolower($uri) . '" AND parent_id IS NULL');
    }
	
    public static function findByUrl($uri)
    {
		return wsActiveRecord::useStatic(self::$_menu_class)->findFirst('LOWER(ws_menus.url) = "' . Orm_Statement::escape(strtolower($uri)) . '"');
    }
    
    public function findByUrlAndParentId($uri, $parent_id)
    {
		return wsActiveRecord::useStatic(self::$_menu_class)->findFirst(array('LOWER(ws_menus.url) = "' . Orm_Statement::escape(strtolower($uri)) . '"', 'parent_id' => $parent_id));
    }

    public static function findAllByController($controller)
    {
        return wsActiveRecord::useStatic(self::$_menu_class)->findAll(array('active'=>1, 'controller' => $controller));
    }

    public static function findByControllerParameter($controller, $parameter)
    {
        return wsActiveRecord::useStatic(self::$_menu_class)->findFirst(array('active'=>1, 'controller'=>$controller, 'parameter'=>$parameter));
    }

    public static function findAllByControllerParameter($controller, $parameter)
    {
        return wsActiveRecord::useStatic(self::$_menu_class)->findAll(array('active' => 1, 'controller' => $controller,'parameter' => $parameter));
    }
	

}

