<?php
@session_start();
if (isset($_SESSION["valid_userprpo"])) {
	require_once("../include_RedThemes/MSSQLServer_connect_2.php");
	//require_once("../include_RedThemes/MSSQLServer_connect.php");	
	require_once("../include/alert.php");
	$empno_user = $_SESSION["empno_user"];
	$ses_deptno = $_SESSION["ses_deptno"];
	$roles_user = $_SESSION["roles_user"];
	$http_host = '172.10.0.16';
	$PathtoUploadFile = "E:\\iso\\";
?>
	<html>

	<head>
		<title>*** PR ***</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>
		<script language='javascript' src='../include/check_inputtype.js'></script>
		<script language='javascript' src='./include/radio_check.js'></script>
		<script language='javascript' src='../include/popcalendar.js'></script>
		<script language='javascript' src='../include_RedThemes/funcPopUp.js'></script>
		<script language='javascript' src='../include/button_del.js'></script>

		<!-- Check Not null -->
		<script language='javascript'>
			function check_pr(obj, flag_obj) {
				if (obj.pr_date.value == "") {
					alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
					obj.pr_date.focus();
					return false;
				}
				if (flag_obj == "8") {
					if (obj.obj_name81.value == "") {
						alert("กรุณาระบุวัตถุประสงค์ด้วยค่ะ");
						return false;
					}
				} else {
					if ((!obj.flag_obj[0].checked) && (!obj.flag_obj[1].checked) && (!obj.flag_obj[2].checked) &&
						(!obj.flag_obj[3].checked) && (!obj.flag_obj[4].checked) && (!obj.flag_obj[5].checked) && (!obj.flag_obj[6].checked)) {
						alert("กรุณาเลือกวัตถุประสงค์ด้วยค่ะ");
						obj.flag_obj[0].focus();
						return false;
					}
					if ((obj.flag_obj[0].checked) && ((obj.obj_name11.value == "") || ((obj.obj_name12.value == "") && (obj.obj_name13.value == "")))) {
						alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
						obj.obj_name11.focus();
						return false;
					}
					if ((obj.flag_obj[1].checked) && ((obj.obj_name21.value == "") || (obj.obj_name22.value == ""))) {
						alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
						obj.obj_name21.focus();
						return false;
					}
					if ((obj.flag_obj[2].checked) && ((obj.obj_name31.value == "") || (obj.obj_name32.value == "") || (obj.obj_name33.value == ""))) {
						alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
						obj.obj_name31.focus();
						return false;
					}
					if ((obj.flag_obj[3].checked) && (obj.obj_name41.value == "")) {
						alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
						obj.obj_name41.focus();
						return false;
					}
					if ((obj.flag_obj[4].checked) && (obj.obj_name51.value == "")) {
						alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
						obj.obj_name51.focus();
						return false;
					}
					if ((obj.flag_obj[6].checked) && (obj.obj_name61.value == "")) {
						alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
						obj.obj_name61.focus();
						return false;
					}
				}

				if (obj.estimate_day.value == "") {
					alert("กรุณาระบุวันด้วยค่ะ");
					obj.estimate_day[0].focus();
					return false;
				}
				if ((!obj.vat_include[0].checked) && (!obj.vat_include[1].checked)) {
					alert("กรุณาระบุราคาสินค้าที่ ต้องการกรอกว่าเป็นราคา รวมหรือยังไม่รวม VAT ด้วยค่ะ");
					obj.vat_include[0].focus();
					return false;
				}
				if (obj.PayCode.value == "none") {
					alert("กรุณาระบุ Payment Term ด้วยค่ะ");
					obj.PayCode.focus();
					return false;
				}
				obj.submit();
			}
		</script>

		<!-- Calculate -->
		<script language='javascript'>
			function flag_obj_check(obj, ck_num) {
				if (ck_num != 1) {
					obj.obj_name11.value = '';
					obj.obj_name12.value = '';
					obj.obj_name13.value = '';
					obj.obj_name11.style.display = "none";
					obj.obj_name12.style.display = "none";
					obj.obj_name13.style.display = "none";
				}
				if (ck_num != 2) {
					obj.obj_name21.value = '';
					obj.obj_name22.value = '';
					obj.obj_name21.style.display = "none";
					obj.obj_name22.style.display = "none";
					obj.acc_but.style.display = "none";
				}
				if (ck_num != 3) {
					obj.obj_name31.value = '';
					obj.obj_name32.value = '';
					obj.obj_name33.value = '';
					obj.obj_name31.style.display = "none";
					obj.obj_name32.style.display = "none";
					obj.obj_name33.style.display = "none";
					obj.acc_but2.style.display = "none";
				}
				if (ck_num != 4) {
					obj.obj_name41.value = '';
					obj.obj_name41.style.display = "none";
				}
				if (ck_num != 5) {
					obj.obj_name51.value = '';
					obj.obj_name51.style.display = "none";
				}
				if (ck_num != 6) {
					obj.obj_name61.value = '';
					obj.obj_name61.style.display = "none";
				}

				if (ck_num == 1) {
					obj.obj_name11.style.display = "";
					obj.obj_name12.style.display = "";
					obj.obj_name13.style.display = "";
					obj.acc_but.style.display = "none";
					obj.acc_but2.style.display = "none";
				} else if (ck_num == 2) {
					obj.obj_name21.style.display = "";
					obj.obj_name22.style.display = "";
					obj.acc_but.style.display = "";
					obj.acc_but2.style.display = "none";
				} else if (ck_num == 3) {
					obj.obj_name31.style.display = "";
					obj.obj_name32.style.display = "";
					obj.obj_name33.style.display = "";
					obj.acc_but.style.display = "none";
					obj.acc_but2.style.display = "";
				} else if (ck_num == 4) {
					obj.obj_name41.style.display = "";
					obj.acc_but.style.display = "none";
					obj.acc_but2.style.display = "none";
				} else if (ck_num == 5) {
					obj.obj_name51.style.display = "";
					obj.acc_but.style.display = "none";
					obj.acc_but2.style.display = "none";
				} else if (ck_num == 6) {
					obj.obj_name61.style.display = "";
					obj.acc_but.style.display = "none";
					obj.acc_but2.style.display = "none";
				}
			}
		</script>

		<!-- LOV : Supplier -->
		<script type="text/javascript" language="javascript">
			function openSupplier() {
				returnvalue = window.showModalDialog('../include_RedThemes/lov/lov_search_supplier.php?themes=DefaultBlue', 'newWin', 'dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}

			function lovSupplier() {
				returnvalue = openSupplier();
				if (returnvalue != null) {
					var values = returnvalue.split("|-|");
					if (values[0] != "") {
						document.getElementById("supplier_id").value = values[0];
					} else {
						document.getElementById("supplier_id").value = '';
					}
					if (values[1] != "") {
						document.getElementById("supplier_show").value = values[1];
					} else {
						document.getElementById("supplier_show").value = '';
					}
					if (values[2] != "") {
						document.getElementById("PayCode").value = values[2];
					}
					if (values[3] != "") {
						document.getElementById("pr_payment").value = values[3];
					}
				}
			}
		</script>
		<!-- LOV : Emp -->
		<script type="text/javascript" language="javascript">
			function openEmp(type_use) {
				returnvalue = window.showModalDialog('../include_RedThemes/lov/lov_search_emp.php?themes=DefaultBlue&type_use=' + type_use, 'newWin', 'dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}

			function lovEmp() {
				returnvalue = openEmp('');
				if (returnvalue != null) {
					var values = returnvalue.split("|-|");
					if (values[0] != "") {
						document.getElementById("empno").value = values[0];
					} else {
						document.getElementById("empno").value = '';
					}
					if (values[1] != "") {
						document.getElementById("empno_show").value = values[1];
					} else {
						document.getElementById("empno_show").value = '';
					}
				}
			}

			function lovMng() {
				returnvalue = openEmp('MngPr_po');
				if (returnvalue != null) {
					var values = returnvalue.split("|-|");
					if (values[0] != "") {
						document.getElementById("mngno").value = values[0];
					}
					if (values[1] != "") {
						document.getElementById("mngno_show").value = values[1];
					}
				}
			}
		</script>
		<!-- LOV : Dept_use -->
		<script type="text/javascript" language="javascript">
			function openDeptUse() {
				returnvalue = window.showModalDialog('../include_RedThemes/lov/lov_search_dept_use.php?themes=DefaultBlue', 'newWin', 'dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}

			function lovDeptUse() {
				returnvalue = openDeptUse();
				if (returnvalue != null) {
					var values = returnvalue.split("|-|");
					if (values[0] != "") {
						document.getElementById("deptno").value = values[0];
					} else {
						document.getElementById("deptno").value = '';
					}
					if (values[1] != "") {
						document.getElementById("deptno_show").value = values[1];
					} else {
						document.getElementById("deptno_show").value = '';
					}
				}
			}
		</script>
		<!-- LOV : CodeAccount -->
		<script type="text/javascript" language="javascript">
			function openCodeAccount() {
				returnvalue = window.showModalDialog('../include_RedThemes/lov/lov_search_code_account.php?themes=DefaultBlue', 'newWin', 'dialogWidth:780px;dialogHeight:600px;');
				return returnvalue;
			}

			function lovCodeAccount1() {
				returnvalue = openCodeAccount();
				if (returnvalue != null) {
					var txtReturn = '';
					var values = returnvalue.split("|-|");
					if (document.getElementById("obj_name22").value == "") txtReturn = values[0];
					else txtReturn = document.getElementById("obj_name22").value + ',' + values[0];
					document.getElementById("obj_name22").value = txtReturn.substring(0, 30);
				}
			}

			function lovCodeAccount2() {
				returnvalue = openCodeAccount();
				if (returnvalue != null) {
					var txtReturn = '';
					var values = returnvalue.split("|-|");
					if (document.getElementById("obj_name32").value == "") txtReturn = values[0];
					else txtReturn = document.getElementById("obj_name32").value + ',' + values[0];
					document.getElementById("obj_name32").value = txtReturn.substring(0, 30);
				}
			}
		</script>

	</head>

	<body topmargin="0" leftmargin="0">
		<?php
		$flagAction = @$_POST["flagAction"];
		if (@$flagAction == 'UpCode') {
			$pr_no = @$_POST["pr_no"];
			$pr_date = @$_POST["pr_date"];
			$PayCode = @$_POST["PayCode"];
			$pr_payment = @$_POST["pr_payment"];

			$flag_obj = @$_POST["flag_obj"];

			$supplier_id = @$_POST["supplier_id"];
			$pr_remark = iconv( "windows-874", "utf-8" ,@$_POST["pr_remark"]) ;

			$empno = @$_POST["empno"];
			$deptno = @$_POST["deptno"];
			$mngno = @$_POST["mngno"];
			$Jobno = @$_POST["Jobno"];
			$estimate_day = @$_POST["estimate_day"];
			$obj_name1 = "";
			$obj_name2 = "";
			$obj_name3 = "";

			if ($flag_obj == "8") {
				$obj_name1 = @$_POST["obj_name81"];
			} else {
				if ($flag_obj == "1") {
					$obj_name1 = @$_POST["obj_name11"];
					$obj_name2 = @$_POST["obj_name12"];
					$obj_name3 = @$_POST["obj_name13"];
				} else if ($flag_obj == "2") {
					$obj_name1 = @$_POST["obj_name21"];
					$obj_name2 = @$_POST["obj_name22"];
				} else if ($flag_obj == "3") {
					$obj_name1 = @$_POST["obj_name31"];
					$obj_name2 = @$_POST["obj_name32"];
					$obj_name3 = @$_POST["obj_name33"];
				} else if ($flag_obj == "4") {
					$obj_name1 = @$_POST["obj_name41"];
				} else if ($flag_obj == "5") {
					$obj_name1 = @$_POST["obj_name51"];
				} else {
					$obj_name1 = @$_POST["obj_name61"];
				}
			}
			$vat_include = @$_POST["vat_include"];
			$pr_pathold = @$_POST["pr_pathold"];


			$pr_payment = iconv("utf-8" ,  "windows-874" ,$pr_payment);
			$obj_name1 = iconv("utf-8" ,  "windows-874" ,$obj_name1);
			$obj_name2 = iconv("utf-8" ,  "windows-874" ,$obj_name2);
			$obj_name3 = iconv("utf-8" ,  "windows-874" ,$obj_name3);

			$strUPD = "update pr_master set 
						pr_date=convert(date, '$pr_date',103),
						deptno='$deptno',
						empno='$empno',
						mngno='$mngno',
						PayCode = '$PayCode',
						pr_payment='$pr_payment',";
			if ($flag_obj != "") {
				$strUPD .= "flag_obj='$flag_obj',";
			}

			$strUPD .= "obj_name1='$obj_name1',
						obj_name2='$obj_name2',
						obj_name3='$obj_name3',
						estimate_day='$estimate_day',
						supplier_id='$supplier_id',
						pr_remark='$pr_remark',
						pr_status='1',
						jobno = '$Jobno',
						vat_include='$vat_include',
						mng_remark='',";

			$file_name = $_FILES['pr_path']['name'];
			$uploadResult = 1;
			if ($_FILES['pr_path']['name'] != '') {
				if (is_uploaded_file($_FILES['pr_path']['tmp_name'])) {
					$path_file = $PathtoUploadFile . "pr_thai";
					if ($pr_pathold != "") {
						@unlink("$path_file\\$pr_pathold");
					}

					$type_file = substr($file_name, -3, 3);
					$filename = $pr_no . "." . $type_file;

					if (move_uploaded_file($_FILES['pr_path']['tmp_name'], "$path_file\\$filename")) {
						$strUPD .= "pr_path='$filename',";
					} else {
						$uploadResult = 0;
					}
				} else {
					$uploadResult = 0;
				}
			}
			$strUPD .= "last_user='$empno_user',
						last_date=getdate() 
						where pr_no='$pr_no'";
			$exeUPD = odbc_exec($conn, $strUPD);
			$exe_commit = @odbc_exec($conn,"commit");

			if ($uploadResult) {
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'alert ("บันทึกข้อมูลเรียบร้อยแล้วค่ะ");';
				echo '</script>';
			} else {
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'alert ("บันทึกข้อมูลเรียบร้อยแล้ว\n\n แต่ไม่สามารถ Upload ไฟล์ไว้บน Server ได้ค่ะ!!!!");';
				echo '</script>';
			}
		}

		$flag = @$_GET["flag"];
		if ($flag == "edit") {
			$_SESSION["sespk_no"] = $_GET["pr_no"];
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'parent.mid_frame.location.href = "./pr_head.php?tabno=2";';
			echo '</script>';
		}

		$pr_no = $_SESSION["sespk_no"];
		$txt_pr_master = "select format(pr.pr_date,'dd-MM-yyyy') pr_date,pr.deptno,
						pr.empno,pr.mngno,pr.PayCode,pr.pr_payment,vat_include, 
						pr.flag_obj,pr.obj_name1,pr.obj_name2,pr.obj_name3,
						pr.estimate_day,pr.pr_remark,pr.supplier_id,pr.pr_path,
						e.e_name empno_show,d.deptname deptno_show,s.company_name ,pr.jobno
					from pr_master pr
					left join dept d on pr.deptno= d.deptno
					left join emp e on pr.empno= e.empno
					left join supplier s on pr.supplier_id= s.supplier_id
					where pr_no= '$pr_no'";
		$cur_pr_master = odbc_exec($conn, $txt_pr_master);
		$pr_date = odbc_result($cur_pr_master, "pr_date");
		$deptno = odbc_result($cur_pr_master, "deptno");
		$deptno_show = iconv( "windows-874", "utf-8" ,odbc_result($cur_pr_master, "deptno_show")) ;
		$empno = odbc_result($cur_pr_master, "empno");
		$empno_show =iconv( "windows-874", "utf-8" , odbc_result($cur_pr_master, "empno_show")) ;
		$mngno = odbc_result($cur_pr_master, "mngno");

		if ($mngno != "-") {
			$cur_mng = odbc_exec($conn, "select  e_name from emp where empno='$mngno'");
			$mngno_show = iconv( "windows-874", "utf-8" ,odbc_result($cur_mng, "e_name")) ;
		}
		$supplier_id = odbc_result($cur_pr_master, "supplier_id");
		$supplier_show = iconv( "windows-874", "utf-8" ,odbc_result($cur_pr_master, "company_name")) ;
		$paycode = odbc_result($cur_pr_master, "PayCode");
		$pr_payment = odbc_result($cur_pr_master, "pr_payment");
		//$pr_remark =  odbc_result($cur_pr_master, "pr_remark");
		$pr_remark = iconv( "windows-874", "utf-8" ,odbc_result($cur_pr_master, "pr_remark"));
		$Jobno = odbc_result($cur_pr_master, "jobno");
		$flag_obj = odbc_result($cur_pr_master, "flag_obj");
		$obj_name1 = iconv( "windows-874", "utf-8" , odbc_result($cur_pr_master, "obj_name1"));
		//$obj_name2 = iconv( "windows-874", "utf-8" ,odbc_result($cur_pr_master, "obj_name2"));
		$obj_name2 = iconv( "windows-874", "utf-8" , odbc_result($cur_pr_master, "obj_name2")) ;
		$obj_name3 =iconv( "windows-874", "utf-8" , odbc_result($cur_pr_master, "obj_name3"));
		$estimate_day = odbc_result($cur_pr_master, "estimate_day");
		$vat_include = odbc_result($cur_pr_master, "vat_include");
		$pr_path = odbc_result($cur_pr_master, "pr_path");

		$str_query_prdet = "select id,prod_no,prod_name,prod_qty,
						prod_unit,prod_price,isnull(discount_baht,0) discount_baht 
					from pr_details 
					where pr_no='$pr_no' 
					order by id";
		//echo $str_query_prdet;
		$cur_query_prdet = odbc_exec($conn, $str_query_prdet);


		$strCOU = "select count(*) c from pr_details where pr_no='$pr_no'";
		$curCOU = odbc_exec($conn, $strCOU);
		$numrow = odbc_result($curCOU, "c");
		?>
		<br>
		<center>
 
			<form name="form_pr" action="prmas_edit.php" method="post" enctype="multipart/form-data">
				<input name="flagAction" type="hidden" value="UpCode">
				<table width="870" border="0" cellpadding="0" cellspacing="0" bgcolor="E9EAEB">
					<tr>
						<th> &nbsp;&nbsp;แก้ไข PR <?php if ($flag_obj == "8") echo "Transtek"; 
													else echo "Supreme"; ?></th>
						<th>
							<div align="right">&nbsp;</div>
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<table width="100%">
								<tr>
									<td>
										<table width="100%" border="1" cellpadding="0" cellspacing="0">
											<tr>
												<td width="120" class="tdleftwhite"> &nbsp;เลขที่ PR <span class="style_star">*</span> </td>
												<td><input name="pr_no" type="text" value="<?= $pr_no; ?>" size="31" class="style_readonly" readonly=""></td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;วันที่ขอซื้อ<span class="style_star">*</span> </td>
												<td>
													<input name="pr_date" type="text" class="style_readonly" readonly="" value="<?= $pr_date; ?>" size="8">
													<script language='javascript'>
														if (!document.layers) {
															document.write("<img src=\"../include/images/date_icon.gif\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, form_pr.pr_date, \"dd-mm-yyyy\")'>");
														}
													</script>
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite"> &nbsp;เงื่อนไขการชำระเงิน<span class="style_star">*</span></td>
												<?php /*onChange="var i = document.form_pr.PayCode;
															if(i.value == 'none')
																document.form_pr.pr_payment.value = '';
															else	document.form_pr.pr_payment.value = i.options[i.selectedIndex].text;" */ ?>
												<td><select name="PayCode" id="PayCode">
														<?php
														$strSEL = "select payment_name, payment_description from Payment_method where status = 'Y'";
														$queSEL = @odbc_exec($conn, $strSEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
														?>
														<option value="none">กรุณาระบุ Payment Term</option>
														<?php
														while (@odbc_fetch_row($queSEL)) {
															$PayCode = @odbc_result($queSEL, "payment_name");
															//$Day = @odbc_result($queSEL,"Day");
														?>
															<option value="<?= $PayCode; ?>" <?php if ($PayCode == $paycode) echo "selected"; ?>><?= $PayCode; ?></option>
														<?php
														}
														?>
													</select>
													<input type="text" name="pr_payment" value="<?= iconv( "windows-874", "utf-8" ,@$pr_payment); ?>" size="70" maxlength="120">
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;วัตถุประสงค์<span class="style_star"> *</span></td>
												<td>
													<?php if ($flag_obj == "8") { ?>
														<input name="flag_obj" type="hidden" value="8">
														<input name="obj_name81" type="text" onKeyUp="return check_string(document.form_pr.obj_name81,100);" value="<?php echo @$obj_name1; ?>" size="100" maxlength="100"><br>
													<?php } else { ?>
														<input name="flag_obj" type="radio" value="1" onClick="return flag_obj_check(document.form_pr,1)" <?php if ((@$flag_obj == "1") || (@$flag_obj == "")) echo "checked";  ?> <? if ($numrow != 0) echo "disabled"; ?>>
														ขายให้กับลูกค้า (ชื่อ) <input name="obj_name11" type="text" onKeyUp="return check_string(document.form_pr.obj_name11,100);" value="<?= @$obj_name1; ?>" size="33" maxlength="100">
														Req No. <input name="obj_name12" type="text" onKeyUp="return check_string(document.form_pr.obj_name12,30);" value="<?= @$obj_name2; ?>" size="15" maxlength="30">
														Job No.<input name="obj_name13" type="text" onKeyUp="return check_string(document.form_pr.obj_name13,30);" value="<?= @$obj_name3; ?>" size="15" maxlength="30">
														&nbsp;&nbsp;&lt;&lt;product&gt;&gt;<br>
														<input name="flag_obj" type="radio" value="2" onClick="return flag_obj_check(document.form_pr,2)" <?php if (@$flag_obj == "2") echo "checked";  ?> <?php if ($numrow != 0) echo "disabled"; ?>>
														ผลิตสินค้าเพื่อขายในโครงการ (ชื่อ) <input name="obj_name21" type="text" onKeyUp="return check_string(document.form_pr.obj_name21,100);" value="<?= @$obj_name1; ?>" size="40" maxlength="100">
														รหัสบัญชี <input name="obj_name22" type="text" onKeyUp="return check_string(document.form_pr.obj_name22,30);" value="<?= @$obj_name2; ?>" size="20" maxlength="30"><input name="acc_but" type="button" value="..." onClick="lovCodeAccount1();">
														&nbsp;&lt;&lt;bom&gt;&gt;<br>
														<input name="flag_obj" type="radio" value="3" onClick="return flag_obj_check(document.form_pr,3)" <?php if (@$flag_obj == "3") echo "checked";  ?> <?php if ($numrow != 0) echo "disabled"; ?>>
														จ้างทำอะหลั่ยเพื่อซ่อมเครื่องมือแพทย์ให้กับลูกค้า (ชื่อ) <input name="obj_name31" type="text" onKeyUp="return check_string(document.form_pr.obj_name31,100);" value="<?= @$obj_name1; ?>" size="15" maxlength="100">
														รหัสบัญชี <input name="obj_name32" type="text" onKeyUp="return check_string(document.form_pr.obj_name32,30);" value="<?= @$obj_name2; ?>" size="8" maxlength="30"><input name="acc_but2" type="button" value="..." onClick="lovCodeAccount2();">
														Job No. <input name="obj_name33" type="text" onKeyUp="return check_string(document.form_pr.obj_name33,30);" value="<?= @$obj_name3; ?>" size="10" maxlength="30">
														&lt;&lt;service&gt;&gt;<br>
														<input name="flag_obj" type="radio" value="4" onClick="return flag_obj_check(document.form_pr,4)" <?php if (@$flag_obj == "4") echo "checked";  ?> <?php if ($numrow != 0) echo "disabled"; ?>>
														เพื่อใช้ในงานตกแต่งสถานที่เพื่อการติดตั้งสินค้า (ชื่อ) <input name="obj_name41" type="text" onKeyUp="return check_string(document.form_pr.obj_name41,100);" value="<?= @$obj_name1; ?>" size="60" maxlength="100">
														&nbsp;&lt;&lt;etc&gt;&gt;<br>
														<input name="flag_obj" type="radio" value="5" onClick="return flag_obj_check(document.form_pr,5)" <?php if (@$flag_obj == "5") echo "checked";  ?> <?php if ($numrow != 0) echo "disabled"; ?>>
														อุปกรณ์/เครื่องมือเครื่องใช้ในแผนก (ระบุ) <input name="obj_name51" type="text" onKeyUp="return check_string(document.form_pr.obj_name51,100);" value="<?= @$obj_name1; ?>" size="68" maxlength="100">
														&nbsp;&lt;&lt;etc&gt;&gt;<br>
														<input name="flag_obj" type="radio" value="7" onClick="return flag_obj_check(document.form_pr,7)" <?php if (@$flag_obj == "7") echo "checked";  ?> <?php if ($numrow != 0) echo "disabled"; ?>>
														SubContact
														&nbsp;&lt;&lt;subcontact&gt;&gt;<br>
														<input name="flag_obj" type="radio" value="6" onClick="return flag_obj_check(document.form_pr,6)" <?php if (@$flag_obj == "6") echo "checked";  ?> <?php if ($numrow != 0) echo "disabled"; ?>>
														อื่นๆ (ระบุ) <input name="obj_name61" type="text" onKeyUp="return check_string(document.form_pr.obj_name61,100);" value="<?= @$obj_name1; ?>" size="92" maxlength="100">
														&nbsp;&lt;&lt;etc&gt;&gt;<br>
														<input name="flag_obj" type="hidden" value="<?= @$flag_obj; ?>" <?php if ($numrow == 0) echo "disabled"; ?>>
														<?php
														echo '<script language="JavaScript" type="text/JavaScript">';
														echo 'flag_obj_check(document.form_pr,' . @$flag_obj . ');';
														echo '</script>';

														?>
													<?php } ?><span class="style_text">หากมีการเปลี่ยนแปลงวัตถุประสงค์ภายหลัง ต้องทำการบันทึกก่อนจึงค่อยเลือกรายการสินค้าใหม่ค่ะ</span>
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;ต้องการใช้ภายใน<span class="style_star">*</span></td>
												<td>
													<input name="estimate_day" type="text" onKeyUp="return check_string(document.form_pr.estimate_day,10);" value="<?= @$estimate_day; ?>" size="8" maxlength="15">
													วัน<br>
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;เพื่อ Job No.</td>
												<td>
													<input name="Jobno" type="text" onKeyUp="return check_string(document.form_pr.Jobno,9);" value="<?php echo @$Jobno; ?>" size="20" maxlength="30">
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;สถานที่หาซื้อได้</td>
												<td>
													<input name="supplier_id" type="text" value="<?= @$supplier_id; ?>" size="10" class="style_readonly" readonly=""><input name="supplier_show" type="text" value="<?= @$supplier_show; ?>" size="70" class="style_readonly" readonly=""><input name="supplier_but" type="button" value="..." onClick="lovSupplier();">
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;หมายเหตุ</td>
												<td><textarea name="pr_remark" cols="85" rows="3" onKeyUp="return check_string(document.form_pr.pr_remark,300);"><?= @$pr_remark; ?></textarea></td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;ผู้ขอซื้อ<span class="style_star">*</span></td>
												<td>
													<input name="empno" type="text" value="<?= @$empno; ?>" size="10" maxlength="15" class="style_readonly" readonly=""><input name="empno_show" type="text" value="<?=  @$empno_show; ?>" size="50" maxlength="15" class="style_readonly" readonly="">
													<?php
													if (substr($roles_user, 0, 3) != 'MNG') {
														echo "&nbsp;";
													} else {
													?>
														<img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovEmp();">
													<?php
													}
													?>
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite"> &nbsp;แผนก<span class="style_star">*</span></td>
												<td>
													<input name="deptno" type="text" value="<?= @$deptno; ?>" size="10" maxlength="20" class="style_readonly" readonly=""><input name="deptno_show" type="text" value="<?= @$deptno_show; ?>" size="50" maxlength="15" class="style_readonly" readonly=""><input name="deptno_but" type="button" value="..." onClick="lovDeptUse();">
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;ผู้อนุมัติ<span class="style_star">*</span></td>
												<td>
													<input name="mngno" type="text" value="<?= @$mngno; ?>" size="10" maxlength="15" class="style_readonly" readonly=""><input name="mngno_show" type="text" value="<?= @$mngno_show; ?>" size="50" maxlength="15" class="style_readonly" readonly="">
													<img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovMng();">
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;ราคาสินค้าที่บันทึก<span class="style_star">*</span></td>
												<td>
													<input name="vat_include" type="radio" value="1" <?php if (@$vat_include == "1") echo "checked";  ?>>รวม VAT แล้ว
													<input name="vat_include" type="radio" value="0" <?php if (@$vat_include == "0") echo "checked";  ?>>ยังไม่รวม VAT
												</td>
											</tr>
											<tr>
												<td class="tdleftwhite">&nbsp;ใบกรรมวิธี<br>&nbsp;ตรวจรับสินค้า</td>
												<td>
													<?php if ($pr_path != "") { ?>
														<a onClick="window.open('\\\\<?= $http_host; ?>\\iso\\pr_thai\\<?= $pr_path; ?>');" target="_blank" style="cursor:hand">
															<span class="style_text">คลิ๊กที่นี่</span> เพื่อดู ใบกรรมวิธีตรวจรับสินค้า ที่ Upload โดยผู้ใช้
														</a>
														<br>
													<?php  } ?>

													<input name="pr_path" type="file" size="50"><input name="pr_pathold" type="hidden" value="<?= $pr_path; ?>">
													<span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>
													<?php
													/*		 
										 		 switch($ses_deptno){
													case '08'	:
													case '09'	:
													case '11'	:
													case '20'	:
													case '23'	:
													case '17'	:	echo '<input name="file_location" type="file" size="50">';
																		break;
													default		:	if(($_SESSION["empno_user"] == ' 05020')||($_SESSION["empno_user"] == '05008')||($_SESSION["empno_user"] == '02019')){									
																			echo '<input name="pr_path" type="file" size="50"><input name="pr_pathold" type="hidden" value="<?= $pr_path; ?>">';
																			echo '<span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>';																	
																		}else{
																			echo '<font color="#FF0000"><b>&lt;&lt;Upload ไฟล์ไม่ได้ชั่วคราว&gt;&gt;</b></font><br><input name="pr_path" type="file" size="50" readonly>';
																		}
																		break;
												} 
												*/
													?>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
											<tr bgcolor="999999">
												<td colspan="9" class="tdleftblack">รายละเอียดสินค้า</td>
											</tr>
											<tr>
												<td width="25" class="tdcenterwhite">Del</td>
												<td width="25" class="tdcenterwhite">Edit</td>
												<td width="125" class="tdcenterwhite">รหัส</td>
												<td width="300" class="tdcenterwhite">ชื่อรายการ</td>
												<td class="tdcenterwhite">จำนวน</td>
												<td class="tdcenterwhite">หน่วยนับ</td>
												<td width="100" class="tdcenterwhite">ราคาต่อหน่วย<br></td>
												<td width="100" class="tdcenterwhite">ราคารวม</td>
											</tr>
											<?php
											$sum_total = 0;
											while (odbc_fetch_row($cur_query_prdet)) {
												$id = odbc_result($cur_query_prdet, "id");
												$prod_no = odbc_result($cur_query_prdet, "prod_no");
												$prod_name = odbc_result($cur_query_prdet, "prod_name") ;
												$prod_qty = odbc_result($cur_query_prdet, "prod_qty");
												$prod_unit = odbc_result($cur_query_prdet, "prod_unit");
												$prod_price = odbc_result($cur_query_prdet, "prod_price");
												$discount_baht = odbc_result($cur_query_prdet, "discount_baht");


												if ((($prod_price * $prod_qty) - $discount_baht) == 0) $prod_price = 0;
												else $prod_price = (($prod_price * $prod_qty) - $discount_baht) / $prod_qty;
												$total_price = $prod_price * $prod_qty;
												$sum_total = $sum_total + $total_price;

												if ($flag_obj == "7") {
													$strQUEDetailSubjob = "select pd.subjob_show || '=' || sj.qty subjob_show
															from pr_details_subjob sj, mrp_pd pd
															where sj.subjob=pd.subjob 
															and pr_no= '$pr_no'
															and id='$id'";
															echo $strQUEDetailSubjob;
													$subjob_show = "";
													while (odbc_fetch_row($curQUEDetailSubjob)) {
														if ($subjob_show == "") $subjob_show = odbc_result($curQUEDetailSubjob, "subjob_show");
														else $subjob_show .= "," . odbc_result($curQUEDetailSubjob, "subjob_show");
													}
													$prod_name .= "<br>(" . $subjob_show . ")";
												}
											?>
												<tr>
													<td class="tdcenterwhite">
														<a onClick="remote_del('prdet_delcode.php?id=<?= $id; ?>&pr_no=<?= $pr_no; ?>&flag_obj=<?= $flag_obj; ?>');" style="cursor:hand">
															<img src="../include/images/del_icon.png" width="25" height="25" border="0">
														</a>
													</td>
													<td>
														<?php if ($flag_obj == "2") { ?>
															<a onClick="popupLevel1('prdet_addbom.php?id=<?= $id; ?>&flag=edit&pr_no=<?= $pr_no; ?>&vat_include=<?= @$vat_include; ?>',550,315,100,100);" style="cursor:hand">
																<img src="../include/images/edit_icon.png" width="25" height="25" border="0">
															</a>
														<?php } else if ($flag_obj == "7") { ?>
															<a onClick="popupLevel1('prdet_addsubc.php?id=<?= $id; ?>&flag=edit&pr_no=<?= $pr_no; ?>&vat_include=<?= @$vat_include; ?>',800,550,50,100);" style="cursor:hand">
																<img src="../include/images/edit_icon.png" width="25" height="25" border="0">
															</a>
														<?php } else { ?>
															<a onClick="popupLevel1('prdet_add.php?id=<?= $id; ?>&flag=edit&pr_no=<?= $pr_no; ?>&vat_include=<?= @$vat_include; ?>',600,315,100,100);" style="cursor:hand">
																<img src="../include/images/edit_icon.png" width="25" height="25" border="0">
															</a>
														<?php } ?>
													</td>
													<td>&nbsp;<?= $prod_no; ?></td>
													<td>&nbsp;<?= iconv( "windows-874", "utf-8" ,$prod_name); ?></td>
													<td>&nbsp;<?= $prod_qty; ?></td>
													<td>&nbsp;<?= $prod_unit; ?></td>
													<td>
														<div align="right"><?= number_format($prod_price, 2, ".", ","); ?></div>
													</td>
													<td>
														<div align="right"><?= number_format($total_price, 2, ".", ","); ?></div>
													</td>
												</tr>
											<?php
											}
											?>
											<tr>
												<td colspan="7"><strong> &nbsp;<span class="thai_baht">ราคาที่แสดงเป็นราคาที่หักส่วนลดแล้ว</span></strong></td>
												<td>
													<div align="right"><span class="thai_baht"><?= number_format($sum_total, 2, ".", ","); ?></span></div>
												</td>
											</tr>
											<tr>
												<td colspan="9" class="tdleftblack">
													<div align="right">
														<?php if ($flag_obj == "2") { ?>
															<a onMousedown="document.images['butadd'].src=add3.src" style="cursor:hand" onClick="popupLevel1('prdet_addbom.php?flag=add&vat_include=<?= @$vat_include; ?>',550,315,100,100);">
																<img src="../include/button/add1.gif" name="butadd" width="106" height="24" border="0">
															</a>
														<?php } else if ($flag_obj == "7") { ?>
															<a onMousedown="document.images['butadd'].src=add3.src" style="cursor:hand" onClick="popupLevel1('prdet_addsubc.php?flag=add&vat_include=<?= @$vat_include; ?>',800,550,50,100);">
																<img src="../include/button/add1.gif" name="butadd" width="106" height="24" border="0">
															</a>
														<?php } else { ?>
															<a onMousedown="document.images['butadd'].src=add3.src" style="cursor:hand" onClick="popupLevel1('prdet_add.php?flag=add&vat_include=<?= @$vat_include; ?>',600,315,100,100);">
																<img src="../include/button/add1.gif" name="butadd" width="106" height="24" border="0">
															</a>
														<?php } ?>
													</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
											<tr>
												<th colspan="2">
												<input type="button" name="Button2" value="ทดสอบ" style="cursor:hand" onClick="window.open('./prmas_reportcode01.php')">
													<input type="button" name="Button" value="แสดงรายงาน" style="cursor:hand" onClick="window.open('./prmas_reportcode.php?pr_no=<?= $pr_no; ?>');">
													<input type="button" name="Button" value="ส่งให้ผู้จัดการอนุมัติ" style="cursor:hand" onClick="remote_confirm('pr_commitcode.php?pr_no=<?= $pr_no; ?>&flag=from_editpage');">
												</th>
												<th>
													<div align="right">
														<a onClick="return check_pr(document.form_pr,'<?= $flag_obj; ?>');" style="cursor:hand">
															<img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0">
														</a>
														<a onClick="document.form_pr.reset();" style="cursor:hand">
															<img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0">
														</a>
													</div>
												</th>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<p>
					<span class="style_text">
						กรณีไม่ได้ส่ง ใบขอซื้อ ให้ผู้จัดการอนุมัติทาง Electronic
						ให้พิมพ์ใบขอซื้อออกมาให้ผู้จัดการเซ็นต์ <br>
						และส่งเอกสาร &quot;ฉบับจริง&quot; มายังแผนก R&amp;D ชั้น 2
						เพื่อปรับสถานะ ให้แผนก MS ทำงานต่อไป
					</span>
					<br class="style_star">
				</p>
			</form>
		</center>
		<script language="JavaScript" type="text/JavaScript">
			document.form_pr.pr_payment.focus();
		</script>
	</body>

	</html>
<?php
} else {
	include("../include_RedThemes/SessionTimeOut.php");
}
?>









s