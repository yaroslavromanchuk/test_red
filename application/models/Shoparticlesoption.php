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
                    'log' => array(
                                        'type'=>'hasMany',
					'class'=>'OptionLog',
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
                return wsActiveRecord::useStatic('Shoparticlesoption')->findAll(["status"=>1, "`start` <= '{$dat}'", " '{$dat}' <= `end`", "komu != 'email'"], ['id'=>'DESC'], $limit);// Shoparticlesoption::find(, , ['id'=>'DESC'], $limit);
	}
        /**
         * Список всех акций кроме активных
         * @param type $limit - количество возвращаемых записей, по умолчанию 10 последних
         * @return array
         */
	public static function findAllActiveOption($limit = 20)
	{
            $dat = date('Y-m-d');
       return wsActiveRecord::useStatic('Shoparticlesoption')->findAll([], ['id'=>'DESC'], $limit);
		//$news_all = Shoparticlesoption::find('Shoparticlesoption', [""], ['id'=>'DESC'], $limit);

		//return $news_all;
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
                
        public function _beforeSave()
                    {
            $info = [];
            if($this->id){
             $param = ['option_id'=>$this->id, 'admin_id'=>Registry::get('Website')->getCustomer()->getId()];
            $op = new Shoparticlesoption($this->id);
            
            
            if($this->option_text != $op->option_text){ $info['Название'] = $op->option_text.' => '.$this->option_text; }
            if($this->value != $op->value){ $info['Скидка'] =  $op->value.' => '.$this->value; }
            if($this->min_summa != $op->min_summa){ $info['Мин.Сумма'] =  $op->min_summa.' => '.$this->min_summa; }
            if($this->type != $op->type){ $info['Тип'] =  $op->type.' => '.$this->type; }
            if($this->timer != $op->timer){ 
                if($this->timer == 1){
                $info['Таймер'] = 'Включен!';
            }else{
                 $info['Таймер'] = 'Выключен!';
            }
            }
            if($this->action != $op->action){ $info['Сегмент'] = $op->action.' => '.$this->action; }
             if($this->komu != $op->komu){ $info['Охват'] =  $op->komu.' => '.$this->komu; }
             if($this->email != $op->email){ $info['Трекер'] =  $op->email.' => '.$this->email; }
             if($this->start != $op->start){ $info['Начало'] =  $op->start.' => '.$this->start; }
             if($this->end != $op->end){ $info['Окончание'] =  $op->end.' => '.$this->end; }
             
            if($this->status != $op->status){ 
                if($this->status == 1){
                $info['Активность'] = 'Активировано!';
            }else{
                 $info['Активность'] = 'Деактивировано!';
            }
            }
            $param['info'] = serialize($info);
            
                    return OptionLog::add($param);
            }
            return true;
            
                    }

	
}
