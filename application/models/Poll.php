<?php
class Poll extends wsActiveRecord{
	protected $_table = 'poll';
	protected $_orderby = array('active'=>'DESC','date' => 'DESC');
        protected $_multilang = array('name' => 'name');
	protected function _defineRelations()
	{
		$this->_relations = array(
			'questions' => array('type'=>'hasMany',
							   'class'=>'PollQuestions',
							   'field_foreign'=>'poll_id',
							   'onDelete' => null
	                   )
		);
	}

	public static function drawLastPoll($customerId = null){
		$view = Registry::get('View');
		$poll = wsActiveRecord::useStatic('Poll')->findFirst(['active'=>1]);
		if ($poll){
			//search user id base with current Poll  // NULL id for open . but need add COOKIE for UNIQUE
			//if (wsActiveRecord::useStatic('PollResults')->findAll(array('poll_id'=>$poll->getId(),'customer_id'=>$customerId))->count()== 0){
				$view->pollTitle = $poll->getName();
                                
				$view->questions = $poll->getQuestions(array(), array('id'=>'ASC'));
				return $view->render('/poll/index.tpl.php');
		}
		return '';
	}
     /*   public static function getQuestions($customer_id, $id){
            $res = wsActiveRecord::useStatic('PollResults')->findAll(['poll_id'=>$id, 'customer_id'=> $customer_id]);
            if($res->count()){
                //return $res;
                return  false;
            }
            return wsActiveRecord::useStatic('PollQuestions')->findAll(['poll_id'=>$id]);
           
        }*/

        public static function textPoll(){
		return Registry::get('View')->render('poll/poll.php');
	}
        public static function indexPoll(){
		$view = Registry::get('View');
		$view->pollTitle = 'Викторина!!!';
               // $view->contentPoll = self::textPoll();
                $view->contentPoll = self::formaPoll();
		return $view->render('poll/index.php');
	}
        public static function formaPoll($result = []){
            if(count($result)){
               
                foreach ($result as $pp){
                $find = wsActiveRecord::useStatic('PollResults')->findFirst(array('customer_id' => $pp['customer_id'], 'poll_id' => $pp['poll_id']));
                if (!$find) {
                    $userPoll = new PollResults();
                    $userPoll->import($pp);
                    $userPoll->save();
                }
                }
            }
		$view = Registry::get('View');
                $polls = wsActiveRecord::useStatic('Poll')->findAll(['active'=>1],['id'=>'ASC']);
		if ($polls){
                    $view->polls = $polls;
			//search user id base with current Poll  // NULL id for open . but need add COOKIE for UNIQUE
			//if (wsActiveRecord::useStatic('PollResults')->findAll(array('poll_id'=>$poll->getId(),'customer_id'=>$customerId))->count()== 0){
				//$view->pollTitle = $poll->getName();
                                ///$view->id = $poll->id;
                               // $q = self::getQuestions($result['customer_id'],$poll->id);
                               // if($q){
                                 //   $view->questions = $q;
                              //  }else{
                                    
                                //    $view->message = 'Вы уже ответили на все вопросы, спасибо за внимание. Ожидайте объявление победителя.';
                               // }
				
				
                }else{
                     $view->message = 'Вы уже ответили на все вопросы, спасибо за внимание. Ожидайте объявление победителя.';
                }
                return $view->render('poll/forma.php');
		//$view->pollTitle = 'Викторина!!!! Участвуй в викторине, получи прыз';
                //$view->contentPoll = 'Мы проводим викторину, отвечай на вопросы и получай бонус на депозит!!!!';
		//return 'Спасибо за внимание';
	}
        public static function calcPoll($mass = []){
            $res = [
                    18 => 48,
                    19 => 53,
                    20 => 58,
                    21 => 61,
                    22 => 64,
                    23 => 68,
                    24 => 72,
                    25 => 75,
                    26 => 82,
                    27 => 84
            ];
            $all = 0;
            $ok = 0;
            $res_mass = [];
            foreach ($mass as $key => $v) {
                 $res_mass[$key]['poll'] = $key;
                 $res_mass[$key]['question'] = $v['question_id'];
                if($v['question_id'] == $res[$key]){
                    $res_mass[$key]['result'] = 1; 
                    $ok++;
                }else{
                    $res_mass[$key]['result'] = 0;
                }
                $all++;
            }
            //echo 'Вы правильно ответили на '.$ok.' c '.$all.'вопросов.<br> Вы набрали '.$ok.' балов.';
            
		return ['ok'=>$ok, 'res'=>self::resultPoll(['all' => $all, 'ok' => $ok, 'mass'=> $res_mass])];
	}
        
        public static function resultPoll($mass = []){
            $view = Registry::get('View');
            $view->result = $mass['mass'];
            $view->ok = $mass['ok'];
            $view->all = $mass['all'];
		return $view->render('poll/result.php');
	}
}