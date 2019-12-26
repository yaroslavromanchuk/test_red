<?php
	class JustinRequestDeliveryInfo  extends wsActiveRecord {
		protected $_table = 'ws_justin_request_delivery_info';
		protected $_orderby = array( 'id' => 'ASC');
		//protected $_multilang = array('question' => 'question', 'answer' => 'answer');

		protected function _defineRelations() {

		}
                public function getDelivery($ttn){
                     return wsActiveRecord::useStatic('JustinRequestDeliveryInfo')->findFirst(['number_ttn'=>$ttn])->number_pms;
                }




	}