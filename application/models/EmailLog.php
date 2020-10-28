<?php

class EmailLog extends wsActiveRecord
{
    protected $_table = 'ws_log_email';
  //  protected $_orderby = array('ctime' => 'DESC');
    
    /**
     * 
     * @param type $subject - тема письма
     * @param type $message - тело письма
     * @param type $catalog - место хранения файла относительно "корень/email/"...по умолчанию "shop"
     * @param type $id - ід пользователя получателя письма, по умолчанию  = 0
     * @param type $order_id - номер заказа, по умолчанию  = 0

     */
  public static function add($subject = '', $message = '', $catalog = 'shop', $id = 0,  $order_id = 0 ){
      $l = new EmailLog();
      $l->setCtime(date('Y-m-d H:i:s'));
      $l->setSubject($subject);
      $l->setCustomerId($id);
      $l->setHash(self::set_file_email($catalog, $id, $message));
      $l->setCatalog($catalog);
      $l->setOrderId($order_id);
      $l->save();
  }
  static function set_file_email($catalog, $id, $message){
      $fn = md5(date('Y-m-d H:i:s').$id);
           $file = $fn.'.html';
           $fp =  fopen(INPATH.'email/'.$catalog.'/'.$file,'w');//если файла info.txt не существует, создаем его
           fwrite($fp, $message);//записываем в файл
           fclose($fp);//закрываем файл.
      return $file;
  }
  
  public static function getListEmail($id, $type = 'customer'){
      switch ($type){
          case 'order': break;
          case 'customer': return self::list_customer($id);
          case 'cart': break;
          case 'birthday': break;
          default : 
      }
  }
  
  static function list_customer($id){
      $t = '<div class="panel "><div class="panel-body"><table class="table"><tr><th>Время</th><th>Email</th></tr>';
     $email =  EmailLog::findByQueryArray("SELECT ctime, catalog, hash, subject FROM `ws_log_email`
WHERE `customer_id` = {$id}
ORDER BY `ws_log_email`.`id` DESC
LIMIT 0 , 30 ");
if(count($email)){
    foreach ($email as $e){
        $t .= '<tr><td>'.$e->ctime.'</td><td><a href="#" onclick="return LoadGetForm(\'/email/'.$e->catalog.'/'.$e->hash.'\');">Тема: '.$e->subject.'</a></td></tr>';
    }
}
      $t.='</table></div></div>';
      $t.= '<div class="panel panel-danger"><div class="panel-body"><div id="result_load_form"></div></div></div>';
   /*   $t.=" <script>
          function LoadGetForm(file){
        $.ajax({
			url: '/admin/nowamail/',
			type: 'POST',
			dataType: 'json',
			data: {file: file, metod: 'gel_email_load_form'},
                      
			success: function (res) {
                            $('#result_load_form').html(res);
                            }
			
		});
       return false;
    
}
</script>";*/
      return $t;
  }
}
