<?php
define("ROOT_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */
define("APP_PATH", ROOT_PATH . "/application");

$app  = new Yaf_Application(ROOT_PATH . "/conf/application.ini",'product');

try {
	$app->bootstrap()->run();
} catch (Yaf_Exception $e) {
	$ret = "";
	$code = $e->getCode();
	$msg = $e->getMessage();
	sysFun::getInstance()->display($ret, $code, $msg);
}

