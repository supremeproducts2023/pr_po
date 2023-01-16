<?
  	@session_start();
	if(session_is_registered("valid_userprpo")) {
			require_once("../include_RedThemes/odbc_connect.php");			
			//require_once("../include_RedThemes/MSSQLServer_connect.php");		
			require_once("../include/alert.php");
			$empno_user = $_SESSION["empno_user"];
			
			$txtCode=@$_POST["txtCode"];
			
			if($txtCode != ""){
										$company_name= @$_POST["company_name"];
										$supplier_address1=@$_POST["supplier_address1"];
										$supplier_address2=@$_POST["supplier_address2"];
										$supplier_address3=@$_POST["supplier_address3"];
										$supplier_address3_1=@$_POST["supplier_address3_1"];
										$PayCode=@$_POST["PayCode"];
										$postcode = @$_POST["postcode"];
										$supplier_payment=@$_POST["supplier_payment"];
										$supplier_title = @$_POST["supplier_title"];
										$sup_type = @$_POST["sup_type"];
										$tambol = @$_POST["tambol"];
										$district = @$_POST["district"];
										$province = @$_POST["province"];	
										$province_id = @$_POST["province_id"];									
										$fax_number = @$_POST["fax_number"];
										$status = @$_POST["status"];
										$country = @$_POST["country"];
										$strSupplierMX = "select ISNULL(max(supplier_id)+1,1) int_mx from supplier ";
										$recSupplierMX = @odbc_exec($conn,$strSupplierMX);
										$supplier_id = @odbc_result($recSupplierMX, "int_mx");
										$strSupplierMX = "select ISNULL(max(supplier_id)+1,1) int_mx from supplier  ";
										$recSupplierMX = @odbc_exec($conn,$strSupplierMX);
										$sup_as400id = @odbc_result($recSupplierMX, "int_mx");																							
										$company = @$_POST["company"];	
										$vendorno = @$_POST["vendorno"];
										
                                        $branch_name = @$_POST["branch_name"];	
										$pid = @$_POST["pid"];	
										$branch_type = @$_POST["branch_type"];	
										if($branch_type=="0")
										{
										$branch_name ="�ӹѡ�ҹ�˭�";
										}
										
										$curQUEVendorSup = odbc_exec($conn,"select ISNULL(count(supplier_id),0) as count_vendor from supplier where vendor_no='$vendorno' ");
										$totalVendorNo = @odbc_result($curQUEVendorSup, "count_vendor");	
										
										if ($totalVendorNo==0){
										
										$strSupplierINS = "insert into supplier (
																				supplier_id,company_name,supplier_title,
																				supplier_address1,supplier_address2,supplier_address3,supplier_address3_1,
																				PayCode,supplier_payment,rec_user,rec_date,
																				sup_type,tambol,district,province,province_id,fax_number,postcode,
																				sent_to_SAP,sent_to_DATE,status,country,sup_as400id,company,vendor_no,pid,branch_name,branch_type
																			) values(
																				'$supplier_id','$company_name','$supplier_title',
																				'$supplier_address1','$supplier_address2','$supplier_address3','$supplier_address3_1',
																				'$PayCode','$supplier_payment','$empno_user',getdate(),
																				'$sup_type','$tambol','$district','$province','$province_id','$fax_number','$postcode',
																				'I',null,'$status','$country','$sup_as400id','$company','$vendorno','$pid','$branch_name',$branch_type
																			)";
										
										//echo $strSupplierINS;
										$exeSupplierINS = @odbc_exec($conn,$strSupplierINS);
										if($exeSupplierINS){
													$exeCommit = @odbc_exec($conn,"commit");
													$_SESSION["sespk_no"] = $supplier_id;			
?>
													<script language="JavaScript" type="text/JavaScript">
															alert ("�ѹ�֡���������º�������Ǥ��");
															parent.main_frame.location.href = './supplier_edit.php';
													</script>						
<? 										
										}else{
?>
													<script language="JavaScript" type="text/JavaScript">
															alert ("�к��ջѭ���������ö�ѹ�֡����������");
													</script>						
<? 
										}
										}else{

									echo '<script language="JavaScript" type="text/JavaScript">';
									echo 'alert ("��سһ�͹���� Vendor �������ͧ�ҡ���� Vendor �������к�����");';
									echo '</script>';						
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
				
				//	���������� Requirment �Ţ��� 213
				function openVender(type_use){
						returnvalue = window.showModalDialog( '../include_RedThemes/lov/lov_search_vendor_group.php?themes=DefaultBlue&type_use='+type_use,'newWin','dialogWidth:500px;dialogHeight:540px;');											
						return returnvalue;
				}			
			  // END
			  
				function lovProvinceSale(){
						returnvalue = openProvince(''); 
						if (returnvalue != null){ 
							var values =  returnvalue.split("|-|");
							if(values[0]!= ""){ document.getElementById("province").value = values[0]; }
							if(values[1]!= ""){ document.getElementById("province_id").value = values[1]; }
						 }
				}       
				
				//	���������� Requirment �Ţ��� 213
				function lovVender(){
						returnvalue = openVender(''); 
						if (returnvalue != null){ 
							var values =  returnvalue.split("|-|");
							if(values[0]!= ""){ document.getElementById("vendorname").value = values[0]; }
							if(values[1]!= ""){ document.getElementById("vendorno").value = values[1]; }
						 }
				}       
				// END
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
										alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
										obj.branch_name.focus();
										return false;
									}
								}
							 }
							 if(obj.company_name.value==""){  	
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
								obj.company_name.focus();
								return false;
							}
							  if(obj.pid.value==""){
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
								obj.pid.focus();
								return false;
							}		
							
							if(obj.PayCode.value=="none"){
								alert("��سҡ�͡�����ŷ��������ͧ���� * ���ú���");
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
			 //obj.value = '';
			 obj.disabled = false;
		}
		function disableElement(obj)
		{
			 obj.value = '';
			 obj.disabled = true;
		}
		 function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if(charCode==46||charCode==45)
            return true;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
		 </script>							
		 
