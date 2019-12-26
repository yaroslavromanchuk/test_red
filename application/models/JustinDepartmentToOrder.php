<?php
	class JustinDepartmentToOrder  extends wsActiveRecord {
		protected $_table = 'ws_justin_department_to_order';
		//protected $_orderby = array( 'number' => 'ASC');
		//protected $_multilang = array('question' => 'question', 'answer' => 'answer');

		protected function _defineRelations() {

		}
                
                public static function newOrderJastin($order, $city, $branch)
    {
        $post = new JustinDepartmentToOrder();
		$post->setOrderId($order);
		$post->setNumberDepartment($branch);
		$post->setUuidCity($city);
                $post->save();
		return $post->getId();
    }
    public function getUuid($id){
        return wsActiveRecord::useStatic('JustinDepartmentToOrder')->findFirst(['order_id'=>$id])->number_department;
    }




	}