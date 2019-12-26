<?php
	class JustinDepartments  extends wsActiveRecord {
		protected $_table = 'ws_justin_departments';
		protected $_orderby = array( 'number' => 'ASC');
		//protected $_multilang = array('question' => 'question', 'answer' => 'answer');

		protected function _defineRelations() {

		}
                public function getDepartment($branch){
                     return wsActiveRecord::useStatic('JustinDepartments')->findFirst(['branch'=>$branch])->depart_descr;
                }




	}