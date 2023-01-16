<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {	
		require_once("../include_RedThemes/odbc_connect.php");				
		require_once("../include_RedThemes/MSSQLServer_connect.php");				
		require_once("../include/alert.php");
		$empno_user = $_SESSION["empno_user"];
	
		$po_no=@$_GET["po_no"];
		$port = true;

		$strSQL = "select PO_NO, ReadFlg from POHD_Center where PO_NO = '$po_no'";
		$strResult =@odbc_exec($MSSQL_connect,$strSQL)or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
		if(@odbc_result($strResult,"PO_NO") == '') 
			$type = 'A'; 
		else
		{ 
			$type = 'A';
			$strResult =@odbc_exec($MSSQL_connect,$strSQL)or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
			while(@odbc_fetch_row($strResult))
			{
				if(@odbc_result($strResult,"ReadFlg") == "R")
				{	
					$type = 'U';
					break;
				}
			}	
		}
		
//----------------------------------------------------------------------------insert into POHD_Center------------------------------------------------------------------------------------//	
		$strSQL = "select PO_DATE, SUPPLIER_ID, DISCOUNT_PERCENT, DISCOUNT_BAHT, OUR_REF, DESPATCH_TO, DELIVERY_TIME, PAYMENT, PO_REMARK, FOR_REF, ACCID, COSTID, REDHEAD, YOUR_REF, E_MAIL_DATE, FLAG_VAT,REF_PO_NO,PO_COMPANY from PO_MASTER where PO_NO = '$po_no'";
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
		$po_remark = @odbc_result($strResult,"PO_REMARK");
		$for_ref = @odbc_result($strResult,"FOR_REF");
		$accid = @odbc_result($strResult,"ACCID");
		$costID = @odbc_result($strResult,"COSTID");
		$redhead = @odbc_result($strResult,"REDHEAD");
		$your_ref = @odbc_result($strResult,"YOUR_REF");
		$e_mail_date = @odbc_result($strResult,"E_MAIL_DATE");			
		$flag_vat = @odbc_result($strResult,"FLAG_VAT");
		$ref_po_no = @odbc_result($strResult,"REF_PO_NO");
		$PO_COMPANY = @odbc_result($strResult,"PO_COMPANY");

		$strSQLhead = "insert into POHD_Center(PO_NO, TAXDATE, VENDORCODE, DISCOUNT_PERCENT, DISCOUNT_BATH, OUR_REF, ADDRESS2, DELIVERYTIME, PAYMENTREMARK, POREMARK, FORREF, ACCID, COSTID, REDHEAD, YOUR_REF, E_MAIL_DATE, TRType,PO_NOREF,Compcode) 
		values('$po_no', '$po_date', '$supplier_id', '$discount_percent', '$discount_baht', '$our_ref', '$despatch_to', '$delivery_time', 
		'$payment', '$po_remark', '$for_ref', '$accid', '$costID', '$redhead', '$your_ref', '$e_mail_date','$type','$ref_po_no'";
        		if($PO_COMPANY=="T")  {
				$strSQLhead  = $strSQLhead. ",'TK'";
				
				} 
				else {
				$strSQLhead  = $strSQLhead.",'SP'";}
				$strSQLhead  = $strSQLhead. ")";		
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//-----------------------------------------------------------------------------insert into PODT_Center----------------------------------------------------------------------------------//	
		$strSQL = "select ID, PROD_NO, GAR_QTY, GAR_UNIT, GAR_PRICE, DISCOUNT_PERCENT, DISCOUNT_BAHT, PROD_NAME, PROD_TYPE, PROD_PRICE, PROD_QTY, PROD_UNIT, CODE, ITEM_CODE from PO_DETAILS where PO_NO = '$po_no'";
		$strResult = @odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
		
		while(@odbc_fetch_row($strResult)){
			$id = @odbc_result($strResult,"ID");
			$prod_no = @odbc_result($strResult,"PROD_NO");
			$item_code = @odbc_result($strResult,"ITEM_CODE");
			$gar_qty = @odbc_result($strResult,"GAR_QTY");
			$gar_qty = (float)$gar_qty;
			$gar_unit = @odbc_result($strResult,"GAR_UNIT");
			$gar_price = @odbc_result($strResult,"GAR_PRICE");
			$gar_price = (float)$gar_price;
			$discount_percent = @odbc_result($strResult,"DISCOUNT_PERCENT");
				$discount_percent = (float)$discount_percent;
			$discount_baht = @odbc_result($strResult,"DISCOUNT_BAHT");
				$discount_baht = (float)$discount_baht;
			$prod_name = @odbc_result($strResult,"PROD_NAME");
			$prod_type = @odbc_result($strResult,"PROD_TYPE");
			$prod_price = @odbc_result($strResult,"PROD_PRICE");
			$prod_price = (float)$prod_price;
			if($flag_vat == "0")
				$prod_price = $prod_price*100/107;
			$prod_qty = @odbc_result($strResult,"PROD_QTY");
			$prod_qty = (float)$prod_qty;
			$prod_unit = @odbc_result($strResult,"PROD_UNIT");
			$code = @odbc_result($strResult,"CODE");
			
			if($prod_type=="5")
			{
				if($item_code=="")
				{	
					$port = false;
					alert("คุณไม่สามารถ Port ข้อมูลไปยัง B1 ได้\\nเนื่องจากคุณไม่ได้ระบุกลุ่มของค่าใช้จ่ายกรณีที่สินค้าเป็นประเภท Etc. ค่ะ");
					include("./po_search.php");	
					break;
				}
				$item = $item_code; 
				$prod_type = (float)$prod_type;
			}else{
				$item = $prod_no;
				$prod_type = (float)$prod_type;
			}
			$strSQL = "insert into PODT_Center(RunNo, PO_NO, LINENUM, ITEMCODE, QUANTITY, UNITMSR, PRICE, DISPRCNT, DISCOUNT_BAHT, PROD_NAME, PROD_TYPE, PROD_PRICE, PROD_QTY, PROD_UNIT, TRType, CODE) 
			values('$run_no', '$po_no', '$id', '$item', '$gar_qty', '$gar_unit', '$gar_price', '$discount_percent', '$discount_baht', '$prod_name', '$prod_type',
			'$prod_price', '$prod_qty', '$prod_unit', '$type','$code')";		
			@odbc_exec($MSSQL_connect,$strSQL)or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
		}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//		
		if($port)
		{	@odbc_exec($MSSQL_connect,$strSQLhead) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
			$strSQL = "update PO_MASTER set PORTSQL = getdate() where PO_NO = '$po_no'";
			@odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
			@odbc_exec($conn,"commit");
	
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("Port ข้อมูลไปยัง B1 เรียบร้อยแล้วค่ะ");';
			echo '</script>';
			include("./po_search.php");	
		}
	}
	else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>
<html><head>
<title>PO</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
</head>
<body></body>
</html>	