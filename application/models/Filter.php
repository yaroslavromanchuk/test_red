<?php

class Filter extends wsActiveRecord{

    public static function getArticlesFilter($search = '', $addtional = false, $category_id = false, $order_by = false,  $page, $onPage){

        //order by
		switch($order_by){
		case 1: $order_by = 'price ASC'; break;
		case 2: $order_by = 'price DESC'; break;
		case 3: $order_by = 'views ASC'; break;
		case 4: $order_by = 'views DESC'; break;
		case 5: $order_by = 'ctime ASC'; break;
		case 6: $order_by = 'ctime DESC'; break;
		default:  $order_by = 'ctime DESC';
		}
		
		$t_t = date("Y-m-d H:m:s", strtotime("-5 day"));
		
        $where = "
                  FROM ws_articles_sizes
					JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id 
					WHERE ws_articles_sizes.count > 0 and ws_articles.active = 'y' 
						AND ws_articles.stock not like '0'  
						and ws_articles.status = 3
                 ";


        $category = new Shopcategories($category_id);
        $category_kids = $category->getKidsIds();
       // $category_kids[] = $category_id;
		switch($category->id){
		case 106: $where .= " AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0"; break;
		case 1: $category_id = 106; $where .=" AND ws_articles.ctime > '$t_t' AND ws_articles.old_price = 0 "; break;
		case 267:  break;
		default:  if(count($category_kids) > 0 and @$category_kids[0]) $where .= ' AND (ws_articles.category_id IN (' . (implode(',', $category_kids)) . ') OR ws_articles.dop_cat_id IN (' . (implode(',', $category_kids)) . '))'; break;
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
                $where .= ' AND ( ws_articles.model like "%' . mysql_real_escape_string($search) . '%" or ws_articles.model_uk like "%' . mysql_real_escape_string($search) . '%"
            or ws_articles.brand like "%' . mysql_real_escape_string($search) . '%"
            or ws_articles.long_text like "%' . mysql_real_escape_string($search) . '%" or ws_articles.long_text_uk like "%' . mysql_real_escape_string($search) . '%" or ws_articles_sizes.code like "%' . mysql_real_escape_string($search) . '%" )';
            }
        }

		
		$param = array();
		
		$min_max_price = 'SELECT MIN(price) as min, MAX(price) as max ' . $where;
		
		if (@$addtional['price']['min']) $where .= ' AND ws_articles.price >= ' . $addtional['price']['min'];
		if (@$addtional['price']['max']) $where .= ' and ws_articles.price <= ' . $addtional['price']['max'];
		
		$param['categories'] = Filter::getAllParametrs($where, $category->id, 'categories');
		
		
		
        if (count(@$addtional['categories'])) {
   $where .= ' AND (ws_articles.category_id IN (' . $addtional['categories'] . ') OR ws_articles.dop_cat_id IN (' . $addtional['categories'] . '))';
        }
		
		$param['brands'] = Filter::getAllParametrs($where, $category->id, 'brands');
		
		if (count(@$addtional['brands'])) {
            $where .= ' AND ws_articles.brand_id IN ("' . implode('","', $addtional['brands']) . '")';
        }
		$param['sezons'] = Filter::getAllParametrs($where, $category->id, 'sezons');
		if (count(@$addtional['sezons'])) {
            $where .= ' AND ws_articles.sezon IN ('.implode(',', $addtional['sezons']).')';
        }
		$param['colors'] = Filter::getAllParametrs($where, $category->id, 'colors');
        if (count(@$addtional['colors'])) {
            $where .= ' AND ws_articles.color_id IN (' . implode(',', $addtional['colors']) . ')';
        }
		$param['sizes'] = Filter::getAllParametrs($where, $category->id, 'sizes');
        if (count(@$addtional['sizes'])) {
            $where .= ' AND ws_articles_sizes.id_size IN (' . implode(',', $addtional['sizes']) . ')';
        }
		$param['labels'] = Filter::getAllParametrs($where, $category->id, 'labels');
        if (count(@$addtional['labels'])) {
            $where .= ' AND ws_articles.label_id IN ('.implode(',', $addtional['labels']).')';
        }

		$param['skidka'] = Filter::getAllParametrs($where, $category->id, 'skidka');
		 if (count(@$addtional['skidka'])) {
            $where .= ' AND ws_articles.ucenka IN ("'.implode('","', $addtional['skidka']).'")';
        }
		//$param['categories'] = Filter::getAllParametrs($where, $category->id, 'categories');
		//$param['brands'] = Filter::getAllParametrs($where, $category->id, 'brands');
		//$param['sezons'] = Filter::getAllParametrs($where, $category->id, 'sezons');
		//$param['colors'] = Filter::getAllParametrs($where, $category->id, 'colors');
		//$param['sizes'] = Filter::getAllParametrs($where, $category->id, 'sizes');
		//$param['labels'] = Filter::getAllParametrs($where, $category->id, 'labels');
		//$param['skidka'] = Filter::getAllParametrs($where, $category->id, 'skidka');
