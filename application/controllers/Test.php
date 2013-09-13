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

class TestController extends Yaf_Controller_Abstract 
{
	public function testAction()
	{
		$pay = new paytest_alipay();
		$pay->aliapytest();
	}
	
	public function getAction()
	{
		$id = $this->getRequest()->getParam("id");
		if (empty($id)) throw new Yaf_Exception("ID为必需参数", 100001);
		
		$test = new TestModel();
		$result = $test->get($id);
		echo json_encode($result);
		
		//sysFun::getInstance()->display($result);
	}
	
	public function postAction()
	{
		$test = new TestModel();
		$data = array(
				'name' => "guest",
				'pwd' => "123456"
		);
		$id = $test->insert($data);
		echo "\$id = ", $id, "<br>";
	}
	
	public function putAction()
	{
		
		$test = new TestModel();
		$data = array(
				'name' => "guest4",
				'pwd' => "123456"
		);
		$id = $test->save($data, $id = 9);
		echo "\$id = ", $id, "<br>";
	}
	
	public function putaAction()
	{
		$test = new TestModel();
		$data = array(
				'name' => "guest2",
				'pwd' => "654321"
		);
		$id = $test->update($data, "`id` = 7");
		echo "\$id = ", $id, "<br>";
	}
	
	public function countAction()
	{
		$type = $this->getRequest()->getParam("type");
		$where = '';
		if ($type == 1) {
			$where = "`id` > 5";
		}
		$test = new TestModel();
		$id = $test->count($where);
		echo "\$id = ", $id, "<br>";
	}
	
	public function fetchAction()
	{
		$id = $this->getRequest()->getParam("id");
		if (empty($id)) throw new Yaf_Exception("ID为必需参数", 100001);
		
		$test = new TestModel();
		$result = $test->fetch("`id` = {$id}");
		sysFun::getInstance()->display($result);
	}
	
	public function kvlistAction()
	{
		$id = $this->getRequest()->getParam("id");
		if (empty($id)) throw new Yaf_Exception("ID为必需参数", 100001);
		
		$test = new TestModel();
		$result = $test->kvlist("id", "name", "`id` > {$id}");
		sysFun::getInstance()->display($result);
	}
}