<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");				
		//require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		$empno_user = $_SESSION["empno_user"];
		
		//============= Start-ส่วนการทำงานเมื่อผ่านการกดปุ่ม SUBMIT ในหน้าการทำงานนี้ (code) ===============	
		$flagAction = @$_POST["flagAction"];		
		if($flagAction == "AddCode"){
				$v_id = $_POST["id"];
				$v_po_no = $_POST["po_no"];
				$v_show_id = $_POST["show_id"];
				
				$v_prodno = $_POST["prod_no"];
				$v_prod_name = $_POST["prod_name"];
				$v_prod_qty = $_POST["prod_qty"];
				$v_prod_unit = $_POST["prod_unit"];
				$v_prod_price = $_POST["prod_price"];
				$v_discount_percent = $_POST["discount_percent"];
				$v_discount_baht = $_POST["discount_baht"];
				$v_gar_qty = $_POST["gar_qty"];
				$v_gar_unit = $_POST["gar_unit"];
				$v_gar_price = $_POST["gar_price"];
				$item_code = $_POST["item_code"];
				
				$strUPD = "update po_details set 
											show_id='$v_show_id',
											prod_no='$v_prodno',
											prod_name='$v_prod_name',
											prod_qty='$v_prod_qty',
											prod_unit='$v_prod_unit',
											prod_price='$v_prod_price',
											discount_percent='$v_discount_percent',
											discount_baht='$v_discount_baht',
											gar_qty='$v_gar_qty',
											gar_unit='$v_gar_unit',
											gar_price='$v_gar_price',
											last_user='$empno_user',
											last_date=sysdate, 
											item_code = '$item_code'
										where po_no='$v_po_no'
										and id='$v_id'";
				$exeUPD = odbc_exec($conn,$strUPD);
				$result=odbc_exec($conn,"update po_master set po_status='1' where po_no='$v_po_no' ");
				$exe_commit = odbc_exec($conn,"commit");
				echo '<script language="JavaScript" type="text/JavaScript">';
				echo 'window.opener.location.reload("./pomas_edit.php");';
				echo 'window.close();';
				echo '</script>';		
		}
		//============= End-ส่วนการทำงานเมื่อผ่านการกดปุ่ม SUBMIT ในหน้าการทำงานนี้ (code) ===============	
		$v_po_no = @$_GET["po_no"]; 
		$v_id = @$_GET["id"];
		$ref_po_no = @$_GET["ref_po_no"];
		
		$strQUEDetail = "select  po_no,id,show_id,
												prod_no,prod_name,prod_type,
												prod_qty,prod_unit,prod_price,
												discount_percent,discount_baht,
												gar_qty,gar_unit,gar_price,item_code 
										from po_details  
										where po_no = '$v_po_no' 
										and id= '$v_id'";
		$curQUEDetail = odbc_exec($conn,$strQUEDetail);

		$v_show_id = @odbc_result($curQUEDetail, "show_id");						
		$v_prod_no = @odbc_result($curQUEDetail, "prod_no");
		$v_prod_name = @odbc_result($curQUEDetail, "prod_name");						
		$v_prod_type = @odbc_result($curQUEDetail, "prod_type");
		$item_code = @odbc_result($curQUEDetail, "item_code");
		$v_prod_qty = @odbc_result($curQUEDetail, "prod_qty");
		$v_prod_unit = @odbc_result($curQUEDetail, "prod_unit");
		$v_prod_price = @odbc_result($curQUEDetail, "prod_price");
		$v_discount_percent = @odbc_result($curQUEDetail, "discount_percent");
		$v_discount_baht = @odbc_result($curQUEDetail, "discount_baht");
		$v_gar_qty = @odbc_result($curQUEDetail, "gar_qty");
		$v_gar_unit = @odbc_result($curQUEDetail, "gar_unit");
		$v_gar_price = @odbc_result($curQUEDetail, "gar_price");
		$v_total_price = ($v_prod_qty * $v_prod_price)-$v_discount_baht;
		
		switch($v_prod_type){
			case '1'	:	$v_prod_type_show = 'BOM';					break;
			case '2'	:	$v_prod_type_show = 'SubContact';		break;
			case '3'	:	$v_prod_type_show = 'Product';				break;
			case '4'	:	$v_prod_type_show = 'Service';				break;
			case '5'	:	$v_prod_type_show = 'Etc';						break;
			case '6'	:	$v_prod_type_show = 'Detail';					break;										
		}
