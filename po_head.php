<?
@session_start();
if(session_is_registered("valid_userprpo")) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<script language='javascript' src='./include/tabanimate.js'></script>		
<title>Untitled Document</title>
</head>

<body topmargin="0" leftmargin="0">
<table width="1100" border="0" cellspacing="0" cellpadding="0">
<tr><td>
	<img src="./include/tab/search-03.jpg" name="tsearch" border="0" ><img src="./include/tab/tabblank.jpg" width="1000" height="30"><br />
</td></tr>
</table>
</body>
</html>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>