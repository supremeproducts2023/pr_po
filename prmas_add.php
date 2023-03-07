<?php
  	@session_start();
	if(isset($_SESSION["valid_userprpo"])) {
			require_once("../include_RedThemes/MSSQLServer_connect_2.php");
		    require_once("../include/alert.php");
			$empno_user = $_SESSION["empno_user"];
			$ses_deptno = $_SESSION["ses_deptno"];
			$roles_user = $_SESSION["roles_user"];
			$PathtoUploadFile = "E:\\iso\\";
?>
<html><head>
<title>*** PR ***</title>
		<!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-874"> -->
		<meta http-equiv=Content-Type content="text/html; charset=utf-8">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		 <script language='javascript' src='./include/radio_check.js'></script>							
		<script language='javascript' src='../include/popcalendar.js'></script>				

				<!-- Check Not null -->
				<script language='javascript'>
				function check_pr(obj,pr_type){
							if(obj.pr_date.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.pr_date.focus();
								return false;
							}			
							if(obj.PayCode.value == "none"){
								alert("กรุณาระบุ Payment Term ด้วยค่ะ");
								obj.PayCode.focus();
								return false;
							}
							if(pr_type=="T"){
								if(obj.obj_name81.value=="") {	
									alert("กรุณาระบุวัตถุประสงค์ด้วยค่ะ");
									return false;
								}									
							}else{
									if((!obj.flag_obj[0].checked)&&(!obj.flag_obj[1].checked)&&(!obj.flag_obj[2].checked)&&
										(!obj.flag_obj[3].checked)&&(!obj.flag_obj[4].checked)&&(!obj.flag_obj[5].checked)&&(!obj.flag_obj[6].checked)){  	
										alert("กรุณาเลือกวัตถุประสงค์ด้วยค่ะ");
										obj.flag_obj[0].focus();
										return false;
									}		
									if((obj.flag_obj[0].checked)&&((obj.obj_name11.value=="")||((obj.obj_name12.value=="")&&(obj.obj_name13.value=="")))){  	
										alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
										obj.obj_name11.focus();
										return false;
									}	
									if((obj.flag_obj[1].checked)&&((obj.obj_name21.value=="")||(obj.obj_name22.value==""))){  	
										alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
										obj.obj_name21.focus();
										return false;
									}	
									if((obj.flag_obj[2].checked)&&((obj.obj_name31.value=="")||(obj.obj_name32.value=="")||(obj.obj_name33.value==""))){  	
										alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
										obj.obj_name31.focus();
										return false;
									}	
									if((obj.flag_obj[3].checked)&&(obj.obj_name41.value=="")){  	
										alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
										obj.obj_name41.focus();
										return false;
									}	
									if((obj.flag_obj[4].checked)&&(obj.obj_name51.value=="")){  	
										alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
										obj.obj_name51.focus();
										return false;
									}	
									if((obj.flag_obj[6].checked)&&(obj.obj_name61.value=="")){  	
										alert("กรุณากรอกรายละเอียดของวัตถุประสงค์ให้ครบค่ะ");
										obj.obj_name61.focus();
										return false;
									}	
							}
														
							if(obj.estimate_day.value==""){  	
								alert("กรุณาระบุวันด้วยค่ะ");
								obj.estimate_day[0].focus();
								return false;
							}		
				
							if((!obj.vat_include[0].checked)&&(!obj.vat_include[1].checked)){  	
								alert("กรุณาระบุราคาสินค้าที่ ต้องการกรอกว่าเป็นราคา รวมหรือยังไม่รวม VAT ด้วยค่ะ");
								obj.vat_include[0].focus();
								return false;
							}								
							obj.submit();
				}
		 </script>		
		 
		<!-- Calculate -->
		<script language='javascript'>
				function flag_obj_check(obj,ck_num){
							if(ck_num !=1){
									obj.obj_name11.value='';
									obj.obj_name12.value='';
									obj.obj_name13.value='';
									obj.obj_name11.style.display = "none"; 
									obj.obj_name12.style.display = "none"; 
									obj.obj_name13.style.display = "none"; 
							}
							if(ck_num !=2){
									obj.obj_name21.value='';
									obj.obj_name22.value='';
									obj.obj_name21.style.display = "none"; 
									obj.obj_name22.style.display = "none"; 
									obj.acc_but.style.display = "none"; 
							}
							if(ck_num !=3){
									obj.obj_name31.value='';
									obj.obj_name32.value='';
									obj.obj_name33.value='';
									obj.obj_name31.style.display = "none"; 
									obj.obj_name32.style.display = "none"; 
									obj.obj_name33.style.display = "none"; 
									obj.acc_but2.style.display = "none"; 
							}
							if(ck_num !=4){
									obj.obj_name41.value='';
									obj.obj_name41.style.display = "none"; 
							}
							if(ck_num !=5){
									obj.obj_name51.value='';
									obj.obj_name51.style.display = "none"; 
							}
							if(ck_num !=6){
									obj.obj_name61.value='';
									obj.obj_name61.style.display = "none"; 
							}
			
							if(ck_num==1){
									obj.obj_name11.style.display = ""; 
									obj.obj_name12.style.display = ""; 
									obj.obj_name13.style.display = ""; 
									obj.acc_but.style.display = "none"; 
									obj.acc_but2.style.display = "none"; 
							}else if(ck_num==2){
									obj.obj_name21.style.display = ""; 
									obj.obj_name22.style.display = ""; 
									obj.acc_but.style.display = ""; 
									obj.acc_but2.style.display = "none"; 
							}else if(ck_num==3){
									obj.obj_name31.style.display = ""; 
									obj.obj_name32.style.display = ""; 
									obj.obj_name33.style.display = ""; 
									obj.acc_but.style.display = "none"; 
									obj.acc_but2.style.display = ""; 
							}else if(ck_num==4){
									obj.obj_name41.style.display = ""; 
									obj.acc_but.style.display = "none"; 
									obj.acc_but2.style.display = "none"; 
							}else if(ck_num==5){
									obj.obj_name51.style.display = ""; 
									obj.acc_but.style.display = "none"; 
									obj.acc_but2.style.display = "none"; 
							}else if(ck_num==6){
									obj.obj_name61.style.display = ""; 
									obj.acc_but.style.display = "none"; 
									obj.acc_but2.style.display = "none"; 
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
					if(values[2]!= ""){ document.getElementById("PayCode").value = values[2];  }															
					if(values[3]!= ""){ document.getElementById("pr_payment").value = values[3];  }
				 }
			}       
		</script>
		<!-- LOV : Emp -->			
		<script type="text/javascript" language="javascript">
			function openEmp(type_use){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_emp.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovEmp(){
				returnvalue = openEmp(''); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= ""){ document.getElementById("empno").value = values[0];  }else{ document.getElementById("empno").value =''; }
					if(values[1]!= ""){ document.getElementById("empno_show").value = values[1];  }	else{ document.getElementById("empno_show").value =''; }				
				 }
			}       

			function lovMng(){
				returnvalue = openEmp('MngPr_po'); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= ""){ document.getElementById("mngno").value = values[0];  }
					if(values[1]!= ""){ document.getElementById("mngno_show").value = values[1];  }					
				 }
			}       
		</script>
		<!-- LOV : Dept_use -->			
		<script type="text/javascript" language="javascript">
			function openDeptUse(){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_dept_use.php?themes=DefaultBlue','newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovDeptUse(){
				returnvalue = openDeptUse(); 
				if (returnvalue != null){ 
					var values =  returnvalue.split("|-|");
					if(values[0]!= ""){ document.getElementById("deptno").value = values[0];  }else{ document.getElementById("deptno").value =''; }
					if(values[1]!= ""){ document.getElementById("deptno_show").value = values[1];  }else{ document.getElementById("deptno_show").value =''; }					
				 }
			}       
		</script>
		<!-- LOV : CodeAccount -->			
		<script type="text/javascript" language="javascript">
			function openCodeAccount(){
				returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_code_account.php?themes=DefaultBlue','newWin','dialogWidth:800px;dialogHeight:600px;');
				return returnvalue;
			}
			
			function lovCodeAccount1(){
				returnvalue = openCodeAccount(); 
				if (returnvalue != null){ 
					var txtReturn='';
					var values =  returnvalue.split("|-|");
						if(document.getElementById("obj_name22").value =="") txtReturn = values[0];
						else txtReturn = document.getElementById("obj_name22").value+','+values[0];
						document.getElementById("obj_name22").value = txtReturn.substring(0,30);				 
				 }
			}       
			
			function lovCodeAccount2(){
				returnvalue = openCodeAccount(); 
				if (returnvalue != null){ 
					var txtReturn='';
					var values =  returnvalue.split("|-|");
						if(document.getElementById("obj_name32").value =="") txtReturn = values[0];
						else txtReturn = document.getElementById("obj_name32").value+','+values[0];
						document.getElementById("obj_name32").value = txtReturn.substring(0,30);				 
				 }
			}       			
		</script>

</head>
<body topmargin="0" leftmargin="0">
<?php
			if(@$flagAction =='AddCode'){			
				$pr_date=@$_POST["pr_date"];
				$PayCode = @$_POST["PayCode"];
				$pr_payment=@$_POST["pr_payment"];
				$flag_obj=@$_POST["flag_obj"];
				$supplier_id=@$_POST["supplier_id"];			
				$pr_remark=@$_POST["pr_remark"];
				$empno=@$_POST["empno"];
				$mngno=@$_POST["mngno"];
				$deptno=@$_POST["deptno"];
				$Jobno=@$_POST["Jobno"];
				$estimate_day = @$_POST["estimate_day"];
				$obj_name1="";
				$obj_name2="";
				$obj_name3="";
				$vat_include = @$_POST["vat_include"];
				$file_name = $_FILES['pr_path']['name'];  
				
				if($flag_obj=="8"){
					$obj_name1 = @$_POST["obj_name81"];							
				}else{
							if($flag_obj=="1"){
							$obj_name1=@$_POST["obj_name11"];
							$obj_name2=@$_POST["obj_name12"];
							$obj_name3=@$_POST["obj_name13"];
							}else if($flag_obj=="2"){
								$obj_name1=@$_POST["obj_name21"];
								$obj_name2=@$_POST["obj_name22"];
							}else if($flag_obj=="3"){
								$obj_name1=@$_POST["obj_name31"];
								$obj_name2=@$_POST["obj_name32"];
								$obj_name3=@$_POST["obj_name33"];
							}else if($flag_obj=="4"){
								$obj_name1=@$_POST["obj_name41"];
							}else if($flag_obj=="5"){
								$obj_name1=@$_POST["obj_name51"];
							}else{
								$obj_name1=@$_POST["obj_name61"];
							}
				}						
						
				//  Generate Primary key   PR YY xxxxx //
					$str_int_year = "select substr(to_char(sysdate,'YYYY')+543,3,2) int_year from dual";			
					$cur_int_year = @odbc_exec($conn,$str_int_year);
					$int_year = @odbc_result($cur_int_year, "int_year");
				
					$str_mx = "select nvl(max(substr(pr_no,5,5))+1,1) int_mx from pr_master ";
					$str_mx = $str_mx."where substr(pr_no,3,2) = '".$int_year."'";
					$cur_mx = @odbc_exec($conn,$str_mx);
					$int_mx = @odbc_result($cur_mx, "int_mx");
					
					if ($int_mx >= 10000) $str_middle = '';
					else if ($int_mx >= 1000) $str_middle = '0';
					else if ($int_mx >= 100) $str_middle = '00';
					else if ($int_mx >= 10) $str_middle = '000';
					else $str_middle = '0000';
		
					$pr_no = "PR" . $int_year . $str_middle . $int_mx;
				//  End Generate  //
				$filename = "";
				if($_FILES['pr_path']['name']!='') {    
							if(is_uploaded_file($_FILES['pr_path']['tmp_name']))  {    
											$path_file = $PathtoUploadFile."pr_thai";	
											$type_file =substr($file_name,-3,3);
											$filename = $pr_no.".".$type_file;																						
											if(!move_uploaded_file($_FILES['pr_path']['tmp_name'],"$path_file\\$filename")){											
													$filename="";
													echo '<script language="JavaScript" type="text/JavaScript">';
													echo 'alert ("พบปัญหาไม่สามารถ Attach ไฟล์ ใบกรรมวิธีตรวจรับสินค้า ได้ค่ะ");';
													echo '</script>';
											}
							}else{
										$filename="";
										echo '<script language="JavaScript" type="text/JavaScript">';
										echo 'alert ("พบปัญหาไม่สามารถ Attach ไฟล์ ใบกรรมวิธีตรวจรับสินค้า ได้ค่ะ");';
										echo '</script>';
							}
				}
				
				$strINS = "insert into pr_master (
											pr_no,pr_date,deptno,empno,mngno,PayCode,pr_payment,
											flag_obj,obj_name1,obj_name2,obj_name3,
											estimate_day,supplier_id,pr_remark,
											pr_status,vat_include,pr_path,
											rec_user,rec_date,jobno
									) values(
											'$pr_no',to_date('$pr_date','dd-mm-yyyy'),'$deptno','$empno','$mngno','$PayCode','$pr_payment',
											'$flag_obj','$obj_name1','$obj_name2','$obj_name3',
											'$estimate_day','$supplier_id','$pr_remark',
											'1','$vat_include','$filename',
											'$empno_user',sysdate,'$Jobno'
									)";
				$exeINS = odbc_exec($conn,$strINS);
				$exeCOMMIT = odbc_exec($conn,"commit");
				
				$_SESSION["sespk_no"] = $pr_no;			
				echo '<script language="JavaScript" type="text/JavaScript">
								alert ("บันทึกข้อมูลส่วนหัวของ PR เรียบร้อยแล้ว กรุณาใส่รายละเอียดสินค้าที่ต้องการขอซื้อค่ะ");
								parent.main_frame.location.href = "./prmas_edit.php";
						</script>';					
	}			
	
		$cur_emp = odbc_exec($conn, "select  e_name,mngno,deptno from emp where empno='$empno_user'" );
		$empno_show =odbc_result($cur_emp,"e_name");		
					
		if(@$mngno==""){
					if(($roles_user == "MNG")||($roles_user == "MNGGWD")||($roles_user == "MNGShowPO")){
							$mngno =$empno_user;
							$mngno_show=$empno_show;
					}else{
							$mngno =odbc_result($cur_emp,"mngno");						
							if($mngno!="-"){
									$cur_mng = odbc_exec($conn, "select  e_name from emp where empno='$mngno'" );
									$mngno_show=odbc_result($cur_mng,"e_name");		
							}							
					}
		}
		
		if(@$deptno==""){
					$deptno =odbc_result($cur_emp,"deptno");										
					$cur_dept = odbc_exec($conn, "select  deptname from dept where deptno='$deptno'" );
					$deptno_show=odbc_result($cur_dept,"deptname");		
		}
	
