<?php
$Title = 'NNMSystem';
require_once("XML/RPC.php");  
require_once dirname(__FILE__) . '/inc/mysql.php';

function dsp_header($title, $action){
echo <<<EOT
<table id="tbl_{$action}" border="0" cellspacing="0" cellpadding="0" style="width:1400px;text-align:left;font-size:10.5pt">
  <tr style="line-height:5em">
    <td>&nbsp;</td>
    <td colspan="35" id="title" style="font-size:18pt;font-weight:bold;color:maroon;text-align:center">{$title}</td>
EOT;

switch ($action) {
  case "StartUp":
  case "Execute":
echo <<<EOT
    <td colspan="28" style="text-align:right" title="ファイルを選択してください！">ファイル&nbsp;Z:&#165;SSC&#165;
      <input type="text" id="{$action}_ap" name="{$action}_ap" value="" title="ファイル名を入力してください！" />
    </td>
EOT;
    break;
  default:
    echo "<td colspan='28' style='text-align:right'>&nbsp;&nbsp;</td>";
    break;
}

echo <<<EOT
    <td colspan="35" style="text-align:right">
      <input type="button" id="{$action}_clr" value="全て解除" />
      <input type="button" id="{$action}_sel" value="全て選択" />
      <input type="button" id="{$action}_can" value="実行" />
      <input type="reset" id="{$action}_rst" value="リセット" />
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="98" style="font-size:14pt;font-weight:bold;color:maroon;text-align:center">Ｇａｔｅｗａｙフィルター
      <select id="gateway" name="gateway" size="1">
EOT;
  $sql = "SELECT * FROM cpus GROUP BY xGateway";
  $P = mysql::singleton();
  $rs = $P->query( "index.php", $sql );
  $cc = 0;
  while( $x = $rs->fetch_object() ){
    $cc++;
    if ( $cc == 1 ){
      echo "<option value='{$x->xGateway}' selected>{$x->xGateway}</option>";
    }
    else{
      echo "<option value='{$x->xGateway}'>{$x->xGateway}</option>";
    }
  }
  $P->myclose();
echo <<<EOT
        <option value='ALL'>ALL</option>"
      </select>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr style="line-height:0.5em"><td colspan="100" >&nbsp;</td></tr>
  <tr id="tr_{$action}">
    <td>&nbsp;</td>
    <td colspan="3">count</td>
    <td colspan="3">check</td>
    <td colspan="3">id</td>
    <td colspan="14">host_name</td>
    <td colspan="9">mac_address</td>
    <td colspan="9">ip_address</td>
    <td colspan="9">gateway_address</td>
    <td colspan="14">LoginUser</td>
    <td colspan="28">status</td>
    <td colspan="6">TimeStamp</td>
    <td>&nbsp;</td>
  </tr>
EOT;
}// dsp_header() END  ##########################

function dsp_trail($title, $action){
echo <<<EOT
  <tr style="line-height:0.5em"><td colspan="100" >&nbsp;</td></tr>
  <tr style="line-height:5em">
    <td>&nbsp;</td>
    <td colspan="35" id="title" style="font-size:18pt;font-weight:bold;color:maroon;text-align:center">{$title}</td>
    <td colspan="28" style="text-align:right">&nbsp;</td>
    <td colspan="35" style="text-align:right">
      <input type="button" id="{$action}_clr" value="全て解除" />
      <input type="button" id="{$action}_sel" value="全て選択" />
      <input type="button" id="{$action}_can" value="実行" />
      <input type="reset" id="{$action}_rst" value="リセット" />
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
EOT;
}// dsp_trail() END  ##########################

require_once dirname(__FILE__) . '/inc/header.php';

