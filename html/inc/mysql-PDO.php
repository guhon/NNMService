﻿<?php
require_once('common.def');
require_once('common.php');
class mysql extends c_common
{
  private static  $debug = 0;
  //* クラスのインスタンスを保持する
  private static $instance = null;
  public $con = null, $pdo = null;

  //* singleton メソッド clone 抑止
  //* Singleton パターンは、クラスのインスタンスが一つだけであることが 必要である場合に適用。 
  //* この最も一般的な例は、データベースへの接続。 
  //* このパターンを実装することで、プログラマは この単一のインスタンスが他の多くのオブジェクトから容易に アクセスできるようにすることができる。 
  public static function singleton()
  {
    if (!isset(self::$instance)) {
      self::$instance = new mysql();
    }
    return self::$instance;
  }

  //* ユーザーがインターフェースを複製するのを防ぐ
  public function __clone()
  {
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

  public function myclose(){
    if ( isset($GLOBALS['con']) ) {
      mysqli_close( $GLOBALS['con'] );
      unset($GLOBALS['con']);
      $this->con = "";
    }
    if ( !empty(self::$con) ){
      self::$con = null;
    }
  }

  public function send_err( $subject, $body, $from="ssc@fukuda.ac.jp", $files="", $to="m.yorikazu@nifty.com" ){
    exit();
    $C = c_common::singleton();
    $C->pf_Mail( $subject, $body, $from, $files, $to, $host);  //* エラーメール発信
    $infoerr = 'infoerr.php';
    $C->locat_assign($infoerr);                                //* 案内メッセージへ
    exit();
  }

  public function myconnect( $msg ){
    if ( empty($this->pdo) ){
      $GLOBALS['errmsg'] = $msg;
      $ip = DB_IP;
      $port = DB_PORT;
      $sock = DB_SOCK;
      $db = DB_NAME;
      $user = DB_USER;
      $pw = DB_PW;
      $key = DB_KEY;
      $cert = DB_CERT;
      $ca = DB_CA;
      $capath = DB_CAPATH;

      $dsn = "mysql:host={$ip};port={$port};dbname={$db}";
      $op = array(
        ///PDO::MYSQL_ATTR_SSL_KEY =>'{$key}',
        ///PDO::MYSQL_ATTR_SSL_CERT=>'{$cert}',
        PDO::MYSQL_ATTR_SSL_CA =>$ca );

      try{
        $this->pdo = new PDO( $dsn, $user, $pw, $op );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e) {
        $GLOBALS['pdo'] = "";
        unset( $GLOBALS['pdo'] );
        $body = sprintf( "%s ＳＳＬコネクション失敗\n%s", $msg, $e->getMessage() );
        print "Error!: " . $body . "<br/>";
        die();
        exit();
      }
      $GLOBALS['pdo'] = $this->pdo;
      return $this->pdo;
    }
    else{
      $this->pdo = $GLOBALS['pdo'];
      return $this->pdo;
    }
/**************************
    if ( empty($this->con) ){
      $GLOBALS['errmsg'] = $msg;
      $ip = DB_IP;
      $port = DB_PORT;
      $sock = DB_SOCK;
      $db = DB_NAME;
      $user = DB_USER;
      $pw = DB_PW;
      $key = DB_KEY;
      $cert = DB_CERT;
      $ca = DB_CA;
      $capath = DB_CAPATH;

      ini_set ('error_reporting', E_ALL);
      ini_set ('display_errors', '1');
      error_reporting (E_ALL|E_STRICT);

      $this->con = mysqli_init();
      if (!$this->con) {
        $GLOBALS['con'] = "";
        unset( $GLOBALS['con'] );
        $body = sprintf( "%s ｓｓｌコネクション失敗\n", $msg);
        $this->send_err( "ssl_myconnect({$msg})", $body );  //* エラーメール発信
        exit();
      }

      /// @ mysqli_ssl_set( $this->con, $key, $cert, $ca, apath, NULL );
      @ mysqli_ssl_set( $this->con, $key, $cert, $ca, NULL, NULL );
      /// @ mysqli_ssl_set( $this->con, NULL, $cert, NULL, NULL, NULL );
      /// @ mysqli_ssl_set( $this->con, $key, NULL, NULL, NULL, NULL );

      $ret = @ $this->con->real_connect( $ip, $user, $pw, $db, $port, NULL, MYSQLI_CLIENT_SSL );
      if (!$ret) {
        $GLOBALS['con'] = "";
        unset( $GLOBALS['con'] );
        $body = sprintf( "%s ＳＳＬコネクション失敗\n%s", $msg, mysqli_connect_errno());
        $this->send_err( "ssl_myconnect({$msg})", $body );  //* エラーメール発信
        exit();
      }

      $GLOBALS['con'] = $this->con;
      return $this->con;
    }
    else{
      $this->con = $GLOBALS['con'];
      return $this->con;
    }
*****************************/
  }

/*****************************************************************
  public function myconnect( $msg ){
///    if ( empty($this->con) ){
    if ( empty($GLOBALS['con']) ){
      $GLOBALS['errmsg'] = $msg;
      $ip = DB_IP;
      $db = DB_NAME;
      $user = DB_USER;
      $pw = DB_PW;
      $DB_KEY = DB_KEY;
      
      $this->con = mysqli_connect( $ip, $user, $pw, $db );
//ym20150324      $this->con = new mysqli( $ip, $user, $pw, $db );
      if ($this->con->connect_error) {
        ///printf( "Connect failed:(%s)%s<br>", $this->con->connect_errno, $this->con->connect_error );
        $GLOBALS['con'] = "";
        unset( $GLOBALS['con'] );
        $body = sprintf( "%s Connection status bad(%s)%s\n", $msg,  $this->con->connect_errno, $this->con->connect_error );
        $this->send_err( $msg.":myconnect", $body );  //* エラーメール発信
        exit();
      }
      //else {
      //  $this->debug( "Connection status ok\n" );
      //}
      $GLOBALS['con'] = $this->con;
      return $this->con;
    }
    else{
      $this->con = $GLOBALS['con'];
      return $this->con;
    }
  }
*****************************************************************/

  public function select_count($msg, $table, $where){
    $sql = "SELECT count(*) FROM {$table} {$where}";
    $pdo = $this->myconnect($msg);
    try{
      $pdo->prepare($sql);
      $con->execute();
      $row = $pdo->fetch(PDO::FETCH_NUM);

/********************************************************************************
    $con = $this->myconnect($msg);
    $rs = mysqli_query( $con, $sql );
    ///$rs = $this->query($msg, $sql, $url="");
    $row = $rs->fetch_row();
********************************************************************************/
      return $row[0];
    }
    catch (PDOException $e) {
        $body = sprintf( "%s 失敗\n%s", $msg, $e->getMessage());
        print "Error!: " . $body . "<br/>";
        die();
        exit();
    }
  }

  public function seikei($sss){
    $sss = addslashes( $sss );               //* for MySQL
    $sss = ereg_replace( "\t|\n","", $sss );
    ///$sss = str_replace( "'","''", $sss ); //* need postgreSQL7
    ///$sss = str_replace( "\r","", $sss );  //* need postgreSQL7
    return $sss;
  }

  public function query($msg, $sql, $url=""){
    ///echo "query({$msg})=" . $sql . "<br>";

    $pdo = $this->myconnect($msg);
    try{
      $pdo->prepare($sql);
      $pdo->execute();
      $obj = $pdo->fetch(PDO::FETCH_OBJ);
      return $obj;
    }
    catch (PDOException $e) {
        $body = sprintf( "%s 失敗\n%s", $msg, $e->getMessage() );
        print "Error!: " . $body . "<br/>";
        die();
        exit();
    }

/******************************************************************************
    $con = $this->myconnect($msg);
    $rs = mysqli_query( $con, $sql );
    ///$rs = $con->query( $sql );
    if (!$rs){
      $body = sprintf("%s:::%s(%s)\n",$msg, mysqli_error(), $sql );
      $this->send_err( "{$msg}:query()", $body );  //* エラーメール発信
      exit;
    }
    return $rs;
************************************************************************/
  }

  function sql_insert($msg, $sql, $url=""){
    $pdo = $this->myconnect($msg);
    try{
      $rs = $pdo->query($sql);
      return $pdo->lastInsertId;
    }
    catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
/**********************************************************************************
    $con = $this->myconnect($msg);
    $rs = mysqli_query( $con, $sql );
    ///$rs = $con->query( $sql );
    if (!$rs){
      $body = sprintf("%s:::%s(%s)\n",$msg, mysql_error(), $sql );
      $this->send_err( $msg.": sql_insert()", $body );  //* エラーメール発信
      exit;
    }
    return $con->insert_id;
***************************************************************************************/
  }

  function debug( $str ){
    if ( self::$debug == 1 ){
      if ( is_array( $str ) )  print_r( $str );
      else                     echo $str;
      flush();ob_flush();
      sleep( 1 );
    }
  }

  function destroy() {  //* 無理矢理デストラクタ
    unset($this);
  }

  public function setpw($msg=""){

/******************************************************************************************************************
    $con = $this->myconnect("setpw()");
    $m5pw = md5($_POST['pw']);
    $sql = sprintf( "UPDATE USERS SET `password`='%s' WHERE `uid`='%s' ", $con->real_escape_string($m5pw), $_POST['uid'] );
    $ret = $this->query("setpw()", $sql);
    $this->myclose();
    return $ret;
***********************************************************************************************************/
  }

  public function chk_id($table="USERS"){
    //* echo "ＳＱＬインジェクション対策プリペアードステートメント<br>";
    $ret=-1;
    $m5pw="";
    if ( !empty($_POST['uid']) && empty($_POST['pw']) ){
      return 0;
    }
    if ( isset($_POST['uid']) && isset($_POST['pw']) ) {
      $con = $this->myconnect("chk_id()");
    }
    else{
      return -1;
    }
    if ( empty($_POST['pw']) ){
      $m5pw = "";
    }
    else{
      $m5pw = md5($_POST['pw']);
    }
    $sql = sprintf( "SELECT * FROM {$table} WHERE uid = '%s' AND password = '%s'",
                    $_POST['uid'], $con->real_escape_string($m5pw) );

    $x = $this->query("chk_id()", $sql);
    $y = $x->num_rows( $x );
    if ( $y < 1 )  return -1;

    $z = $x->fetch_object();

    $_SESSION['ss_id'] = $z->id;
    $_SESSION['ss_uid'] = $z->uid;
    $_SESSION['ss_pw'] = $z->password;
    $_SESSION['ss_sei'] = $z->first_name;
    $_SESSION['ss_mei'] = $z->last_name;
    $_SESSION['ss_shime'] = "{$z->first_name} {$z->last_name}";
    $_SESSION['ss_tel'] = $z->tel;
    $_SESSION['ss_e-mail'] = $z->email;
    $_SESSION['ss_user_ip'] = $_SERVER["REMOTE_ADDR"];
    $_SESSION['ss_login_date'] = date('Y/m/d H:i:s');

    if ( empty($_POST['pw']) ){
      $ret = 0;
    }
    else{
      $ret = 1;
    }

    return $ret;

  }
}
