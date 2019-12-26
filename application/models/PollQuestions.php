<?php
class PollQuestions extends wsActiveRecord{
	protected $_table = 'poll_questions';
	protected $_orderby = array('id' => 'ASC');
        protected $_multilang = array('name' => 'name');


}