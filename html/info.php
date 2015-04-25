<?php
///require_once dirname(__FILE__) . '/../php/PEAR/PEAR/Info.php';
require_once 'C:\ProgramData\NNM\php\PEAR\pear\PEAR\Info.php';
date_default_timezone_set('Asia/Tokyo');
phpinfo();
$info = new PEAR_Info();
$info->show();
?>