?>
<br>
<center>
			<form name="form_pr" action="prmas_add.php" method="post"  enctype="multipart/form-data">
			<input name="flagAction" type="hidden" value="AddCode">					
		<table width="870"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
          <tr>
            <th> &nbsp;&nbsp;เพิ่ม PR <?php if($_SESSION["pr_type"]=="T") echo "Transtek"; else echo "Supreme"; ?></th>
            <th><div align="right">&nbsp;</div></th>
          </tr>
          <tr>
            <td colspan="2">
              <table width="100%">
                <tr>
                  <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="120" class="tdleftwhite"> &nbsp;เลขที่ PR <span class="style_star">*</span> </td>
                        <td><input name="pr_no" type="text"  value="ระบบจะ Generate ให้ หลังจาก Save ค่ะ" size="31"  class="style_readonly" readonly="">						</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;วันที่ขอซื้อ<span class="style_star">*</span> </td>
                        <td>
                          <input name="pr_date" type="text"  class="style_readonly" readonly="" value="<?php echo date("d-m-Y"); ?>" size="8" >						  
                        <script language='javascript'>
											<!-- 
											  if (!document.layers) {
												document.write("<img src=\"../include/images/date_icon.gif\" name=\"butdate\" border=\"0\"   onclick='popUpCalendar(this, form_pr.pr_date, \"dd-mm-yyyy\")'>");
											}
											//-->
							</script>						</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">  &nbsp;เงื่อนไขการชำระเงิน<span class="style_star">*</span></td>