echo <<<EOT
<script src="jquery/jquery-1.11.2.min.js"></script>
<link href="inc/index.css" media="screen" rel="stylesheet" type="text/css" />
<script>
$(function(){
  $("ul.tab li:first a").addClass("selected");
  $("ul.panel li:not(:first)").hide();
  $("ul.tab a").click(function(){
    if(!$(this).hasClass("selected")){
      $("ul.tab a.selected").removeClass("selected");
      $(this).addClass("selected");
      $("ul.panel li").hide().filter($(this).attr("href")).show();
    }
    return false;
  })
  $("#pwon_clr").click(function(){
    $("#tbl_pwon").find("input").removeAttr('checked');
  });
  $("#pwon_sel").click(function(){
    $("#tbl_pwon").find("input").attr("checked", "checked");
  });
  $("#pwon_rst").click(function(){
    location.reload();
  });
  $("#shutdown_clr").click(function(){
    $("#tbl_shutdown").find("input").removeAttr('checked');
  });
  $("#shutdown_sel").click(function(){
    $("#tbl_shutdown").find("input").attr("checked", "checked");
  });
  $("#shutdown_rst").click(function(){
    location.reload();
  });
  $("#reboot_clr").click(function(){
    $("#tbl_reboot").find("input").removeAttr('checked');
  });
  $("#reboot_sel").click(function(){
    $("#tbl_reboot").find("input").attr("checked", "checked");
  });
  $("#reboot_rst").click(function(){
    location.reload();
  });
  $("#ToolwizFreezeOFF_clr").click(function(){
    $("#tbl_ToolwizFreezeOFF").find("input").removeAttr('checked');
  });
  $("#ToolwizFreezeOFF_sel").click(function(){
    $("#tbl_ToolwizFreezeOFF").find("input").attr("checked", "checked");
  });
  $("#ToolwizFreezeOFF_rst").click(function(){
    location.reload();
  });
  $("#ToolwizFreezeON_clr").click(function(){
    $("#tbl_ToolwizFreezeON").find("input").removeAttr('checked');
  });
  $("#ToolwizFreezeON_sel").click(function(){
    $("#tbl_ToolwizFreezeON").find("input").attr("checked", "checked");
  });
  $("#ToolwizFreezeON_rst").click(function(){
    location.reload();
  });
  $("#update_windows_clr").click(function(){
    $("#tbl_update_windows").find("input").removeAttr('checked');
  });
  $("#update_windows_sel").click(function(){
    $("#tbl_update_windows").find("input").attr("checked", "checked");
  });
  $("#update_windows_rst").click(function(){
    location.reload();
  });
  $("#updateStop_windows_clr").click(function(){
    $("#tbl_updateStop_windows").find("input").removeAttr('checked');
  });
  $("#updateStop_windows_sel").click(function(){
    $("#tbl_updateStop_windows").find("input").attr("checked", "checked");
  });
  $("#updateStop_windows_rst").click(function(){
    location.reload();
  });
  $("#dbreg_clr").click(function(){
    $("#tbl_dbreg").find("input").removeAttr('checked');
  });
  $("#dbreg_sel").click(function(){
    $("#tbl_dbreg").find("input").attr("checked", "checked");
  });
  $("#dbreg_rst").click(function(){
    location.reload();
  });
  $("#CCleanerCLRAll_clr").click(function(){
    $("#tbl_CCleanerCLRAll").find("input").removeAttr('checked');
  });
  $("#CCleanerCLRAll_sel").click(function(){
    $("#tbl_CCleanerCLRAll").find("input").attr("checked", "checked");
  });
  $("#CCleanerCLRAll_rst").click(function(){
    location.reload();
  });
  $("#avgUpdate_clr").click(function(){
    $("#tbl_avgUpdate").find("input").removeAttr('checked');
  });
  $("#avgUpdate_sel").click(function(){
    $("#tbl_avgUpdate").find("input").attr("checked", "checked");
  });
  $("#avgUpdate_rst").click(function(){
    location.reload();
  });
  $("#Ping_clr").click(function(){
    $("#tbl_Ping").find("input").removeAttr('checked');
  });
  $("#Ping_sel").click(function(){
    $("#tbl_Ping").find("input").attr("checked", "checked");
  });
  $("#Ping_rst").click(function(){
    location.reload();
  });
  $("#Defrag_clr").click(function(){
    $("#tbl_Defrag").find("input").removeAttr('checked');
  });
  $("#Defrag_sel").click(function(){
    $("#tbl_Defrag").find("input").attr("checked", "checked");
  });
  $("#Defrag_rst").click(function(){
    location.reload();
  });
  $("#fullAuto_clr").click(function(){
    $("#tbl_fullAuto").find("input").removeAttr('checked');
  });
  $("#fullAuto_sel").click(function(){
    $("#tbl_fullAuto").find("input").attr("checked", "checked");
  });
  $("#fullAuto_rst").click(function(){
    location.reload();
  });
  $("#Execute_clr").click(function(){
    $("#tbl_Execute").find("input").removeAttr('checked');
  });
  $("#Execute_sel").click(function(){
    $("#tbl_Execute").find("input").attr("checked", "checked");
  });
  $("#Execute_rst").click(function(){
    location.reload();
  });
  $("#StartUp_clr").click(function(){
    $("#tbl_StartUp").find("input").removeAttr('checked');
  });
  $("#StartUp_sel").click(function(){
    $("#tbl_StartUp").find("input").attr("checked", "checked");
  });
  $("#StartUp_rst").click(function(){
    location.reload();
  });
  $("#dataDelete_clr").click(function(){
    $("#tbl_dataDelete").find("input").removeAttr('checked');
  });
  $("#dataDelete_sel").click(function(){
    $("#tbl_dataDelete").find("input").attr("checked", "checked");
  });
  $("#dataDelete_rst").click(function(){
    location.reload();
  });

  var gw = $("#gateway option:selected").text();

  $("#gateway").change(function(){
    gw = $("#gateway option:selected").text();
    var strID = $(this).parent().parent().parent().parent().attr("id");
    var n = strID.length;
    var len = n - 4;
    var tbdy = strID.substr(4, len);
    ///alert("tbody=" + tbdy);
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_" + tbdy).html(data);
      },
      "html"
    );
  });

  $("#pwon_can").click(function(){          //* 電源ＯＮ
    var recs = $("#tbl_pwon input:checked");//* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      //// alert( $(rec).find("#ip").text() );
      var mac = $(rec).find("#mac").text(); //* mac address 取得
      var bc = $(rec).find("#bc").text(); //* network address 取得
      ///var bc = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/wol.php",
        { "mac": mac , "bc": bc},
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#dbreg_can").click(function(){          //* ＤＢ登録
    var recs = $("#tbl_dbreg input:checked");//* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip , "order": "dbreg"},
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#CCleanerCLRAll_can").click(function(){          //* 清掃
    var recs = $("#tbl_CCleanerCLRAll input:checked");//* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip , "order": "CCleanerCLRAll"},
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#avgUpdate_can").click(function(){          //* ＡＶＧＵｐｄａｔｅ
    var recs = $("#tbl_avgUpdate input:checked");//* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip , "order": "avgUpdate"},
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#shutdown_can").click(function(){
    var recs = $("#tbl_shutdown input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      /// alert( "ip=" + ip );
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "shutdown" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#reboot_can").click(function(){
    var recs = $("#tbl_reboot input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "reboot" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#ToolwizFreezeOFF_can").click(function(){
    var recs = $("#tbl_ToolwizFreezeOFF input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "ToolwizFreezeOFF" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#ToolwizFreezeON_can").click(function(){
    var recs = $("#tbl_ToolwizFreezeON input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "ToolwizFreezeON" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#update_windows_can").click(function(){
    var recs = $("#tbl_update_windows input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "update_windows" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#updateStop_windows_can").click(function(){
    var recs = $("#tbl_updateStop_windows input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      /// alert( $(this).parent().parent().get(0).tagName );
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "updateStop_windows" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#Ping_can").click(function(){
    var recs = $("#tbl_Ping input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "Ping" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#Defrag_can").click(function(){
    var recs = $("#tbl_Defrag input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      alert( $(rec).find("#ip").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "Defrag" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#fullAuto_can").click(function(){
    var recs = $("#tbl_fullAuto input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "fullAuto" },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#Execute_can").click(function(){
    var recs = $("#tbl_Execute input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      var Execute_ap = $("#Execute_ap").val();
      if (StartUp_ap == ""){
        alert("ファイルを選択してください！");
        return;
      }
      var tab = unescape( "%09" );  //* Tab 文字
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "Execute" + tab + StartUp_ap },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#StartUp_can").click(function(){
    var recs = $("#tbl_StartUp input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      ///alert( $(rec).find("#host").text() );
      var ip = $(rec).find("#ip").text(); //* ip address 取得
      var StartUp_ap = $("#StartUp_ap").val();
      ///alert("ＡＰ名=" + StartUp_ap);
      if (StartUp_ap == ""){
        alert("ファイルを選択してください！");
        return;
      }
      var tab = unescape( "%09" );
      $.post(
        "inc/socket.php",
        { "ip": ip, "order": "StartUp" + tab + StartUp_ap },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    });
  });

  $("#dataDelete_can").click(function(){
    var recs = $("#tbl_dataDelete input:checked");  //* チェックされているチェックボックス セレクト
    $(recs).each( function() {
      var rec = $(this).parent().parent().get(0);  //* チェックされているチェックボックスの<tr>セレクト
      /// alert( $(rec).find("#host").text() );
      var id = $(rec).find("#id").text(); //* id 取得
      $.post(
        "inc/dataDelete.php",
        { "id": id },
        function(data){
          $(rec).find("#status").replaceWith(data);
        },
        "html"
      );
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_dataDelete").html(data);
      },
      "html"
    );
    });
  });

  $("#a_tab2").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_pwon").html(data);
      },
      "html"
    );
  });

  $("#a_tab3").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_shutdown").html(data);
      },
      "html"
    );
  });

  $("#a_tab4").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_reboot").html(data);
      },
      "html"
    );
  });

  $("#a_tab5").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_ToolwizFreezeOFF").html(data);
      },
      "html"
    );
  });

  $("#a_tab6").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_ToolwizFreezeON").html(data);
      },
      "html"
    );
  });

  $("#a_tab7").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_update_windows").html(data);
      },
      "html"
    );
  });

  $("#a_tab8").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_updateStop_windows").html(data);
      },
      "html"
    );
  });

  $("#a_tab9").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_dbreg").html(data);
      },
      "html"
    );
  });

  $("#a_tabA").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_CCleanerCLRAll").html(data);
      },
      "html"
    );
  });

  $("#a_tabB").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_avgUpdate").html(data);
      },
      "html"
    );
  });

  $("#a_tabC").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_Ping").html(data);
      },
      "html"
    );
  });

  $("#a_tabD").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_Defrag").html(data);
      },
      "html"
    );
  });

  $("#a_tabE").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_fullAuto").html(data);
      },
      "html"
    );
  });

  $("#a_tabF").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_Execute").html(data);
      },
      "html"
    );
  });

  $("#a_tabG").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_StartUp").html(data);
      },
      "html"
    );
  });

  $("#a_tabH").click(function(){
    $.post(
      "inc/dsp_mcpus.php",
      { "gw": gw },
      function(data){
        $("#tbody_dataDelete").html(data);
      },
      "html"
    );
  });
});
$(window).load(function(){
  // ページ全体を表す要素を取得
  var h = $('html, body');
  // スクロール位置を先頭／左端に移動
  h.scrollTop(0).scrollLeft(0);
});
</script>
EOT;
?>
</head>
<body>
<div id="container">
  <h2 id="topline">ネットワークノード管理　システム</h2>
  <ul class="tab">
    <li><a href="#tab1">概要</a></li> 
    <li><a href="#tab2" id="a_tab2">電源ON</a></li> 
    <li><a href="#tab3" id="a_tab3">電源断</a></li> 
    <li><a href="#tab4" id="a_tab4">再起動</a></li>
    <li><a href="#tab5" id="a_tab5">変更保存</a></li>
    <li><a href="#tab6" id="a_tab6">変更破棄</a></li>
    <li><a href="#tab7" id="a_tab7" style="font-size:8pt">更新予約</a></li>
    <li><a href="#tab8" id="a_tab8" style="font-size:8pt">更新停止</a></li>
    <li><a href="#tab9" id="a_tab9">DB登録</a></li> 
    <li><a href="#tabA" id="a_tabA">清掃</a></li>
    <li><a href="#tabB" id="a_tabB">AVG更新</a></li>
    <li><a href="#tabC" id="a_tabC">Ping</a></li>
    <li><a href="#tabD" id="a_tabD">Defrag</a></li>
    <li><a href="#tabE" id="a_tabE">全自動</a></li>
    <li><a href="#tabF" id="a_tabF">Execute</a></li>
    <li><a href="#tabG" id="a_tabG">StartUp</a></li>
    <li><a href="#tabH" id="a_tabH">削除</a></li>
    <li><a href="#tabZ">ヘルプ</a></li>
  </ul>
  <ul class="panel">
    <li id="tab1">
