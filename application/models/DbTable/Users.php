<?php

class Default_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
	protected $_name = 'wikiusers';
	/* -- Set the row class for this table gateway object.
	 * Examine the parent class if you don't know the property name.
	 */
	
	protected $_referenceMap    = array(
        'LastModified' => array(
            'columns'           => 'user_id',
            'refTableClass'     => 'Default_Model_DbTable_Articles',
            'refColumns'        => 'user_id'
        )
    );
	
}