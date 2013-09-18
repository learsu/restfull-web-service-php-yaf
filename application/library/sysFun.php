<?php

class sysFun
{
	//唯一实例
	private static $_instance;
	
	//获取唯一实例 @author learsu
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 信息整合输出
	 * @param string $ret
	 * @param number $code
	 * @param string $msg
	 * @param number $flag
	 */
	public function display($ret, $code = 0, $msg = "", $flag = 1)
	{
		if ($flag == 0) {
			echo json_encode(array("code" => $code, "msg" => $msg, "ret" => $ret));
		} else {
			echo "<pre>";
			print_r(array("code" => $code, "msg" => $msg, "ret" => $ret));
			echo "</pre>";
		}
	}
	
	/**
	 * 调试输出数组
	 * @param unknown $source
	 */
	public function pr($source)
	{
		echo "<pre>";
		print_r($source);
		echo "</pre>";
	}
}