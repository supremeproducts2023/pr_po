<?
@session_start();
if(session_is_registered("empno_user")) {
	function CreatePO($doc_no,$report_type){	// report_type :  1=all, 2=nonprice, 3=nontail
		// Orcle Connect
			include("../include_RedThemes/odbc_connect.php");				
		//------------------------- mysql : Connect ----------------------------------
			include("../include_RedThemes/mysql_connect.php");								
			@require_once("../include_RedThemes/funcShow.php");			
 
	 							
								
		//------------------------- ź������ 㹵��ҧ po_master , po_details ------------------------- 
			mysql_query("delete from po_master",$conn_mysql);
			mysql_query("delete from po_details",$conn_mysql);

		// Select Master �ҡ Oracle		
/*			$strPoMaster = "select to_char(p.po_date,'YYYY-MM-DD') po_date,
						s.company_name suppliername,(s.supplier_address1 || ', ' || s.supplier_address2) supplier_address1,
						('�Ӻ�' || s.tambol || ' �����' ||  s.district || ' �ѧ��Ѵ' || s.province || ' ������ɳ��� ' || s.postcode) supplier_address2,
						('�������Ѿ��: ' || s.supplier_address3 || ', ' || s.supplier_address3_1 || ' ῡ�� : ' || s.fax_number ) supplier_address3,s.supplier_title,
						your_ref,our_ref,despatch_to,delivery_time,payment,
						discount_percent,discount_baht,flag_vat,
						p.accid,p.costid,p.for_ref,po_remark,							
						p.redhead,p.po_status					
						from po_master p,supplier s 
						where p.supplier_id=s.supplier_id(+) 
						and p.po_no='$doc_no'"; */

				$strPoMaster = "select to_char(p.po_date,'YYYY-MM-DD') po_date,
						s.company_name suppliername,s.supplier_address1, s.supplier_address2,
						s.tambol, s.district , s.province, s.postcode, s.country,
						s.supplier_address3, s.supplier_address3_1, s.fax_number, s.supplier_title,
						your_ref,our_ref,despatch_to,delivery_time,payment,
						discount_percent,discount_baht,flag_vat,
						p.accid,p.costid,p.for_ref,po_remark,							
						p.redhead,p.po_status,p.po_company
						from po_master p,supplier s 
						where p.supplier_id=s.supplier_id(+) 
						and p.po_no='$doc_no'";
												
			$curPoMaster = odbc_exec($conn,$strPoMaster);	
	
			$po_date=odbc_result($curPoMaster, "po_date");
			$suppliername=odbc_result($curPoMaster, "supplier_title")." ".odbc_result($curPoMaster, "suppliername");
			$supplier_address1=odbc_result($curPoMaster, "supplier_address1");			
			$supplier_address2=odbc_result($curPoMaster, "supplier_address2");
			$supplier_address1 = $supplier_address1." ".$supplier_address2;
			$province = odbc_result($curPoMaster,"province");
			$tambol = odbc_result($curPoMaster,"tambol");
			$district = odbc_result($curPoMaster,"district");
			$postcode = odbc_result($curPoMaster,"postcode");			
			$supplier_address3=odbc_result($curPoMaster, "supplier_address3");
			$supplier_address3_1=odbc_result($curPoMaster, "supplier_address3_1");
			$fax_number = odbc_result($curPoMaster,"fax_number");
			$country = odbc_result($curPoMaster,"country");
			$po_company = odbc_result($curPoMaster,"po_company");
			
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
					$supplier_address3 .= " ����ῡ�� : ".$fax_number;	
				else	$supplier_address3 = "����ῡ�� : ".$fax_number;
			}
			$your_ref=odbc_result($curPoMaster, "your_ref");
			$our_ref=odbc_result($curPoMaster, "our_ref");
			$despatch_to=odbc_result($curPoMaster, "despatch_to");
			$delivery_time=odbc_result($curPoMaster, "delivery_time");
			$payment=odbc_result($curPoMaster, "payment");
			$discount_percent=odbc_result($curPoMaster, "discount_percent");
			$discount_baht=odbc_result($curPoMaster, "discount_baht");
			$flag_vat=odbc_result($curPoMaster, "flag_vat");
			$accid=odbc_result($curPoMaster, "accid");
			$costid=odbc_result($curPoMaster, "costid");
			$for_ref=odbc_result($curPoMaster, "for_ref");
			$po_remark=odbc_result($curPoMaster, "po_remark");
			$redhead=odbc_result($curPoMaster, "redhead");
			$po_status=odbc_result($curPoMaster, "po_status");
			
		// ------------------------- Insert Master ŧ MySQL ------------------------- 
			$strInsPoMaster ="insert into po_master (
							po_no,po_date,
							suppliername,supplier_address1,supplier_address2,supplier_address3,
							your_ref,our_ref,despatch_to,delivery_time,payment,					
							discount_percent,discount_baht,
							redhead,po_status,flag_report,po_company
						) values(
							'$doc_no',".chkINSMySQL($po_date).",
							".chkINSMySQL($suppliername).",".chkINSMySQL($supplier_address1).",".chkINSMySQL($supplier_address2).",
							".chkINSMySQL($supplier_address3).",".chkINSMySQL($your_ref).",".chkINSMySQL($our_ref).",
							".chkINSMySQL($despatch_to).",".chkINSMySQL($delivery_time).",".chkINSMySQL($payment).",						
							".chkINSMySQL($discount_percent).",".chkINSMySQL($discount_baht).",".chkINSMySQL($redhead).",
							".chkINSMySQL($po_status).",".chkINSMySQL($report_type).",".chkINSMySQL($po_company).")";