<h3>電源</h3>
<p>電源ＯＦＦのコンピューターの電源をＯＮに出来ます。</p>
<p>電源をＯＮにするコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>電源をＯＮにするには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータは電源をＯＮにしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤電源をＯＮを実行するには「実行」ボタンをクリックします。</p>
<h3>ＤＢ登録</h3>
<p>稼働中のコンピューター情報をデータベースに登録します。。</p>
<p>登録するコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>稼働中のコンピューター情報をデータベースに登録するには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤シャットダウンを実行するには「実行」ボタンをクリックします。</p>
<p><strong>注意１　ＤＢ登録を実行すると途中で中止できません。</strong></p>
<h3>シャットダウン</h3>
<p>稼働中のコンピューターをシャットダウン出来ます。</p>
<p>シャットダウンするコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>シャットダウンするには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤シャットダウンを実行するには「実行」ボタンをクリックします。</p>
<p><strong>注意１　シャットダウンを実行すると途中で中止できません。</strong></p>
<p><strong>注意２　シャットダウンは強制的に実施され、起動中のプログラムのデータは保存されません。</strong></p>
<h3>再起動</h3>
<p>稼働中のコンピューターを再起動出来ます。</p>
<p>再起動するコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>再起動するには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤再起動を実行するには「実行」ボタンをクリックします。</p>
<p><strong>注意１　再起動を実行すると途中で中止できません。</strong></p>
<p><strong>注意２　再起動は強制的に実施され、起動中のプログラムのデータは保存されません。</strong></p>
<h3>変更保存</h3>
<p>稼働中のコンピューターの全ての変更をシャットダウン時に保存します。</p>
<p>ＷｉｎｄｏｗｓＵｐｄａｔｅ、アプリケーションのインストールやＵＰＤＡＴＥ等を行った時にこの設定を行います。</p>
<p><strong>「変更保存」を実施したらコンピューターを「再起動」してください。その後「変更破棄」を実施してください。</strong></p>
<p>コンピューターの全ての変更を保存するコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>シャットダウン時に変更を保存するには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤変更保存を実行するには「実行」ボタンをクリックします。</p>
<h3>変更破棄</h3>
<p>稼働中のコンピューターの全ての変更をシャットダウン時に破棄します。通常はこの設定にしておきます。</p>
<p>コンピューターの全ての変更を破棄するコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>シャットダウン時に変更を破棄するには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤変更破棄を実行するには「実行」ボタンをクリックします。</p>
<h3>アップデート</h3>
<p>稼働中のコンピューターのＷｉｎｄｏｗｓＵｐｄａｔｅの予約設定や更新停止が出来ます。</p>
<p>「稼働中のコンピューターの全ての変更をシャットダウン時に保存する」設定を自動的に行います。</p>
<p><strong>このアクションを実施したら、アップデート後コンピューターを「再起動」してください。その後「変更破棄」を実施してください。</strong></p>
<p>ＷｉｎｄｏｗｓＵｐｄａｔｅを予約・更新停止するコンピューターを選択出来ます。全てのコンピュータを選択することも出来ます。</p>
<p>ＷｉｎｄｏｗｓＵｐｄａｔｅの予約・更新停止を設定するには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤ＲＡＤＩＯボタンの「予約設定」か「更新停止」をクリックします。
<p>⑥ＷｉｎｄｏｗｓＵｐｄａｔｅの予約・更新停止を実行するには「実行」ボタンをクリックします。</p>
<h4>　予約設定</h4>
<p>　Ｗｉｎｄｏｗｓの更新プログラムをインストールする方法を例えば下図の通り変更します。</p>
<p>　Ｗｉｎｄｏｗｓの更新プログラムのインストールは現時刻の次の時間（例えば現時刻が９時５分の場合実行時刻は１０時０分）から実行されます。</p>
<p><img src="windows_update.JPG" alt="Ｗｉｎｄｏｗｓの更新プログラムをインストールする方法" style="margin-bottom:0.5em;margin-top:0.5em;border:1px solid black;width:606px;height:443px;"></p>
<h4>　更新停止</h4>
<p>　Ｗｉｎｄｏｗｓの更新プログラムを確認しないに設定します。</p>
<p>　Ｗｉｎｄｏｗｓの更新プログラムをインストールする方法を例えば下図の通り変更します。</p>
<p>　この設定により、ＷｉｎｄｏｗｓＵｐｄａｔｅに関する通知は無効になります。</p>
<p><img src="update_destroy.JPG" alt="Ｗｉｎｄｏｗｓの更新プログラムをインストールする方法" style="margin-bottom:0.5em;margin-top:0.5em;border:1px solid black;width:617px;height:557px;"></p>
<h3>Ｅｘｅｃｕｔｅ</h3>
<p>稼働中のコンピューターのマウスとキーボードをコントロールします。</p>
<p>マウスとキーボード入力を記録して再生するソフト（<a href="http://www.uwsc.info/" target="_blank">ＵＷＳＣ</a>）を使用して、選択したコンピュータのマウスとキーボードを自動コントロール出来ます。</p>
<p>ＵＷＳＣスクリプトを<span style="font-weight:900;color:maroon">管理者として実行する</span>ショートカットを、ファイル名「 Ex.lnk 」として、仮想ドライブ Ｚ フォルダ（ Z:\ ）に保存してください。</p>
<p>ＵＷＳＣスクリプトを実行するには</p>
<p>①表示されているコンピューターのチェックボックスにチェックを入れます。</p>
<p>②全てのコンピュータを選択するには「全て選択」ボタンをクリックします。全コンピューターのチェックボックスにチェックが入ります。</p>
<p>③チェックボックスのチェックをもう一度クリックするとチェックは外れます。チェックが外れているコンピュータはシャットダウンしません。</p>
<p>④全てのチェックボックスのチェックを外す場合は「クリア」ボタンをクリックします。</p>
<p>⑤ＵＷＳＣスクリプトを実行するには「実行」ボタンをクリックします。</p>

