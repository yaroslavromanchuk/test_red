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
    	return wsActiveRecord::useStatic('Other')->findFirst(array('cod = "'.(string)$cod.'" and "'.$today_c.'" <= expirationtime'));
    }
    /**
     * 
     * @param type $cod
     * @return type
     */
    
    static function findActiveCode($cod)
    {
         $cod = preg_replace('/[^ a-zа-яA-ZА-Я\d]/ui', '',$cod);
            $res = [];
	$today_c = date("Y-m-d H:i:s");
    	$code =  wsActiveRecord::useStatic('Other')->findAll(["cod = '".(string)$cod."' "])->at(0);
        if($code->id){
            if($code->ctime <= $today_c and $today_c <= $code->expirationtime) {
                $res['flag'] = true;
                $res['message'] = 'Промокод "'.$code->cod.'" - активировано';
                $res['cod'] = $code->cod;
                $res['skidka'] = $code->skidka;
                $res['min_sum'] = $code->min_sum;
               
                
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
    
    /**
     * 
     */
   public static function flagCodeMinSumActive($cod, $summa = 0){
      // l($summa);
        $code =  wsActiveRecord::useStatic('Other')->findAll(["cod = '$cod' and  $summa >= min_sum "])->at(0);
      //  l(SQLLogger::getInstance()->reportBySql());
        if($code->id) {return true;}
        
        return false;
    }
    public function getCountOrderPromo(){
     //   kupon
         $sql = "SELECT COUNT(  `ws_orders`.`id` ) AS  `ctn`, sum(amount+deposit) as suma from `ws_orders` WHERE `ws_orders`.status not in(2,5,7,0,17) and `ws_orders`.`kupon` = '{$this->cod}'";
       return wsActiveRecord::useStatic('Shoporders')->findByQuery($sql)->at(0);
    }

    }
