<?php
// @session_start();
if(isset($_SESSION["valid_userprpo"])) {
		require_once("../include_RedThemes/MSSQLServer_connect_2.php");
		//require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		$empno_user = $_SESSION["empno_user"];		
		$txtCode=@$_POST["txtCode"];
				if($txtCode!=""){
						$supplier_id= @$_POST["supplier_id"];
						$company_name= @$_POST["company_name"];
						$supplier_address1=@$_POST["supplier_address1"];
						$supplier_address2=@$_POST["supplier_address2"];
						$supplier_address3=@$_POST["supplier_address3"];
						$supplier_address3_1=@$_POST["supplier_address3_1"];
						$PayCode = @$_POST["PayCode"];
						$supplier_payment=@$_POST["supplier_payment"];
						$supplier_title=@$_POST["supplier_title"];
						$sup_type=@$_POST["sup_type"];
						$tambol=@$_POST["tambol"];
						$district=@$_POST["district"];
						$province=@$_POST["province"];
						$province_id = @$_POST["province_id"];		
						$fax_number=@$_POST["fax_number"];				
						$postcode = @$_POST["postcode"];
						$status = @$_POST["status"];
						$country_id = @$_POST["country"];
						$sup_as400id = @$_POST["as400"];
						$company = @$_POST["company"];
						$strSQL = "select sent_to_SAP,sent_to_DATE from supplier where supplier_id = '$supplier_id' ";
						$strResult = @odbc_exec($conn,$strSQL);
						$sent_to_SAP = @odbc_result($strResult,"sent_to_SAP");
						$sent_to_DATE = @odbc_result($strResult,"sent_to_DATE");
						$vendorno = @$_POST["vendorno"];			
						if($sent_to_SAP=='I' && $sent_to_DATE!=NULL) {
							$sent_to_SAP='U';
							$sent_to_DATE=NULL;
						}else if($sent_to_SAP=='U' && $sent_to_DATE!=NULL) {
							$sent_to_SAP='U';
							$sent_to_DATE=NULL;
						}
						$branch_name = @$_POST["branch_name"];	
						$pid = @$_POST["pid"];	
						$branch_type = @$_POST["branch_type"];	
						if($branch_type=="0")
						{
							$branch_name ="สำนักงานใหญ่";
						}
						if($company=="P")
						{
						$branch_name="";
						$branch_type=0;
						}
						$curQUEVendorSup = odbc_exec($conn,"select isnull(count(supplier_id),0) as count_vendor from supplier where vendor_no='$vendorno' and supplier_id != '$supplier_id'");
						$totalVendorNo = @odbc_result($curQUEVendorSup, "count_vendor");	
						
						if ($totalVendorNo==0){				
						$strSupplierUPD =  "update supplier set COMPANY_NAME = '$company_name',
														SUPPLIER_ADDRESS1 = '$supplier_address1',
														SUPPLIER_ADDRESS2 = '$supplier_address2',
														SUPPLIER_ADDRESS3 = '$supplier_address3',
														SUPPLIER_ADDRESS3_1 = '$supplier_address3_1',
														SUPPLIER_PAYMENT = '$supplier_payment',
														LAST_USER = '$empno_user',
														LAST_DATE = getdate(),
														SUPPLIER_TITLE = '$supplier_title',
														PAYCODE = '$PayCode',
														TAMBOL = '$tambol',
														DISTRICT = '$district',
														PROVINCE = '$province',
														PROVINCE_ID = '$province_id',
														POSTCODE = '$postcode',
														FAX_NUMBER = '$fax_number',
														SUP_TYPE = '$sup_type',
														SENT_TO_SAP = '$sent_to_SAP',
														SENT_TO_DATE = '$sent_to_DATE',
														status = '$status',
														country = '$country_id',
														sup_as400id = '$sup_as400id',
														company = '$company',
														vendor_no = '$vendorno',
														pid='$pid',
														branch_name='$branch_name',
														branch_type=$branch_type
														where supplier_id='$supplier_id'";
														//echo $strSupplierUPD;
						$exeSupplierUPD = odbc_exec($conn,$strSupplierUPD);
						if($exeSupplierUPD){
									$exeCommit = @odbc_exec($conn,"commit");
									echo '<script language="JavaScript" type="text/JavaScript">';
									echo 'alert ("บันทึกข้อมูลเรียบร้อยแล้วค่ะ");';
									echo '</script>';
						}else{
									echo '<script language="JavaScript" type="text/JavaScript">';
									echo 'alert ("ระบบมีปัญหาไม่สามารถบันทึกข้อมูลได้ค่ะ");';
									echo '</script>';
						}		
						}else{
									echo '<script language="JavaScript" type="text/JavaScript">';
									echo 'alert ("กรุณาป้อนรหัส Vendor ใหม่เนื่องจากรหัส Vendor นี้มีในระบบแล้ว");';
									echo '</script>';		
						}						
				}
				
				$flag= @$_GET["flag"];							
				if($flag=="edit"){
							 	$_SESSION["sespk_no"] = $_GET["supplier_id"];
								echo '<script language="JavaScript" type="text/JavaScript">';
								echo 'parent.mid_frame.location.href = "./pr_head.php?tabno=2";';
								echo '</script>';
				}			
				
				$supplier_id= $_SESSION["sespk_no"];

				/*
				แก้ไขตาม Requirment เลขที่  213
				$strSupplierQUE = "select supplier_id,company_name,supplier_address1,supplier_address2,supplier_address3,
												PayCode,supplier_payment,supplier_title,supplier_address3_1,sup_type,
												tambol,district,province,fax_number,postcode,status,country,sup_as400id,company,vendor_no
												from supplier 
												where supplier_id='$supplier_id'";
				*/
				$strSupplierQUE = "select s.supplier_id,s.company_name,s.supplier_address1,s.supplier_address2,s.supplier_address3,
												s.PayCode,s.supplier_payment,s.supplier_title,s.supplier_address3_1,s.sup_type,
												s.tambol,s.district,s.province,s.fax_number,s.postcode,s.status,
												s.country,s.sup_as400id,s.company,s.vendor_no,vg.vendor_name,branch_name,branch_type,pid
												from supplier s  left join vendor_group vg on s.vendor_no = convert(varchar,vg.vendor_id) or s.vendor_no is null
												where s.supplier_id='$supplier_id'";
				// END
				$curSupplierQUE = @odbc_exec($conn, $strSupplierQUE );
				
				$supplier_id =@odbc_result($curSupplierQUE,"supplier_id");		
				$company_name =@odbc_result($curSupplierQUE,"company_name");		
				$supplier_address1 =@odbc_result($curSupplierQUE,"supplier_address1");		
				$supplier_address2 =@odbc_result($curSupplierQUE,"supplier_address2");		
				$supplier_address3 =@odbc_result($curSupplierQUE,"supplier_address3");		
				$supplier_address3_1=@odbc_result($curSupplierQUE,"supplier_address3_1");
				$paycode = @odbc_result($curSupplierQUE,"PayCode");
				$supplier_payment =@odbc_result($curSupplierQUE,"supplier_payment");		
				$supplier_title =@odbc_result($curSupplierQUE,"supplier_title");		
				$sup_type =@odbc_result($curSupplierQUE,"sup_type");		
				$tambol = @odbc_result($curSupplierQUE,"tambol");		
				$district = @odbc_result($curSupplierQUE,"district");
				$province = @odbc_result($curSupplierQUE,"province");
				$fax_number = @odbc_result($curSupplierQUE,"fax_number");
				$postcode = @odbc_result($curSupplierQUE,"postcode");
				$status = @odbc_result($curSupplierQUE,"status");
				$country_id = @odbc_result($curSupplierQUE,"country");
				$sup_as400id = @odbc_result($curSupplierQUE,"sup_as400id");
				$company = @odbc_result($curSupplierQUE,"company");
				$vendor_no = @odbc_result($curSupplierQUE,"vender_no");
				$branch_type=@odbc_result($curSupplierQUE,"branch_type");
				$branch_name=@odbc_result($curSupplierQUE,"branch_name");
				$pid=@odbc_result($curSupplierQUE,"pid");
				if($branch_type=="0")
				{
				$branch_name='';
				echo '<script language="JavaScript" type="text/JavaScript">';
									echo ' document.getElementById("branch_name").disabled = true;';
									echo '</script>';	
				}
				else
				{
				echo '<script language="JavaScript" type="text/JavaScript">';
									echo ' document.getElementById("branch_name").disabled = false;';
									echo '</script>';
				}
				if($company=="P")
				{
						$branch_name="";
						$branch_type="";
				}
				// เพิ่มเติมตาม Requirment เลขที่  213
				$vendor_name = @odbc_result($curSupplierQUE,"vendor_name");
				//END
?>
<html>
	<head>
			<title>*** SUPPLIER ***</title>
			<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
			
			<link href="../include/style1.css" rel="stylesheet" type="text/css">
			<script language='javascript' src='../include/buttonanimate.js'></script>		
			<script language='javascript' src='../include/check_inputtype.js'></script>				
			<script type="text/javascript" language="javascript">
				function openProvince(type_use){
						returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_province_sale.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:500px;dialogHeight:540px;');											
						return returnvalue;
				}			
				
				// เพิ่มเติมตาม Requirment เลขที่  213			
				function openVender(type_use){
						returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_vendor_group.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:500px;dialogHeight:540px;');											
						return returnvalue;
				}	
				//END
									
				function lovProvinceSale(){
						returnvalue = openProvince(''); 
						if (returnvalue != null){ 
							var values =  returnvalue.split("|-|");
							if(values[0]!= ""){ document.getElementById("province").value = values[0]; }
							if(values[1]!= ""){ document.getElementById("province_id").value = values[1]; }
						 }
				}       
				
				// เพิ่มเติมตาม Requirment เลขที่  213
				function lovVender(){
						returnvalue = openVender(''); 
						if (returnvalue != null){ 
							var values =  returnvalue.split("|-|");
							if(values[0]!= ""){ document.getElementById("vendorname").value = values[0]; }
							if(values[1]!= ""){ document.getElementById("vendorno").value = values[1]; }
						 }
				}       
				//END
		</script>							
			<!-- Check Not null -->
			 <script language='javascript'>
					function check_sup(obj){
					             var a = document.getElementById("company");
							str=a.options[a.selectedIndex].value;
							 if(str=="C")
							 {
							
							  var sel = 0;
							   var radio = document.getElementsByName("branch_type");
								 for (var i = 0; i < radio.length; i++)
								 {
								  if (radio[i].checked)
								   sel=radio[i].value;
								 }
							
								if (sel == '1') {
							   
									if(obj.branch_name.value==""){ 
										alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
										obj.branch_name.focus();
										return false;
									}
								}
							 }
							 
							if(obj.company_name.value==""){  	
									alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
									obj.company_name.focus();
									return false;
							}
							if(obj.pid.value==""){
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.pid.focus();
								return false;
							}		
							if(obj.PayCode.value=="none"){
									alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
									obj.PayCode.focus();
									return false;
							}	
							
								obj.submit();
					}
		function remote_vendor(a,b){
				window.showModalDialog("./include/checkVendor.php?vendorno="+a+"&supno="+b+"","","width=1,height=1,status=no,titlebar=no,scrollbars=no,top=180,left=230");
        }
		function enableElement(obj)
		{
			 
			 obj.disabled = false;
		}
		function disableElement(obj)
		{
			 obj.value = '';
			 obj.disabled = true;
		}
			 </script>							
	</head>
	<body topmargin="0" leftmargin="0">
			<br>
			<center>
						<form name="form_sup" action="supplier_edit.php" method="post">
							<table width="600"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
							  <tr>
								<th> &nbsp;&nbsp;แก้ไขข้อมูล Approval Supplier List </th>
								<th><div align="right">&nbsp;</div></th>
							  </tr>
							  <tr>
								<td colspan="2">
									  <table width="100%">
										<tr>
										                    <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="120" class="tdleftwhite"> &nbsp;เลขที่ Supplier </td>
                        <td><input name="supplier_id" type="text" size="45"  class="style_readonly" readonly="" value="<?=$supplier_id;?>">						</td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;รหัส SAP</td>
						<td><input type="text" name="as400" size="45"  value="<?=$sup_as400id;?>"></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;Vendor Group</td>
						<td>
                        <!-- 
                        แก้ไขตาม Requirment เลขที่  213
                        <input name="vendorno" type="text" id="vendorno"  value="<?php //ech o$vendor_no?>" onKeyPress="return handleEnter(this, event);" size="25" maxlength="50" onKeyDown="return check_string(document.form_sup.vendorno,14);" />
						<input type="button" name="Button" value="ตรวจสอบรหัส" onClick="remote_vendor(document.form_sup.vendorno.value,document.form_sup.supplier_id.value);" />
                        -->
                        <input name="vendorname" type="text" id="vendorname" size="45" maxlength="150"  value="<?=$vendor_name?>"
                       			 readonly=""  class="style_readonly"/>
						<input name="vendor" type="button" id="vendor" value="  ...  " onClick="lovVender();" style="cursor:pointer;">
                        <input name="vendorno" type="hidden" value="<?=$vendor_no?>">
                        <!-- END-->
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ชื่อการจดทะเบียน</td>
                        <td><input name="supplier_title" type="text" onKeyUp="return check_string(document.form_sup.supplier_title,20);" value="<?php echo iconv( "windows-874", "utf-8" ,@$supplier_title); ?>"  size="15" maxlength="20">
							<input type="button" name="b1" value="บริษัท" onClick="document.form_sup.supplier_title.value='บริษัท';" style="width:35px;"><input type="button" name="b12" value="ห้างหุ้นส่วน" onClick="document.form_sup.supplier_title.value='ห้างหุ้นส่วน';" style="width:65px;"><input type="button" name="b122" value="ห้างหุ้นส่วนจำกัด" onClick="document.form_sup.supplier_title.value='ห้างหุ้นส่วนจำกัด';" style="width:85px;"><input type="button" name="b12" value="ร้าน" onClick="document.form_sup.supplier_title.value='ร้าน';" style="width:25px;">
						</td>
                      </tr>
					   <tr>
				  	<td class="tdleftwhite">&nbsp;นิติบุคคล/บุคคลธรรมดา</td>
						<td><select name="company" id="company">
						  <option value="C" <?php if($company=="C") echo "selected"; ?>>C</option>
						  <option value="P" <?php if($company=="P") echo "selected"; ?>>P</option>
						</select>&nbsp;&nbsp;C=นิติบุคคล  P=บุคคลธรรมดา</td>
					  </tr>
					   <tr>
					  	<td class="tdleftwhite">&nbsp;สถานประกอบการ<span class="style_star">*</span></td>
						<td>
						<input name="branch_type" type="radio"  id="branch_type" onClick="disableElement(this.form.branch_name);"value="0"  <?php echo ($branch_type== '0') ?  "checked" : "" ;  ?>/> สำนักงานใหญ่
						<input name="branch_type" type="radio" id="branch_type" onClick="enableElement(this.form.branch_name);" value="1" <?php echo ($branch_type== '1') ?  "checked" : "" ;  ?>/> สาขา <input name="branch_name" id="branch_name" type="text"   onKeyUp="return check_string(document.form_sup.branch_name,200);" value="<?php echo iconv( "windows-874", "utf-8" ,@$branch_name); ?>" size="50"  maxlength="200">
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ชื่อ Supplier <span class="style_star">*</span> </td>
                        <td><input name="company_name" type="text" onKeyUp="return check_string(document.form_sup.company_name,100);" value="<?php echo iconv( "windows-874", "utf-8" ,@$company_name); ?>"  size="70" maxlength="100"></td>
                      </tr>
					   <tr>
					  	<td class="tdleftwhite">&nbsp;เลขประจำตัวผู้เสียภาษี<span class="style_star">*</span></td>
						<td><input name="pid" id="pid" type="text" value="<?=@$pid;?>" size="30" maxlength="13"></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;ประเภท Supplier</td>
						<td>
							<select name="sup_type" style="width:100px;">
								<option value="1" <?php if(@$sup_type=="1") echo 'selected="selected"'; ?>>ในประเทศ</option>
								<option value="2" <?php if(@$sup_type=="2") echo 'selected="selected"'; ?>>ต่างประเทศ</option>
							</select>
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ที่อยู่</td>
                        <td><input name="supplier_address1" type="text" onKeyUp="return check_string(document.form_sup.supplier_address1,70);" value="<?php echo iconv( "windows-874", "utf-8" ,@$supplier_address1); ?>"  size="70" maxlength="70">
                        <br>
                        <input name="supplier_address2" type="text" onKeyUp="return check_string(document.form_sup.supplier_address2,70);" value="<?php echo iconv( "windows-874", "utf-8" ,@$supplier_address2); ?>"  size="70" maxlength="70"></td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;แขวง/ตำบล</td>
						<td><input name="tambol" type="text" onKeyUp="return check_string(document.form_sup.tambol,70);" value="<?=iconv( "windows-874", "utf-8" ,@$tambol);?>" size="30" maxlength="70"></td>
					  </tr>
  					  <tr>
					  	<td class="tdleftwhite">&nbsp;เขต/อำเภอ</td>
						<td><input name="district" type="text" onKeyUp="return check_string(document.form_sup.district,70);" value="<?=iconv( "windows-874", "utf-8" ,@$district);?>" size="30" maxlength="70"></td>
					  </tr>
   					  <tr>
					  	<td class="tdleftwhite">&nbsp;จังหวัด</td>
						<td><input name="province" type="text" onKeyUp="return check_string(document.form_sup.province,100);" value="<?=iconv( "windows-874", "utf-8" ,@$province);?>" size="30" maxlength="100" readonly="" class="style_readonly">&nbsp;<input name="province" type="button" id="province" value="  ...  " onClick="lovProvinceSale();" style="cursor:pointer;"><input name="province_id" type="hidden" value=""></td>
					  </tr>
   					  <tr>
					  	<td class="tdleftwhite">&nbsp;รหัสไปรษณีย์</td>
						<td><input name="postcode" type="text" value="<?=@$postcode;?>" size="30" maxlength="20"></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;ประเทศ</td>
						<td><select name="country">				  				 
						  <?php
						  		$strSQL = "select country, id from cushos_country";
								$strResult = @odbc_exec($conn,$strSQL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
								while(@odbc_fetch_row($strResult))
								{
									$country = @odbc_result($strResult,"country");
									$id = @odbc_result($strResult,"id");
						  ?>
						   <option value="<?=$id;?>" <?php if($id == $country_id) echo "selected";?>><?=$country." (".$id.")";?></option>
						  <?php										
								}
						  ?>
						</select></td>
					  </tr>				
                      <tr>
                        <td class="tdleftwhite">&nbsp;เบอร์โทรศัพท์ 1</td>
                        <td><input name="supplier_address3" type="text" onKeyUp="return check_string(document.form_sup.supplier_address3,20);" value="<?php echo iconv( "windows-874", "utf-8" ,@$supplier_address3); ?>"  size="30" maxlength="20"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;เบอร์โทรศัพท์ 2 </td>
                        <td><input name="supplier_address3_1" type="text" onKeyUp="return check_string(document.form_sup.supplier_address3_1,20);" value="<?php echo iconv( "windows-874", "utf-8" ,@$supplier_address3_1); ?>"  size="30" maxlength="20"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;เบอร์แฟกซ์</td>
                        <td><input name="fax_number" type="text" onKeyUp="return check_string(document.form_sup.fax_number,20);" value="<?=@$fax_number; ?>"  size="30" maxlength="20"></td>
                      </tr>
					  					  <tr>
					  	<td class="tdleftwhite">&nbsp;สถานะ</td>
						<td><select name="status">
						  <option value="1" <?php if($status=="1") echo "selected"; ?>>ใช้งาน</option>
						  <option value="0" <?php if($status=="0") echo "selected"; ?>>ไม่ใช้งาน</option>
						</select></td>
					  </tr>					 
					 
					  <tr>
					  	<td class="tdleftwhite">&nbsp;Payment Term <span class="style_star">*</span></td>
						<!-- onChange="var i = document.form_sup.PayCode;
													if(i.value == 'none')
														document.form_sup.supplier_payment.value = '';
													else	document.form_sup.supplier_payment.value = i.options[i.selectedIndex].text;" -->
						<td>
							<select name="PayCode" id="PayCode">
							<?php	
									$strSEL = "select payment_name, payment_description from Payment_method where status = 'Y'";
									$queSEL = @odbc_exec($conn,$strSEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
							?>	
									<option value="none">กรุณาระบุ Payment Term</option> 
							<?php 
									while(@odbc_fetch_row($queSEL)){
										$PayCode = @odbc_result($queSEL,"payment_name");
							?>	
									<option value="<?=$PayCode;?>" <?php if($PayCode==$paycode) echo "selected"; ?>><?= $PayCode; ?></option>
							<?php	
									}	
							?>
							</select>
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;เงื่อนไขการชำระเงิน</td>
                        <td><input name="supplier_payment" type="text" onKeyUp="return check_string(document.form_sup.supplier_payment,120);" value="<?php echo iconv( "windows-874", "utf-8" ,@$supplier_payment); ?>"  size="70" maxlength="120"></td>
                      </tr>
                  </table></td>
										</tr>
										<tr>
										  <td>
											  <table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
												<tr>
												  <th colspan="3">
														<div align="right">
															<input name="txtCode" type="hidden" value="บันทึก">                   
															<a onClick="return check_sup(document.form_sup);" style="cursor:hand"
															onMousedown="document.images['butsave'].src=save3.src" 
															onMouseup="document.images['butsave'].src=save1.src"						
															onMouseOver="document.images['butsave'].src=save2.src" 
															onMouseOut="document.images['butsave'].src=save1.src">						 
																<img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" >
															</a>
														
															<a onClick="document.form_sup.reset();" style="cursor:hand"
															onMousedown="document.images['butcancel'].src=cancel3.src" 
															onMouseup="document.images['butcancel'].src=cancel1.src"						
															onMouseOver="document.images['butcancel'].src=cancel2.src" 
															onMouseOut="document.images['butcancel'].src=cancel1.src">						 
																<img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >
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
						</form>		
			</center>
			<script language="JavaScript" type="text/JavaScript">
					document.form_sup.company_name.focus();
			</script>
	</body>
</html>
<?php
	}else{
		include("../include_RedThemes/SessionTimeOut.php");	}
?>








