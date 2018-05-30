<?php
class Reviews extends wsActiveRecord
{
    protected $_table = 'ws_comment_system';
	protected $_orderby = array('id' => 'DESC'); 

	    protected function _defineRelations()
    {

        $this->_relations = array(
            'coments' => array(
                'class' => 'Reviews',
                'field_foreign' => 'id'
            ),

        );


    }
	
	static public function findAllActive()
    {
        $comments = 'SELECT  distinct(`id`), `parent_id`, `url_id`, `id_material`, `name`, `url`, `mail`, `text`, `date_add`, `public`FROM ws_comment_system where public = 1 and parent_id= 0 order by id DESC';
        $comments = wsActiveRecord::useStatic('Reviews')->findByQuery($comments);
        return $comments;

    }

}
?>