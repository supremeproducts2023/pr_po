<?php
  	// @session_start();
	  if(isset($_SESSION["valid_userprpo"])) {
			require_once("../include_RedThemes/MSSQLServer_connect_2.php");			
			// require_once("../include_RedThemes/MSSQLServer_connect.php");		
			require_once("../include/alert.php");
			$empno_user = $_SESSION["empno_user"];	
			
			$txtCode= @$_POST["txtCode"];
			
			if($txtCode != ""){
										$vendor_no= @$_POST["vendor_no"];
										$vendor_name=@$_POST["vendor_name"];
                                        
										$curQUEVendorSup = odbc_exec($conn,"select isnull(count(vendor_no),0) as count_vendor from vendor_group where vendor_no='$vendor_no' ");
										$totalVendorNo = @odbc_result($curQUEVendorSup, "count_vendor");	
										
										if ($totalVendorNo==0){
										
										$strSupplierINS = "insert into vendor_group (
																				vendor_id,vendor_no,vendor_name,
																				recuser_id,rec_date,vendor_status
																			) values(
																				(select nvl(max(vendor_id),0)+1 vendor_id from  vendor_group),
																				'$vendor_no','$vendor_name','$empno_user',sysdate,'1'
																			)";
										$exeSupplierINS = @odbc_exec($conn,$strSupplierINS);
										if($exeSupplierINS){
													$_SESSION["vendor_no"] = @$vendor_no;
													$exeCommit = @odbc_exec($conn,"commit");
?>
													<script language="JavaScript" type="text/JavaScript">
															alert ("บันทึกข้อมูลเรียบร้อยแล้วค่ะ");
															location.href('vendorGroup_add.php'+''); 
													</script>						
<?php 										
										}else{
?>
													<script language="JavaScript" type="text/JavaScript">
															alert ("ระบบมีปัญหาไม่สามารถบันทึกข้อมูลได้ค่ะ");
													</script>						
<?php 
										}
										}else{
											?>
													<script language="JavaScript" type="text/JavaScript">
															alert ("กรุณาป้อนรหัส Vendor ใหม่เนื่องจากรหัส Vendor นี้มีในระบบแล้ว");
															location.href('vendorGroup_add.php'+''); 
													</script>				
                                    <?php					
										}
				}else{
?>
<html><head>
		<title>*** Approval Supplier List ***</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
		<link href="../include/style1.css" rel="stylesheet" type="text/css">
		<script language='javascript' src='../include/buttonanimate.js'></script>		
		<script language='javascript' src='../include/check_inputtype.js'></script>		
		<script type="text/javascript" language="javascript">
				function openProvince(type_use){
						returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_province_sale.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:500px;dialogHeight:540px;');											
						return returnvalue;
				}							
				function lovProvinceSale(){
						returnvalue = openProvince(''); 
						if (returnvalue != null){ 
							var values =  returnvalue.split("|-|");
							if(values[0]!= ""){ document.getElementById("province").value = values[0]; }
							if(values[1]!= ""){ document.getElementById("province_id").value = values[1]; }
						 }
				}       
		</script>							
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
				
	    function remote_vendor(a,b){
		window.showModalDialog("./include/checkVendor.php?vendorno="+a+"&supno="+b+"","","width=1,height=1,status=no,titlebar=no,scrollbars=no,top=180,left=230");
        }
		 </script>							
		 
</head>
<body topmargin="0" leftmargin="0">
<br>
<center>
			<form name="form_sup" action="vendorGroup_add.php" method="post">
		<table width="600"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
          <tr>
            <th> &nbsp;&nbsp;เพิ่มข้อมูล vendor Group</th>
          </tr>
          <tr>
            <td colspan="2">
              <table width="100%">
                <tr>
                  <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="148" class="tdleftwhite"> &nbsp;เลขที่ vendor Group <span class="style_star">*</span></td>
                        <td width="438"><input name="vendor_no" type="text"  size="20" ></td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;ชื่อ Vendor Group <span class="style_star">*</span></td>
						<td><input name="vendor_name" type="text" id="vendorno"  size="50" maxlength="150" /></td>
					  </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <th colspan="3"><div align="right">  <input name="txtCode" type="hidden" value="บันทึก">              
						<a  style="cursor:hand" onClick="check_sup(document.form_sup);"
						onMousedown="document.images['butsave'].src=save3.src" 
						onMouseup="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src">						 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0"></a>
						 						 
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
                  </table></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br>
			</form>		
</center>
</body>
</html>
<?php
			}
	}
	else{
			include("../include_RedThemes/SessionTimeOut.php");
	}
?>








