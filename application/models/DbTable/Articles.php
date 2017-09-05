<?php

class Default_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{
	protected $_name = 'listings';
	protected $_rowClass = 'Default_Model_Article';
	protected $_referenceMap    = array(
        'Owner' => array(
            'columns'           => 'user_id',
            'refTableClass'     => 'Default_Model_DbTable_Users',
            'refColumns'        => 'user_id'
        ),
        'Original' => array(
            'columns'           => 'title',
            'refTableClass'     => 'Default_Model_DbTable_Articles',
            'refColumns'        => 'title'
        )
    );
}