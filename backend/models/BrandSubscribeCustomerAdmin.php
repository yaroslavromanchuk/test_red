<?php
class BrandSubscribeCustomerAdmin extends BrandSubscribeCustomer{
    
    public static function getCustomer($brand){
        $s = wsActiveRecord::useStatic('BrandSubscribeCustomer')->findAll(['brand_id'=>$brand, 'active'=>1]);
       
        if($s->count()){ return $s; }
        return false;
    }
}
