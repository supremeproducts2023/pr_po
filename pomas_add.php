<?php
  	// @session_start();
	if(isset($_SESSION["valid_userprpo"])) {
			require_once("../include_RedThemes/MSSQLServer_connect_2.php");
			//require_once("../include_RedThemes/MSSQLServer_connect.php");	
			require_once("../include/alert.php");
			$empno_user = $_SESSION["empno_user"];
			
			$flagAction = @$_POST["flagAction"];
			if(@$flagAction =='AddCode'){
                $po_company = @$_POST["po_company"];
				$po_date=@$_POST["po_date"];
				$supplier_id=@$_POST["supplier_id"];
				$your_ref=@$_POST["your_ref"];
				$our_ref=@$_POST["our_ref"];
				$despatch_to=@$_POST["despatch_to"];
				$delivery_time=@$_POST["delivery_time"];	
				$delivery_date=@$_POST["delivery_date"];				
				$PayCode=@$_POST["PayCode"];
				$payment = @$_POST["payment"];
				$po_remark=@$_POST["po_remark"];
				$flag_vat=@$_POST["flag_vat"];
				$flag_boi=@$_POST["flag_boi"];
				$accid=@$_POST["accid"];
				$costid=@$_POST["costid"];
				$for_ref=@$_POST["for_ref"];
				$redhead=@$_POST["redhead"];
				$ref_po_no=@$_POST["ref_po_no"];
				$po_c=@$_POST["po_c"];
				
				//  Generate Primary key   PO YY xxxxx //
					$str_int_year = "select substr(to_char(sysdate,'YYYY')+543,3,2) int_year from dual";			
					$cur_int_year = @odbc_exec($conn,$str_int_year);
					$int_year = @odbc_result($cur_int_year, "int_year");
			
					if($po_c == 's'){
					$str_mx = "select nvl(max(substr(po_no,6,5))+1,1) int_mx from po_master ";
					$str_mx = $str_mx."where substr(po_no,3,1) = '1' and substr(po_no,4,2) = '".$int_year."' and  length(po_no) = 10";
					$cur_mx = @odbc_exec($conn,$str_mx);
					$int_mx = @odbc_result($cur_mx, "int_mx");

					if ($int_mx >= 10000) $str_middle = '';
					else if ($int_mx >= 1000) $str_middle = '0';
					else if ($int_mx >= 100) $str_middle = '00';
					else if ($int_mx >= 10) $str_middle = '000';
					else $str_middle = '0000';
					
						$po_no = "PO1" . $int_year . $str_middle . $int_mx;
					}
					else{
						$str_mx = "select nvl(max(substr(po_no,6,5))+1,1) int_mx from po_master ";
						$str_mx = $str_mx."where substr(po_no,3,1) = '3' and substr(po_no,4,2) = '".$int_year."' and  length(po_no) = 10";
						$cur_mx = @odbc_exec($conn,$str_mx);
						$int_mx = @odbc_result($cur_mx, "int_mx");

						if ($int_mx >= 10000) $str_middle = '';
						else if ($int_mx >= 1000) $str_middle = '0';
						else if ($int_mx >= 100) $str_middle = '00';
						else if ($int_mx >= 10) $str_middle = '000';
						else $str_middle = '0000';
						$po_no = "PO3" . $int_year . $str_middle . $int_mx;
					}
				//  End Generate  //
				$boi = '';
				if ($flag_boi == '1')
				{$boi = 'B';} 
				else if ($flag_boi == '2')
				{$boi = '2';} 
				else if ($flag_boi == '3')
				{$boi = '3';} 
		
				$strINS = "insert into po_master (
									po_no,po_date,supplier_id,
									your_ref,our_ref,despatch_to,delivery_time, PayCode, payment,
									po_remark,flag_vat,accid,costid,
									redhead,
									for_ref,po_status,rec_user,rec_date,ref_po_no,po_company,delivery_date,boi_flg
									) values(
									'$po_no',to_date('$po_date','dd-MM-yyyy'),'$supplier_id',
									'$your_ref','$our_ref','$despatch_to','$delivery_time', '$PayCode', '$payment',
									'$po_remark','$flag_vat','$accid','$costid',
									'$redhead',
									'$for_ref','1','$empno_user',sysdate,'$ref_po_no','$po_company',to_date('$delivery_date','dd-MM-yyyy'),'$boi')";
									echo $strINS;
				$exeINS = @odbc_exec($conn,$strINS) or die(alert("เกิดข้อผิดพลาดขึ้นกับระบบ ทำให้ไม่สามารถบันทึกข้อมูล PO นี้ลงบนฐานข้อมูลได้ค่ะ"));
				$exeCOMMIT = @odbc_exec($conn,"commit");
				
				$_SESSION["sespk_no"] = $po_no;			
				echo '<script language="JavaScript" type="text/JavaScript">
								alert ("บันทึกข้อมูลส่วนหัวของ PO เรียบร้อยแล้ว กรุณาใส่รายละเอียดสินค้าที่ต้องการสั่งซื้อค่ะ");
								parent.main_frame.location.href = "./pomas_edit.php";
						</script>';
			}			
				
