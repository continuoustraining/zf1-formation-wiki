<?php

class ArticleController extends Zend_Controller_Action
{

    public function viewAction()
    {
    	$articleTable = new Default_Model_DbTable_Articles();
        $request      = $this->getRequest();
        $title        = $request->getParam('title');

        if (empty($title)) {
            $this->_redirect('/');
            return;
        }

        $this->view->pageTitle = $title;

        if ($request->getParam('id')) {
            $listingId = $request->getParam('id');
            /* -- Use $articleTable to find a single listing based on $listingId 
             * Place it in $listing.
             * */
        } else {
            $listing = $articleTable
            	->select()
            	->where('title = ?', $title)
            	->order('modified DESC')
            	->limit(1)
            	->query()
            	->fetchObject('Default_Model_Article');
        }
        
        if ($listing) {
        	$this->view->lastModified 	= $listing->findDefault_Model_DbTable_UsersByLastModified()->current();
        	$originalListing = $listing->findDefault_Model_DbTable_ArticlesByOriginal()->current();
        	$this->view->owner 			= $originalListing->findDefault_Model_DbTable_UsersByLastModified()->current();
            $this->view->listing 		= $listing;
        } else {
        	/* -- Forward the request to the newarticle action.  Test this 
        	 * by navigating to an article that does not exist.
        	 */
        }
    }
    
    public function newarticleAction()
    {
		
        $title = $this->_request->getParam('title');
        if ($this->_checkAcl($title)) {
        	$form = new Default_Form_Article();
        	if ($this->_request->isPost()) {
        		if ($form->isValid($this->_request->getPost())) {
        			$articleTable = new Default_Model_DbTable_Articles();
        			$article = $articleTable->fetchNew();
        			
        			/* -- Populate the $article object with 
        			 *  - The content from the form;
        			 *  - The title 
        			 *  - Set the modified time to the current time
        			 *  - set the user_id from the current identity of the user
        			 *  
        			 *  Finish by saving it.
        			 */
        			
        			$this->_forward('view', 'article', null, array('title' => $title));
        			return;        			
        		}
        	}
	    	$url = $this->view->url(
	            array(
	            	'controller'	=> 'article',
	            	'action'		=> 'newarticle',
	            	'title'			=> $title
	            )
			);
            $form->setAction($url);
            /* -- Attach the form to the view object property called "form" */
    		
        }
    	
    }

    public function viewhistoryAction()
    {    
        
        $title        = $this->_request->getParam('title');
        $title        = urldecode($title);

        if (empty($title)) {
            return $this->_redirect('/');
        }

        $this->view->pageTitle = $title;
        $history = array();
        $historyOwner = array();
        $articleTable = new Default_Model_DbTable_Articles();
        $stmt = $articleTable
		            ->select()
		            /* -- add a where clause for selecting by "title".
		             * Don't forget to put in the prepared statement
		             * placeholder.
		             * */
		            ->order('modified DESC')
		            ->query();
		/* -- Execute the prepared statement, remembering to pass in the title */
		$this->view->listing = null;
        while (($obj = $stmt->fetchObject('Default_Model_Article')) !== false) {

        	$this->view->listing = $history[] = $obj;
        	
        }
        
        if ($history) {
            $this->view->history = $history;
        }
    }

    public function editAction()
    {
        if (!$title = $this->_getTitle()) {
            return;
        }

        // Enforcing access control here
        if (!$this->_checkAcl($title)) {
            return;
        }

        // Validate form
        $form    = new Default_Form_ArticleEdit();
        $form->setMethod('post')
    		->setAction($this->view->url());
        
        $request = $this->getRequest();
        if (!$request->isPost() || !$form->isValid($request->getPost())) {
            // Failed validation; redisplay form
			$articleTable = new Default_Model_DbTable_Articles();
            $listing = $articleTable
            	->select()
            	->where('title = ?', $title)
            	->order('modified DESC')
            	->limit(1)
            	->query()
            	->fetchObject('Default_Model_Article');
            	
            if (!$listing) {
                // Attempted to submit an edit form for a new article
                throw new MyWiki_Exception('Cannot edit entries that do not exist');
            }
            if (!$request->isPost()) {
            	$form->getElement('content')->setValue($listing->content);
            }
            $this->view->form        = $form;
            /* -- Assign $title to the view's pageTitle property */
            /* -- Assign $listing to the view's listing property */
            
            return;
        }

		$articleTable = new Default_Model_DbTable_Articles();
        $article = $articleTable->fetchNew();
        $article->content = $form->getValue('content');
        $article->title = $title;
        $article->user_id = Zend_Auth::getInstance()->getIdentity()->user_id;
		$article->modified = time();
		
        // Insert the new article
        $article->save();
        
        return $this->_forward('view', 'article', null, array('title' => $title));
        
    }

    protected function _getTitle()
    {
        $title = urldecode($this->getRequest()->getParam('title', ''));
        if (empty($title)) {
            $this->_redirect('/');
            return false;
        }
        return $title;
    }

    protected function _checkAcl($title)
    {
        $username = 'Guest';
        $auth     = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->username;
        }
        $acl      = new MyWiki_Acl();
        /* -- Validate $uesrname against the ACL for the action "create"
         * on the resource "article" */
        if (!false) {
            if ('view' != $this->getRequest()->getActionName()) {
                throw new MyWiki_PermissionDenied_Exception('You need to be logged in to create and edit pages!');
                $this->_forward('view', 'article', null,array('title' => $title));
            }
            return false;
        }
        return true;
    }

}
