<html xmlns="http://www.w3.org/1999/xhtml">
<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		$keyword= @$_GET["keyword"];

		require_once("../include_RedThemes/odbc_connect.php");				
		$strSupplierQUE = "select supplier_id,company_name,supplier_address1,supplier_title,
															supplier_address2,supplier_address3,supplier_address3_1,
															tambol,district,province,postcode,supplier_payment,fax_number,c.country 
															from supplier s
															left join cushos_country c on s.country = c.id
															where 1 = 1 ";
		if(@$keyword != '') $strSupplierQUE .= "and  upper(company_name) like upper('%$keyword%')  ";			
		$strSupplierQUE .= "order by  company_name";
		$curSupplierQUE= odbc_exec($conn,$strSupplierQUE);	
				
	// ���ҧ��� Excel
	$excel = new COM("Excel.Application");
	$excel->sheetsinnewworkbook=1;
	$excel->Workbooks->Add();		// ���ҧ������� ����Դ��������  �ѹ���᷹ $excel->Workbooks->Open();
	$book = $excel->Workbooks(1);	// ��Ҷ֧ work book ���� $book ᷹
	$sheet = $book->Worksheets(1);  	// ��Ҷ֧ work sheet ���� $sheet ᷹  (1 Workbooks = 1-255 Worksheets)
	$sheet->Name="Approval Supplier List";			// ��駪������ Sheet ����

	// Set Font ��� Sheet
	$all = $sheet->Cells();
	$all->activate;
	$all->Font->Name = "Tahoma";
	$all->Font->Size = 8;
	$all->WrapText = true;
	$all->VerticalAlignment = 1;

	// ��駡�д��
	$sheet->PageSetup->RightHeader="�ѹ��� ".date("d/m/Y")."\n˹�� &P";
	$sheet->PageSetup->PaperSize=1;
	$sheet->PageSetup->LeftMargin =10;
	$sheet->PageSetup->RightMargin = 10;
	$sheet->PageSetup->BottomMargin = 10;
	$sheet->PageSetup->Orientation=1;
	$sheet->PageSetup->PrintTitleRows = "$1:$2";

		// ��� Column
		$cell = $sheet->Range($sheet->Cells(1,1),$sheet->Cells(1,3));	
		$cell->activate;	 					$cell->MergeCells = true;				$cell->Font->Size = 11;		
		$cell->ColumnWidth = 27;		$cell->HorizontalAlignment = 3;		$cell->value = "Approval Supplier List";
		
		$cell = $sheet->Cells(2,1);	$cell->activate;	 $cell->ColumnWidth = 27;		$cell->HorizontalAlignment = 3;		$cell->value = "���� Supplier";
		$cell = $sheet->Cells(2,2);	$cell->activate;	 $cell->ColumnWidth = 50;		$cell->HorizontalAlignment = 3;		$cell->value = "������� Supplier";
		$cell = $sheet->Cells(2,3);	$cell->activate;	 $cell->ColumnWidth = 26;		$cell->HorizontalAlignment = 3;		$cell->value = "���͹�";
		$i= 2;
  				while(odbc_fetch_row($curSupplierQUE)){
				$i++;
						$supplier_id = odbc_result($curSupplierQUE, "supplier_id");
						$company_name = odbc_result($curSupplierQUE, "company_name");
						$supplier_address1 = odbc_result($curSupplierQUE, "supplier_address1");
						$supplier_address2 = odbc_result($curSupplierQUE, "supplier_address2");
						$supplier_address3 = odbc_result($curSupplierQUE, "supplier_address3");
						$supplier_payment = odbc_result($curSupplierQUE, "supplier_payment");
						$supplier_title =  odbc_result($curSupplierQUE, "supplier_title");
						$supplier_address3_1 = odbc_result($curSupplierQUE, "supplier_address3_1");
						$tambol = odbc_result($curSupplierQUE, "tambol");
						$district = odbc_result($curSupplierQUE, "district");
						$province = odbc_result($curSupplierQUE, "province");
						$fax_number = odbc_result($curSupplierQUE, "fax_number");
						$postcode = odbc_result($curSupplierQUE, "postcode");
						$country = odbc_result($curSupplierQUE, "country");
						
						if($supplier_title!='') $company_name = $supplier_title.' '.$company_name;						
						
						$supplier_address="";
						if($supplier_address1!=""&&$supplier_address2!="")
							$supplier_address1 = $supplier_address1.chr(10).chr(13).$supplier_address2;						
						else if($supplier_address1!=""&&$supplier_address2=="")	
							$supplier_address1 = $supplier_address1;						
						else	if($supplier_address1==""&&$supplier_address2=="")	
							$supplier_address1 = "";
						else	if($supplier_address1==""&&$supplier_address2!="")		
							$supplier_address1 = $supplier_address2;						
							
						if($supplier_address1!="")
							$supplier_address .= $supplier_address1;
							
						$supplier_address2 = "";
						if($province=="��ا෾�")
						{
							if($tambol!="")	{
								$supplier_address2 .= "�ǧ".$tambol;
							}
							if($district!="") {
								if($supplier_address2=="")
									$supplier_address2 = "ࢵ".$district;
								else $supplier_address2 .= " ࢵ".$district;
							}
						}else{
							if($tambol!="")
								$supplier_address2 .= "�Ӻ�".$tambol;
							if($district!="") {
								if($supplier_address2=="")
									$supplier_address2 = "�����".$district;
								else 	$supplier_address2 .= " �����".$district;
							}
						}	
						if($province!=""){
							if($supplier_address2=="")
								$supplier_address2 = "�ѧ��Ѵ".$province;					
							else 	$supplier_address2 .= " �ѧ��Ѵ".$province;					
						}
						if($postcode!=""){
							if($supplier_address2=="")
								$supplier_address2 = "������ɳ��� ".$postcode;					
							else 	$supplier_address2 .= " ������ɳ��� ".$postcode;					
						}
						if($country!=""){
							if($supplier_address2=="")
								$supplier_address2 = $country;
							else 	$supplier_address2 .= " ".$country;
						}
						if($supplier_address2!="")
						{
							if($supplier_address!="")
								$supplier_address .=  chr(10).chr(13).$supplier_address2;
							else $supplier_address = $supplier_address2;
						}
								
						if($supplier_address3!="")
							$supplier_address3 = "�������Ѿ�� : ".$supplier_address3;
						if($supplier_address3_1!="")
						{	
							if($supplier_address3 != "")
								$supplier_address3 .= ", ".$supplier_address3_1;	
							else	$supplier_address3 = "�������Ѿ�� : ".$supplier_address3_1;
						}
						if($fax_number!="")
						{
							if($supplier_address3 != "")
								$supplier_address3 .= chr(10).chr(13)."����ῡ�� : ".$supplier_address3_1;	
							else	$supplier_address3 = chr(10).chr(13)."����ῡ�� : ".$supplier_address3_1;
						}
						if($supplier_address3!="")
						{
							if($supplier_address!="")
								$supplier_address .=  chr(10).chr(13).$supplier_address3;
							else $supplier_address = $supplier_address3;
						}					
				if($company_name != ''){ 
						$cell = $sheet->Cells($i,1);		
						$cell->activate;		
						$cell->NumberFormat="@";			
						$cell->value = $company_name;	
				}
				if($supplier_address != ''){ 
						$cell = $sheet->Cells($i,2);		
						$cell->activate;		
						$cell->NumberFormat="@";			
						$cell->value = $supplier_address;	
				}
				if($supplier_payment != ''){ 
						$cell = $sheet->Cells($i,3);		
						$cell->activate;		
						$cell->NumberFormat="@";			
						$cell->value = $supplier_payment;	
				}
		}
		
		$cell = $sheet->Range($sheet->Cells(2,1),$sheet->Cells($i,3));	
		$cell->activate;			
		$cell->Borders->LineStyle = 1;	


	// ���¡�д��
	$dirname = "C:\WebServ\webroot\include\\temp_report\\";
	$dirname2 = "http://".$_SERVER['HTTP_HOST']."/include/temp_report/";
	$strTime = time();
	$strDate = date("d-m-Y");
	$filename = "Supplier-".$strDate."(".$strTime.").xls";
	
	$book->saveas($dirname.$filename);
	$book->Close(false);
	unset($sheet);
	unset($book);
	$excel->Workbooks->Close();
	$excel->Quit();
	unset($excel);
	echo '<script language="JavaScript" type="text/JavaScript">';
	echo 'window.location.reload("'.$dirname2.$filename.'");';
	echo '</script>';
				
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>