<?php	/*					onChange="var i = document.form_pr.PayCode;
															if(i.value == 'none')
																document.form_pr.pr_payment.value = '';
															else	document.form_pr.pr_payment.value = i.options[i.selectedIndex].text;" */?>
                        <td><select name="PayCode" id="PayCode">
									<?php	
											$strSEL = "select payment_name, payment_description from Payment_method where status = 'Y'";
											$queSEL = @odbc_exec($conn,$strSEL) or die(alert("เกิดข้อผิดพลาด ไม่สามารถประมวลผลข้อมูลในฐานข้อมูลได้ค่ะ"));
									?>	
											<option value="none">กรุณาระบุ Payment Term</option> 
									<?php 
											while(@odbc_fetch_row($queSEL)){
												$PayCode = @odbc_result($queSEL,"payment_name");
									?>	
											<option value="<?=$PayCode;?>"><?= $PayCode; ?></option>
									<?php	
											}	
									?>
									</select>
						<input type="text" name="pr_payment" value="<?=@$pr_payment;?>" size="70" maxlength="120"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;วัตถุประสงค์<span class="style_star"> *</span></td>
                        <td>						
						<?php if($_SESSION["pr_type"]=="T") { ?>
						<input name="flag_obj" type="hidden" value="8">
						<input name="obj_name81" type="text" onKeyUp="return check_string(document.form_pr.obj_name81,100);" value="<?php echo @$obj_name1; ?>" size="100" maxlength="100">
						<?php }else{ ?>
						<input name="flag_obj" type="radio" value="1" onClick="return flag_obj_check(document.form_pr,1)" <?php if((@$flag_obj=="1") || (@$flag_obj==""))echo "checked";  ?> > 
						ขายให้กับลูกค้า (ชื่อ)  <input name="obj_name11" type="text" onKeyUp="return check_string(document.form_pr.obj_name11,100);" value="<?php echo @$obj_name1; ?>"   size="33" maxlength="100">
                        Req No.  <input name="obj_name12" type="text"  onKeyUp="return check_string(document.form_pr.obj_name12,30);" value="<?php echo @$obj_name2; ?>"   size="15" maxlength="30">
                        Job No.<input name="obj_name13" type="text" onKeyUp="return check_string(document.form_pr.obj_name13,30);" value="<?php echo @$obj_name3; ?>"   size="15" maxlength="30">
						&lt;&lt;product&gt;&gt;<br>
						<input name="flag_obj" type="radio" value="2" onClick="return flag_obj_check(document.form_pr,2)" <?php if(@$flag_obj=="2")echo "checked";  ?>> 
						ผลิตสินค้าเพื่อขายในโครงการ (ชื่อ) <input name="obj_name21" type="text" onKeyUp="return check_string(document.form_pr.obj_name21,100);" value="<?php echo @$obj_name1; ?>"   size="40" maxlength="100">
                         รหัสบัญชี <input name="obj_name22" type="text"  onKeyUp="return check_string(document.form_pr.obj_name22,30);" value="<?php echo @$obj_name2; ?>"   size="20" maxlength="30"><input name="acc_but" type="button" value="..." onClick="lovCodeAccount1();">
&nbsp;&lt;&lt;bom&gt;&gt;<br>
						
						<input name="flag_obj" type="radio" value="3" onClick="return flag_obj_check(document.form_pr,3)" <?php if(@$flag_obj=="3")echo "checked";  ?>> 
						จ้างทำอะหลั่ยเพื่อซ่อมเครื่องมือแพทย์ให้กับลูกค้า (ชื่อ)  <input name="obj_name31" type="text" onKeyUp="return check_string(document.form_pr.obj_name31,100);" value="<?php echo @$obj_name1; ?>"   size="15" maxlength="100">
                        รหัสบัญชี  <input name="obj_name32" type="text"  onKeyUp="return check_string(document.form_pr.obj_name32,30);" value="<?php echo @$obj_name2; ?>"   size="8" maxlength="30"><input name="acc_but2" type="button" value="..." onClick="lovCodeAccount2();">
                        Job No.  
                        <input name="obj_name33" type="text" onKeyUp="return check_string(document.form_pr.obj_name33,30);" value="<?php echo @$obj_name3; ?>"   size="10" maxlength="30" >
						  &lt;&lt;service&gt;&gt;<br>
						<input name="flag_obj" type="radio" value="4" onClick="return flag_obj_check(document.form_pr,4)" <?php if(@$flag_obj=="4")echo "checked";  ?>> 
						เพื่อใช้ในงานตกแต่งสถานที่เพื่อการติดตั้งสินค้า (ชื่อ) <input name="obj_name41" type="text" onKeyUp="return check_string(document.form_pr.obj_name41,100);" value="<?php echo @$obj_name1; ?>"   size="60" maxlength="100">
						&nbsp;&lt;&lt;etc&gt;&gt;<br>
						<input name="flag_obj" type="radio" value="5" onClick="return flag_obj_check(document.form_pr,5)" <?php if(@$flag_obj=="5")echo "checked";  ?>>
						 อุปกรณ์/เครื่องมือเครื่องใช้ในแผนก (ระบุ) <input name="obj_name51" type="text" onKeyUp="return check_string(document.form_pr.obj_name51,100);" value="<? echo @$obj_name1; ?>"   size="68" maxlength="100">
						  &nbsp;&lt;&lt;etc&gt;&gt;<br>
						 <input name="flag_obj" type="radio" value="7" onClick="return flag_obj_check(document.form_pr,7)" <?php if(@$flag_obj=="7")echo "checked";  ?>>
SubContact
										&nbsp;&lt;&lt;subcontact&gt;&gt;<br>
						<input name="flag_obj" type="radio" value="6" onClick="return flag_obj_check(document.form_pr,6)" <?php if(@$flag_obj=="6")echo "checked";  ?>> 
						อื่นๆ (ระบุ) <input name="obj_name61" type="text" onKeyUp="return check_string(document.form_pr.obj_name61,100);" value="<?php echo @$obj_name1; ?>"   size="92" maxlength="100" >
						&nbsp;&lt;&lt;etc&gt;&gt;<br>
			<script language="JavaScript" type="text/JavaScript">
			<!--
							flag_obj_check(document.form_pr,1);
			-->
			</script>						
					<?php } ?>
					</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ต้องการใช้ภายใน<span class="style_star">*</span></td>
                        <td>
						 <input name="estimate_day" type="text"  onKeyUp="return check_string(document.form_pr.estimate_day,10);" value="<?php echo @$estimate_day; ?>"   size="10" maxlength="15">
						วัน</td>
                      </tr>
					  <tr>
                        <td class="tdleftwhite">&nbsp;เพื่อ Job No.</td>
                        <td>
						   <input name="Jobno" type="text" onKeyUp="return check_string(document.form_pr.Jobno,9);" value="<?php echo @$Jobno; ?>"   size="20" maxlength="30" >
						 </td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;สถานที่หาซื้อได้</td>
                        <td><input name="supplier_id" type="text" value="<?php echo @$supplier_id; ?>"  size="10" class="style_readonly" readonly="">
                        <input name="supplier_show" type="text" value="<?php echo @$supplier_show; ?>"  size="70" class="style_readonly" readonly=""><input name="supplier_but" type="button" value="..." onClick="lovSupplier();"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;หมายเหตุ</td>
                        <td><textarea name="pr_remark" cols="85" rows="3" onKeyUp="return check_string(document.form_pr.pr_remark,300);"><?php echo @$pr_remark; ?></textarea></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ผู้ขอซื้อ<span class="style_star">*</span></td>
                        <td><input name="empno" type="text" value="<?php echo @$empno_user; ?>"   size="10" maxlength="15" class="style_readonly" readonly="">
						<input name="empno_show" type="text" value="<?php echo @$empno_show; ?>"   size="50" maxlength="15" class="style_readonly" readonly="">
						<?
								if(substr($roles_user,0,3) != 'MNG' ){
									echo "&nbsp;";
								}else{
						?>
						<img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovEmp();">						
						<?
								}
						?>						</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite"> &nbsp;แผนก<span class="style_star">*</span></td>
                        <td><input name="deptno" type="text" value="<?php echo @$deptno; ?>"  size="10" maxlength="20" class="style_readonly" readonly="">
						<input name="deptno_show" type="text" value="<?php echo @$deptno_show; ?>"   size="50" maxlength="15" class="style_readonly" readonly=""><input name="deptno_but" type="button" value="..." onClick="lovDeptUse();">						</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ผู้อนุมัติ<span class="style_star">*</span></td>
                        <td><input name="mngno" type="text" value="<?php echo @$mngno; ?>"   size="10" maxlength="15" class="style_readonly" readonly="">
						<input name="mngno_show" type="text" value="<?php echo @$mngno_show; ?>"   size="50" maxlength="15" class="style_readonly" readonly="">
						<img src="../include/images/emp_icon.gif" width="20" height="19" onClick="lovMng();">						</td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ราคาสินค้าที่บันทึก<span class="style_star">*</span></td>
                        <td><input name="vat_include" type="radio" value="1">
                          รวม VAT แล้ว 
                          <input name="vat_include" type="radio" value="0"> 
                          ยังไม่รวม VAT </td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;ใบกรรมวิธี<br>
                          &nbsp;ตรวจรับสินค้า</td>
                        <td>
						
                        <input name="pr_path" type="file" size="50">
                        <span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>															
						 <?php
