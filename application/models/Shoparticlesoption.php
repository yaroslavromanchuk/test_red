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
        
        /**
         * Список активных акций
         * @param type $limit - количество возвращаемых записей, по умолчанию 5
         * @return array
         */
	public static function findActiveOption($limit = 10)
	{
            // $dat = date("Y-m-d", strtotime('-1 days'));
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
		return "/news/id/" . $this->getId() .'/name/' . $this->_generateUrl($this->option_text).'/';
	}
        
        /**
         * Возвращает ссылку на группу товаров в акции
         * @return url "/articles/option/(id акции)/(название акции)"
         */
          public function getPathFind()
                {
                    return "/articles/option/".$this->id."/";
                }	
	
}
