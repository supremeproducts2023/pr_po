<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");	

			$vendor_id= @$_GET["v_id"];
			$status_ok = 0;
			$empno_user = $_SESSION["empno_user"];		
			
				$strvendorUPD =  "update vendor_group set 
														vendor_status = '0',
														lastuser_id = '$empno_user',
														last_date = getdate()
														where vendor_id='$vendor_id'";
														
				$result1=@odbc_exec($conn,$strvendorUPD);			
				if($result1){
						$status_ok = 1;
				}
			
			include("./sup_search.php");
			
			if($status_ok==1){
				odbc_exec($conn,"commit");
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'alert ("บันทึกข้อมูลเรียบร้อยแล้วค่ะ");';
				echo "location.href('vendor_search.php');"; 
				echo '</script>';
				
			}else{
				odbc_exec($conn,"rollback");
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'alert ("ไม่สามารถลบข้อมูลได้เนื่องจากถูกเรียกใช้งานจากที่อื่นค่ะ");';
				echo "location.href('vendor_search.php');"; 
				echo '</script>';				
			}
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>
<html><head>
<title>SUPPLIER</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	