<h3><a href="https://pma.nnms/phpMyAdmin/" target="_blank">データベース管理（ｐｈｐＭｙＡｄｍｉｎ）</a></h3>
<p>コンピューター等データを保存するデータベース（ＭｙＳＱＬ）を管理できます。</p>
<p>データの新規登録・削除・修正・参照が可能です。</p>
<p>テーブルの新規登録・削除が可能です。</p>
<p>テーブルのコラムの新規登録・削除・修正・表示が可能です。</p>
<p>ユーザ名＝「nnm」パスワードは本コンピュータへのログインパスワードと同じです。</p>
<p>データベース名は「nnm」　テーブル名は「cpus」です。</p>
<h3>ヘルプ</h3>
<p>バージョン情報等を表示します。</p>
    </li>

    <li id="tab2">
<?php
  dsp_header("コンピューターの電源を入れます", "pwon");
echo <<<EOT
      <tbody id="tbody_pwon">
      </tbody>
EOT;
  dsp_trail("コンピューターの電源を入れます", "pwon");
?>
    </li>

    <li id="tab3">
<?php
  dsp_header("コンピューターをシャットダウンします", "shutdown");
echo <<<EOT
      <tbody id="tbody_shutdown">
      </tbody>
EOT;
  dsp_trail("コンピューターをシャットダウンします", "shutdown");