</head>
<body topmargin="0" leftmargin="0">
<br>
<center>
			<form name="form_sup" action="supplier_add.php" method="post">
		<table width="600"  border="0" cellpadding="0" cellspacing="0"  bgcolor="E9EAEB">
          <tr>
            <th> &nbsp;&nbsp;���������� Approval Supplier List</th>
          </tr>
          <tr>
            <td colspan="2">
              <table width="100%">
                <tr>
                  <td><table width="100%"  border="1" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="120" class="tdleftwhite"> &nbsp;�Ţ��� Supplier</td>
                        <td><input name="supplier_id" type="text"  value="�к��� Generate �ѵ��ѵ� ��ѧ�ѹ�֡�����Ť��" size="45"  class="style_readonly" readonly="">						</td>
                      </tr>
				    <tr>
					  	<td class="tdleftwhite">&nbsp;���� SAP</td>
						<td><input type="text" name="as400" value="�к��� Generate �ѵ��ѵ� ��ѧ�ѹ�֡�����Ť��" size="45"  class="style_readonly" readonly=""></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;Vendor Group</td>
						<td>
                       <!-- 
                       	��䢵�� Requirment �Ţ��� 213
                       <input name="vendorno" type="text" id="vendorno"  onkeypress="return handleEnter(this, event);" size="25" maxlength="50" onKeyDown="return check_string(document.form_sup.vendorno,14);" />
						<input type="button" name="Button" value="��Ǩ�ͺ����" onClick="remote_vendor(document.form_sup.vendorno.value,'');" /> -->
                        <input name="vendor_name" type="text" id="vendorname" size="45" maxlength="150" readonly="" class="style_readonly"/>
						<input name="vendor" type="button" id="vendor" value="  ...  " onClick="lovVender();" style="cursor:pointer;">
                        <input name="vendorno" type="hidden">
                        <!-- END -->
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;���͡�è�����¹</td>
                        <td><input name="supplier_title" type="text" onKeyUp="return check_string(document.form_sup.supplier_title,20);" value="<? echo @$supplier_title; ?>"  size="15" maxlength="20">
							<input type="button" name="b1" value="����ѷ" onClick="document.form_sup.supplier_title.value='����ѷ';" style="width:35px;"><input type="button" name="b12" value="��ҧ�����ǹ" onClick="document.form_sup.supplier_title.value='��ҧ�����ǹ';" style="width:65px;"><input type="button" name="b122" value="��ҧ�����ǹ�ӡѴ" onClick="document.form_sup.supplier_title.value='��ҧ�����ǹ�ӡѴ';" style="width:85px;"><input type="button" name="b12" value="��ҹ" onClick="document.form_sup.supplier_title.value='��ҹ';" style="width:25px;">
						</td>
                      </tr>
					   <tr>
					  	<td class="tdleftwhite">&nbsp;�ԵԺؤ��/�ؤ�Ÿ�����<span class="style_star">*</span></td>
						<td><select name="company" id="company">
						  <option value="C">C</option>
						  <option value="P">P</option>
						</select>&nbsp;&nbsp;C=�ԵԺؤ��  P=�ؤ�Ÿ�����</td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;ʶҹ��Сͺ���<span class="style_star">*</span></td>
						<td>
						<input name="branch_type" type="radio"  id="branch_type" onClick="disableElement(this.form.branch_name);"value="0" checked /> �ӹѡ�ҹ�˭�
						<input name="branch_type" type="radio" id="branch_type" onClick="enableElement(this.form.branch_name);" value="1"/> �Ң� <input name="branch_name" id="branch_name" type="text" disabled="true"  onKeyUp="return check_string(document.form_sup.branch_name,200);" value="<? echo @$branch_name; ?>" size="50"  maxlength="200">
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;���� Supplier <span class="style_star">*</span> </td>
                        <td><input name="company_name" id="company_name"type="text" onKeyUp="return check_string(document.form_sup.company_name,100);" value="<? echo @$company_name; ?>"  size="70" maxlength="100"></td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;�Ţ��Шӵ�Ǽ����������<span class="style_star">*</span></td>
						<td><input name="pid" id="pid" onkeypress="return isNumberKey(event)" type="text" value="<?=@$pid;?>" size="30" maxlength="13"></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;������ Supplier</td>
						<td>
							<select name="sup_type" style="width:100px;">
								<option value="1">㹻����</option>
								<option value="2">��ҧ�����</option>
							</select>
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;�������</td>
                        <td><input name="supplier_address1" type="text" onKeyUp="return check_string(document.form_sup.supplier_address1,70);" value="<? echo @$supplier_address1; ?>"  size="70" maxlength="70">
                        <br>
                        <input name="supplier_address2" type="text" onKeyUp="return check_string(document.form_sup.supplier_address2,70);" value="<? echo @$supplier_address2; ?>"  size="70" maxlength="70"></td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;�ǧ/�Ӻ�</td>
						<td><input name="tambol" type="text" onKeyUp="return check_string(document.form_sup.tambol,70);" value="<?=@$tambol;?>" size="30" maxlength="70"></td>
					  </tr>
  					  <tr>
					  	<td class="tdleftwhite">&nbsp;ࢵ/�����</td>
						<td><input name="district" type="text" onKeyUp="return check_string(document.form_sup.district,70);" value="<?=@$district;?>" size="30" maxlength="70"></td>
					  </tr>
   					  <tr>
					  	<td class="tdleftwhite">&nbsp;�ѧ��Ѵ</td>
						<td><input name="province" id="province" type="text" onKeyUp="return check_string(document.form_sup.province,100);" value="<?=@$province;?>" size="30" maxlength="100" readonly="" class="style_readonly">&nbsp;<input name="provincebutton" type="button" id="provincebutton" value="  ...  " onClick="lovProvinceSale();" style="cursor:pointer;"><input name="province_id" type="hidden" value=""></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;������ɳ���</td>
						<td><input name="postcode" type="text" value="<?=@$postcode;?>" size="30" maxlength="20"></td>
					  </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;�����</td>
						<td><select name="country">				  				 
						  <?php
						  		$strSQL = "select country, id from cushos_country";
								$strResult = @odbc_exec($conn,$strSQL) or die(alert("�Դ��ͼԴ��Ҵ ������������ö�����żŢ�����㹰ҹ����������"));
								while(@odbc_fetch_row($strResult))
								{
									$country = @odbc_result($strResult,"country");
									$id = @odbc_result($strResult,"id");
						  ?>
						   <option value="<?=$id;?>" <?php if($id == "TH") echo "selected";?>><?=$country." (".$id.")";?></option>
						  <?php										
								}
						  ?>
						</select></td>
					  </tr>					  
                      <tr>
                        <td class="tdleftwhite">&nbsp;�������Ѿ�� 1</td>
                        <td><input name="supplier_address3" type="text" onKeyUp="return check_string(document.form_sup.supplier_address3,20);" value="<? echo @$supplier_address3; ?>"  size="30" maxlength="20"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;�������Ѿ�� 2 </td>
                        <td><input name="supplier_address3_1" type="text" onKeyUp="return check_string(document.form_sup.supplier_address3_1,20);" value="<? echo @$supplier_address3_1; ?>"  size="30" maxlength="20"></td>
                      </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;����ῡ��</td>
                        <td><input name="fax_number" type="text" onKeyUp="return check_string(document.form_sup.fax_number,20);" value="<?=@$fax_number; ?>"  size="30" maxlength="20"></td>
                      </tr>
					  <tr>
					  	<td class="tdleftwhite">&nbsp;ʶҹ�</td>
						<td><select name="status">
						  <option value="1">��ҹ</option>
						  <option value="0">�����ҹ</option>
						</select></td>
					  </tr>
					 
					  <tr>
					  	<td class="tdleftwhite">&nbsp;Payment Term <span class="style_star">*</span></td>
						<!-- onChange="var i = document.form_sup.PayCode;
													if(i.value == 'none')
														document.form_sup.supplier_payment.value = '';
													else	document.form_sup.supplier_payment.value = i.options[i.selectedIndex].text;" -->
						<td>
							<select name="PayCode" id="PayCode" >
							<?php	
									$strSEL = "select payment_name, payment_description from Payment_method where status = 'Y'";
									$queSEL = @odbc_exec($conn,$strSEL) or die(alert("�Դ��ͼԴ��Ҵ �������ö�����żŢ�����㹰ҹ����������"));
							?>	
									<option value="none">��س��к� Payment Term</option> 
							<?php 
									while(@odbc_fetch_row($queSEL)){
										$PayCode = @odbc_result($queSEL,"payment_name");
							?>	
									<option value="<?=$PayCode;?>" ><?= $PayCode; ?></option>
							<?php	
									}	
							?>
							</select>
						</td>
					  </tr>
                      <tr>
                        <td class="tdleftwhite">&nbsp;���͹䢡�ê����Թ</td>
                        <td><input name="supplier_payment" type="text" onKeyUp="return check_string(document.form_sup.supplier_payment,120);" value="<? echo @$supplier_payment; ?>"  size="70" maxlength="120"></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <th colspan="3"><div align="right">  <input name="txtCode" type="hidden" value="�ѹ�֡">              
						<a onClick="return check_sup(document.form_sup);" style="cursor:hand"
						onMousedown="document.images['butsave'].src=save3.src" 
						onMouseup="document.images['butsave'].src=save1.src"						
						 onMouseOver="document.images['butsave'].src=save2.src" 
						 onMouseOut="document.images['butsave'].src=save1.src">						 
						 <img src="../include/button/save1.gif" name="butsave" width="106" height="24" border="0" ></a>
						 						 
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
			<script language="JavaScript" type="text/JavaScript">
							document.form_sup.company_name.focus();
			</script>
</body>
</html>
<?
			}
	}
	else{
			include("../include_RedThemes/SessionTimeOut.php");
	}
?>








