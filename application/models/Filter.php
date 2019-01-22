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
    public static function getArticlesFilter($search = '', $addtional = false, $category = false, $order_by = false,  $page = 0, $onPage = 30)
	{
        $param = [];
        $meta = [];
        $title =[];
        //order by
        
		switch($order_by){
                case 'cena_vozrastaniyu': $order_by = 'price ASC'; $meta['nofollow'] = 1;  $meta['footer']['block'] = 'tut';  break;
                case 'cena_ubyvaniyu': $order_by = 'price DESC'; $meta['nofollow'] = 1;  $meta['footer']['block'] = 'tut';  break;
                case 'populyarnost_vozrastaniyu': $order_by = 'views ASC'; $meta['nofollow'] = 1;  $meta['footer']['block'] = 'tut';  break;
                case 'populyarnost_spadaniyu': $order_by = 'views DESC'; $meta['nofollow'] = 1;  $meta['footer']['block'] = 'tut';  break;
                case 'date_vozrastaniye': $order_by = 'ctime ASC'; $meta['nofollow'] = 1;  $meta['footer']['block'] = 'tut';  break;
                case 'date_spadaniye': $order_by = 'ctime DESC'; $meta['nofollow'] = 1;  $meta['footer']['block'] = 'tut';  break;
		default:  $order_by = 'ctime DESC';
		}
        
                if($page){
                   // $meta['nofollow'] = 1;
                    $meta['footer']['block'] = 'tut';
                }
		
		$t_t = date("Y-m-d H:m:s", strtotime("-5 day"));
		
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
		case 106: $where .= " AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0"; break;
                case 267: break;
		default:  if(count($category_kids) > 0 and $category_kids[0])
                            {
                                $where .= ' AND (ws_articles.category_id IN (' . (implode(',', $category_kids)) . ') '
                                          . 'OR ws_articles.dop_cat_id IN (' . (implode(',', $category_kids)) . '))';
                    
                            }
                break;
		}

		
        if ($search)
            {
            $meta['footer']['block'] = 'tut';
            $meta['nofollow'] = 1;
            $title[] = $search;
            $mas = explode(' ', $search);
            if (count($mas) > 1) {

                $find_slov = array();
                foreach ($mas as $v) {
                    $find_slov[] = '
                                (model like "%' . mysql_real_escape_string($v) . '%"
                                            or brand like "%' . mysql_real_escape_string($v) . '%"
                                 )';
                }
                $where .= ' AND ( (' . implode('AND ', $find_slov) . ') OR ' . '( model like "%' . mysql_real_escape_string($search) . '%" or model_uk like "%' . mysql_real_escape_string($search) . '%"
                                        or brand like "%' . mysql_real_escape_string($search) . '%"
                                        or long_text like "%' . mysql_real_escape_string($search) . '%" or long_text_uk like "%' . mysql_real_escape_string($search) . '%"))';

            } else {
                $where .= ' AND ( ws_articles.model like "%' . mysql_real_escape_string($search) . '%" or ws_articles.model_uk like "%' . mysql_real_escape_string($search) . '%"
            or ws_articles.brand like "%' . mysql_real_escape_string($search) . '%"
            or ws_articles.long_text like "%' . mysql_real_escape_string($search) . '%" or ws_articles.long_text_uk like "%' . mysql_real_escape_string($search) . '%" or ws_articles_sizes.code like "%' . mysql_real_escape_string($search) . '%" )';
            }
        }

		
		

		//$min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;
		
		//if (@$addtional['price']['min']) $where .= ' AND ws_articles.price >= ' . $addtional['price']['min'];
		//if (@$addtional['price']['max']) $where .= ' and ws_articles.price <= ' . $addtional['price']['max'];
		$param['newcat'] = self::getAllCategory($category);
		$param['categories'] = self::getAllParametrs($where, $category->id, 'categories', $search);
		
		
               
        if (count($addtional['categories'])) {
             foreach ($param['categories'] as $value) {
if(in_array($value['id'], $addtional['categories'])){
                               $title[] = mb_strtolower($value['title']);
                        }
                }
              $meta['footer']['block'] = 'tut';
   $where .= ' AND (ws_articles.category_id IN (' . implode(',', $addtional['categories']) . ') OR ws_articles.dop_cat_id IN (' . implode(',', $addtional['categories']) . '))';
        }
      //  d($where, false);
		
		$param['brands'] = self::getAllParametrs($where, $category->id, 'brands');
		
		if (count($addtional['brands'])) {
                  foreach ($param['brands'] as $value) {
                       if(in_array($value['id'], $addtional['brands'])){
                             $title[] = $value['title'];
                        }
                }
                 if(count($addtional) == 1 and count($addtional['brands']) == 1){
                  $text = FooterText::Text(['category_id' =>$category->id, 'brand_id' => $addtional['brands'][0]]);
                  if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
                
               // $meta['footer']['block'] = 'tut';
             
            $where .= ' AND ws_articles.brand_id IN (' . implode(',', $addtional['brands']) . ')';
           // $where .= ' AND ws_articles.brand_id IN (' . $addtional['brands'] . ')';
        }
        // d($where, false);
		$param['sezons'] = self::getAllParametrs($where, $category->id, 'sezons');
                
	if (count($addtional['sezons'])) {
            foreach ($param['sezons'] as $value) {
                if(in_array($value['id'], $addtional['sezons'])){
                             $title[] = $value['title'];
                        }
                }
                
                if(count($addtional) == 1 and count($addtional['sezons']) == 1){
                 $text = FooterText::Text(['category_id' =>$category->id, 'sezon_id' => $addtional['sezons'][0]]);
                 if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
                
                   
                    
            $where .= ' AND ws_articles.sezon IN ('.implode(',',$addtional['sezons']).')';
            //$where .= ' AND ws_articles.sezon IN ('.$addtional['sezons'].')';
        }
        //d($where, false);
		$param['colors'] = self::getAllParametrs($where, $category->id, 'colors');
        if (count($addtional['colors'])) {
            foreach ($param['colors'] as $value) {
                if(in_array($value['id'], $addtional['colors'])){
                                 $title[] = mb_strtolower($value['title']);
                        }
                }
                
                if(count($addtional) == 1 and count($addtional['colors']) == 1){
                 $text = FooterText::Text(['category_id' =>$category->id, 'color_id' => $addtional['colors'][0]]);
                 if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
                
            $where .= ' AND ws_articles.color_id IN (' . implode(',', $addtional['colors']) . ')';
             //$where .= ' AND ws_articles.color_id IN (' . $addtional['colors'] . ')';
          // $meta['footer']['block'] = 'tut';
        }
		$param['sizes'] = self::getAllParametrs($where, $category->id, 'sizes');
        if (count($addtional['sizes'])) {
            foreach ($param['sizes'] as $value) {
                if(in_array($value['id'], $addtional['sizes'])){
                                 $title[] = mb_strtolower($value['title']);
                        }
                }
                
                if(count($addtional) == 1 and count($addtional['sizes']) == 1){
                  $text = FooterText::Text(['category_id' =>$category->id, 'size_id' => $addtional['sizes'][0]]);
                    if($text){
                     $meta['footer']['text'] = $text;
                 }else{
                      $meta['footer']['block'] = 'tut';
                 }
                }else{
                    $meta['footer']['block'] = 'tut';
                }
                
            $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
            //$where .= ' AND ws_articles_sizes.id_size IN (' . $addtional['sizes'] . ')';
           // $meta['footer']['block'] = 'tut';
        }
		$param['labels'] = self::getAllParametrs($where, $category->id, 'labels');
        if (count($addtional['labels'])) {
            $meta['nofollow'] = 1;
            $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
            //$where .= ' AND ws_articles.label_id IN ('.$addtional['labels'].')';
            $meta['footer']['block'] = 'tut';
        }

	$param['skidka'] = self::getAllParametrs($where, $category->id, 'skidka');
	if (count($addtional['skidka'])) {
                $meta['nofollow'] = 1;
            $where .= ' AND ws_articles.ucenka IN ('.implode(',', $addtional['skidka']).')';
           // var_dump($where);
             //$where .= ' AND ws_articles.ucenka IN ('.$addtional['skidka'].')';
          $meta['footer']['block'] = 'tut';
        }

        $title = ' '.implode(', ', $title);
        
        $meta['h1'] = ($category->getH1()?$category->getH1():$category->getName()).' '.$title.' '.Translator::get('в интернет магазине RED');
        
        $title_obr = explode(' ', ($category->getH1()?$category->getH1():$category->getName()));
        krsort($title_obr);
        $title_obr = implode(' ', $title_obr);
        if($category->parent_id == 85){
             $meta['title'] = trim(($category->getTitle()?$category->getTitle():$category->getName()));
        }else{
            $meta['title'] = trim(($category->getTitle()?$category->getTitle():$category->getName()).' '.$title.' '.Translator::get('dop_title'));
        }
        
        if($category->id){
            if($category->id == 85){
                $meta['descriptions'] =$category->getDescription();
            }elseif($category->parent_id == 85){
                $meta['descriptions'] = $category->getH1().' '.mb_strtolower($title).' '.Translator::get('в интернет магазине RED').$category->getDescription();
            }else{
             $meta['descriptions'] = $category->getRoutezGolovna().' '.Translator::get('в интернет магазине RED').' '.Translator::get('покупайте').' '.mb_strtolower($title_obr).mb_strtolower($title).' '.Translator::get('description_exit');
            }
             
            }else{
             $meta['descriptions'] = trim($title).' '.Translator::get('в интернет магазине RED').' '.Translator::get('покупайте').' '.mb_strtolower($title_obr).mb_strtolower($title).' '.Translator::get('description_exit');
        }
       
		
		$min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;
		
		if ($addtional['price']['min']) { $meta['nofollow'] = 1; $where .= ' AND ws_articles.price >= ' . $addtional['price']['min'];}
		if ($addtional['price']['max']) {  $meta['nofollow'] = 1; $where .= ' and ws_articles.price <= ' . $addtional['price']['max'];}
		
//d($where, false);

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' .$where;
//d($count_article_query, false);
       


        $total_articles = wsActiveRecord::findByQueryArray($count_article_query)[0]->cnt;
		//d($total_articles, false);
        if (!$total_articles){
			$rest = iconv_substr($search, 0, -1, 'UTF-8');
			$total_articles = 0;
			$where = str_replace($search, mysql_real_escape_string($rest), $where);
                        $meta['nofollow'] = 1;
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
            'meta' => $meta,
            //'min_max' => $min_max_price
        );
    }
	
    /**
     * 
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
               case 85: 
                    $sql = 'SELECT  * FROM  `ws_categories` WHERE  `ws_categories`.`parent_id` = 85 and `ws_categories`.`active` = 1';
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
                    'url' => $cat->getPath().'labels-13',
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCountFilter('label_id = 13'),
                    'title' => $cat->getName() 
                             ];
                             foreach ($cat->kids as $c){
                                 if($c->getActiveProductCountFilter('label_id = 13')){
                                    $array[$cat->id]['kids'][] = [
                    'url' => $c->getPath().'labels-13',
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
                    'url' => $cat->getPath().'labels-13',
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
                    $array[$cat->id] = [
                    'url' => $cat->getPath(),
                    'id' => $cat->id,
                    'name' => $cat->getRoutez(),
                    'parent' => '',//$cat->getRoutezGolovna(), 
                    'count' => $cat->getActiveProductCountSale(),
                    'title' => $cat->getName() 
                      ]; 
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
        $categories = 'SELECT ws_articles.category_id, count(distinct(ws_articles.id)) AS cnt  ' . $where.' and ws_articles.category_id != '.$category_id.'  group by ws_articles.category_id  order by ws_articles.category_id';
       //d($categories, false);
                    $categories = wsActiveRecord::useStatic('Shoparticles')->findByQuery($categories);
        //convert to array
        foreach ($categories as $cat) {
         /*   if($cat->category->getKids()->count() == 0){
                
                 $array[] = [
                    'url' => $cat->category->getNewPath(),
                    'id' => $cat->category_id,
                    'name' => $cat->category->getName(),
                    'parent' => '',
                    'count' => $cat->cnt,
                    'title' => $cat->category->getName()
                ];
            }else{
                foreach ($cat->category->getKids() as $v) {
                    $array[] = [
                    'url' => $v->getNewPath(),
                    'id' => $v->id,
                    'name' => $v->getName(),
                    'parent' => '',
                    'count' => '',
                    'title' => $v->getName()
                ];
                }
                
            }*/
            
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
                            $array[] = ['id' => $brand->brand_id, 'name' => strtolower($brand->brand), 'count' =>  $brand->cnt, 'title' =>$brand->brand ];
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
                case 'cena_vozrastaniyu': $order_by = 'price ASC'; $meta['nofollow'] = true;  break;
                case 'cena_ubyvaniyu': $order_by = 'price DESC'; $meta['nofollow'] = true;  break;
                case 'populyarnost_vozrastaniyu': $order_by = 'views ASC'; $meta['nofollow'] = true;  break;
                case 'populyarnost_spadaniyu': $order_by = 'views DESC'; $meta['nofollow'] = true;  break;
                case 'date_vozrastaniye': $order_by = 'ctime ASC'; $meta['nofollow'] = true;  break;
                case 'date_spadaniye': $order_by = 'ctime DESC'; $meta['nofollow'] = true;  break;
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
//d($count_article_query, false);
        $total_articles = Shoparticles::findByQueryArray($count_article_query)[0]->cnt;
		//d($total_articles, false);
        if (!$total_articles){ $total_articles = 0; }
			
        $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' .$where. ' ORDER BY ' . $order_by . ' LIMIT ' . $page * $onPage . ',' . $onPage;	
//d($articles_query, false);
        $articles_list = Shoparticles::useStatic('Shoparticles')->findByQuery($articles_query);
      //  d($articles_list, false);
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
    public static function getArticlesOptionList($option, $addtional = false, $order_by = false,  $page, $onPage)
    {
        $options =  $option;//=  Shoparticlesoption::useStatic('Shoparticlesoption')->findById((int)$option);
         
         if($options){
             //var_dump($option);
             $param = [];
        $meta = [];
        $title =[];
             
        
        //order by
		switch($order_by){
                case 'cena_vozrastaniyu': $order_by = 'price ASC'; $meta['nofollow'] = true;  break;
                case 'cena_ubyvaniyu': $order_by = 'price DESC'; $meta['nofollow'] = true;  break;
                case 'populyarnost_vozrastaniyu': $order_by = 'views ASC'; $meta['nofollow'] = true;  break;
                case 'populyarnost_spadaniyu': $order_by = 'views DESC'; $meta['nofollow'] = true;  break;
                case 'date_vozrastaniye': $order_by = 'ctime ASC'; $meta['nofollow'] = true;  break;
                case 'date_spadaniye': $order_by = 'ctime DESC'; $meta['nofollow'] = true;  break;
		default:  $order_by = 'ctime DESC';
		}
             
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
                                                AND ws_articles_options.option_id = ".$options->id;
                      break;
                  case 'brand': 
                      $where .=" JOIN ws_articles_options on ws_articles.brand_id = ws_articles_options.brand_id"
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
             /// var_dump($where);
              $param['categories'] = self::getAllParametrs($where, 0, 'categories', '', true);
              
                     if (count($addtional['categories'])) {
             foreach ($param['categories'] as $value) {
                if(in_array($value['id'], $addtional['categories'])){
                               $title[] = mb_strtolower($value['title']);
                        }
                }
              $meta['footer'] = 'tut';
   $where .= ' AND (ws_articles.category_id IN (' . implode(',', $addtional['categories']) . ') OR ws_articles.dop_cat_id IN (' . implode(',', $addtional['categories']) . '))';
        }
      //  d($where, false);
		
		$param['brands'] = self::getAllParametrs($where, $category->id, 'brands');
		
		if (count($addtional['brands'])) {
                  foreach ($param['brands'] as $value) {
                       if(in_array($value['id'], $addtional['brands'])){
                             $title[] = $value['title'];
                        }
                }
                    $meta['footer'] = 'tut';
            $where .= ' AND ws_articles.brand_id IN (' . implode(',', $addtional['brands']) . ')';
           // $where .= ' AND ws_articles.brand_id IN (' . $addtional['brands'] . ')';
        }
        // d($where, false);
		$param['sezons'] = self::getAllParametrs($where, $category->id, 'sezons');
                
		if (count($addtional['sezons'])) {
                    foreach ($param['sezons'] as $value) {
                        if(in_array($value['id'], $addtional['sezons'])){
                             $title[] = $value['title'];
                        }
                }
                    $meta['footer'] = 'tut';
                    
            $where .= ' AND ws_articles.sezon IN ('.implode(',',$addtional['sezons']).')';
            //$where .= ' AND ws_articles.sezon IN ('.$addtional['sezons'].')';
        }
        //d($where, false);
		$param['colors'] = self::getAllParametrs($where, $category->id, 'colors');
        if (count($addtional['colors'])) {
            foreach ($param['colors'] as $value) {
                if(in_array($value['id'], $addtional['colors'])){
                                 $title[] = mb_strtolower($value['title']);
                        }
                }
            $where .= ' AND ws_articles.color_id IN (' . implode(',', $addtional['colors']) . ')';
             //$where .= ' AND ws_articles.color_id IN (' . $addtional['colors'] . ')';
        }
		$param['sizes'] = self::getAllParametrs($where, $category->id, 'sizes');
        if (count($addtional['sizes'])) {
            foreach ($param['sizes'] as $value) {
                if(in_array($value['id'], $addtional['sizes'])){
                                 $title[] = mb_strtolower($value['title']);
                        }
                }
            $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
            //$where .= ' AND ws_articles_sizes.id_size IN (' . $addtional['sizes'] . ')';
        }
		$param['labels'] = self::getAllParametrs($where, $category->id, 'labels');
        if (count($addtional['labels'])) {
            $meta['nofollow'] = 1;
            $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
            //$where .= ' AND ws_articles.label_id IN ('.$addtional['labels'].')';
        }

		$param['skidka'] = self::getAllParametrs($where, $category->id, 'skidka');
		 if (count($addtional['skidka'])) {
                     $meta['nofollow'] = 1;
            $where .= ' AND ws_articles.ucenka IN ('.implode(',', $addtional['skidka']).')';
           // var_dump($where);
             //$where .= ' AND ws_articles.ucenka IN ('.$addtional['skidka'].')';
        }
              

              //  $param['categories'] = self::getAllParametrs($where, '', 'categories', '', true);
		
		
		if ($addtional['price']['min']) {$where .= ' AND ws_articles.price >= ' . $addtional['price']['min'];}
		if ($addtional['price']['max']) {$where .= ' and ws_articles.price <= ' . $addtional['price']['max'];}
		
		
		
                
        $min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' .$where;
//d($count_article_query, false);
        $total_articles = Shoparticles::findByQueryArray($count_article_query)[0]->cnt;
		//d($total_articles, false);
        if (!$total_articles){ $total_articles = 0; }
			
        $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' .$where. ' ORDER BY ' . $order_by . ' LIMIT ' . $page * $onPage . ',' . $onPage;	
//d($articles_query, false);
        $articles_list = Shoparticles::useStatic('Shoparticles')->findByQuery($articles_query);
      //  d($articles_list, false);
        $min_max_price =  Shoparticles::findByQueryArray($min_max_price);
        $param['price_min'] = $min_max_price[0]->min;
        $param['price_max'] = $min_max_price[0]->max;
		
        return [
            'count' => $total_articles,
            'articles' => $articles_list,
            'pages' => ceil($total_articles / $onPage),
            'parametr' =>  $param
        ];
         }else{
             return false;
         }
        
    }
    
	
			
}