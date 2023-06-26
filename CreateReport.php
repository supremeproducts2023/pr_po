<?php
if (isset($_SESSION["empno_user"])) {
	function CreatePO($doc_no, $report_type)
	{	// report_type :  1=all, 2=nonprice, 3=nontail
		// Orcle Connect
		include("../include_RedThemes/MSSQLServer_connect_2.php");
		//------------------------- mysql : Connect ----------------------------------
		include("../include_RedThemes/mysql_connect.php");
		@require_once("../include_RedThemes/funcShow.php");



		//------------------------- ลบข้อมูล ในตาราง po_master , po_details ------------------------- 
		//mysql_query("delete from po_master", $conn_mysql);
		//mysql_query("delete from po_details", $conn_mysql);
		odbc_exec($conn_mysql, "delete from po_master");
		odbc_exec($conn_mysql, "delete from po_details");

		$strPoMaster = "select format(p.po_date,'yyyy-MM-dd') po_date,
						s.company_name suppliername,s.supplier_address1, s.supplier_address2,
						s.tambol, s.district , s.province, s.postcode, s.country,
						s.supplier_address3, s.supplier_address3_1, s.fax_number, s.supplier_title,
						your_ref,our_ref,despatch_to,delivery_time,payment,
						discount_percent,discount_baht,flag_vat,
						p.accid,p.costid,p.for_ref,po_remark,							
						p.redhead,p.po_status,p.po_company
						from po_master p
						left join supplier s  on  p.supplier_id=s.supplier_id
						where p.po_no='$doc_no'";

		$curPoMaster = odbc_exec($conn, $strPoMaster);

		$po_date = odbc_result($curPoMaster, "po_date");
		$suppliername = odbc_result($curPoMaster, "supplier_title") . " " . odbc_result($curPoMaster, "suppliername");
		$supplier_address1 = odbc_result($curPoMaster, "supplier_address1");
		$supplier_address2 = odbc_result($curPoMaster, "supplier_address2");
		$supplier_address1 = $supplier_address1 . " " . $supplier_address2;
		$province = odbc_result($curPoMaster, "province");
		$tambol = odbc_result($curPoMaster, "tambol");
		$district = odbc_result($curPoMaster, "district");
		$postcode = odbc_result($curPoMaster, "postcode");
		$supplier_address3 = odbc_result($curPoMaster, "supplier_address3");
		$supplier_address3_1 = odbc_result($curPoMaster, "supplier_address3_1");
		$fax_number = odbc_result($curPoMaster, "fax_number");
		$country = odbc_result($curPoMaster, "country");
		$po_company = odbc_result($curPoMaster, "po_company");

		$supplier_address2 = "";
		if ($province == "กรุงเทพฯ") {
			if ($tambol != "") {
				$supplier_address2 .= "แขวง" . $tambol;
			}
			if ($district != "") {
				if ($supplier_address2 == "")
					$supplier_address2 = "เขต" . $district;
				else $supplier_address2 .= " เขต" . $district;
			}
		} else {
			if ($tambol != "")
				$supplier_address2 .= "ตำบล" . $tambol;
			if ($district != "") {
				if ($supplier_address2 == "")
					$supplier_address2 = "อำเภอ" . $district;
				else 	$supplier_address2 .= " อำเภอ" . $district;
			}
		}
		if ($province != "") {
			if ($supplier_address2 == "")
				$supplier_address2 = "จังหวัด" . $province;
			else 	$supplier_address2 .= " จังหวัด" . $province;
		}
		if ($postcode != "") {
			if ($supplier_address2 == "")
				$supplier_address2 = "รหัสไปรษณีย์ " . $postcode;
			else 	$supplier_address2 .= " รหัสไปรษณีย์ " . $postcode;
		}
		if ($country != "") {
			if ($supplier_address2 == "")
				$supplier_address2 = $country;
			else 	$supplier_address2 .= " " . $country;
		}
		if ($supplier_address3 != "")
			$supplier_address3 = "เบอร์โทรศัพท์ : " . $supplier_address3;
		if ($supplier_address3_1 != "") {
			if ($supplier_address3 != "")
				$supplier_address3 .= ", " . $supplier_address3_1;
			else	$supplier_address3 = "เบอร์โทรศัพท์ : " . $supplier_address3_1;
		}
		if ($fax_number != "") {
			if ($supplier_address3 != "")
				$supplier_address3 .= " เบอร์แฟกซ์ : " . $fax_number;
			else	$supplier_address3 = "เบอร์แฟกซ์ : " . $fax_number;
		}
		$your_ref = odbc_result($curPoMaster, "your_ref");
		$our_ref = odbc_result($curPoMaster, "our_ref");
		$despatch_to = odbc_result($curPoMaster, "despatch_to");
		$delivery_time = odbc_result($curPoMaster, "delivery_time");
		$payment = odbc_result($curPoMaster, "payment");
		$discount_percent = odbc_result($curPoMaster, "discount_percent");
		$discount_baht = odbc_result($curPoMaster, "discount_baht");
		$flag_vat = odbc_result($curPoMaster, "flag_vat");
		$accid = odbc_result($curPoMaster, "accid");
		$costid = odbc_result($curPoMaster, "costid");
		$for_ref = odbc_result($curPoMaster, "for_ref");
		$po_remark = odbc_result($curPoMaster, "po_remark");
		$redhead = odbc_result($curPoMaster, "redhead");
		$po_status = odbc_result($curPoMaster, "po_status");

		// ------------------------- Insert Master ลง MySQL ------------------------- 
		$strInsPoMaster = "insert into po_master (
							po_no,po_date,
							suppliername,supplier_address1,supplier_address2,supplier_address3,
							your_ref,our_ref,despatch_to,delivery_time,payment,					
							discount_percent,discount_baht,
							redhead,po_status,flag_report,po_company
						) values(
							'$doc_no'," . chkINSMySQL($po_date) . ",
							" . chkINSMySQL($suppliername) . "," . chkINSMySQL($supplier_address1) . "," . chkINSMySQL($supplier_address2) . ",
							" . chkINSMySQL($supplier_address3) . "," . chkINSMySQL($your_ref) . "," . chkINSMySQL($our_ref) . ",
							" . chkINSMySQL($despatch_to) . "," . chkINSMySQL($delivery_time) . "," . chkINSMySQL($payment) . ",						
							" . chkINSMySQL($discount_percent) . "," . chkINSMySQL($discount_baht) . "," . chkINSMySQL($redhead) . ",
							" . chkINSMySQL($po_status) . "," . chkINSMySQL($report_type) . "," . chkINSMySQL($po_company) . ")";
		//			echo  $strInsPoMaster.'<br>';
		//mysql_query($strInsPoMaster, $conn_mysql);
		odbc_exec($conn_mysql, $strInsPoMaster);

		// Select Detail จาก Oracle
		$strPoDetails = "select id,prod_no,prod_name,prod_type,show_id,
														prod_qty,prod_price,prod_unit,
														gar_qty,gar_price,gar_unit,
														isnull(discount_baht,0) discount_baht_d
											from po_details 
											where po_no='$doc_no' order by id";
		$curPoDetails = odbc_exec($conn, $strPoDetails);
		// ------------------------- Insert Detail ลง MySQL ------------------------- 
		$sum_price = 0;
		while (odbc_fetch_row($curPoDetails)) {
			$id = odbc_result($curPoDetails, "id");
			$prod_no = odbc_result($curPoDetails, "prod_no");
			$prod_name = odbc_result($curPoDetails, "prod_name");
			$prod_type = odbc_result($curPoDetails, "prod_type");
			$show_id = odbc_result($curPoDetails, "show_id");

			$prod_qty = odbc_result($curPoDetails, "prod_qty");
			$prod_unit = odbc_result($curPoDetails, "prod_unit");
			$prod_price = odbc_result($curPoDetails, "prod_price");

			$gar_qty = odbc_result($curPoDetails, "gar_qty");
			$gar_unit = odbc_result($curPoDetails, "gar_unit");
			$gar_price = odbc_result($curPoDetails, "gar_price");

			$discount_baht_d = odbc_result($curPoDetails, "discount_baht_d");


			if ((($prod_price * $prod_qty) - $discount_baht_d) == 0) $prod_price = 0;
			else $prod_price = (($prod_price * $prod_qty) - $discount_baht_d) / $prod_qty;

			$total_price = $prod_price * $prod_qty;
			$sum_price += $total_price;


			$strInsPoDetails = "insert into po_details (
														po_no,id,prod_no,prod_name,prod_type,
														prod_qty,prod_unit,prod_price,
														gar_qty,gar_unit,gar_price,show_id
													) values(
														'$doc_no','$id'," . chkINSMySQL($prod_no) . "," . chkINSMySQL($prod_name) . "," . chkINSMySQL($prod_type) . ",
														" . chkINSMySQL($prod_qty) . "," . chkINSMySQL($prod_unit) . "," . chkINSMySQL($prod_price) . ",
														" . chkINSMySQL($gar_qty) . "," . chkINSMySQL($gar_unit) . "," . chkINSMySQL($gar_price) . "," . chkINSMySQL($show_id) . "
														) ";
																							
			//mysql_query($strInsPoDetails, $conn_mysql);
			odbc_exec($conn_mysql, $strInsPoDetails);		
		}
		$price = $sum_price;

		// ------------------------- คำนวนเรื่อง ส่วนลด & VAT -------------------------
		if ($report_type != "2") {	// !=nonprice
			$i = 990;
			if ($flag_vat == 1) { // คำนวณ VAT
				if ($discount_baht != 0) {  //มีส่วนลด
					//mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ");		
					$i = $i + 1;
					//mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")");		
					$i = $i + 1;
					$price = $price - $discount_baht;
				}

				//mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ")", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ")");
				$i = $i + 1;
				if ($price == 0) $vat = 0;
				else $vat = $price * 7 / 100;

				//mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ภาษีมูลค่าเพิ่ม 7%'," . chkINSMySQL($vat) . ")", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ภาษีมูลค่าเพิ่ม 7%'," . chkINSMySQL($vat) . ")");		
				$i = $i + 1;
				$price = $price + $vat;

				//mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")");
			} else if ($flag_vat == 0) { // ไม่คำนวณ VAT
				if ($discount_baht != 0) {  //มีส่วนลด

					//mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ");	
					$i = $i + 1;

					//mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")");	
					$i = $i + 1;
					$price = $price - $discount_baht;
				}
				// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")");	
				$i = $i + 1;
				// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','(ราคานี้รวมภาษีมูลค่าเพิ่มแล้ว)',NULL)", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','(ราคานี้รวมภาษีมูลค่าเพิ่มแล้ว)',NULL)");	
			} else if ($flag_vat == 2) { // ไม่อยู่ในระบบ
				if ($discount_baht != 0) {  //มีส่วนลด
					// mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ");	
					$i = $i + 1;
					// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")");	
					$i = $i + 1;
					$price = $price - $discount_baht;
				}
				// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")");	
				$i = $i + 1;
				// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','(ไม่มี VAT เพราะไม่ได้อยู่ในระบบภาษีมูลค่าเพิ่ม)',NULL)", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','(ไม่มี VAT เพราะไม่ได้อยู่ในระบบภาษีมูลค่าเพิ่ม)',NULL)");	
			} else if ($flag_vat == 3) { // ไม่แสดง
				if ($discount_baht != 0) {  //มีส่วนลด
					// mysql_query("insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_price) values('$doc_no','$i'," . chkINSMySQL($price) . ") ");	
					$i = $i + 1;
					// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")", $conn_mysql);
					odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','ส่วนลด $discount_percent'," . chkINSMySQL($discount_baht) . ")");	
					$i = $i + 1;
					$price = $price - $discount_baht;
				}
				// mysql_query("insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")", $conn_mysql);
				odbc_exec($conn_mysql, "insert into po_details (po_no,id,prod_name,prod_price) values('$doc_no','$i','จำนวนเงินรวมทั้งสิ้น'," . chkINSMySQL($price) . ")");	
				$i = $i + 1;
			}
		}

		//------------------------- Insert ข้อมูล  acc_id,cost_id,for_ref และ po_remark -------------------------
		if (($accid != '') || ($costid != '') || ($for_ref != '') || ($po_remark != '')) {
			$strInsPoDetails = "insert into po_details (po_no,id,prod_name) values('$doc_no','995','') ";
			// mysql_query($strInsPoDetails, $conn_mysql);
			odbc_exec($conn_mysql, $strInsPoDetails);
		}

		if ($report_type != "3") { 	// !=nontail
			if ($accid != '') {
				$strInsPoDetails = "insert into po_details (po_no,id,prod_name) values('$doc_no','996','$accid') ";
				// mysql_query($strInsPoDetails, $conn_mysql);
				odbc_exec($conn_mysql, $strInsPoDetails);
			}
			if ($costid != '') {
				$strInsPoDetails = "insert into po_details (po_no,id,prod_name) values('$doc_no','997','$costid') ";
				// mysql_query($strInsPoDetails, $conn_mysql);
				odbc_exec($conn_mysql, $strInsPoDetails);
			}
			if ($for_ref != '') {
				$strInsPoDetails = "insert into po_details (po_no,id,prod_name) values('$doc_no','998','$for_ref') ";
				// mysql_query($strInsPoDetails, $conn_mysql);
				odbc_exec($conn_mysql, $strInsPoDetails);
			}
		}

		if ($po_remark != '') {
			$strInsPoDetails = "insert into po_details (po_no,id,prod_name) values('$doc_no','999','$po_remark') ";
			// mysql_query($strInsPoDetails, $conn_mysql);
			odbc_exec($conn_mysql, $strInsPoDetails);
		}

		//------------------------- mysql : Disconnect ----------------------------------
		// mysql_close($conn_mysql);
	}
} else {
	include("../include_RedThemes/SessionTimeOut.php");
}
