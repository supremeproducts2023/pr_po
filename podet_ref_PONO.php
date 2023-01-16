<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");	
		require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");			
		$empno_user = $_SESSION["empno_user"];
		
		//============= Start-ส่วนการทำงานเมื่อผ่านการกดปุ่ม SUBMIT ในหน้าการทำงานนี้ (code) ===============	
				$flagAction=@$_POST["flagAction"];
				if($flagAction=='AddCode')
				{
					$v_po_no = $_POST["v_po_no"];
					$k=0;		
					for($i = 0;$i<$_POST["amount"];$i++)
					{
						$checkbox = @$_POST["checkbox"][$i];
						$item = @$_POST["item"][$checkbox];
						$prod_no = @$_POST["prod_no"][$checkbox];
						$prod_name = @$_POST["prod_name"][$checkbox];
						$prod_qty = @$_POST["prod_qty"][$checkbox];
							if($prod_qty == "") $prod_qty = 0;
						$prod_unit = @$_POST["prod_unit"][$checkbox];
						$prod_price = @$_POST["prod_price"][$checkbox];
							if($prod_price == "") $prod_price = 0;
						$gar_qty = @$_POST["gar_qty"][$checkbox];
							if($gar_qty == "") $gar_qty = 0;
						$gar_unit = @$_POST["gar_unit"][$checkbox];
						$gar_price = @$_POST["gar_price"][$checkbox];
							if($gar_price == "") $gar_price = 0;
						$prod_type = @$_POST["prod_type"][$checkbox];
						$code = @$_POST["code"][$checkbox];
						$show_id = @$_POST["show_id"][$checkbox];
						if($checkbox != "")
						{  
							$strQUE = "select id from po_details where po_no = '$v_po_no' and id = '$checkbox'";
							$strResult = @odbc_exec($conn,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลข้อมูลในฐานข้อมูลได้ค่ะ"));
							$id = @odbc_result($strResult,"id");
							if($id == "") {	
								$strQUE = "insert into po_details(PO_NO,ID,PROD_NO,PROD_NAME,PROD_QTY,PROD_UNIT,PROD_PRICE,
											  GAR_QTY,GAR_UNIT,GAR_PRICE,ITEM_CODE,CODE,PROD_TYPE,SHOW_ID,REC_USER,REC_DATE) 
											  values('$v_po_no','$checkbox','$prod_no','$prod_name','$prod_qty','$prod_unit','$prod_price',
											  '$gar_qty','$gar_unit','$gar_price','$item','$code','$prod_type','$show_id','$empno_user',getdate())";
								@odbc_exec($conn,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถบันทึกข้อมูลลงในฐานข้อมูลได้ค่ะ"));
								@odbc_exec($conn,"commit");
								$k++;
							}else{
								$strQUE = "update po_details
												  set prod_no = '$prod_no',
												  prod_name = '$prod_name',
												  prod_qty = '$prod_qty',
												  prod_unit = '$prod_unit',
												  prod_price = '$prod_price',
												  gar_qty = '$gar_qty',
												  gar_unit = '$gar_unit',
												  gar_price = '$gar_price',
												  item_code = '$item',
												  code = '$code',
												  prod_type = '$prod_type',
												  show_id = '$show_id',
												  last_user = '$empno_user',
												  last_date = getdate()
												  where po_no = '$v_po_no' and id = '$id' ";
								@odbc_exec($conn,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถบันทึกข้อมูลลงในฐานข้อมูลได้ค่ะ"));
								@odbc_exec($conn,"commit");
								$k++;
							}
						}
					}
					echo '<script language="JavaScript" type="text/JavaScript">';
					echo '		alert ("ข้อมูลถูกบันทึกทั้งสิ้น '.$k.' รายการค่ะ");';
					echo '		window.opener.location.reload("./pomas_edit.php");';
					echo '		window.close();';
					echo '</script>';   
				}else{
							$v_po_no = $_GET["po_no"];
							$ref_po_no = $_GET["ref_po_no"];
							
							$strQUE =  "select p.id,p.prod_type,p.prod_no,p.prod_name,
												p.prod_qty,p.prod_unit,p.prod_price,
												p.gar_qty,p.gar_unit,p.gar_price,p.item_code,
												p.code,p.show_id
												from po_details p
												where p.po_no = '$ref_po_no'
												order by p.id";
							$curQUE = @odbc_exec($conn,$strQUE) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
				}
		//============= End-ส่วนการทำงานเมื่อผ่านการกดปุ่ม SUBMIT ในหน้าการทำงานนี้ (code) ===============	
	include "../include_RedThemes/wait.php";
	flush();
?>
<html>
	<head>
		<title>เพิ่มสินค้าจากใบ PO เก่า</title>
		<script type="text/javascript">document.title = "เพิ่มสินค้าจากใบ PO เก่า";</script>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include_RedThemes/funcChkInput.js'></script>	
		<script language='javascript' src='../include_RedThemes/funcEtc.js'></script>	
	</head>	
<body topmargin="0" leftmargin="0">
	<div align="center">  
		<form name="form_po" action="podet_ref_PONO.php" method="post">
			<input name="flagAction" type="hidden" value="SearchCode">
			<table width="900"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
			<tr>
				<td>
					<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
						  <tr>
							<td width="15%" class="tdleftwhite">&nbsp;เลขที่ใบ PO <span class="style_star">*</span></td>
							<td width="85%"><input name="po_no" type="text" class="style_readonly" value="&nbsp;<?=$v_po_no;?>" readonly=""></td>
						  </tr>
            		</table>
				</td>
			</tr>
			<tr>
				<th>&nbsp;&nbsp;เลือกสินค้าจากรายการที่มีอยู่</th>
			</tr>
			<tr>
				<td> 
					<table width="100%" border="0" align="center">			  
					<tr>
						<td>
							<table width="100%"  border="1" cellspacing="0" cellpadding="0">
							<tr>
								<td width="20%"  class="tdleftwhite">&nbsp;ข้อมูลรายการสินค้าของ PO </td>
								<td width="80%"><input name="ref_po" type="text" value="&nbsp;<?= $ref_po_no; ?>" class="style_readonly"></td>
							</tr>
							</table>						
						</td>
					</tr>		
					<tr>
						<td>
							<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" >
									<tr>
										<td width="4%" rowspan="2" class="tdcenterblack">เลือก<br><input type="checkbox" name="CheckOrUn" onClick="return unOrCheckCheckbox(document.form_po);"></td>
										<td width="7%" rowspan="2" class="tdcenterblack">ประเภท</td>
										<td width="13%" rowspan="2" class="tdcenterblack">กลุ่มของค่าใช้จ่าย</td>
										<td width="10%" rowspan="2" class="tdcenterblack">รหัสสินค้า</td>
										<td width="16%" rowspan="2" class="tdcenterblack">ชื่อสินค้า</td>
										<td width="20%" colspan="3" class="tdcenterblack">รายการบนใบ PO </td>
										<td width="20%" colspan="3" class="tdcenterblack">รายการรับเข้า</td>
										<td width="11%" rowspan="2" class="tdcenterblack">จำนวน<br>
										ที่รับเข้าแล้ว</td>
	                                    <!--									<td width="3%" rowspan="2" class="tdcenterblack">&nbsp;</td>	-->
								    </tr>
									<tr>
									  <td width="5%" class="tdcenterblack">จำนวน</td>
									  <td width="5%" class="tdcenterblack">หน่วย</td>
									  <td width="10%" class="tdcenterblack">ราคาต่อหน่วย</td>
									  <td width="5%" class="tdcenterblack">จำนวน</td>
									  <td width="5%" class="tdcenterblack">หน่วย</td>
									  <td width="10%" class="tdcenterblack">ราคาต่อหน่วย</td>
									</tr>
									</table>								
								</td>
							</tr>
							<tr>
								<td>
									<div style="height:390px; width:900px; overflow:auto; z-index:2; display:block">	
									<table border="1" align="left" cellpadding="0" cellspacing="0">
									<?php
											$i=0;
											while(odbc_fetch_row($curQUE)){
												$id = @odbc_result($curQUE,"id");
												$prod_type = @odbc_result($curQUE,"prod_type");
												$prod_no = @odbc_result($curQUE,"prod_no");
												$prod_name = @odbc_result($curQUE,"prod_name");
												$prod_qty = @odbc_result($curQUE,"prod_qty");
												$prod_unit = @odbc_result($curQUE,"prod_unit");
												$prod_price = @odbc_result($curQUE,"prod_price");
												$gar_qty = @odbc_result($curQUE,"gar_qty");
												$gar_unit = @odbc_result($curQUE,"gar_unit");
												$gar_price = @odbc_result($curQUE,"gar_price");
												$item_code = @odbc_result($curQUE,"item_code");
												$code = @odbc_result($curQUE,"code");
												$show_id = @odbc_result($curQUE,"show_id");
									?>			
									<input type="hidden" value="<?=$prod_type;?>" name="prod_type[<?=$id;?>]">
									<input type="hidden" value="<?=$code;?>" name="code[<?=$id;?>]">
									<input type="hidden" value="<?=$show_id;?>" name="show_id[<?=$id;?>]">									
									<?php 														
												$strSEL = "select  Received_QTY 
																from PODT_Center 
																where PO_NO = '$ref_po_no' 
																and Runno = (select max(Runno) from PODT_Center where PO_NO = '$ref_po_no') 
																and LineNum = '$id'";
												$queSEL = @odbc_exec($MSSQL_connect, $strSEL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
												$received_qty = @odbc_result($queSEL,"Received_QTY");
													if($received_qty == "") $received_qty = 0;
												$quantity = $gar_qty-$received_qty;
												$strSEL = "select description from nonitem_master where item_code = '$item_code'";
												$queSEL = @odbc_exec($MSSQL_connect, $strSEL) or die(alert("เกิดข้อผิดพลาด ทำให้ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
												$item = @odbc_result($queSEL,"description");
									?>
										<tr>
											<td width="35" class="tdcenterwhite"><input type="checkbox" name="checkbox[]" value="<?=$id;?>" <?php if($quantity<=0 && $prod_type != '5' && $prod_type != '6') echo "disabled"; ?>></td>
											<td width="63">&nbsp;
													<?
															switch($prod_type){
																case '1'	:	echo ' BOM';					break;
																case '2'	:	echo ' SubContact';			break;
																case '3'	:	echo ' Product';				break;
																case '4'	:	echo ' Service';				break;
																case '5'	:	echo ' Etc';						break;
																case '6'	:	echo ' Detail';					break;										
															}
													?>
											</td>
											<td width="122">&nbsp;<?=$item; ?><input type="hidden" name="item[<?=$id;?>]" value="<?=$item_code;?>"></td>
											<td width="90">&nbsp;<?=$prod_no; ?><input type="hidden" name="prod_no[<?=$id;?>]" value="<?=$prod_no;?>"></td>
											<td width="148">&nbsp;<?=$prod_name; ?><input type="hidden" name="prod_name[<?=$id;?>]" value="<?=$prod_name;?>"></td>
										  	<td width="43" ><div align="center"><input name="prod_qty[<?=$id;?>]" type="text" onKeyDown="return chkNumberInput('float');" value="<? printf("%d",$prod_qty);?>" size="2"></div></td>
										  	<td width="43" ><div align="center"><input name="prod_unit[<?=$id;?>]" type="text" onKeyUp="return chkStringInput(this);" value="<?=$prod_unit; ?>" size="2" ></div></td>
										  	<td width="89" ><div align="center"><input name="prod_price[<?=$id;?>]" type="text" onKeyDown="return chkNumberInput('float');" value="<? printf("%.2f",$prod_price); ?>" size="10" ></div></td>
										  	<td width="43"><div align="center"><input name="gar_qty[<?=$id;?>]" type="text" onKeyDown="return chkNumberInput('float');" onBlur="if(this.value><?=$quantity;?>) { alert('จำนวนรับเข้าของสินค้ารายการนี้ต้องไม่เกิน <?= $quantity;?> ค่ะ'); this.select(); this.focus(); }" value="<? printf("%d",$quantity); ?>" size="2" ></div></td>
										  	<td width="42"><div align="center"><input name="gar_unit[<?=$id;?>]" type="text" value="<?=$gar_unit;?>"  onKeyUp="return chkStringInput(this);" size="2" ></div></td>
										  	<td width="89" ><div align="center"><input name="gar_price[<?=$id;?>]" type="text" onKeyDown="return chkNumberInput('float');" value="<? printf("%.2f",$gar_price); ?>" size="10" ></div></td>
  										  <td width="93"><div align="center">&nbsp;<?=$received_qty;?></div></td>
										</tr>
										<?			
											$i++;					
											}
										?>				
										<input type="hidden" value="<?=$i;?>" name="amount">
										<input type="hidden" value="<?=$v_po_no;?>" name="v_po_no">
										</table>	
									</div>								
								</td>
							</tr>
							</table>						
						</td>
					</tr>	  
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<th colspan="3">
								<div align="right">                          
									<a onClick="document.form_po.flagAction.value = 'AddCode'; document.form_po.submit();" style="cursor:hand">						 
										<img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" >
									</a>
									<a onClick="document.form_po.reset();" style="cursor:hand">						 
										<img src="../include/button/cancel1.gif" name="butcancel" width="106" height="24" border="0" >
									</a>
								</div>
							</th>
						</tr>
					</table>
				</td>
			</tr>
		  </table>
		</form>
	</div>
</body>
</html>
<?
	sleep(0);
	echo '<script>';
	echo 'document.all.welcome.style.display = "none";';
	echo '</script>';
?>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>

