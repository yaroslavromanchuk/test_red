<?php
class MailingList extends wsActiveRecord
{
	protected $_table = 'mailing_lists';
	protected $_orderby = array('id' => 'ASC');

	protected function _defineRelations()
	{	
	}

}
?>