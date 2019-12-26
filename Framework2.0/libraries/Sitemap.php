<?php 
class Sitemap {

    private $_encoding="UTF-8";
    private $_title="";
    private $_language="ru";
    private $_description="";
    private $_link="";
    private $_category="";
    private $_generator="SITEMAP";
   // private $_version="1.0";

    public function __construct($title) {
        $this->_title=$title;
    }
	public function __get($name) {
        if ($name=='encoding')        return $this->_encoding;
        if ($name=='title')        return $this->_title;
        if ($name=='language')        return $this->_language;
        if ($name=='description')    return $this->_description;
		if ($name=='category')    return $this->_category;
        if ($name=='generator')        return $this->_generator;
        if ($name=='link')        return $this->_link;
    }
    public function __set($name,$value) {
        if ($name=='encoding'){        $this->_encoding=stripslashes($value);}
        if ($name=='title'){        $this->_title=stripslashes($value);}
        if ($name=='language') {       $this->_language=stripslashes($value);}
        if ($name=='description')    $this->_description=stripslashes($value);
	if ($name=='category')    $this->_category=stripslashes($value);
        if ($name=='generator')        $this->_generator=stripslashes($value);
        if ($name=='link')        $this->_link=stripslashes($value);

    }
	  public function get($menu, $category) {
              
	 // $t_f = date("Y-m-d 00:00:00"); 
          $data = date("Y-m-d");
        $res="";
        // header

        $res.='<?xml version="1.0" encoding="UTF-8"?>';

$res.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
       // $res.="<title>".$this->_title."</title>";
       // $res.="<description>".$this->_description."</description>";
	//$res.="<category>".$this->_category."</category>";
       // $res.="<link>".$this->_link."</link>";
       // $res.="<language>".$this->_language."</language>";
       // $res.="<generator>".$this->_generator."</generator>";
        
       // $res.="<url>";
          //  	$res.="<title><![CDATA[".$this->_title."]]></title>";
            //    $res.="<loc><![CDATA[".$this->_link."]]></loc>";
            //    $res.="<lastmod>".$data."</lastmod>";
             //   $res.="<changefreq>Always</changefreq>";
		//$res.="<priority>1.0</priority>";
           // $res.="</url>";
        //items
        $i = 0;
        foreach($menu as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
            if($item->name == 'RED.UA'){
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$this->_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Always</changefreq>";
		$res.="<priority>1.0</priority>";
            $res.="</url>";
                
            }else{
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$this->_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Daily</changefreq>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            }
            $i++;
        }
       
		 foreach($category as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$this->_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Weekly</changefreq>";
		$res.="<priority>0.8</priority>";
            $res.="</url>";
            $i++;
            /*
            $sezon = wsActiveRecord::useStatic('Shopcategories')->findByQuery("SELECT  `ws_articles_sezon`.`translate` 
FROM  `ws_categories` 
INNER JOIN  `ws_articles` ON  `ws_categories`.`id` =  `ws_articles`.`category_id` 
INNER JOIN  `ws_articles_sezon` ON  `ws_articles`.`sezon` =  `ws_articles_sezon`.`id` 
WHERE  `ws_articles`.`active` =  'y'
AND  `ws_articles`.`stock` NOT LIKE  '0'
AND  `ws_articles`.`status` = 3 and `ws_categories`.`id`=".$item->id."
GROUP BY  `ws_articles_sezon`.`translate` ");
            if($sezon){
            foreach ($sezon as $value) {
                $res.="<url>";
                $res.="<loc>".$this->_link.stripslashes($item->getPath().'sezons-'.$value->translate.'/')."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
            }
            
            $sql ="SELECT  `red_brands`.`name` 
FROM  `red_brands` 
INNER JOIN  `ws_articles` ON  `red_brands`.`id` =  `ws_articles`.`brand_id` 
INNER JOIN  `ws_categories` ON  `ws_articles`.`category_id` =  `ws_categories`.`id` 
WHERE  `ws_articles`.`active` =  'y'
AND  `ws_articles`.`stock` NOT LIKE  '0'
AND  `ws_articles`.`status` =3 and `ws_categories`.`id`= ".$item->id."
GROUP BY  `red_brands`.`name` ";
                 }
                 $brand = wsActiveRecord::useStatic('Brand')->findByQuery($sql);
                 if($brand){
                     foreach ($brand as $value) {
                     $res.="<url>";
                $res.="<loc>".$this->_link.stripslashes($item->getPath().'brands-'.strtolower(str_replace('&', '%26', $value->name)).'/')."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
		$res.="<priority>0.7</priority>";
            $res.="</url>";
            $i++;
                     }
                 }
                 */
                 
                 }
                 
		// foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => $item->getId(), 'active' => 1, 'sitemap' => 1), array('name'=>'ASC')) as $it) {
		///	   $res.="<url>";
            	///$res.="<title><![CDATA[".stripslashes($it->getName())."]]></title>";
              //  $res.="<loc><![CDATA[".stripslashes($it->getPath())."]]></loc>";
               // $res.="<lastmod>".$data."</lastmod>";
              //  $res.="<changefreq>Weekly</changefreq>";
			//	$res.="<priority>0.9</priority>";
           // $res.="</url>";
		$limit = 500 - $i;	
			 foreach(wsActiveRecord::useStatic('Shoparticles')->findAll(array( 'active' => "y",  'stock not like "0"', 'status' => 3), array('id'=>'DESC'), [0, $limit]) as $ar){
                             $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($ar->getModel().' ( '.$ar->getBrand().' )')."]]></title>";
                $res.="<loc>".$this->_link.stripslashes($ar->getPath())."</loc>";
                $res.="<lastmod>".date('Y-m-d', strtotime($ar->ctime))."</lastmod>";
               // $res.="<changefreq>Weekly</changefreq>";
				$res.="<priority>0.6</priority>";
            $res.="</url>";
            //$i++;
           // if($i >= 50000) {break;}
			 }
			// }
       // }
        
		
        //footer
        $res.="</urlset>";
       // echo $res;
        return $res;
    }
}

