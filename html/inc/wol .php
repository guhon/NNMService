<?php
class WakeOnLan {
  const BROADCAST_MAC_ADDR = 'FF:FF:FF:FF:FF:FF';
  const DEFAULT_BROADCAST_IP = '255.255.255.255';
  const DEFAULT_PORT = 2304;
  private static $instance = null;


  protected $_broadcastIp;

  protected $_port;

  public function __construct($broadcastIp = null, $port = null) {
    
    $this->setBroadcastIp($broadcastIp)->setPort($port);
///echo "broadcastIp={$broadcastIp}***port={$this->_port}---<br />";

  }

  //* singleton メソッド clone 抑止
  //* Singleton パターンは、クラスのインスタンスが一つだけであることが 必要である場合に適用。 
  //* この最も一般的な例は、データベースへの接続。 
  //* このパターンを実装することで、プログラマは この単一のインスタンスが他の多くのオブジェクトから容易に アクセスできるようにすることができる。 
  public static function singleton($ip=null, $port=null)
  {
    if (!isset(self::$instance)) {
      self::$instance = new WakeOnLan($ip, $port);
    }
    return self::$instance;
  }

  //* ユーザーがインターフェースを複製するのを防ぐ
  public function __clone()
  {
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

  public static function macAddrToBytes($mac) {
    $mac = (string)$mac;
    if(! self::isValidMacAddr($mac)) {
      throw new Exception('invalid MAC address');
    }

    $buf = array();
    foreach(preg_split('/[:\-]/', $mac) as $one_octet) {
    ///*echo "<strong>{$one_octet}:</strong>";
      $buf[] = chr(intval($one_octet, 16));
    }
    ///*echo "<br />";
    return join('', $buf);
  }

  public static function isValidMacAddr($mac) {
    return preg_match('/^[\da-zA-Z]{2}([:\-][\da-zA-Z]{2}){5}$/', $mac);
  }

  public function getBroadcastIp() {
    return $this->_broadcastIp;
  }
  public function setBroadcastIp($ip) {
    $ip = (string)$ip;
    if(empty($ip)) $ip = $this->DEFAULT_BROADCAST_IP;
    $this->_broadcastIp = $ip;
    return $this;
  }
  public function getBroadcastUrl() {
    return 'udp://' . $this->getBroadcastIp();
  }

  public function getPort() {
    return $this->_port;
  }
  public function setPort($port) {
    if($port == null) $port = -1;
    $port = (int)$port;
    if($port < 0) $port = self::DEFAULT_PORT;
    $this->_port = $port;
    return $this;
  }

  //* デミリッターのないマックアドレスにコロンを追加した結果を返す
  public function macAddrAddColon($mac) {
    $ret = sprintf("%s:%s:%s:%s:%s:%s", mb_substr($mac,0,2),mb_substr($mac,2,2),mb_substr($mac,4,2),mb_substr($mac,6,2),mb_substr($mac,8,2),mb_substr($mac,10,2));
    return $ret;
  }

  public function sendTo($mac) {
    
    $magicPacket = "";	//* Magic Packet 192bytes 格納バッファ
    //* Magic Packet（6bytes）文字列作成
    $magicPacket = self::macAddrToBytes(self::BROADCAST_MAC_ADDR);

    //* マックアドレスにコロン追加
    $mac = self::macAddrAddColon($mac);
    //* ネットワーク・アダプタのMACアドレス（6bytes）文字列作成
    $macstr = self::macAddrToBytes($mac);

    //* 16回繰り返した、計192bytesのデータを持つUDPデータグラム作成
    for($i = 0; $i < 16; $i++){
        $magicPacket .= $macstr;
    }
    $bcurl = $this->getBroadcastUrl();
///echo "getBroadcastUrl()=" . $bcurl . "<br>";
    $fso = fsockopen($bcurl, 2304, $errno, $errstr);
    if (!$fso) {
        print("ERROR: $errno - $errstr\n");
    } else {
        fwrite($fso, $magicPacket);
        fwrite($fso, $magicPacket);
        fclose($fso);
    }
  }
}
$wol = WakeOnLan::singleton($_POST['bc'], 2304);
$wol->sendTo($_POST['mac']);
echo "<td colspan='24' id='status'>電源ＯＮパケット送信{$_POST['mac']}{$_POST['bc']}</td>";
///echo "<td colspan='24' id='status'>電源ＯＮto {$_POST['mac']}:{$_POST['bc']}</td>";
?>
