<?php
/**
 * 数据校验类
 * 主要用于入库前按MODEL层规则对数据进行校验
 * @author learsu
 *
 */
class Validate
{
	//唯一实例
	private static $_instance;
	
	/**
	 * 获取唯一实例
	 */
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 数据校验方法
	 * 主要用于TABLE层数据校验
	 * @param array $data
	 * @param array $ruleArr
	 * @throws Yaf_Exception
	 */
	public function _validate($data, $ruleArr)
	{
		if (empty($ruleArr)) return $data;
		foreach ($ruleArr as $key => $patterns) {
			if (!isset($data[$key])) continue;
			foreach ($patterns as $pattern => $notice) {
				$funName = $pattern . "Valid";
				if (!method_exists($this, $funName)) {
					throw new Yaf_Exception("validate method don't exist", 3);
				}
				if (!$this->$funName($data[$key])) {
					throw new Yaf_Exception($notice['msg'], $notice['code']);
				}
			}
		}
	}
	
	/**
	 * 验证不为空
	 * @param string $string
	 * @return bool
	 */
	function isNotEmptyValid($string)
	{
		if (trim($string) == "") return false;
		return true;
	}
	
	/**
	 * 验证用户名
	 * @param string $string
	 * @return bool
	 */
	function isUserNameValid($string)
	{
		if (!preg_match("/^[a-zA-Z][0-9a-zA-Z\-]{3,14}$/", $string)) return false;
		return true;
	}
	
	public function isEmailValid($string)
	{
		if (!preg_match("/[\._a-zA-Z0-9-]+(@|#)[\._a-zA-Z0-9-]+/i", $string)) return false;
		return true;
	}
	
	/**
	 * 验证网址
	 * @param string $string
	 * @return bool
	 */
	public function isUrlValid($string)
	{
		if (!preg_match("|^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$|i",$string)) return false;
		return true;
	}
	
	/**
	 * 验证手机号
	 * @param string $string
	 * @return bool
	 */
	public function isMobileValid($string)
	{
		//"/^(13[0-9]|15[0|3|6|7|8|9]|18[5|6|8|9])\d{8}$/"
		if (!preg_match("/^1\d{10}$/", $string)) return false;
		return true;
	}
	
	/**
	 * 验证ZIP
	 * @param string $string
	 * @return bool
	 */
	public function isZipValid($string)
	{
		if (!preg_match("/^[1-9]\d{5}$/", $string)) return false;
		return true;
	}
	
	/**
	 * 验证身份证
	 * @param string $string
	 * @return bool
	 */
	function isIDValid($string)
	{
		if (!preg_match("/^((\d{15})|(\d{17}([0-9]|X|x)))$/", $string)) return false;
		return true;
	}
}
