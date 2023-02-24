<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<title>PR and PO</title>
		<script language='javascript' src='../include/windowfullscreen.js'></script>				
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0">
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="1002" height="593" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="include/pr_pomenu.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="include/pr_pomenu.swf" quality="high" bgcolor="#ffffff" width="1002" height="593" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</body>
</html>
<?php
	//echo $_SESSION["strGloUsername"].'<br>';
		$dept_str = substr($_SESSION["strGloUsername"], 0, 3);  
if ($dept_str == 'MKS' || $_SESSION["empno_user"] =='14002')
{		
//echo 'dept--->'.$dept_str ;
//include("mail_delivery_date2.php");
//include("mail_delivery_date4.php");
//}
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
