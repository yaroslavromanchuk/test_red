<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XmlController
 *
 * @author PHP
 */
class XmlController extends controllerAbstract{

    public function sitemapAction(){
        $data = date("Y-m-d");
        $_link = "https://www.red.ua";
       header('Content-Type: application/xml');
$res = "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
$i = 0;
        foreach( wsActiveRecord::useStatic('Menu')->findAll(['type_id is not null', 'parent_id' => null, 'no_sitemap'=>NULL, 'nofollow'=>NULL], ['sequence' => 'ASC']) as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
            if($item->name == 'RED.UA'){
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Always</changefreq>";
		$res.="<priority>1.0</priority>";
            $res.="</url>";
                
            }else{
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Daily</changefreq>";
		$res.="<priority>0.9</priority>";
            $res.="</url>";
            }
            $i++;
        }
        foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(['sitemap' => 1, 'active' => 1]) as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
                $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($item->getName())."]]></title>";
                $res.="<loc>".$_link.stripslashes($item->getPath())."</loc>";
                $res.="<lastmod>".$data."</lastmod>";
               // $res.="<changefreq>Weekly</changefreq>";
		$res.="<priority>0.8</priority>";
            $res.="</url>";
            $i++;
        }
        $limit = 50000 - $i;	
			 foreach(wsActiveRecord::useStatic('Shoparticles')->findAll(array( 'active' => "y",  'stock not like "0"', 'status' => 3), array('id'=>'DESC'), [0, $limit]) as $ar){
                             $res.="<url>";
            	//$res.="<title><![CDATA[".stripslashes($ar->getModel().' ( '.$ar->getBrand().' )')."]]></title>";
                $res.="<loc>".$_link.stripslashes($ar->getPath())."</loc>";
                $res.="<lastmod>".date('Y-m-d', strtotime($ar->ctime))."</lastmod>";
               // $res.="<changefreq>Weekly</changefreq>";
				$res.="<priority>0.6</priority>";
            $res.="</url>";
            //$i++;
           // if($i >= 50000) {break;}
			 }
$res.="</urlset>";
print ($res);
exit();
    }
    
}
