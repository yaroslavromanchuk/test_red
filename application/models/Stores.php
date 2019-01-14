<?php 
class Stores extends wsActiveRecord
{
protected $_table = 'ws_stores'; 
	protected $_orderby = array('sort' => 'ASC'); 
	
	public function getAllSrores(){
		
		return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'addres']);
		
		}
        public function getAkcii(){
                    //return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'new']);
                     return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'new', 'active' => 1], [], [0, 5]);
        }
         public function getInfo(){
                    return wsActiveRecord::useStatic('Stores')->findAll(['type' => 'info', 'active' => 1], [], [0, 5]);
        }
        public function getIds($id){
               return wsActiveRecord::useStatic('Stores')->findById($id);
        }
        public function getOnePostIds($id){
               return wsActiveRecord::useStatic('Stores')->findFirst(['id'=>$id, 'type'=>'temp', 'active'=>1]);
        }
        public function getPath(){
            return '/stores/post/id/'.$this->id.'/';
        }
}
