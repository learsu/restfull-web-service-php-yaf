<?php

class Base
{
	//数据库链接句柄
	protected $connect = false;
	//SQL执行结果集
	protected $result = false;
	//SQL语句
	protected $strSql = '';
	
	//表名
	protected  $_name = '';
	//主键
	protected  $_primary = '';
	//校验规则
	protected $_dataValidate = '';
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->connection();
	}
	
	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		$this->closeConnection();
		unset($this->connect);
		unset($this->result);
		unset($this->strSql);
		
		unset($this->_primary);
		unset($this->_name);
		unset($this->_dataValidate);
	}
	
	/**
	 * 数据库链接函数
	 * @throws Yaf_Exception
	 */
	protected function connection()
	{
		if ($this->connect) {
			return $this->connect;
		}
		//引入配置文件MYSQL配置数组
		$conf = Yaf_Registry::get("config");
		//建立MYSQL链接
		$this->connect = mysqli_connect($conf['db']['host'], $conf['db']['user'], $conf['db']['pwd'],
				$conf['db']['database'], $conf['db']['port']);
		if (!$this->connect) {
			throw new Yaf_Exception(mysqli_connect_error(), 1);
		}
		//设置字符集
		mysqli_set_charset($this->connect, $conf['db']['charset']);
		return $this->connect;
	}
	
	/**
	 * 出错信息
	 * @throws Yaf_Exception
	 */
	protected function errorMsg()
	{
		throw new Yaf_Exception(mysqli_error($this->connect), 2);
	}
	
	/**
	 * 关闭数据库链接
	 */
	protected function closeConnection()
	{
		$this->connect && mysqli_close($this->connect);
	}
	
	/**
	 * 事务执行
	 * @param array $sqlArr
	 */
	protected function commit()
	{
		$all_query_ok = true;
		mysqli_autocommit($this->connect, FALSE);
		foreach ($this->strSql as $value) {
			mysqli_query($this->connect, $value) ? null : $all_query_ok = false;
		}
		$all_query_ok ? mysqli_commit($this->connect) : mysqli_rollback($this->connect);
		return $all_query_ok;
	}
	
	/**
	 * dict 
	 * key=>value
	 * @param string $where
	 * @param array $fields($key, $value)
	 * @param string $order
	 * @param int $limit
	 * @return array
	 */
	public function dict($where = '', $fields = array(), $order = '', $limit = 200)
	{
		list($key, $value) = $fields;
		//组合需要的字段名
		$fields = "`{$key}`, `{$value}`";
		//组合查询条件
		$where == '' && $where = '1=1';
		$this->strSql = "select {$fields} from {$this->_name} where {$where}";
		//组合排序条件
		!empty($order) && $this->strSql .= " order by $order";
		$limit != '' && $this->strSql .= " limit $limit";
		
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		$resultArr = array();
		while ($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)) {
			$resultArr[$row[$key]] = $row[$value];
		}
		mysqli_free_result($this->result);
		return $resultArr;
	}
	
	/**
	 * left join 以joinItem为key返回数组
	 * @param string $joinItem
	 * @param string $where
	 * @param unknown $fields
	 * @param string $order
	 * @param number $limit
	 * @return multitype:unknown
	 */
	public function join($joinItem = '', $where = '', $fields = array(), $order = '', $limit = 200)
	{
		//组合需要的字段名
		$fields == '' && $fields = array();
		$fields = (array) $fields;
		if (!empty($fields)) {
			$fields = "`" . implode("`,`", $fields) . "`";
		} else {
			$fields = '*';
		}
		//组合查询条件
		$where == '' && $where = '1=1';
		$this->strSql = "select {$fields} from {$this->_name} where {$where}";
		//组合排序条件
		!empty($order) && $this->strSql .= " order by $order";
		$limit != '' && $this->strSql .= " limit $limit";
		
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		$resultArr = array();
		while ($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)) {
			$resultArr[$row[$joinItem]] = $row;
		}
		mysqli_free_result($this->result);
		return $resultArr;
	}
	
	/**
	 * 获取多条记录
	 * $limit默认情况最多获取200条数据
	 * @param string $where
	 * @param array $fields
	 * @param string $order
	 * @param string $limit
	 * @return array
	 */
	public function iter($where = '', $fields = array(), $order = '', $limit = 200)
	{
		//组合需要的字段名
		$fields == '' && $fields = array();
		$fields = (array) $fields;
		if (!empty($fields)) {
			$fields = "`" . implode("`,`", $fields) . "`";
		} else {
			$fields = '*';
		}
		//组合查询条件
		$where == '' && $where = '1=1';
		$this->strSql = "select {$fields} from {$this->_name} where {$where}";
		//组合排序条件
		!empty($order) && $this->strSql .= " order by $order";
		$limit != '' && $this->strSql .= " limit $limit";
		
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		$resultArr = array();
		while ($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)) {
			$resultArr[] = $row;
		}
		mysqli_free_result($this->result);
		return $resultArr;
	}
	
	/**
	 * 获取单条记录
	 * @param string $where
	 * @param array $fields
	 * @param string $order
	 * @return array
	 */
	public function find($where, $fields = array(), $order = '')
	{
		//组合需要的字段名
		$fields == '' && $fields = array();
		$fields = (array) $fields;
		if (!empty($fields)) {
			$fields = "`" . implode("`,`", $fields) . "`";
		} else {
			$fields = '*';
		}
		//组合查询条件
		$where == '' && $where = '1=1';
		$this->strSql = "select {$fields} from {$this->_name} where {$where}";
		//组合排序条件
		!empty($order) && $this->strSql .= " order by $order";
		$this->strSql .= " limit 1";
		$resultArr = array();
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		$row = mysqli_fetch_array($this->result, MYSQLI_ASSOC);
		mysqli_free_result($this->result);
		!empty($row) && $resultArr = $row;
		return $resultArr;
	}
	
	/**
	 * 获取单条记录 by primary key
	 * 此方法只获取一条 by primary key
	 * @param int $id
	 * @param array $fields
	 * @return array
	 */
	public function get($id, $fields = array())
	{
		//组合需要的字段名
		$fields == '' && $fields = array();
		$fields = (array) $fields;
		if (!empty($fields)) {
			$fields = "`" . implode("`,`", $fields) . "`";
		} else {
			$fields = '*';
		}
		$this->strSql = "select {$fields} from {$this->_name} where {$this->_primary} = '{$id}' limit 1";
		$resultArr = array();
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		$row = mysqli_fetch_array($this->result, MYSQLI_ASSOC);
		mysqli_free_result($this->result);
		!empty($row) && $resultArr = $row;
		return $resultArr;
	}
	
	/**
	 * 统计记录
	 * @param string $where
	 * @return int
	 */
	public function count($where = '')
	{
		if (empty($where)) {
			$this->strSql = "select count(*) as num from {$this->_name}";
		} else {
			$this->strSql = "select count(*) as num from {$this->_name} where {$where}";
		}
		$resultArr = array();
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		$row = mysqli_fetch_array($this->result, MYSQLI_ASSOC);
		mysqli_free_result($this->result);
		!empty($row) && $resultArr = $row;
		return $resultArr['num'];
	}
	
	/**
	 * 插入记录
	 * @param array $data
	 * @return int
	 */
	public function insert($data)
	{
		//数据校验
		$validateObj = Validate::getInstance();
		$validateObj->_validate($data, $this->_dataValidate);
		//组合键／值
		$fieldArr = array_keys($data);
		$fields = "`" . implode("`,`", $fieldArr) . "`";
		$valueArr = array_values($data);
		$values = "'" . implode("','", $valueArr) . "'";
		//执行SQL
		$this->strSql = "insert into {$this->_name} ({$fields}) values ({$values})";
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		return mysqli_insert_id($this->connect);
	}
	
	/**
	 * 更新记录
	 * $flag默认情况只能更新200条记录
	 * @param array $data
	 * @param string $where
	 * @param int $flag
	 * @return int
	 */
	public function update($data, $where = "1=1", $flag = 0)
	{
		//数据校验
		$validateObj = Validate::getInstance();
		$validateObj->_validate($data, $this->_dataValidate);
		//组合键／值
		$dateStr = "";
		foreach ($data as $key => $value) {
			$dateStr .= "`{$key}` = '{$value}',";
		}
		$dateStr = substr($dateStr, 0, -1);
		$limit = "limit 200";
		$flag == 1 && $limit = '';
		//执行SQL
		$this->strSql = "update {$this->_name} set {$dateStr} where {$where} {$limit}";
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		return mysqli_affected_rows($this->connect);
	}
	
	/**
	 * 更新记录 by primary key
	 * 此方法只更新一条 by primary key
	 * @param array $data
	 * @param int $id
	 * @return int 0|1
	 */
	public function save($data, $id = 0)
	{
		//数据校验
		$validateObj = Validate::getInstance();
		$validateObj->_validate($data, $this->_dataValidate);
		//组合键／值
		$dateStr = "";
		foreach ($data as $key => $value) {
			$dateStr .= "`{$key}` = '{$value}',";
		}
		$dateStr = substr($dateStr, 0, -1);
		//执行SQL
		$this->strSql = "update {$this->_name} set {$dateStr} where {$this->_primary} = '{$id}' limit 1";
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		return mysqli_affected_rows($this->connect);
	}
	
	/**
	 * 删除记录
	 * $flag默认情况只能删除200条记录
	 * @param string $where
	 * @param int $flag
	 * @return int
	 */
	public function delete($where, $flag = 0)
	{
		$limit = "limit 200";
		$flag == 1 && $limit = '';
		$this->strSql = "delete from {$this->_name} where {$where} {$limit}";
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		return mysqli_affected_rows($this->connect);
	}
	
	/**
	 * 删除记录 by primary key
	 * 此方法只删除一条 by primary key
	 * @param int $id
	 * @return int 0|1
	 */
	public function del($id)
	{
		$this->strSql = "delete from {$this->_name} where {$this->_primary} = '{$id}' limit 1";
		$this->result = mysqli_query($this->connect, $this->strSql);
		!$this->result && $this->errorMsg();
		return mysqli_affected_rows($this->connect);
	}
}