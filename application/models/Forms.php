<?php
    class Forms extends wsActiveRecord
    {
        protected $_table = 'ws_forms';
        protected $_orderby = array( 'id' => 'DESC');


      protected function _defineRelations()
        {
            	$this->_relations = array(
                    /*'items' => array(
					'type'=>'hasMany',
					'class'=>'FormsItem',
					'field_foreign' => 'forms_id',
                                        'orderby' => array('id' => 'ASC'),
                                        'onDelete'  => 'delete')*/
                    'items11' => [
                        'type' =>'hasOne',
                        'class' => 'FormsItem',
                        'field' => 'forms_id'
                    ]
                );

        }
        /**
         * Список всех форм
         * @return type
         */
        static function getListForms(){
            return wsActiveRecord::useStatic('Forms')->findAll();
        }
     private  function getItems(){
            return wsActiveRecord::useStatic('FormsItem')->findAll(['forms_id'=>$this->id],[],[0,1])->at(0)->item;
        }

        /**
         * Отобразить форму по имени
         * @param type $name - имя формы
         * @return string
         */
        static function getForms($name= ''){
            $forma =  wsActiveRecord::useStatic('Forms')->findFirst(['name'=>$name]);
            if($forma){
               return '<form name="'.$forma->name.'" action="'.$forma->action.'" method="'.$forma->method.'" >'.$forma->getItems().'</form>'; 
            }
            return 'Форма "'.$name.'" не найдена. Просерьте правильность названия формы.';  
        }
        
    }
