<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<script language="javascript" type="text/javascript">
function turn_value(a) { 
	if(a == 0){
		alert('สามารถใช้รหัส Vendor นี้ได้ค่ะ');
	}else{
		alert('กรุณากรอกรหัส Vendor ใหม่ค่ะ เพราะว่ามีในฐานข้อมูลแล้วค่ะ');
	}
	window.close();
}
</script>
<title>Untitled Document</title>
</head>
<body>
<?
			session_start();
			include("../../include/odbc_connect.php");
			
			$vendor_code = $_GET["vendorno"];
			$supno = $_GET["supno"];	
            echo  $vendor_code;
			if($supno != ''){//edit
			
					$sqlCountDup = "select vendor_no from supplier  where supplier_id = '$supno' ";
					$resultDup = odbc_exec($conn,$sqlCountDup);
					$vendor_codeDb = odbc_result($resultDup,"vendor_no");
					
					if($vendor_code != $vendor_codeDb){
							$sqlCountDup = "select count(*) from supplier  where upper(vendor_no) like upper('%$vendor_code%')";
							$resultDup = odbc_exec($conn,$sqlCountDup);
							$chkCountDup = odbc_result($resultDup,"count(*)");
					}else{
							$chkCountDup = 0;
					}

			}else{//add
					$sqlCountDup = "select count(*) from supplier  where upper(vendor_no) = upper('$vendor_code') ";
					$resultDup = odbc_exec($conn,$sqlCountDup);
					$chkCountDup = odbc_result($resultDup,"count(*)");
			}
		
			echo "<script language='javascript'>";
			echo "turn_value('".$chkCountDup."');";
			echo "</script>";

?>			
</body>
</html>