?>
    </li> 
    <li id="tab4"> 
<?php
  dsp_header("コンピューターを再起動します", "reboot");
echo <<<EOT
      <tbody id="tbody_reboot">
      </tbody>
EOT;
  dsp_trail("コンピューターを再起動します", "reboot");
?>
    </li>
    <li id="tab5">
<?php
  dsp_header("コンピューターの全ての変更を保存します", "ToolwizFreezeOFF");
echo <<<EOT
      <tbody id="tbody_ToolwizFreezeOFF">
      </tbody>
EOT;
  dsp_trail("コンピューターの全ての変更を保存します", "ToolwizFreezeOFF");
?>
    </li>
    <li id="tab6">
<?php
  dsp_header("コンピューターの全ての変更を破棄します", "ToolwizFreezeON");
echo <<<EOT
      <tbody id="tbody_ToolwizFreezeON">
      </tbody>
EOT;
  dsp_trail("コンピューターの全ての変更を破棄します", "ToolwizFreezeON");
?>
    </li>
    <li id="tab7">
<?php
  dsp_header("ＷｉｎｄｏｗｓＵｐｄａｔｅの予約を設定します", "update_windows");
echo <<<EOT
      <tbody id="tbody_update_windows">
      </tbody>
EOT;
  dsp_trail("ＷｉｎｄｏｗｓＵｐｄａｔｅの予約を設定します", "update_windows");
