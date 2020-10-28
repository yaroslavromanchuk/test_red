<?php
class Shoparticlestop extends wsActiveRecord
{
	protected $_table = 'ws_articles_top';
	protected $_orderby = array('sequence'=>'DESC');
	
	protected function _defineRelations()
	{	
		$this->_relations = [
                    'article' => [
                        'type' => 'hasOne',
                        'class' => self::$_shop_articles_class,
                        'field' => 'article_id'
                       ]
		];
	}
	
	public function findLastSequenceRecord() {
		return $this->findFirst(array(), array('sequence'=>'DESC'));
	}
	
	public function findMaxSequence() {
		if ($tmp = $this->findLastSequenceRecord()){
                    return $tmp->getSequence();
                                    }else{
                                        return 0;
                                        }
	}
        /**
         * 
         */
        public static function activeTopArticle(){
            $date = date("Y-m-d H:i:s");
          return  wsActiveRecord::useStatic('Shoparticlestop')->findAll([" ctime <= '$date' ", " '$date' <= utime "], ['id'=>'DESC'], ['limit'=>' 0, 1 ']);
        }
        public function fromTop(){
            return $this->getId();
           $date = date("Y-m-d H:i:s");
          return  wsActiveRecord::useStatic('Shoparticlestop')->findAll([" ctime <= '$date' ", " '$date' <= utime ", 'article_id'=>$this->getId()], ['id'=>'DESC'], ['limit'=>' 0, 1 ']);
            
        }
}
