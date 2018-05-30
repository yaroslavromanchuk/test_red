<?php 
class Amazonorders extends wsActiveRecord
{
 protected $_table = 'ws_amazon_orders';
 protected $_orderby = array('id' => 'DESC');
 
 
 protected function _defineRelations()
    {
	 $this->_relations = array(
			'articles' => array(
				'type' => 'hasMany',
				'class' => 'Amazonorderarticles',
				'field_foreign' => 'order_id',
				'orderby' => array('id' => 'ASC'),
				'onDelete' => 'delete'
			)
			);
	}
	public function ReCalculate(){
	$article = wsActiveRecord::useStatic('Amazonorderarticles')->findAll(array('order_id'=>$this->getId()));
	$count = 0;
	$sum = 0;
	foreach($article as $a){
	$count+=$a->getCount();
	$sum+=$a->getCount()*$a->getPrice();
	}
	$this->setCount($count);
	$this->setPrice($sum);
	$this->save();
	return true;
	}

}

?>