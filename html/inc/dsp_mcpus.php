<?php
$Title = 'NNMdsp_cpus';
require_once dirname(__FILE__) . '/mysql.php';
global $recs;

function dsp_body( $rec, $count ){
  global $recs;
echo <<<EOT
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" id="count">{$count}</td>
    <td colspan="3">
      <input type="checkbox" id="cbox" value="{$recs[$count]['id']}"></td>
    <td colspan="3" id="id">{$rec['id']}</td>
    <td colspan="14" id="host">{$rec['host_name']}</td>
    <td colspan="9" id="mac" >{$rec['mac_address']}</td>
    <td colspan="9" id="ip" >{$rec['ip']}</td>
    <td colspan="9" id="bc" >{$rec['bc']}</td>
    <td colspan="14" id="User" >{$rec['LoginUser']}</td>
    <td colspan="28" id="status">&nbsp;</td>
    <td colspan="6">{$rec['stamp']}</td>
    <td>&nbsp;</td>
  </tr>
EOT;

  if ( ob_get_length() ){
    ob_flush();
    flush();
  }
}// dsp_body() END  ##########################

function dsp_cpu(){
  global $recs;

  $recs = get_cpu();
  $counter = 1;
  foreach( $recs as $rec ){
    dsp_body( $rec, $counter++ );
  }
}

function get_cpu(){
  $recs = array();

  $P = mysql::singleton();
  $gw = $_POST['gw'];
  if ( $gw == "ALL" )  $where = "";
  else  $where = "WHERE xGateway LIKE '{$gw}'";
  $sql = "SELECT * FROM cpus {$where} ORDER BY xHostName ASC ";

  $counter = 1;
  $rs = $P->query( "get_cpu()", $sql, "" );
  while( $x = $rs->fetch_object() ){
    $mask = $x->xIPv4Mask;
    $ipv4 = $x->xIPV4;
    $nwaddress = get_nwaddress($ipv4, $mask);
    $bcaddress = get_bcaddress($ipv4, $mask);
    // $recs[$counter++] = array("id" => $x->id, "host_name" => $x->xHostName, "mac_address" => $x->xMAC, "ip" => $x->xIPV4, "bc" => $x->xGateway, "LoginUser" => $x->xRegisteredUser);
    $recs[$counter++] = array("id" => $x->id, "host_name" => $x->xHostName, "mac_address" => $x->xMAC, "ip" => $x->xIPV4, "bc" => $bcaddress, "LoginUser" => $x->xRegisteredUser, "stamp" => $x->xUpdate);

  }
  $P->myclose();
  return $recs;
}

dsp_cpu();
?>
