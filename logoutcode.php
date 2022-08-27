<?
	@session_start();
	
	$valid_userprpo=@$_SESSION["valid_userprpo"];

	$old_user=$valid_userprpo;
	$result = session_unregister("valid_userprpo");
	session_unregister("empno_user");
	session_unregister("roles_user");
	session_unregister("ses_Search");
	session_unregister("ses_poCheck");
	session_unregister("ses_poImport");
		
	session_destroy();
	if(isset($old_user)){
		if($result)	
			include("../include_RedThemes/CallFirstPage.php");
		else{
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("ไม่สามารถออกจากระบบได้");';
			echo '</script>';
			include("../include_RedThemes/CallFirstPage.php");
		}
	}
	else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
<html><head>
<title>PR REPORT</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	