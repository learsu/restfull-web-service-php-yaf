<?php
class TestModel extends Base
{
	//表名
	protected $_name = 'test';
	//主键
	protected $_primary = 'id';
	//事务关联表名
	protected $_pk_test1 = "test1";
	protected $_pk_test2 = "test2";
	
	//数据校验规则
	protected $_dataValidate = array(
			'name'=> array(
					'isNotEmpty' => array('msg' => '用户名不能为空', 'code' => 1001),
					'isUserName' => array('msg' => '用户名不符合规则', 'code' => 1002),
			),
			'pwd'=> array(
					'isNotEmpty' => array('msg' => '密码不能为空', 'code' => 1003),
			)
	);
	
	/**
	 * 事务
	 * @param unknown $data
	 */
	public function transaction($data)
	{
		list($testId, $test2Id, $test2Id) = $data;
		
		$sqlArr = array();
		//事务SQL ARRAY 开始
		$sqlArr[] = "update {$this->_name} set status = '200' where id = '{$testId}'";
		$sqlArr[] = "update {$this->_pk_test1} set fil1 = '111' where id = '{$test2Id}'";
		$sqlArr[] = "insert into {$this->_pk_test2} (id, fil1) values ('{$test2Id}', '2222')";
		//执行SQL
		return $this->_DB->commit($sqlArr);
	}
}