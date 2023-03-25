<?php
		if(isset($_SESSION["valid_userprpo"])) {
			require_once("../include_RedThemes/MSSQLServer_connect_2.php");	
			$PathtoUploadFile = "E:\\iso\\";

			$pr_no=@$_GET["pr_no"];
			$flag_obj = @$_GET["flag_obj"];
			if($flag_obj=="8")
				$_SESSION["pr_type"] = "T";
			else 	$_SESSION["pr_type"] = "S";
			$status_ok = 0;

			$strQUE = "select pr_path from pr_master where pr_no='$pr_no'";
			$curQUE = @odbc_exec($conn,$strQUE);
			$pr_path = @odbc_result($curQUE, "pr_path");

			$str_num = "select count(*) c from pr_and_po where pr_no='$pr_no'";			
			$cur_num = @odbc_exec($conn,$str_num);
			$number = @odbc_result($cur_num, "count(*) c");
			if($number == 0){
				$result1=@odbc_exec($conn,"delete from pr_details_subjob where pr_no='$pr_no'");			
				if($result1){
					$result2=@odbc_exec($conn,"delete from pr_details where pr_no='$pr_no'");			
					if($result2){
						$result3=@odbc_exec($conn,"delete from pr_master where pr_no='$pr_no'");			
						if($result3){
							$status_ok = 1;
						}
					}
				}
			}
			
			
			if($status_ok==1){
				if($pr_path != ""){
					$path_file = $PathtoUploadFile."pr_thai";
					@unlink("$path_file\\$pr_path");
				}
							
				@odbc_exec($conn,"commit");
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'alert ("บันทึกรายการเรียบร้อยแล้วค่ะ");';
				echo '</script>';
				
			}else{
				@odbc_exec($conn,"rollback");
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'alert ("ไม่สามารถลบข้อมูลได้เนื่องจากถูกเรียกใช้งานจากที่อื่นค่ะ");';
				echo '</script>';				
			}
			// include("./pr_search.php");
			
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