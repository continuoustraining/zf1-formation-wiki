<?php
class Default_Form_ArticleEdit extends Zend_Form
{
    public function init()
    {
    	/* -- Create a required textarea element called "content
    	 * with a StringTrim filter 
    	 */

        $this->addElement('submit', 'Save', array(
            'size'   => 10,
            'label'  => 'Save',
            'ignore' => true,
        ));
    }
}
