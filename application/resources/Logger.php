<?php

/**
 * MyWiki_Log
 *
 * Logs all messages to a log file
 * path to log file defined in configs
 * 
 */
class MyWiki_Resource_Logger extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * 
	 * @var Zend_Log
	 */
    protected $_log = null;
    
    public function init()
    {
    	return $this;
    }
    
    /**
     * 
     * @return Zend_Log
     */
    
    public function getLogger()
    {
        if ($this->_log === NULL) {
        	$options = $this->getOptions();
            $logFile    = $options['logs'] . date( 'Ymd') . '_' . $options['filename'];
            /* -- Create and attach a new Zend_Log instance to $this->_log */
        }
        
        return $this->_log;
    }
}
