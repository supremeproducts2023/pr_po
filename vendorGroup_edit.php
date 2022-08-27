<?
@session_start();
if(session_is_registered("valid_userprpo")) {
		require_once("../include_RedThemes/odbc_connect.php");
		require_once("../include_RedThemes/MSSQLServer_connect.php");	
		require_once("../include/alert.php");
		$empno_user = $_SESSION["empno_user"];		
		$txtCode=@$_POST["txtCode"];
				if($txtCode!=""){
						$vendor_no= @$_POST["vendor_no"];
						$vendor_id= @$_POST["vendor_id"];
						$vendor_name=@$_POST["vendor_name"];
						$vendor_no_old=@$_POST["vendor_no_old"];
						
						$curQUEVendorSup = odbc_exec($conn,"select nvl(count(vendor_id),0) as count_vendor from vendor_group where vendor_no='$vendor_no'");
						$totalVendorNo = @odbc_result($curQUEVendorSup, "count_vendor");	
						
						if ($totalVendorNo==0 or $vendor_no_old == $vendor_no){				
						$strSupplierUPD =  "update vendor_group set 
														vendor_no = '$vendor_no',
														vendor_name = '$vendor_name',
														lastuser_id = '$empno_user',
														last_date = sysdate
														where vendor_id='$vendor_id'";
						$exeSupplierUPD = odbc_exec($conn,$strSupplierUPD);
						$_SESSION["vendor_no"] = $vendor_no;
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
				
				$v_no= @$_GET["v_no"];
				
				if($v_no == "")
					$vendor_no= $_SESSION["vendor_no"];
				else
					$vendor_no = $v_no;
					
				$strvendorQUE = "select *
												from vendor_group 
												where vendor_no='$vendor_no'";
				$curvendorQUE = @odbc_exec($conn, $strvendorQUE );
				
				$vendor_id =@odbc_result($curvendorQUE,"vendor_id");		
				$vendor_no =@odbc_result($curvendorQUE,"vendor_no");		
				$vendor_name =@odbc_result($curvendorQUE,"vendor_name");		
?>
<html>
	<head>
			<title>*** SUPPLIER ***</title>
			<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
			
			<link href="../include/style1.css" rel="stylesheet" type="text/css">
			<script language='javascript' src='../include/buttonanimate.js'></script>		
			<script language='javascript' src='../include/check_inputtype.js'></script>				
				
			<!-- Check Not null -->
			 <script language='javascript'>
					function check_sup(obj){
							if(obj.vendor_no.value==""){  	
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.vendor_no.focus();
								return false;
							}
							if(obj.vendor_name.value==""){
								alert("กรุณากรอกข้อมูลที่มีเครื่องหมาย * ให้ครบค่ะ");
								obj.vendor_name.focus();
								return false;
							}			
							obj.submit();
				}
			 </script>							
	</head>
	<body topmargin="0" leftmargin="0">
			<br>
			<center>
						<form name="form_sup" action="vendorGroup_edit.php" method="post">
							<table width="600"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
							  <tr>
								<th> &nbsp;&nbsp;แก้ไขข้อมูล vendor GROUP</th>
								<th><div align="right">&nbsp;</div></th>
							  </tr>
							  <tr>
								<td colspan="2">
									  <table width="100%">
										<tr>
										                    <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="148" class="tdleftwhite"> &nbsp;เลขที่ vendor Group <span class="style_star">*</span></td>
                        <td width="438">	<input name="vendor_no" type="text"  size="20"  value="<?=$vendor_no?>" >
                       		 						<input name="vendor_id" type="hidden"   value="<?=$vendor_id?>" >
                                                    <input name="vendor_no_old" type="hidden"   value="<?=$vendor_no?>" >
                        </td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;ชื่อ Vendor Group <span class="style_star">*</span></td>
						<td><input name="vendor_name" type="text" id="vendorno"  size="50" maxlength="150"  value="<?=$vendor_name?>"/></td>
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
	</body>
</html>
<?
	}else{
		include("../include_RedThemes/SessionTimeOut.php");	}
?>








