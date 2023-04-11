<?
@session_start();
if(isset($_SESSION["valid_userprpo"])) {
?>
<html>
<head>
<title>PR</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<script language='javascript' src='file://///172.10.0.16/WebServ/webroot/include/windowfullscreen.js'></script>				
</head>
<?php 
	$pr_no = @$_GET["pr_no"];
	$flag = @$_GET["flag"];
 	if($pr_no!="" && $flag!="") {
		$src = "prmas_edit.php?pr_no=".$pr_no."&flag=edit";		
	}else{
		$src = "pr_search.php";		
	}
?>
<frameset rows="68,30,*" frameborder="NO" border="0" framespacing="0">
  <frame src="picheadpage.php" name="top_frame" scrolling="NO" noresize>
<frame src="pr_head.php?tabno=1.php" name="mid_frame" scrolling="NO" noresize>  
  <frame src="<?=$src;?>"  name="main_frame">
</frameset>
<noframes><body>
</body></noframes>
</html>
<?
}else{
		include("file:///C|/WebServ/webroot/include_RedThemes/SessionTimeOut.php");
}
?>