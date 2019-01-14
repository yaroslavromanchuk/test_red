<?php
class Shopcategories extends wsActiveRecord
{
	protected $_table = 'ws_categories';
	protected $_orderby = ['sequence'=>'ASC'];

	protected $_multilang = array('name' => 'name', 'title'=>'title', 'description'=>'description', 'footer'=>'footer', 'h1'=>'h1');
									
	protected function _defineRelations()
	{	
		$this->_relations = array(
                    'parent' => array(
				'type'=>'hasOne',
				'class'=>self::$_shop_categories_class,
				'field'=>'parent_id'
                                        ),							
                    'articles' => array(
                        'type' => 'hasMany',
			'class' => self::$_shop_articles_class,
			'field_foreign' => 'category_id',
			'orderby' => array('sequence' => 'DESC'),
					),
                    'kids' => array(
                        'type' => 'hasMany',
                        'class' => self::$_shop_categories_class,
			'field_foreign' => 'parent_id',
			'orderby' => array('sequence' => 'ASC'),
				),
                    'balance_category' => array(
                                            'type' => 'hasMany',
                                            'class' => 'BalanceCategory',
                                            'field_foreign' => 'id_category',
                                            'orderby' => array('id' => 'ASC')
                                                ),							
					);
	}
	
	public function findLastSequenceRecord() {
		return $this->findFirst(array(), array('sequence'=>'DESC'));
	}
	
	public function getUrl()
	{
		return $this->_generateUrl($this->name);
	}

      
        public function getPath(){
        $lang = '';
        if(Registry::get('lang') == 'uk'){$lang = '/uk';}
       /* switch ($this->controller){
            case 'sale': ''; break;
            case '': ''; break;
            default :  return $lang.'/'.$this->controller.'/'.$this->action.'/';
    
}*/
            return $lang.'/'.$this->controller.'/'.$this->action.'/';
            
        

        }
        


        public function getPath1()
        {
       // $items = array();
        $result = '';
        $lang = '';
        if(Registry::get('lang') == 'uk'){$lang = '/uk';}
        
        if (!$this->getParents()) {
            return $lang.'/category/id/' .$this->getId() .'/'. $this->getUrl() . '/';
            
        }else{

        foreach ($this->getParents() as $item){
		$result .= $item->getUrl().'/';
		}
              //  return $lang.'/category/id/' .$this->getId().'/'. $result;
        }

        $result .= $this->getUrl() . '/';

		return $lang.'/category/id/' .$this->getId().'/'. $result;
        }
	
	/*
    public function getPath()
    {
        $items = array();
        $result = '';

        if (!$items = $this->getParents())
            return '/' . $this->getUrl() . '/';

        foreach ($items as $item)
            $result .= '/' . $item->getUrl();

        $result .= '/' . $this->getUrl() . '/';

		return $result;
    }
    */

    public function getParents($show_myself = 0)
    {
    	if ($this->_parents){
            return $this->_parents; 
            
        }
        $parent = null;
                if($show_myself){
                    $add = array($this);
                    
                }else{
                    $add = null;
                    
                }
                
        if ( ($this->getParentId() == -1) || !$this->getParentId() ){
            return $add;
            
        }
        if (!$parent = $this->getParent()){
            return $add;
            
        }

        $parents = array();

		if($show_myself){ $parents[] = $this; }
        do
        {
           $parents[] = $parent;
        }
        while ($parent->getParentId() > 0 and $parent = $parent->getParent());

        $this->_parents = array_reverse($parents);

        return $this->_parents;
    }

    
    public function getRoute($links = 1)
    {
    	$p = $this->getParents();
    	$res = [];
         $i=1;
    	if($p and count($p) > 1){

    	foreach($p as $parent){
            $res[] = '<li class="breadcrumb-item" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . $parent->getPath() . '"><span itemprop="title">' . $parent->getName() . '</span></a></li>';
            
        }
        
            }
            $res[] = '<li class="breadcrumb-item active" aria-current="page" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . $this->getPath() . '"><span itemprop="title">' . $this->getName() . '</span></a></li>';
        
    	$ret = implode('', $res);

    	if(!$links)
    		return strip_tags($ret);
    	else
    		return $ret;
    }

	public function getKidsIds()
	{
		$ids = array($this->getId());
                
		if($kids = $this->getKids()){
			foreach($kids as $kid)
			{
                    
				$kid_ids = $kid->getKidsIds();
				foreach($kid_ids as $id){
					$ids[] = $id;
                                }
			}
        }
		return $ids;
	}
    
