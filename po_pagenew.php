<?php
@session_start();
if(isset($_SESSION["valid_userprpo"])) {
?>
<html>
<head>
<title>PO</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<script language='javascript' src='file://///172.10.0.16/WebServ/webroot/include/windowfullscreen.js'></script>				
</head>
<frameset rows="68,30,*" frameborder="NO" border="0" framespacing="0">
  <frame src="picheadpage.php" name="top_frame" scrolling="NO" noresize>
<frame src="pr_head.php?tabno=1.php" name="mid_frame" scrolling="NO" noresize>  
  <frame src="pomas_add.php" name="main_frame">
</frameset>
<noframes><body>
</body></noframes>
</html>
<?php
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>