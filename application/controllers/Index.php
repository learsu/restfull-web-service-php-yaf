<?php
/**
 * test服务
 * service name test
 * service code 100
 * code range 001-999
 * service code range 100001-100999
 * @author learsu
 *
 */
class IndexController extends Yaf_Controller_Abstract 
{
	public function indexAction()
	{
		echo phpinfo();
	}
}