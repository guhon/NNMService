<?php
date_default_timezone_set('Asia/Tokyo');
$today = getdate();
$yyyy = $today['year'];
echo <<< EOT
<table style="font-size:9pt; color:navy; font-weight:500 width:730px; clear:both" border="0" cellpadding="0">
<col width="15"><col width="700%"><col width="15">
  <tr style="line-height:1em">
    <td>&nbsp;</td>
    <TD>Copyright&nbsp;&#169;&nbsp;2011-{$yyyy}&nbsp;THOORASAP&nbsp;Corporation.&nbsp;All&nbsp;rights&nbsp;reserved.</TD>
    <td>&nbsp;</td>
  </TR>
</table>
</div><!--container-->
</body>
</html>
EOT;
