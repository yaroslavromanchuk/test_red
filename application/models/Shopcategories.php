<?php
class Shopcategories extends wsActiveRecord
{
	protected $_table = 'ws_categories';
	protected $_orderby = array('sequence'=>'ASC');

	protected $_multilang = array('name' => 'name', 'title'=>'title', 'description'=>'description', 'footer'=>'footer', 'h1'=>'h1');
									
	protected function _defineRelations()
	{	
		$this->_relations = array(	'parent' => array(
										'type'=>'hasOne',
										'class'=>self::$_shop_categories_class,
										'field'=>'parent_id'),
										
									'articles' => array('type' => 'hasMany',
													'class' => self::$_shop_articles_class,
													'field_foreign' => 'category_id',
													'orderby' => array('sequence' => 'DESC'),
													),
									'kids' => array('type' => 'hasMany',
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
		return $this->_generateUrl($this->getName());
	}
	
	public function getPath()
    {
        $items = array();
        $result = '';

        if (!$this->getParents()) return '/category/id/' .$this->getId() .'/'. $this->getUrl() . '/';

        foreach ($this->getParents() as $item){
		$result .= $item->getUrl().'/';
		}

        $result .= $this->getUrl() . '/';

		return '/category/id/' .$this->getId().'/'. $result;
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
    	if ($this->_parents)
    		return $this->_parents;
        $parent = null;
		if($show_myself)
			$add = array($this);
		else
			$add = null;
        if ( ($this->getParentId() == -1) || !$this->getParentId() )
            return $add;
        if (!$parent = $this->getParent())
        	return $add;

        $parents = array();

		if($show_myself)
			 $parents[] = $this;
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
    	$p = $this->getParents(1);
    	$res = array();

    	if($p)
    	foreach($p as $parent)
    		$res[] = '<a href="' . $parent->getPath() . '">' . $parent->getName() . '</a>';
    	$ret = implode(' <span class="sep">:</span> ', $res);

    	if(!$links)
    		return strip_tags($ret);
    	else
    		return $ret;
    }

	public function getKidsIds()
	{
		$ids = array($this->getId());
		if($kids = $this->getKids())
			foreach($kids as $kid)
			{
				$kid_ids = $kid->getKidsIds();
				foreach($kid_ids as $id)
					$ids[] = $id;
			}
		return $ids;
	}
    
	public function getActiveProductCount()
	{
		$ids = array($this->getId());
		if($kids = $this->getKids())
			foreach($kids as $kid)
				$ids[] = $kid->getId();
		$cat_text = 'category_id in (' . implode(', ', $ids) . ')';	
		return wsActiveRecord::useStatic('Shoparticles')->count(array('stock > 0', 'active="y" ', $cat_text));
	}
    public function getRoutez(){
        $a = '';
        if($this->parent){ if($this->parent->parent){ $a .= $this->parent->parent->getName().' : '; }
            $a .= $this->parent->getName().' : ';
        }
        $a .= $this->getName();
        return $a;

    }
	public function getRoutezGolovna(){
        $a = '';
        if($this->parent){
		if($this->parent->parent){
		if($this->parent->parent->parent){
		$a .= $this->parent->parent->getName();
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
            /*  $name = explode(' : ',$cat->getRoutez());
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
              }*/
              /*if(trim(@$name[1])=='Мужская одежда' or trim(@$name[1])=='Женская одежда' or trim(@$name[1])=='Одежда'){

                  unset($name[1]);
              }*/
              //$mas[$cat->getId()][$cat->getParentId()]=implode(' - ',$name);

          }
          asort($mas);
        return $mas;
    }
	static function CatName($id){
	$c =  wsActiveRecord::useStatic('Shopcategories')->findById($id);
	return $c->getRoutez();//$c['name'];
	
	}
	
}
?>