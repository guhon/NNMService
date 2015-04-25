<?php
require_once 'C:\ProgramData\NNM\php\PEAR\pear/PEAR.php';
require_once 'C:\ProgramData\NNM\php\PEAR\pear/Mail.php';
require_once 'common.def';
require_once 'C:\ProgramData\NNM\php\PEAR\pear/Mail/mimePart.php';
require_once 'C:\ProgramData\NNM\php\PEAR\pear/Mail/mime.php';

class c_common
{
  //* クラスのインスタンスを保持する
  private static $instance = null;

  //* singleton メソッド clone 抑止
  //* Singleton パターンは、クラスのインスタンスが一つだけであることが 必要である場合に適用。 
  //* この最も一般的な例は、データベースへの接続。 
  //* このパターンを実装することで、プログラマは この単一のインスタンスが他の多くのオブジェクトから容易に アクセスできるようにすることができる。 
  public static function singleton()
  {
    if (!isset(self::$instance)) {
      ///$c = __CLASS__;
      self::$instance = new c_common();
    }
    return self::$instance;
  }

  //* ユーザーがインターフェースを複製するのを防ぐ
  public function __clone()
  {
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

  public function logw($buf){
    date_default_timezone_set('Asia/Tokyo');
    $time_buf = date("Y.m.d H:i:s");
    $buff = $time_buf . "\t" . $buf . "\n";
    //*  受け取った値をファイル(log.csv)に出力する
    $file = fopen("../../logs/log.csv","a+");
    fwrite($file,$buff);
    fclose($file);
  }

  public function mailog($buf){
    date_default_timezone_set('Asia/Tokyo');
    $time_buf = date("Y.m.d H:i:s");
    $buff = $time_buf . "\t" . $buf . "\n";
    //*  受け取った値をファイル(maillog.csv)に出力する
    $file = fopen("../../logs/maillog.csv","a+");
    fwrite($file,$buff);
    fclose($file);
  }

  public function chk_https(){
    if (( $_SERVER["SERVER_PORT"] == "443" ) && ( $_SERVER["HTTPS"] == "on" )){
      return "on";
    }
    else{
      return "off";
    }
  }

  public function MakeUrl( $a ){    //絶対パス版
    $h="https";
    $strurl="{$h}://" . $_SERVER['HTTP_HOST'] . "/" . $a;
    return $strurl;
  }

  public function locat_assign($str){
    $strurl=$this->MakeUrl($str);
    if ( !headers_sent() ){
      header( "Location:".$strurl );
    }
    else{
echo <<<EOT
<script type="text/javascript">
window.location.href="{$strurl};"
</script>
<noscript>
<meta http-equiv="refresh" content="0;url={$strurl}" />
</noscript>
EOT;
    }
  }

  public function files_arrange($files){
    $rspace = str_replace(' ', '', $files);
    $rcr = str_replace('\n ', '', $rspace);

  }

  public function pf_mail( $subject, $msg, $from, $files="", $to="ssc@fukuda.ac.jp", $host="nnms" ){
    date_default_timezone_set('Asia/Tokyo');
    mb_internal_encoding("UTF-8");

    $MessageId = "<message_id=" . date("YmdHis") . $this->create_MessageId() . ">";
    $body = mb_convert_encoding( $msg.$MessageId, "ISO-2022-JP" );

    //echo "メール送信 id:{$_SESSION['id']} 氏名:{$_SESSION['last_name']}{$_SESSION['first_name']} files:{$files}<br>";
    //echo "<br>bcc:{$to}<br>subject:{$subject}<br>msg:{$msg}<br>{$MessageId}<br>";

    switch ($host){
      case 'nnms':
        //* SMTPサーバ
        $mail_options = array(
          'host'      => "localhost", //* ホスト名
          'username'  => "ssc",       //* アカウント名
          'password'  => "29D@",      //* パスワード
          'port'      => 587,         //* ポート番号
          'auth'      => true,        //* 認証必要？
          'localhost' => "localhost"  //* EHLO あるいは HELO を送信する際に使用する
         );
        break;
      case 'fukuda':
        $mail_options = array(
          'host'      => "smtp.gmail.com",      //* ホスト名
          'username'  => "admin@fukuda.ac.jp",  //* アカウント名
          'password'  => "Fkd4admn",            //* パスワード
          'port'      => 587,                   //* ポート番号
          'smtpauth'  => true,                  //* 認証必要？
          'smtpsecure'=> "tls",
          'localhost' => "localhost"            //* EHLO あるいは HELO を送信する際に使用する
         );
        break;
    }
    //print_r($mail_options);


    //*ym081031 'To'  => $to
    $mail_header = array(
      'To'  => $to,
      'From'  => $from
    );

    $mime_header = array(
      'text_charset'      => 'ISO-2022-JP',
      'Message-Id'      => $MessageId,
    );
    //* Create the mail object using the Mail::factory method
    $mail =& Mail::factory("SMTP",$mail_options);  //* SMTP送信準備

    if ( PEAR::isError( $mail ) ) {
      echo "SMTP送信準備失敗<br>";
      echo $mail_object->getMessage(  ) . "<br>";
      $this->mailog( $mail->getMessage()."SMTP送信準備失敗【{$to}】{$subject} id:{$_SESSION['id']} 氏名:{$_SESSION['last_name']}{$_SESSION['first_name']}" );
      return "SMTPNG";
    }
    else {
      //echo "SMTP送信準備完了<br>";
    }
    $crlf = "\n";
    $mime = new Mail_mime( $crlf );
    $mime->setSubject(mb_encode_mimeheader($subject, 'ISO-2022-JP') );
    $mime->setFrom(mb_encode_mimeheader('ＯＣＲ校友会') . '<koyukai@ocr.ac.jp>');
    $mime->setTxtBody( $body );

    //* 添付ファイル処理↓
    if ( $files != "" ){
      $filein = preg_replace("/[\r\n]/", "", $files); //改行コード削除
      //echo "<pre>?改行コード削除++" . $filein. "</pre><br />";

      $filein = mb_ereg_replace("^[,、]+|[,、]$",'',$filein);    //* 前後のコンマ削除
      $filein = mb_ereg_replace("[\s:：；;,]", ":", $filein);    //* 半角全角の空白、コロンとセミコロンを半角コロンに置換
      //echo "?++".$filein."<br />";

      $arrfile = preg_split("/:/", $filein);  //* 配列へ格納
      //print_r($arrfile);
      //echo "<br />";

      foreach ( $arrfile as &$path_file ){
        $arr_name = preg_split("/\//", $path_file);
        ///$arr_name = basename($path_file);
        $arr_name = array_reverse($arr_name);

        //echo "arr_name={$arr_name[0]}<br />";
        $f_name = $arr_name[0];
        //echo "<pre>f_name={$f_name}</pre><br />";

        $ret = $mime->addAttachment($path_file, "text/plain");
        if ( !$ret ) echo "FALSE<br />";
        //$mime_header['Content-Disposition'] = 'attachment';
        //$mime_header['filename*'] = "iso-2022-jp'ja'" . urlencode($f_name);
      }
      //print_r($arrfile);
      //echo "<br />";
    }
    //* 添付ファイル処理↑

    $body = $mime->get( $mime_header );
    $headers = $mime->headers( $mail_header );

//  $recipients = "raiwa@softbank.ne.jp,ob62399@cb3.so-net.ne.jp";
    $recipients = $to;  //* 実際のメール送付先設定
    $ret = $mail->send($recipients, $headers, $body);
    if ( PEAR::isError( $ret ) ) {
      echo "メール送信失敗しました。<br>";
      echo $ret->getMessage( );
      $this->mailog( $ret->getMessage()."メール送信失敗【{$to}】{$subject} id:{$_SESSION['id']} 氏名:{$_SESSION['last_name']}{$_SESSION['first_name']}" );
      return "SENDNG";
    }
    else{
      //echo "メール送信完了【{$to}】{$subject} id:{$_SESSION['id']} 氏名:{$_SESSION['last_name']}{$_SESSION['first_name']}<br>";
      $this->mailog( "メール送信完了【{$to}】{$subject} id:{$_SESSION['id']} 氏名:{$_SESSION['last_name']}{$_SESSION['first_name']}" );
      return "OK";
    }
  }

  public function session_cls(){  //* セッションの初期化

    $_SESSION = array();

    // セッションを切断するにはセッションクッキーも削除する。
    // Note: セッション情報だけでなくセッションを破壊する。
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time()-42000, '/');
    }
    // 最終的に、セッションを破壊する
    session_destroy();
  }

  public function oy( $a ){
    if ( is_file( $a )==false )  $a = "../" . $a;
    return $a;
  }

}  //***** END of class common *****


