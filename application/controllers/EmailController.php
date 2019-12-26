<?php

class EmailController extends AdminController
{
     private $_files_folder = 'backend';
    private $_controller = 'admin';
    /**
     * Название темы
     * @var type 
     */
    private $_theme = 'default';
    /**
     * Файл шаблона
     * @var type 
     */
   // private $_template = 'admin.tpl.php';
    
	public function init()
        {
          //  parent::init();
           mb_internal_encoding("UTF-8");
        
        $this->view->path = '/' . $this->_controller . '/';
        $this->user = $this->website->getCustomer();
        $this->trans = new Translator();
        $this->view->files  = '/' . $this->_files_folder . '/theme/'.$this->_theme.'/';
        $this->view->setRenderPath(INPATH . $this->_files_folder.'/theme/'.$this->_theme);
	}
        
        public function gomailAction(){
             if (isset($this->post->getarticles)) {
                if (isset($this->post->id)) {
        $data = [];
    $articles = wsActiveRecord::useStatic('Shoparticles')->findAll(['category_id' => $this->post->id, 'active' => 'y', 'stock > 0', 'status'=>3],['views'=>'DESC']);
                    if ($articles->count()){
                        foreach ($articles as $article){
                            $data[] = [
                                'id' => $article->getId(),
                                'title' => $article->getTitle() . " (" . Shoparticles::showPrice($article->getRealPrice()) . " грн)",
                                'img' => $article->getImagePath('listing')
                            ];
                }
                    }
                    $res = [
                        'result' => 'done',
                        'type' => 'articles',
                        'data' => $data
                    ];
                }else{
                    $res = ['result' => 'false'];
                }
                die(json_encode($res));
            }elseif($this->post->method == 'preview') {
            $this->view->post = $this->post;
        if(isset($this->post->s_start) and $this->post->s_start == 1){
            $subject = $this->post->subject_start.', TEST, '.$this->post->subject; 
        }else{
            $subject = $this->post->subject;
        }
        $this->view->name = 'Test';
        $this->view->email = 'test@red.ua';
         
         $this->view->track_open = 'https://www.red.ua/email/image/?photo=test.jpg';
        $this->view->track = '?'
                                . '&utm_source=test_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign=TEST'
                                . '&utm_email_track=test';
        
         
        $this->view->unsubscribe = ''
                                . '&utm_source=test_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign=TEST';
        
        die(json_encode(['title' => $subject, 'message'=>$this->view->render('mailing/general-email.tpl.php')])); 
            }elseif ($this->post->method == 'save'){
                $id = false;
                if($this->post->id_post){
                    $id = $this->post->id_post;
                }
                                    $parr = [
                                       'ctime' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'segment_id' => $this->post->segment_id,
                                        'subject_start' => isset($this->post->subject_start)?$this->post->subject_start:'',
                                        'subject' => isset($this->post->subject)?$this->post->subject:'',
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                    ];
                die(Subscribers::saveSubscribe($id, $parr));
            }elseif($this->post->method == 'go_test_email'){
            $subject_start = '';
            $subject = $this->post->subject;
	if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
	if (isset($this->post->s_start) and $this->post->s_start == 1){
            $subject = $subject_start.', TEST, '.$subject; 
        }
        
        $copy = 2;                             
	if(isset($this->post->copy) and isset($this->post->copy_email)){
            $copy = $this->post->copy;   
	}
        
        $this->view->post = $this->post;  
        $this->view->name = 'Test';
        $this->view->email = $this->post->test_email;
        $this->view->track_open = 'https://www.red.ua/email/image/?photo=test.jpg';
        $this->view->track = '?'
                                . '&utm_source=test_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign=TEST'
                                . '&utm_email_track=test';
        
         
        $this->view->unsubscribe = ''
                                . '&utm_source=test_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign=TEST';
        
        SendMail::getInstance()->sendSubEmail($this->post->test_email, 'Testing', $subject, $this->view->render('mailing/general-email.tpl.php'));
	//SendMail::getInstance()->sendSubEmail($_POST['test_email'], 'Testing', $subject, $msg, '','','', '', $copy, 'management@red.ua', 'Ирина');
            die(json_encode(array('status' => 'send', 'from' => $this->post->test_email)));
    }elseif($this->post->method == 'go_send_email') {
        $s_track = '';
        if($this->post->track){
            $s_track = $this->post->track;
        }
        
        $cnt = 0;
	$error = 0;
	$subject_start = '';
        $subject = $this->post->subject;
        
	if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
	if($this->post->from_mail == 0){
            $s_track = base64_encode(date('Y-m-d H:i:s'));
				if($this->post->id_post){
				$s = new Emailpost($this->post->id_post);
                                if($s->segment_id == $this->post->segment_id){
				$s->setGo(date('Y-m-d H:i:s'));
                                $s->setCountGo($this->post->all_count);
                                $s->setTrack($s_track);
				$s->setIdCustomerGo($this->user->getId());
				$s->save();
                                }else{
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'go' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'id_customer_go' => $this->user->getId(),
                                        'segment_id' => $this->post->segment_id,
                                        'subject_start' => $subject_start,
                                        'subject' => $subject,
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'track' => $s_track,
                                        'count_go' => $this->post->all_count
                                    ];
				Subscribers::saveSubscribe(false, $parr);
                                }
				}else{
                                    $parr = [
                                        'ctime' => date('Y-m-d H:i:s'),
                                        'go' => date('Y-m-d H:i:s'),
                                        'id_customer_new' => $this->user->getId(),
                                        'id_customer_go' => $this->user->getId(),
                                        'segment_id' => $this->post->segment_id,
                                        'subject_start' => $subject_start,
                                        'subject' => $subject,
                                        'intro' => $this->post->intro?$this->post->intro:'',
                                        'ending' => $this->post->ending?$this->post->ending:'',
                                        'track' => $s_track,
                                        'count_go' => $this->post->all_count
                                    ];
				Subscribers::saveSubscribe(false, $parr);
				}
		}
    $this->view->post = $this->post;        
    $count = $this->post->count;
    $emails = [];
foreach (wsActiveRecord::useStatic('Subscriber')->findAll(['active' => 1, 'segment_id' => $this->post->segment_id ], ['email' => 'ASC'], [$this->post->from_mail, $count]) as $sub){  
    if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
		
        $subject_new = $subject;
                
        if(isset( $this->post->s_start) and  $this->post->s_start == 1){
		$subject_new = $subject_start.', '.$sub->getName().', '.$subject;
        }
                    
                    $track = $sub->segment->track;
                    $this->view->track_open = 'https://www.red.ua/email/image/?photo='.$s_track.'.jpg';
			$this->view->openimg = ''
                                . 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1'
                                . '&cid='.$sub->getId().''
                                . '&t=event'
                                . '&el='.$sub->getId().''
                                . '&cs='.strtolower($track).'_open_'.date('d.m.Y').''
                                . '&cm=open'
                                . '&cn='.$track;
                        
                        $this->view->track = '?'
                                . '&utm_source='.strtolower($track).'_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign='.$track.''
                                . '&utm_email_track='.$s_track;
                        
                        $this->view->unsubscribe = ''
                                . '&utm_source='.strtolower($track).'_unsubscribe_'.date('d.m.Y').''
                                . '&utm_medium=link'
                                . '&utm_campaign='.$track;
                       
                        $this->view->name = $sub->getName();
                        $this->view->email = $sub->getEmail();
                        $emails[] = $sub->getEmail();
			SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->getName(), $subject_new, $this->view->render('mailing/general-email.tpl.php'));
			$cnt++;
                        sleep(2);
		}else{
                    $sub->setActive(0);
                    $sub->save();
                    $error++;
			wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
		}			 
    }	   
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => implode(',',$emails), 'cnt'=>$cnt, 'error'=>$error, 'track'=>$s_track))); 
        }elseif($this->post->method == 'go_send_email_segment'){
            $s_track = '';
        if($this->post->track){
            $s_track = $this->post->track;
        }else{
             $s_track = base64_encode(date('Y-m-d H:i:s'));
        }
             $cnt = 0;
	$error = 0;
	$subject_start = '';
        $subject = $this->post->subject;
        
	if($this->post->subject_start){
            $subject_start = $this->post->subject_start;
        }
	
    $this->view->post = $this->post;        
    $count = $this->post->count;
                    
    $emails = [];
foreach (wsActiveRecord::useStatic('Customer')->findAll(['segment_id' => $this->post->segment_id ], ['id' => 'ASC'], [$this->post->from_mail, $count]) as $sub){  
    if (isValidEmailNew($sub->getEmail()) and isValidEmailRu($sub->getEmail())){
		
        $subject_new = $subject;
                
        if(isset( $this->post->s_start) and  $this->post->s_start == 1){
		$subject_new = $subject_start.', '.$sub->first_name.', '.$subject;
        }
                    
                  $track = $sub->segment->track;
                    $this->view->track_open = 'https://www.red.ua/email/image/?photo='.$s_track.'.jpg';
			$this->view->openimg = ''
                                . 'https://www.google-analytics.com/collect?v=1&tid=UA-29951245-1'
                                . '&cid='.$sub->getId().''
                                . '&t=event'
                                . '&el='.$sub->getId().''
                                . '&cs='.strtolower($track).'_open_'.date('d.m.Y').''
                                . '&cm=open'
                                . '&cn='.$track;
                        
                        $this->view->track = '?'
                                . '&utm_source='.strtolower($track).'_click_'.date('d.m.Y').''
                                . '&utm_medium=email'
                                . '&utm_campaign='.$track.''
                                . '&utm_email_track='.$s_track;
                        
                        $this->view->unsubscribe = '';
                               
                        $this->view->name = $sub->first_name;
                        $this->view->email = $sub->getEmail();
                        $emails[] = $sub->getEmail();
			//SendMail::getInstance()->sendSubEmail($sub->getEmail(), $sub->first_name, $subject_new, $this->view->render('mailing/general-email.tpl.php'));
			$cnt++;
		}else{
                   // $sub->setActive(0);
                   // $sub->save();
                    $error++;
			wsLog::add('E-mail error: ' . $sub->getEmail(), 'EMAIL');
		}			 
    }	   
    die(json_encode(array('status' => 'send', 'from' => $this->post->from_mail, 'count' => $count, 'emails' => implode(',',$emails), 'cnt'=>$cnt, 'error'=>$error, 'track'=>$s_track))); 
            
        }   
        }
        
        public function imageAction(){
        if($this->get->photo){
            $track = substr($this->get->photo, 0, -4);
            Emailpost::openEmail(['track'=>$track]);
        }  
        return true;
        }

}
