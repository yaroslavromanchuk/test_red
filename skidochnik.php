<?php
chdir(__DIR__);
require_once('cron_init.php');
header("Content-Type: text/xml");
$xml='';
$xml .= '<?xml version="1.0" encoding="UTF-8" ?>';
$xml .= '<yml_catalog date="'.date('Y-m-d H:m').'">';
$xml .= '<shop>';
$xml.='<version>1.0</version>';
$xml .= '<name>Интернет магазин модной одежды red.ua</name>';
$xml .= '<company>RED.UA</company>';
$xml .= '<url>https://www.red.ua/</url>';
$xml .= '<description>Интернет-магазин одежды RED: стильные и яркие вещи по доступной цене. Обувь, сумки, платья, летняя одежда для детей и подростков. Скидки! </description>';
$categorys =  wsActiveRecord::useStatic('Shopcategories')->findAll(array(' active = 1 and parent_id = 0 and id in(33,14,15,54,59,146,248)'));
 $mas = [];
if($categorys){
        $xml .= '<categories>';
foreach($categorys as $cat){
    $mas[] = $cat->id;
       $xml .= '<category id="'.$cat->id.'">'.$cat->name.'</category>';
        $dop_category =  wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>$cat->id));
        if($dop_category){
	  foreach($dop_category as $d_cat){
              $mas[] = $d_cat->id;
            $xml .= '<category id="'.$d_cat->id.'"  parentId="'.$cat->id.'" >'.$d_cat->name.'</category>'; 
                $dop_dop_category =  wsActiveRecord::useStatic('Shopcategories')->findAll(array('active'=>1, 'parent_id'=>$d_cat->id));
                if($dop_dop_category){
                     foreach($dop_dop_category as $dd_cat){
                         $mas[] = $dd_cat->id;
                    $xml .= '<category id="'.$dd_cat->id.'"  parentId="'.$d_cat->id.'" >'.$dd_cat->name.'</category>'; 
                     }
                }
          }
        }
  }
        $xml .= '</categories>';
}
        $xml .= '<offers>';
            	$qq = '
		SELECT DISTINCT (ws_articles.id) as id, ws_articles.brand, ws_articles.category_id, ws_articles.model,
                ws_articles.image, ws_articles.old_price, ws_articles.price,
                ws_articles.long_text,
                `ws_articles_sizes`.`code`
FROM ws_articles_sizes
JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
WHERE ws_articles_sizes.count >0
AND ws_articles.active =  "y"
AND ws_articles.stock > 1
AND ws_articles.status = 3
AND ws_articles.old_price > 0
AND ws_articles.category_id
IN (' . (implode(',', $mas)) . ') 
ORDER BY  `ws_articles`.`ctime` DESC limit 100';
		
	$articles = wsActiveRecord::useStatic('Shoparticles')->findByQuery($qq);
        if($articles){
            
        foreach($articles as $a){
            $brand = htmlspecialchars(str_replace("'", "&apos;", str_replace('&', '&amp;', strip_tags($a->brand))));
            $img = $a->getImagePath('detail');
            $xml .= '<offer id="'.$a->id.'">';
            $xml .= '<currencyId>UAH</currencyId>';
            $xml .= '<categoryId>'.$a->category_id.'</categoryId>';
            $xml .= '<vendor>'.$brand.'</vendor>';
            $xml .= '<name>'.$a->model.' '.$brand.'</name>';
            $xml .= '<url>https://www.red.ua'.htmlspecialchars(strip_tags($a->getPath())).'</url>';
           // $xml .= '<picture>https://www.red.ua/files/360_360/'.substr($a->getImage(), 0, -4).'_w360_h360_cf_ft_fc255_255_255.jpg</picture>';
            $xml .= '<picture>https://www.red.ua'.$img.'</picture>';
            $xml .= '<price>'.$a->price.'</price>';
            $xml .= '<oldprice>'.$a->old_price.'</oldprice>';
            $xml .= '<description><![CDATA['.$a->long_text.']]></description>';
            
            $xml .= '</offer>';
        }
}
        $xml .= '</offers>';
    $xml .= '</shop>';
$xml .= '</yml_catalog>';
echo $xml;