?>
    </li>
    <li id="tab8">
<?php
  dsp_header("ＷｉｎｄｏｗｓＵｐｄａｔｅを停止します", "updateStop_windows");
echo <<<EOT
      <tbody id="tbody_updateStop_windows">
      </tbody>
EOT;
  dsp_trail("ＷｉｎｄｏｗｓＵｐｄａｔｅを停止します", "updateStop_windows");
?>
    </li> 
    <li id="tab9">
<?php
  dsp_header("コンピューター情報をＤＢに登録します", "dbreg");
echo <<<EOT
      <tbody id="tbody_dbreg">
      </tbody>
EOT;
  dsp_trail("コンピューター情報をＤＢに登録します", "dbreg");
?>
    </li> 

    <li id="tabA">
<?php
  dsp_header("不要ファイルやレジストリデータを削除します", "CCleanerCLRAll");
echo <<<EOT
      <tbody id="tbody_CCleanerCLRAll">
      </tbody>
EOT;
  dsp_trail("不要ファイルやレジストリデータを削除します", "CCleanerCLRAll");
?>
    </li> 

    <li id="tabB">
<?php
  dsp_header("ＡＶＧを更新します", "avgUpdate");
echo <<<EOT
      <tbody id="tbody_avgUpdate">
      </tbody>