//d($where, false);

        $count_article_query = 'SELECT count(distinct(ws_articles.id)) as cnt' .$where;
//d($count_article_query, false);
       


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
        $min_max_price =  wsActiveRecord::useStatic('Shoparticles')->findByQueryArray($min_max_price);
		
		//$this->view->filters =;
		
        return array(
			'search'=> $search,
            'count' => $total_articles,
            'articles' => $articles,
            'pages' => ceil($total_articles / $onPage),
			'parametr' =>  $param,
			'min_max' => $min_max_price
        );
    }
	


    public static function getAllParametrs($where, $category_id, $type=''){
		$array = array();
		switch($type){
		case 'categories': //Categories
        $categories = 'SELECT ws_articles.category_id, count(distinct(ws_articles.id)) AS cnt  ' . $where.' group by ws_articles.category_id  order by ws_articles.category_id';
        $categories = wsActiveRecord::useStatic('Shoparticles')->findByQuery($categories);
        //convert to array
        foreach ($categories as $cat) {
            if (in_array($category_id, array(106, 85, 299, 300, 301, 302, 303, 11))){
				$array[] = array(
                    'id' => $cat->category_id,
                    'name' => $cat->category->getName(),
                    'parent' => $cat->category->getRoutezGolovna(), 
					'count' => $cat->cnt
                );
            }else{
                $array[] = array(
                    'id' => $cat->category_id,
                    'name' => $cat->category->getName(),
                    'parent' => '',
					'count' => $cat->cnt
                );
            }

        } break;
		
		case 'brands': $brands = 'SELECT brand_id, brand, count(distinct(ws_articles.id)) AS cnt '. $where.' GROUP BY ws_articles.brand_id ORDER BY `ws_articles`.brand ';
        $brands = wsActiveRecord::useStatic('Shoparticles')->findByQuery($brands);
        $i = 0;
        foreach ($brands as $brand) {
            $array[] = array(
                'id' => $brand->getBrandId(),
                'name' => $brand->getBrand(),
				'count' =>  $brand->getCnt()
            );
        } break;
		case 'sezons': $sezons = 'SELECT `ws_articles`.`sezon`, count(distinct(ws_articles.id)) AS ctn ' . $where.' AND  `ws_articles`.`sezon` IS NOT NULL  group by `ws_articles`.`sezon`';
        $sezons = wsActiveRecord::useStatic('Shoparticles')->findByQuery($sezons);
        foreach ($sezons as $sez) {
		if($sez->getSezon() > 0){
            $array[] = array(
			'id' => $sez->sezon,
			'name' => $sez->name_sezon->name,
			'count' =>  $sez->ctn
			);
			}
        } break;
		case 'sizes': $sizes = 'SELECT ws_articles_sizes.id_size, COUNT(distinct(ws_articles_sizes.id_article)) AS ctn ' . $where .' group by ws_articles_sizes.id_size';
        $sizes = wsActiveRecord::useStatic('Shoparticlessize')->findByQuery($sizes);
        foreach ($sizes as $size) {
			 $array[] = array(
                'id' => $size->id_size,
                'name' => $size->size->getSize(),
				'count' =>$size->getCtn()
				
            );
        } break;
		case 'colors': $colors = 'SELECT  ws_articles.color_id, count(distinct(ws_articles.id)) AS ctn  ' . $where.' group by ws_articles.color_id';
        $colors = wsActiveRecord::useStatic('Shoparticles')->findByQuery($colors);
        foreach ($colors as $col) {
		$array[] = array(
                'id' => $col->color_id,
                'name' => $col->color_name->name,
				'count' => $col->getCtn(),
            );
        } break;
		case 'labels': $labels = 'SELECT ws_articles.label_id, count(distinct(ws_articles.id)) AS ctn ' . $where.' and ws_articles.label_id !=0 GROUP BY ws_articles.label_id';
        $labels = wsActiveRecord::useStatic('Shoparticles')->findByQuery($labels);
		$array = array();
        foreach ($labels as $lab) {
		 $array[] = array(
                'id' => $lab->label_id,
                'name' => $lab->label->name,
				'count' => $lab->ctn
            );
        } break;
		case 'skidka': $ucenka = 'SELECT `ws_articles`.`ucenka`,count(distinct(ws_articles.id)) AS cnt ' . $where;
        $ucenka = wsActiveRecord::useStatic('Shoparticles')->findByQuery($ucenka.' and `ws_articles`.`ucenka` != 0  group by `ws_articles`.`ucenka`');
        foreach ($ucenka as $uc) {
		if($uc->getCnt() > 0){
            $array[] = array(
			'id' => $uc->getUcenka(),
			'name' => '- '.$uc->getUcenka().' %',
			'count' =>  $uc->getCnt()
			);
			}
        } break;
		default:

		break;
		}
		
		return $array;
    }
	
			
}