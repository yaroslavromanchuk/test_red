<?php

class Home extends wsActiveRecord
{
public  function newActiveArticles(){
	 $mas=array(30,69,70,77,80,113,142,144,147,148,32,39,40,73,78,107,149,36,57,58,62,60,68,67,53,273,274,275,276,277,281,282,283); 
	 $articles = array();
	 
	foreach ($mas as $m) {
	$arr = wsActiveRecord::useStatic('Shoparticles')->findByQuery('SELECT distinct(ws_articles.id), ws_articles.*,DATE_FORMAT(ws_articles.data_new,"%Y-%m-%d") as orderctime
        FROM ws_articles_sizes
        JOIN ws_articles ON ws_articles_sizes.id_article = ws_articles.id
        WHERE ws_articles_sizes.count > 0
        AND ws_articles.active = "y"
        AND ws_articles.stock > 0
		AND ws_articles.category_id = '.$m.'
        AND (DATE_FORMAT(ws_articles.ctime,"%Y-%m-%d") < DATE_ADD(NOW(), INTERVAL -1 DAY) OR ws_articles.get_now = 1)
        ORDER BY RAND()  LIMIT 10 ');
		$c = $arr->count();
		if($c == 10) $articles[] = $arr;
	
	}
	return $articles;

    }

}
?>