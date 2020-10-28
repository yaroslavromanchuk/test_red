<?php 
class Shopordersmeestexpres extends wsActiveRecord
    {
	  protected $_table = 'ws_order_meestexpres';
        protected $_orderby = array('id' => 'DESC');
	
	/**
         * 
         * @param type $type
         * @param type $order
         * @param type $city
         * @param type $branch
         * @param type $street
         * @param type $massa
         * @return type
         */
	public static function newPost($type, $order, $city, $branch, $street, $massa)
    {
	
        $post = new Shopordersmeestexpres();
		$post->setCtime(date('Y-m-d H:i:s'));
		$post->setTypeId($type);
		$post->setOrderId($order);
		$post->setUuidCity($city);
		$post->setUuidBranch($branch);
		$post->setUuidStreet($street);
		$post->setMassa($massa);
        $post->save();
		
		return $post->getId();

    }
    /**
     * 
     * @param type $order
     * @param type $city
     * @param type $branch
     * @param type $type
     * @return type
     */
	public static function newOrderNp($order, $city, $branch, $type = 0)
    {
        $post = new Shopordersmeestexpres();
		$post->setCtime(date('Y-m-d H:i:s'));
		$post->setOrderId($order);
		$post->setTypeId($type);
		$post->setUuidCity($city);
		$post->setUuidBranch($branch);
        $post->save();
		return $post->getId();
    }
    public function getUuid($id){
        if(!empty($id)){
        return wsActiveRecord::useStatic('Shopordersmeestexpres')->findById($id);
        }
        return '';
    }

	
	
	
	
	
	}
