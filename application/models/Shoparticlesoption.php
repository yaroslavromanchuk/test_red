<?php
class Shoparticlesoption extends wsActiveRecord
{
	protected $_table = 'ws_articles_option';
	protected $_orderby = array('id'=>'DESC');
	
	protected function _defineRelations()
	{	
		$this->_relations = array(
                    'options' => array(
                                        'type'=>'hasMany',
					'class'=>'Shoparticlesoptions',
					'field_foreign'=>'option_id'
                                        ),
					);
	}

    
      /**
       * Возвращает скидки на которую нужно умножить цену что бы получить стоимость( 0.8)
       * @param type $id - акции
       * @return int
       */  
    public static function getProcSkidka($id)
	{
	return 1-(wsActiveRecord::findByQueryArray("Select value from ws_articles_option where id =$id")[0]->value/100);
	}
    
        /*
        
    public static function getActiveOptions($a)
	{	
	$dat = date('Y-m-d');
	$sql ="SELECT  `ws_articles_option`. * 
FROM  `ws_articles_option` 
JOIN  `ws_articles_options` ON  `ws_articles_option`.`id` =  `ws_articles_options`.`option_id` 
WHERE  `ws_articles_option`.`status` =1
AND  `start` <=  '$dat'
AND  `end` >=  '$dat'
AND (
 `ws_articles_options`.`article_id` = $a->id
OR  `ws_articles_options`.`category_id` = $a->category_id
OR  `ws_articles_options`.`brand_id` = $a->brand_id
)";
	
		$option = wsActiveRecord::findByQueryArray($sql);
		if($option){
                        return $option;
		}

		return false;
	}
         */
        
        
        /**
         * 
         * @param type $a - указатель на товар
         * 
         * @return false - товар не участвует в акции
         * или
         * @return параметры акции в которой учавствует товар
         */
        /*
	public static function getAllOptions($a)
	{

	$dat = date('Y-m-d');
	$sql ="SELECT  `ws_articles_option`. * 
FROM  `ws_articles_option` 
JOIN  `ws_articles_options` ON  `ws_articles_option`.`id` =  `ws_articles_options`.`option_id` 
WHERE  `ws_articles_option`.`status` = 1
AND  `start` <=  '$dat'
AND  `end` >=  '$dat'
AND (
 `ws_articles_options`.`article_id` = $a->id
OR  `ws_articles_options`.`category_id` = $a->category_id
OR  `ws_articles_options`.`brand_id` = $a->brand_id
)";
	
		$option = wsActiveRecord::findByQueryArray($sql);
		if($option){
		//d($option, false);
                    return $option;
		}

		return false;
	}
        
        */
        
        /**
         * Список активных акций
         * @param type $limit - количество возвращаемых записей, по умолчанию 5
         * @return array
         */
	public static function findActiveOption($limit = 5)
	{
            $dat = date('Y-m-d');
		return Shoparticlesoption::find('Shoparticlesoption', ["status"=>1, "`start` <= '$dat'", "`end` >= '$dat'"], ['id'=>'DESC'], $limit);
	}
        /**
         * Список всех акций
         * @param type $limit - количество возвращаемых записей, по умолчанию 10 последних
         * @return array
         */
	public static function findAllActiveOption($limit = 10)
	{

        
		$news_all = Shoparticlesoption::find('Shoparticlesoption', [], ['id'=>'DESC'], $limit);

		return $news_all;
	}
        
         /**
         * Ссылка на акцию
         * 
         * @return string
         */
	public function getPath()
	{
		return "/news/name/" . $this->_generateUrl($this->option_text) . "/id/" . $this->getId() .'/';
	}
        
        /**
         * Возвращает ссылку на группу товаров в акции
         * @return url "/articles/option/(id акции)/(название акции)"
         */
          public function getPathFind()
                {
 
                    return "/articles/option/".$this->id."/".$this->_generateUrl($this->option_text);
                }	
	
}
