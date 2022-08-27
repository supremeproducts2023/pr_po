<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {	
		require_once("../include_RedThemes/odbc_connect.php");				
		$empno_user = $_SESSION["empno_user"];
	
		$po_no=@$_GET["po_no"];
		$flag=@$_GET["flag"];

		$txt_up = "update po_master set po_status='$flag',last_user='$empno_user' where po_no='$po_no'";
		$exe_up = odbc_exec($conn,$txt_up);
		$exe_commit = odbc_exec($conn,"commit");
					
		include("./po_search.php");						
		echo '<script language="JavaScript" type="text/JavaScript">';
		echo 'alert ("บันทึกการเปลี่ยนแปลงเรียบร้อยแล้วค่ะ");';
		echo '</script>';

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