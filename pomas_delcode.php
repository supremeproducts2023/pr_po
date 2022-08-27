<?
	@session_start();
	if(session_is_registered("valid_userprpo")) {
			require_once("../include_RedThemes/odbc_connect.php");	
			require_once("../include_RedThemes/MSSQLServer_connect.php");	
			require_once("../include/alert.php");
//			$PathtoUploadFile = "E:\\iso\\";

			$po_no=@$_GET["po_no"];
/*	
			$strQUE = "select po_file,po_file2,po_file3 from po_master where po_no='$po_no'";
			$curQUE = @odbc_exec($conn,$strQUE);
			$po_file = @odbc_result($curQUE, "po_file");
			$po_file2 = @odbc_result($curQUE, "po_file2");
			$po_file3 = @odbc_result($curQUE, "po_file3");
			
			$result1=@odbc_exec($conn,"delete from po_details_subjob where po_no='$po_no'");			
			if($result1){
				$result2=@odbc_exec($conn,"delete from pr_and_po where po_no='$po_no'");			
				if($result2){
					$result3=@odbc_exec($conn,"delete from po_details where po_no='$po_no'");			
					if($result3){
						$result4=@odbc_exec($conn,"delete from po_master where po_no='$po_no'");			
						if($result4){
							$status_ok = 1;
						}
					}
				}
			}  */
			
			$strDEL = "update PO_MASTER set PO_STATUS = '5' where PO_NO = '$po_no'";
			$queDEL = @odbc_exec($conn,$strDEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
								
			if($queDEL){
/*						if($po_file != ""){
							$path_file = $PathtoUploadFile."po_thai";
							@unlink("$path_file\\$po_file");
						}
						if($po_file2 != ""){
							$path_file = $PathtoUploadFile."po_thai2";
							@unlink("$path_file\\$po_file2");
						}
						if($po_file3 != ""){
							$path_file = $PathtoUploadFile."po_thai3";
							@unlink("$path_file\\$po_file3");
						}
*/					
					$type = 'D';
//----------------------------------------------------------------------------insert into POHD_Center------------------------------------------------------------------------------------//	
				$strSQL = "select PO_DATE, SUPPLIER_ID, DISCOUNT_PERCENT, DISCOUNT_BAHT, OUR_REF, DESPATCH_TO, DELIVERY_TIME, PAYMENT, FOR_REF, ACCID, COSTID, REDHEAD, YOUR_REF, E_MAIL_DATE from PO_MASTER where PO_NO = '$po_no'";
				$strResult = @odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
				
				$strSQL = "select max(RunNo)+1 as RunNo from POHD_Center";
				$result = @odbc_exec($MSSQL_connect,$strSQL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
				$run_no = @odbc_result($result,"RunNo");
				if($run_no == '') $run_no = 1;
				
				$po_date = @odbc_result($strResult,"PO_DATE");
				$supplier_id = @odbc_result($strResult,"SUPPLIER_ID");
				$discount_percent = @odbc_result($strResult,"DISCOUNT_PERCENT");
					$discount_percent = (float)$discount_percent;
				$discount_baht = @odbc_result($strResult,"DISCOUNT_BAHT");
					$discount_baht = (float)$discount_baht;
				$our_ref = @odbc_result($strResult,"OUR_REF");
				$despatch_to = @odbc_result($strResult,"DESPATCH_TO");
				$delivery_time = @odbc_result($strResult,"DELIVERY_TIME");
				$payment = @odbc_result($strResult,"PAYMENT");
				$for_ref = @odbc_result($strResult,"FOR_REF");
				$accid = @odbc_result($strResult,"ACCID");
				$costID = @odbc_result($strResult,"COSTID");
				$redhead = @odbc_result($strResult,"REDHEAD");
				$your_ref = @odbc_result($strResult,"YOUR_REF");
				$e_mail_date = @odbc_result($strResult,"E_MAIL_DATE");			
						
				$strSQL = "insert into POHD_Center(PO_NO, TAXDATE, VENDORCODE, DISCOUNT_PERCENT, DISCOUNT_BATH, OUR_REF, ADDRESS2, DELIVERYTIME, PAYMENTREMARK, FORREF, ACCID, COSTID, REDHEAD, YOUR_REF, E_MAIL_DATE, TRType) 
				values('$po_no', '$po_date', '$supplier_id', '$discount_percent', '$discount_baht', '$our_ref', '$despatch_to', '$delivery_time', 
				'$payment', '$for_ref', '$accid', '$costID', '$redhead', '$your_ref', '$e_mail_date','$type')";		
				@odbc_exec($MSSQL_connect,$strSQL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
		//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
			
//-----------------------------------------------------------------------------insert into PODT_Center----------------------------------------------------------------------------------//	
				$strSQL = "select ID, PROD_NO, GAR_QTY, GAR_UNIT, GAR_PRICE, DISCOUNT_PERCENT, PROD_NAME, PROD_TYPE, PROD_PRICE, PROD_QTY, PROD_UNIT from PO_DETAILS where PO_NO = '$po_no'";
				$strResult = @odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
				
				while(@odbc_fetch_row($strResult)){
					$id = @odbc_result($strResult,"ID");
					$prod_no = @odbc_result($strResult,"PROD_NO");
					$gar_qty = @odbc_result($strResult,"GAR_QTY");
					$gar_qty = (float)$gar_qty;
					$gar_unit = @odbc_result($strResult,"GAR_UNIT");
					$gar_price = @odbc_result($strResult,"GAR_PRICE");
					$gar_price = (float)$gar_price;
					$discount_percent = @odbc_result($strResult,"DISCOUNT_PERCENT");
					$discount_percent = (float)$discount_percent;
					$prod_name = @odbc_result($strResult,"PROD_NAME");
					$prod_type = @odbc_result($strResult,"PROD_TYPE");
					$prod_type = (float)$prod_type;
					$prod_price = @odbc_result($strResult,"PROD_PRICE");
					$prod_price = (float)$prod_price;
					$prod_qty = @odbc_result($strResult,"PROD_QTY");
					$prod_qty = (float)$prod_qty;
					$prod_unit = @odbc_result($strResult,"PROD_UNIT");
						
					$strSQL = "insert into PODT_Center(RunNo, PO_NO, LINENUM, ITEMCODE, QUANTITY, UNITMSR, PRICE, DISPRCNT, PROD_NAME, PROD_TYPE, PROD_PRICE, PROD_QTY, PROD_UNIT, TRType) 
					values('$run_no', '$po_no', '$id', '$prod_no', '$gar_qty', '$gar_unit', '$gar_price', '$discount_percent', '$prod_name', '$prod_type',
					'$prod_price', '$prod_qty', '$prod_unit', '$type')";		
					@odbc_exec($MSSQL_connect,$strSQL)or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
				}	//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//		
		
						@odbc_exec($conn,"commit");
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'alert ("บันทึกรายการเรียบร้อยแล้วค่ะ");';
						echo '</script>';	
			}
		include("./po_search.php");
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