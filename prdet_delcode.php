<?
	@session_start();
	if(session_is_registered("valid_userprpo")) {
			require_once("../include_RedThemes/odbc_connect.php");	

		$id=@$_GET["id"];
		$pr_no=@$_GET["pr_no"];
		$flag_obj=@$_GET["flag_obj"];

			if($flag_obj=="7"){
				$result=odbc_exec($conn,"delete from pr_details_subjob where id='$id' and pr_no='$pr_no'");
			}
			$result=odbc_exec($conn,"delete from pr_details where id='$id' and pr_no='$pr_no'");
			$result2=odbc_exec($conn,"commit");

			include("./prmas_edit.php");
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
<html><head>
<title>PR REPORT</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	