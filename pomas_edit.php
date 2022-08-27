<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");
		//require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		$empno_user = $_SESSION["empno_user"];		
		$http_host = '172.10.0.16';
		//$PathtoUploadFile = "D:\\iso\\";
		$PathtoUploadFile = "E:\\iso\\";
?>
<html>
	<head>
		<title>*** PO ***</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		<script language='javascript' src='../include/popcalendar.js'></script>				

		<script language='javascript' src='../include_RedThemes/funcPopUp.js'></script>				
		<script language='javascript' src='../include/button_del.js'></script>				

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
		
		<!-- Calculate -->
		<script language="javascript">
				function round(number,X) {
					X = (!X ? 2 : X);
					return Math.round(number*Math.pow(10,X))/Math.pow(10,X);
				}		
		
				function cal_pomas(){	
					var flo_price = document.getElementById("flo_price").value;
					var discount_percent = document.getElementById("discount_percent").value;
					var discount_baht = document.getElementById("discount_baht").value;
					
					var flo_calculate1 = flo_price;
					var flo_calculate2 = 0;
						if((flo_price!="")&&(discount_percent!="")){			
			
								var vars = discount_percent.split("%");
								for (var i=0;i<vars.length;i++) {
									flo_calculate2 = flo_calculate2 + (flo_calculate1 * vars[i] /100);
									flo_calculate1 = flo_calculate1 - flo_calculate2;
								  } 
								  document.getElementById("discount_baht").value = round(flo_calculate2,2);
						}else if((flo_price!="")&&(discount_baht!="")){
								document.getElementById("discount_percent").value = round((discount_baht*100)/flo_price,2);								
						}else if(flo_price==""){
									alert("ไม่พบราคาของสินค้า จึงไม่สามารถคำนวณส่วนลดได้ค่ะ");
						}else{
									alert("ไม่พบส่วนลด จึงไม่สามารถคำนวณ ได้ค่ะ");
						}
				}

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
		<!-- LOV : CostCenter -->			
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
	<?
	
		$flagAction = @$_POST["flagAction"];
		if(@$flagAction =='UpCode'){
			$po_no=@$_POST["po_no"];
			$po_date=@$_POST["po_date"];
			$supplier_id=@$_POST["supplier_id"];
			$your_ref=@$_POST["your_ref"];
			$our_ref=@$_POST["our_ref"];
			$despatch_to=@$_POST["despatch_to"];
			$delivery_time=@$_POST["delivery_time"];	
			$delivery_date=@$_POST["delivery_date"];			
			$PayCode=@$_POST["PayCode"];
			$payment=@$_POST["payment"];
			$discount_percent=@$_POST["discount_percent"];
			$discount_baht=@$_POST["discount_baht"];
			$po_remark=@$_POST["po_remark"];
			$flag_vat=@$_POST["flag_vat"];
			$flag_boi=@$_POST["flag_boi"];
			$accid=@$_POST["accid"];
			$costid=@$_POST["costid"];
			$for_ref=@$_POST["for_ref"];
			$po_fileold=@$_POST["po_fileold"];
			$po_file2old=@$_POST["po_file2old"];
			$po_file3old=@$_POST["po_file3old"];
			$redhead=@$_POST["redhead"];
			if($discount_percent=="")$discount_percent=0;
			if($discount_baht=="")$discount_baht=0;								
			$file_name1 = $_FILES['po_file']['name'];  
			$file_name2 = $_FILES['po_file2']['name'];  
			$file_name3 = $_FILES['po_file3']['name'];  
			$boi = '';
			if ($flag_boi == '1')
			{$boi = 'B';} 		
			else if ($flag_boi == '2')
			{$boi = '2';} 
			else if ($flag_boi == '3')
			{$boi = '3';} 
				
			$txt_up = "update po_master set 
									po_date=to_date('$po_date','dd-mm-yyyy'),
									supplier_id='$supplier_id',
									your_ref='$your_ref',
									our_ref='$our_ref',
									despatch_to='$despatch_to',
									delivery_time='$delivery_time',
									delivery_date=to_date('$delivery_date','dd-MM-yyyy'),
									PayCode='$PayCode',
									payment='$payment',
									discount_percent='$discount_percent',
									discount_baht='$discount_baht',
									po_remark='$po_remark',
									po_status='1',
									flag_vat='$flag_vat',
									boi_flg='$boi',
									accid='$accid',
									costid='$costid',
									for_ref='$for_ref',
									redhead='$redhead',
									redheadspecial='',
									approve_date='', ";
			if(is_uploaded_file($_FILES['po_file']['tmp_name'])){     
				$path_file = $PathtoUploadFile."po_thai";
				if($po_fileold != ""){
					@unlink("$path_file\\$po_fileold");
				}
				$type_file =substr($file_name1,-3,3);
				$filename = $po_no.".".$type_file;
				
				if(!move_uploaded_file($_FILES['po_file']['tmp_name'],"$path_file\\$filename")){
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'alert ("พบปัญหาไม่สามารถ Attach ไฟล์ po(แสดงรายการทั้งหมด) ได้ค่ะ");';
						echo '</script>';
				}else{
						$txt_up .="po_file='$filename',";									
				}
			}
			if(is_uploaded_file($_FILES['po_file2']['tmp_name'])){     
				$path_file2 = $PathtoUploadFile."po_thai2";
				if($po_file2old != ""){
					@unlink("$path_file2\\$po_file2old");
				}
				$type_file =substr($file_name2,-3,3);
				$filename2 = $po_no.".".$type_file;
				
				if(!move_uploaded_file($_FILES['po_file2']['tmp_name'],"$path_file2\\$filename2")){				
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'alert ("พบปัญหาไม่สามารถ Attach ไฟล์ po(ปิดราคา) ได้ค่ะ");';
						echo '</script>';
				}else{
						$txt_up .="po_file2='$filename2',";									
				}
			}
			if(is_uploaded_file($_FILES['po_file3']['tmp_name'])){     
				$path_file3 = $PathtoUploadFile."po_thai3";
				if($po_file3old != ""){
					@unlink("$path_file3\\$po_file3old");
				}
				$type_file =substr($file_name3,-3,3);
				$filename3 = $po_no.".".$type_file;
				
				if(!move_uploaded_file($_FILES['po_file3']['tmp_name'],"$path_file3\\$filename3")){				
						echo '<script language="JavaScript" type="text/JavaScript">';
						echo 'alert ("พบปัญหาไม่สามารถ Attach ไฟล์ po(ปิดท้าย) ได้ค่ะ");';
						echo '</script>';
				}else{
						$txt_up .="po_file3='$filename3',";									
				}
			}
			$txt_up .= "last_user='$empno_user',
								last_date=sysdate
								where po_no='$po_no'";	
			//echo $txt_up;					
			$exe_up = @odbc_exec($conn,$txt_up) or die(alert("เกิดข้อผิดพลาดขึ้นกับระบบ ทำให้ไม่สามารถอัพเดท PO นี้ได้ค่ะ"));
			$exe_commit = @odbc_exec($conn,"commit");
		    echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'alert ("บันทึกข้อมูลเรียบร้อยแล้วค่ะ");';
			echo '</script>';				
		}			
		
		$flag= @$_GET["flag"];
		if($flag=="edit"){
			$_SESSION["sespk_no"] = $_GET["po_no"];
			echo '<script language="JavaScript" type="text/JavaScript">';
			echo 'parent.mid_frame.location.href = "./pr_head.php?tabno=2";';
			echo '</script>';										
		}			
	
		$po_no= $_SESSION["sespk_no"];
		$str_po_master = "select to_char(po.po_date,'DD-MM-YYYY') po_date,s.company_name,po.supplier_id,po.for_ref,
												po.your_ref,po.our_ref,po.despatch_to,po.delivery_time,po.paycode,po.payment,
												po.discount_percent,po.discount_baht,po.po_remark,flag_vat,accid,costid,
												po.po_file,po.po_file2,po.po_file3,po.redhead,po.ref_po_no,po.po_company,to_char(po.delivery_date,'DD-MM-YYYY') delivery_date,
												po.boi_flg
											from po_master po,supplier s 
											where po.supplier_id= s.supplier_id(+) 
											and po_no= '$po_no'";
		$cur_po_master = odbc_exec($conn, $str_po_master );
		$po_date =odbc_result($cur_po_master,"po_date");		
		$supplier_show =odbc_result($cur_po_master,"company_name");		
		$supplier_id =odbc_result($cur_po_master,"supplier_id");		
		$your_ref =odbc_result($cur_po_master,"your_ref");		
		$our_ref =odbc_result($cur_po_master,"our_ref");		
		$despatch_to =odbc_result($cur_po_master,"despatch_to");		
		$delivery_time =odbc_result($cur_po_master,"delivery_time");		
		$delivery_date =odbc_result($cur_po_master,"delivery_date");	
		$paycode = odbc_result($cur_po_master,"PayCode");
		$payment =odbc_result($cur_po_master,"payment");		
		$discount_percent =odbc_result($cur_po_master,"discount_percent");		
		$discount_baht =odbc_result($cur_po_master,"discount_baht");		
		$po_remark =odbc_result($cur_po_master,"po_remark");		
		$flag_vat =odbc_result($cur_po_master,"flag_vat");		
		$boi_flg =odbc_result($cur_po_master,"boi_flg");		
		$accid =odbc_result($cur_po_master,"accid");		
		$costid =odbc_result($cur_po_master,"costid");		
		$for_ref =odbc_result($cur_po_master,"for_ref");		
		$po_file =odbc_result($cur_po_master,"po_file");		
		$po_file2 =odbc_result($cur_po_master,"po_file2");		
		$po_file3 =odbc_result($cur_po_master,"po_file3");		
		$redhead =odbc_result($cur_po_master,"redhead");		
		$ref_po_no =odbc_result($cur_po_master,"ref_po_no");
        $po_company = odbc_result($cur_po_master,"po_company");
		
		$str_sum_po_detail = "select sum((prod_qty*prod_price)-nvl(discount_baht,0)) flo_price 
						from po_details 
						where po_no= '$po_no' ";
		$cur_sum_po_detail = odbc_exec($conn, $str_sum_po_detail );
		$flo_price = odbc_result($cur_sum_po_detail,"flo_price");		
	
		$str_query_podet = "select id,prod_no,prod_name,prod_qty,
													prod_unit,prod_price,nvl(discount_baht,0) discount_baht ,
													code,prod_type,item_code,gar_qty,gar_unit,gar_price
												from po_details 
												where po_no='$po_no' order by id";
		$cur_query_podet = odbc_exec($conn,$str_query_podet);	
		
		$strCOU = "select count(*) from po_details where po_no='$po_no'";
		$curCOU = odbc_exec($conn,$strCOU);	
		$numrow = odbc_result($curCOU, "count(*)");				
	?>
		<br>
		<center>
			<form name="form_po" action="pomas_edit.php" method="post" enctype="multipart/form-data">
				<input name="flagAction" type="hidden" value="UpCode">						
				<table width="975"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
				<tr>
					<th> &nbsp;&nbsp;ปรับปรุง PO <?php if($po_company=="T") echo "Transtek"; else echo "Supreme"; ?></th>
					<th><div align="right">&nbsp;</div></th>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
						<tr>
							<td>
								<table width="100%"  border="1" cellpadding="0" cellspacing="0">								
								<tr>
									<td width="124" class="tdleftwhite"> &nbsp;เลขที่ PO <span class="style_star">*</span> </td>
								<?php if($po_company=="S") { ?>
									<td width="353"><input name="po_no" type="text"  class="style_readonly" value="<? echo $po_no; ?>" size="31" readonly=""></td>
								    <td width="105">&nbsp;อ้างอิง PO เก่าเลขที่ </td>
								    <td width="350"><input name="ref_po_no" type="text"  class="style_readonly" id="ref_po_no" value="<? echo $ref_po_no; ?>" size="31" readonly=""></td>
								<?php }else{ ?>			
									<td colspan="3"><input name="po_no" type="text"  class="style_readonly" value="<? echo $po_no; ?>" size="31" readonly=""></td>
								<?php } ?>
								</tr>
	
								<tr>
									<td class="tdleftwhite">&nbsp;Date <span class="style_star">*</span> </td>
									<td colspan="3" class="tdleftwhite">
										<input name="po_date" type="text"  class="style_readonly"value="<? echo @$po_date; ?>" size="8" readonly="" >						  
										<script language='javascript'>
											if (!document.layers) {
												document.write("<img src=\"../include/images/date_icon.gif\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, form_po.po_date, \"dd-mm-yyyy\")'>");
											}
										</script>
										<input name="flag_boi" type="radio" value="1" <? if($boi_flg=="B") echo "checked"; ?>>BOI
										<input name="flag_boi" type="radio" value="0" <? if($boi_flg=="") echo "checked"; ?>> ไม่เป็น BOI	
										<input name="flag_boi" type="radio" value="2" <? if($boi_flg=="2") echo "checked"; ?>>BOI Factory 2
										<input name="flag_boi" type="radio" value="3" <? if($boi_flg=="3") echo "checked"; ?>> ไม่เป็น BOI Factory 2	
										</td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;To Messers <span class="style_star"> *</span> </td>
									<td colspan="3">
										<input name="supplier_id" type="text" value="<? echo @$supplier_id; ?>"  size="10" class="style_readonly" readonly=""><input name="supplier_show" type="text" value="<? echo @$supplier_show; ?>"  size="70" class="style_readonly" readonly=""><input name="supplier_but" type="button" value="&nbsp;...&nbsp;" onClick="lovSupplier();">									</td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;Your Ref. </td>
									<td colspan="3"><input name="your_ref" type="text" onKeyUp="return check_string(document.form_po.your_ref,50);" value="<? echo @$your_ref; ?>"  size="70" maxlength="50"></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;Our Ref. </td>
									<td colspan="3"><input name="our_ref" type="text" onKeyUp="return check_string(document.form_po.our_ref,50);" value="<? echo @$our_ref; ?>"  size="70" maxlength="50"></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;Despatch to </td>
									<td colspan="3">
										<input name="despatch_to" type="text" onKeyUp="return check_string(document.form_po.despatch_to,50);" value="<? echo @$despatch_to; ?>"  size="70" maxlength="50"><input type="button" name="despatch_tobut" value="&nbsp;...&nbsp;" onClick="return cal_descpatch_to(document.form_po.despatch_to);">									</td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;Delivery Time</td>
									<td colspan="3">
										<input name="delivery_time" type="text" onKeyUp="return check_string(document.form_po.delivery_time,50);" value="<? echo @$delivery_time; ?>"  size="70" maxlength="50"><input type="button" name="delivery_timebut" value="&nbsp;...&nbsp;" onClick="return cal_delivery_time(document.form_po.delivery_time);">									
										&nbsp;Delivery Date&nbsp;<input name="delivery_date" type="text"  class="style_readonly"value="<?  echo @$delivery_date; ?>" size="8" readonly="" >						  
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
									<select name="PayCode" id="PayCode"  >
									<?php	
											$strSEL = "select payment_name, payment_description from Payment_method where status = 'Y'";
											$queSEL = @odbc_exec($conn,$strSEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
									?>	
											<option value="none">กรุณาระบุ Payment Term</option> 
									<?php 
											while(@odbc_fetch_row($queSEL)){
												$PayCode = @odbc_result($queSEL,"payment_name");
									?>	
											<option value="<?=$PayCode;?>" <?php if($PayCode==$paycode) echo "selected"; ?> ><?= $PayCode; ?></option>
									<?php	
											}	
									?>
									</select>		
									<input type="text" name="payment" id="payment" value="<?=@$payment;?>" size="70" maxlength="120">									</td>
								  </tr>
								<tr>
									<td class="tdleftwhite">&nbsp;Dicount</td>
									<td colspan="3">
										<span class="tdleftwhite">เป็นจำนวน(%) 
											<input name="discount_percent" type="text"  onKeyDown="return check_number();" value="<? echo @$discount_percent; ?>" size="10" maxlength="20" > คิดเป็นเงิน(บาท)
											<input name="discount_baht" type="text"  onKeyDown="return check_number();" value="<? echo @$discount_baht; ?>" size="16" maxlength="15" > 
										</span>
										<input name="but_cal" type="button" value="Calculate" onClick="cal_pomas();">
										<input name="flo_price" type="hidden" size="5"  value="<? echo $flo_price;?>">									</td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;ภาษี 7%</td>
									<td colspan="3" class="tdleftwhite">
										<input name="flag_vat" type="radio" value="1" <? if($flag_vat=="1") echo "checked"; ?>>คำนวณ VAT
										<input name="flag_vat" type="radio" value="0" <? if($flag_vat=="0") echo "checked"; ?>> ไม่คำนวณ VAT
										<input name="flag_vat" type="radio" value="2" <? if($flag_vat=="2") echo "checked"; ?>>ไม่อยู่ในระบบ 
										<input name="flag_vat" type="radio" value="3" <? if($flag_vat=="3") echo "checked"; ?>>ไม่แสดง									</td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;รหัสบัญชี </td>
									<td colspan="3">
										<textarea name="accid" cols="85" rows="3" onKeyUp="return check_string(document.form_po.accid,200);"><? echo @$accid; ?></textarea><input name="acc_but" type="button" value="&nbsp;...&nbsp;" onClick="lovCodeAccount()">									</td>
								</tr>					  
								<tr>
									<td class="tdleftwhite">&nbsp;COST CENTER </td>
									<td colspan="3">
										<textarea name="costid" cols="85" rows="3" onKeyUp="return check_string(document.form_po.costid,200);"><? echo @$costid; ?></textarea><input name="cost_but" type="button" value="&nbsp;...&nbsp;" onClick="lovCostCenter()">									</td>
								</tr>					  
								<tr>
									<td class="tdleftwhite">&nbsp;For </td>
									<td colspan="3"><textarea name="for_ref" cols="85" rows="3" onKeyUp="return check_string(document.form_po.for_ref,500);"><? echo @$for_ref; ?></textarea></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;Remark</td>
									<td colspan="3"><textarea name="po_remark" cols="85" rows="3" onKeyUp="return check_string(document.form_po.po_remark,300);"><? echo @$po_remark; ?></textarea></td>
								</tr>
								<tr>
									<td class="tdleftwhite">&nbsp;<span class="thai_baht">หัวแดง (ปกติ) </spa_n></td>
									<td colspan="3"><input name="redhead" type="text" onKeyUp="return checkstring(document.form_po.redhead,100);" value="<? echo @$redhead; ?>"  size="70" maxlength="100"></td>
								</tr>									  
								<tr>
									<td width="124" class="tdleftwhite">&nbsp;ไฟล์ที่ Upload <br>โดยผู้ใช้งาน (ทั้งหมด) </td>
									<td colspan="3">
										<?  if($po_file != ""){ ?>			
											<a onClick="window.open('\\\\<?= $http_host; ?>\\iso\\po_thai\\<? echo $po_file; ?>');"  target="_blank"  style="cursor:hand" >
												<span class="style_text">คลิ๊กที่นี่</span> เพื่อดูใบ PO ที่ Upload โดยผู้ใช้ แบบแสดงรายการทั้งหมด											</a><br>
										<?  } ?>							
										<input name="po_file" type="file" size="50" ><input name="po_fileold" type="hidden" value="<? echo $po_file; ?>">	
										<span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>									</td>
								</tr>
								<tr>
									<td class="tdleftwhite">
										&nbsp;ไฟล์ที่ Upload <br>โดยผู้ใช้งาน (ปิดราคา)									</td>
									<td colspan="3">
										<?  if($po_file2 != ""){ ?>			
											<a onClick="window.open('\\\\<?= $http_host; ?>\\iso\\po_thai2\\<? echo $po_file2; ?>');"  target="_blank"  style="cursor:hand" >
												<span class="style_text">คลิ๊กที่นี่</span> เพื่อดูใบ PO ที่ Upload โดยผู้ใช้ แบบปิดราคา											</a>
											<br>
										<?  } ?>							
										<input name="po_file2" type="file" size="50" ><input name="po_file2old" type="hidden" value="<? echo $po_file2; ?>">	
										<span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>									</td>
								</tr>
								<tr>
									<td class="tdleftwhite">
										&nbsp;ไฟล์ที่ Upload <br>โดยผู้ใช้งาน (ปิดท้าย)									</td>
									<td colspan="3">
										<?  if($po_file3 != ""){ ?>			
											<a onClick="window.open('\\\\<?= $http_host; ?>\\iso\\po_thai3\\<? echo $po_file3; ?>');"  target="_blank"  style="cursor:hand" >
												<span class="style_text">คลิ๊กที่นี่</span> เพื่อดูใบ PO ที่ Upload โดยผู้ใช้ แบบปิดท้าย											</a>
											<br>
										<?  } ?>							
										<input name="po_file3" type="file" size="50" ><input name="po_file3old" type="hidden" value="<? echo $po_file3; ?>">	
										<span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>									</td>
								</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
								<tr bgcolor="999999">
									<td colspan="13" class="tdleftblack">รายละเอียดสินค้า</td>
								</tr>
								<tr>
								  <td width="25" rowspan="2" class="tdcenterwhite">Del</td>
								  <td width="25" rowspan="2" class="tdcenterwhite">Edit</td>
								  <td width="25" rowspan="2" class="tdcenterwhite">Add Jobs</td>
								  <td width="60" rowspan="2" class="tdcenterwhite">ประเภท</td>
								  <td width="130" rowspan="2" class="tdcenterwhite">กลุ่มของค่าใช้จ่าย</td>
								  <td width="100" rowspan="2" class="tdcenterwhite">รหัสสินค้า</td>
								  <td rowspan="2" class="tdcenterwhite">ชื่อรายการ</td>
								  <td colspan="3" class="tdcenterwhite">รายการที่แสดงบนใบ PO </td>
								  <td colspan="3" class="tdcenterwhite">รายการที่ใช้ในการรับเข้า</td>
<?php 	if($ref_po_no <> ''){	?>	<td width="75" rowspan="2" class="tdcenterwhite">จำนวน<br>ที่รับเข้าแล้ว</td>  <?php } ?>
								  </tr>
								<tr>
									<td width="50" class="tdcenterwhite">จำนวน</td>
								  	<td width="80" class="tdcenterwhite">ราคาต่อหน่วย<br></td>
									<td width="80" class="tdcenterwhite">ราคารวม</td>
								    <td width="50" class="tdcenterwhite">จำนวน</td>
								    <td width="80" class="tdcenterwhite">ราคาต่อหน่วย</td>
								    <td width="80" class="tdcenterwhite">ราคารวม</td>
								</tr>
								<?
									$sum_total=0;
									$sum_total_gar= 0;
									while(odbc_fetch_row($cur_query_podet)){
										$id = odbc_result($cur_query_podet, "id");
										$prod_no = odbc_result($cur_query_podet, "prod_no");
										$prod_name = odbc_result($cur_query_podet, "prod_name");
										$prod_qty = odbc_result($cur_query_podet, "prod_qty");
										$prod_unit = odbc_result($cur_query_podet, "prod_unit");
										$prod_price = odbc_result($cur_query_podet, "prod_price");
										$discount_baht = odbc_result($cur_query_podet, "discount_baht");
										$code = odbc_result($cur_query_podet, "code");
										$prod_type = odbc_result($cur_query_podet, "prod_type");
										$item_code = odbc_result($cur_query_podet, "item_code");
										$gar_qty = odbc_result($cur_query_podet, "gar_qty");
										$gar_unit = odbc_result($cur_query_podet, "gar_unit");
										$gar_price = odbc_result($cur_query_podet, "gar_price");
										
										if($prod_type=="2"){
											$strQUEDetailSubjob = "select pd.subjob_show || '=' || sj.qty || '/' || sj.cost || '.-' subjob_show
																						from po_details_subjob sj, mrp_pd pd
																						where sj.subjob=pd.subjob 
																						and po_no= '$po_no'
																						and id='$id'";
											$curQUEDetailSubjob = odbc_exec($conn, $strQUEDetailSubjob );
											$subjob_show="";
											while(odbc_fetch_row($curQUEDetailSubjob)){			
												if($subjob_show=="")$subjob_show =odbc_result($curQUEDetailSubjob,"subjob_show");	
												else $subjob_show .= ", ".odbc_result($curQUEDetailSubjob,"subjob_show");	
											}									
											$prod_name .= "<br>(".$subjob_show.")";
										}

										if((($prod_price*$prod_qty)-$discount_baht)==0) $prod_price = 0;
										else $prod_price = (($prod_price*$prod_qty)-$discount_baht)/$prod_qty;
										
										$total_price = $prod_price * $prod_qty;
										$total_price_gar = $gar_price * $gar_qty;

										$sum_total += $total_price;										
										$sum_total_gar += $total_price_gar;										
										
								?>												  
								<tr>
								<?php 
										$strSEL = "select  Received_QTY 
														from PODT_Center 
														where PO_NO = '$ref_po_no' 
														and Runno = (select max(Runno) from PODT_Center where PO_NO = '$ref_po_no') 
														and LineNum = '$id'";
										//$queSEL = @odbc_exec(	$MSSQL_connect, $strSEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
										$read = @odbc_result($queSEL,"Received");
										//$received_qty = @odbc_result($queSEL,"Received_QTY"); 
								?>
									<td class="tdcenterwhite">
									<?php if($read != "Y"){ ?>
										<a onClick="remote_del('podet_delcode.php?id=<?= $id;?>&po_no=<?= $po_no; ?>&prod_type=<?= $prod_type; ?>');" style="cursor:hand">
											<img src="../include/images/del_icon.png" width="25" height="25" border="0"></a>									
									<?php  }else{ echo "&nbsp;"; } ?></td>
									<td>
									<?php  if($read != "Y"){  ?>
										<a onClick="popupLevel1('podet_up.php?id=<?= $id;?>&po_no=<?= $po_no; ?>&ref_po_no=<?= $ref_po_no; ?>',550,500,100,200);" style="cursor:hand">
											<img src="../include/images/edit_icon.png" width="25" height="25" border="0"></a>
									<?php  }else{ echo "&nbsp;"; }  ?></td>		
                                    <td class="tdcenterwhite">
									<a onClick="popupLevel1('po_details_job.php?id=<?= $id;?>&po_no=<?= $po_no; ?>',900,650,100,200);" style="cursor:hand">
									<img src="../include/images/Add_Jobs.GIF" width="25" height="25" border="0"></a>
									</td>									
									<td>
									<?
											switch($prod_type){
												case '1'	:	echo ' BOM';					break;
												case '2'	:	echo ' SubContact';		break;
												case '3'	:	echo ' Product';				break;
												case '4'	:	echo ' Service';				break;
												case '5'	:	echo ' Etc';						break;
												case '6'	:	echo ' Detail';					break;										
											}
									?></td>					
									<td>
									<?php 
											$strSEL = "select Description from NONITEM_MASTER where Item_Code = '$item_code'";
											$queSEL = @odbc_exec($MSSQL_connect,$strSEL);
											$item = @odbc_result($queSEL,"Description");
									?>
											&nbsp;<? echo $item; ?>
									</td>
									<td>&nbsp;<? echo $prod_no; ?></td>
									<td>&nbsp;<? echo $prod_name; ?></td>
									<td><div align="center"><? if($prod_qty=="") echo '&nbsp;'; else if(floor($prod_qty)==$prod_qty)echo floor($prod_qty); else echo $prod_qty; echo " ".$prod_unit; ?></div></td>
									<td><div align="right"><? if($prod_price=="") echo '&nbsp;'; else echo number_format($prod_price,2,".",","); ?></div></td>
									<td><div align="right"><? if($total_price=="") echo '&nbsp;'; else echo number_format($total_price,2,".",","); ?></div></td>
									<td><div align="center"><? if(($prod_type=='5')||($prod_type=='6'))echo "-"; else if(floor($gar_qty)==$gar_qty)echo floor($gar_qty); else echo $gar_qty; echo " ".$gar_unit; ?></div></td>
									<td><div align="right"><? if(($prod_type=='5')||($prod_type=='6'))echo "-"; else echo number_format($gar_price,2,".",","); ?></div></td>
									<td><div align="right"><? if(($prod_type=='5')||($prod_type=='6'))echo "-"; else echo number_format($total_price_gar,2,".",","); ?></div></td>
<?php 	/*if($ref_po_no <> ''){	
		if($received_qty =="" && $gar_unit  !="")
			$received_qty = "0 ".$gar_unit;
		else if($received_qty=="" && $gar_unit=="")
			$received_qty ="";	
		else 	$received_qty = $received_qty." ".$gar_unit;
		*/
?>	
		<td style="text-align:center">&nbsp;</td> 
<?php //} ?>
								</tr>
								<?
									}
								?>				
								<tr>
									<td colspan="7" > <strong> &nbsp;<span class="thai_baht">ราคาที่ระบุเป็นราคาที่หักส่วนลด(ตามรายการ) </span></strong></td>
									<td class="thai_baht"><div align="right"><? echo number_format($sum_total,2,".",","); ?></div></td>
								    <td colspan="2" >&nbsp;</td>
							        <td class="thai_baht"><div align="right"><? echo number_format($sum_total_gar,2,".",","); ?></div></td>
							        <td>&nbsp;</td>
								</tr>				  
								<tr>
									<td colspan="13" class="tdleftblack">
									  <div align="right">	
									  		<? 
													if($ref_po_no <> ''){
											?>
											  <input type="button" name="btnRefPoNo" onClick="popupLevel1('podet_ref_PONO.php?po_no=<?=$po_no; ?>&ref_po_no=<?=$ref_po_no; ?>',900,575,50,50);" value="เพิ่มสินค้า" style="width:150px; height:27px; cursor:hand;"   target="_blank">
											<?
											  }else{
											?>
											  <input type="button" name="btnProduct" onClick="popupLevel1('podet_addbom.php?po_no=<?=$po_no; ?>',900,575,50,50);" value="เพิ่มสินค้าประเภท BOM, Product, Service" style="width:260px; height:27px; cursor:hand;"   target="_blank">
										<!--<input type="button" name="btnSubContact" onClick="popupLevel1('podet_addsubc.php?po_no=<?= $po_no; ?>',800,575,50,90);" value="เพิ่มสินค้าประเภท SubContract" style="width:180px; height:27px; cursor:hand;"   target="_blank">-->									  
											  <input type="button" name="btnEtc" onClick="popupLevel1('podet_addkey.php?po_no=<?= $po_no; ?>',905,502,80,50);" value="เพิ่มสินค้าประเภท Detail, Etc" style="width:170px; height:27px; cursor:hand;"   target="_blank">
											<input type="button" name="btnPR" onClick="popupLevel1('podet_addpr.php?po_no=<?= $po_no; ?>',950,600,50,30);" value="เพิ่มสินค้าตาม PR (หลายประเภท)" style="width:180px; height:27px; cursor:hand;"   target="_blank">
											<?
											}
											?>
									  </div>	</td>
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
											<a onClick="return check_po(document.form_po);" style="cursor:hand">						 
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
					</td>
				</tr>
			  </table>
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
				 <tr>
                    <td colspan="2" ><span class="style_text">* กรุณาเลือกรายการสินค้าและจัดทำลำดับรายการสินค้าให้เรียบร้อยก่อนทำการบันทึกข้อมูล จำนวนสินค้า/Job เนื่องจากถ้ามีการลบรายการสินค้าออกจากระบบข้อมูล <BR/>&nbsp;&nbsp;จำนวนสินค้า/Job จะหายไปด้วย</span></td>
                  </tr>
                </table>
				<br>
			</form>		
		</center>
		<script language="JavaScript" type="text/JavaScript">
			document.form_po.your_ref.focus();
		</script>
	</body>
</html>
<?
}else{
		include("../include_RedThemes/SessionTimeOut.php");
}
?>









