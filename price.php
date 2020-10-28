<?php

if(/*isset($_GET['red']) and $_GET['red'] == '5c63fup9d755q55jsue4svcnu5'*/true){
require_once('cron_init.php');
header("Content-type: text/xml; charset: UTF-8");
  $dom = new domDocument("1.0", "utf-8"); 
  
  $root1 = $dom->createElement("yml_catalog");
	$root1->setAttribute("date", date('Y-m-d H:m'));
	$dom->appendChild($root1);
	$root = $root1->appendChild($dom->createElement("shop"));
	 
	// $root->appendChild($shop);
		
		
 // $date = $dom->createElement("date", date('Y-m-d H:m'));
		//$root->appendChild($date);
  $name = $dom->createElement("name", 'Интернет магазин модной одежды red.ua');
		$root->appendChild($name);
 // $company = $dom->createElement("firmId", '');
		//$root->appendChild($company);
		
	//$url = $dom->createElement("url", "http://www.red.ua/");
	//$root->appendChild($url);
		
 $currencies = $dom->createElement("currencies");
		$root->appendChild($currencies);
		
 $currency = $dom->createElement("currency");
	$currency->setAttribute("id", "UAH");
    $currency->setAttribute("rate", 1);
		$currencies->appendChild($currency);
		
 $catalog = $dom->createElement("categories");
		$root->appendChild($catalog);
		
 $categorys =  wsActiveRecord::useStatic('Shopcategories')->findAll(array(' active = 1 and parent_id = 0 and id in(33,14,15,54,59,146,248)'));
 $mas = array();
  foreach($categorys as $cat){
  $category = $dom->createElement("category", $cat->name); // Создаём узел "category" , $cat->name
  
	$category->setAttribute("id", $cat->id);// Устанавливаем атрибут "id" у узла "user"
	
	$mas[] = $cat->id;
		$catalog->appendChild($category);
		//$id = $dom->createElement("id", $cat->id);
		//$category->appendChild($id);
		//$name = $dom->createElement("name", $cat->name);
		//$category->appendChild($name);
		
		
    $dop_category =  wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>$cat->getId()));
	  foreach($dop_category as $d_cat){
 $category = $dom->createElement("category", $d_cat->name); // Создаём узел "category" , $d_cat->name
	$category->setAttribute("id", $d_cat->id);// Устанавливаем атрибут "id" у узла "user"
	$mas[] = $d_cat->id;
   $category->setAttribute("parentId", $cat->id); // Устанавливаем атрибут "id" у узла "user"
	//$category->setAttribute("portal_id", $cat->id); // Устанавливаем атрибут "id" у узла "user"
	//$category->setAttribute("portal_url", $cat->getPath()); // Устанавливаем атрибут "id" у узла "user"
		$catalog->appendChild($category);
		//$id = $dom->createElement("id", $d_cat->id);
		//$category->appendChild($id);
		//$name = $dom->createElement("name", $d_cat->name);
		//$category->appendChild($name);
		//$parentId = $dom->createElement("parentId", $cat->id);
		//$category->appendChild($parentId);
		
		$dop_category_count =  wsActiveRecord::useStatic('Shopcategories')->count(array('active'=>1, 'parent_id'=>$d_cat->getId()));
		if($dop_category_count > 0){
		$dop_category2 =  wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>$d_cat->getId()));
		foreach($dop_category2 as $d_cat2){
 $category = $dom->createElement("category", $d_cat2->name); // Создаём узел "category" , $d_cat->name
	$category->setAttribute("id", $d_cat2->id); // Устанавливаем атрибут "id" у узла "user"
	$mas[] = $d_cat2->id;
    $category->setAttribute("parentId", $d_cat->id); // Устанавливаем атрибут "id" у узла "user"
	//$category->setAttribute("portal_id", $d_cat->id); // Устанавливаем атрибут "id" у узла "user"
	//$category->setAttribute("portal_url", $d_cat->getPath()); // Устанавливаем атрибут "id" у узла "user"
		$catalog->appendChild($category);
		//$id = $dom->createElement("id", $d_cat2->id);
		//$category->appendChild($id);
		//$name = $dom->createElement("name", $d_cat2->name);
		//$category->appendChild($name);
		//$parentId = $dom->createElement("parentId", $d_cat->id);
		//$category->appendChild($parentId);
		}
		
		
		}
		  }
  }
  /*
	$delivery1 = $dom->createElement("delivery");
			$delivery1->setAttribute("id", '1');
			$delivery1->setAttribute("type", "warehouse");
			$delivery1->setAttribute("cost", '0');
			$delivery1->setAttribute("time", "1");
			$delivery1->setAttribute("carrier", "slf");
			$delivery1->setAttribute("region", "01*, 02*, 03*, 04*, 05*, 06*");
		$root->appendChild($delivery1);
  $delivery2 = $dom->createElement("delivery");
			$delivery2->setAttribute("id", '2');
			$delivery2->setAttribute("type", "address");
			$delivery2->setAttribute("cost", '65');
			$delivery2->setAttribute("time", "1");
			$delivery2->setAttribute("carrier", "slf");
			$delivery2->setAttribute("freeFrom", "750");
			$delivery2->setAttribute("region", "01*, 02*, 03*, 04*, 05*, 06*");
		$root->appendChild($delivery2);
  $delivery3 = $dom->createElement("delivery");
			$delivery3->setAttribute("id", '3');
			$delivery3->setAttribute("type", "warehouse");
			$delivery3->setAttribute("cost", '45');
			$delivery3->setAttribute("time", "2");
			$delivery3->setAttribute("carrier", "UP");
		$root->appendChild($delivery3);
$delivery4 = $dom->createElement("delivery");
			$delivery4->setAttribute("id", '4');
			$delivery4->setAttribute("type", "warehouse");
			$delivery4->setAttribute("cost", null);
			$delivery4->setAttribute("time", "2");
			$delivery4->setAttribute("carrier", "NP");
		$root->appendChild($delivery4);	
		*/
		
  $offers = $dom->createElement("offers");
		$root->appendChild($offers);
		
		$qq = '
		SELECT DISTINCT (ws_articles.id), ws_articles.brand, ws_articles.category_id, ws_articles.model, ws_articles.image, ws_articles.old_price, ws_articles.price, ws_articles.long_text, `ws_articles_sizes`.`code`
