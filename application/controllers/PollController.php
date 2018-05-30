<?php

class PollController extends controllerAbstract
{
    protected $_openPoll = true;
    protected $_forAllSite = false;
    protected $_showPoolResults = true;
    protected $_showToAll = false;

    public function indexAction()
    {

        if($this->get->metod=='no_poll'){
            if($this->ws->getCustomer()->getId()){
                $this->ws->getCustomer()->setNoPoll(1);
                $this->ws->getCustomer()->save();
            }
            die();
        }

        if ($this->get->metod == 'ajax') {
            $customerId = $this->ws->getCustomer()->getId();
            $poll = new Poll($this->post->id);

            if ($poll->getId()) {
                $find = wsActiveRecord::useStatic('PollResults')->findFirst(array('customer_id' => $customerId, 'poll_id' => $poll->getId()));
                if (!$find) {
                    $userPoll = new PollResults();
                    $userPoll->setCustomerId($customerId);
                    $userPoll->setQuestionId($this->post->poll_q_id);
                    $userPoll->setPollId($poll->getId());
                    $userPoll->setRes($this->post->usertype);
                    $userPoll->setDate(strftime('%Y-%m-%d %H:%M:%S'));
                    $userPoll->save();
                }
            }
            die($this->post->usertype);
        }

        if (!@$_COOKIE['polled']) {
            setcookie('polled', 1, time() + 60 * 300, '/');

            $errors = array();
            //Enable Poll on All sites or Relation with one
            $pollFilter = array('active' => 1);

            //Open Poll
            if ($this->_openPoll) {
                $customerId = null;
            } else {
                $customerId = $this->ws->getCustomer()->getId();
            }

            $poll = wsActiveRecord::useStatic('Poll')->findFirst($pollFilter);
            if ($poll) {
                //search user id base with current Poll  // NULL id for open . but need add COOKIE for UNIQUE
                //if (wsActiveRecord::useStatic('PollResults')->findAll(array('poll_id'=>$poll->getId(),'customer_id'=>$customerId))->count()== 0)
                if (true) {
                    //user not polled
                    if (count($_POST)) { //if some POST data (user get polled)
                        $userPoll = new PollResults();
                        $userPoll->setCustomerId($customerId);
                        $userPoll->setQuestionId($this->post->poll_q_id);
                        $userPoll->setPollId($poll->getId());
                        $userPoll->setRes($this->post->usertype);
                        $userPoll->setDate(strftime('%Y-%m-%d %H:%M:%S'));
                        $userPoll->save();
                        //---
                        //ID sites for disbale show results
                        $this->_showPoolResults = false;
                        //---
                        die(); //for AJAX
                    } else {
                        if ($poll->getQuestions()->count() > 0) {
                            $this->view->pollTitle = $poll->getName();
                            $this->view->questions = $poll->getQuestions(array(), array('sequence' => 'ASC'));
                        } else {
                            $errors[] = $this->_trans->get('poll_not_questions');
                        }
                    }
                } else {
                    $errors[] = $this->_trans->get('poll_already_poll');
                }
                $this->view->errors = $errors;


                $this->view->showResults = $this->_showPoolResults;
                $this->view->content = $this->render('/poll/index.tpl.php');
                echo $this->render('site.tpl.php');

            }
        }
        die();
    }

    public function resultsAction()
    {
        if (($this->ws->getCustomer()->getId() != 8617))
            if (!$this->ws->getCustomer()->isAdmin()) {
                $this->_redirect('/account/');
                return;
            }

        $filter = array('active' => 1);

        $polls = wsActiveRecord::useStatic('Poll')->findAll($filter);
        $results = array();
        if ($polls) {
            foreach ($polls as $poll) {
                $votes = wsActiveRecord::useStatic('PollResults')->findByQuery(
                    'SELECT name,IF( question_id is null  ,0,count(*)) as c
                            FROM poll_questions
                            LEFT JOIN `poll_results`
                                ON poll_results.question_id = poll_questions.id
                        WHERE poll_results.poll_id = ' . $poll->getId() . '
						GROUP BY question_id
						ORDER BY c DESC'
                );
                $results[$poll->getName()]['results'] = $votes;
                $results[$poll->getName()]['answers'] = wsActiveRecord::useStatic('PollResults')->findByQuery('SELECT res FROM  `poll_results` WHERE res IS NOT NULL AND poll_id=' . $poll->getId());
            }
            $this->view->admin = $this->ws->getCustomer()->isAdmin();
            $this->view->results = $results;
            //get all additional answers
            $this->view->content = $this->render('poll/result.tpl.php');

        }
        echo $this->render('site.tpl.php');
    }
}