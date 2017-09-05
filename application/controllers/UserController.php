<?php
class UserController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $this->view->form = new default_Form_Login();
    }

    public function loginAction()
    {
        $request = $this->getRequest();
                if (!$request->isPost()) {
                    // Didn't get a POST request; show login form
                    $this->_forward('index');
                    return;
                }
        
                // Make sure form validates first
                $form = new default_Form_Login();
                if (!$form->isValid($request->getPost())) {
                    $this->view->form = $form;
                    $this->render('index');
                    return;
                }
        
                // Authenticate user
                $auth = Zend_Auth::getInstance();
                /* -- Create a Zend_Auth adapter object for connecting to the database.
                 * For the database connection, use the one that is currently in use as
                 * the default adapter for Zend_Db_Table_Abstract.  The table name is
                 * "wikiusers", the identity column is "username" and the password column
                 * is "password"
                 * 
                 */
        
        
                /* -- Set the identity and credential for the adapter using the
                 * form values "username" and "password".  Remember that SQLite does
                 * not have an internal MD5() function so you need to make sure to
                 * md5() the password.
                 */
        
                $authResult = $auth->authenticate($adapter);
                if (!$authResult->isValid()) {
                	$form->getElement('username')->addErrors($authResult->getMessages());
                    $this->view->form = $form;
                    $this->render('index');
                    return;
                }
        
                $userTable = new default_Model_DbTable_Users();
                // Store all user details except password in authentication session
                $auth->getStorage()->write(
                    $userTable->find($adapter->getResultRowObject('user_id')->user_id)->current()
                );
        
                // Redirect to home page
                $this->_redirect('/');
    }

    public function logoutAction()
    {
        // Logout the user
                Zend_Auth::getInstance()->clearIdentity();
                
                // Redirect to home page
                $this->_redirect('/');
    }

    public function viewAction()
    {
        $userTable = new default_Model_DbTable_Users();
            	$user = $userTable->find(
            		$this->_request->getParam('user')
            	)->current();
            	if (!$user) {
            		throw new MyWiki_Exception('Unknown user');
            	}
            	$this->view->articles = $user->finddefault_Model_DbTable_ArticlesByOwner();
            	$this->view->user = $user;
    }

}


