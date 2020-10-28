<?php
/**
 * class Filter - клас фильтрации товара
 *   function getArticlesFilter($search = '', $addtional = false, $category_id = false, $order_by = false,  $page, $onPage) 
 *   function getAllParametrs($where, $category_id, $type='', $search = '', $cat = false) Description
 */
class Filter extends wsActiveRecord
{
     /**
     * Фильт главный
     * 
     * @param type $addtional - массив критериев. $addtional = array('categories'=>array(), 'colors'=>array(), 'sizes'=>array(), 'labels'=>array(), 'brands'=>array(), 'sezons'=>array(), 'skidka'=>array(), 'price'=>array());
     * @param type $order_by - тип сортировки
     * @param type $page - отображаема страница
     * @param type $onPage - товаров на странице
     * @return  array(
		'search'=> $search,
                'count' => $total_articles,
                'articles' => $articles,
                'pages' => ceil($total_articles / $onPage),
		'parametr' =>  $param,
		'min_max' => $min_max_price
        )
     */
    public static function getArticlesFilter($filter)
	{
        
        //$search = '', $addtional = false, $category = false, $order_by = false,  $page = 0, $onPage = 30
        $search = $filter['search']?$filter['search']:''; //
        $category = $filter['category']?$filter['category']:false;
        $addtional = $filter['filter']?$filter['filter']:false; //
        $order_by = $filter['order_by']?$filter['order_by']:false; //
        $page = $filter['page']?$filter['page']:0; //
        $onPage = $filter['Onpage']?$filter['Onpage']:60; //
        
        $param = []; //
        
             
		$t_t = date("Y-m-d H:m:s", strtotime("-27 day"));
		
        $where = "
                  FROM ws_articles_sizes
					JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id 
					WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                 ";


       // $category = $category_id;//new Shopcategories($category_id);
        $category_kids = $category->getKidsIds();
		switch($category->id){
                case 106: if(!empty($_GET['code'])){ $cod = $_GET['code']; $where .= " and ws_articles.code in ( {$cod}) "; $onPage = 1000;  }else{ $where .= " AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0";} break;
                case 267: if(!empty($_GET['code'])){ $cod = $_GET['code']; $where .= " and ws_articles.code in ( {$cod} )"; $onPage = 1000; } break;
		default:  if(count($category_kids) > 0 and $category_kids[0])
                            {
                                $where .= ' AND (ws_articles.category_id IN (' . (implode(',', $category_kids)) . ') '
                                          . 'OR ws_articles.dop_cat_id IN (' . (implode(',', $category_kids)) . '))';
                    
                            }
                break;
		}

		
        if ($search)
            {
           
            $mas = explode(' ', $search);
            if (count($mas) > 1) {
                $find_slov = [];
                foreach ($mas as $v) {
                    $find_slov[] = '
                                (model like "%' . mysql_real_escape_string($v) . '%"
                                            or brand like "%' . mysql_real_escape_string($v) . '%"
                                 )';
                }
                $where .= ' AND ( (' . implode('AND ', $find_slov) . ') OR ' . '( model like "%' . mysql_real_escape_string($search) . '%" or model_uk like "%' . mysql_real_escape_string($search) . '%"
                                        or brand like "%' . mysql_real_escape_string($search) . '%"
                                        or long_text like "%' . mysql_real_escape_string($search) . '%" or long_text_uk like "%' . mysql_real_escape_string($search) . '%"))';

            }else{
                $where .= ' AND ( ws_articles.model like "%' . mysql_real_escape_string($search) . '%" or ws_articles.model_uk like "%' . mysql_real_escape_string($search) . '%"
            or ws_articles.brand like "%' . mysql_real_escape_string($search) . '%"
            or ws_articles.long_text like "%' . mysql_real_escape_string($search) . '%" or ws_articles.long_text_uk like "%' . mysql_real_escape_string($search) . '%" or ws_articles_sizes.code like "%' . mysql_real_escape_string($search) . '%" )';
            }
        }
        
        

		$param['newcat'] = self::getAllCategory($category);
		$param['categories'] = self::getAllParametrs($where, $category->id, 'categories', $search);
                
		return self::where($search, $category, $addtional, $order_by, $page, $onPage, $param, $where);
	/*	
               
        if (count($addtional['categories'])) {
             foreach ($param['categories'] as $value) {
if(in_array($value['id'], $addtional['categories'])){
                               $title[] = mb_strtolower($value['title']);
                        }
                }
   $where .= ' AND (ws_articles.category_id IN (' . implode(',', $addtional['categories']) . ') OR ws_articles.dop_cat_id IN (' . implode(',', $addtional['categories']) . '))';
        }
        

	$param['brands'] = self::getAllParametrs($where, $category->id, 'brands');	
            if (count($addtional['brands'])) {
                $where .= ' AND ws_articles.brand_id IN (' .implode(',', $addtional['brands']). ')';
            }
	
        $param['sezons'] = self::getAllParametrs($where, $category->id, 'sezons');     
            if (count($addtional['sezons'])) {      
                $where .= ' AND ws_articles.sezon IN ('.implode(',',$addtional['sezons']).')';
            }
            
	$param['colors'] = self::getAllParametrs($where, $category->id, 'colors');
            if (count($addtional['colors'])) {   
                $where .= ' AND ws_articles.color_id IN (' . implode(',', $addtional['colors']) . ')';
            }
	
        $param['sizes'] = self::getAllParametrs($where, $category->id, 'sizes');
            if (count($addtional['sizes'])) {
                $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
            }
	$param['labels'] = self::getAllParametrs($where, $category->id, 'labels');
            if (count($addtional['labels'])) {
           
                $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
            }

	$param['skidka'] = self::getAllParametrs($where, $category->id, 'skidka');
            if (count($addtional['skidka'])) {
                $where .= ' AND ws_articles.ucenka IN ('.implode(',', $addtional['skidka']).')';
        }

        
		$min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;
		
		if ($addtional['price']['min']) { $where .= ' AND ws_articles.price >= ' . $addtional['price']['min'];}
		if ($addtional['price']['max']) {  $where .= ' and ws_articles.price <= ' . $addtional['price']['max'];}
		

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' .$where;

        $total_articles = wsActiveRecord::findByQueryArray($count_article_query)[0]->cnt;
		//d($total_articles, false);
        if (!$total_articles){
			$rest = iconv_substr($search, 0, -1, 'UTF-8');
			$total_articles = 0;
			$where = str_replace($search, mysql_real_escape_string($rest), $where);
                      
			}
			
        $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' .$where. ' ORDER BY ' . $order_by . ' LIMIT ' . $page * $onPage . ',' . $onPage;
		
//d($articles_query, false);

        $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
        $min_max_price =  Shoparticles::findByQueryArray($min_max_price);
       // d($min_max_price,false);
                if($min_max_price[0]->min) {$param['price_min'] = $min_max_price[0]->min;}else{$param['price_min'] = 0;}
                if($min_max_price[0]->max){$param['price_max'] = $min_max_price[0]->max;}else{$param['price_max'] = 0;}
		//$this->view->filters =;
	//$this->view->filters = $param;
        //$this->view->total_pages = ceil($total_articles / $onPage);
        
        return array(
            'search'=> $search,
            'count' => $total_articles,
            'articles' => $articles,
            'pages' => ceil($total_articles / $onPage),
            'parametr' =>  $param,
           // 'meta' => $meta,
            //'min_max' => $min_max_price
        );
         * */
         
    }
	
    /**
     * Дочерние категории, отображаются слева на странице
     * @param type $where - условие
     * @param type $category - категория которая вызвала
     * @param type $search - текст поиска
     */
    public static function getAllCategory($category = false)
    {
        $array = [];
        $parent = $category->getParentCategory();
            switch ($parent->id)
            {
               case 106: 
                   $sql = 'SELECT  * FROM  `ws_categories` WHERE  `ws_categories`.`parent_id` = 0 and `ws_categories`.`id` not in( 85, 106,267) and `ws_categories`.`active` = 1';
                   break;
            //  case 85: 
                 //   $sql = 'SELECT  * FROM  `ws_categories` WHERE  `ws_categories`.`controller` LIKE "sale" and `ws_categories`.`active` = 1';
                  break;
               case 0: $sql = ''; break;
               case 146: $sql = 'SELECT  * FROM  `ws_categories` WHERE  `ws_categories`.`id` = 146 and `ws_categories`.`active` = 1'; break;
               case 267: $sql = 'SELECT  * FROM  `ws_categories` WHERE  `ws_categories`.`parent_id` = 0 and `ws_categories`.`id` not in( 85, 106,267) and `ws_categories`.`active` = 1';
                   break;
               default: $sql = 'SELECT  * FROM  `ws_categories` WHERE  `ws_categories`.`parent_id` = '.$parent->id.'  and `ws_categories`.`active` = 1'; break;
            }
           // $c = '';

       
           
           // d($sql, false);
            $categories = wsActiveRecord::useStatic('Shopcategories')->findByQuery($sql);
         foreach ($categories as $cat) {
             if (in_array($parent->id, array(106, 85, 267, 0))){
                 switch ($parent->id){
                     case 106: 
                         if($cat->getActiveProductCountFilter('label_id = 13')){
                             if($cat->kids){
                        $array[$cat->id] = [
                    'url' => $cat->getPath().'labels-13/',
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCountFilter('label_id = 13'),
                    'title' => $cat->getName() 
                             ];
                             foreach ($cat->kids as $c){
                                 if($c->getActiveProductCountFilter('label_id = 13')){
                                    $array[$cat->id]['kids'][] = [
                    'url' => $c->getPath().'labels-13/',
                    'id' => $c->id,
                    'name' => $c->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $c->getActiveProductCountFilter('label_id = 13'),
                    'title' => $c->getRoutez()
                          
                      ]; 
                                 }
                    
                                 
                             }
                                 
                             }else{
                    $array[$cat->id] = [
                    'url' => $cat->getPath().'labels-13/',
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCountFilter('label_id = 13'),
                    'title' => $cat->getName() 
                             ];
                    }
                         }
                         break;
                     case 85:  
                         if($cat->getActiveProductCountSale()){
                              if($cat->kids){
                        $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCountSale(),
                    'title' => $cat->getName() 
                      ]; 
                         foreach ($cat->getKids() as $v) {
                    $array[$cat->id]['kids'][$v->id] = [
                    'url' => $v->getPath(),
                    'id' => $v->id,
                    'name' => $v->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $v->getActiveProductCountSale(),
                    'title' => $v->getName()  
                      ]; 
            }
                        
                            
                                 
                             }else{
                    $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCountSale(),
                    'title' => $cat->getName() 
                      ]; 
                             }
                         }
                         
                         break;
                         case 267:  
                         if($cat->getActiveProductCount()){
                             if($cat->kids){
                                  $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCount(),
                    'title' => $cat->getName() 
                             ];
                             foreach ($cat->kids as $c){
                                 if($c->getActiveProductCount()){
                                    $array[$cat->id]['kids'][] = [
                    'url' => $c->getPath(),
                    'id' => $c->id,
                    'name' => $c->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $c->getActiveProductCount(),
                    'title' => $c->getRoutez()
                          
                      ]; 
                                 }
                    
                                 
                             }
                                 
                             }else{
                    $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCount(),
                    'title' => $cat->getName() 
                             ];
                    }
                         }
                         
                         break; 
                     default : break;
                     
                 }
            }else{
                if($cat->getActiveProductCount()){
                 if($cat->getKids()){
                     $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCount(),
                    'title' => $cat->getName()  
                      ]; 
            foreach ($cat->getKids() as $v) {
                    $array[$cat->id]['kids'][$v->id] = [
                    'url' => $v->getPath(),
                    'id' => $v->id,
                    'name' => $v->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $v->getActiveProductCount(),
                    'title' => $v->getName()  
                      ]; 
            }
                    }else{ 
                    $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCount(),
                    'title' => $cat->getName()  
                      ]; 
                    }
            }
            }
             
         }
        
        return $array;
    }

        /**
         * 
         * @param type $where
         * @param type $category_id
         * @param type $type
         * @param type $search
         * @param string $cat
         * @return type
         */
    public static function getAllParametrs($where, $category_id, $type='', $search = '', $cat = false)
	{
		$array = [];
		switch($type){
		case 'categories': //Categories
        $categories = 'SELECT ws_articles.category_id, count(distinct(ws_articles.id)) AS cnt  ' . $where;
                    if(!empty($category_id)) {
                       $categories .= ' and ws_articles.category_id != '.$category_id; 
                    }
                  $categories .=  ' group by ws_articles.category_id  order by ws_articles.category_id';
       //d($categories, false);
        $categories = wsActiveRecord::useStatic('Shoparticles')->findByQuery($categories);
        //l($categories);
        //convert to array
        foreach ($categories as $cat) {
                if (in_array($category_id, array(106, 85, 299, 300, 301, 302, 303, 11, 267, 0)) or $search != '' ){
                    if($cat->category->parent->parent_id == 0){
                $array[$cat->category->id] = [
                    'url' => $cat->category->getNewPath(),
                    'id' => $cat->category_id,
                    'name' => $cat->category->getRoutez(),
                    'parent' => $cat->category->getRoutezGolovna(), 
                    'count' => $cat->cnt,
                    'title' => $cat->category->getName() 
                      ];   
                    }else{
                $array[$cat->category->parent_id]['kids'][] = [
                    'url' => $cat->category->getNewPath(),
                    'id' => $cat->category_id,
                    'name' => $cat->category->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $cat->cnt,
                    'title' => $cat->category->getRoutez()
                          
                      ]; 
                        
                    }
            }else{
                 if($cat->category->parent->parent_id == 0){
                    $array[$cat->category->id] = [
                    'url' => $cat->category->getNewPath(),
                    'id' => $cat->category_id,
                    'name' => $cat->category->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $cat->cnt,
                    'title' => $cat->category->getName()  
                      ];   
                    }else{
                    $array[$cat->category->parent_id]['kids'][] = [
                    'url' => $cat->category->getNewPath(),
                    'id' => $cat->category_id,
                    'name' => $cat->category->getRoutez(),
                    'parent' => '',//$cat->category->getRoutezGolovna(), 
                    'count' => $cat->cnt,
                    'title' => $cat->category->getName()  
                      ]; 
                    }
            }
              
             

        } break;
		
		case 'brands': 
                    $brands = Shoparticles::findByQueryArray('SELECT `ws_articles`.brand_id, `ws_articles`.brand, count(distinct(ws_articles.id)) AS cnt '. $where.' GROUP BY ws_articles.brand_id ORDER BY `ws_articles`.brand ');
                        foreach ($brands as $brand) {
                            $b = str_replace('&', '_', $brand->brand);
                            $b = strtolower(str_replace(' ', '_', $brand->brand));
                            $array[] = ['id' => $brand->brand_id, 'name' => $b, 'count' =>  $brand->cnt, 'title' =>$brand->brand ]; //urlencode()
                        }
                break;
		case 'sezons': $sezons = 'SELECT `ws_articles`.`sezon`, count(distinct(ws_articles.id)) AS ctn ' . $where.' AND  `ws_articles`.`sezon` IS NOT NULL  group by `ws_articles`.`sezon`';
        $sezons = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sezons);
        foreach ($sezons as $sez) {
		if($sez->sezon > 0){
            $array[] = array(
			'id' => $sez->name_sezon->id,
                        'translate' => $sez->name_sezon->translate,
			'name' => $sez->name_sezon->getName(),
			'count' =>  $sez->ctn,
                        'title' => $sez->name_sezon->getName()
			);
			}
        } break;
		case 'sizes': $sizes = 'SELECT ws_articles_sizes.id_size, COUNT(distinct(ws_articles_sizes.id_article)) AS ctn ' . $where .' group by ws_articles_sizes.id_size';
        $sizes = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sizes);
        foreach ($sizes as $size) {
			 $array[] = array(
                                'id' => $size->id_size,
                                'name' => $size->size->getSize(),
				'count' =>$size->ctn,
                                'title' => $size->size->getSize()
				
            );
        } break;
		case 'colors': $colors = 'SELECT  ws_articles.color_id, count(distinct(ws_articles.id)) AS cnt  ' . $where.' group by ws_articles.color_id';
        $colors = wsActiveRecord::useStatic('Shoparticles')->findByQuery($colors);
        foreach ($colors as $col) {
            if($col->color_id > 0){
		$array[] = array(
                'id' => $col->color_id,
                'name' => $col->color_name->getName(),
		'count' => $col->cnt,
                'title' => $col->color_name->getName()
            );
        }
        } break;
		case 'labels': $labels = 'SELECT ws_articles.label_id, count(distinct(ws_articles.id)) AS ctn ' . $where.' and ws_articles.label_id !=0 GROUP BY ws_articles.label_id';
        $labels = wsActiveRecord::useStatic('Shoparticles')->findByQuery($labels);
		$array = array();
        foreach ($labels as $lab) {
		 $array[] = array(
                'id' => $lab->label_id,
                'name' => $lab->label->getName(),
				'count' => $lab->ctn
            );
        } break;
		case 'skidka': $ucenka = 'SELECT `ws_articles`.`ucenka`,count(distinct(ws_articles.id)) AS cnt ' . $where;
        $ucenka = Shoparticles::findByQueryArray($ucenka.' and `ws_articles`.`ucenka` != 0  group by `ws_articles`.`ucenka`');
        foreach ($ucenka as $uc) {
		if($uc->cnt > 0){
            $array[] = array(
			'id' => $uc->ucenka,
			'name' => '- '.$uc->ucenka.' %',
			'count' =>  $uc->cnt
			);
			}
        } break;
		default:

		break;
		}
		
		return $array;
    }
    /**
     * Список выбраных товаров
     * 
     * @param type $articles - массив товаров
     * @param type $order_by - тип сортировки
     * @param type $page - отображаема страница
     * @param type $onPage - товаров на странице
     * @return array список полученіх товаров
     */
    public static function getArticlesList($articles, $addtional = false, $order_by = false,  $page, $onPage)
    {
      $meta = [];
        //order by
		switch($order_by){
                case 'cena_vozrastaniyu': $order_by = 'price ASC'; $meta['noindex'] = true;  break;
                case 'cena_ubyvaniyu': $order_by = 'price DESC'; $meta['noindex'] = true;  break;
                case 'populyarnost_vozrastaniyu': $order_by = 'views ASC'; $meta['noindex'] = true;  break;
                case 'populyarnost_spadaniyu': $order_by = 'views DESC'; $meta['noindex'] = true;  break;
                case 'date_vozrastaniye': $order_by = 'ctime ASC'; $meta['noindex'] = true;  break;
                case 'date_spadaniye': $order_by = 'ctime DESC'; $meta['noindex'] = true;  break;
		default:  $order_by = 'ctime DESC';
		}
             
              $where = "
                  FROM ws_articles_sizes
					JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id 
					WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                 ";  
                
                $param = [];
                
               if (count($articles)) { $where .= ' AND ws_articles.id in ('.$articles.') '; } 
               
               
               
                $param['categories'] = self::getAllParametrs($where, '', 'categories', '', true);
		$param['brands'] = self::getAllParametrs($where, '', 'brands');
		$param['sezons'] = self::getAllParametrs($where, '', 'sezons');
		$param['colors'] = self::getAllParametrs($where, '', 'colors');
		$param['sizes'] = self::getAllParametrs($where, '', 'sizes');
		$param['labels'] = self::getAllParametrs($where, '', 'labels');
		$param['skidka'] = self::getAllParametrs($where, '', 'skidka');
                
        $min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' .$where;
        $total_articles = Shoparticles::findByQueryArray($count_article_query)[0]->cnt;
        if (!$total_articles){ $total_articles = 0; }
			
        $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' .$where. ' ORDER BY ' . $order_by . ' LIMIT ' . $page * $onPage . ',' . $onPage;	
        $articles_list = Shoparticles::useStatic('Shoparticles')->findByQuery($articles_query);
        $min_max_price =  Shoparticles::findByQueryArray($min_max_price);
       $param['price_min'] = $min_max_price[0]->min;
       $param['price_max'] = $min_max_price[0]->max;
		
        return array(
            'count' => $total_articles,
            'articles' => $articles_list,
            'pages' => ceil($total_articles / $onPage),
            'parametr' =>  $param
        );
        
    }
    /**
     * Список товаров участвующих в акции
     * 
     * @param type $articles - массив товаров
     * @param type $order_by - тип сортировки
     * @param type $page - отображаема страница
     * @param type $onPage - товаров на странице
     * @return array список полученіх товаров
     */
    public static function getArticlesOptionList($filter)
    {
        $options =  $filter['option'];//=  Shoparticlesoption::useStatic('Shoparticlesoption')->findById((int)$option);
         
         if($options){
        $addtional = $filter['filter']?$filter['filter']:false; //
        $order_by = $filter['order_by']?$filter['order_by']:false; //
        $page = $filter['page']?$filter['page']:0; //
        $onPage = $filter['Onpage']?$filter['Onpage']:60; //
             $param = [];

        
              $where = " FROM ws_articles_sizes
					JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id ";

              switch ($options->action)
              {
                  case 'article':  
                      $where .=" JOIN ws_articles_options on ws_articles.id = ws_articles_options.article_id"
                             . " WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                                                AND ws_articles_options.option_id = ".$options->id;
                      break;
                  case 'category': 
                      $where .=" JOIN ws_articles_options on ws_articles.category_id = ws_articles_options.category_id"
                             . " WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                                                and ws_articles.old_price != 0.00
                                                AND ws_articles_options.option_id = ".$options->id;
                      break;
                  case 'brand': 
                      $where .=" JOIN ws_articles_options on ws_articles.brand_id = ws_articles_options.brand_id"
                             . " WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                                                AND ws_articles_options.option_id = ".$options->id;
                      break;
                  case 'sezon': 
                      $where .=" JOIN ws_articles_options on ws_articles.sezon = ws_articles_options.sezon_id"
                             . " WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                                                AND ws_articles_options.option_id = ".$options->id;
                      break;
                  default: 
                      $where .= " WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3";
                      break;
              }
              $param['categories'] = self::getAllParametrs($where, $category->id, 'categories', $search);
           return self:: where('', false, $addtional, $order_by, $page, $onPage, $param, $where);

         }else{
             return false;
         }
        
    }
    static private function where($search = '', $category = false, $addtional, $order_by = false, $page = 0, $onPage = 60, $param, $where = '') {
        
        switch($order_by){
                case 'cena_vozrastaniyu': $order_by = 'price ASC'; break;
                case 'cena_ubyvaniyu': $order_by = 'price DESC'; break;
                case 'populyarnost_vozrastaniyu': $order_by = 'views ASC';  break;
                case 'populyarnost_spadaniyu': $order_by = 'views DESC';  break;
                case 'date_vozrastaniye': $order_by = 'ctime ASC';   break;
                case 'date_spadaniye': $order_by = 'ctime DESC';  break;
		default:  $order_by = 'data_new DESC';
                   // default:  $order_by = 'views DESC';
		}
        
        $cat = $category?$category->id:'';
        $param['brands'] = self::getAllParametrs($where, $cat, 'brands');	
            if (count($addtional['brands'])) {
                $where .= ' AND ws_articles.brand_id IN (' .implode(',', $addtional['brands']). ')';
            }
	
        $param['sezons'] = self::getAllParametrs($where, $cat, 'sezons');     
            if (count($addtional['sezons'])) {      
                $where .= ' AND ws_articles.sezon IN ('.implode(',',$addtional['sezons']).')';
            }
            
	$param['colors'] = self::getAllParametrs($where, $cat, 'colors');
            if (count($addtional['colors'])) {   
                $where .= ' AND ws_articles.color_id IN (' . implode(',', $addtional['colors']) . ')';
            }
	
        $param['sizes'] = self::getAllParametrs($where, $cat, 'sizes');
            if (count($addtional['sizes'])) {
                $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
            }
	$param['labels'] = self::getAllParametrs($where, $cat, 'labels');
            if (count($addtional['labels'])) {
           
                $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
            }

	$param['skidka'] = self::getAllParametrs($where, $cat, 'skidka');
            if (count($addtional['skidka'])) {
                $where .= ' AND ws_articles.ucenka IN ('.implode(',', $addtional['skidka']).')';
        }

        $min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;
		
		if ($addtional['price']['min']) { $where .= ' AND ws_articles.price >= ' . $addtional['price']['min'];}
		if ($addtional['price']['max']) {  $where .= ' and ws_articles.price <= ' . $addtional['price']['max'];}
		

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' .$where;

        $total_articles = wsActiveRecord::findByQueryArray($count_article_query)[0]->cnt;
        if (!$total_articles){
			$rest = iconv_substr($search, 0, -1, 'UTF-8');
			$total_articles = 0;
			$where = str_replace($search, mysql_real_escape_string($rest), $where);
                      
			}
			
        $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' .$where. ' ORDER BY ' . $order_by . ' LIMIT ' . $page * $onPage . ',' . $onPage;
        $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);
        
        $min_max_price =  Shoparticles::findByQueryArray($min_max_price);
                if($min_max_price[0]->min) {$param['price_min'] = $min_max_price[0]->min;}else{$param['price_min'] = 0;}
                if($min_max_price[0]->max){$param['price_max'] = $min_max_price[0]->max;}else{$param['price_max'] = 0;}
        
         return [
            'search'=> $search,
            'count' => $total_articles,
            'articles' => $articles,
            'pages' => ceil($total_articles / $onPage),
            'parametr' =>  $param,
        ];
    }
    
	
			
}