	public function getActiveProductCount()
	{
		$ids = array($this->getId());
		if($kids = $this->getKids()){
                        foreach($kids as $kid) { 
                    $ids[] = $kid->getId();
                        
                        }
        }
        
		$cat_text = 'category_id in (' . implode(', ', $ids) . ')';	
		return wsActiveRecord::useStatic('Shoparticles')->count(array('stock not like "0" ', 'status = 3 ', $cat_text));
	}
        /**
         * Количество товара с учетом фильтра
         * по умолчанию :stock not like "0", status = 3, category_id in
         * @param type $filter = '', можно - label_id = 13...
         * @return int
         */
        public function getActiveProductCountFilter($filter = '')
	{
		$ids = array($this->getId());
		if($kids = $this->getKids()){
                        foreach($kids as $kid) { 
                    $ids[] = $kid->getId();
                        
                        }
        }
        
		$cat_text = 'category_id in (' . implode(', ', $ids) . ')';	
		return wsActiveRecord::useStatic('Shoparticles')->count(array('stock not like "0" ', 'status = 3 ', $cat_text, $filter));
	}
        /**
         * Количество товара уцененного
         * @return type
         */
        public function getActiveProductCountSale()
	{
		$ids = array($this->getId());
                
		if($kids = $this->getKids())
                        {
                            foreach($kids as $kid){
                                $ids[] = $kid->getId();
                                
                            }
                        }
		$cat_text = 'dop_cat_id in (' . implode(', ', $ids) . ')';	
		return wsActiveRecord::useStatic('Shoparticles')->count(array('stock not like "0" ', 'status = 3 ', $cat_text));
	}
    public function getRoutez(){
        $a = '';
        if($this->parent){ if($this->parent->parent){ $a .= $this->parent->parent->getName().' : '; }
            $a .= $this->parent->getName().' : ';
        }
        $a .= $this->getName();
        return $a;

    }
    /**
     * Получаем данные первого потомка
     * @return object
     */
    public function getParentCategory()
    {
        $a = false;
        if($this->parent){
		if($this->parent->parent){
                    if($this->parent->parent->parent){
		return $this->parent->parent->parent;
                    }else{
		return $this->parent->parent;
                    }
		}else{
		return $this->parent;
		}
            
        }else{
		return  $this;
		}
    }

    /**
     * Название главной категории
     * @return string
     */
	public function getRoutezGolovna()
                {
        $a = '';
        if($this->parent){
		if($this->parent->parent){
                    if($this->parent->parent->parent){
		$a .= $this->parent->parent->parent->getName();
                    }else{
		$a .= $this->parent->parent->getName();
                    }
		}else{
		$a .= $this->parent->getName();
		}
            
        }else{
		$a .= $this->getName();
		}
        
        return $a;

    }
    static function getAllCategoryToPriceList(){
      $category =  wsActiveRecord::useStatic('Shopcategories')->findAll();
          $mas=array();
          foreach($category as $cat){
              $name = explode(' : ',$cat->getRoutez());
              if($name[0]=='РАСПРОДАЖА'){
                        $name[0] = @$name[1];
                        if($name[0]== 'Женская одежда') $name[0] = 'Женское';
                        if($name[0]== 'Мужская одежда') $name[0] = 'Мужское';
						if($name[0]== 'Детская одежда') $name[0] = 'Детское';
                        if($name[0]== 'Женская обувь') $name[0] = 'Обувь';
                        if($name[0]== 'Мужская обувь') $name[0] = 'Обувь';
						 if($name[0]== 'Детская обувь') $name[0] = 'Обувь';
                        unset($name[1]);
                    }
              if(@$name[1]=='Женская'){
                  $name[1] = $name[0];
                  $name[0] = 'Женское';
              }
                 if(@$name[1]=='Мужская'){
                  $name[1] = $name[0];
                  $name[0] = 'Мужское';
              }
               if(@$name[1]=='Женская обувь'){
                  $name[1] = $name[0];
                  $name[0] = 'Женское';
              }
                  if(@$name[1]=='Мужская обувь'){
                  $name[1] = $name[0];
                  $name[0] = 'Мужское';
              }
			   if(@$name[1]=='Детская обувь'){
                  $name[1] = $name[0];
                  $name[0] = 'Детское';
              }
              /*if(trim(@$name[1])=='Мужская одежда' or trim(@$name[1])=='Женская одежда' or trim(@$name[1])=='Одежда'){

                  unset($name[1]);
              }*/
              $mas[$cat->getId()]=implode(' - ',$name);

          }
          asort($mas);
        return $mas;
    }
	static function getAllCategoryToPriceList2(){
      $category =  wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>0));
          $mas=array();
          foreach($category as $cat){
		  $dop_category =  wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>$cat->getId()));
		  foreach($dop_category as $d_cat){
		  
		  $mas[$cat->getName()][$d_cat->getId()] = $d_cat->getName();//implode(' - ',$name);
		  }

          }
          asort($mas);
        return $mas;
    }
	static function CatName($id){
	$c =  wsActiveRecord::useStatic('Shopcategories')->findById($id);
	return $c->getRoutez();//$c['name'];
	
	}
	
}