//			echo  $strInsPoMaster.'<br>';
			mysql_query($strInsPoMaster,$conn_mysql);
		// Select Detail �ҡ Oracle
			$strPoDetails = "select id,prod_no,prod_name,prod_type,show_id,
														prod_qty,prod_price,prod_unit,
														gar_qty,gar_price,gar_unit,
														nvl(discount_baht,0) discount_baht_d
											from po_details 
											where po_no='$doc_no' order by id";
			$curPoDetails = odbc_exec($conn,$strPoDetails);	
		// ------------------------- Insert Detail ŧ MySQL ------------------------- 
			$sum_price=0;
			while(odbc_fetch_row($curPoDetails)){ 
				$id=odbc_result($curPoDetails, "id");	
				$prod_no=odbc_result($curPoDetails, "prod_no");	
				$prod_name=odbc_result($curPoDetails, "prod_name");	
				$prod_type=odbc_result($curPoDetails, "prod_type");	
				$show_id=odbc_result($curPoDetails, "show_id");	
				
				$prod_qty=odbc_result($curPoDetails, "prod_qty");	
				$prod_unit=odbc_result($curPoDetails, "prod_unit");	
				$prod_price=odbc_result($curPoDetails, "prod_price");	
				
				$gar_qty=odbc_result($curPoDetails, "gar_qty");	
				$gar_unit=odbc_result($curPoDetails, "gar_unit");	
				$gar_price=odbc_result($curPoDetails, "gar_price");	
								
				$discount_baht_d=odbc_result($curPoDetails, "discount_baht_d");	
				

						if((($prod_price*$prod_qty)-$discount_baht_d)==0) $prod_price = 0;
						else $prod_price = (($prod_price*$prod_qty)-$discount_baht_d)/$prod_qty;
						
						$total_price = $prod_price * $prod_qty;
						$sum_price += $total_price;										

			
				$strInsPoDetails ="insert into po_details (
														po_no,id,prod_no,prod_name,prod_type,
														prod_qty,prod_unit,prod_price,
														gar_qty,gar_unit,gar_price,show_id
													) values(
														'$doc_no','$id',".chkINSMySQL($prod_no).",".chkINSMySQL($prod_name).",".chkINSMySQL($prod_type).",
														".chkINSMySQL($prod_qty).",".chkINSMySQL($prod_unit).",".chkINSMySQL($prod_price).",
														".chkINSMySQL($gar_qty).",".chkINSMySQL($gar_unit).",".chkINSMySQL($gar_price).",".chkINSMySQL($show_id)."
														) ";
				
				mysql_query($strInsPoDetails,$conn_mysql);
			}
			$price=$sum_price;
	
		// ------------------------- �ӹǹ����ͧ ��ǹŴ & VAT -------------------------
