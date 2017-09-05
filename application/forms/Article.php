<?php
class Default_Form_Article extends Zend_Form
{
    public function init()
    {
        $this->addElement('textarea', 'content', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));
		/* -- Create a "submit" element with 
		 * size = 10
		 * label = Create
		 * ignore = true
		 */
    }
}
