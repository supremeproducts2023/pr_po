<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<?
@session_start();
if(session_is_registered("valid_userprpo")) {	
	require_once("../include_RedThemes/odbc_connect.php");		
	$po_no = $_GET["po_no"];
	
	// ???????? Excel
		$excel = new COM("Excel.Application");
		$excel->sheetsinnewworkbook=1;
		$excel->Workbooks->Add();				
		$book = $excel->Workbooks(1);			
		$sheet = $book->Worksheets(1);  			
	// Set Font ??? Sheet
		$all = $sheet->Cells();
		$all->activate;
		$all->Font->Name = "Tahoma";
		$all->Font->Size = 8;
		$all->VerticalAlignment = 1;
		$all->Rows->AutoFit();
	// ?????????
		$sheet->PageSetup->PaperSize=5;
		$sheet->PageSetup->TopMargin = 50;
		$sheet->PageSetup->LeftMargin = 1.2;
		$sheet->PageSetup->RightMargin = 1.1;
		$sheet->PageSetup->BottomMargin = 25;
		$sheet->PageSetup->Orientation=2;
			
		$sheet->PageSetup->PrintTitleRows = "$1:$2";
		$sheet->PageSetup->RightHeader = "&P/&N";
	// ?????????
		$cell = $sheet->Cells(1,3);	$cell->activate;	$cell->Font->Bold = true;	$cell->Font->Size = 10;		$cell->value = "รายการสรุปการทำรับเข้า PO เลขที่  $po_no";
		$cell->RowHeight = 20;
		
		$all = $sheet->Range("A2:I2");
		$all->activate;
		$all->HorizontalAlignment = 3;	
		$all->Font->Bold = true;
		$all->Interior->ColorIndex = 15;	
		$all->Borders->LineStyle = 1;	
		
		$cell = $sheet->Cells(2,1);		$cell->activate;			$cell->value = "No.";						$cell->ColumnWidth = 10.00;
		$cell = $sheet->Cells(2,2);		$cell->activate;			$cell->value = "สินค้า";		    $cell->ColumnWidth = 50.00;
		$cell = $sheet->Cells(2,3);		$cell->activate;			$cell->value = "วันที่ PO";		    $cell->ColumnWidth = 20.00;
		$cell = $sheet->Cells(2,4);		$cell->activate;			$cell->value = "จำนวนที่สั่งซื้อ";		$cell->ColumnWidth = 15.00;
		$cell = $sheet->Cells(2,5);		$cell->activate;			$cell->value = "ชื่อผู้ผลิต";				$cell->ColumnWidth = 35.00;
		$cell = $sheet->Cells(2,6);		$cell->activate;			$cell->value = "เลขที่ GAR";					$cell->ColumnWidth = 15.00;
		$cell = $sheet->Cells(2,7);		$cell->activate;			$cell->value = "วันที่รับเข้า";					$cell->ColumnWidth = 20.00;
		$cell = $sheet->Cells(2,8);		$cell->activate;			$cell->value = "สถานะ GAR";					$cell->ColumnWidth = 40.00;
		$cell = $sheet->Cells(2,9);		$cell->activate;			$cell->value = "จำนวนที่ทำรับเข้า";					$cell->ColumnWidth = 15.00;
		$i= 3;		
	// Query Transection
		$strQUE= "select b.po_no,a.status,to_char(b.po_date,'DD/MM/YYYY') po_date
								,b.codeshow,b.gar_qty order_qty,a.gar_no
							   ,(substr(a.gar_date,7,2) || '/' || substr(a.gar_date,5,2) || '/' ||substr(a.gar_date,0,4)) gar_date
							   ,a.RECEIVE_QTY ,b.company_name
						from 
						(
						select ngar.po_no ,ngar.status, ngar.gar_no ,ngar.gar_date ,nprod.id_prod_po,nprod.prod_no, nvl(stk.QTY,0) RECEIVE_QTY 
						from ngar ngar inner join 
							 ngar_prod nprod on ngar.gar_id = nprod.gar_id left join
							 ngar_stock_in stk on nprod.gar_id = stk.gar_id and nprod.prod_id = stk.prod_id 
						where ngar.po_no = '$po_no'  
						and ngar.status != 'XX'
						)a inner join
						(
						   select pm.po_no ,sup.company_name, pm.po_date ,pd.code,pd.id, pd.prod_type,pd.gar_qty ,
								 case when pd.prod_type = '3' then (select (princ_prodno || ' ' || engname ) from product where prodno = pd.code )
								 else (select (bom_codeshow || ' ' || bom_desc )   from nbom_description where bom_code = pd.code ) end as codeshow
						   from po_master pm inner join
								po_details pd on pm.po_no = pd.po_no inner join
								supplier sup on pm.supplier_id = sup.supplier_id
						  where pm.po_no = '$po_no'    
						)b on a.prod_no = b.code and a.id_prod_po = b.id  order by codeshow";
	$curQUE = odbc_exec($conn,$strQUE);
	$v_bomcodeshow = '';
	$v_po_no = '';
	// ?????????? row ???
		while(odbc_fetch_row($curQUE)){ // Loop  Job ????
			$po_no=odbc_result($curQUE, "po_no");
			$codeshow=odbc_result($curQUE, "codeshow");
			$order_qty=odbc_result($curQUE, "order_qty");
			$company_name=odbc_result($curQUE, "company_name");
			$gar_no=odbc_result($curQUE, "gar_no");
			$gar_date=odbc_result($curQUE, "gar_date");
			$po_date=odbc_result($curQUE, "po_date");
			$po_status=odbc_result($curQUE, "status");
			$RECEIVE_QTY=odbc_result($curQUE, "RECEIVE_QTY");
			
			$txt_status = "";
			if($po_status == "00")
				$txt_status = "สินค้าผ่านการนับจำนวนจากโกดังแล้ว";
			else if($po_status == "04")
				$txt_status = "QC ปิ่นเกล้าตรวจสอบเอกสารจากโกดังเรียบร้อยแล้ว";
			else if($po_status == "05")
				$txt_status = "QC ปิ่นเกล้าให้ทางโกดัง แก้ไขเอกสาร GAR";
			else if($po_status == "06")
				$txt_status = "โกดัง แก้ไขเอกสารตามที่  QC  ปิ่นเกล้า โรงงานต้องการ";
			else if($po_status == "11")
				$txt_status = "QC โรงงานตรวจสอบเอกสารจากโกดังเรียบร้อยแล้ว";
			else if($po_status == "12")
				$txt_status = "สินค้าผ่านการตรวจสอบคุณภาพทั้งหมด";
			else if($po_status == "13")
				$txt_status = "สินค้าผ่านการ QC แล้ว รอผู้จัดการพิจารณา";
			else if($po_status == "14")
				$txt_status = "QC ตรวจสอบเอกสารจากโกดังเรียบร้อยแล้ว";
			else if($po_status == "15")
				$txt_status = "QC ให้ทางโกดัง แก้ไขเอกสาร GAR";
			else if($po_status == "16")
				$txt_status = "โกดัง แก้ไขเอกสารตามที่ QC โรงงานต้องการ";
			else if($po_status == "17")
				$txt_status = "โกดังบันทึกการส่งสินค้าแล้ว รอสต็อกรับเข้าสินค้า";
			else if($po_status == "21")
				$txt_status = "ผู้จัดการอนุมัติให้สินค้าผ่านเกณฑ์คุณภาพสินค้าที่ิพิจารณาทั้งหมด";
			else if($po_status == "22")
				$txt_status = "ผู้จัดการมีการส่งคืนสินค้าที่พิจารณาทั้งหมด";
			else if($po_status == "23")
				$txt_status = "ผู้จัดการมีการส่งคืนสินค้าที่พิจารณาบางรายการ";
			else if($po_status == "30")
				$txt_status = "สต๊อกรับเข้าสินค้าบางส่วนแล้ว";
			else if($po_status == "31")
				$txt_status = "สต๊อกทำรับเข้าสินค้าครบแล้ว รอโกดังบันทึกส่งคืนสินค้าที่ไม่ผ่านคุณภาพ";
			else if($po_status == "32")
				$txt_status = "สต๊อกให้ทางโกดังแก้ไขเอกสาร";
			else if($po_status == "33")
				$txt_status = "โกดัง แก้ไขเอกสารให้ตามที่ สต๊อกต้องการ";
			else if($po_status == "41")
				$txt_status = "QC ปิ่นเกล้าอนุมัติให้สินค้าผ่านเกณฑ์ที่พิจารณาทั้งหมด";
			else if($po_status == "42")
				$txt_status = "QC ปิ่นเกล้ามีการส่งคืนสินค้าที่พิจารณาทั้งหมด";
			else if($po_status == "43")
				$txt_status = "QC ปิ่นเกล้ามีการส่งคืนสินค้าที่พิจารณาบางรายการ";
			else if($po_status == "98")
				$txt_status = "GAR ปิดแล้ว [มีการส่งคืนสินค้า]";
			else if($po_status == "99")
				$txt_status = "GAR ปิดแล้ว [เข้า Inventory ทุกรายการ]";
			else if($po_status == "XX")
				$txt_status = "GAR ยกเลิก";
			else if($po_status == "SE")
				$txt_status = "Stock มีการแก้ไขข้อมูล GAR ที่ปิดแล้ว";
			$y = 1;
			
			if ($v_bomcodeshow != $codeshow)
			{
				if ($v_po_no != $po_no)
				{
					if($po_no != ""){
							$cell = $sheet->Cells($i,$y);	
							$cell->activate;	
							$cell->WrapText = True;	
							$cell->NumberFormat = "@";
							$cell->value = $po_no;		
					}			
				}
				$y++;	
				if($codeshow != ""){
							$cell = $sheet->Cells($i,$y);	
							$cell->activate;	
							$cell->WrapText = True;	
							$cell->NumberFormat = "@";
							$cell->value = $codeshow;		
				}	
				$y++;					
				if($po_date != ""){
						$cell = $sheet->Cells($i,$y);	
						$cell->activate;	
						$cell->WrapText = True;	
						$cell->NumberFormat = "@";
						$cell->value = $po_date;		
				}	
				$y++;				
				if($order_qty != ""){
							$cell = $sheet->Cells($i,$y);	
							$cell->activate;	
							$cell->WrapText = True;	
							$cell->NumberFormat = "0.00";
							$cell->value = number_format($order_qty,2,".",",");	
				}		
				$y++;				
				if($company_name != ""){
							$cell = $sheet->Cells($i,$y);	
							$cell->activate;	
							$cell->WrapText = True;	
							$cell->NumberFormat = "@";
							$cell->value = $company_name;	
				}			
				$y++;	
			}
			else
				$y = $y +  5;
			if($gar_no != ""){
						$cell = $sheet->Cells($i,$y);	
						$cell->activate;	
						$cell->WrapText = True;	
						$cell->NumberFormat = "@";
						$cell->value = $gar_no;	
			}		
			$y++;			
			if($gar_date != ""){
						$cell = $sheet->Cells($i,$y);	
						$cell->activate;	
						$cell->WrapText = True;	
						$cell->NumberFormat = "@";
						$cell->value = $gar_date;		
			}	
			$y++;		
			if($txt_status != ""){
						$cell = $sheet->Cells($i,$y);	
						$cell->activate;	
						$cell->WrapText = True;	
						$cell->NumberFormat = "@";
						$cell->value = $txt_status;	
			}		
			$y++;						
			if($RECEIVE_QTY != ""){
						$cell = $sheet->Cells($i,$y);	
						$cell->activate;	
						$cell->WrapText = True;	
						$cell->NumberFormat = "0.00";
						$cell->value = number_format($RECEIVE_QTY,2,".",",");						
			}				
			$y++;
			$i++;
			$v_bomcodeshow = $codeshow;
			$v_po_no = $po_no;
		} // End Loop  Job ????
		$i--;
		$all = $sheet->Range("A3:I$i");
		$all->activate;
		$all->HorizontalAlignment = 2;		
		$all->VerticalAlignment = 1;
		$all->Borders->LineStyle = 1;	
	// ??????????
		$dirname = "C:\WebServ\webroot\include\\temp_report\\";
		$dirname2 = "http://".$_SERVER['HTTP_HOST']."/include/temp_report/";
		$strTime = time();
		$strDate = date("d-m-Y");
		$filename = "report_bill-".$strDate."(".$strTime.").xls";
		
		$book->saveas($dirname.$filename);
		$book->Close(false);
		unset($sheet);
		unset($book);
		$excel->Workbooks->Close();
		$excel->Quit();
		unset($excel);
		echo '<script language="JavaScript" type="text/JavaScript">';
		echo '		location.href ="'.$dirname2.$filename.'";';
		echo '</script>';
}else{
	include("index.php");
	echo '<script language="JavaScript" type="text/JavaScript">';
	echo 'alert ("????????? ??????????? Login");';
	echo '</script>';
}
?>

