<?php

class Finder extends wsActiveRecord
{

    public static function getArticlesByWord($search = '', $addtional = false, $page, $onPage, $category_id = false, $order_by = false)
    {

        //order by
        if ($order_by == 1) {
            $order_by = 'price ASC';
        } elseif ($order_by == 2) {
            $order_by = 'price DESC';
        } elseif ($order_by == 3) {
            $order_by = 'views ASC';
        } elseif ($order_by == 4) {
            $order_by = 'views DESC';

        } elseif ($order_by == 5) {
            $order_by = 'utime ASC';

        } elseif ($order_by == 6) {
            $order_by = 'utime DESC';

        } else {
            $order_by = 'ctime DESC';//RAND()
        }
		$t_f = date("Y-m-d 00:00:00"); 
		$t_t = date("Y-m-d H:m:s", strtotime("-5 day"));
        $where = "
                  FROM ws_articles_sizes
                 JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
                  WHERE ws_articles_sizes.count > 0 
				  AND ws_articles.active = 'y' 
				  AND ws_articles.stock not like '0'  
				  and ws_articles.status = 3
                 ";


        $category = new Shopcategories($category_id);
        $category_kids = $category->getKidsIds();
       // $category_kids[] = $category_id;

        if ($category_id == 106) {
		//$order_by = 'data_new DESC';
		//$t = date("Y-m-d", strtotime("-6 day")); 
            $where .= " AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0";
        } elseif($category_id == 1 and !isset($_COOKIE['s'])){
		$category_id = 106;
		$where .=" AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0 ";
		}elseif(isset($_COOKIE['s']) and $_COOKIE['s'] !='' and $category_id == 1){
		$c_d = date('Y-m-d', strtotime(Shoparticles::decode($_COOKIE['s'], 'coderedua')));
		$where .= ' AND  `ws_articles`.`data_new` >= "' .$c_d. '" ';
		//var_dump($c_d);
		}elseif($category_id == 999){
		$where .= ' AND `ws_articles`.`data_ucenki` > "' . date('Y-m-d 00:00:00').'" and `ws_articles`.`ucenka` > 20';
		}elseif($category_id and $category_id != 267){
            $where .= ' AND (ws_articles.category_id IN (' . (implode(',', $category_kids)) . ') OR ws_articles.dop_cat_id IN (' . (implode(',', $category_kids)) . '))';
        }

        if ($search) {
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
                $where .= ' AND ( model like "%' . mysql_real_escape_string($search) . '%" or model_uk like "%' . mysql_real_escape_string($search) . '%"
            or brand like "%' . mysql_real_escape_string($search) . '%"
            or long_text like "%' . mysql_real_escape_string($search) . '%" or long_text_uk like "%' . mysql_real_escape_string($search) . '%" or ws_articles_sizes.code like "' . mysql_real_escape_string($search) . '" )';
            }
        }
        if (count(@$addtional['prices_min_max'])) {
            $where .= ' AND (ws_articles.price >= ' . (float)$addtional['prices_min_max']['min'] . ' AND ws_articles.price <= ' . (float)$addtional['prices_min_max']['max'] . ')';
        }


        if (count(@$addtional['categories'])) {
           $where .= ' AND (ws_articles.category_id IN (' . implode(',', $addtional['categories']) . ') OR ws_articles.dop_cat_id IN (' . implode(',', $addtional['categories']) . '))';
        }

        if (count(@$addtional['colors'])) {
            $where .= ' AND ws_articles_sizes.id_color IN (' . implode(',', $addtional['colors']) . ')';
        }

        if (count(@$addtional['sizes'])) {
            $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
        }

        if (count(@$addtional['labels'])) {
            $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
        }
		if (count(@$addtional['sezons'])) {
            $where .= ' AND ws_articles.sezon IN ('.implode(',', $addtional['sezons']).')';
        }

        if (count(@$addtional['brands'])) {
            $where .= ' AND ws_articles.brand_id IN ("' . implode('","', $addtional['brands']) . '")';
        }
      //  if (count(@$addtional['models'])) {
         //   $where .= ' AND ws_articles.model IN ("'.implode('","', $addtional['model']).'")';
       // }
		 if (count(@$addtional['skidka'])) {
            $where .= ' AND ws_articles.ucenka IN ("'.implode('","', $addtional['skidka']).'")';
        }

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' . $where;

        $min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;


        $total_articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($count_article_query)->at(0);
        if ($total_articles->getCnt() > 0){
            $total_articles = $total_articles->getCnt();
			}else{
			$rest = iconv_substr($search, 0, -1, 'UTF-8');
			$total_articles = 0;
			$where = str_replace($search, mysql_real_escape_string($rest), $where);
			}
			
        $articles_query = 'SELECT distinct(ws_articles.id), ws_articles.* ' . $where . ' ORDER BY ' . $order_by . ' LIMIT ' . $page * $onPage . ',' . $onPage;
		


        $articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($articles_query);

        $min_max_price = wsActiveRecord::useStatic('Shoparticles')->findByQuery($min_max_price)->at(0);
        return array(
			'search'=> $search,
            'count' => $total_articles,
            'articles' => $articles,
            'pages' => ceil($total_articles / $onPage),
            'min_max' => $min_max_price
        );
    }
	


    public static function getAllEnabledParametrs($search = '', $addtional = false, $category_id = false, $enable_all_child_categories = false)
    {
	$t_f = date("Y-m-d 00:00:00"); 
		$t_t = date("Y-m-d H:m:s", strtotime("-5 day"));
        $where = "
                  FROM ws_articles_sizes
                  JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
                  WHERE ws_articles_sizes.count > 0 
				  AND ws_articles.active = 'y'  
				  AND ws_articles.stock not like '0'  
				  and ws_articles.status = 3
                 ";


        $category = new Shopcategories($category_id);
        $category_kids = $category->getKidsIds();
       // $category_kids[] = $category_id;

        if ($category_id == 106) {
		//$t = date("Y-m-d", strtotime("-6 day")); 
            $where .= " AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0";
        } elseif($category_id == 1 and !isset($_COOKIE['s'])){
		$category_id = 106;
		$where .=" AND  ws_articles.ctime > '$t_t'  AND ws_articles.old_price = 0";
		}elseif(isset($_COOKIE['s']) and $_COOKIE['s'] !='' and $category_id == 1){
		$c_d = date('Y-m-d', strtotime(Shoparticles::decode($_COOKIE['s'], 'coderedua')));
		$where .= ' AND `ws_articles`.`data_new` < "' . date('Y-m-d') . '" AND `ws_articles`.`data_new` >= "' .$c_d. '" ';
		//var_dump($c_d);
		}elseif ($category_id and $category_id != 267) {
            $where .= ' AND (ws_articles.category_id IN (' . (implode(',', $category_kids)) . ') OR ws_articles.dop_cat_id IN (' . (implode(',', $category_kids)) . '))';
        }
	

        if ($search) {
            $mas = explode(' ', $search);
            if (count($mas) > 1) {

                $find_slov = array();
                foreach ($mas as $v) {
                    $find_slov[] = '
                                (model like "%' . mysql_real_escape_string($v) . '%"
                                            or brand like "%' . mysql_real_escape_string($v) . '%"
                                 )';
                }
                $where .= ' AND ( (' . implode('AND ', $find_slov) . ') OR ' . '( model like "%' . mysql_real_escape_string($search) . '%"
                                        or brand like "%' . mysql_real_escape_string($search) . '%"
                                        or long_text like "' . mysql_real_escape_string($search) . '%"))';

            } else {
                $where .= ' AND ( model like "%' . mysql_real_escape_string($search) . '%"
            or brand like "%' . mysql_real_escape_string($search) . '%"
            or long_text like "' . mysql_real_escape_string($search) . '%")';
            }
        }

        if (count(@$addtional['categories'])) {
			$where .= ' AND ( ws_articles.category_id IN (' . implode(',', $addtional['categories']) . ') OR ws_articles.dop_cat_id IN (' . implode(',', $addtional['categories']) . '))';
        }

        if (count(@$addtional['colors'])) {
            $where .= ' AND ws_articles_sizes.id_color IN (' . implode(',', $addtional['colors']) . ')';
        }

        if (count(@$addtional['sizes'])) {
            $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
        }

        if (count(@$addtional['labels'])) {
            $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
        }
		if (count(@$addtional['sezons'])) {
            $where .= ' AND ws_articles.sezon IN ('.implode(',', $addtional['sezons']).')';
        }

        if (count(@$addtional['brands'])) {
            $where .= ' AND ws_articles.brand_id IN (' . implode(',', $addtional['brands']) . ')';
        }
       // if (count(@$addtional['models'])) {
            //$where .= ' AND ws_articles.model IN ("'.implode('","', $addtional['model']).'")';
       // }
	   if (count(@$addtional['skidka'])) {
            $where .= ' AND ws_articles.ucenka IN ("'.implode('","', $addtional['skidka']).'")';
        }

        //Categories
        $categories = 'SELECT ws_articles.category_id, COUNT( DISTINCT (`ws_articles`.`id`) ) AS cnt ' . $where.' group by ws_articles.category_id order by ws_articles.category_id';
        $categories = wsActiveRecord::useStatic('Shoparticles')->findByQuery($categories);
        //convert to array
        $array = array();
        foreach ($categories as $cat) {
		
            if (in_array($category_id, array(106, 85, 299, 300, 301, 302, 303, 11))){
			
              //  $array[$cat->category_id]['id'] = $cat->category_id;
				//$array[$cat->category_id]['name'] =$cat->category->getName();
				//$array[$cat->category_id]['parent'] =$cat->category->getRoutezGolovna();
				//$array[$cat->category_id]['count'] = $cat->cnt;
				
				$array[] = array(
                    'id' => $cat->category_id,
                    'name' => $cat->category->getName(),
                    'parent' => $cat->category->getRoutezGolovna(), 
					'count' => $cat->cnt
                );
            }else{
			
			//$array[$cat->category_id]['id'] = $cat->category_id;
			//	$array[$cat->category_id]['name'] =$cat->category->getName();
			//	$array[$cat->category_id]['parent'] ='';
			//	$array[$cat->category_id]['count'] = $cat->cnt;
				
                $array[] = array(
                    'id' => $cat->category_id,
                    'name' => $cat->category->getName(),
                    'parent' => '',
					'count' => $cat->cnt
                );
            }

        }
		//$array = array();
		//sort($array);
		//d($array, true);
		
		
        $categories = $array;

        //Colors
        $arr = array();
		$array = array();
        $colors = 'SELECT  ws_articles_sizes.id_color, COUNT( DISTINCT (ws_articles_sizes.id_article) ) AS ctn ' . $where.' group by ws_articles_sizes.id_color';
		//d($colors, true);
        $colors = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($colors);
		
        foreach ($colors as $col) {
		$array[] = array(
                'id' => $col->id_color,
                'name' => $col->color->name,
				'count' => $col->getCtn(),
            );
        }
        $colors = $array;
		
        //Sizes
        $array = array();
        $sizes = 'SELECT ws_articles_sizes.id_size, COUNT( DISTINCT (ws_articles_sizes.id_article) ) AS ctn ' . $where.' group by ws_articles_sizes.id_size';
		
        $sizes = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sizes);
        foreach ($sizes as $size) {
			 $array[] = array(
                'id' => $size->id_size,
                'name' => $size->size->getSize(),
				'count' =>$size->getCtn()
				
            );
        }
        $sizes = $array;
		
        //Label
        $labels = 'SELECT ws_articles.label_id, COUNT( DISTINCT (ws_articles_sizes.id_article) ) AS ctn ' . $where.' and ws_articles.label_id !=0 group by label_id';
        $labels = wsActiveRecord::useStatic('Shoparticles')->findByQuery($labels);
		$array = array();
        foreach ($labels as $lab) {
		 $array[] = array(
                'id' => $lab->label_id,
                'name' => $lab->label->name,
				'count' => $lab->ctn
            );
        }

        $labels = $array;
        //Brand

        $array = array();
        $brands = 'SELECT brand_id, brand, COUNT( DISTINCT (`ws_articles`.`id`) ) AS cnt '. $where.' group by brand_id ORDER BY brand ';
        $brands = wsActiveRecord::useStatic('Shoparticles')->findByQuery($brands);
        $i = 0;
        foreach ($brands as $brand) {
            $array[] = array(
                'id' => $brand->getBrandId(),
                'name' => $brand->getBrand(),
				'count' =>  $brand->getCnt()
            );
        }
        $brands = $array;