EOT;
  dsp_trail("ＡＶＧを更新します", "avgUpdate");
?>
    </li> 

    <li id="tabC">
<?php
  dsp_header("Ｐｉｎｇを発行します", "Ping");
echo <<<EOT
      <tbody id="tbody_Ping">
      </tbody>
EOT;
  dsp_trail("Ｐｉｎｇを発行します", "Ping");
?>
    </li>

    <li id="tabD">
<?php
  dsp_header("Defragger を実行します", "Defrag");
echo <<<EOT
      <tbody id="tbody_Defrag">
      </tbody>
EOT;
  dsp_trail("Defragger を実行します", "Defrag");
?>
    </li>

    <li id="tabE">
<?php
  dsp_header("全行程を実行します", "fullAuto");
echo <<<EOT
      <tbody id="tbody_fullAuto">
      </tbody>
EOT;
  dsp_trail("全行程を実行します", "fullAuto");
?>
    </li>

    <li id="tabF">
<?php
  dsp_header("指定のプログラムを実行します", "Execute");
echo <<<EOT
      <tbody id="tbody_Execute">
      </tbody>
EOT;
   dsp_trail("指定のプログラムを実行します", "Execute");
?>
    </li>

    <li id="tabG">
<?php
  dsp_header("スタートアップメニューにＡＰを配置します", "StartUp");
echo <<<EOT
      <tbody id="tbody_StartUp">
      </tbody>
EOT;
  dsp_trail("スタートアップメニューにＡＰを配置します", "StartUp");
?>
    </li>

    <li id="tabH">
<?php
  dsp_header("選択したデータを削除します", "dataDelete");
echo <<<EOT
      <tbody id="tbody_dataDelete">
      </tbody>
EOT;
  dsp_trail("選択したデータを削除します", "dataDelete");
?>
    </li>

    <li id="tabZ">
<h4>ネットワーク・ノード・マネジメントシステム　バージョン１．５．０</h4>
    </li> 
  </ul>
<?php
require_once dirname(__FILE__) . '/inc/footer.php';