if($report_type!="2"){	// !=nonprice
			$i = 2000;
			if($flag_vat==1){ // �ӹǳ VAT
				if($discount_baht!=0){  //����ǹŴ
					mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i',".chkINSMySQL($price).") ",$conn_mysql); 
					$i = $i+1;		
					mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','��ǹŴ $discount_percent',".chkINSMySQL($discount_baht).")",$conn_mysql);	 
					$i = $i+1;			
					$price = $price-$discount_baht;
				}
				
				mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i',".chkINSMySQL($price).")",$conn_mysql);	 
				$i = $i+1;	
				if($price==0)$vat=0; else $vat = $price*7/100;
				
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','������Ť������ 7%',".chkINSMySQL($vat).")",$conn_mysql);	 
				$i = $i+1;		
				$price = $price+$vat;
				
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','�ӹǹ�Թ���������',".chkINSMySQL($price).")",$conn_mysql);													
			}else if($flag_vat==0){ // ���ӹǳ VAT
				if($discount_baht!=0){  //����ǹŴ
				
					mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i',".chkINSMySQL($price).") ",$conn_mysql); 
					$i = $i+1;		

					mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','��ǹŴ $discount_percent',".chkINSMySQL($discount_baht).")",$conn_mysql);	 
					$i = $i+1;			
					$price = $price-$discount_baht;
				}
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','�ӹǹ�Թ���������',".chkINSMySQL($price).")",$conn_mysql);	
				$i = $i+1;			
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','(�Ҥҹ�����������Ť����������)',NULL)",$conn_mysql);													
			}else if($flag_vat==2){ // ���������к�
				if($discount_baht!=0){  //����ǹŴ
					mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i',".chkINSMySQL($price).") ",$conn_mysql); 
					$i = $i+1;		
					mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','��ǹŴ $discount_percent',".chkINSMySQL($discount_baht).")",$conn_mysql);	 
					$i = $i+1;			
					$price = $price-$discount_baht;
				}
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','�ӹǹ�Թ���������',".chkINSMySQL($price).")",$conn_mysql);	 
				$i = $i+1;			
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','(����� VAT ���������������к�������Ť������)',NULL)",$conn_mysql);													
			}else if($flag_vat==3){ // ����ʴ�
				if($discount_baht!=0){  //����ǹŴ
					mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i',".chkINSMySQL($price).") ",$conn_mysql); $i = $i+1;		
					mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','��ǹŴ $discount_percent',".chkINSMySQL($discount_baht).")",$conn_mysql);	 
					$i = $i+1;			
					$price = $price-$discount_baht;
				}
				mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','�ӹǹ�Թ���������',".chkINSMySQL($price).")",$conn_mysql);	 
				$i = $i+1;			
			}
}
		
		//------------------------- Insert ������  acc_id,cost_id,for_ref ��� po_remark -------------------------
			if(($accid !='')||($costid !='')||($for_ref !='')||($po_remark !='')){
				$strInsPoDetails ="insert into po_details (po_no,id,prod_name) values('$doc_no','2005','') ";
				mysql_query($strInsPoDetails,$conn_mysql);	
			}
			
			if($report_type!="3"){ 	// !=nontail
					if($accid !=''){
						$strInsPoDetails ="insert into po_details (po_no,id,prod_name) values('$doc_no','2006','$accid') ";
						mysql_query($strInsPoDetails,$conn_mysql);	
					}
					if($costid !=''){
						$strInsPoDetails ="insert into po_details (po_no,id,prod_name) values('$doc_no','2007','$costid') ";
						mysql_query($strInsPoDetails,$conn_mysql);	
					}
					if($for_ref !=''){
						$strInsPoDetails ="insert into po_details (po_no,id,prod_name) values('$doc_no','2008','$for_ref') ";
						mysql_query($strInsPoDetails,$conn_mysql);	
					}
			}		

			if($po_remark != ''){
				$strInsPoDetails ="insert into po_details (po_no,id,prod_name) values('$doc_no','2009','$po_remark') ";
				mysql_query($strInsPoDetails,$conn_mysql);	
			}
			
		//------------------------- mysql : Disconnect ----------------------------------
			mysql_close($conn_mysql);
	}
	
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>

