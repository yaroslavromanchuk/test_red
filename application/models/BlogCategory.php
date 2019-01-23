<?php
class BlogCategory extends wsActiveRecord
{
    protected $_table = 'ws_blog_catgories'; 
	protected $_orderby = array('id' => 'DESC'); 
	protected $_multilang = array('name' => 'name', 'title'=>'title', 'description'=>'description');

        /**
         * Ссылка на категорию
         * @return type
         */
	public function getPath()
    	{
    		return "/blog/category/" . $this->getId() .'/'.$this->_generateUrl($this->getName()).'/';
    	}
        
            /**
             * Все категории
             * @return type
             */    
	public function getAllCategory(){
      
        return wsActiveRecord::useStatic('BlogCategory')->findAll();
        }

}