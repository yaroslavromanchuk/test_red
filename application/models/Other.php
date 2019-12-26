<?php
    class Other extends wsActiveRecord
    {
        protected $_table = 'ws_other_code';
        protected $_orderby = array( 'id' => 'DESC');
		
		protected function _defineRelations() {

		}
		static function findByCode($cod)
    {
	$today_c = date("Y-m-d H:i:s");
    	return wsActiveRecord::useStatic('Other')->findFirst(array('cod = "'.(string)$cod.'" and "'.$today_c.'" <= utime'));
    }
    	static function findActiveCode($cod)
    {
            $res = [];
	$today_c = date("Y-m-d H:i:s");
    	$code =  wsActiveRecord::useStatic('Other')->findAll(['cod = "'.(string)$cod.'" '])->at(0);
        if($code->id){
            if($code->ctime <= $today_c and $today_c <= $code->utime) {
                $res['flag'] = true;
                $res['message'] = 'Промокод "'.$code->cod.'" - активировано';
                $res['cod'] = $code->cod;
               
                
            }else{
                 $res['flag'] = false;
            $res['message'] = 'Промокод "'.$cod.'" не активный.';
            $res['cod'] = $cod;
            }
            
        }else{
            $res['flag'] = false;
            $res['message'] = 'Промокод "'.$cod.'" отсутсвует в системе.';
            $res['cod'] = $cod;
        }
        
         return $res;
    }

    }