?>
<html>
		<head>
				<title>*** ปรับปรุงรายการสินค้า ***</title>
				<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
				<link href="../include/style1.css" rel="stylesheet" type="text/css">
				<script language='javascript' src='../include/buttonanimate.js'></script>		
				<script language='javascript' src='../include/check_inputtype.js'></script>		
				
				<!-- Check Not null -->
				<script language='javascript'>
						function check_podet(obj){
									if(obj.prod_name.value==""){  	
										alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
										obj.prod_name.focus();
										return false;
									}	
									
									if((obj.prod_type_show.value=='BOM')||(obj.prod_type_show.value=='Product')||(obj.prod_type_show.value=='Service')){
										if(obj.gar_qty.value==""){  	
											alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
											obj.gar_qty.focus();
											return false;
										}			
										if(obj.gar_price.value==""){  	
											alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
											obj.gar_price.focus();
											return false;
										}			
									}				
										
									if(obj.btnSelect.disabled == false && obj.item_desc.value==''){  	
										alert("กรุณาเลือกกลุ่มของค่าใช้จ่ายด้วยค่ะ");
										obj.btnSelect.focus();
										return false;
									}									
									obj.submit();
						}
				</script>			
						
				<!-- Calculate -->
				<script language='javascript'>
						// a=จำนวน, b=ราคา, c=ส่วนลด%, d=ส่วนลด(เงิน), e=ราคารวม
						function cal_prdet_price(a,b,c,d,e,objname){	
								if((a.value=="")||(b.value=="")){
										e.value = '';
										d.value = '';
										c.value = '';
								}else if((objname=='a') || (objname=='b')){
												if(d.value==""){
													e.value= round(a.value*b.value,2);
												}else{
													e.value= round(a.value*b.value - d.value,2);
												}
								}else if(objname=='c'){
										if(c.value==""){
												d.value = '';
												e.value =  round(a.value * b.value,2);
										}else{
						/////////////////
											var flo_calculate1 = (a.value * b.value);
											var flo_calculate2 = 0;
											var vars = c.value.split("%");
											for (var i=0;i<vars.length;i++) {
													flo_calculate2 = flo_calculate2 + (flo_calculate1 * vars[i] /100);
													flo_calculate1 = flo_calculate1 - flo_calculate2;
											} 
											d.value = round(flo_calculate2,2);
											e.value = round((a.value * b.value) - d.value,2);
						/////////////////
										}
								}else if(objname=='d'){
										if(d.value==""){
												c.value = '';
												e.value =  round(a.value * b.value,2);
										}else{
												c.value = round((100 * d.value)/(a.value*b.value),2);
												e.value = round((a.value * b.value) - d.value,2);
										}
								}
						}
						
						function round(number,X) {
							X = (!X ? 2 : X);
							return Math.round(number*Math.pow(10,X))/Math.pow(10,X);
						}
						
						function openItem(type_use){
									returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_Item.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:600px;dialogHeight:500px;');											
									return returnvalue;
								}
								
						function lovItem(id1,id2){
							returnvalue = openItem(''); 
							if (returnvalue != null){ 
								var values =  returnvalue.split("|-|");
								if(values[0]!= ""){ document.getElementById(id1).value = values[0]; }		
								if(values[1]!= ""){ document.getElementById(id2).value = values[1]; }
							 }
						}
				</script>			
				
		</head>
		<body topmargin="0" leftmargin="0">
				<form name="form1" action="podet_up.php" method="post">
						<input name="id" type="hidden" value="<?= $v_id; ?>">
						<input name="flagAction" type="hidden" value="AddCode">	  
						<table width="550"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
						<tr>
								<th width="550">&nbsp;&nbsp;ปรับปรุงรายการสินค้า</th>
						</tr>
						<tr>
						<td> 
						<table width="100%" border="0" align="center">
						<tr>
						<td>
								<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td width="120" class="tdleftwhite">&nbsp;เลขที่ใบ PO<span class="style_star">*</span></td>
									<td width="439"><input name="po_no" type="text" id="po_no" value="<?= $v_po_no; ?>" class="style_readonly"  readonly=""></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;ประเภทสินค้า<span class="style_star">*</span></td>
									<td><input name="prod_type_show" type="text" id="prod_type_show" value="<?= $v_prod_type_show; ?>" class="style_readonly"  readonly=""></td>
								</tr>
								</table>
						</td>
						</tr>			  
						<tr><td>
						<table width="100%"  border="1" cellspacing="0" cellpadding="0">
						<tr>
						  <td  class="tdleftwhite">&nbsp;ลำดับบน PO </td>
						  <td><input type="checkbox" name="show_id" value="1" <? if($v_show_id=="1")echo 'checked'; ?> >
						    แสดงลำดับบนใบ PO </td>
						  </tr>
						<tr>
							<td  class="tdleftwhite">&nbsp;กลุ่มของค่าใช้จ่าย</td>
								<?php 
											$strSEL = "select Description from NONITEM_MASTER where Item_Code = '$item_code'";
											$queSEL = @odbc_exec($conn,$strSEL);
											$item = @odbc_result($queSEL,"Description");
									?>
							<td><input name="item_desc" type="text" readonly="" class="style_readonly" id="description" style="width:225" value="<?= $item; ?>"> <input type="button" style="width:50; " name="btnSelect" value="เลือก..." onClick="lovItem('description','item_code');" <?php if($v_prod_type!='5') echo "disabled"; else echo ""; ?>></td><input type="hidden" name="item_code" id="item_code" value="<?= $item_code; ?>">
						</tr>
						<tr>
							<td width="120"  class="tdleftwhite">&nbsp;รหัสสินค้า</td>
							<td width="433"><input name="prod_no" type="text"  value="<?= $v_prod_no; ?>" size="35" maxlength="50" onKeyUp="return check_string(this,50);" <? if(($v_prod_type=='1')||($v_prod_type=='2')||($v_prod_type=='3')||($v_prod_type=='4')) echo 'class="style_readonly"  readonly=""'; ?>></td>
						</tr>
						<tr>
							<td  class="tdleftwhite">&nbsp;ชื่อสินค้า<span class="style_star">*</span></td>
							<td><input name="prod_name" type="text"  value="<?= $v_prod_name; ?>" size="60" maxlength="300"  onKeyUp="return check_string(this,300);"></td>
						</tr>
						<tr>
							<td colspan="2"  class="tdleftblack">รายการบนใบ PO </td>
						</tr>
						<tr>
							<td  class="tdleftwhite">&nbsp;จำนวน</td>							
							<td><input name="prod_qty" type="text"  onKeyDown="return check_number();" value="<?= $v_prod_qty; ?>" size="10" maxlength="8" onKeyUp="return cal_prdet_price(document.form1.prod_qty,document.form1.prod_price,document.form1.discount_percent,document.form1.discount_baht,document.form1.total_price,'a');"></td>
						</tr>
						<tr>
							<td  class="tdleftwhite">&nbsp;หน่วย</td>
							<td><input name="prod_unit" type="text" value="<?= $v_prod_unit; ?>" size="20"  maxlength="15" onKeyUp="return check_string(this,15);"> </td>
						</tr>
						<tr>
						<td  class="tdleftwhite">&nbsp;ราคาต่อหน่วย</td>
						<td><input name="prod_price" type="text"  onKeyDown="return check_number();" value="<?= $v_prod_price; ?>" size="20" maxlength="17" onKeyUp="return cal_prdet_price(document.form1.prod_qty,document.form1.prod_price,document.form1.discount_percent,document.form1.discount_baht,document.form1.total_price,'b');"></td>
						</tr>
						<tr>
							<td  class="tdleftwhite">&nbsp;ส่วนลด</td>
							<td class="tdleftwhite">
									<input name="discount_percent" type="text"  onKeyDown="return check_number();" value="<?= $v_discount_percent; ?>" size="5" maxlength="5" onKeyUp="return cal_prdet_price(document.form1.prod_qty,document.form1.prod_price,document.form1.discount_percent,document.form1.discount_baht,document.form1.total_price,'c');"> % คิดเป็นเงิน 
									<input name="discount_baht" type="text"  onKeyDown="return check_number();" value="<?= $v_discount_baht; ?>" size="16" maxlength="17" onKeyUp="return cal_prdet_price(document.form1.prod_qty,document.form1.prod_price,document.form1.discount_percent,document.form1.discount_baht,document.form1.total_price,'d');"> บาท							</td>
						</tr>
						<tr>
							<td  class="tdleftwhite">&nbsp;ราคาหลังหักส่วนลด</td>
							<td><input name="total_price" type="text"  value="<?= $v_total_price; ?>" size="20" class="style_readonly"  readonly=""></td>
						</tr>
						<tr>
							<td colspan="2"  class="tdleftblack">รายการที่ใช้ในการรับเข้า</td>
						</tr>
						<tr>
						<?php
									$strQUE =  "select p.gar_qty
														from po_details p
														where p.po_no = '$ref_po_no'
														and p.id = '$v_id'";
								 	$curQUE = @odbc_exec($conn,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
									
						?>
						<td  class="tdleftwhite">&nbsp;จำนวน<span class="style_star">*</span></td>
						<td><input name="gar_qty" type="text"  onKeyDown="return check_number();"  value="<?= $v_gar_qty; ?>" size="10" maxlength="8" <? if(($v_prod_type=='2')||($v_prod_type=='5')||($v_prod_type=='6')) echo 'class="style_readonly"  readonly=""'; ?>></td>
						</tr>
						<tr>
						<td  class="tdleftwhite">&nbsp;หน่วย</td>
						<td><input name="gar_unit" type="text" value="<?= $v_gar_unit; ?>" size="20"  maxlength="15" onKeyUp="return check_string(this,15);"  class="style_readonly"  readonly=""></td>
						</tr>
						<tr>
						<td  class="tdleftwhite">&nbsp;ราคาต่อหน่วย<span class="style_star">*</span></td>
						<td><input name="gar_price" type="text"  onKeyDown="return check_number();" value="<?= $v_gar_price; ?>" size="20" maxlength="17" <? if(($v_prod_type=='2')||($v_prod_type=='5')||($v_prod_type=='6')) echo 'class="style_readonly"  readonly=""'; ?>></td>
						</tr>
						</table>
						</td>
						</tr>			  
						<tr>
							<td>				
									<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
									<tr>
										<th >
												<div align="right">                        
														<a onClick="return check_podet(document.form1);" style="cursor:hand"
														onMouseDown="document.images['butsave'].src=save3.src" 
														onMouseUp="document.images['butsave'].src=save1.src"						
														onMouseOver="document.images['butsave'].src=save2.src" 
														onMouseOut="document.images['butsave'].src=save1.src"> 
																<img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
														<a  onClick="document.form1.reset();" style="cursor:hand"
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
		</body>
</html>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>
