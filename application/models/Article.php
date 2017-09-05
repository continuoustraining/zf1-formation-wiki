<?php

class Default_Model_Article extends Zend_Db_Table_Row_Abstract
{
	protected $_data = array(
		'listing_id' 	=> null,	
		'title' 		=> null,	
		'content' 		=> null,
		'modified' 		=> null,
		'user_id'		=> null
	);
	protected $_tableClass = 'Default_Model_DbTable_Articles';
	
}