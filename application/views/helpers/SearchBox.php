<?php
class Zend_View_Helper_SearchBox extends Zend_View_Helper_Abstract
{
	
	/**
	 * 
	 * @return Default_Form_Search
	 */
    public function searchBox() 
    {
        $urlHome = $this->view->urlHome;
        $form    = new Default_Form_Search(array(
            'action' => $this->view->url(
        		array(
        			'controller'	=> 'article',
        			'action'		=> 'view'
        		),
        		null,
        		true
        	)
        ));
    
        return $form;
    }
}
