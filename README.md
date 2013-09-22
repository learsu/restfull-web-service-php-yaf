restfull-web-service-php-yaf
=======

restfull-web-service-php-yaf

CREATE TABLE IF NOT EXISTS `test` (  
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `name` varchar(20) NOT NULL,  
  `pwd` varchar(32) NOT NULL,  
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;  

INSERT INTO `test` (`id`, `name`, `pwd`) VALUES  
(1, 'python-test', '123456'),  
(2, 'python-test', '123456'),  
(3, 'python-test', '123456');  

存在问题，我把Yaf_Exception和程序错误全部放一起了，重新定义了错误码，只需要try catch bootstrap()->run()就能捕捉到错误信息和错误码。这会捕捉框架的错误，建议大家自定义exception不捕捉框架级别错误，除非你有把握0 warning.

说好了，拍砖随意但不能拍脸。
