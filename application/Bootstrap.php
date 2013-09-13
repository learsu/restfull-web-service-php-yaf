<?php
/**
 * This file should be under the APPLICATION_PATH . "/application/"(which was defined in the config passed to Yaf_Application).
 * and named Bootstrap.php,  so the Yaf_Application can find it 
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {
	
	public $config = "";
    
    public function _initConfig() {
    	$this->config = Yaf_Application::app()->getConfig()->toArray();
    	Yaf_Registry::set("config", $this->config);
    }
    
    public function _initRoute(Yaf_Dispatcher $dispatcher) {
    	$router = $dispatcher->getRouter();
    }
    
    public function _initViewParameters(Yaf_Dispatcher $dispatcher) {
    	$dispatcher->disableView();
    }
}