//********
function w($str){
//********
  echo $str;
}

//********
function ww($str){
//********
  echo $str . "<br>";
}


function chk_post_sbf(){
    if ( is_null($_POST['msubject']) and is_null($_POST['mbody']) and is_null($_POST['mfiles']) ){
      return 0;
    }
    if ( $_POST['msubject']=='' and $_POST['mbody']=='' and $_POST['mfiles']=='' ){
      return 0;
    }
}
//* 戻り値	ネットワークアドレス xxx.xxx.xxx.xxx
//* 文字列 $ipv4 IP アドレス xxx.xxx.xxx.xxx
//* 文字列 $mask 255.255.255.0
function get_nwaddress( $ipv4, $mask ){

    $lmask = sprintf("%b", ip2long($mask));
    $lipv4 = decbin(ip2long($ipv4));
    $bnw = $lipv4 & $lmask;
    $dnw = bindec( $bnw );
    $nwstr = long2ip(-(4294967296-$dnw));
    return $nwstr;
}
//* 戻り値	ブロードキャストアドレス xxx.xxx.xxx.255
//* 文字列 $ipv4 IP アドレス xxx.xxx.xxx.xxx
//* 文字列 $mask 255.255.255.0
function get_bcaddress( $ipv4, $mask ){
    $lmask = sprintf("%b", ip2long($mask));
    $cmask = substr_count($lmask,"1");
    $lipv4 = decbin(ip2long($ipv4));
    $bnw = $lipv4 & $lmask;
    $strmsk = str_repeat("1", 32 - $cmask);
    $bbcast = substr_replace($bnw, $strmsk, $cmask);
    $dbcast = bindec($bbcast);
    $bcstr = long2ip(-(4294967296-$dbcast));
    return $bcstr;
}
?>