/*						 	 switch($ses_deptno){
									case '08'	:
									case '09'	:
									case '11'	:
									case '20'	:
									case '23'	:
									case '17'	:	echo '<input name="file_location" type="file" size="50">';
														break;
									default		:	if(($_SESSION["empno_user"] == ' 05020')||($_SESSION["empno_user"] == '05008')||($_SESSION["empno_user"] == '02019')){									
															echo '<input name="pr_path" type="file" size="50">';
															echo '<span class="style_text">* กรณีเลือกไฟล์เข้าไปเพิ่ม จะบันทึกทับไฟล์เก่า</span>';																	
														}else{
															echo '<font color="#FF0000"><b>&lt;&lt;Upload ไฟล์ไม่ได้ชั่วคราว&gt;&gt;</b></font><br><input name="pr_path" type="file" size="50" readonly>';
														}
														break;
								} 
*/                       ?>          
						
						
		
						</td>
                      </tr>
                  </table></td>
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
                    <td width="100" class="tdcenterwhite">ราคาต่อหน่วย<br>                    </td>
                    <td width="100" class="tdcenterwhite">ราคารวม</td>
                  </tr>
				<tr>
                    <td colspan="7" > <strong> &nbsp;<span class="thai_baht">ราคาที่แสดงเป็นราคาที่หักส่วนลดแล้ว</span></strong></td>
                    <td >&nbsp;</td>
                  </tr>				  
                  <tr>
                    <td colspan="9" class="tdleftblack">
                      <div align="right"><img src="../include/button/add4.gif" name="butadd" width="106" height="24" border="0" ></a> </div></td>
                  </tr>
                </table>
				</td>
				</tr>
                <tr>
                  <td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <th colspan="3"><div align="right">                          
						<a onClick="return check_pr(document.form_pr,'<?=$_SESSION["pr_type"];?>');" style="cursor:hand"
						onMousedown="document.images['butsave'].src=save3.src" 
						onMouseup="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src">						 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
						 						 
						<a onClick="document.form_pr.reset();" style="cursor:hand"
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
			</form>		
</center>
			<script language="JavaScript" type="text/JavaScript">
							document.form_pr.pr_payment.focus();
			</script>
</body>
</html>
<?php
	}else{
		include("../include_RedThemes/SessionTimeOut.php");
	}
?>