?>
<html><head>
		<title>*** PO ***</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		<script language='javascript' src='../include/popcalendar.js'></script>				
		 		
		<!-- Check Not null -->
		 <script language='javascript'>
				function check_po(obj){
							if(obj.po_date.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.po_date.focus();
								return false;
							}			
							if(obj.supplier_id.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.supplier_but.focus();
								return false;
							}			
							if(obj.PayCode.value == "none"){
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.PayCode.focus();
								return false;
							}
							obj.submit();
				}
		 </script>							
		<!-- LOV : POCAN -->			
		<script type="text/javascript" language="javascript">
			function openPO_CAN(po_company){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_PO_CAN.php?themes=DefaultBlue&po_company='+po_company,'newWin','dialogWidth:600px;dialogHeight:500px;');											
				return returnvalue;
			}
			
			function lov_PO_CAN(po_company){
				returnvalue = openPO_CAN(po_company); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					document.getElementById("ref_po_no").value = values[0]; 
				 }
			}       
		</script>
		<!-- Calculate -->
		<script language="javascript">
				function cal_descpatch_to(descpatch_to){
							if(descpatch_to.value=="บริษัท สุพรีม โพรดักส์ จำกัด"){
									descpatch_to.value='บริษัท ทรานสเทค จำกัด';
							}else if(descpatch_to.value=="บริษัท ทรานสเทค จำกัด"){
									descpatch_to.value='โรงงานราษฏร์นิยม';
							}else if(descpatch_to.value=="โรงงานราษฏร์นิยม"){
									descpatch_to.value='ช่างไปรับสินค้า';
							}else if(descpatch_to.value=="ช่างไปรับสินค้า"){
									descpatch_to.value='โกดังไปรับสินค้า';
							}else if(descpatch_to.value=="โกดังไปรับสินค้า"){
									descpatch_to.value='รพ.กรุงเทพ (ตึกอินเตอร์ชั้นใต้ดิน)';
							}else if(descpatch_to.value=="รพ.กรุงเทพ (ตึกอินเตอร์ชั้นใต้ดิน)"){
									descpatch_to.value='';
							}else{
									descpatch_to.value='บริษัท สุพรีม โพรดักส์ จำกัด';
							}
				}
				
				function cal_delivery_time(delivery_time){
							if(delivery_time.value=="ได้รับสินค้าแล้ว"){
									delivery_time.value='ภายใน    วัน';
							}else if(delivery_time.value=="ภายใน    วัน"){
									delivery_time.value='ภายในวันที่';
							}else if(delivery_time.value=="ภายในวันที่"){
									delivery_time.value='ดำเนินการแล้ว';
							}else if(delivery_time.value=="ดำเนินการแล้ว"){
									delivery_time.value='';
							}else{
									delivery_time.value='ได้รับสินค้าแล้ว';
							}
				}
		</script>		

		<!-- LOV : Supplier -->			
		<script type="text/javascript" language="javascript">
			function openSupplier(){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_supplier.php?themes=DefaultBlue','newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovSupplier(){
				returnvalue = openSupplier(); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= ""){ document.getElementById("supplier_id").value = values[0];  }else{ document.getElementById("supplier_id").value =''; }
					if(values[1]!= ""){ document.getElementById("supplier_show").value = values[1];  }else{ document.getElementById("supplier_show").value =''; }
					if(values[2]== null)
                      { document.getElementById("PayCode").value = "none"; }
                    else if(values[2]== "")
                      { document.getElementById("PayCode").value = "none"; }
                    else{ document.getElementById("PayCode").value = values[2]; }
					if(values[3] == null)
                      { document.getElementById("payment").value = ""; }
                    else if(values[3] == "")
                      { document.getElementById("payment").value = ""; }
                    else { document.getElementById("payment").value = values[3]; }
				 }
			}       
		</script>
		<!-- LOV : CostCenter-->			
		<script type="text/javascript" language="javascript">
			function openCostCenter(){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_cost_center.php?themes=DefaultBlue','newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovCostCenter(){
				returnvalue = openCostCenter(); 
				if (returnvalue != null){ 
					var txtReturn='';
					var values =  returnvalue.split("|-|");
						if(document.getElementById("costid").value =="") txtReturn = values[0]+' M/L '+values[1];
						else txtReturn = document.getElementById("costid").value+'\n'+values[0]+' M/L '+values[1];
						document.getElementById("costid").value = txtReturn.substring(0,200);				 
				 }
			}       
		</script>
		<!-- LOV : CodeAccount -->			
		<script type="text/javascript" language="javascript">
			function openCodeAccount(){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_code_account.php?themes=DefaultBlue','newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovCodeAccount(){
				returnvalue = openCodeAccount(); 
				if (returnvalue != null){ 
					var txtReturn='';
					var values =  returnvalue.split("|-|");
						if(document.getElementById("accid").value =="") txtReturn = 'รหัสบัญชี '+values[0]+' '+values[1];
						else txtReturn = document.getElementById("accid").value+'\n'+values[0]+' '+values[1];
						document.getElementById("accid").value = txtReturn.substring(0,200);				 
				 }
			}       
		</script>
		
		 
</head>
<body topmargin="0" leftmargin="0">
<br>
<center>
			<form name="form_po" action="pomas_add.php" method="post">
			<input name="flagAction" type="hidden" value="AddCode">
			<?php $po_company = $_SESSION["po_company"]; ?>
            <input name="po_company" type="hidden" value="<?=$po_company;?>">
		<table width="950"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
          <tr>
            <th> &nbsp;&nbsp;เพิ่ม PO <?php if($po_company=="T") $po_company = "Transtek"; else $po_company = "Supreme";
																echo $po_company; ?> </th>
            <th><div align="right">&nbsp;</div></th>
          </tr>
          <tr>
            <td colspan="2">
              <table width="100%">
                <tr>
                  <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="120" class="tdleftwhite"> &nbsp;เลขที่ PO <span class="style_star">*</span> </td>                       
						<?php if($po_company=="Supreme") { ?>
						 <td width="363"><input name="po_no" type="text"  class="style_readonly" value="ระบบจะ Generate ให้ หลังจาก Save ค่ะ" size="31" readonly="">
						 <input name="po_c" type="hidden" value="s">
						 </td>
                        <td width="102">อ้างอิง PO เก่าเลขที่</td>
                        <td width="347"><input name="ref_po_no" type="text"  class="style_readonly" id="ref_po_no" value="" size="31" readonly="">
                        <input name='btn_select' type='button' value='...' onClick='lov_PO_CAN("<?php if($po_company=="Supreme") echo "S"; else echo "T"; ?>");' style=' width:40; cursor:pointer'></td>
						<?php }else{ ?>						
						<td colspan="3"><input name="po_no" type="text"  class="style_readonly" value="ระบบจะ Generate ให้ หลังจาก Save ค่ะ" size="31" readonly="">
						<input name="po_c" type="hidden" value="t">
						</td>
						<?php } ?>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;Date <span class="style_star">*</span> </td>
                        <td colspan="3" class="tdleftwhite">
              			<input name="po_date" type="text"  class="style_readonly"value="<?php echo date("d-m-Y"); ?>" size="8" readonly="" >	
                        <script language='javascript'>
											<!-- 
											  if (!document.layers) {
												document.write("<img src=\"../include/images/date_icon.gif\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, form_po.po_date, \"dd-mm-yyyy\")'>");
											}
											//-->
							</script>						
								
						  <input name="flag_boi" type="radio" value="1" >
						   BOI
						  <input name="flag_boi" type="radio" value="0" checked> 
						   ไม่เป็น BOI	
						    <input name="flag_boi" type="radio" value="2" >
						   BOI Factory 2
						  <input name="flag_boi" type="radio" value="3"> 
						   ไม่เป็น BOI Factory 2	
							</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;To Messers <span class="style_star"> *</span> </td>
                        <td colspan="3"><input name="supplier_id" type="text" value="<?php echo @$supplier_id; ?>"  size="10" class="style_readonly" readonly=""><input name="supplier_show" type="text" value="<?php echo @$supplier_show; ?>"  size="70" class="style_readonly" readonly=""><input name="supplier_but" type="button" value="&nbsp;...&nbsp;" onClick="lovSupplier();"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;Your Ref. </td>
                        <td colspan="3"><input name="your_ref" type="text" onKeyUp="return check_string(document.form_po.your_ref,50);" value="<?php echo @$your_ref; ?>"  size="70" maxlength="50"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;Our Ref. </td>
                        <td colspan="3"><input name="our_ref" type="text" onKeyUp="return check_string(document.form_po.our_ref,50);" value="<?php echo @$our_ref; ?>"  size="70" maxlength="50"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;Despatch to </td>
                        <td colspan="3"><input name="despatch_to" type="text" onKeyUp="return check_string(document.form_po.despatch_to,50);" value="<?php echo @$despatch_to; ?>"  size="70" maxlength="50"><input type="button" name="despatch_tobut" value="&nbsp;...&nbsp;" onClick="return cal_descpatch_to(document.form_po.despatch_to);"></td>
                      </tr>
                      <tr class="tdleftwhite">
                        <td class="tdleftwhite">&nbsp;Delivery Time</td>
                        <td colspan="3"><input name="delivery_time" type="text" onKeyUp="return check_string(document.form_po.delivery_time,50);" value="<?php echo @$delivery_time; ?>"  size="70" maxlength="50"><input type="button" name="delivery_timebut" value="&nbsp;...&nbsp;" onClick="return cal_delivery_time(document.form_po.delivery_time);">
						&nbsp;Delivery Date&nbsp;<input name="delivery_date" type="text"  class="style_readonly"value="<?php echo date("d-m-Y"); ?>" size="8" readonly="" >						  
                        <script language='javascript'>
											<!-- 
											  if (!document.layers) {
												document.write("<img src=\"../include/images/date_icon.gif\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, form_po.delivery_date, \"dd-mm-yyyy\")'>");
											}
											//-->
							</script>	
						</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;Payment <span class="style_star">*</span> </td>
                        <td colspan="3">
						<!--
						onChange="var i = document.form_po.PayCode;
												if(i.value == 'none')
													document.form_po.payment.value = '';
												else	document.form_po.payment.value = i.options[i.selectedIndex].text;"
						 -->			
                        <select name="PayCode" id="PayCode" >
						<?php	
								$strSEL = "select payment_name, payment_description from Payment_method where status = 'Y'";
								$queSEL = @odbc_exec($conn,$strSEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
						?>	
								<option value="none">กรุณาระบุ Payment Term</option> 
						<?php 
								while(@odbc_fetch_row($queSEL)){
									$PayCode = @odbc_result($queSEL,"payment_name");
						?>	
								<option value="<?=$PayCode;?>" ><?= $PayCode; ?></option>
						<?php	
								}	
						?>
						</select>		
						<input type="text" name="payment" id="payment" value=""<?=@$payment;?>" size="70" maxlength="120">						</td>
                      </tr>
                      
                      <tr>
                        <td class="tdleftwhite">&nbsp;ภาษี 7%</td>
                        <td colspan="3" class="tdleftwhite">
						<input name="flag_vat" type="radio" value="1" checked>
						 คำนวณ VAT
						<input name="flag_vat" type="radio" value="0"> 
						 ไม่คำนวณ VAT
						 <input name="flag_vat" type="radio" value="2">
						ไม่อยู่ในระบบ 
						<input name="flag_vat" type="radio" value="3">
						ไม่แสดง						</td>
                      </tr>
						<tr>
                        <td class="tdleftwhite">&nbsp;รหัสบัญชี </td>
                        <td colspan="3">
						<textarea name="accid" cols="85" rows="3" onKeyUp="return check_string(document.form_po.accid,200);"><?php echo @$accid; ?></textarea><input name="acc_but" type="button" value="&nbsp;...&nbsp;" onClick="lovCodeAccount()">						</td>
                      </tr>					  
						<tr>
                        <td class="tdleftwhite">&nbsp;COST CENTER </td>
                        <td colspan="3">
						<textarea name="costid" cols="85" rows="3" onKeyUp="return check_string(document.form_po.costid,200);"><?php echo @$costid; ?></textarea><input name="cost_but" type="button" value="&nbsp;...&nbsp;" onClick="lovCostCenter()">						</td>
                      </tr>					  
                        <tr>
                          <td class="tdleftwhite"> &nbsp;For</td>
                          <td colspan="3">
                          <textarea name="for_ref" cols="85" rows="3" onKeyUp="return check_string(document.form_po.for_ref,500);"><?php echo @$for_ref; ?></textarea>						  </td>
                        </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;Remark</td>
                        <td colspan="3"><textarea name="po_remark" cols="85" rows="3" onKeyUp="return check_string(document.form_po.po_remark,300);"><?php echo @$po_remark; ?></textarea></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;<span class="thai_baht">หัวแดง (ปกติ) </span></td>
                        <td colspan="3"><input name="redhead" type="text" onKeyUp="return check_string(document.form_po.redhead,100);"   size="70" maxlength="100"></td>
                      </tr>
					</table>					  
				 </td>
                </tr>
				<tr>
				<td>
				<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
                  <tr bgcolor="999999">
                    <td colspan="11" class="tdleftblack">รายละเอียดสินค้า</td>
                  </tr>
                  <tr>
                    <td width="25" rowspan="2" class="tdcenterwhite">Del</td>
                    <td width="25" rowspan="2" class="tdcenterwhite">Edit</td>
                    <td width="70" rowspan="2" class="tdcenterwhite">ประเภท</td>
                    <td width="120" rowspan="2" class="tdcenterwhite">รหัสสินค้า</td>
                    <td width="300" rowspan="2" class="tdcenterwhite">ชื่อรายการ</td>
                    <td colspan="3" class="tdcenterwhite">รายการที่แสดงบนใบ PO <br>                    </td>
                    <td colspan="2" class="tdcenterwhite">รายการที่ใช้ในการรับเข้า</td>
                  </tr>
                  <tr>
                    <td width="80" class="tdcenterwhite">จำนวน</td>
                    <td width="90" class="tdcenterwhite">ราคาต่อหน่วย</td>
                    <td width="90" class="tdcenterwhite">ราคารวม</td>
                    <td width="80" class="tdcenterwhite">จำนวน</td>
                    <td width="90" class="tdcenterwhite">ราคาต่อหน่วย</td>
                  </tr>
				<tr>
                    <td colspan="7" > <strong> &nbsp;<span class="thai_baht">ราคาที่ระบุเป็นราคาที่หักส่วนลด(ตามรายการ) </span></strong></td>
                    <td >&nbsp;</td>
                    <td colspan="2" >&nbsp;</td>
                  </tr>				  
                  <tr>
                    <td colspan="11" class="tdleftblack">
                      <div align="right">
					    <input type="button" name="btnProduct"  value="เพิ่มสินค้าประเภท BOM, Product, Service" style="width:260px; height:27px; "   target="_blank" disabled="disabled">
<!--                        <input type="button" name="btnSubContact"  value="เพิ่มสินค้าประเภท SubContract" style="width:180px; height:27px; "   target="_blank" disabled="disabled"> -->
                        <input type="button" name="btnEtc" value="เพิ่มสินค้าประเภท Detail, Etc" style="width:170px; height:27px; "   target="_blank" disabled="disabled">
						<input type="button" name="btnPR"  value="เพิ่มสินค้าตาม PR (หลายประเภท)" style="width:180px; height:27px; "   target="_blank" disabled="disabled">
					  </div></td>
                  </tr>
                </table>
				</td>
				</tr>
                <tr>
                  <td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <th colspan="3"><div align="right">                          
						<a onClick="return check_po(document.form_po);" style="cursor:hand"
						onMousedown="document.images['butsave'].src=save3.src" 
						onMouseup="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src">						 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
						 						 
						<a onClick="document.form_po.reset();" style="cursor:hand"
						onMousedown="document.images['butcancel'].src=cancel3.src" 
						onMouseup="document.images['butcancel'].src=cancel1.src"						
						 onMouseOver="document.images['butcancel'].src=cancel2.src" 
						 onMouseOut="document.images['butcancel'].src=cancel1.src">						 
						 <img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >
						</a>
                        </div>
						
					  </th>
                    </tr>
                  </table></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br>
			<table width="900" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2" class="tdleftwhite">ประเภทของสินค้า</td>
              </tr>
              <tr>
                <td width="80">- BOM</td>
                <td>เป็นรายการสินค้าที่ใช้ในการผลิต</td>
              </tr>
              <tr>
                <td>- SubContact </td>
                <td>เป็นค่าจ้างทำสินค้าที่ใช้ในการผลิตต้องระบุ Subjob(เพื่อให้ราคาลง JOB) </td>
              </tr>
              <tr>
                <td>- Product </td>
                <td>เป็นรายการสินค้าสำเร็จรูปในบริษัท จับต้องได้ ต้องทำการรับเข้า (เพื่อขาย หรือเป็นของแถม) </td>
              </tr>
              <tr>
                <td>- Service </td>
                <td>เป็นรายการสินค้าสำเร็จรูปในบริษัท จับต้องได้ ต้องทำการรับเข้า (เพื่อซ่อม หรือเป็นอะไหล่) </td>
              </tr>
              <tr>
                <td>- Etc </td>
                <td>เป็นสินค้าที่ไม่ต้องทำการรับเข้า เช่น ซื้อมาใช้งานในแผนก ฯลฯ </td>
              </tr>
              <tr>
                <td>- Detail </td>
                <td>เป็นรายการที่ไม่ต้องนำไปใช้งานต่อ ใช้เพียงเพื่อความสวยงามของ PO เท่านั้น</td>
              </tr>
            </table>
			</form>		
</center>
			<script language="JavaScript" type="text/JavaScript">
							document.form_po.your_ref.focus();
			</script>
</body>
</html>
<?php
	}
	else{
			include("index.php");
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("ขออภัยค่ะ คุณยังไม่ได้ Login");';
			echo '</script>';
	}
?>