/*
        //Models
        $array = array();
        $models = 'SELECT distinct(model) ' . $where;
        $models = wsActiveRecord::useStatic('Shoparticles')->findByQuery($models . ' ORDER BY model');
        $i = 0;
        foreach ($models as $model) {
            $array[] = array(
                'id' => $i++,
                'name' => $model->getModel()
            );
        }
        $models = $array;
		*/
		
		//Sezon
		$array = array();
		$sezons = 'SELECT `ws_articles`.`sezon`, COUNT( DISTINCT (`ws_articles`.`id`) ) AS ctn ' . $where.' AND  `ws_articles`.`sezon` IS NOT NULL GROUP BY  `ws_articles`.`sezon`';
        $sezons = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sezons);
        foreach ($sezons as $sez) {
		if($sez->getSezon() > 0){
            $array[] = array(
			'id' => $sez->sezon,
			'name' => $sez->name_sezon->name,
			'count' =>  $sez->ctn
			);
			}
        }
		$sezons = $array;
		//Ucenka
		$array = array();
		$ucenka = 'SELECT `ws_articles`.`ucenka`, COUNT( DISTINCT (`ws_articles`.`id`) ) AS cnt ' . $where;
        $ucenka = wsActiveRecord::useStatic('Shoparticles')->findByQuery($ucenka.' and `ws_articles`.`ucenka` != 0 GROUP BY  `ws_articles`.`ucenka`');
        foreach ($ucenka as $uc) {
		if($uc->getCnt() > 0){
            $array[] = array(
			'id' => $uc->getUcenka(),
			'name' => '- '.$uc->getUcenka().' %',
			'count' =>  $uc->getCnt()
			);
			}
        }
		$ucenka = $array;
        $result = array(
            'categories' => $categories,
            'colors' => $colors,
            'labels' => $labels,
            'brands' => $brands,
           // 'models' => $models,
           'sizes' => $sizes,
			'sezons' => $sezons,
			'skidka' => $ucenka,
           // 'sizes_categories' => $sizes_categories
        );
		
        //die($where);
        return $result;
    }
	
			
}