FROM ws_articles_sizes
JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
WHERE ws_articles_sizes.count >0
AND ws_articles.active =  "y"
AND ws_articles.stock > 1
AND ws_articles.status = 3
AND ws_articles.category_id
IN (' . (implode(',', $mas)) . ') 
ORDER BY  `ws_articles`.`ctime` DESC limit 1000';
		
	$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($qq);
		
		foreach($articles as $a){
                    $img = $a->getImagePath('detail');
		//echo $a->id;
		 $offer = $dom->createElement("offer");
		$offer->setAttribute("id", $a->id);
		// $offer->setAttribute("available", "true");
		 // $offer->setAttribute("selling_type", "s");
		 $offers->appendChild($offer);
		 
		// $offer->appendChild($dom->createElement("id", $a->id));
		 $offer->appendChild($dom->createElement("categoryId", $a->category_id));//
		// $offer->appendChild($dom->createElement("code", $a->code));//
		 $offer->appendChild($dom->createElement("vendor",  htmlspecialchars(strip_tags($a->brand))));//
		 $offer->appendChild($dom->createElement("name", $a->model));//
		 //$offer->appendChild($dom->createElement("description",  htmlspecialchars(strip_tags($a->long_text))));//
		 $offer->appendChild($dom->createElement("url", "https://www.red.ua".htmlspecialchars(strip_tags($a->getPath()))));//
		 $offer->appendChild($dom->createElement("picture",  "https://www.red.ua".$img));//
		 $offer->appendChild($dom->createElement("price",  $a->price));//
		 //$offer->appendChild($dom->createElement("stock",  iconv('windows-1251', 'UTF-8', "� �������")));//
		 $offer->appendChild($dom->createElement("store",  "true"));//
		 //$offer->appendChild($dom->createElement("guarantee",  iconv('windows-1251', 'UTF-8',"14 ����")));//
		 
		 $colors = '';
		 $sizes = '';
		 $i=0;
		 foreach(wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT * FROM ws_articles_sizes WHERE id_article='.$a->id.' AND count > 0') as $col){
		 if($i == 0){
		 $colors = $col->getColor()->getName();
		  $sizes .= $col->getSize()->getSize();
		 }else{
		  $sizes .= ', '.$col->getSize()->getSize();
		 }
		 $i++;
		 }
		// wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("code LIKE '".$a->code."' "));
		// $param = $dom->createElement("param", $colors);//
		 //$param->setAttribute("name", iconv('windows-1251', 'UTF-8', "����"));
		// $offer->appendChild($param);
		 
		// $colors = wsActiveRecord::useStatic('Shoparticlessize')->findFirst(array("code LIKE '".$a->code."' "))->getColor()->getName()
		 //$param = $dom->createElement("param", $sizes);//
		 //$param->setAttribute("name", iconv('windows-1251', 'UTF-8', "������"));
		// $offer->appendChild($param);
		// $vendor = $dom->createElement("vendor",  htmlspecialchars(strip_tags($a->brand)));//
			//$offer->appendChild($vendor);
		// $reference = $dom->createElement("reference", $a->id);//
			//$offer->appendChild($reference);

			if($a->old_price > 0){
		$oldprice = $dom->createElement("oldprice",  $a->old_price);
		$offer->appendChild($oldprice);
			}
		
		
		
		/*$colours = $dom->createElement("colours");
			$offer->appendChild($colours);
			
		foreach(wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT * FROM ws_articles_sizes WHERE id_article='.$a->id.' AND count > 0 GROUP BY id_color') as $col){
            if($col and $col->color){
		$color = $dom->createElement("colour", $col->color->getName());
		$colours->appendChild($color);
             } 
			 } 
			 
			 $sizes = $dom->createElement("sizes");
			$offer->appendChild($sizes);
			
		foreach(wsActiveRecord::useStatic('Shoparticlessize')->findByQuery('SELECT * FROM ws_articles_sizes WHERE id_article='.$a->id.'  AND count > 0 GROUP BY id_size') as $siz){
		if(@$siz and @$siz->color){
			if(@$siz->size->getSize() and $siz->count > 0){
			$size = $dom->createElement("size", $siz->size->getSize());
		$sizes->appendChild($size);
		
		 }
		 }
		 } 
		*/
		 
		
		}
  echo $dom->saveXML();
  }
  //echo printf("<pre>%s</pre>",print_r($mas, true));
  