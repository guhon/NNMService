<?php
/*************
socket.php 
*************/
require_once dirname(__FILE__) . '/mysql.php';

global  $Tcp5350, $Tcp5351, $Sock;

$Send5350 = 5350;    //* Send TCPport
$Listen5351 = 5351;  //* Listen TCPport

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush(TRUE);

// 配列から要素を取り除く関数
function remove_elem(&$arr, $elem) {
  foreach ($arr as $k => $v) {
    if ($v == $elem) {
      unset($arr[$k]);
    }
  }
}

function get_sock_err( $socket ) {
  $errorcode = socket_last_error($socket);
  socket_clear_error();
  ///$errormsg = socket_strerror($errorcode);
  $ret = sprintf("コード（%d）", $errorcode);
  ///$retmsg = mb_convert_encoding($ret, "UTF-8", "auto");
  return $ret;
}

function goSend( $host, $data, $args="" ){
  global  $Send5350;
  socket_clear_error();
  $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  if ( !$sock ) {
    $errorcode = socket_last_error($socket);
    ///$errormsg = socket_strerror($errorcode);
    socket_clear_error();
    $ret = sprintf("ソケット生成エラー：コード（%d）", $errorcode);
    return $err;
  }
  if (!socket_connect($sock, $host, 5350)) {
    $err = "ソケット接続エラー：" . get_sock_err($sock);
    ///$err = get_sock_err($sock);
    socket_close($sock);
    return $err;
  }

  //* $retsend = 送信したバイト数あるいはエラー時に FALSE を返す
  $retmsg = socket_write($sock, $data, strlen($data));
  if ( $retmsg === FALSE ){
    $retmsg = "ソケット送信エラー：" . get_sock_err($sock);
  }
  socket_close($sock);
  return $retmsg;
}

function doSend($host, $data){
  $ret = goSend( $host, $data, $args="" );
  //echo $sock."{$data}//* ret = 送信したバイト数あるいはエラー文字列={$ret}--<br />";
  ///$str = mb_convert_encoding($ret, "UTF-8", "auto");
  ///echo "*******" . $str . "++++++++<br>";
  return $ret;
}

///$_POST['ip'] = "192.168.54.11";
///$_POST['order'] = "Execute";

if ( $_POST['order'] == "Ping" ){
  // cpus.status クリア
  $P = mysql::singleton();
  $sql = sprintf("UPDATE `cpus` SET `status` = '' WHERE `xIPV4` LIKE '%s'", $_POST['ip'] );
  $P->query( "from socket.php for order Ping SET STATUS ''", $sql );
  usleep(500000);  //* 0.5秒遅延
  $ret = doSend( $_POST['ip'] , $_POST['order'] );
  usleep(1500000);  //* 1.5秒遅延
  $sql = sprintf( "SELECT `status` FROM `cpus` WHERE  `xIPV4` LIKE '%s'", $_POST['ip'] );
  $rs = $P->query( "from socket.php for order Ping GET STATUS", $sql );
  $X = $rs->fetch_object();
  $strRet = sprintf("<td colspan='24' id='status'>%s</td>", $X->status);
  $P->myclose();
  echo $strRet;
}
else{
  $ret = doSend( $_POST['ip'] , $_POST['order'] );
  echo "<td colspan='24' id='status'>{$_POST['order']}※{$ret}</td>";
}
