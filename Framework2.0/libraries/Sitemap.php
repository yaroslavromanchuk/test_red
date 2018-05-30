<?php 
class Sitemap {

    private $_encoding="UTF-8";
    private $_title="";
    private $_language="ru";
    private $_description="";
    private $_link="";
	private $_category="";
    private $_generator="SITEMAP";
    private $_version="1.0";

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
        if ($name=='encoding')        $this->_encoding=stripslashes($value);
        if ($name=='title')        $this->_title=stripslashes($value);
        if ($name=='language')        $this->_language=stripslashes($value);
        if ($name=='description')    $this->_description=stripslashes($value);
		if ($name=='category')    $this->_category=stripslashes($value);
        if ($name=='generator')        $this->_generator=stripslashes($value);
        if ($name=='link')        $this->_link=stripslashes($value);

    }
	  public function get($menu, $category) {
	  $t_f = date("Y-m-d 00:00:00"); 
        $res="";
        // header
        $res.="<?xml version=\"1.0\" encoding=\"".$this->_encoding."\"?>\n";
        $res.="<urlset xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>\n";
        $res.="\t\t<title><![CDATA[".$this->_title."]]></title>\n";
        $res.="\t\t<description><![CDATA[".$this->_description."]]></description>\n";
		$res.="\t\t<category><![CDATA[".$this->_category."]]></category>\n";
        $res.="\t\t<link>".$this->_link."</link>\n";
        $res.="\t\t<language>".$this->_language."</language>\n";
       // $res.="\t\t<generator>".$this->_generator."</generator>\n";
        //items
        foreach($menu as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
                $res.="\t\t<url>\n";
            	$res.="\t\t\t<title><![CDATA[".stripslashes($item->getName())."]]></title>\n";
                $res.="\t\t\t<loc><![CDATA[".stripslashes($item->getPath())."]]></loc>\n";
                $res.="\t\t\t<lastmod>".date("d.m.Y")."</lastmod>\n";
				$res.="\t\t\t<priority>1.0</priority>\n";
            $res.="\t\t</url>\n";
        }
		 foreach($category as $item) {
		//<loc>https://www.red.ua/</loc>
	//<lastmod>2017-05-10T11:29:32+01:00</lastmod>
	//<priority>1.0</priority>
                $res.="\t\t<url>\n";
            	$res.="\t\t\t<title><![CDATA[".stripslashes($item->getName())."]]></title>\n";
                $res.="\t\t\t<loc><![CDATA[".stripslashes($item->getPath())."]]></loc>\n";
                $res.="\t\t\t<lastmod>".date("d.m.Y")."</lastmod>\n";
				$res.="\t\t\t<priority>0.9</priority>\n";
            $res.="\t\t</url>\n";
			
		 foreach(wsActiveRecord::useStatic('Shopcategories')->findAll(array('parent_id' => $item->getId(), 'active' => 1), array('name'=>'ASC')) as $it) {
			   $res.="\t\t<url>\n";
            	$res.="\t\t\t<title><![CDATA[".stripslashes($it->getName())."]]></title>\n";
                $res.="\t\t\t<loc><![CDATA[".stripslashes($it->getPath())."]]></loc>\n";
                $res.="\t\t\t<lastmod>".date("d.m.Y")."</lastmod>\n";
				$res.="\t\t\t<priority>0.8</priority>\n";
            $res.="\t\t</url>\n";
			
			 foreach(wsActiveRecord::useStatic('Shoparticles')->findAll(array('category_id' => $it->getId(), 'active = "y" and stock > 0 and ctime < "'.$t_f.'"'), array('id'=>'ASC'), array(0,10)) as $ar){
			  $res.="\t\t<url>\n";
            	$res.="\t\t\t<title><![CDATA[".stripslashes($ar->getModel().' ( '.$ar->getBrand().' )')."]]></title>\n";
                $res.="\t\t\t<loc><![CDATA[".stripslashes($ar->getPath())."]]></loc>\n";
                $res.="\t\t\t<lastmod>".date("d.m.Y", strtotime($ar->getCtime()))."</lastmod>\n";
				$res.="\t\t\t<priority>0.7</priority>\n";
            $res.="\t\t</url>\n";
			 }
			 }
        }
		
        //footer
        $res.="</urlset>\n";
        return $res;
    }
}

?>