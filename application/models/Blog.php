<?php
class Blog extends wsActiveRecord
{
    protected $_table = 'ws_blog'; 
	protected $_orderby = array('ctime' => 'DESC'); 
	protected $_multilang = array('post_name' => 'post_name', 'autor'=>'autor', 'preview_post'=>'preview_post', 'content_post'=>'content_post');

	protected function _defineRelations()
	{	
		$this->_relations = array(); 
	}
 public function _beforeDelete()
    {

        @unlink($_SERVER['DOCUMENT_ROOT'].$this->getImage());
        return true;
    }
	public function getPath()
    	{
    		return "/blog/id/" . $this->getId() .'/'.$this->_generateUrl($this->getPostName().'/');
    	    	}
                
	public function LikeActive($id_customer, $id_post){
      $sql = "SELECT * FROM ws_blog_like WHERE id_customer = ".$id_customer." AND id_post = ".$id_post;
		$result=mysql_query($sql);
		if(mysql_num_rows($result)==0){return false;}
        return true;
    }

}