<?php
$Title = 'dataDelete';
require_once dirname(__FILE__) . '/mysql.php';

$P = mysql::singleton();
$sql = sprintf("DELETE FROM `cpus` WHERE `id`=%s", $_POST['id'] );
$body = $P->query( "from dataDelete.php", $sql );
$Pp->myclose();
echo "<td colspan='24' id='status'>{$body}</td>";
?>
