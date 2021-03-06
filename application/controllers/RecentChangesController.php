<?php

class RecentChangesController extends Zend_Controller_Action 
{
    public function indexAction() 
    {
        $this->view->pageTitle = "Recent Changes";
        $articleTable = new Default_Model_DbTable_Articles();
        
        $stmt = $articleTable->select()
	        			  ->order('modified DESC')
	        			  ->group('title')
	               		  ->limit(20, 0)
				          ->query();
		$this->view->listings = array();
		while (($obj = $stmt->fetchObject('Default_Model_Article')) !== false) {
			$this->view->listings[] = $obj;
		}
    }
}
