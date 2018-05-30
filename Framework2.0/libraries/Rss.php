<?php

class Rss {

    private $_encoding="UTF-8";
    private $_title="";
    private $_language="ru";
    private $_description="";
    private $_link="";
	private $_category="";
    private $_generator="RSS";
    private $_version="2.0";

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

    /**
    Make an xml document of the rss stream
    @param: items: n row of associative array with theses field:
            'title': title of the item
            'description': short description of the item
            'pubData': publication timestamp of the item
            'link': url to show the item
    @result: xml document of rss stream
    **/
    public function get($items) {
        $res="";
        // header
        $res.="<?xml version=\"1.0\" encoding=\"".$this->_encoding."\"?>\n";
        $res.="<rss version=\"2.0\">\n";
        $res.="\t<channel>\n";
        $res.="\t\t<title><![CDATA[".$this->_title."]]></title>\n";
        $res.="\t\t<description><![CDATA[".$this->_description."]]></description>\n";
		$res.="\t\t<category><![CDATA[".$this->_category."]]></category>\n";
        $res.="\t\t<link>".$this->_link."</link>\n";
        $res.="\t\t<language>".$this->_language."</language>\n";
       // $res.="\t\t<generator>".$this->_generator."</generator>\n";
        //items
        foreach($items as $item) {
                //$date = date("r", stripslashes($item["pubDate"]));
                $res.="\t\t<item>\n";
            	$res.="\t\t\t<title><![CDATA[".stripslashes($item["post_name"])."]]></title>\n";
                $res.="\t\t\t<description><![CDATA[".stripslashes($item["preview_post"])."]]></description>\n";
				$res.="\t\t\t<link>".stripslashes("https://www.red.ua" . $item->getPath())."</link>\n";
                $res.="\t\t\t<pubDate>".date("d.m.Y", strtotime($item["ctime"]))."</pubDate>\n";
               
            $res.="\t\t</item>\n";
        }
        //footer
        $res.="\t</channel>\n";
        $res.="</rss>\n";
        return $res;
    }
}

?>